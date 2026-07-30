<?php
/**
 * Asset e comportamento della nuova Home Platinum.
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
 * Verifica se la pagina corrente è la Home Platinum in bozza.
 */
function bodyenergy_is_home_platinum_page()
{
    return is_page(113);
}

/**
 * Aggiunge una classe di pagina stabile per isolare le rifiniture dal tema.
 */
function bodyenergy_home_platinum_body_class($classes)
{
    if (bodyenergy_is_home_platinum_page()) {
        $classes[] = 'bodyenergy-home-platinum-page';
    }

    return $classes;
}
add_filter('body_class', 'bodyenergy_home_platinum_body_class');

/**
 * Restituisce l'URL risolto di una rotta interna, rispettando bozze e visibilità.
 */
function bodyenergy_home_platinum_route_url($route_key, $fallback = '')
{
    if (function_exists('bodyenergy_navigation_resolved_routes')) {
        $routes = bodyenergy_navigation_resolved_routes();

        if (
            isset($routes[$route_key])
            && !empty($routes[$route_key]['visible'])
            && !empty($routes[$route_key]['url'])
        ) {
            return (string) $routes[$route_key]['url'];
        }
    }

    return (string) $fallback;
}

/**
 * Carica gli asset solo sulla nuova Home in bozza.
 */
function bodyenergy_home_platinum_enqueue_assets()
{
    if (!bodyenergy_is_home_platinum_page()) {
        return;
    }

    $base_url = plugin_dir_url(BODYENERGY_WORDPRESS_FILE);

    wp_enqueue_style(
        'bodyenergy-home-platinum',
        $base_url . 'assets/css/home-platinum.css',
        array(),
        BODYENERGY_WORDPRESS_VERSION
    );

    wp_enqueue_style(
        'bodyenergy-home-experience',
        $base_url . 'assets/css/home-experience.css',
        array('bodyenergy-home-platinum'),
        BODYENERGY_WORDPRESS_VERSION
    );

    wp_enqueue_style(
        'bodyenergy-home-closing',
        $base_url . 'assets/css/home-closing.css',
        array('bodyenergy-home-experience'),
        BODYENERGY_WORDPRESS_VERSION
    );

    wp_enqueue_script(
        'bodyenergy-home-platinum',
        $base_url . 'assets/js/home-platinum.js',
        array(),
        BODYENERGY_WORDPRESS_VERSION,
        true
    );

    wp_enqueue_script(
        'bodyenergy-home-closing',
        $base_url . 'assets/js/home-closing.js',
        array('bodyenergy-home-platinum'),
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

    wp_localize_script(
        'bodyenergy-home-closing',
        'BodyEnergyHomeClosing',
        array(
            'contactsUrl' => bodyenergy_home_platinum_route_url(
                'contacts',
                'mailto:bodyenergy.asd@gmail.com'
            ),
            'gymUrl' => bodyenergy_home_platinum_route_url('gym'),
            'year' => current_time('Y'),
        )
    );
}
add_action('wp_enqueue_scripts', 'bodyenergy_home_platinum_enqueue_assets', 90);

/**
 * Elimina dalla sola Home Platinum il footer e il chrome generico del tema.
 */
function bodyenergy_print_home_platinum_layout_css()
{
    if (!bodyenergy_is_home_platinum_page()) {
        return;
    }
    ?>
    <style id="bodyenergy-home-platinum-layout">
    html,body{max-width:none!important;margin:0!important;padding:0!important;overflow-x:clip!important;background:#070709!important}
    body.bodyenergy-home-platinum-page .site-content,body.bodyenergy-home-platinum-page #content,body.bodyenergy-home-platinum-page #primary,body.bodyenergy-home-platinum-page main.site-main,body.bodyenergy-home-platinum-page .content-area,body.bodyenergy-home-platinum-page .entry-content,body.bodyenergy-home-platinum-page .page-content,body.bodyenergy-home-platinum-page article.page,body.bodyenergy-home-platinum-page .inside-article,body.bodyenergy-home-platinum-page .elementor,body.bodyenergy-home-platinum-page .elementor-section-wrap{width:100%!important;max-width:none!important;margin:0!important;padding:0!important;overflow:visible!important}
    body.bodyenergy-home-platinum-page .entry-header,body.bodyenergy-home-platinum-page .page-header,body.bodyenergy-home-platinum-page .page-hero,body.bodyenergy-home-platinum-page .featured-image,body.bodyenergy-home-platinum-page .post-thumbnail,body.bodyenergy-home-platinum-page .entry-title,body.bodyenergy-home-platinum-page .entry-meta,body.bodyenergy-home-platinum-page .breadcrumb,body.bodyenergy-home-platinum-page .breadcrumbs,body.bodyenergy-home-platinum-page .breadcrumb-area,body.bodyenergy-home-platinum-page .breadcrumb-section,body.bodyenergy-home-platinum-page .breadcumb-area,body.bodyenergy-home-platinum-page .breadcrumb-bg,body.bodyenergy-home-platinum-page .page-title-area,body.bodyenergy-home-platinum-page .page-title-section,body.bodyenergy-home-platinum-page .inner-banner,body.bodyenergy-home-platinum-page .page-banner,body.bodyenergy-home-platinum-page .banner-area,body.bodyenergy-home-platinum-page .sub-banner,body.bodyenergy-home-platinum-page .elementor-page-title,body.bodyenergy-home-platinum-page footer,body.bodyenergy-home-platinum-page #colophon,body.bodyenergy-home-platinum-page .site-footer,body.bodyenergy-home-platinum-page .footer-widgets,body.bodyenergy-home-platinum-page .site-info,body.bodyenergy-home-platinum-page .edit-link,body.bodyenergy-home-platinum-page .post-edit-link,body.bodyenergy-home-platinum-page a.post-edit-link,body.bodyenergy-home-platinum-page .entry-footer{display:none!important}
    body.bodyenergy-home-platinum-page .be-platinum-experience,body.bodyenergy-home-platinum-page .be-platinum-home-close{position:relative!important;left:50%!important;width:100vw!important;max-width:100vw!important;margin-left:-50vw!important;margin-right:-50vw!important}
    </style>
    <?php
}
add_action('wp_head', 'bodyenergy_print_home_platinum_layout_css', 999);
