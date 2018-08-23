<div class="wrap">
	<h1><?php /** @scrutinizer ignore-call */ esc_html_e(/** @scrutinizer ignore-call */ get_admin_page_title()); ?></h1>

	  <form method="post" action="options.php">

	<!-- Display necessary hidden fields for settings -->
    <?php /** @scrutinizer ignore-call */ settings_fields('wpplugin_settings'); ?>
    <!-- Display the settings sections for the page -->
    <?php /** @scrutinizer ignore-call */ do_settings_sections('wpplugin'); ?>
    <!-- Default Submit Button -->
    <?php /** @scrutinizer ignore-call */ submit_button(); ?>
  </form>
</div>
