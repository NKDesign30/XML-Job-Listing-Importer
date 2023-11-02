<?php
/*
Plugin Name: WP XML Job Listing Integration
Description: Ein Plugin zum Hochladen von Job-Listings als XML und zur automatischen Beitragserstellung.
Version: 1.0
Author: Niko
*/

// Admin-Menü hinzufügen
add_action('admin_menu', 'wp_xml_jli_add_admin_menu');
function wp_xml_jli_add_admin_menu()
{
  add_menu_page('XML Job Listing Upload', 'XML Job Listing Upload', 'manage_options', 'xml-job-listing-upload', 'wp_xml_jli_upload_page', 'dashicons-upload', 6);
}

// Upload-Seite rendern
function wp_xml_jli_upload_page()
{
  include(plugin_dir_path(__FILE__) . 'includes/xml-upload-handler.php');
}

// Enqueue Admin CSS
add_action('admin_enqueue_scripts', 'wp_xml_jli_enqueue_admin_styles');
function wp_xml_jli_enqueue_admin_styles()
{
  wp_enqueue_style('wp-xml-jli-admin-style', plugin_dir_url(__FILE__) . 'assets/css/admin-style.css');
}

// Shortcode für das Upload-Formular
add_shortcode('wp_xml_jli_upload_form', 'wp_xml_jli_upload_form_shortcode');
function wp_xml_jli_upload_form_shortcode()
{
  if (!is_user_logged_in()) {
    return 'Bitte melden Sie sich an, um eine Stellenanzeige hochzuladen.';
  }

  ob_start();
?>
  <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post" enctype="multipart/form-data">
    <?php wp_nonce_field('wp_xml_jli_upload_nonce', '_wpnonce'); ?>
    <label for="xml_job_listing">Wählen Sie die XML-Datei zum Hochladen aus:</label>
    <input type="file" name="xml_job_listing" id="xml_job_listing" accept=".xml">
    <input type="hidden" name="action" value="xml_job_listing_upload">
    <input type="submit" name="submit" value="Hochladen">
  </form>
<?php
  return ob_get_clean();
}

// Action-Hook für das Verarbeiten des Uploads
add_action('admin_post_xml_job_listing_upload', 'wp_xml_jli_handle_upload');
function wp_xml_jli_handle_upload()
{
  include(plugin_dir_path(__FILE__) . 'includes/xml-upload-handler.php');
}
?>