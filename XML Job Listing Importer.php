<?php
/*
Plugin Name: XML Job Listing Importer
Description: Ein Plugin zum Importieren von XML-Dateien und zur automatischen Beitragserstellung als 'job_listing'.
Version: 1.0
Author: Niko
Author URI: https://github.com/NKDesign30
*/

// Admin-Menü hinzufügen
add_action('admin_menu', 'xmljli_add_admin_menu');
function xmljli_add_admin_menu()
{
  add_menu_page('XML Job Listing Upload', 'XML Job Listing Upload', 'manage_options', 'xml-job-listing-upload', 'xmljli_upload_page', 'dashicons-upload', 6);
}
// Upload-Seite rendern
function xmljli_upload_page()
{
?>
  <div class="wrap">
    <h1>XML Job Listing Importer</h1>
    <form method="post" enctype="multipart/form-data">
      <?php wp_nonce_field('xmljli_upload_nonce', '_wpnonce'); ?>
      <input type="file" name="xml_file" accept=".xml" required>
      <input type="submit" name="import_xml" value="Importieren" class="button button-primary">
    </form>
  </div>
<?php

  // Überprüfen, ob das Formular abgesendet wurde
  if (isset($_POST['import_xml']) && check_admin_referer('xmljli_upload_nonce', '_wpnonce')) {
    $file = $_FILES['xml_file'];
    if ($file['type'] === 'text/xml' || $file['type'] === 'application/xml') {
      // Hier würden Sie den Code zum Verarbeiten der XML-Datei einfügen
      xmljli_process_xml($file['tmp_name']);
    } else {
      echo '<div class="error"><p>Bitte laden Sie eine gültige XML-Datei hoch.</p></div>';
    }
  }
}

// Enqueue Admin CSS
add_action('admin_enqueue_scripts', 'xmljli_enqueue_admin_styles');
function xmljli_enqueue_admin_styles()
{
  wp_enqueue_style('xmljli-admin-style', plugin_dir_url(__FILE__) . 'assets/css/admin-style.css');
}

// Kurzcode für das Upload-Formular
function xmljli_upload_form_shortcode()
{
  // Überprüfen, ob der Benutzer eingeloggt ist
  if (!is_user_logged_in()) {
    return 'Bitte melden Sie sich an, um eine Stellenanzeige hochzuladen.';
  }

  $output = ''; // Variable für die Ausgabe

  // Wenn das Formular abgesendet wurde
  if (isset($_POST['submit']) && isset($_FILES['xmlFile']) && wp_verify_nonce($_POST['_wpnonce'], 'xmljli_upload_nonce')) {

    $uploadedFile = $_FILES['xmlFile'];

    // Überprüfen Sie die Dateierweiterung
    $file_extension = pathinfo($uploadedFile['name'], PATHINFO_EXTENSION);
    if (strtolower($file_extension) == 'xml') {

      // Lese den Inhalt der XML-Datei
      $xml_content = file_get_contents($uploadedFile['tmp_name']);
      $xml = simplexml_load_string($xml_content);
      if ($xml === false) {
        $output .= "Fehler beim Laden der XML-Datei.";
      } else {
        // Verarbeiten Sie hier die XML-Daten und erstellen Sie Beiträge
        foreach ($xml->job as $job_item) {
          $post_id = wp_insert_post(array(
            'post_title'    => (string) $job_item->title,
            'post_content'  => (string) $job_item->description,
            'post_status'   => 'publish',
            'post_author'   => get_current_user_id(),
            'post_type'     => 'job_listing'
            // Fügen Sie hier weitere Felder hinzu, wie z.B. 'post_category' oder benutzerdefinierte Felder
          ));

          if ($post_id) {
            // Fügen Sie hier Code hinzu, um benutzerdefinierte Felder oder Taxonomien zu aktualisieren
            $output .= "Stellenanzeige erfolgreich erstellt!";
          } else {
            $output .= "Fehler beim Erstellen der Stellenanzeige.";
          }
        }
      }
    } else {
      $output .= "Bitte laden Sie nur XML-Dateien hoch.";
    }
  }

  ob_start(); // Starte die Ausgabepufferung

  // Ihr Formularcode hier
  $output .= '
    <form action="" method="post" enctype="multipart/form-data">
        ' . wp_nonce_field('xmljli_upload_nonce', '_wpnonce', true, false) . '
        <label for="xmlFile">Wählen Sie die XML-Datei zum Hochladen aus:</label>
        <input type="file" name="xmlFile" id="xmlFile" accept=".xml">
        <input type="submit" name="submit" value="Hochladen">
    </form>';

  return ob_get_clean(); // Gib den generierten Inhalt zurück
}
add_shortcode('xmljli_upload_form', 'xmljli_upload_form_shortcode');

// Weitere Funktionen und Handler können hier hinzugefügt werden...
