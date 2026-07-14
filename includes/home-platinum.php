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
 * URL esatto del video indicato dal proprietario del sito.
 */
function bodyenergy_home_platinum_direct_video_url()
{
    return 'https://videos.files.wordpress.com/gUAnLMKw/copy_c96d5d8a-9b51-4560-9399-cf6ce1109395.mov';
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
 * Estrae il primo URL video valido da una stringa WordPress/Elementor.
 * Conservata per compatibilità con gli strumenti amministrativi esistenti.
 *
 * @param mixed $value Valore da analizzare.
 * @return string
 */
function bodyenergy_home_platinum_extract_video_url($value)
{
    if (!is_string($value) || '' === trim($value)) {
        return '';
    }

    $decoded = html_entity_decode(str_replace('\\/', '/', $value), ENT_QUOTES, 'UTF-8');
    $pattern = '~https?://[^"\'\s<>]+?\.(?:mp4|m4v|webm|ogv|ogg|mov)(?:\?[^"\'\s<>]*)?~i';

    if (preg_match($pattern, $decoded, $matches)) {
        return esc_url_raw($matches[0]);
    }

    return '';
}

/**
 * Converte un allegato video WordPress nei dati necessari all'Hero.
 * Usata anche dal pannello amministrativo Video Hero Home.
 *
 * @param int $attachment_id ID allegato.
 * @return array{url:string,mime:string,poster:string}|null
 */
function bodyenergy_home_platinum_attachment_video_data($attachment_id)
{
    $attachment_id = absint($attachment_id);

    if (!$attachment_id || 'attachment' !== get_post_type($attachment_id)) {
        return null;
    }

    $url = wp_get_attachment_url($attachment_id);

    if (!is_string($url) || '' === $url) {
        return null;
    }

    $mime = (string) get_post_mime_type($attachment_id);
    $filetype = wp_check_filetype($url);
    $extension = !empty($filetype['ext']) ? strtolower((string) $filetype['ext']) : '';
    $allowed_extensions = array('mp4', 'm4v', 'webm', 'ogv', 'ogg', 'mov');

    if (0 !== strpos($mime, 'video/') && !in_array($extension, $allowed_extensions, true)) {
        return null;
    }

    $poster = bodyenergy_home_platinum_image_url();
    $poster_id = (int) get_post_thumbnail_id($attachment_id);

    if ($poster_id) {
        $poster_url = wp_get_attachment_image_url($poster_id, 'full');
        if (is_string($poster_url) && '' !== $poster_url) {
            $poster = $poster_url;
        }
    }

    return array(
        'url' => esc_url_raw($url),
        'mime' => $mime ?: (!empty($filetype['type']) ? (string) $filetype['type'] : 'video/mp4'),
        'poster' => $poster,
    );
}

/**
 * Restituisce esclusivamente la sorgente video verificata e comunicata.
 * Nessuna ricerca automatica e nessuna selezione ambigua.
 *
 * @return array{url:string,mime:string,poster:string}
 */
function bodyenergy_home_platinum_video_data()
{
    return array(
        'url' => esc_url_raw(bodyenergy_home_platinum_direct_video_url()),
        'mime' => 'video/quicktime',
        'poster' => bodyenergy_home_platinum_image_url(),
    );
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
    $video = bodyenergy_home_platinum_video_data();

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
            'videoUrl' => $video['url'],
            'videoMime' => $video['mime'],
            'posterUrl' => $video['poster'],
            'videoSource' => 'direct-owner-url',
        )
    );
}
add_action('wp_enqueue_scripts', 'bodyenergy_home_platinum_enqueue_assets', 90);
