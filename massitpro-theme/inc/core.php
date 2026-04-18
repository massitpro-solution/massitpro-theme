<?php
/**
 * Core theme setup and utility helpers.
 *
 * @package MassITPro
 */

if (! defined('ABSPATH')) {
	exit;
}

/**
 * Theme setup.
 */
function massitpro_theme_setup() {
	add_theme_support('title-tag');
	add_theme_support('post-thumbnails');
	add_theme_support(
		'html5',
		[
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		]
	);
	add_theme_support(
		'custom-logo',
		[
			'height'      => 80,
			'width'       => 280,
			'flex-width'  => true,
			'flex-height' => true,
		]
	);
	add_theme_support('editor-styles');
	add_theme_support('wp-block-styles');
	add_theme_support('responsive-embeds');
	add_theme_support('align-wide');
	add_post_type_support('page', 'excerpt');

	add_editor_style('assets/css/app.css');

	register_nav_menus(
		[
			'primary' => __('Primary Navigation', 'massitpro'),
		]
	);

	add_image_size('massitpro-card', 960, 720, true);
	add_image_size('massitpro-wide', 1440, 960, true);
}
add_action('after_setup_theme', 'massitpro_theme_setup');

/**
 * Theme assets.
 */
function massitpro_enqueue_assets() {
	$css_path = get_template_directory() . '/assets/css/app.css';
	$js_path  = get_template_directory() . '/assets/js/app.js';

	wp_enqueue_style(
		'massitpro-fonts',
		'https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@400;500;600;700&family=Outfit:wght@500;600;700;800&display=swap',
		[],
		null
	);

	wp_enqueue_style(
		'massitpro-app',
		get_template_directory_uri() . '/assets/css/app.css',
		['massitpro-fonts'],
		file_exists($css_path) ? filemtime($css_path) : null
	);

	wp_enqueue_script(
		'massitpro-app',
		get_template_directory_uri() . '/assets/js/app.js',
		[],
		file_exists($js_path) ? filemtime($js_path) : null,
		true
	);
}
add_action('wp_enqueue_scripts', 'massitpro_enqueue_assets');

/**
 * Register custom content types.
 */
function massitpro_register_post_types() {
	register_post_type(
		'project',
		[
			'labels'       => [
				'name'          => __('Projects', 'massitpro'),
				'singular_name' => __('Project', 'massitpro'),
			],
			'public'       => true,
			'show_in_rest' => true,
			'menu_icon'    => 'dashicons-portfolio',
			'supports'     => ['title', 'editor', 'excerpt', 'thumbnail', 'page-attributes'],
			'has_archive'  => false,
			'rewrite'      => ['slug' => 'project'],
		]
	);

	register_post_type(
		'testimonial',
		[
			'labels'       => [
				'name'          => __('Testimonials', 'massitpro'),
				'singular_name' => __('Testimonial', 'massitpro'),
			],
			'public'       => true,
			'show_in_rest' => true,
			'menu_icon'    => 'dashicons-format-quote',
			'supports'     => ['title', 'editor', 'excerpt', 'thumbnail', 'page-attributes'],
			'has_archive'  => false,
			'rewrite'      => ['slug' => 'testimonial'],
		]
	);

	register_taxonomy(
		'testimonial_industry',
		'testimonial',
		[
			'labels'             => [
				'name'          => __('Industries', 'massitpro'),
				'singular_name' => __('Industry', 'massitpro'),
				'add_new_item'  => __('Add New Industry', 'massitpro'),
				'menu_name'     => __('Industries', 'massitpro'),
			],
			'hierarchical'       => false,
			'show_in_rest'       => true,
			'show_admin_column'  => true,
			'rewrite'            => ['slug' => 'testimonial-industry'],
		]
	);

	register_taxonomy(
		'project_category',
		'project',
		[
			'labels'             => [
				'name'          => __('Project Categories', 'massitpro'),
				'singular_name' => __('Project Category', 'massitpro'),
				'add_new_item'  => __('Add New Project Category', 'massitpro'),
				'menu_name'     => __('Categories', 'massitpro'),
			],
			'hierarchical'       => true,
			'show_in_rest'       => true,
			'show_admin_column'  => true,
			'rewrite'            => ['slug' => 'project-category'],
		]
	);
}
add_action('init', 'massitpro_register_post_types');

