<?php
/**
 * SEO integration: feed Mass IT Pro custom field content
 * into Rank Math content analysis via the JS API.
 *
 * @package MassITPro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueue the Rank Math content analysis integration script
 * only on admin pages where Rank Math is active.
 */
function massitpro_enqueue_rankmath_integration() {
	if ( ! is_admin() ) {
		return;
	}

	$screen = get_current_screen();

	if ( ! $screen ) {
		return;
	}

	// Only load on post/page edit screens
	if ( ! in_array( $screen->base, [ 'post', 'page' ], true ) ) {
		return;
	}

	// Only load if Rank Math is active
	if ( ! defined( 'RANK_MATH_VERSION' ) ) {
		return;
	}

	wp_enqueue_script(
		'massitpro-rankmath-integration',
		get_template_directory_uri() . '/assets/js/rank-math-integration.js',
		[ 'wp-hooks', 'rank-math-analyzer' ],
		'1.0.0',
		true
	);
}
add_action( 'admin_enqueue_scripts', 'massitpro_enqueue_rankmath_integration' );
