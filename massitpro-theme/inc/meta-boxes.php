<?php
/**
 * Native page meta boxes and meta registration.
 *
 * @package MassITPro
 */

if (! defined('ABSPATH')) {
	exit;
}

/**
 * Native section registry for the current implementation pass.
 *
 * @return array<string,array<string,mixed>>
 */
function massitpro_get_native_section_registry() {
	return [
		'front-page'      => [
			'title'    => __('Mass IT Pro Homepage Fields', 'massitpro'),
			'sections' => [
				'hero'                       => ['label' => __('Hero', 'massitpro'), 'type' => 'hero'],
				'trust_strip'                => ['label' => __('Trusted Companies', 'massitpro'), 'type' => 'trust_strip', 'rows' => 14],
				'stats_section'              => ['label' => __('Why Trust Us', 'massitpro'), 'type' => 'stats', 'rows' => 4],
				'services_carousel_section'  => ['label' => __('Business Services', 'massitpro'), 'type' => 'cards', 'rows' => 6, 'fields' => ['title', 'body', 'link_label', 'link_url'], 'has_eyebrow' => true],
				'secondary_services_section' => ['label' => __('Other Services', 'massitpro'), 'type' => 'cards', 'rows' => 2, 'fields' => ['icon', 'title', 'body', 'description', 'image', 'link_label', 'link_url'], 'has_eyebrow' => true],
				'industries_section'         => ['label' => __('Industry Solutions', 'massitpro'), 'type' => 'cards', 'rows' => 6, 'fields' => ['icon', 'title', 'body', 'link_url'], 'has_eyebrow' => true],
				'locations_section'          => ['label' => __('Service Coverage Areas', 'massitpro'), 'type' => 'cards', 'rows' => 11, 'fields' => ['title', 'link_url'], 'has_eyebrow' => true],
				'blog_section'               => ['label' => __('Blog', 'massitpro'), 'type' => 'blog'],
				'cta_block'                  => ['label' => __('CTA Block', 'massitpro'), 'type' => 'cta'],
			],
		],
		'service-detail'  => [
			'title'    => __('Mass IT Pro Service Detail Fields', 'massitpro'),
			'sections' => [
				'hero'                       => ['label' => __('Hero', 'massitpro'), 'type' => 'hero'],
				'capabilities_section'       => ['label' => __('Capabilities', 'massitpro'), 'type' => 'cards', 'rows' => 4, 'fields' => ['image', 'title', 'body', 'link_url'], 'has_eyebrow' => true],
				'deliverables_section'       => ['label' => __('Deliverables', 'massitpro'), 'type' => 'deliverables_pills'],
				'process_section'            => ['label' => __('Process Steps', 'massitpro'), 'type' => 'process', 'rows' => 4],
				'related_services_section'   => ['label' => __('Ideal For', 'massitpro'), 'type' => 'related_links', 'rows' => 6],
				'faq_section'                => ['label' => __('FAQs', 'massitpro'), 'type' => 'cards', 'rows' => 6, 'fields' => ['question', 'answer']],
				'cta_block'                  => ['label' => __('CTA Block', 'massitpro'), 'type' => 'cta'],
			],
		],
		'industry-detail' => [
			'title'    => __('Mass IT Pro Industry Detail Fields', 'massitpro'),
			'sections' => [
				'hero'                        => ['label' => __('Hero', 'massitpro'), 'type' => 'hero'],
				'overview_section'            => ['label' => __('Overview', 'massitpro'), 'type' => 'intro'],
				'pain_points_section'         => ['label' => __('Pain Points', 'massitpro'), 'type' => 'cards', 'rows' => 6, 'fields' => ['title', 'body', 'image']],
				'recommended_services_section'=> ['label' => __('Recommended Services', 'massitpro'), 'type' => 'cards', 'rows' => 6, 'fields' => ['icon', 'title', 'body', 'link_label', 'link_url']],
				'sub_clusters_section'        => ['label' => __('Sub-Clusters', 'massitpro'), 'type' => 'cards', 'rows' => 6, 'fields' => ['title', 'body', 'image']],
				'compliance_section'          => ['label' => __('Compliance', 'massitpro'), 'type' => 'cards', 'rows' => 6, 'fields' => ['icon', 'title', 'body']],
				'featured_project_section'    => ['label' => __('Featured Project', 'massitpro'), 'type' => 'featured_project'],
				'faq_section'                 => ['label' => __('FAQs', 'massitpro'), 'type' => 'cards', 'rows' => 6, 'fields' => ['question', 'answer']],
				'related_links_section'       => ['label' => __('Related Links', 'massitpro'), 'type' => 'related_links', 'rows' => 6],
				'cta_block'                   => ['label' => __('CTA Block', 'massitpro'), 'type' => 'cta'],
			],
		],
		'location-detail' => [
			'title'    => __('Mass IT Pro Location Detail Fields', 'massitpro'),
			'sections' => [
				'hero'                     => ['label' => __('Hero', 'massitpro'), 'type' => 'hero'],
				'overview_section'         => ['label' => __('Overview', 'massitpro'), 'type' => 'location_intro'],
				'why_local_section'        => ['label' => __('Why Local', 'massitpro'), 'type' => 'cards', 'rows' => 3, 'fields' => ['icon', 'title', 'body']],
				'available_services_section' => ['label' => __('Available Services', 'massitpro'), 'type' => 'cards', 'rows' => 6, 'fields' => ['title', 'body', 'link_label', 'link_url'], 'has_eyebrow' => true],
				'served_industries_section'  => ['label' => __('Served Industries', 'massitpro'), 'type' => 'cards', 'rows' => 6, 'fields' => ['title', 'body', 'link_url']],
				'faq_section'              => ['label' => __('FAQs', 'massitpro'), 'type' => 'cards', 'rows' => 6, 'fields' => ['question', 'answer']],
				'related_links_section'    => ['label' => __('Related Links', 'massitpro'), 'type' => 'related_links', 'rows' => 6],
				'cta_block'                => ['label' => __('CTA Block', 'massitpro'), 'type' => 'cta'],
			],
		],
		'services-hub'    => [
			'title'    => __('Mass IT Pro Services Hub Fields', 'massitpro'),
			'sections' => [
				'hero'                         => ['label' => __('Hero', 'massitpro'), 'type' => 'hero'],
				'business_services_section'    => ['label' => __('Business Services', 'massitpro'), 'type' => 'cards', 'rows' => 9, 'fields' => ['icon', 'title', 'body', 'highlights', 'link_label', 'link_url'], 'has_eyebrow' => true],
				'residential_services_section' => ['label' => __('Residential Services', 'massitpro'), 'type' => 'cards', 'rows' => 7, 'fields' => ['icon', 'title', 'body', 'highlights', 'link_label', 'link_url'], 'hide_section_intro' => true],
				'why_trust_section'            => ['label' => __('Why Trust Us', 'massitpro'), 'type' => 'stats', 'rows' => 4],
				'served_industries_section'    => ['label' => __('Industry Solutions', 'massitpro'), 'type' => 'cards', 'rows' => 6, 'fields' => ['title', 'body', 'link_url']],
				'related_links_section'        => ['label' => __('Related Links', 'massitpro'), 'type' => 'related_links', 'rows' => 6],
				'process_section'              => ['label' => __('Process Steps', 'massitpro'), 'type' => 'process', 'rows' => 4],
				'cta_block'                    => ['label' => __('CTA Block', 'massitpro'), 'type' => 'cta'],
			],
		],
		'services-business' => [
			'title'    => __('Mass IT Pro Business Landing Fields', 'massitpro'),
			'sections' => [
				'hero'             => ['label' => __('Hero', 'massitpro'), 'type' => 'hero'],
				'intro_section'    => ['label' => __('Intro', 'massitpro'), 'type' => 'intro'],
				'services_section' => ['label' => __('Services', 'massitpro'), 'type' => 'cards', 'rows' => 6, 'fields' => ['icon', 'title', 'body', 'link_label', 'link_url']],
				'benefits_section' => ['label' => __('Benefits', 'massitpro'), 'type' => 'cards', 'rows' => 6, 'fields' => ['icon', 'title', 'body']],
				'process_section'  => ['label' => __('Process Steps', 'massitpro'), 'type' => 'process', 'rows' => 4],
				'faq_section'      => ['label' => __('FAQs', 'massitpro'), 'type' => 'cards', 'rows' => 6, 'fields' => ['question', 'answer']],
				'cta_block'        => ['label' => __('CTA Block', 'massitpro'), 'type' => 'cta'],
			],
		],
		'services-residential' => [
			'title'    => __('Mass IT Pro Residential Landing Fields', 'massitpro'),
			'sections' => [
				'hero'             => ['label' => __('Hero', 'massitpro'), 'type' => 'hero'],
				'intro_section'    => ['label' => __('Intro', 'massitpro'), 'type' => 'intro'],
				'services_section' => ['label' => __('Services', 'massitpro'), 'type' => 'cards', 'rows' => 6, 'fields' => ['icon', 'title', 'body', 'link_label', 'link_url']],
				'benefits_section' => ['label' => __('Benefits', 'massitpro'), 'type' => 'cards', 'rows' => 6, 'fields' => ['icon', 'title', 'body']],
				'process_section'  => ['label' => __('Process Steps', 'massitpro'), 'type' => 'process', 'rows' => 4],
				'faq_section'      => ['label' => __('FAQs', 'massitpro'), 'type' => 'cards', 'rows' => 6, 'fields' => ['question', 'answer']],
				'cta_block'        => ['label' => __('CTA Block', 'massitpro'), 'type' => 'cta'],
			],
		],
		'industries-hub' => [
			'title'    => __('Mass IT Pro Industries Hub Fields', 'massitpro'),
			'sections' => [
				'hero'                => ['label' => __('Hero', 'massitpro'), 'type' => 'hero'],
				'services_section'    => ['label' => __('Business Services', 'massitpro'), 'type' => 'cards', 'rows' => 9, 'fields' => ['image', 'title', 'body', 'link_label', 'link_url'], 'has_eyebrow' => true],
				'why_trust_section'   => ['label' => __('Why Trust Us', 'massitpro'), 'type' => 'stats', 'rows' => 4],
				'locations_section'   => ['label' => __('Locations', 'massitpro'), 'type' => 'cards', 'rows' => 6, 'fields' => ['title', 'body', 'link_url']],
				'industries_section'  => ['label' => __('Industries', 'massitpro'), 'type' => 'related_links', 'rows' => 6],
				'process_section'     => ['label' => __('Process Steps', 'massitpro'), 'type' => 'process', 'rows' => 4],
				'cta_block'           => ['label' => __('CTA Block', 'massitpro'), 'type' => 'cta'],
			],
		],
		'locations-hub' => [
			'title'    => __('Mass IT Pro Locations Hub Fields', 'massitpro'),
			'sections' => [
				'hero'                       => ['label' => __('Hero', 'massitpro'), 'type' => 'hero'],
				'intro_section'              => ['label' => __('Intro', 'massitpro'), 'type' => 'location_hub_intro'],
				'featured_locations_section' => ['label' => __('Featured Locations', 'massitpro'), 'type' => 'cards', 'rows' => 11, 'fields' => ['title', 'body', 'image', 'link_url'], 'has_eyebrow' => true],
				'service_highlights_section' => ['label' => __('Service Highlights', 'massitpro'), 'type' => 'cards', 'rows' => 6, 'fields' => ['title', 'body', 'link_label', 'link_url'], 'has_eyebrow' => true],
				'related_industry_section'   => ['label' => __('Related Industry', 'massitpro'), 'type' => 'related_links', 'rows' => 6],
				'cta_block'                  => ['label' => __('CTA Block', 'massitpro'), 'type' => 'cta'],
			],
		],
		'about' => [
			'title'    => __('Mass IT Pro About Page Fields', 'massitpro'),
			'sections' => [
				'hero'                     => ['label' => __('Hero', 'massitpro'), 'type' => 'hero'],
				'mission_section'          => ['label' => __('Mission', 'massitpro'), 'type' => 'about_mission'],
				'stats_section'            => ['label' => __('Stats', 'massitpro'), 'type' => 'stats', 'rows' => 4],
				'value_cards_section'      => ['label' => __('Values', 'massitpro'), 'type' => 'cards', 'rows' => 3, 'fields' => ['icon', 'title', 'body']],
				'process_section'          => ['label' => __('Process Steps', 'massitpro'), 'type' => 'about_process', 'rows' => 4],
				'team_section'             => ['label' => __('Team', 'massitpro'), 'type' => 'about_team'],
				'certifications_section'   => ['label' => __('Certifications', 'massitpro'), 'type' => 'about_certifications'],
				'service_coverage_section' => ['label' => __('Service Coverage Areas', 'massitpro'), 'type' => 'cards', 'rows' => 11, 'fields' => ['title', 'link_url'], 'has_eyebrow' => true],
				'cta_block'                => ['label' => __('CTA Block', 'massitpro'), 'type' => 'cta'],
			],
		],
		'testimonials' => [
			'title'    => __('Mass IT Pro Testimonials Page Fields', 'massitpro'),
			'sections' => [
				'hero'                              => ['label' => __('Hero', 'massitpro'), 'type' => 'hero'],
				'testimonials_featured_review_section' => ['label' => __('Featured Review Image', 'massitpro'), 'type' => 'featured_review_image'],
				'testimonials_section'              => ['label' => __('All Reviews Heading', 'massitpro'), 'type' => 'section_heading'],
				'stats_section'                     => ['label' => __('Stats', 'massitpro'), 'type' => 'stats', 'rows' => 4],
				'testimonials_location_section'     => ['label' => __('Location Coverage', 'massitpro'), 'type' => 'related_links'],
				'cta_block'                         => ['label' => __('CTA Block', 'massitpro'), 'type' => 'cta'],
			],
		],
		'contact' => [
			'title'    => __('Mass IT Pro Contact Page Fields', 'massitpro'),
			'sections' => [
				'hero'                     => ['label' => __('Hero', 'massitpro'), 'type' => 'hero'],
				'contact_form_section'     => ['label' => __('Contact Form & Info Cards', 'massitpro'), 'type' => 'contact_form'],
				'contact_process_section'  => ['label' => __('Process Steps', 'massitpro'), 'type' => 'process', 'rows' => 4, 'has_eyebrow' => true],
				'contact_industries_section' => [
					'label'       => __('Industries', 'massitpro'),
					'type'        => 'cards',
					'rows'        => 6,
					'fields'      => ['title', 'body', 'link_url'],
					'has_eyebrow' => true,
				],
				'contact_location_section' => ['label' => __('Location', 'massitpro'), 'type' => 'contact_location'],
				'cta_block'                => ['label' => __('CTA Block', 'massitpro'), 'type' => 'cta'],
			],
		],
		'faq' => [
			'title'    => __('Mass IT Pro FAQ Page Fields', 'massitpro'),
			'sections' => [
				'hero' => ['label' => __('Hero', 'massitpro'), 'type' => 'hero'],

				'quick_answers_section' => [
					'label'       => __('Quick Answers', 'massitpro'),
					'type'        => 'cards',
					'rows'        => 4,
					'fields'      => ['question', 'answer'],
					'has_eyebrow' => true,
					'has_image'   => true,
				],

				'faq_accordion_section' => [
					'label'  => __('FAQ Accordion', 'massitpro'),
					'type'   => 'cards',
					'rows'   => 30,
					'fields' => ['category', 'question', 'answer'],
				],

				'still_have_questions_section' => [
					'label'              => __('Still Have Questions', 'massitpro'),
					'type'               => 'cards',
					'rows'               => 2,
					'fields'             => ['title', 'body', 'link_label', 'link_url'],
					'hide_section_intro' => true,
				],

				'faq_related_resources_section' => [
					'label' => __('Related Resources', 'massitpro'),
					'type'  => 'blog',
				],

				'faq_location_section' => [
					'label' => __('Location', 'massitpro'),
					'type'  => 'related_links',
				],

				'cta_block' => ['label' => __('CTA Block', 'massitpro'), 'type' => 'cta'],
			],
		],
		'blog' => [
			'title'    => __('Mass IT Pro Blog Page Fields', 'massitpro'),
			'sections' => [
				'hero'                    => ['label' => __('Hero', 'massitpro'), 'type' => 'hero'],
				'featured_article_section'=> ['label' => __('Featured Article', 'massitpro'), 'type' => 'blog'],
				'topics_section'          => [
					'label'       => __('Browse by Topic', 'massitpro'),
					'type'        => 'cards',
					'rows'        => 6,
					'fields'      => ['title', 'body', 'link_label', 'link_url'],
					'has_eyebrow' => true,
				],
				'newsletter_section'      => ['label' => __('Newsletter CTA', 'massitpro'), 'type' => 'spotlight'],
				'cta_block'               => ['label' => __('CTA Block', 'massitpro'), 'type' => 'cta'],
			],
		],
		'projects' => [
			'title'    => __('Mass IT Pro Projects Page Fields', 'massitpro'),
			'sections' => [
				'hero'              => ['label' => __('Hero', 'massitpro'), 'type' => 'hero'],
				'projects_grid_section' => ['label' => __('Projects Grid Heading', 'massitpro'), 'type' => 'section_heading'],
				'process_section'   => ['label' => __('How We Deliver Results', 'massitpro'), 'type' => 'process', 'rows' => 6, 'has_eyebrow' => true],
				'industries_section'=> [
					'label'       => __('Industries Served', 'massitpro'),
					'type'        => 'cards',
					'rows'        => 6,
					'fields'      => ['icon', 'title', 'body', 'link_url'],
					'has_eyebrow' => true,
				],
				'cta_block'         => ['label' => __('CTA Block', 'massitpro'), 'type' => 'cta'],
			],
		],
	];
}