/**
 * Register CPT post meta fields.
 */
function massitpro_register_cpt_meta() {
	foreach (['_testimonial_role', '_testimonial_company'] as $key) {
		register_post_meta(
			'testimonial',
			$key,
			[
				'show_in_rest'      => true,
				'single'            => true,
				'type'              => 'string',
				'sanitize_callback' => 'sanitize_text_field',
			]
		);
	}

	register_post_meta(
		'project',
		'_project_subtitle',
		[
			'show_in_rest'      => true,
			'single'            => true,
			'type'              => 'string',
			'sanitize_callback' => 'sanitize_text_field',
		]
	);
}
add_action('init', 'massitpro_register_cpt_meta');

/**
 * Render the testimonial CPT meta box fields.
 *
 * @param WP_Post $post Current post object.
 */
function massitpro_render_testimonial_meta_box_callback($post) {
	wp_nonce_field('massitpro_save_cpt_meta', 'massitpro_cpt_meta_nonce');
	$role    = (string) get_post_meta($post->ID, '_testimonial_role', true);
	$company = (string) get_post_meta($post->ID, '_testimonial_company', true);
	?>
	<p>
		<label for="massitpro_testimonial_role"><?php esc_html_e('Role', 'massitpro'); ?></label><br>
		<input type="text" id="massitpro_testimonial_role" name="_testimonial_role" value="<?php echo esc_attr($role); ?>" class="widefat">
	</p>
	<p>
		<label for="massitpro_testimonial_company"><?php esc_html_e('Company', 'massitpro'); ?></label><br>
		<input type="text" id="massitpro_testimonial_company" name="_testimonial_company" value="<?php echo esc_attr($company); ?>" class="widefat">
	</p>
	<p class="description"><?php esc_html_e('Industry is set using the Industries taxonomy in the right sidebar.', 'massitpro'); ?></p>
	<?php
}

/**
 * Render the project CPT meta box fields.
 *
 * @param WP_Post $post Current post object.
 */
function massitpro_render_project_meta_box_callback($post) {
	wp_nonce_field('massitpro_save_cpt_meta', 'massitpro_cpt_meta_nonce');
	$subtitle = (string) get_post_meta($post->ID, '_project_subtitle', true);
	?>
	<p>
		<label for="massitpro_project_subtitle"><?php esc_html_e('Subtitle / Category Label', 'massitpro'); ?></label><br>
		<input type="text" id="massitpro_project_subtitle" name="_project_subtitle" value="<?php echo esc_attr($subtitle); ?>" class="widefat">
	</p>
	<p class="description"><?php esc_html_e('Use featured image for the project image. Use the title for the project name.', 'massitpro'); ?></p>
	<?php
}

/**
 * Add CPT meta boxes for testimonial and project post types.
 */
function massitpro_render_cpt_meta_boxes() {
	add_meta_box(
		'massitpro_testimonial_details',
		__('Testimonial Details', 'massitpro'),
		'massitpro_render_testimonial_meta_box_callback',
		'testimonial',
		'normal',
		'high'
	);

	add_meta_box(
		'massitpro_project_details',
		__('Project Details', 'massitpro'),
		'massitpro_render_project_meta_box_callback',
		'project',
		'normal',
		'high'
	);
}
add_action('add_meta_boxes', 'massitpro_render_cpt_meta_boxes');

/**
 * Save CPT meta fields.
 *
 * @param int $post_id Post ID.
 */
