<?php
/**
 * Native meta access and content normalization helpers.
 *
 * @package MassITPro
 */

if (! defined('ABSPATH')) {
	exit;
}

/**
 * Build a native meta key for a section.
 *
 * @param string $section Section slug.
 * @return string
 */
function massitpro_get_section_meta_key($section) {
	return 'massitpro_' . sanitize_key($section);
}

/**
 * Get a decoded native section payload.
 *
 * @param string $section Section slug.
 * @param int    $post_id Post ID.
 * @param mixed  $default Default value.
 * @return mixed
 */
function massitpro_get_section_meta($section, $post_id = 0, $default = []) {
	$post_id = $post_id ?: get_the_ID();

	if (! $post_id) {
		return $default;
	}

	$value = get_post_meta($post_id, massitpro_get_section_meta_key($section), true);

	if ('' === $value || null === $value) {
		return $default;
	}

	if (is_array($value)) {
		return $value;
	}

	if (is_string($value)) {
		$decoded = json_decode($value, true);

		if (JSON_ERROR_NONE === json_last_error()) {
			return is_array($decoded) ? $decoded : $default;
		}
	}

	return $default;
}

/**
 * Get a native scalar meta value.
 *
 * @param string $key     Meta key.
 * @param int    $post_id Post ID.
 * @param mixed  $default Default value.
 * @return mixed
 */
function massitpro_get_native_meta($key, $post_id = 0, $default = null) {
	$post_id = $post_id ?: get_the_ID();

	if (! $post_id) {
		return $default;
	}

	$value = get_post_meta($post_id, 'massitpro_' . sanitize_key($key), true);

	return '' === $value || null === $value ? $default : $value;
}

/**
 * Determine whether a value should be treated as empty.
 *
 * @param mixed $value Value to inspect.
 * @return bool
 */
function massitpro_is_empty_value($value) {
	if (is_array($value)) {
		foreach ($value as $item) {
			if (! massitpro_is_empty_value($item)) {
				return false;
			}
		}

		return true;
	}

	if ($value instanceof WP_Post) {
		return false;
	}

	if (is_string($value)) {
		return '' === trim(wp_strip_all_tags($value));
	}

	return empty($value);
}

/**
 * Normalize a link-like value.
 *
 * @param mixed $link Link value.
 * @return array<string,string>
 */
function massitpro_normalize_link($link) {
	if (is_array($link)) {
		return [
			'label'  => trim((string) ($link['title'] ?? ($link['label'] ?? ''))),
			'url'    => trim((string) ($link['url'] ?? '')),
			'target' => trim((string) ($link['target'] ?? '')),
		];
	}

	if (is_string($link) && '' !== trim($link)) {
		return [
			'label'  => '',
			'url'    => trim($link),
			'target' => '',
		];
	}

	return [
		'label'  => '',
		'url'    => '',
		'target' => '',
	];
}

/**
 * Normalize a buttons array.
 *
 * @param array<int,array<string,mixed>>|mixed $rows Button rows.
 * @return array<int,array<string,string>>
 */
function massitpro_normalize_button_rows($rows) {
	$buttons = [];

	foreach ((array) $rows as $row) {
		if (! is_array($row)) {
			continue;
		}

		if (! empty($row['label']) && ! empty($row['url'])) {
			$buttons[] = [
				'label'   => trim((string) $row['label']),
				'url'     => trim((string) $row['url']),
				'target'  => trim((string) ($row['target'] ?? '')),
				'variant' => sanitize_key((string) ($row['variant'] ?? 'default')),
				'size'    => sanitize_key((string) ($row['size'] ?? 'default')),
			];
			continue;
		}

		$link = massitpro_normalize_link($row['link'] ?? []);

		if (! $link['label'] || ! $link['url']) {
			continue;
		}

		$buttons[] = [
			'label'   => $link['label'],
			'url'     => $link['url'],
			'target'  => $link['target'],
			'variant' => sanitize_key((string) ($row['style'] ?? ($row['variant'] ?? 'default'))),
			'size'    => sanitize_key((string) ($row['size'] ?? 'default')),
		];
	}

	return $buttons;
}

/**
 * Normalize IDs or posts into posts.
 *
 * @param mixed       $items     Post collection.
 * @param string|null $post_type Optional post type filter.
 * @return array<int,WP_Post>
 */
function massitpro_normalize_related_posts($items, $post_type = null) {
	$posts = [];

	foreach ((array) $items as $item) {
		if ($item instanceof WP_Post) {
			if ($post_type && $post_type !== $item->post_type) {
				continue;
			}

			$posts[] = $item;
			continue;
		}

		if (! $item) {
			continue;
		}

		$post = get_post((int) $item);

		if (! $post instanceof WP_Post) {
			continue;
		}

		if ($post_type && $post_type !== $post->post_type) {
			continue;
		}

		$posts[] = $post;
	}

	return $posts;
}

/**
 * Resolve rich text output.
 *
 * @param string $content Rich text content.
 * @return string
 */
function massitpro_format_rich_text($content) {
	$content = trim((string) $content);

	if ('' === $content) {
		return '';
	}

	return wp_kses_post(wpautop($content));
}

/**
 * Resolve page body content.
 *
 * @param int $post_id Post ID.
 * @return string
 */
function massitpro_get_page_body_content($post_id = 0) {
	$post_id = $post_id ?: get_the_ID();
	$post    = get_post($post_id);

	if (! $post instanceof WP_Post) {
		return '';
	}

	return trim((string) apply_filters('the_content', $post->post_content));
}

