<?php
function wpplugin_settings_page()
{
	add_menu_page(
	__('Plugin Name','wpplugin'),
		__('Plugin Menu','wpplugin'),
		'manage_options',
		'wpplugin',
		'wpplugin_settings_page_markup',
		'dashicons-image-filter',
		100
	);
}
add_action('admin_menu','wpplugin_settings_page');
function wpplugin_settings_page_markup()
{
	if(! current_user_can('manage_options')){
		return;
	}
	include(WPPLUGIN_DIR.'templates/admin/settings-page.php');
}

function wpplugin_default_subpage(){
	add_dashboard_page(
		__('Cool default sub page','wpplugin'),
		__('Custom sub page','wpplugin'),
		'manage_options',
		'wpplugin-subpage',
		'wpplugin_settings_page_markup'
	);
}
add_action('admin_menu','wpplugin_default_subpage');
//Add a link to your settings page in your plugin
function wpplugin_add_settings_link($links){
	$settings_link='<a href="admin.php?page=wpplugin">'. __('Settings','wpplugin').'</a>';
	array_push($links,$settings_link); 
	return $links;
}
$filter_name="plugin_action_links_". plugin_basename(__FILE__);
add_filter($filter_name,'wpplugin_add_settings_link');
