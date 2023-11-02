<?php
// Sicherstellen, dass diese Datei nicht direkt aufgerufen wird
if (!defined('ABSPATH')) {
  exit;
}

// Überprüfen, ob das Formular abgesendet wurde
if (isset($_POST['submit']) && isset($_FILES['xml_job_listing']) && wp_verify_nonce($_POST['_wpnonce'], 'wp_xml_jli_upload_nonce')) {
  require_once(plugin_dir_path(__FILE__) . 'post-creator.php');
  $uploadedFile = $_FILES['xml_job_listing'];

  // Überprüfen, ob es sich um eine XML-Datei handelt
  if ($uploadedFile['type'] == 'text/xml' || $uploadedFile['type'] == 'application/xml') {
    $xml_content = file_get_contents($uploadedFile['tmp_name']);
    $xml = simplexml_load_string($xml_content);
    if ($xml) {
      foreach ($xml->job as $job) {
        wp_xml_jli_create_post($job);
      }
      wp_redirect(admin_url('edit.php?post_type=job_listing'));
      exit;
    } else {
      wp_die('Fehler beim Parsen der XML-Datei.');
    }
  } else {
    wp_die('Bitte laden Sie eine gültige XML-Datei hoch.');
  }
}
