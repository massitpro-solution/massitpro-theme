<?php
/**
 * Generic page template.
 *
 * @package MassITPro
 */

get_header();

if (have_posts()) {
	while (have_posts()) {
		the_post();

		$page_path       = massitpro_normalize_path(get_page_uri(get_the_ID()));
		$context         = massitpro_get_page_context_from_path($page_path);
		$service_entry   = massitpro_get_canonical_service_by_path($page_path);
		$industry_entry  = massitpro_get_canonical_industry_by_path($page_path);
		$location_entry  = massitpro_get_canonical_location_by_path($page_path);

		massitpro_render_context_page(
			$context,
			[
				'service'  => $service_entry,
				'industry' => $industry_entry,
				'location' => $location_entry,
			]
		);
	}
}

get_footer();
