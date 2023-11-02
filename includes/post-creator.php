<?php
// Dies ist nur ein einfacher Prototyp für den Beitragsersteller.
// Es müssen weitere Logiken hinzugefügt werden, um die XML-Daten korrekt zu integrieren.

// Beispielcode zum Erstellen eines Beitrags aus XML-Daten:
function wp_xml_jli_create_post($job) {
    // Hier würden Sie die Daten aus dem $job SimpleXMLElement extrahieren
    // und sie in ein Array umwandeln, das von wp_insert_post verwendet werden kann.
    
    $post_data = array(
        'post_title'    => (string)$job->title, // Beispiel für die Umwandlung in einen String
        'post_content'  => (string)$job->description, // Beispiel für die Umwandlung in einen String
        'post_status'   => 'publish',
        'post_type'     => 'job_listing',
        'post_author'   => get_current_user_id(),
        // Weitere Felder wie 'meta_input' für benutzerdefinierte Felder könnten hier hinzugefügt werden
    );

    $post_id = wp_insert_post($post_data);
    if ($post_id) {
        // Hier könnten Sie zusätzliche Aktionen durchführen, wie das Setzen von Taxonomien oder Meta-Daten
    } else {
        // Fehlerbehandlung
    }
}
