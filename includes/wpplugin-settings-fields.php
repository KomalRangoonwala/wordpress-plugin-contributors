  <?php

	function wpplugin_settings(){
		if(! get_option('wpplugin_settings')){
			 add_option('wpplugin_settings');
		}
	
		//define at least one section for our fields
		add_settings_section(
			//unique id for the section
			'wpplugin_settings_section',
			//section title
			 __('Plugin settings section','wpplugin'),
			//callback for an optional description
			'wpplugin_settings_section_callback',
			//admin page to add section to
			'wpplugin'
		);
	
		add_meta_box( 
			'my-meta-box-id', 
			'My First Meta Box', 
			'cd_meta_box_cb', 
			'post', 
			'normal', 
			'high' );
		register_setting(
			'wpplugin_settings',
			'wpplugin_settings'
		);

	}
	add_action('add_meta_boxes','cd_meta_box_add');
	add_action('admin_init','wpplugin_settings');
	
	function wpplugin_settings_section_callback(){
		esc_html_e('Plugin settings section description','wpplugin');
	}
	function cd_meta_box_cb()
	{
    	echo 'What you put here, show\'s up in the meta box';   
	}

?>
