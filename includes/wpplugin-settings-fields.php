  <?php

	function wpplugin_settings(){
		if(! /** @scrutinizer ignore-call */ get_option('wpplugin_settings')){
			 /** @scrutinizer ignore-call */ add_option('wpplugin_settings');
		}
	
		//define at least one section for our fields
		/** @scrutinizer ignore-call */ 
		add_settings_section(
			//unique id for the section
			'wpplugin_settings_section',
			//section title
			 /** @scrutinizer ignore-call */ __('Plugin settings section','wpplugin'),
			//callback for an optional description
			'wpplugin_settings_section_callback',
			//admin page to add section to
			'wpplugin'
		);
	
		/** @scrutinizer ignore-call */ add_meta_box( 
			'my-meta-box-id', 
			'My First Meta Box', 
			'cd_meta_box_cb', 
			'post', 
			'normal', 
			'high' );
		/** @scrutinizer ignore-call */ 
		register_setting(
			'wpplugin_settings',
			'wpplugin_settings'
		);

	}
	/** @scrutinizer ignore-call */ 
	add_action('add_meta_boxes','cd_meta_box_add');
	add_action('admin_init','wpplugin_settings');
	
	function wpplugin_settings_section_callback(){
		/** @scrutinizer ignore-call */ esc_html_e('Plugin settings section description','wpplugin');
	}
	function cd_meta_box_cb()
	{
    	echo 'What you put here, show\'s up in the meta box';   
	}