/**
 * Preserve JSON meta payloads without flattening rich text.
 *
 * @param mixed $value Raw meta value.
 * @return string
 */
function massitpro_sanitize_native_section_json_meta($value) {
	return is_string($value) ? wp_check_invalid_utf8($value) : '';
}

/**
 * Register native meta keys for supported page sections.
 */
function massitpro_register_native_section_meta() {
	$registered = [];

	foreach (massitpro_get_native_section_registry() as $context) {
		foreach (array_keys((array) ($context['sections'] ?? [])) as $section) {
			$key = massitpro_get_section_meta_key($section);

			if (isset($registered[$key])) {
				continue;
			}

			register_post_meta(
				'page',
				$key,
				[
					'single'            => true,
					'type'              => 'string',
					'sanitize_callback' => 'massitpro_sanitize_native_section_json_meta',
					'show_in_rest'      => false,
				]
			);

			$registered[$key] = true;
		}
	}
}
add_action('init', 'massitpro_register_native_section_meta');

/**
 * Add native meta boxes for supported page contexts.
 *
 * @param string  $post_type Post type.
 * @param WP_Post $post      Current post.
 */
function massitpro_add_native_page_meta_boxes($post_type, $post) {
	if ('page' !== $post_type || ! $post instanceof WP_Post) {
		return;
	}

	$context  = massitpro_get_page_context_for_post($post->ID);
	$registry = massitpro_get_native_section_registry();

	if (empty($registry[$context])) {
		return;
	}

	add_meta_box(
		'massitpro_native_sections',
		(string) $registry[$context]['title'],
		'massitpro_render_native_page_meta_box',
		'page',
		'normal',
		'default',
		[
			'context' => $context,
		]
	);
}
add_action('add_meta_boxes', 'massitpro_add_native_page_meta_boxes', 10, 2);

/**
 * Enqueue native meta box assets.
 *
 * @param string $hook_suffix Admin hook.
 */
function massitpro_enqueue_native_meta_box_assets($hook_suffix) {
	if (! in_array($hook_suffix, ['post.php', 'post-new.php'], true)) {
		return;
	}

	$screen = get_current_screen();

	if (! $screen || 'page' !== $screen->post_type) {
		return;
	}

	wp_enqueue_media();
	wp_enqueue_script(
		'massitpro-admin-meta',
		get_template_directory_uri() . '/assets/js/admin-meta.js',
		['jquery'],
		file_exists(get_template_directory() . '/assets/js/admin-meta.js') ? filemtime(get_template_directory() . '/assets/js/admin-meta.js') : null,
		true
	);
}
add_action('admin_enqueue_scripts', 'massitpro_enqueue_native_meta_box_assets');

/**
 * Get an icon choice list for native editors.
 *
 * @return array<string,string>
 */
function massitpro_get_native_icon_choices() {
	return [
		'clock'      => __('Clock', 'massitpro'),
		'map-pin'    => __('Map Pin', 'massitpro'),
		'server'     => __('Server', 'massitpro'),
		'users'      => __('Users', 'massitpro'),
		'shield'     => __('Shield', 'massitpro'),
		'monitor'    => __('Monitor', 'massitpro'),
		'cloud'      => __('Cloud', 'massitpro'),
		'wifi'       => __('WiFi', 'massitpro'),
		'hard-drive' => __('Hard Drive', 'massitpro'),
		'headphones' => __('Headphones', 'massitpro'),
		'home'       => __('Home', 'massitpro'),
		'globe'      => __('Globe', 'massitpro'),
		'wrench'     => __('Wrench', 'massitpro'),
		'mail'       => __('Mail', 'massitpro'),
		'phone'      => __('Phone', 'massitpro'),
		'target'     => __('Target', 'massitpro'),
		'eye'        => __('Eye', 'massitpro'),
		'check'      => __('Check', 'massitpro'),
	];
}

/**
 * Resolve post choices for a relationship source.
 *
 * @param string $source Source slug.
 * @return array<int,WP_Post>
 */
function massitpro_get_native_relationship_posts($source) {
	switch ($source) {
		case 'services':
			return massitpro_get_existing_canonical_pages(array_values(massitpro_get_canonical_service_entries()));
		case 'industries':
			return massitpro_get_existing_canonical_pages(array_values(massitpro_get_canonical_industry_entries()));
		case 'locations':
			return massitpro_get_existing_canonical_pages(array_values(massitpro_get_canonical_location_entries()));
		case 'projects':
			return massitpro_query_posts(['post_type' => 'project', 'post_status' => 'publish', 'posts_per_page' => -1, 'orderby' => ['menu_order' => 'ASC', 'title' => 'ASC']]);
		case 'testimonials':
			return massitpro_query_posts(['post_type' => 'testimonial', 'post_status' => 'publish', 'posts_per_page' => -1, 'orderby' => ['menu_order' => 'ASC', 'title' => 'ASC']]);
		case 'faqs':
			return massitpro_query_posts(['post_type' => 'faq_item', 'post_status' => 'publish', 'posts_per_page' => -1, 'orderby' => ['menu_order' => 'ASC', 'title' => 'ASC']]);
		case 'posts':
			return massitpro_query_posts(['post_type' => 'post', 'post_status' => 'publish', 'posts_per_page' => -1, 'orderby' => ['date' => 'DESC']]);
	}

	return [];
}

/**
 * Resolve existing page posts from canonical entries.
 *
 * @param array<int,array<string,mixed>> $entries Canonical entries.
 * @return array<int,WP_Post>
 */
function massitpro_get_existing_canonical_pages($entries) {
	$posts = [];

	foreach ($entries as $entry) {
		$page = massitpro_find_page_by_path((string) ($entry['path'] ?? ''));

		if ($page instanceof WP_Post) {
			$posts[] = $page;
		}
	}

	return $posts;
}

/**
 * Save native page section data.
 *
 * @param int $post_id Post ID.
 */
