<?php
/**
 * Asset e comportamento dell'Hero Platinum della nuova Home.
 *
 * Interviene esclusivamente sulla pagina di staging ID 113 e non modifica
 * i dati Elementor salvati nel database.
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * GUID VideoPress ricavato dall'URL originale fornito dal proprietario.
 */
function bodyenergy_home_platinum_videopress_guid()
{
    return 'gUAnLMKw';
}

/**
 * URL ufficiale del player VideoPress.
 *
 * Il file originale è un MOV QuickTime, non affidabile come sorgente HTML5
 * diretta su Edge/Chrome. VideoPress serve invece la versione transcodificata
 * compatibile con il browser.
 */
function bodyenergy_home_platinum_videopress_url()
{
    return add_query_arg(
        array(
            'autoPlay' => 'true',
            'cover' => 'true',
            'controls' => 'false',
            'hd' => 'true',
            'loop' => 'true',
            'muted' => 'true',
            'persistVolume' => 'false',
            'playsinline' => 'true',
            'preloadContent' => 'auto',
            'resizeToParent' => 'true',
            'useAverageColor' => 'false',
        ),
        'https://videopress.com/v/' . bodyenergy_home_platinum_videopress_guid()
    );
}

/**
 * Restituisce la migliore immagine già configurata nel tema.
 */
function bodyenergy_home_platinum_image_url()
{
    $header_image = function_exists('get_header_image') ? get_header_image() : '';

    if (is_string($header_image) && '' !== $header_image) {
        return $header_image;
    }

    $background_image = function_exists('get_background_image') ? get_background_image() : '';

    if (is_string($background_image) && '' !== $background_image) {
        return $background_image;
    }

    return '';
}

/**
 * Carica gli asset solo sulla nuova Home in bozza.
 */
function bodyenergy_home_platinum_enqueue_assets()
{
    if (!is_page(113)) {
        return;
    }

    $base_url = plugin_dir_url(BODYENERGY_WORDPRESS_FILE);

    wp_enqueue_style(
        'bodyenergy-home-platinum',
        $base_url . 'assets/css/home-platinum.css',
        array(),
        BODYENERGY_WORDPRESS_VERSION
    );

    wp_enqueue_script(
        'bodyenergy-home-platinum',
        $base_url . 'assets/js/home-platinum.js',
        array(),
        BODYENERGY_WORDPRESS_VERSION,
        true
    );

    wp_localize_script(
        'bodyenergy-home-platinum',
        'BodyEnergyPlatinumHome',
        array(
            'imageUrl' => bodyenergy_home_platinum_image_url(),
            'videoPressGuid' => bodyenergy_home_platinum_videopress_guid(),
            'videoPressUrl' => bodyenergy_home_platinum_videopress_url(),
        )
    );
}
add_action('wp_enqueue_scripts', 'bodyenergy_home_platinum_enqueue_assets', 90);
