<?php
/**
 * Allinea la landing Pilates alla capacità reale di cinque postazioni.
 *
 * @package BodyEnergyWordPress
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Corregge l'output dello shortcode storico senza modificare pagine o dati Elementor.
 *
 * @param string $output Output generato.
 * @param string $tag    Nome shortcode.
 * @return string
 */
function bodyenergy_correct_pilates_capacity_output($output, $tag)
{
    if ('bodyenergy_pilates_landing' !== $tag) {
        return $output;
    }

    return str_replace(
        array(
            'Solo quattro postazioni.',
            'Massimo quattro persone',
            '>04<',
        ),
        array(
            'Solo cinque postazioni.',
            'Massimo cinque persone',
            '>05<',
        ),
        $output
    );
}
add_filter('do_shortcode_tag', 'bodyenergy_correct_pilates_capacity_output', 20, 2);
