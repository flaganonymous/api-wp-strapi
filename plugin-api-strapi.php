<?php
/*
Plugin Name: Mon plugin API
Description: Ce plugin interroge une API externe et crée des articles à partir des données reçues.
*/

// Ajoutez une action pour exécuter une mise à jour de l'API lorsque l'administrateur active le plugin
register_activation_hook(__FILE__, 'mon_plugin_api_update');

// Ajoutez une action pour exécuter une mise à jour de l'API régulièrement
add_action('mon_plugin_api_update_event', 'mon_plugin_api_update');

// Ajoutez une action pour créer un événement programmé pour mettre à jour régulièrement l'API
register_activation_hook(__FILE__, 'mon_plugin_api_schedule_update');

// Fonction pour interroger l'API et créer des articles à partir des données reçues
function mon_plugin_api_update() {
    // Utilisez cURL ou file_get_contents() pour interroger l'API
    $api_url = 'http://3b27caufcj.preview.infomaniak.website/api/events';
    $response = wp_remote_get( $api_url );
    $response_code = wp_remote_retrieve_response_code( $response );
    $data = json_decode( wp_remote_retrieve_body( $response ) );
    if( $response_code == 200 ){
        // Parcourez les données reçues de l'API et créez des articles pour chaque entrée
        foreach ($data as $event) {
            $post_title = $event->name;
            $post_content = $event->description;
            $post_date = $event->date;
            $post_author = 1; // ID de l'utilisateur à attribuer comme auteur de l'article
            $post_status = 'publish'; // Statut de publication de l'article
            $post_type = 'post'; // Type de contenu de l'article
            
            // Créez l'article en utilisant les fonctions WordPress
            $post_id = wp_insert_post(array(
                'post_title' => $post_title,
                'post_content' => $post_content,
                'post_date' => $post_date,
                'post_author' => $post_author,
                'post_status' => $post_status,
                'post_type' => $post_type
            ));
        }
    }