function massitpro_save_native_page_meta($post_id) {
	if ('page' !== get_post_type($post_id)) {
		return;
	}

	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return;
	}

	if (! isset($_POST['massitpro_native_sections_nonce']) || ! wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['massitpro_native_sections_nonce'])), 'massitpro_save_native_sections')) {
		return;
	}

	if (! current_user_can('edit_page', $post_id)) {
		return;
	}

	$context  = massitpro_get_page_context_for_post($post_id);
	$registry = massitpro_get_native_section_registry();
	$sections = isset($_POST['massitpro_sections']) && is_array($_POST['massitpro_sections']) ? wp_unslash($_POST['massitpro_sections']) : [];

	if (empty($registry[$context]['sections'])) {
		return;
	}

	foreach ($registry[$context]['sections'] as $section_key => $definition) {
		$sanitized = massitpro_sanitize_native_section($definition, $sections[$section_key] ?? []);
		$meta_key  = massitpro_get_section_meta_key($section_key);

		if (massitpro_is_empty_value($sanitized)) {
			delete_post_meta($post_id, $meta_key);
			continue;
		}

		update_post_meta($post_id, $meta_key, wp_json_encode($sanitized));
	}
}
add_action('save_post_page', 'massitpro_save_native_page_meta');

/**
 * Render the native page meta box.
 *
 * @param WP_Post              $post     Current page.
 * @param array<string,mixed>  $meta_box Meta box args.
 */
function massitpro_render_native_page_meta_box($post, $meta_box) {
	$context  = (string) ($meta_box['args']['context'] ?? '');
	$registry = massitpro_get_native_section_registry();
	$config   = $registry[$context] ?? null;

	if (! $config) {
		return;
	}

	wp_nonce_field('massitpro_save_native_sections', 'massitpro_native_sections_nonce');
	?>
	<div class="massitpro-meta-box">
		<?php foreach ((array) $config['sections'] as $section_key => $definition) : ?>
			<?php $value = massitpro_get_section_meta($section_key, $post->ID, []); ?>
			<div class="massitpro-meta-box__section">
				<h3><?php echo esc_html((string) $definition['label']); ?></h3>
				<?php massitpro_render_native_section_editor($section_key, (array) $definition, $value); ?>
			</div>
		<?php endforeach; ?>
	</div>
	<?php
}

/**
 * Render a native section editor.
 *
 * @param string               $section_key Section key.
 * @param array<string,mixed>  $definition  Section definition.
 * @param array<string,mixed>  $value       Current value.
 */
function massitpro_render_native_section_editor($section_key, $definition, $value) {
	$type = (string) ($definition['type'] ?? '');

	switch ($type) {
		case 'hero':
			massitpro_render_native_hero_editor($section_key, $value);
			return;
		case 'intro':
			massitpro_render_native_intro_editor($section_key, $value);
			return;
		case 'location_intro':
			massitpro_render_native_location_intro_editor($section_key, $value);
			return;
		case 'location_hub_intro':
			massitpro_render_native_location_hub_intro_editor($section_key, $value);
			return;
		case 'simple_list':
			massitpro_render_native_simple_list_editor($section_key, $value);
			return;
		case 'cards':
			massitpro_render_native_cards_editor($section_key, $definition, $value);
			return;
		case 'process':
			massitpro_render_native_process_editor($section_key, $definition, $value);
			return;
		case 'relationship':
			massitpro_render_native_relationship_editor($section_key, $definition, $value);
			return;
		case 'trust_strip':
			massitpro_render_native_trust_strip_editor($section_key, $definition, $value);
			return;
		case 'stats':
			massitpro_render_native_stats_editor($section_key, $definition, $value);
			return;
		case 'blog':
			massitpro_render_native_blog_editor($section_key, $value);
			return;
		case 'featured_project':
			massitpro_render_native_featured_project_editor($section_key, $value);
			return;
		case 'featured_testimonial':
			massitpro_render_native_featured_testimonial_editor($section_key, $value);
			return;
		case 'featured_review_image':
			massitpro_render_native_image_field($section_key, 'image', __('Featured Review Image', 'massitpro'), absint($value['image'] ?? 0));
			return;
		case 'related_links':
			massitpro_render_native_related_links_editor($section_key, $definition, $value);
			return;
		case 'spotlight':
			massitpro_render_native_spotlight_editor($section_key, $value);
			return;
		case 'about_mission':
			massitpro_render_native_about_mission_editor($section_key, $value);
			return;
		case 'about_process':
			massitpro_render_native_about_process_editor($section_key, $definition, $value);
			return;
		case 'about_team':
			massitpro_render_native_about_team_editor($section_key, $value);
			return;
		case 'about_certifications':
			massitpro_render_native_about_certifications_editor($section_key, $value);
			return;
		case 'deliverables_pills':
			massitpro_render_native_deliverables_pills_editor($section_key, $value);
			return;
		case 'section_heading':
			massitpro_render_native_text_input($section_key, 'eyebrow', __('Eyebrow', 'massitpro'), (string) ($value['eyebrow'] ?? ''));
			massitpro_render_native_text_input($section_key, 'heading', __('Heading', 'massitpro'), (string) ($value['heading'] ?? ''));
			massitpro_render_native_textarea($section_key, 'body', __('Body', 'massitpro'), (string) ($value['body'] ?? ''), 3);
			return;
		case 'contact_form':
			massitpro_render_native_contact_form_editor($section_key, $value);
			return;
		case 'contact_location':
			massitpro_render_native_contact_location_editor($section_key, $value);
			return;
		case 'faq_location':
			massitpro_render_native_faq_location_editor($section_key, $value);
			return;
		case 'cta':
			massitpro_render_native_cta_editor($section_key, $value);
			return;
	}
}

/**
 * Build a native field name.
 *
 * @param string $section Section key.
 * @param string $path    Field path suffix.
 * @return string
 */
function massitpro_native_field_name($section, $path) {
	return 'massitpro_sections[' . $section . ']' . $path;
}

/**
 * Render a text input row.
 *
 * @param string $section Section key.
 * @param string $key     Field key.
 * @param string $label   Field label.
 * @param string $value   Field value.
 */
function massitpro_render_native_text_input($section, $key, $label, $value) {
	?>
	<p>
		<label>
			<strong><?php echo esc_html($label); ?></strong><br>
			<input type="text" class="widefat" name="<?php echo esc_attr(massitpro_native_field_name($section, '[' . $key . ']')); ?>" value="<?php echo esc_attr($value); ?>">
		</label>
	</p>
	<?php
}

/**
 * Render a textarea row.
 *
 * @param string $section Section key.
 * @param string $key     Field key.
 * @param string $label   Field label.
 * @param string $value   Field value.
 * @param int    $rows    Row count.
 */
function massitpro_render_native_textarea($section, $key, $label, $value, $rows = 4) {
	?>
	<p>
		<label>
			<strong><?php echo esc_html($label); ?></strong><br>
			<textarea class="widefat" rows="<?php echo esc_attr((string) $rows); ?>" name="<?php echo esc_attr(massitpro_native_field_name($section, '[' . $key . ']')); ?>"><?php echo esc_textarea($value); ?></textarea>
		</label>
	</p>
	<?php
}

/**
 * Render an image selector field.
 *
 * @param string $section Section key.
 * @param string $key     Field key.
 * @param string $label   Field label.
 * @param int    $value   Attachment ID.
 */
function massitpro_render_native_image_field($section, $key, $label, $value) {
	$image_id = (int) $value;
	?>
	<div class="massitpro-media-field">
		<p><strong><?php echo esc_html($label); ?></strong></p>
		<p>
			<input type="text" class="widefat massitpro-media-field__input" name="<?php echo esc_attr(massitpro_native_field_name($section, '[' . $key . ']')); ?>" value="<?php echo esc_attr((string) $image_id); ?>">
		</p>
		<p>
			<button type="button" class="button massitpro-media-button"><?php esc_html_e('Select Image', 'massitpro'); ?></button>
			<button type="button" class="button massitpro-media-clear"><?php esc_html_e('Clear', 'massitpro'); ?></button>
		</p>
		<div class="massitpro-media-field__preview">
			<?php if ($image_id) : ?>
				<?php echo wp_kses_post(wp_get_attachment_image($image_id, 'thumbnail')); ?>
			<?php endif; ?>
		</div>
	</div>
	<?php
}

/**
 * Render a select input.
 *
 * @param string              $section Section key.
 * @param string              $key     Field key.
 * @param string              $label   Field label.
 * @param string              $value   Field value.
 * @param array<string,string> $choices Choices.
 */
function massitpro_render_native_select($section, $key, $label, $value, $choices) {
	?>
	<p>
		<label>
			<strong><?php echo esc_html($label); ?></strong><br>
			<select class="widefat" name="<?php echo esc_attr(massitpro_native_field_name($section, '[' . $key . ']')); ?>">
				<?php foreach ($choices as $choice_value => $choice_label) : ?>
					<option value="<?php echo esc_attr($choice_value); ?>"<?php selected($value, $choice_value); ?>><?php echo esc_html($choice_label); ?></option>
				<?php endforeach; ?>
			</select>
		</label>
	</p>
	<?php
}

/**
 * Render a hero editor.
 *
 * @param string              $section Section key.
 * @param array<string,mixed> $value   Current value.
 */
function massitpro_render_native_hero_editor($section, $value) {
	$buttons = array_values((array) ($value['buttons'] ?? []));

	massitpro_render_native_text_input($section, 'eyebrow', __('Hero Eyebrow', 'massitpro'), (string) ($value['eyebrow'] ?? ''));
	massitpro_render_native_text_input($section, 'title_override', __('Hero Title Override', 'massitpro'), (string) ($value['title_override'] ?? ''));
	massitpro_render_native_textarea($section, 'subtitle', __('Hero Subtitle', 'massitpro'), (string) ($value['subtitle'] ?? ''), 3);
	massitpro_render_native_image_field($section, 'image', __('Hero Image', 'massitpro'), (int) ($value['image'] ?? 0));

	foreach ([0, 1] as $index) {
		$button = $buttons[$index] ?? [];
		echo '<div class="massitpro-meta-box__subsection"><h4>' . esc_html(sprintf(__('Hero Button %d', 'massitpro'), $index + 1)) . '</h4>';
		massitpro_render_native_text_input($section, 'buttons][' . $index . '][label', __('Button Label', 'massitpro'), (string) ($button['label'] ?? ''));
		massitpro_render_native_text_input($section, 'buttons][' . $index . '][url', __('Button URL', 'massitpro'), (string) ($button['url'] ?? ''));
		massitpro_render_native_select($section, 'buttons][' . $index . '][variant', __('Button Style', 'massitpro'), (string) ($button['variant'] ?? ($index ? 'hero-outline' : 'hero')), [
			'hero'         => __('Hero', 'massitpro'),
			'hero-outline' => __('Hero Outline', 'massitpro'),
			'action'       => __('Action', 'massitpro'),
			'default'      => __('Default', 'massitpro'),
			'outline'      => __('Outline', 'massitpro'),
		]);
		massitpro_render_native_select($section, 'buttons][' . $index . '][size', __('Button Size', 'massitpro'), (string) ($button['size'] ?? 'xl'), [
			'sm'      => __('Small', 'massitpro'),
			'default' => __('Default', 'massitpro'),
			'lg'      => __('Large', 'massitpro'),
			'xl'      => __('Extra Large', 'massitpro'),
		]);
		echo '</div>';
	}
}

/**
 * Render an intro/overview editor.
 *
 * @param string              $section Section key.
 * @param array<string,mixed> $value   Current value.
 */
function massitpro_render_native_intro_editor($section, $value) {
	massitpro_render_native_text_input($section, 'heading', __('Section Heading', 'massitpro'), (string) ($value['heading'] ?? ''));
	massitpro_render_native_textarea($section, 'body', __('Section Body', 'massitpro'), (string) ($value['body'] ?? ''), 5);
	massitpro_render_native_image_field($section, 'image', __('Section Image', 'massitpro'), (int) ($value['image'] ?? 0));
}

/**
 * Render the location-detail overview editor with extended fields.
 *
 * @param string              $section Section key.
 * @param array<string,mixed> $value   Current value.
 */
