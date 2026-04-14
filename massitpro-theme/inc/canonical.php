<?php
/**
 * Canonical route registry and page-context helpers.
 *
 * @package MassITPro
 */

if (! defined('ABSPATH')) {
	exit;
}

/**
 * Normalize a path for comparisons.
 *
 * @param string $path Path value.
 * @return string
 */
function massitpro_normalize_path($path) {
	return trim((string) $path, '/');
}

/**
 * Canonical top-level pages.
 *
 * @return array<string,array<string,mixed>>
 */
function massitpro_get_canonical_page_entries() {
	return [
		'home'                 => [
			'label' => __('Home', 'massitpro'),
			'path'  => '',
		],
		'about'                => [
			'label' => __('About', 'massitpro'),
			'path'  => 'about-it-company-in-massachusetts',
		],
		'testimonials'         => [
			'label' => __('Testimonials', 'massitpro'),
			'path'  => 'testimonial',
		],
		'faq'                  => [
			'label' => __('FAQs', 'massitpro'),
			'path'  => 'faqs',
		],
		'projects'             => [
			'label' => __('Projects', 'massitpro'),
			'path'  => 'projects',
		],
		'industries_hub'       => [
			'label' => __('Industries', 'massitpro'),
			'path'  => 'industries',
		],
		'services_hub'         => [
			'label' => __('Services', 'massitpro'),
			'path'  => 'services',
		],
		'services_business'    => [
			'label' => __('Business', 'massitpro'),
			'path'  => 'services/business',
		],
		'services_residential' => [
			'label' => __('Residential', 'massitpro'),
			'path'  => 'services/residential',
		],
		'locations_hub'        => [
			'label' => __('Locations', 'massitpro'),
			'path'  => 'it-support-across-massachusetts-service-areas',
		],
		'contact'              => [
			'label' => __('Contact', 'massitpro'),
			'path'  => 'contactus',
		],
		'blog'                 => [
			'label' => __('Blog', 'massitpro'),
			'path'  => 'blog',
		],
	];
}

/**
 * Canonical service detail pages.
 *
 * @return array<string,array<string,mixed>>
 */
function massitpro_get_canonical_service_entries() {
	return [
		'managed_it_services'              => [
			'label'  => __('Managed IT Services', 'massitpro'),
			'path'   => 'services/managed-it-services',
			'groups' => ['business'],
		],
		'compliance'                       => [
			'label'  => __('Compliance', 'massitpro'),
			'path'   => 'services/compliance',
			'groups' => ['business'],
		],
		'it_support_and_help_desk'         => [
			'label'  => __('IT Support and Help Desk', 'massitpro'),
			'path'   => 'services/it-support-and-help-desk',
			'groups' => ['business'],
		],
		'cybersecurity_endpoint'           => [
			'label'  => __('Cybersecurity and Endpoint', 'massitpro'),
			'path'   => 'services/cybersecurity-endpoint-protection',
			'groups' => ['business'],
		],
		'business_network_solution'        => [
			'label'  => __('Business Network Solution', 'massitpro'),
			'path'   => 'services/networking-solutions',
			'groups' => ['business'],
		],
		'microsoft_365_cloud_solutions'    => [
			'label'  => __('Microsoft 365 and Cloud Solutions', 'massitpro'),
			'path'   => 'services/microsoft-365-cloud-solutions',
			'groups' => ['business'],
		],
		'backup_disaster_recovery'         => [
			'label'  => __('Backup and Disaster Recovery', 'massitpro'),
			'path'   => 'services/backup-disaster-recovery',
			'groups' => ['business'],
		],
		'remote_it_support_services'       => [
			'label'  => __('Remote Support', 'massitpro'),
			'path'   => 'services/remote-it-support-services',
			'groups' => ['business', 'residential'],
		],
		'web_design_massachusetts'         => [
			'label'  => __('Websites', 'massitpro'),
			'path'   => 'services/web-design-massachusetts',
			'groups' => ['business'],
		],
		'pc_and_mac_repair'                => [
			'label'  => __('PC and Mac Repair', 'massitpro'),
			'path'   => 'services/pc-and-mac-repair',
			'groups' => ['residential'],
		],
		'virus_removal_services'           => [
			'label'  => __('Virus Removal', 'massitpro'),
			'path'   => 'services/virus-removal-services',
			'groups' => ['residential'],
		],
		'os_upgrade_services'              => [
			'label'  => __('Operating System Upgrades', 'massitpro'),
			'path'   => 'services/os-upgrade-services',
			'groups' => ['residential'],
		],
		'home_automation_services'         => [
			'label'  => __('Smart Home Solution', 'massitpro'),
			'path'   => 'services/home-automation-services',
			'groups' => ['residential'],
		],
		'home_wifi_network_help'           => [
			'label'  => __('Home Wifi and Network Help', 'massitpro'),
			'path'   => 'services/home-wifi-network-help',
			'groups' => ['residential'],
		],
		'data_recovery_massachusetts'      => [
			'label'  => __('Data Recovery', 'massitpro'),
			'path'   => 'services/data-recovery-massachusetts',
			'groups' => ['residential'],
		],
	];
}

