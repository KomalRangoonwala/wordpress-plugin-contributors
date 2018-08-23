<?php
//conditionally loading CSS on plugin settings page only

	function wpplugin_admin_styles($hook){
		/** @scrutinizer ignore-call */ wp_register_style(
			'wpplugin-admin',
			WPPLUGIN_URL.'admin/css/wpplugin-admin-style.css',
			[],
			time()
		);
	
	if('toplevel_page_wpplugin'==$hook 
		OR 'wpplugin_page_wpplugin-feature-1'==$hook
		){
		
		/** @scrutinizer ignore-call */ wp_enqueue_style('wpplugin-admin');
	}
}
/** @scrutinizer ignore-call */ 

add_action('admin_enqueue_scripts','wpplugin_admin_styles');

//load CSS on frontend
	function wpplugin_frontend_styles( /** @scrutinizer ignore-unused */ $hook){
		
		/** @scrutinizer ignore-call */ wp_register_style(
			'wpplugin-frontend',
			WPPLUGIN_URL.'frontend/css/wpplugin-frontend-style.css',
			[],
			time()
		);
	
	if(/** @scrutinizer ignore-call */ is_single()){
		/** @scrutinizer ignore-call */ wp_enqueue_style('wpplugin-frontend');
	}
}
/** @scrutinizer ignore-call */ 
add_action('wp_enqueue_scripts','wpplugin_frontend_styles',100);
