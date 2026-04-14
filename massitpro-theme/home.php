<?php
/**
 * Blog index template.
 *
 * @package MassITPro
 */

get_header();

massitpro_render_context_page(
	'blog',
	[
		'post_id' => (int) get_option('page_for_posts'),
	]
);

get_footer();