/**
 * Canonical industry detail pages.
 *
 * @return array<string,array<string,mixed>>
 */
function massitpro_get_canonical_industry_entries() {
	return [
		'law_firms'                    => [
			'label' => __('Law Firms', 'massitpro'),
			'path'  => 'industries/law-firms',
		],
		'construction_trades'         => [
			'label' => __('Construction and Trading', 'massitpro'),
			'path'  => 'industries/construction-trades',
		],
		'accounting_financial'        => [
			'label' => __('Accounting and Financial', 'massitpro'),
			'path'  => 'industries/accounting-financial',
		],
		'medical_dental'              => [
			'label' => __('Medical and Dental', 'massitpro'),
			'path'  => 'industries/medical-dental',
		],
		'nonprofits'                  => [
			'label' => __('Non Profits', 'massitpro'),
			'path'  => 'industries/nonprofits',
		],
		'professional_services_smb'   => [
			'label' => __('Professional Services, SMB, Local Offices', 'massitpro'),
			'path'  => 'industries/professional-services-smb',
		],
	];
}

/**
 * Canonical location detail pages.
 *
 * @return array<string,array<string,mixed>>
 */
function massitpro_get_canonical_location_entries() {
	return [
		'boston'     => [
			'label' => __('Boston', 'massitpro'),
			'path'  => 'it-support-across-massachusetts-service-areas/boston',
		],
		'cambridge'  => [
			'label' => __('Cambridge', 'massitpro'),
			'path'  => 'it-support-across-massachusetts-service-areas/cambridge',
		],
		'waltham'    => [
			'label' => __('Waltham', 'massitpro'),
			'path'  => 'it-support-across-massachusetts-service-areas/waltham',
		],
		'framingham' => [
			'label' => __('Framingham', 'massitpro'),
			'path'  => 'it-support-across-massachusetts-service-areas/framingham',
		],
		'worcester'  => [
			'label' => __('Worcester', 'massitpro'),
			'path'  => 'it-support-across-massachusetts-service-areas/worchester',
		],
		'brookline'  => [
			'label' => __('Brookline', 'massitpro'),
			'path'  => 'it-support-across-massachusetts-service-areas/brookline',
		],
		'wellesley'  => [
			'label' => __('Wellesley', 'massitpro'),
			'path'  => 'it-support-across-massachusetts-service-areas/wellesley',
		],
		'dedham'     => [
			'label' => __('Dedham', 'massitpro'),
			'path'  => 'it-support-across-massachusetts-service-areas/dedham',
		],
		'needham'    => [
			'label' => __('Needham', 'massitpro'),
			'path'  => 'it-support-across-massachusetts-service-areas/needham',
		],
		'lowell'     => [
			'label' => __('Lowell', 'massitpro'),
			'path'  => 'it-support-across-massachusetts-service-areas/lowell',
		],
		'newton'     => [
			'label' => __('Newton', 'massitpro'),
			'path'  => 'it-support-across-massachusetts-service-areas/newton',
		],
	];
}

/**
 * Build a canonical URL from a path.
 *
 * @param string $path Relative site path.
 * @return string
 */
function massitpro_canonical_url_from_path($path) {
	$path = massitpro_normalize_path($path);

	return '' === $path ? home_url('/') : home_url('/' . $path . '/');
}

/**
 * Fetch a canonical page entry by key.
 *
 * @param string $key Canonical page key.
 * @return array<string,mixed>|null
 */
function massitpro_get_canonical_page($key) {
	$pages = massitpro_get_canonical_page_entries();

	return $pages[$key] ?? null;
}

/**
 * Resolve a canonical page URL by key.
 *
 * @param string $key Canonical page key.
 * @return string
 */