/**
 * Resolve an image value, optionally falling back to the featured image.
 *
 * @param mixed $image            Image value.
 * @param int   $fallback_post_id Fallback post ID.
 * @return mixed
 */
function massitpro_resolve_image_value($image, $fallback_post_id = 0) {
	if (! massitpro_is_empty_value($image)) {
		return $image;
	}

	$fallback_post_id = (int) $fallback_post_id;

	if ($fallback_post_id && has_post_thumbnail($fallback_post_id)) {
		return get_post_thumbnail_id($fallback_post_id);
	}

	return null;
}

/**
 * Resolve a featured image or override for a post.
 *
 * @param int|WP_Post  $post         Post object or ID.
 * @param string|null  $override_key Optional native meta key suffix.
 * @return mixed
 */
function massitpro_get_post_display_image($post, $override_key = null) {
	$post = get_post($post);

	if (! $post instanceof WP_Post) {
		return null;
	}

	if ($override_key) {
		$image = massitpro_get_native_meta($override_key, $post->ID);

		if (! massitpro_is_empty_value($image)) {
			return $image;
		}
	}

	if (has_post_thumbnail($post)) {
		return get_post_thumbnail_id($post);
	}

	return null;
}

/**
 * Query posts helper.
 *
 * @param array<string,mixed> $args Query args.
 * @return array<int,WP_Post>
 */
function massitpro_query_posts($args) {
	$query = new WP_Query($args);

	return $query->posts;
}

/**
 * Get structured testimonial data from a CPT post.
 *
 * @param int|WP_Post $post Post object or ID.
 * @return array<string,mixed>
 */
function massitpro_get_testimonial_data($post) {
	$post = get_post($post);

	if (! $post instanceof WP_Post) {
		return [
			'quote'    => '',
			'name'     => '',
			'role'     => '',
			'company'  => '',
			'industry' => '',
			'image'    => null,
		];
	}

	$terms         = get_the_terms($post->ID, 'testimonial_industry');
	$industry      = '';
	$industry_slug = '';

	if (! is_wp_error($terms) && ! empty($terms)) {
		$industry      = (string) $terms[0]->name;
		$industry_slug = (string) $terms[0]->slug;
	}

	return [
		'quote'         => trim(wp_strip_all_tags((string) $post->post_content)),
		'name'          => get_the_title($post),
		'role'          => (string) get_post_meta($post->ID, '_testimonial_role', true),
		'company'       => (string) get_post_meta($post->ID, '_testimonial_company', true),
		'industry'      => $industry,
		'industry_slug' => $industry_slug,
		'image'         => has_post_thumbnail($post->ID) ? get_post_thumbnail_id($post->ID) : null,
	];
}

/**
 * Get structured project data from a CPT post.
 *
 * @param int|WP_Post $post Post object or ID.
 * @return array<string,mixed>
 */
function massitpro_get_project_data($post) {
	$post = get_post($post);

	if (! $post instanceof WP_Post) {
		return [
			'title'          => '',
			'subtitle'       => '',
			'category'       => '',
			'desc'           => '',
			'image'          => null,
			'link'           => '',
			'client_name'    => '',
			'industry_label' => '',
			'challenge'      => '',
			'solution'       => '',
			'results'        => [],
		];
	}

	$terms    = get_the_terms($post->ID, 'project_category');
	$category = '';

	if (! is_wp_error($terms) && ! empty($terms)) {
		$category = (string) $terms[0]->name;
	}

	$excerpt = trim(wp_strip_all_tags((string) $post->post_excerpt));

	if (! $excerpt) {
		$content = trim(wp_strip_all_tags((string) $post->post_content));
		$excerpt = $content ? wp_trim_words($content, 20, '...') : '';
	}

	$results_raw = trim((string) get_post_meta($post->ID, '_project_results', true));
	$results     = [];

	if ($results_raw) {
		foreach (preg_split('/\r\n|\r|\n/', $results_raw) as $line) {
			$line = trim($line);

			if ('' !== $line) {
				$results[] = $line;
			}
		}
	}

	return [
		'title'          => get_the_title($post),
		'subtitle'       => (string) get_post_meta($post->ID, '_project_subtitle', true),
		'category'       => $category,
		'desc'           => $excerpt,
		'image'          => has_post_thumbnail($post->ID) ? get_post_thumbnail_id($post->ID) : null,
		'link'           => get_permalink($post),
		'client_name'    => (string) get_post_meta($post->ID, '_project_client_name', true),
		'industry_label' => (string) get_post_meta($post->ID, '_project_industry_label', true),
		'challenge'      => (string) get_post_meta($post->ID, '_project_challenge', true),
		'solution'       => (string) get_post_meta($post->ID, '_project_solution', true),
		'results'        => $results,
	];
}

/**
 * Query published posts in random order.
 *
 * @param string $post_type Post type slug.
 * @param int    $count     Maximum number of posts.
 * @return array<int,WP_Post>
 */
function massitpro_query_random_posts($post_type, $count) {
	$query = new WP_Query(
		[
			'post_type'      => sanitize_key((string) $post_type),
			'post_status'    => 'publish',
			'posts_per_page' => max(1, (int) $count),
			'orderby'        => 'rand',
			'ignore_sticky_posts' => true,
		]
	);

	return $query->posts;
}
