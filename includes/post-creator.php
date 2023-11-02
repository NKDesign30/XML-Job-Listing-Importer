<?php
// Sicherstellen, dass diese Datei nicht direkt aufgerufen wird
if (!defined('ABSPATH')) {
  exit;
}

function wp_xml_jli_create_post($job)
{
  $post_id = wp_insert_post(array(
    'post_title'    => sanitize_text_field($job->title),
    'post_content'  => sanitize_textarea_field($job->description),
    'post_status'   => 'publish',
    'post_author'   => get_current_user_id(),
    'post_type'     => 'job_listing'
  ));

  if (!$post_id) {
    wp_die('Fehler beim Erstellen des Job-Listings.');
  }
}