function massitpro_get_canonical_page_url($key) {
	$page = massitpro_get_canonical_page($key);

	return $page ? massitpro_canonical_url_from_path($page['path']) : home_url('/');
}

/**
 * Lookup an item in a canonical registry by path.
 *
 * @param array<string,array<string,mixed>> $entries Canonical entries.
 * @param string                            $path    Current page path.
 * @return array<string,mixed>|null
 */
function massitpro_get_canonical_entry_by_path($entries, $path) {
	$path = massitpro_normalize_path($path);

	foreach ($entries as $key => $entry) {
		if ($path === massitpro_normalize_path($entry['path'])) {
			$entry['key'] = $key;
			return $entry;
		}
	}

	return null;
}

/**
 * Lookup a canonical service entry by path.
 *
 * @param string $path Current page path.
 * @return array<string,mixed>|null
 */
function massitpro_get_canonical_service_by_path($path) {
	return massitpro_get_canonical_entry_by_path(massitpro_get_canonical_service_entries(), $path);
}

/**
 * Lookup a canonical industry entry by path.
 *
 * @param string $path Current page path.
 * @return array<string,mixed>|null
 */
function massitpro_get_canonical_industry_by_path($path) {
	return massitpro_get_canonical_entry_by_path(massitpro_get_canonical_industry_entries(), $path);
}

/**
 * Lookup a canonical location entry by path.
 *
 * @param string $path Current page path.
 * @return array<string,mixed>|null
 */
function massitpro_get_canonical_location_by_path($path) {
	return massitpro_get_canonical_entry_by_path(massitpro_get_canonical_location_entries(), $path);
}

/**
 * Collect canonical services by group.
 *
 * @param string $group Service landing group.
 * @return array<int,array<string,mixed>>
 */
function massitpro_get_canonical_services_by_group($group) {
	$services = [];

	foreach (massitpro_get_canonical_service_entries() as $key => $entry) {
		if (in_array($group, (array) ($entry['groups'] ?? []), true)) {
			$entry['key'] = $key;
			$services[]   = $entry;
		}
	}

	return $services;
}

/**
 * Resolve a page context from a page path.
 *
 * @param string $page_path Page URI.
 * @return string
 */
function massitpro_get_page_context_from_path($page_path) {
	$page_path = massitpro_normalize_path($page_path);
	$pages     = massitpro_get_canonical_page_entries();

	if ($page_path === massitpro_normalize_path($pages['about']['path'])) {
		return 'about';
	}

	if ($page_path === massitpro_normalize_path($pages['services_business']['path'])) {
		return 'services-business';
	}

	if ($page_path === massitpro_normalize_path($pages['services_residential']['path'])) {
		return 'services-residential';
	}

	if ($page_path === massitpro_normalize_path($pages['services_hub']['path'])) {
		return 'services-hub';
	}

	if (massitpro_get_canonical_service_by_path($page_path)) {
		return 'service-detail';
	}

	if ($page_path === massitpro_normalize_path($pages['industries_hub']['path'])) {
		return 'industries-hub';
	}

	if (massitpro_get_canonical_industry_by_path($page_path)) {
		return 'industry-detail';
	}

	if ($page_path === massitpro_normalize_path($pages['locations_hub']['path'])) {
		return 'locations-hub';
	}

	if (massitpro_get_canonical_location_by_path($page_path)) {
		return 'location-detail';
	}

	if ($page_path === massitpro_normalize_path($pages['contact']['path'])) {
		return 'contact';
	}

	if ($page_path === massitpro_normalize_path($pages['projects']['path'])) {
		return 'projects';
	}

	if ($page_path === massitpro_normalize_path($pages['testimonials']['path'])) {
		return 'testimonials';
	}

	if ($page_path === massitpro_normalize_path($pages['faq']['path'])) {
		return 'faq';
	}

	if ($page_path === massitpro_normalize_path($pages['blog']['path'])) {
		return 'blog';
	}

	return 'default';
}

/**
 * Resolve the saved page context for a page ID.
 *
 * @param int $post_id Page ID.
 * @return string
 */
function massitpro_get_page_context_for_post($post_id) {
	$post_id = (int) $post_id;

	if (! $post_id || 'page' !== get_post_type($post_id)) {
		return 'default';
	}

	if ((int) get_option('page_on_front') === $post_id) {
		return 'front-page';
	}

	return massitpro_get_page_context_from_path(get_page_uri($post_id));
}