function massitpro_render_native_location_intro_editor($section, $value) {
	massitpro_render_native_text_input($section, 'eyebrow', __('Eyebrow Label', 'massitpro'), (string) ($value['eyebrow'] ?? ''));
	massitpro_render_native_text_input($section, 'heading', __('Section Heading', 'massitpro'), (string) ($value['heading'] ?? ''));
	massitpro_render_native_textarea($section, 'body', __('Section Body', 'massitpro'), (string) ($value['body'] ?? ''), 5);
	massitpro_render_native_text_input($section, 'location_city', __('Location / City Text', 'massitpro'), (string) ($value['location_city'] ?? ''));
	massitpro_render_native_text_input($section, 'button_label', __('CTA Button Label', 'massitpro'), (string) ($value['button_label'] ?? ''));
	massitpro_render_native_text_input($section, 'button_url', __('CTA Button URL', 'massitpro'), (string) ($value['button_url'] ?? ''));
	massitpro_render_native_image_field($section, 'image', __('Section Image', 'massitpro'), (int) ($value['image'] ?? 0));
}

/**
 * Render the locations-hub intro editor: eyebrow, heading, body, 3 feature items, image.
 *
 * @param string              $section Section key.
 * @param array<string,mixed> $value   Current value.
 */
function massitpro_render_native_location_hub_intro_editor($section, $value) {
	massitpro_render_native_text_input($section, 'eyebrow', __('Eyebrow Label', 'massitpro'), (string) ($value['eyebrow'] ?? ''));
	massitpro_render_native_text_input($section, 'heading', __('Section Heading', 'massitpro'), (string) ($value['heading'] ?? ''));
	massitpro_render_native_textarea($section, 'body', __('Section Body', 'massitpro'), (string) ($value['body'] ?? ''), 5);

	for ($i = 0; $i < 3; $i++) {
		$item = (array) ($value['items'][$i] ?? []);
		echo '<div class="massitpro-meta-box__subsection"><h4>' . esc_html(sprintf(__('Feature Item %d', 'massitpro'), $i + 1)) . '</h4>';
		massitpro_render_native_select($section, 'items][' . $i . '][icon', __('Icon', 'massitpro'), (string) ($item['icon'] ?? 'check'), massitpro_get_native_icon_choices());
		massitpro_render_native_text_input($section, 'items][' . $i . '][title', __('Title', 'massitpro'), (string) ($item['title'] ?? ''));
		massitpro_render_native_textarea($section, 'items][' . $i . '][body', __('Body', 'massitpro'), (string) ($item['body'] ?? ''), 3);
		echo '</div>';
	}

	massitpro_render_native_image_field($section, 'image', __('Section Image', 'massitpro'), (int) ($value['image'] ?? 0));
}

/**
 * Sanitize location hub intro section.
 *
 * @param array<string,mixed> $input Raw input.
 * @return array<string,mixed>
 */
function massitpro_sanitize_native_location_hub_intro_section($input) {
	$section = [
		'eyebrow' => sanitize_text_field((string) ($input['eyebrow'] ?? '')),
		'heading' => sanitize_text_field((string) ($input['heading'] ?? '')),
		'body'    => wp_kses_post((string) ($input['body'] ?? '')),
		'image'   => absint($input['image'] ?? 0),
		'items'   => [],
	];

	foreach ((array) ($input['items'] ?? []) as $row) {
		$row  = is_array($row) ? $row : [];
		$item = [
			'icon'  => sanitize_key((string) ($row['icon'] ?? 'check')),
			'title' => sanitize_text_field((string) ($row['title'] ?? '')),
			'body'  => sanitize_textarea_field((string) ($row['body'] ?? '')),
		];

		if (! massitpro_is_empty_value($item)) {
			$section['items'][] = $item;
		}
	}

	return $section;
}

/**
 * Render a simple list editor.
 *
 * @param string              $section Section key.
 * @param array<string,mixed> $value   Current value.
 */
function massitpro_render_native_simple_list_editor($section, $value) {
	$items = [];

	foreach ((array) ($value['items'] ?? []) as $item) {
		if (! empty($item['text'])) {
			$items[] = (string) $item['text'];
		}
	}

	massitpro_render_native_text_input($section, 'heading', __('Section Heading', 'massitpro'), (string) ($value['heading'] ?? ''));
	massitpro_render_native_textarea($section, 'body', __('Section Body', 'massitpro'), (string) ($value['body'] ?? ''), 4);
	massitpro_render_native_textarea($section, 'items_text', __('List Items', 'massitpro') . ' - ' . __('one item per line', 'massitpro'), implode("\n", $items), 6);
}

/**
 * Render a generic fixed-row cards editor.
 *
 * @param string              $section    Section key.
 * @param array<string,mixed> $definition Section definition.
 * @param array<string,mixed> $value      Current value.
 */
function massitpro_render_native_cards_editor($section, $definition, $value) {
	$rows   = (int) ($definition['rows'] ?? 4);
	$fields = (array) ($definition['fields'] ?? []);

	if (empty($definition['hide_section_intro'])) {
		if (! empty($definition['has_eyebrow'])) {
			massitpro_render_native_text_input($section, 'eyebrow', __('Eyebrow', 'massitpro'), (string) ($value['eyebrow'] ?? ''));
		}

		massitpro_render_native_text_input($section, 'heading', __('Section Heading', 'massitpro'), (string) ($value['heading'] ?? ''));
		massitpro_render_native_textarea($section, 'body', __('Section Body', 'massitpro'), (string) ($value['body'] ?? ''), 4);
	}

	if (! empty($definition['has_image'])) {
		massitpro_render_native_image_field($section, 'image', __('Section Image', 'massitpro'), (int) ($value['image'] ?? 0));
	}

	for ($index = 0; $index < $rows; $index++) {
		$row = (array) (($value['items'][$index] ?? []));
		echo '<div class="massitpro-meta-box__subsection"><h4>' . esc_html(sprintf(__('Item %d', 'massitpro'), $index + 1)) . '</h4>';

		if (in_array('icon', $fields, true)) {
			massitpro_render_native_select($section, 'items][' . $index . '][icon', __('Icon', 'massitpro'), (string) ($row['icon'] ?? 'check'), massitpro_get_native_icon_choices());
		}

		if (in_array('value', $fields, true)) {
			massitpro_render_native_text_input($section, 'items][' . $index . '][value', __('Value', 'massitpro'), (string) ($row['value'] ?? ''));
		}

		if (in_array('label', $fields, true)) {
			massitpro_render_native_text_input($section, 'items][' . $index . '][label', __('Label', 'massitpro'), (string) ($row['label'] ?? ''));
		}

		if (in_array('title', $fields, true)) {
			massitpro_render_native_text_input($section, 'items][' . $index . '][title', __('Title', 'massitpro'), (string) ($row['title'] ?? ''));
		}

		if (in_array('body', $fields, true)) {
			massitpro_render_native_textarea($section, 'items][' . $index . '][body', __('Body', 'massitpro'), (string) ($row['body'] ?? ''), 3);
		}

		if (in_array('highlights', $fields, true)) {
			$hl_val = $row['highlights'] ?? '';
			if (is_array($hl_val)) { $hl_val = implode("\n", $hl_val); }
			massitpro_render_native_textarea($section, 'items][' . $index . '][highlights', __('Highlights', 'massitpro') . ' — ' . __('one per line', 'massitpro'), (string) $hl_val, 3);
		}

		if (in_array('description', $fields, true)) {
			massitpro_render_native_textarea($section, 'items][' . $index . '][description', __('Description', 'massitpro'), (string) ($row['description'] ?? ''), 3);
		}

		if (in_array('image', $fields, true)) {
			massitpro_render_native_image_field($section, 'items][' . $index . '][image', __('Image', 'massitpro'), (int) ($row['image'] ?? 0));
		}

		if (in_array('link', $fields, true)) {
			$link = (array) ($row['link'] ?? []);
			massitpro_render_native_text_input($section, 'items][' . $index . '][link][title', __('Link Label', 'massitpro'), (string) ($link['title'] ?? ($link['label'] ?? '')));
			massitpro_render_native_text_input($section, 'items][' . $index . '][link][url', __('Link URL', 'massitpro'), (string) ($link['url'] ?? ''));
		}

		if (in_array('link_label', $fields, true)) {
			massitpro_render_native_text_input($section, 'items][' . $index . '][link_label', __('Link Label', 'massitpro'), (string) ($row['link_label'] ?? ''));
		}

		if (in_array('link_url', $fields, true)) {
			massitpro_render_native_text_input($section, 'items][' . $index . '][link_url', __('Link URL', 'massitpro'), (string) ($row['link_url'] ?? ''));
		}

		if (in_array('category', $fields, true)) {
			massitpro_render_native_text_input($section, 'items][' . $index . '][category', __('Category', 'massitpro'), (string) ($row['category'] ?? ''));
		}

		if (in_array('quote', $fields, true)) {
			massitpro_render_native_textarea($section, 'items][' . $index . '][quote', __('Quote', 'massitpro'), (string) ($row['quote'] ?? ''), 4);
		}

		if (in_array('name', $fields, true)) {
			massitpro_render_native_text_input($section, 'items][' . $index . '][name', __('Name', 'massitpro'), (string) ($row['name'] ?? ''));
		}

		if (in_array('role', $fields, true)) {
			massitpro_render_native_text_input($section, 'items][' . $index . '][role', __('Role', 'massitpro'), (string) ($row['role'] ?? ''));
		}

		if (in_array('company', $fields, true)) {
			massitpro_render_native_text_input($section, 'items][' . $index . '][company', __('Company', 'massitpro'), (string) ($row['company'] ?? ''));
		}

		if (in_array('industry_tag', $fields, true)) {
			massitpro_render_native_text_input($section, 'items][' . $index . '][industry_tag', __('Industry Tag', 'massitpro'), (string) ($row['industry_tag'] ?? ''));
		}

		if (in_array('question', $fields, true)) {
			massitpro_render_native_text_input($section, 'items][' . $index . '][question', __('Question', 'massitpro'), (string) ($row['question'] ?? ''));
		}

		if (in_array('answer', $fields, true)) {
			massitpro_render_native_textarea($section, 'items][' . $index . '][answer', __('Answer', 'massitpro'), (string) ($row['answer'] ?? ''), 4);
		}

		echo '</div>';
	}
}

/**
 * Render a process editor.
 *
 * @param string              $section    Section key.
 * @param array<string,mixed> $definition Section definition.
 * @param array<string,mixed> $value      Current value.
 */
function massitpro_render_native_process_editor($section, $definition, $value) {
	$rows = (int) ($definition['rows'] ?? 6);

	if (! empty($definition['has_eyebrow'])) {
		massitpro_render_native_text_input($section, 'eyebrow', __('Eyebrow', 'massitpro'), (string) ($value['eyebrow'] ?? ''));
	}

	massitpro_render_native_text_input($section, 'heading', __('Section Heading', 'massitpro'), (string) ($value['heading'] ?? ''));
	massitpro_render_native_textarea($section, 'body', __('Section Body', 'massitpro'), (string) ($value['body'] ?? ''), 4);

	for ($index = 0; $index < $rows; $index++) {
		$row = (array) ($value['steps'][$index] ?? []);
		echo '<div class="massitpro-meta-box__subsection"><h4>' . esc_html(sprintf(__('Step %d', 'massitpro'), $index + 1)) . '</h4>';
		massitpro_render_native_text_input($section, 'steps][' . $index . '][step_label', __('Step Label', 'massitpro'), (string) ($row['step_label'] ?? ''));
		massitpro_render_native_text_input($section, 'steps][' . $index . '][title', __('Title', 'massitpro'), (string) ($row['title'] ?? ''));
		massitpro_render_native_textarea($section, 'steps][' . $index . '][body', __('Body', 'massitpro'), (string) ($row['body'] ?? ''), 3);
		echo '</div>';
	}
}

/**
 * Render a relationship editor.
 *
 * @param string              $section    Section key.
 * @param array<string,mixed> $definition Section definition.
 * @param array<string,mixed> $value      Current value.
 */