function massitpro_save_cpt_meta($post_id) {
	if (! isset($_POST['massitpro_cpt_meta_nonce'])) {
		return;
	}

	if (! wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['massitpro_cpt_meta_nonce'])), 'massitpro_save_cpt_meta')) {
		return;
	}

	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return;
	}

	if (! current_user_can('edit_post', $post_id)) {
		return;
	}

	$post_type = get_post_type($post_id);

	if ('testimonial' === $post_type) {
		if (isset($_POST['_testimonial_role'])) {
			update_post_meta($post_id, '_testimonial_role', sanitize_text_field(wp_unslash($_POST['_testimonial_role'])));
		}

		if (isset($_POST['_testimonial_company'])) {
			update_post_meta($post_id, '_testimonial_company', sanitize_text_field(wp_unslash($_POST['_testimonial_company'])));
		}
	}

	if ('project' === $post_type) {
		if (isset($_POST['_project_subtitle'])) {
			update_post_meta($post_id, '_project_subtitle', sanitize_text_field(wp_unslash($_POST['_project_subtitle'])));
		}
	}
}
add_action('save_post', 'massitpro_save_cpt_meta');

/**
 * Read a shared contact setting.
 *
 * @param string $key Setting key.
 * @return string
 */
function massitpro_theme_option($key) {
	$defaults = massitpro_get_theme_defaults();
	$fallback = $defaults[$key] ?? '';
	$value = get_theme_mod('massitpro_' . $key, $fallback);

	return is_string($value) ? trim($value) : $fallback;
}

/**
 * Resolve the global header CTA row.
 *
 * @return array<string,string>
 */
function massitpro_get_header_primary_cta() {
	$contact = massitpro_get_canonical_page('contact');
	$page    = $contact ? massitpro_find_page_by_path((string) $contact['path']) : null;

	if (! $page instanceof WP_Post) {
		return [];
	}

	return [
		'label'   => massitpro_get_post_title_text($page->ID) ?: __('Contact', 'massitpro'),
		'url'     => get_permalink($page),
		'target'  => '',
		'variant' => 'nav',
		'size'    => 'sm',
	];
}

/**
 * Resolve footer CTA settings.
 *
 * @return array<string,mixed>
 */
function massitpro_get_footer_cta_settings() {
	return [];
}

/**
 * Resolve footer legal links.
 *
 * @return array<int,array<string,mixed>>
 */
function massitpro_get_footer_legal_links() {
	return [];
}

/**
 * Body classes.
 *
 * @param array<int,string> $classes Existing classes.
 * @return array<int,string>
 */
function massitpro_body_classes($classes) {
	if (is_front_page()) {
		$classes[] = 'page-home';
	}

	if (is_singular('page')) {
		$classes[] = 'page-context-' . sanitize_html_class(massitpro_get_page_context_for_post(get_the_ID()));
	}

	return $classes;
}
add_filter('body_class', 'massitpro_body_classes');

/**
 * Resolve a page object by canonical path.
 *
 * @param string $path Relative path.
 * @return WP_Post|null
 */
function massitpro_find_page_by_path($path) {
	$path = massitpro_normalize_path($path);

	if ('' === $path) {
		$front_page_id = (int) get_option('page_on_front');

		return $front_page_id ? get_post($front_page_id) : null;
	}

	$page = get_page_by_path($path);

	return $page instanceof WP_Post ? $page : null;
}

/**
 * Resolve a canonical entry URL to a live permalink when possible.
 *
 * @param array<string,mixed> $entry Entry data with a `path`.
 * @return string
 */
function massitpro_entry_url($entry) {
	$path = massitpro_normalize_path((string) ($entry['path'] ?? ''));
	$page = massitpro_find_page_by_path($path);

	if ($page instanceof WP_Post) {
		return get_permalink($page);
	}

	return massitpro_canonical_url_from_path($path);
}

/**
 * Load an image asset if it exists.
 *
 * @param string $relative_path Relative path inside assets/images.
 * @return array<string,string>|null
 */
function massitpro_get_image_asset($relative_path) {
	$relative_path = ltrim((string) $relative_path, '/');
	$file_path     = get_theme_file_path('assets/images/' . $relative_path);

	if (! file_exists($file_path)) {
		return null;
	}

	return [
		'path' => $file_path,
		'url'  => get_theme_file_uri('assets/images/' . $relative_path),
	];
}

