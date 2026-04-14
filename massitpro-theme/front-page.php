<?php
/**
 * Front page template.
 *
 * @package MassITPro
 */

get_header();

if (have_posts()) {
	while (have_posts()) {
		the_post();
		massitpro_render_homepage();
	}
}

get_footer();

