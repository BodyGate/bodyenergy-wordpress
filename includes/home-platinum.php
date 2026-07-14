<?php
/**
 * Asset e comportamento automatico dell'Hero Platinum della nuova Home.
 *
 * Interviene esclusivamente sulla pagina di staging ID 113 e non modifica
 * i dati Elementor salvati nel database.
 */

if (!defined('ABSPATH')) {
    exit;
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
    $pattern = '~https?://[^"\'\s<>]+?\.(?:mp4|webm|ogv|ogg|mov)(?:\?[^"\'\s<>]*)?~i';

    if (preg_match($pattern, $decoded, $matches)) {
        return esc_url_raw($matches[0]);
    }

    return '';
}

/**
 * Cerca il video già usato nella Home esistente e, in seconda battuta,
 * seleziona il video più pertinente presente nella Libreria Media.
 *
 * @return array{url:string,mime:string,poster:string}
 */
function bodyenergy_home_platinum_video_data()
{
    static $resolved = null;

    if (is_array($resolved)) {
        return $resolved;
    }

    $resolved = array(
        'url' => '',
        'mime' => '',
        'poster' => bodyenergy_home_platinum_image_url(),
    );

    $front_page_id = (int) get_option('page_on_front');
    $page_ids = array_values(array_unique(array_filter(array(
        $front_page_id,
        (int) get_queried_object_id(),
    ))));

    foreach ($page_ids as $page_id) {
        $post = get_post($page_id);
        $values = array();

        if ($post instanceof WP_Post) {
            $values[] = $post->post_content;
        }

        $all_meta = get_post_meta($page_id);
        foreach ($all_meta as $meta_values) {
            foreach ((array) $meta_values as $meta_value) {
                if (is_string($meta_value)) {
                    $values[] = $meta_value;
                }
            }
        }

        foreach ($values as $value) {
            $url = bodyenergy_home_platinum_extract_video_url($value);
            if ('' === $url) {
                continue;
            }

            $filetype = wp_check_filetype($url);
            $attachment_id = attachment_url_to_postid($url);
            $poster_id = $attachment_id ? (int) get_post_thumbnail_id($attachment_id) : 0;

            $resolved['url'] = $url;
            $resolved['mime'] = !empty($filetype['type']) ? (string) $filetype['type'] : 'video/mp4';

            if ($poster_id) {
                $poster = wp_get_attachment_image_url($poster_id, 'full');
                if (is_string($poster) && '' !== $poster) {
                    $resolved['poster'] = $poster;
                }
            }

            return $resolved;
        }
    }

    $videos = get_posts(array(
        'post_type' => 'attachment',
        'post_status' => 'inherit',
        'post_mime_type' => 'video',
        'posts_per_page' => 50,
        'orderby' => 'date',
        'order' => 'DESC',
    ));

    $best_video = null;
    $best_score = -1;
    $keywords = array('body', 'energy', 'palestra', 'gym', 'fitness', 'home');

    foreach ($videos as $index => $video) {
        if (!$video instanceof WP_Post) {
            continue;
        }

        $url = wp_get_attachment_url($video->ID);
        if (!is_string($url) || '' === $url) {
            continue;
        }

        $score = max(0, 50 - (int) $index);

        if ($front_page_id && (int) $video->post_parent === $front_page_id) {
            $score += 200;
        }

        $haystack = strtolower($video->post_title . ' ' . $video->post_name . ' ' . $url);
        foreach ($keywords as $keyword) {
            if (false !== strpos($haystack, $keyword)) {
                $score += 25;
            }
        }

        if ($score > $best_score) {
            $best_video = $video;
            $best_score = $score;
        }
    }

    if ($best_video instanceof WP_Post) {
        $url = wp_get_attachment_url($best_video->ID);
        $filetype = is_string($url) ? wp_check_filetype($url) : array();
        $poster_id = (int) get_post_thumbnail_id($best_video->ID);

        $resolved['url'] = is_string($url) ? $url : '';
        $resolved['mime'] = !empty($filetype['type']) ? (string) $filetype['type'] : 'video/mp4';

        if ($poster_id) {
            $poster = wp_get_attachment_image_url($poster_id, 'full');
            if (is_string($poster) && '' !== $poster) {
                $resolved['poster'] = $poster;
            }
        }
    }

    return $resolved;
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
        )
    );
}
add_action('wp_enqueue_scripts', 'bodyenergy_home_platinum_enqueue_assets', 90);
