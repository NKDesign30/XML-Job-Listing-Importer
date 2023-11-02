<?php
// Sicherstellen, dass diese Datei nicht direkt aufgerufen wird
if (!defined('ABSPATH')) {
  exit;
}

function wp_xml_jli_create_post($job)
{
  // Erstelle ein Array mit den Beitragseigenschaften
  $post_data = array(
    'post_title'    => sanitize_text_field((string)$job->title),
    'post_content'  => sanitize_textarea_field((string)$job->description),
    'post_status'   => 'publish',
    'post_author'   => get_current_user_id(),
    'post_type'     => 'job_listing',
    // Fügen Sie hier weitere benutzerdefinierte Felder hinzu, falls benötigt
  );

  // Füge den Beitrag in die Datenbank ein
  $post_id = wp_insert_post($post_data);

  // Überprüfe auf Fehler
  if (is_wp_error($post_id)) {
    wp_die('Fehler beim Erstellen des Job-Listings: ' . $post_id->get_error_message());
  }

  // Hier können Sie zusätzliche Aktionen durchführen, wie z.B. das Setzen von Taxonomien oder Meta-Daten
}
