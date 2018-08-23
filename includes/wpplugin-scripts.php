<?php
//Conditionally load JS on plugin settings page only
function wpplugin_admin_scripts($hook){
	 /** @scrutinizer ignore-call */ wp_register_script(
		'wpplugin-admin',
		WPPLUGIN_URL.'admin/js/wpplugin-admin.js',
		['jquery'],
		time()
	);
	 /** @scrutinizer ignore-call */ wp_localize_script('wpplugin-admin','wpplugin',[
		'hook'=>$hook
	]);
	if('toplevel_page_wpplugin'==$hook){
		/** @scrutinizer ignore-call */ wp_enqueue_script('wpplugin-admin');
	}
}
/** @scrutinizer ignore-call */ 
add_action('admin_enqueue_scripts','wpplugin_admin_scripts');
//Conditionally load JS on single post pages only on frontend
function wpplugin_frontend_scripts(){
	/** @scrutinizer ignore-call */ wp_register_script(
		'wpplugin-frontend',
		WPPLUGIN_URL.'frontend/js/wpplugin-frontend.js',
		[],
		time()
	);
	if(/** @scrutinizer ignore-call */ is_single()){
		/** @scrutinizer ignore-call */ 
		wp_enqueue_script('wpplugin-frontend');
	}
}
/** @scrutinizer ignore-call */ 
add_action('wp_enqueue_scripts','wpplugin_frontend_scripts',100);
