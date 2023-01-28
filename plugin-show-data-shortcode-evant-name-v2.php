<?php
/*
Plugin Name: Mon plugin Strapi
Description: Ce plugin interroge l'API Strapi pour afficher les données d'un événement spécifique
*/

function mon_plugin_strapi_shortcode( $atts ) {
    $a = shortcode_atts( array(
        'nom_evenement' => '',
    ), $atts );
    // Appel à l'API Strapi pour récupérer les données de l'événement spécifié
    $response = wp_remote_get( 'http://3b27caufcj.preview.infomaniak.website/api/events?nom_evenement=' . $a['nom_evenement'] );
    $data = json_decode( $response['body'], true );
    // Vérification de la réponse de l'API
    if ( is_array( $data ) && ! empty( $data ) ) {
        // Affichage des données dans un tableau
        $output = '<table>';
        $output .= '<tr><th>Nom</th><th>Date</th><th>Lieu</th></tr>';
        foreach ( $data as $event ) {
            $output .= '<tr>';
            $output .= '<td>' . $event['nom'] . '</td>';
            $output .= '<td>' . $event['date'] . '</td>';
            $output .= '<td>' . $event['lieu'] . '</td>';
            $output .= '</tr>';
        }
        $output .= '</table>';
    } else {
        $output = 'Aucun événement trouvé avec ce nom.';
    }
    return $output;
}
add_shortcode( 'mon_plugin_strapi', 'mon_plugin_strapi_shortcode' );