function massitpro_render_native_relationship_editor($section, $definition, $value) {
	$items_key = (string) ($definition['items_key'] ?? 'items');
	$selected  = array_map('intval', (array) ($value[$items_key] ?? []));
	$posts     = massitpro_get_native_relationship_posts((string) ($definition['source'] ?? ''));

	massitpro_render_native_text_input($section, 'heading', __('Section Heading', 'massitpro'), (string) ($value['heading'] ?? ''));
	massitpro_render_native_textarea($section, 'body', __('Section Body', 'massitpro'), (string) ($value['body'] ?? ''), 4);

	if (! $posts) {
		echo '<p>' . esc_html__('No eligible items found yet for this section.', 'massitpro') . '</p>';
		return;
	}

	echo '<div class="massitpro-meta-box__subsection"><h4>' . esc_html__('Select Items', 'massitpro') . '</h4>';

	foreach ($posts as $post) {
		echo '<label style="display:block;margin-bottom:6px;">';
		echo '<input type="checkbox" name="' . esc_attr(massitpro_native_field_name($section, '[' . $items_key . '][]')) . '" value="' . esc_attr((string) $post->ID) . '"' . checked(in_array($post->ID, $selected, true), true, false) . '> ';
		echo esc_html(get_the_title($post));
		echo '</label>';
	}

	echo '</div>';
}

/**
 * Render a trust-strip editor.
 *
 * @param string              $section    Section key.
 * @param array<string,mixed> $definition Section definition.
 * @param array<string,mixed> $value      Current value.
 */
function massitpro_render_native_trust_strip_editor($section, $definition, $value) {
	$rows = (int) ($definition['rows'] ?? 6);

	massitpro_render_native_text_input($section, 'eyebrow', __('Eyebrow', 'massitpro'), (string) ($value['eyebrow'] ?? ''));
	massitpro_render_native_text_input($section, 'heading', __('Heading', 'massitpro'), (string) ($value['heading'] ?? ''));

	for ($index = 0; $index < $rows; $index++) {
		massitpro_render_native_text_input($section, 'items][' . $index . '][label', sprintf(__('Trust Item %d', 'massitpro'), $index + 1), (string) ($value['items'][$index]['label'] ?? ''));
	}
}

/**
 * Render a stats editor.
 *
 * @param string              $section    Section key.
 * @param array<string,mixed> $definition Section definition.
 * @param array<string,mixed> $value      Current value.
 */
function massitpro_render_native_stats_editor($section, $definition, $value) {
	$rows = (int) ($definition['rows'] ?? 4);

	massitpro_render_native_text_input($section, 'eyebrow', __('Eyebrow', 'massitpro'), (string) ($value['eyebrow'] ?? ''));
	massitpro_render_native_text_input($section, 'heading', __('Heading', 'massitpro'), (string) ($value['heading'] ?? ''));
	massitpro_render_native_textarea($section, 'body', __('Body', 'massitpro'), (string) ($value['body'] ?? ''), 4);

	for ($index = 0; $index < $rows; $index++) {
		$row = (array) ($value['items'][$index] ?? []);
		echo '<div class="massitpro-meta-box__subsection"><h4>' . esc_html(sprintf(__('Stat %d', 'massitpro'), $index + 1)) . '</h4>';
		massitpro_render_native_select($section, 'items][' . $index . '][icon', __('Icon', 'massitpro'), (string) ($row['icon'] ?? 'clock'), massitpro_get_native_icon_choices());
		massitpro_render_native_text_input($section, 'items][' . $index . '][value', __('Value', 'massitpro'), (string) ($row['value'] ?? ''));
		massitpro_render_native_text_input($section, 'items][' . $index . '][label', __('Label', 'massitpro'), (string) ($row['label'] ?? ''));
		massitpro_render_native_textarea($section, 'items][' . $index . '][description', __('Description', 'massitpro'), (string) ($row['description'] ?? ''), 3);
		echo '</div>';
	}
}

/**
 * Render a homepage blog editor.
 *
 * @param string              $section Section key.
 * @param array<string,mixed> $value   Current value.
 */
function massitpro_render_native_blog_editor($section, $value) {
	$selected = array_map('intval', (array) ($value['posts'] ?? []));
	$posts    = massitpro_get_native_relationship_posts('posts');

	massitpro_render_native_text_input($section, 'eyebrow', __('Eyebrow', 'massitpro'), (string) ($value['eyebrow'] ?? ''));
	massitpro_render_native_text_input($section, 'heading', __('Heading', 'massitpro'), (string) ($value['heading'] ?? ''));
	massitpro_render_native_textarea($section, 'body', __('Body', 'massitpro'), (string) ($value['body'] ?? ''), 4);
	massitpro_render_native_text_input($section, 'posts_count', __('Latest Posts Count', 'massitpro'), (string) ($value['posts_count'] ?? 3));

	echo '<div class="massitpro-meta-box__subsection"><h4>' . esc_html__('Featured Posts', 'massitpro') . '</h4>';

	foreach ($posts as $post) {
		echo '<label style="display:block;margin-bottom:6px;">';
		echo '<input type="checkbox" name="' . esc_attr(massitpro_native_field_name($section, '[posts][]')) . '" value="' . esc_attr((string) $post->ID) . '"' . checked(in_array($post->ID, $selected, true), true, false) . '> ';
		echo esc_html(get_the_title($post));
		echo '</label>';
	}

	echo '</div>';
}

/**
 * Render a featured-project editor.
 *
 * @param string              $section Section key.
 * @param array<string,mixed> $value   Current value.
 */
function massitpro_render_native_featured_project_editor($section, $value) {
	$selected = (int) ($value['project'] ?? 0);
	$posts    = massitpro_get_native_relationship_posts('projects');

	massitpro_render_native_text_input($section, 'heading', __('Section Heading', 'massitpro'), (string) ($value['heading'] ?? ''));
	massitpro_render_native_textarea($section, 'body', __('Section Body', 'massitpro'), (string) ($value['body'] ?? ''), 4);

	echo '<p><label><strong>' . esc_html__('Featured Project', 'massitpro') . '</strong><br><select class="widefat" name="' . esc_attr(massitpro_native_field_name($section, '[project]')) . '">';
	echo '<option value="">' . esc_html__('Select a project', 'massitpro') . '</option>';

	foreach ($posts as $post) {
		echo '<option value="' . esc_attr((string) $post->ID) . '"' . selected($selected, $post->ID, false) . '>' . esc_html(get_the_title($post)) . '</option>';
	}

	echo '</select></label></p>';
}

/**
 * Render a featured-testimonial editor.
 *
 * @param string              $section Section key.
 * @param array<string,mixed> $value   Current value.
 */
function massitpro_render_native_featured_testimonial_editor($section, $value) {
	massitpro_render_native_textarea($section, 'featured_quote', __('Featured Quote', 'massitpro'), (string) ($value['featured_quote'] ?? ''), 5);
	massitpro_render_native_text_input($section, 'featured_name', __('Name', 'massitpro'), (string) ($value['featured_name'] ?? ''));
	massitpro_render_native_text_input($section, 'featured_role', __('Role', 'massitpro'), (string) ($value['featured_role'] ?? ''));
	massitpro_render_native_text_input($section, 'featured_company', __('Company', 'massitpro'), (string) ($value['featured_company'] ?? ''));
	massitpro_render_native_text_input($section, 'featured_industry', __('Industry Tag', 'massitpro'), (string) ($value['featured_industry'] ?? ''));
	massitpro_render_native_image_field($section, 'featured_image', __('Featured Image', 'massitpro'), (int) ($value['featured_image'] ?? 0));
}

/**
 * Render a spotlight editor.
 *
 * @param string              $section Section key.
 * @param array<string,mixed> $value   Current value.
 */
function massitpro_render_native_spotlight_editor($section, $value) {
	$link = (array) ($value['link'] ?? []);

	massitpro_render_native_text_input($section, 'eyebrow', __('Eyebrow', 'massitpro'), (string) ($value['eyebrow'] ?? ''));
	massitpro_render_native_text_input($section, 'heading', __('Section Heading', 'massitpro'), (string) ($value['heading'] ?? ''));
	massitpro_render_native_textarea($section, 'body', __('Section Body', 'massitpro'), (string) ($value['body'] ?? ''), 4);
	massitpro_render_native_image_field($section, 'image', __('Section Image', 'massitpro'), (int) ($value['image'] ?? 0));
	massitpro_render_native_text_input($section, 'link][title', __('Link Label', 'massitpro'), (string) ($link['title'] ?? ($link['label'] ?? '')));
	massitpro_render_native_text_input($section, 'link][url', __('Link URL', 'massitpro'), (string) ($link['url'] ?? ''));
}

/**
 * Render a related-links editor.
 *
 * @param string              $section    Section key.
 * @param array<string,mixed> $definition Section definition.
 * @param array<string,mixed> $value      Current value.
 */
function massitpro_render_native_related_links_editor($section, $definition, $value) {
	$rows = (int) ($definition['rows'] ?? 6);

	massitpro_render_native_text_input($section, 'eyebrow', __('Eyebrow Label', 'massitpro'), (string) ($value['eyebrow'] ?? ''));
	massitpro_render_native_text_input($section, 'heading', __('Section Heading', 'massitpro'), (string) ($value['heading'] ?? ''));
	massitpro_render_native_textarea($section, 'body', __('Section Body', 'massitpro'), (string) ($value['body'] ?? ''), 4);
	massitpro_render_native_image_field($section, 'image', __('Section Image (left side)', 'massitpro'), (int) ($value['image'] ?? 0));

	for ($index = 0; $index < $rows; $index++) {
		$row = (array) ($value['items'][$index] ?? []);
		$link = (array) ($row['link'] ?? []);
		echo '<div class="massitpro-meta-box__subsection"><h4>' . esc_html(sprintf(__('Link %d', 'massitpro'), $index + 1)) . '</h4>';
		massitpro_render_native_select($section, 'items][' . $index . '][icon', __('Icon', 'massitpro'), (string) ($row['icon'] ?? 'arrow-right'), massitpro_get_native_icon_choices());
		massitpro_render_native_text_input($section, 'items][' . $index . '][link][title', __('Link Label', 'massitpro'), (string) ($link['title'] ?? ($link['label'] ?? '')));
		massitpro_render_native_text_input($section, 'items][' . $index . '][link][url', __('Link URL', 'massitpro'), (string) ($link['url'] ?? ''));
		massitpro_render_native_textarea($section, 'items][' . $index . '][description', __('Description', 'massitpro'), (string) ($row['description'] ?? ''), 3);
		echo '</div>';
	}
}

/**
 * Render a CTA editor.
 *
 * @param string              $section Section key.
 * @param array<string,mixed> $value   Current value.
 */
function massitpro_render_native_cta_editor($section, $value) {
	$buttons = array_values((array) ($value['buttons'] ?? []));

	massitpro_render_native_text_input($section, 'eyebrow', __('CTA Eyebrow', 'massitpro'), (string) ($value['eyebrow'] ?? ''));
	massitpro_render_native_text_input($section, 'heading', __('CTA Heading', 'massitpro'), (string) ($value['heading'] ?? ''));
	massitpro_render_native_textarea($section, 'body', __('CTA Body', 'massitpro'), (string) ($value['body'] ?? ''), 4);
	massitpro_render_native_image_field($section, 'image', __('CTA Image', 'massitpro'), (int) ($value['image'] ?? 0));

	foreach ([0, 1] as $index) {
		$button = $buttons[$index] ?? [];
		echo '<div class="massitpro-meta-box__subsection"><h4>' . esc_html(sprintf(__('CTA Button %d', 'massitpro'), $index + 1)) . '</h4>';
		massitpro_render_native_text_input($section, 'buttons][' . $index . '][label', __('Button Label', 'massitpro'), (string) ($button['label'] ?? ''));
		massitpro_render_native_text_input($section, 'buttons][' . $index . '][url', __('Button URL', 'massitpro'), (string) ($button['url'] ?? ''));
		massitpro_render_native_select($section, 'buttons][' . $index . '][variant', __('Button Style', 'massitpro'), (string) ($button['variant'] ?? ($index ? 'hero-outline' : 'hero')), [
			'hero'         => __('Hero', 'massitpro'),
			'hero-outline' => __('Hero Outline', 'massitpro'),
			'action'       => __('Action', 'massitpro'),
			'default'      => __('Default', 'massitpro'),
			'outline'      => __('Outline', 'massitpro'),
		]);
		massitpro_render_native_select($section, 'buttons][' . $index . '][size', __('Button Size', 'massitpro'), (string) ($button['size'] ?? 'xl'), [
			'sm'      => __('Small', 'massitpro'),
			'default' => __('Default', 'massitpro'),
			'lg'      => __('Large', 'massitpro'),
			'xl'      => __('Extra Large', 'massitpro'),
		]);
		echo '</div>';
	}
}

