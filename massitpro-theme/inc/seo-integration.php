<?php
/**
 * SEO integration: inject Mass IT Pro custom field content into Rank Math analysis.
 *
 * @package MassITPro
 */

if (! defined('ABSPATH')) {
	exit;
}

add_filter('rank_math/researched/post_content', 'massitpro_inject_meta_content_for_rankmath', 10, 2);

function massitpro_inject_meta_content_for_rankmath($content, $post) {
	if (! $post || ! isset($post->ID)) {
		return $content;
	}

	$post_id = (int) $post->ID;
	$text_parts = [];

	// Get all post meta for this post
	$all_meta = get_post_meta($post_id);

	if (! is_array($all_meta)) {
		return $content;
	}

	foreach ($all_meta as $key => $values) {
		// Only process massitpro_ prefixed meta keys
		if (strpos($key, 'massitpro_') !== 0) {
			continue;
		}

		foreach ($values as $raw) {
			// Handle both serialized arrays and plain strings
			if (is_serialized($raw)) {
				$decoded = maybe_unserialize($raw);
			} elseif (is_string($raw) && strpos($raw, '{') === 0) {
				$decoded = json_decode($raw, true);
			} else {
				$decoded = $raw;
			}

			massitpro_extract_text_recursive($decoded, $text_parts);
		}
	}

	if (empty($text_parts)) {
		return $content;
	}

	// Append extracted text to existing content
	$injected = implode(' ', array_filter(array_map('trim', $text_parts)));

	return $content . ' ' . wp_strip_all_tags($injected);
}

/**
 * Recursively extract all string values from nested arrays.
 * Skips image IDs, URLs, icon slugs, link_url fields, and
 * any value that looks like a number only or a URL.
 */
function massitpro_extract_text_recursive($data, &$parts) {
	if (is_string($data)) {
		$trimmed = trim($data);

		// Skip empty, pure numbers, URLs, icon slugs (short single words)
		if (
			empty($trimmed) ||
			is_numeric($trimmed) ||
			filter_var($trimmed, FILTER_VALIDATE_URL) ||
			(strlen($trimmed) < 4 && ! preg_match('/\s/', $trimmed))
		) {
			return;
		}

		$parts[] = $trimmed;
		return;
	}

	if (is_array($data)) {
		// Skip keys that are image IDs, URLs, or icon references
		$skip_keys = ['image', 'link_url', 'icon', 'url', 'href', 'id'];

		foreach ($data as $key => $value) {
			if (in_array($key, $skip_keys, true)) {
				continue;
			}
			massitpro_extract_text_recursive($value, $parts);
		}
	}
}
