<?php
/**
 * Shared theme data that is safe to keep in code.
 *
 * @package MassITPro
 */

if (! defined('ABSPATH')) {
	exit;
}

/**
 * Default global settings used only when admin data is empty.
 *
 * @return array<string,string>
 */
function massitpro_get_theme_defaults() {
	return [
		'phone'         => '',
		'email'         => '',
		'business_hours'=> '',
		'service_area'  => '',
	];
}

/**
 * Primary fallback navigation groups.
 *
 * @return array<int,array<string,mixed>>
 */
function massitpro_get_nav_links() {
	$pages = massitpro_get_canonical_page_entries();

	return [
		[
			'label'    => __('Services', 'massitpro'),
			'path'     => $pages['services_hub']['path'],
			'children' => array_merge(
				[
					['label' => __('Business', 'massitpro'), 'path' => $pages['services_business']['path']],
					['label' => __('Residential', 'massitpro'), 'path' => $pages['services_residential']['path']],
				],
				array_values(massitpro_get_canonical_service_entries())
			),
		],
		[
			'label'    => __('Industries', 'massitpro'),
			'path'     => $pages['industries_hub']['path'],
			'children' => array_values(massitpro_get_canonical_industry_entries()),
		],
		[
			'label'    => __('Locations', 'massitpro'),
			'path'     => $pages['locations_hub']['path'],
			'children' => array_values(massitpro_get_canonical_location_entries()),
		],
		[
			'label'    => __('About', 'massitpro'),
			'path'     => $pages['about']['path'],
			'children' => [
				['label' => $pages['testimonials']['label'], 'path' => $pages['testimonials']['path']],
				['label' => $pages['faq']['label'], 'path' => $pages['faq']['path']],
				['label' => $pages['projects']['label'], 'path' => $pages['projects']['path']],
			],
		],
		[
			'label' => $pages['blog']['label'],
			'path'  => $pages['blog']['path'],
		],
		[
			'label' => $pages['contact']['label'],
			'path'  => $pages['contact']['path'],
		],
	];
}

/**
 * Footer link groups.
 *
 * @return array<string,array<int,array<string,mixed>>>
 */
function massitpro_get_footer_link_groups() {
	$pages = massitpro_get_canonical_page_entries();

	return [
		__('Main', 'massitpro')        => [
			['label' => $pages['home']['label'], 'path' => $pages['home']['path']],
			['label' => $pages['about']['label'], 'path' => $pages['about']['path']],
			['label' => $pages['blog']['label'], 'path' => $pages['blog']['path']],
			['label' => $pages['contact']['label'], 'path' => $pages['contact']['path']],
		],
		__('About', 'massitpro')       => [
			['label' => $pages['testimonials']['label'], 'path' => $pages['testimonials']['path']],
			['label' => $pages['faq']['label'], 'path' => $pages['faq']['path']],
			['label' => $pages['projects']['label'], 'path' => $pages['projects']['path']],
		],
		__('Business', 'massitpro')    => massitpro_get_canonical_services_by_group('business'),
		__('Residential', 'massitpro') => massitpro_get_canonical_services_by_group('residential'),
		__('Industries', 'massitpro')  => array_values(massitpro_get_canonical_industry_entries()),
		__('Locations', 'massitpro')   => array_values(massitpro_get_canonical_location_entries()),
	];
}