/**
 * Sanitize a native section payload.
 *
 * @param array<string,mixed> $definition Section definition.
 * @param mixed               $input      Raw input.
 * @return array<string,mixed>
 */
function massitpro_sanitize_native_section($definition, $input) {
	$input = is_array($input) ? $input : [];
	$type  = (string) ($definition['type'] ?? '');

	switch ($type) {
		case 'hero':
		case 'cta':
			return massitpro_sanitize_native_hero_like_section($input, 'cta' === $type);
		case 'intro':
			return [
				'heading' => sanitize_text_field((string) ($input['heading'] ?? '')),
				'body'    => wp_kses_post((string) ($input['body'] ?? '')),
				'image'   => absint($input['image'] ?? 0),
			];
		case 'location_intro':
			return [
				'eyebrow'       => sanitize_text_field((string) ($input['eyebrow'] ?? '')),
				'heading'       => sanitize_text_field((string) ($input['heading'] ?? '')),
				'body'          => wp_kses_post((string) ($input['body'] ?? '')),
				'location_city' => sanitize_text_field((string) ($input['location_city'] ?? '')),
				'button_label'  => sanitize_text_field((string) ($input['button_label'] ?? '')),
				'button_url'    => esc_url_raw((string) ($input['button_url'] ?? '')),
				'image'         => absint($input['image'] ?? 0),
			];
		case 'location_hub_intro':
			return massitpro_sanitize_native_location_hub_intro_section($input);
		case 'simple_list':
			return [
				'heading' => sanitize_text_field((string) ($input['heading'] ?? '')),
				'body'    => wp_kses_post((string) ($input['body'] ?? '')),
				'items'   => massitpro_sanitize_native_text_items((string) ($input['items_text'] ?? '')),
			];
		case 'cards':
			return massitpro_sanitize_native_cards_section($definition, $input);
		case 'process':
			return massitpro_sanitize_native_process_section($input);
		case 'relationship':
			return [
				'heading'                           => sanitize_text_field((string) ($input['heading'] ?? '')),
				'body'                              => wp_kses_post((string) ($input['body'] ?? '')),
				(string) ($definition['items_key'] ?? 'items') => array_values(array_filter(array_map('absint', (array) ($input[$definition['items_key']] ?? [])))),
			];
		case 'trust_strip':
			return [
				'eyebrow' => sanitize_text_field((string) ($input['eyebrow'] ?? '')),
				'heading' => sanitize_text_field((string) ($input['heading'] ?? '')),
				'items'   => massitpro_sanitize_native_label_items((array) ($input['items'] ?? [])),
			];
		case 'stats':
			return massitpro_sanitize_native_stats_section($input);
		case 'blog':
			return [
				'eyebrow'    => sanitize_text_field((string) ($input['eyebrow'] ?? '')),
				'heading'    => sanitize_text_field((string) ($input['heading'] ?? '')),
				'body'       => wp_kses_post((string) ($input['body'] ?? '')),
				'posts_count'=> max(1, min(6, absint($input['posts_count'] ?? 3))),
				'posts'      => array_values(array_filter(array_map('absint', (array) ($input['posts'] ?? [])))),
			];
		case 'featured_project':
			return [
				'heading' => sanitize_text_field((string) ($input['heading'] ?? '')),
				'body'    => wp_kses_post((string) ($input['body'] ?? '')),
				'project' => absint($input['project'] ?? 0),
			];
		case 'featured_testimonial':
			return [
				'featured_quote'    => sanitize_textarea_field((string) ($input['featured_quote'] ?? '')),
				'featured_name'     => sanitize_text_field((string) ($input['featured_name'] ?? '')),
				'featured_role'     => sanitize_text_field((string) ($input['featured_role'] ?? '')),
				'featured_company'  => sanitize_text_field((string) ($input['featured_company'] ?? '')),
				'featured_industry' => sanitize_text_field((string) ($input['featured_industry'] ?? '')),
				'featured_image'    => absint($input['featured_image'] ?? 0),
			];
		case 'spotlight':
			return [
				'eyebrow' => sanitize_text_field((string) ($input['eyebrow'] ?? '')),
				'heading' => sanitize_text_field((string) ($input['heading'] ?? '')),
				'body'    => wp_kses_post((string) ($input['body'] ?? '')),
				'image'   => absint($input['image'] ?? 0),
				'link'    => [
					'title'  => sanitize_text_field((string) (($input['link']['title'] ?? ''))),
					'url'    => esc_url_raw((string) (($input['link']['url'] ?? ''))),
					'target' => '',
				],
			];
		case 'featured_review_image':
			return [
				'image' => absint($input['image'] ?? 0),
			];
		case 'related_links':
			return massitpro_sanitize_native_related_links_section($input);
		case 'about_mission':
			return [
				'eyebrow' => sanitize_text_field((string) ($input['eyebrow'] ?? '')),
				'heading' => sanitize_text_field((string) ($input['heading'] ?? '')),
				'body'    => wp_kses_post((string) ($input['body'] ?? '')),
				'image'   => absint($input['image'] ?? 0),
			];
		case 'section_heading':
			return [
				'eyebrow' => sanitize_text_field((string) ($input['eyebrow'] ?? '')),
				'heading' => sanitize_text_field((string) ($input['heading'] ?? '')),
				'body'    => wp_kses_post((string) ($input['body'] ?? '')),
			];
		case 'about_process':
			return massitpro_sanitize_native_about_process_section($input);
		case 'about_team':
			return massitpro_sanitize_native_about_team_section($input);
		case 'about_certifications':
		case 'deliverables_pills':
			return massitpro_sanitize_native_about_certifications_section($input);
		case 'contact_form':
			return massitpro_sanitize_native_contact_form_section($input);
		case 'contact_location':
			return massitpro_sanitize_native_contact_location_section($input);
		case 'faq_location':
			return massitpro_sanitize_native_faq_location_section($input);
	}

	return [];
}

/**
 * Sanitize hero or CTA section payload.
 *
 * @param array<string,mixed> $input  Raw input.
 * @param bool                $is_cta Whether this is a CTA block.
 * @return array<string,mixed>
 */
function massitpro_sanitize_native_hero_like_section($input, $is_cta = false) {
	$section = [
		'eyebrow' => sanitize_text_field((string) ($input['eyebrow'] ?? '')),
		'body'    => '',
		'image'   => absint($input['image'] ?? 0),
		'buttons' => [],
	];

	if ($is_cta) {
		$section['heading'] = sanitize_text_field((string) ($input['heading'] ?? ''));
		$section['body']    = wp_kses_post((string) ($input['body'] ?? ''));
	} else {
		$section['title_override'] = sanitize_text_field((string) ($input['title_override'] ?? ''));
		$section['subtitle']       = sanitize_text_field((string) ($input['subtitle'] ?? ''));
		unset($section['body']);
	}

	foreach ((array) ($input['buttons'] ?? []) as $row) {
		$row = is_array($row) ? $row : [];
		$label = sanitize_text_field((string) ($row['label'] ?? ''));
		$url   = esc_url_raw((string) ($row['url'] ?? ''));

		if (! $label || ! $url) {
			continue;
		}

		$section['buttons'][] = [
			'label'   => $label,
			'url'     => $url,
			'variant' => sanitize_key((string) ($row['variant'] ?? 'default')),
			'size'    => sanitize_key((string) ($row['size'] ?? 'default')),
		];
	}

	return $section;
}

/**
 * Sanitize list items from a textarea.
 *
 * @param string $items_text Multiline text.
 * @return array<int,array<string,string>>
 */
function massitpro_sanitize_native_text_items($items_text) {
	$items = [];

	foreach (preg_split('/\r\n|\r|\n/', (string) $items_text) as $line) {
		$line = sanitize_text_field($line);

		if (! $line) {
			continue;
		}

		$items[] = ['text' => $line];
	}

	return $items;
}

/**
 * Sanitize trust-strip labels.
 *
 * @param array<int,mixed> $items Raw rows.
 * @return array<int,array<string,string>>
 */
function massitpro_sanitize_native_label_items($items) {
	$clean = [];

	foreach ($items as $item) {
		$label = sanitize_text_field((string) (($item['label'] ?? '')));

		if (! $label) {
			continue;
		}

		$clean[] = ['label' => $label];
	}

	return $clean;
}

/**
 * Sanitize a generic cards section.
 *
 * @param array<string,mixed> $definition Section definition.
 * @param array<string,mixed> $input      Raw input.
 * @return array<string,mixed>
 */
function massitpro_sanitize_native_cards_section($definition, $input) {
	$section = [
		'heading' => sanitize_text_field((string) ($input['heading'] ?? '')),
		'body'    => wp_kses_post((string) ($input['body'] ?? '')),
		'items'   => [],
	];
	$fields = (array) ($definition['fields'] ?? []);

	if (! empty($definition['has_eyebrow'])) {
		$section['eyebrow'] = sanitize_text_field((string) ($input['eyebrow'] ?? ''));
	}

	if (! empty($definition['has_image'])) {
		$section['image'] = absint($input['image'] ?? 0);
	}

	foreach ((array) ($input['items'] ?? []) as $row) {
		$row  = is_array($row) ? $row : [];
		$item = [];

		if (in_array('icon', $fields, true)) {
			$item['icon'] = sanitize_key((string) ($row['icon'] ?? 'check'));
		}

		if (in_array('value', $fields, true)) {
			$item['value'] = sanitize_text_field((string) ($row['value'] ?? ''));
		}

		if (in_array('label', $fields, true)) {
			$item['label'] = sanitize_text_field((string) ($row['label'] ?? ''));
		}

		if (in_array('title', $fields, true)) {
			$item['title'] = sanitize_text_field((string) ($row['title'] ?? ''));
		}

		if (in_array('body', $fields, true)) {
			$item['body'] = sanitize_textarea_field((string) ($row['body'] ?? ''));
		}

		if (in_array('highlights', $fields, true)) {
			$hl_raw = sanitize_textarea_field((string) ($row['highlights'] ?? ''));
			$item['highlights'] = array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $hl_raw))));
		}

		if (in_array('description', $fields, true)) {
			$item['description'] = sanitize_textarea_field((string) ($row['description'] ?? ''));
		}

		if (in_array('image', $fields, true)) {
			$item['image'] = absint($row['image'] ?? 0);
		}

		if (in_array('link', $fields, true)) {
			$title = sanitize_text_field((string) (($row['link']['title'] ?? '')));
			$url   = esc_url_raw((string) (($row['link']['url'] ?? '')));

			if ($title && $url) {
				$item['link'] = [
					'title'  => $title,
					'url'    => $url,
					'target' => '',
				];
			}
		}

		if (in_array('link_label', $fields, true)) {
			$item['link_label'] = sanitize_text_field((string) ($row['link_label'] ?? ''));
		}

		if (in_array('link_url', $fields, true)) {
			$item['link_url'] = esc_url_raw((string) ($row['link_url'] ?? ''));
		}

		if (in_array('category', $fields, true)) {
			$item['category'] = sanitize_text_field((string) ($row['category'] ?? ''));
		}

		if (in_array('quote', $fields, true)) {
			$item['quote'] = sanitize_textarea_field((string) ($row['quote'] ?? ''));
		}

		if (in_array('name', $fields, true)) {
			$item['name'] = sanitize_text_field((string) ($row['name'] ?? ''));
		}

		if (in_array('role', $fields, true)) {
			$item['role'] = sanitize_text_field((string) ($row['role'] ?? ''));
		}

		if (in_array('company', $fields, true)) {
			$item['company'] = sanitize_text_field((string) ($row['company'] ?? ''));
		}

		if (in_array('industry_tag', $fields, true)) {
			$item['industry_tag'] = sanitize_text_field((string) ($row['industry_tag'] ?? ''));
		}

		if (in_array('question', $fields, true)) {
			$item['question'] = sanitize_text_field((string) ($row['question'] ?? ''));
		}

		if (in_array('answer', $fields, true)) {
			$item['answer'] = wp_kses_post((string) ($row['answer'] ?? ''));
		}

		if (! massitpro_is_empty_value($item)) {
			$section['items'][] = $item;
		}
	}

	return $section;
}