/**
 * Resolve the expected logo asset.
 *
 * @param string $context Either light or dark background context.
 * @param bool   $mark    Whether to use the mark-only file.
 * @return array<string,string>|null
 */
function massitpro_get_logo_asset($context = 'light', $mark = false) {
	$context  = 'dark' === $context ? 'dark' : 'light';
	$basename = $mark ? 'logo-mark-' : 'logo-main-';
	$variant  = 'dark' === $context ? 'light' : 'dark';

	return massitpro_get_image_asset('logo/' . $basename . $variant . '.png');
}

/**
 * Build a tel: href.
 *
 * @param string $phone Phone number.
 * @return string
 */
function massitpro_tel_href($phone) {
	return 'tel:' . preg_replace('/[^0-9+]/', '', (string) $phone);
}

/**
 * SVG icon map used across the theme.
 *
 * @param string $name Icon name.
 * @return string
 */
function massitpro_svg_icon($name) {
	$icons = [
		'arrow-right' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12h14M13 5l7 7-7 7" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"/></svg>',
		'arrow-left'  => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M19 12H5m6-7-7 7 7 7" fill="none" stroke="currentColor" stroke-linecap="round" stroke-width="1.8"/></svg>',
		'chevron-down'=> '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="m7 10 5 5 5-5" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"/></svg>',
		'menu'        => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 7h16M4 12h16M4 17h16" fill="none" stroke="currentColor" stroke-linecap="round" stroke-width="1.8"/></svg>',
		'close'       => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="m6 6 12 12M18 6 6 18" fill="none" stroke="currentColor" stroke-linecap="round" stroke-width="1.8"/></svg>',
		'shield'      => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 3 5 6v5c0 5 2.8 8.5 7 10 4.2-1.5 7-5 7-10V6l-7-3Z" fill="none" stroke="currentColor" stroke-width="1.8"/></svg>',
		'monitor'     => '<svg viewBox="0 0 24 24" aria-hidden="true"><rect x="4" y="5" width="16" height="10" rx="2" fill="none" stroke="currentColor" stroke-width="1.8"/><path d="M10 19h4M12 15v4" fill="none" stroke="currentColor" stroke-linecap="round" stroke-width="1.8"/></svg>',
		'cloud'       => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M8 18h8a4 4 0 0 0 .4-8A5.5 5.5 0 0 0 6.3 8.2 4 4 0 0 0 8 18Z" fill="none" stroke="currentColor" stroke-linecap="round" stroke-width="1.8"/></svg>',
		'wifi'        => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 9a11 11 0 0 1 14 0M8 12.5a6.5 6.5 0 0 1 8 0M11.5 16a2 2 0 0 1 1 0" fill="none" stroke="currentColor" stroke-linecap="round" stroke-width="1.8"/><circle cx="12" cy="19" r="1" fill="currentColor"/></svg>',
		'hard-drive'  => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 15h16l-2-7H6l-2 7Zm0 0v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-2M8 12h.01M12 12h.01" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"/></svg>',
		'headphones'  => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12a7 7 0 0 1 14 0v5h-4v-4h4M5 17h4v4H5z" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"/></svg>',
		'map-pin'     => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 21s6-5.3 6-11a6 6 0 1 0-12 0c0 5.7 6 11 6 11Z" fill="none" stroke="currentColor" stroke-width="1.8"/><circle cx="12" cy="10" r="2.2" fill="none" stroke="currentColor" stroke-width="1.8"/></svg>',
		'clock'       => '<svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="8" fill="none" stroke="currentColor" stroke-width="1.8"/><path d="M12 8v4l2.5 2.5" fill="none" stroke="currentColor" stroke-linecap="round" stroke-width="1.8"/></svg>',
		'server'      => '<svg viewBox="0 0 24 24" aria-hidden="true"><rect x="4" y="5" width="16" height="5" rx="1.5" fill="none" stroke="currentColor" stroke-width="1.8"/><rect x="4" y="14" width="16" height="5" rx="1.5" fill="none" stroke="currentColor" stroke-width="1.8"/><path d="M8 7.5h.01M8 16.5h.01" fill="none" stroke="currentColor" stroke-linecap="round" stroke-width="1.8"/></svg>',
		'users'       => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M16 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2M10 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8Zm10 10v-2a4 4 0 0 0-3-3.87M13 4.13a4 4 0 0 1 0 7.75" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"/></svg>',
		'home'        => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="m4 11 8-6 8 6v8a1 1 0 0 1-1 1h-4v-5H9v5H5a1 1 0 0 1-1-1Z" fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="1.8"/></svg>',
		'globe'       => '<svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="9" fill="none" stroke="currentColor" stroke-width="1.8"/><path d="M3 12h18M12 3a14 14 0 0 1 0 18M12 3a14 14 0 0 0 0 18" fill="none" stroke="currentColor" stroke-width="1.8"/></svg>',
		'wrench'      => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="m14.7 6.3 3 3L9 18l-4 1 1-4 8.7-8.7Zm0 0a3 3 0 1 1 4.2 4.2" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"/></svg>',
		'mail'        => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 7h16v10H4z" fill="none" stroke="currentColor" stroke-width="1.8"/><path d="m4 8 8 6 8-6" fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="1.8"/></svg>',
		'phone'       => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M8 4h3l1 4-2 2a14 14 0 0 0 4 4l2-2 4 1v3a2 2 0 0 1-2 2A14 14 0 0 1 4 6a2 2 0 0 1 2-2Z" fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="1.8"/></svg>',
		'target'      => '<svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="8" fill="none" stroke="currentColor" stroke-width="1.8"/><circle cx="12" cy="12" r="4" fill="none" stroke="currentColor" stroke-width="1.8"/><circle cx="12" cy="12" r="1.6" fill="currentColor"/></svg>',
		'eye'         => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6S2 12 2 12Z" fill="none" stroke="currentColor" stroke-width="1.8"/><circle cx="12" cy="12" r="3" fill="none" stroke="currentColor" stroke-width="1.8"/></svg>',
		'check'       => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="m5 13 4 4L19 7" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"/></svg>',
		'star'        => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="m12 3.6 2.6 5.3 5.8.8-4.2 4.1 1 5.8L12 16.8l-5.2 2.8 1-5.8L3.6 9.7l5.8-.8L12 3.6Z" fill="currentColor"/></svg>',
	];

	return $icons[$name] ?? $icons['arrow-right'];
}

