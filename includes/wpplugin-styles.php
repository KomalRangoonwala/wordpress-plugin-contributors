<?php

		function wpplugin_admin_styles(){
		wp_enqueue_style(
			'wpplugin-admin',
			WPPLUGIN_URL.'admin/css/wpplugin-admin-style.css',
			[],
			time()
		);
	}
	
add_action('admin_enqueue_scripts', 'wpplugin_admin_styles');

//load CSS on frontend
	function wpplugin_frontend_styles(){
		wp_enqueue_style(
			'wpplugin-frontend',
			WPPLUGIN_URL.'frontend/css/wpplugin-frontend-style.css',
			[],
			time()
		);
	}
	add_action('wp_enqueue_scripts','wpplugin_frontend_styles',100);