/**
 * Sanitize a process section.
 *
 * @param array<string,mixed> $input Raw input.
 * @return array<string,mixed>
 */
function massitpro_sanitize_native_process_section($input) {
	$section = [
		'eyebrow' => sanitize_text_field((string) ($input['eyebrow'] ?? '')),
		'heading'  => sanitize_text_field((string) ($input['heading'] ?? '')),
		'body'     => wp_kses_post((string) ($input['body'] ?? '')),
		'steps'    => [],
	];

	foreach ((array) ($input['steps'] ?? []) as $row) {
		$item = [
			'step_label' => sanitize_text_field((string) ($row['step_label'] ?? '')),
			'title'      => sanitize_text_field((string) ($row['title'] ?? '')),
			'body'       => sanitize_textarea_field((string) ($row['body'] ?? '')),
		];

		if (! massitpro_is_empty_value($item)) {
			$section['steps'][] = $item;
		}
	}

	return $section;
}

/**
 * Sanitize a stats section.
 *
 * @param array<string,mixed> $input Raw input.
 * @return array<string,mixed>
 */
function massitpro_sanitize_native_stats_section($input) {
	$section = [
		'eyebrow' => sanitize_text_field((string) ($input['eyebrow'] ?? '')),
		'heading' => sanitize_text_field((string) ($input['heading'] ?? '')),
		'body'    => wp_kses_post((string) ($input['body'] ?? '')),
		'items'   => [],
	];

	foreach ((array) ($input['items'] ?? []) as $row) {
		$item = [
			'icon'        => sanitize_key((string) ($row['icon'] ?? 'clock')),
			'value'       => sanitize_text_field((string) ($row['value'] ?? '')),
			'label'       => sanitize_text_field((string) ($row['label'] ?? '')),
			'description' => sanitize_textarea_field((string) ($row['description'] ?? '')),
		];

		if (! massitpro_is_empty_value($item)) {
			$section['items'][] = $item;
		}
	}

	return $section;
}

/**
 * Sanitize a related-links section.
 *
 * @param array<string,mixed> $input Raw input.
 * @return array<string,mixed>
 */
function massitpro_sanitize_native_related_links_section($input) {
	$section = [
		'eyebrow' => sanitize_text_field((string) ($input['eyebrow'] ?? '')),
		'heading' => sanitize_text_field((string) ($input['heading'] ?? '')),
		'body'    => wp_kses_post((string) ($input['body'] ?? '')),
		'image'   => absint($input['image'] ?? 0),
		'items'   => [],
	];

	foreach ((array) ($input['items'] ?? []) as $row) {
		$title = sanitize_text_field((string) (($row['link']['title'] ?? '')));
		$url   = esc_url_raw((string) (($row['link']['url'] ?? '')));
		$item  = [
			'icon'        => sanitize_key((string) ($row['icon'] ?? 'arrow-right')),
			'link'        => $title && $url ? ['title' => $title, 'url' => $url, 'target' => ''] : [],
			'description' => sanitize_textarea_field((string) ($row['description'] ?? '')),
		];

		if (! massitpro_is_empty_value($item)) {
			$section['items'][] = $item;
		}
	}

	return $section;
}

/**
 * Render the About Mission editor.
 *
 * @param string              $section Section key.
 * @param array<string,mixed> $value   Current value.
 */
function massitpro_render_native_about_mission_editor($section, $value) {
	massitpro_render_native_text_input($section, 'eyebrow', __('Mission Eyebrow', 'massitpro'), (string) ($value['eyebrow'] ?? ''));
	massitpro_render_native_text_input($section, 'heading', __('Mission Heading', 'massitpro'), (string) ($value['heading'] ?? ''));
	massitpro_render_native_textarea($section, 'body', __('Mission Body', 'massitpro'), (string) ($value['body'] ?? ''), 5);
	massitpro_render_native_image_field($section, 'image', __('Mission Image', 'massitpro'), (int) ($value['image'] ?? 0));
}

/**
 * Render the About Process editor with image per step.
 *
 * @param string              $section    Section key.
 * @param array<string,mixed> $definition Section definition.
 * @param array<string,mixed> $value      Current value.
 */
function massitpro_render_native_about_process_editor($section, $definition, $value) {
	$rows = (int) ($definition['rows'] ?? 4);

	massitpro_render_native_text_input($section, 'eyebrow', __('Process Eyebrow', 'massitpro'), (string) ($value['eyebrow'] ?? ''));
	massitpro_render_native_text_input($section, 'heading', __('Process Heading', 'massitpro'), (string) ($value['heading'] ?? ''));
	massitpro_render_native_textarea($section, 'body', __('Process Body', 'massitpro'), (string) ($value['body'] ?? ''), 4);

	for ($index = 0; $index < $rows; $index++) {
		$row = (array) ($value['steps'][$index] ?? []);
		echo '<div class="massitpro-meta-box__subsection"><h4>' . esc_html(sprintf(__('Step %d', 'massitpro'), $index + 1)) . '</h4>';
		massitpro_render_native_text_input($section, 'steps][' . $index . '][step_label', __('Step Label', 'massitpro'), (string) ($row['step_label'] ?? ''));
		massitpro_render_native_text_input($section, 'steps][' . $index . '][title', __('Title', 'massitpro'), (string) ($row['title'] ?? ''));
		massitpro_render_native_textarea($section, 'steps][' . $index . '][body', __('Body', 'massitpro'), (string) ($row['body'] ?? ''), 3);
		massitpro_render_native_image_field($section, 'steps][' . $index . '][image', __('Step Image', 'massitpro'), (int) ($row['image'] ?? 0));
		echo '</div>';
	}
}

/**
 * Render the About Team editor.
 *
 * @param string              $section Section key.
 * @param array<string,mixed> $value   Current value.
 */
function massitpro_render_native_about_team_editor($section, $value) {
	massitpro_render_native_image_field($section, 'image', __('Team Image (left column)', 'massitpro'), (int) ($value['image'] ?? 0));
	massitpro_render_native_text_input($section, 'eyebrow', __('Team Eyebrow', 'massitpro'), (string) ($value['eyebrow'] ?? ''));
	massitpro_render_native_text_input($section, 'heading', __('Team Heading', 'massitpro'), (string) ($value['heading'] ?? ''));
	massitpro_render_native_textarea($section, 'body', __('Team Body', 'massitpro'), (string) ($value['body'] ?? ''), 4);

	for ($index = 0; $index < 3; $index++) {
		$row = (array) ($value['items'][$index] ?? []);
		echo '<div class="massitpro-meta-box__subsection"><h4>' . esc_html(sprintf(__('Team Card %d', 'massitpro'), $index + 1)) . '</h4>';
		massitpro_render_native_select($section, 'items][' . $index . '][icon', __('Icon', 'massitpro'), (string) ($row['icon'] ?? 'check'), massitpro_get_native_icon_choices());
		massitpro_render_native_text_input($section, 'items][' . $index . '][title', __('Heading', 'massitpro'), (string) ($row['title'] ?? ''));
		massitpro_render_native_textarea($section, 'items][' . $index . '][body', __('Description', 'massitpro'), (string) ($row['body'] ?? ''), 3);
		echo '</div>';
	}
}

/**
 * Render the About Certifications editor.
 *
 * @param string              $section Section key.
 * @param array<string,mixed> $value   Current value.
 */
function massitpro_render_native_about_certifications_editor($section, $value) {
	massitpro_render_native_text_input($section, 'eyebrow', __('Certifications Eyebrow', 'massitpro'), (string) ($value['eyebrow'] ?? ''));
	massitpro_render_native_text_input($section, 'heading', __('Certifications Heading', 'massitpro'), (string) ($value['heading'] ?? ''));
	massitpro_render_native_textarea($section, 'body', __('Certifications Body', 'massitpro'), (string) ($value['body'] ?? ''), 4);

	for ($index = 0; $index < 8; $index++) {
		$row = (array) ($value['items'][$index] ?? []);
		echo '<div class="massitpro-meta-box__subsection"><h4>' . esc_html(sprintf(__('Certification %d', 'massitpro'), $index + 1)) . '</h4>';
		massitpro_render_native_select($section, 'items][' . $index . '][icon', __('Icon', 'massitpro'), (string) ($row['icon'] ?? 'check'), massitpro_get_native_icon_choices());
		massitpro_render_native_text_input($section, 'items][' . $index . '][label', __('Label', 'massitpro'), (string) ($row['label'] ?? ''));
		echo '</div>';
	}
}

/**
 * Render the Deliverables Pills editor (certification-style items for service detail).
 *
 * @param string              $section Section key.
 * @param array<string,mixed> $value   Current value.
 */
function massitpro_render_native_deliverables_pills_editor($section, $value) {
	massitpro_render_native_text_input($section, 'eyebrow', __('Deliverables Eyebrow', 'massitpro'), (string) ($value['eyebrow'] ?? ''));
	massitpro_render_native_text_input($section, 'heading', __('Deliverables Heading', 'massitpro'), (string) ($value['heading'] ?? ''));
	massitpro_render_native_textarea($section, 'body', __('Deliverables Body', 'massitpro'), (string) ($value['body'] ?? ''), 4);

	for ($index = 0; $index < 8; $index++) {
		$row = (array) ($value['items'][$index] ?? []);
		echo '<div class="massitpro-meta-box__subsection"><h4>' . esc_html(sprintf(__('Deliverable %d', 'massitpro'), $index + 1)) . '</h4>';
		massitpro_render_native_select($section, 'items][' . $index . '][icon', __('Icon', 'massitpro'), (string) ($row['icon'] ?? 'check'), massitpro_get_native_icon_choices());
		massitpro_render_native_text_input($section, 'items][' . $index . '][label', __('Label', 'massitpro'), (string) ($row['label'] ?? ''));
		echo '</div>';
	}
}

/**
 * Sanitize the About Process section with image per step.
 *
 * @param array<string,mixed> $input Raw input.
 * @return array<string,mixed>
 */
function massitpro_sanitize_native_about_process_section($input) {
	$section = [
		'eyebrow' => sanitize_text_field((string) ($input['eyebrow'] ?? '')),
		'heading'  => sanitize_text_field((string) ($input['heading'] ?? '')),
		'body'     => wp_kses_post((string) ($input['body'] ?? '')),
		'steps'    => [],
	];

	foreach ((array) ($input['steps'] ?? []) as $row) {
		$item = [
			'step_label' => sanitize_text_field((string) ($row['step_label'] ?? '')),
			'title'      => sanitize_text_field((string) ($row['title'] ?? '')),
			'body'       => sanitize_textarea_field((string) ($row['body'] ?? '')),
			'image'      => absint($row['image'] ?? 0),
		];

		if (! massitpro_is_empty_value([$item['step_label'], $item['title'], $item['body']])) {
			$section['steps'][] = $item;
		}
	}

	return $section;
}

/**
 * Sanitize the About Team section.
 *
 * @param array<string,mixed> $input Raw input.
 * @return array<string,mixed>
 */