/**
 * Primary nav fallback with canonical grouped links.
 *
 * @param array<string,mixed>|object|null $args Menu args.
 */
function massitpro_primary_menu_fallback($args = null) {
	$menu_class = 'site-menu';

	if (is_object($args) && ! empty($args->menu_class)) {
		$menu_class = (string) $args->menu_class;
	}

	echo '<ul class="' . esc_attr($menu_class) . '">';

	foreach (massitpro_get_nav_links() as $item) {
		$children   = array_values((array) ($item['children'] ?? []));
		$item_class = $children ? 'menu-item menu-item-has-children' : 'menu-item';

		echo '<li class="' . esc_attr($item_class) . '">';
		echo '<a href="' . esc_url(massitpro_entry_url($item)) . '">' . esc_html((string) $item['label']) . '</a>';

		if ($children) {
			echo '<ul class="sub-menu">';

			foreach ($children as $child) {
				echo '<li class="menu-item"><a href="' . esc_url(massitpro_entry_url($child)) . '">' . esc_html((string) $child['label']) . '</a></li>';
			}

			echo '</ul>';
		}

		echo '</li>';
	}

	echo '</ul>';
}

remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('admin_print_scripts', 'print_emoji_detection_script');
remove_action('wp_print_styles', 'print_emoji_styles');
remove_action('admin_print_styles', 'print_emoji_styles');
remove_action('wp_head', 'wp_oembed_add_discovery_links');
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wlwmanifest_link');
