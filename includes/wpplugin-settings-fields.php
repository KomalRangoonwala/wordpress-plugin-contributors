<?php

function wpplugin_settings() {

  // If plugin settings don't exist, then create them
  if( !get_option( 'wpplugin_settings' ) ) {
      add_option( 'wpplugin_settings' );
  }

  // Define (at least) one section for our fields
  add_settings_section(
    // Unique identifier for the section
    'wpplugin_settings_section',
    // Section Title
    __( 'Settings Section', 'wpplugin' ),
    // Callback for an optional description
    'wpplugin_settings_section_callback',
    // Admin page to add section to
    'wpplugin'
  );

   // Checkbox Field
  add_settings_field(
    'wpplugin_settings_checkbox',
    __( 'Check to enable the Contributors meta-box', 'wpplugin'),
    'wpplugin_settings_checkbox_callback',
    'wpplugin',
    'wpplugin_settings_section',
    [
      'label1' => 'Post and pages',
    ]
  );



  register_setting(
    'wpplugin_settings',
    'wpplugin_settings'
  );

}
add_action( 'admin_init', 'wpplugin_settings' );

function wpplugin_settings_section_callback() {

  esc_html_e( 'You can control whether the Contributors meta-box should be displayed or not.', 'wpplugin' );

}

function wpplugin_settings_checkbox_callback( $args ) {

  $options = get_option( 'wpplugin_settings' );

  $checkbox = '';
  if( isset( $options[ 'checkbox' ] ) ) {
    $checkbox = esc_html( $options['checkbox'] );
  }

  $html = '<input type="checkbox" id="wpplugin_settings_checkbox" name="wpplugin_settings[checkbox]" value="1"' . checked( 1, $checkbox, false ) . '/>';
  $html .= '&nbsp;';
  $html .= '<label for="wpplugin_settings_checkbox">' . $args['label1'] . '</label>';

  echo $html;

}