function massitpro_sanitize_native_about_team_section($input) {
	$section = [
		'image'   => absint($input['image'] ?? 0),
		'eyebrow' => sanitize_text_field((string) ($input['eyebrow'] ?? '')),
		'heading' => sanitize_text_field((string) ($input['heading'] ?? '')),
		'body'    => wp_kses_post((string) ($input['body'] ?? '')),
		'items'   => [],
	];

	foreach ((array) ($input['items'] ?? []) as $row) {
		$item = [
			'icon'  => sanitize_key((string) ($row['icon'] ?? 'check')),
			'title' => sanitize_text_field((string) ($row['title'] ?? '')),
			'body'  => sanitize_textarea_field((string) ($row['body'] ?? '')),
		];

		if (! massitpro_is_empty_value([$item['title'], $item['body']])) {
			$section['items'][] = $item;
		}
	}

	return $section;
}

/**
 * Sanitize the About Certifications section.
 *
 * @param array<string,mixed> $input Raw input.
 * @return array<string,mixed>
 */
function massitpro_sanitize_native_about_certifications_section($input) {
	$section = [
		'eyebrow' => sanitize_text_field((string) ($input['eyebrow'] ?? '')),
		'heading' => sanitize_text_field((string) ($input['heading'] ?? '')),
		'body'    => wp_kses_post((string) ($input['body'] ?? '')),
		'items'   => [],
	];

	foreach ((array) ($input['items'] ?? []) as $row) {
		$label = sanitize_text_field((string) ($row['label'] ?? ''));

		if (! $label) {
			continue;
		}

		$section['items'][] = [
			'icon'  => sanitize_key((string) ($row['icon'] ?? 'check')),
			'label' => $label,
		];
	}

	return $section;
}

/**
 * Render the Contact Form & Info Cards editor.
 *
 * @param string              $section Section key.
 * @param array<string,mixed> $value   Current value.
 */
function massitpro_render_native_contact_form_editor($section, $value) {
	massitpro_render_native_text_input($section, 'eyebrow', __('Section Eyebrow', 'massitpro'), (string) ($value['eyebrow'] ?? ''));
	massitpro_render_native_text_input($section, 'heading', __('Section Heading', 'massitpro'), (string) ($value['heading'] ?? ''));
	massitpro_render_native_textarea($section, 'body', __('Section Body', 'massitpro'), (string) ($value['body'] ?? ''), 4);

	echo '<hr><h4>' . esc_html__('Form Settings', 'massitpro') . '</h4>';

	massitpro_render_native_select($section, 'form_mode', __('Form Mode', 'massitpro'), (string) ($value['form_mode'] ?? 'native'), [
		'native'    => __('Native Theme Form', 'massitpro'),
		'shortcode' => __('Shortcode / Embed', 'massitpro'),
	]);

	massitpro_render_native_text_input($section, 'form_shortcode', __('Form Shortcode', 'massitpro'), (string) ($value['form_shortcode'] ?? ''));

	echo '<p class="description">' . esc_html__('If form mode is Shortcode, paste a shortcode above. Otherwise the native form renders.', 'massitpro') . '</p>';

	massitpro_render_native_text_input($section, 'form_heading', __('Form Heading', 'massitpro'), (string) ($value['form_heading'] ?? ''));
	massitpro_render_native_textarea($section, 'form_body', __('Form Intro Text', 'massitpro'), (string) ($value['form_body'] ?? ''), 3);
	massitpro_render_native_text_input($section, 'submit_label', __('Submit Button Label', 'massitpro'), (string) ($value['submit_label'] ?? ''));
	massitpro_render_native_text_input($section, 'recipient_email', __('Recipient Email', 'massitpro'), (string) ($value['recipient_email'] ?? ''));
	massitpro_render_native_text_input($section, 'success_message', __('Success Message', 'massitpro'), (string) ($value['success_message'] ?? ''));
	massitpro_render_native_text_input($section, 'error_message', __('Error Message', 'massitpro'), (string) ($value['error_message'] ?? ''));
	massitpro_render_native_text_input($section, 'privacy_url', __('Privacy Policy URL', 'massitpro'), (string) ($value['privacy_url'] ?? ''));

	echo '<hr><h4>' . esc_html__('Contact Info Cards (Right Column)', 'massitpro') . '</h4>';
	echo '<p class="description">' . esc_html__('Up to 5 contact info cards. Body appears above the heading in each card.', 'massitpro') . '</p>';

	for ($index = 0; $index < 5; $index++) {
		$row = (array) (($value['cards'][$index] ?? []));
		echo '<div class="massitpro-meta-box__subsection"><h4>' . esc_html(sprintf(__('Card %d', 'massitpro'), $index + 1)) . '</h4>';
		massitpro_render_native_select($section, 'cards][' . $index . '][icon', __('Icon', 'massitpro'), (string) ($row['icon'] ?? 'check'), massitpro_get_native_icon_choices());
		massitpro_render_native_textarea($section, 'cards][' . $index . '][body', __('Body', 'massitpro'), (string) ($row['body'] ?? ''), 2);
		massitpro_render_native_text_input($section, 'cards][' . $index . '][heading', __('Heading', 'massitpro'), (string) ($row['heading'] ?? ''));
		massitpro_render_native_text_input($section, 'cards][' . $index . '][link_url', __('Link URL (optional)', 'massitpro'), (string) ($row['link_url'] ?? ''));
		echo '</div>';
	}
}

/**
 * Sanitize Contact Form & Info Cards section.
 *
 * @param array<string,mixed> $input Raw input.
 * @return array<string,mixed>
 */
function massitpro_sanitize_native_contact_form_section($input) {
	$section = [
		'eyebrow'         => sanitize_text_field((string) ($input['eyebrow'] ?? '')),
		'heading'         => sanitize_text_field((string) ($input['heading'] ?? '')),
		'body'            => wp_kses_post((string) ($input['body'] ?? '')),
		'form_mode'       => in_array((string) ($input['form_mode'] ?? ''), ['native', 'shortcode'], true) ? (string) $input['form_mode'] : 'native',
		'form_shortcode'  => sanitize_text_field((string) ($input['form_shortcode'] ?? '')),
		'form_heading'    => sanitize_text_field((string) ($input['form_heading'] ?? '')),
		'form_body'       => wp_kses_post((string) ($input['form_body'] ?? '')),
		'submit_label'    => sanitize_text_field((string) ($input['submit_label'] ?? '')),
		'recipient_email' => sanitize_email((string) ($input['recipient_email'] ?? '')),
		'success_message' => sanitize_text_field((string) ($input['success_message'] ?? '')),
		'error_message'   => sanitize_text_field((string) ($input['error_message'] ?? '')),
		'privacy_url'     => esc_url_raw((string) ($input['privacy_url'] ?? '')),
		'cards'           => [],
	];

	foreach ((array) ($input['cards'] ?? []) as $row) {
		$body    = sanitize_textarea_field((string) ($row['body'] ?? ''));
		$heading = sanitize_text_field((string) ($row['heading'] ?? ''));

		if (! $body && ! $heading) {
			continue;
		}

		$section['cards'][] = [
			'icon'     => sanitize_key((string) ($row['icon'] ?? 'check')),
			'body'     => $body,
			'heading'  => $heading,
			'link_url' => esc_url_raw((string) ($row['link_url'] ?? '')),
		];
	}

	return $section;
}

/**
 * Render the Contact Location editor.
 *
 * @param string              $section Section key.
 * @param array<string,mixed> $value   Current value.
 */
function massitpro_render_native_contact_location_editor($section, $value) {
	massitpro_render_native_text_input($section, 'eyebrow', __('Eyebrow', 'massitpro'), (string) ($value['eyebrow'] ?? ''));
	massitpro_render_native_text_input($section, 'heading', __('Section Heading', 'massitpro'), (string) ($value['heading'] ?? ''));
	massitpro_render_native_textarea($section, 'body', __('Section Body', 'massitpro'), (string) ($value['body'] ?? ''), 4);
	massitpro_render_native_image_field($section, 'image', __('Section Image', 'massitpro'), (int) ($value['image'] ?? 0));
	massitpro_render_native_text_input($section, 'button_label', __('Button Label', 'massitpro'), (string) ($value['button_label'] ?? ''));
	massitpro_render_native_text_input($section, 'button_url', __('Button URL', 'massitpro'), (string) ($value['button_url'] ?? ''));

	echo '<hr><h4>' . esc_html__('Location Links (optional)', 'massitpro') . '</h4>';

	for ($index = 0; $index < 6; $index++) {
		$row = (array) (($value['links'][$index] ?? []));
		echo '<div class="massitpro-meta-box__subsection"><h4>' . esc_html(sprintf(__('Location %d', 'massitpro'), $index + 1)) . '</h4>';
		massitpro_render_native_text_input($section, 'links][' . $index . '][label', __('Label', 'massitpro'), (string) ($row['label'] ?? ''));
		massitpro_render_native_text_input($section, 'links][' . $index . '][url', __('URL', 'massitpro'), (string) ($row['url'] ?? ''));
		echo '</div>';
	}
}

/**
 * Sanitize Contact Location section.
 *
 * @param array<string,mixed> $input Raw input.
 * @return array<string,mixed>
 */
function massitpro_sanitize_native_contact_location_section($input) {
	$section = [
		'eyebrow'      => sanitize_text_field((string) ($input['eyebrow'] ?? '')),
		'heading'       => sanitize_text_field((string) ($input['heading'] ?? '')),
		'body'          => wp_kses_post((string) ($input['body'] ?? '')),
		'image'         => absint($input['image'] ?? 0),
		'button_label'  => sanitize_text_field((string) ($input['button_label'] ?? '')),
		'button_url'    => esc_url_raw((string) ($input['button_url'] ?? '')),
		'links'         => [],
	];

	foreach ((array) ($input['links'] ?? []) as $row) {
		$label = sanitize_text_field((string) ($row['label'] ?? ''));
		$url   = esc_url_raw((string) ($row['url'] ?? ''));

		if (! $label) {
			continue;
		}

		$section['links'][] = [
			'label' => $label,
			'url'   => $url,
		];
	}

	return $section;
}

/**
 * Render FAQ Location section editor.
 *
 * @param string              $section Section key.
 * @param array<string,mixed> $value   Current value.
 */
function massitpro_render_native_faq_location_editor($section, $value) {
	massitpro_render_native_text_input($section, 'eyebrow', __('Eyebrow', 'massitpro'), (string) ($value['eyebrow'] ?? ''));
	massitpro_render_native_text_input($section, 'heading', __('Section Heading', 'massitpro'), (string) ($value['heading'] ?? ''));
	massitpro_render_native_textarea($section, 'body', __('Section Body', 'massitpro'), (string) ($value['body'] ?? ''), 4);

	echo '<hr><h4>' . esc_html__('Location Items', 'massitpro') . '</h4>';

	for ($index = 0; $index < 12; $index++) {
		$row = (array) (($value['items'][$index] ?? []));
		echo '<div class="massitpro-meta-box__subsection"><h4>' . esc_html(sprintf(__('Location %d', 'massitpro'), $index + 1)) . '</h4>';
		massitpro_render_native_text_input($section, 'items][' . $index . '][title', __('Title', 'massitpro'), (string) ($row['title'] ?? ''));
		massitpro_render_native_text_input($section, 'items][' . $index . '][link_url', __('Link URL', 'massitpro'), (string) ($row['link_url'] ?? ''));
		echo '</div>';
	}
}

/**
 * Sanitize FAQ Location section.
 *
 * @param array<string,mixed> $input Raw input.
 * @return array<string,mixed>
 */
function massitpro_sanitize_native_faq_location_section($input) {
	$section = [
		'eyebrow' => sanitize_text_field((string) ($input['eyebrow'] ?? '')),
		'heading' => sanitize_text_field((string) ($input['heading'] ?? '')),
		'body'    => wp_kses_post((string) ($input['body'] ?? '')),
		'items'   => [],
	];

	foreach ((array) ($input['items'] ?? []) as $row) {
		$title    = sanitize_text_field((string) ($row['title'] ?? ''));
		$link_url = esc_url_raw((string) ($row['link_url'] ?? ''));

		if (! $title) {
			continue;
		}

		$section['items'][] = [
			'title'    => $title,
			'link_url' => $link_url,
		];
	}

	return $section;
}
