<?php
function wpplugin_settings_page()
{
	/** @scrutinizer ignore-call */ add_menu_page(
	/** @scrutinizer ignore-call */ __('Plugin Name', 'wpplugin'),
		/** @scrutinizer ignore-call */ __('Plugin Menu', 'wpplugin'),
		'manage_options',
		'wpplugin',
		'wpplugin_settings_page_markup',
		'dashicons-image-filter',
		100
	);
}
/** @scrutinizer ignore-call */ 
add_action('admin_menu', 'wpplugin_settings_page');
function wpplugin_settings_page_markup()
{
	if (!/** @scrutinizer ignore-call */ current_user_can('manage_options')) {
		return;
	}
	include(WPPLUGIN_DIR.'templates/admin/settings-page.php');
}

function wpplugin_default_subpage() {
	/** @scrutinizer ignore-call */ add_dashboard_page(
		/** @scrutinizer ignore-call */ __('Cool default sub page', 'wpplugin'),
		/** @scrutinizer ignore-call */ __('Custom sub page', 'wpplugin'),
		'manage_options',
		'wpplugin-subpage',
		'wpplugin_settings_page_markup'
	);
}
/** @scrutinizer ignore-call */ 
add_action('admin_menu', 'wpplugin_default_subpage');
//Add a link to your settings page in your plugin
function wpplugin_add_settings_link($links) {
	$settings_link='<a href="admin.php?page=wpplugin">'./** @scrutinizer ignore-call */ __('Settings', 'wpplugin').'</a>';
	array_push($links, $settings_link); 
	return $links;
}
$filter_name="plugin_action_links_"./** @scrutinizer ignore-call */ plugin_basename(__FILE__);
/** @scrutinizer ignore-call */ 
add_filter($filter_name, 'wpplugin_add_settings_link');
