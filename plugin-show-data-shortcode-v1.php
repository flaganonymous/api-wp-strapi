<?php
/*
Plugin Name: Mon plugin API
Description: Ce plugin interroge une API externe et affiche les données reçues dans un tableau via un shortcode.
*/

// Ajoutez un shortcode pour afficher le tableau des données de l'API
add_shortcode('mon_plugin_api_table', 'mon_plugin_api_table_shortcode');

// Fonction pour interroger l'API et retourner les données
function mon_plugin_api_get_data() {
    // Utilisez cURL ou file_get_contents() pour interroger l'API
    $api_url = 'http://3b27caufcj.preview.infomaniak.website/api/events';
    $response = wp_remote_get( $api_url );
    $response_code = wp_remote_retrieve_response_code( $response );
    $data = json_decode( wp_remote_retrieve_body( $response ) );
    if( $response_code == 200 ){
        return $data;
    }
    else{
        return false;
    }
}

// Fonction pour afficher le tableau des données de l'API via un shortcode
function mon_plugin_api_table_shortcode() {
    $data = mon_plugin_api_get_data();
    if($data){
        $output = '<table>';
        $output .= '<thead><tr>';
        $output .= '<th>Nom</th>';
        $output .= '<th>Description</th>';
        $output .= '<th>Date</th>';
        $output .= '</tr></thead>';
        $output .= '<tbody>';
        foreach ($data as $event) {
            $output .= '<tr>';
            $output .= '<td>'.$event->name.'</td>';
            $output .= '<td>'.$event->description.'</td>';
            $output .= '<td>'.$event->date.'</td>';
            $output .= '</tr>';
        }
        $output .= '</tbody>';
        $output .= '</table>';
    }else{
        $output = "Il y a eu une erreur lors de la récupération des données.";
    }
    return $output;
}
