<?php
/**
 * Correzioni di layout per la landing Pilates Reformer.
 *
 * @package BodyEnergyWordPress
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Verifica se la richiesta corrente contiene la landing Pilates.
 *
 * Il controllo usa slug, contenuto classico e dati Elementor, così funziona
 * anche quando la pagina è ancora una bozza visualizzata in anteprima.
 *
 * @return bool
 */
function bodyenergy_is_pilates_landing_request()
{
    if (!is_singular('page')) {
        return false;
    }

    $post_id = get_queried_object_id();

    if (!$post_id) {
        return false;
    }

    $post = get_post($post_id);

    if (!($post instanceof WP_Post)) {
        return false;
    }

    if ('pilates-reformer' === $post->post_name) {
        return true;
    }

    if (has_shortcode((string) $post->post_content, 'bodyenergy_pilates_landing')) {
        return true;
    }

    $elementor_data = get_post_meta($post_id, '_elementor_data', true);

    return is_string($elementor_data)
        && false !== strpos($elementor_data, 'bodyenergy_pilates_landing');
}

/**
 * Rimuove margini, padding e larghezze massime del canvas Elementor soltanto
 * dalla pagina che contiene la landing Pilates.
 */
function bodyenergy_print_pilates_full_bleed_css()
{
    if (!bodyenergy_is_pilates_landing_request()) {
        return;
    }
    ?>
    <style id="bodyenergy-pilates-full-bleed">
        html,
        body,
        body.elementor-template-canvas,
        body.page-template-elementor_canvas {
            width: 100% !important;
            max-width: none !important;
            margin: 0 !important;
            padding: 0 !important;
            overflow-x: clip !important;
            background: #070709 !important;
        }

        body.elementor-template-canvas > .elementor,
        body.elementor-template-canvas .elementor-section-wrap,
        body.elementor-template-canvas .elementor-element.e-con,
        body.elementor-template-canvas .e-con-inner,
        body.elementor-template-canvas .elementor-widget-shortcode,
        body.elementor-template-canvas .elementor-widget-shortcode > .elementor-widget-container,
        body.page-template-elementor_canvas > .elementor,
        body.page-template-elementor_canvas .elementor-section-wrap,
        body.page-template-elementor_canvas .elementor-element.e-con,
        body.page-template-elementor_canvas .e-con-inner,
        body.page-template-elementor_canvas .elementor-widget-shortcode,
        body.page-template-elementor_canvas .elementor-widget-shortcode > .elementor-widget-container {
            width: 100% !important;
            max-width: none !important;
            margin: 0 !important;
            padding: 0 !important;
            overflow-x: clip !important;
        }

        body.elementor-template-canvas .elementor-element.e-con,
        body.page-template-elementor_canvas .elementor-element.e-con {
            --content-width: 100%;
            --padding-top: 0;
            --padding-right: 0;
            --padding-bottom: 0;
            --padding-left: 0;
        }

        body.elementor-template-canvas .be-pilates,
        body.page-template-elementor_canvas .be-pilates {
            position: relative;
            left: auto;
            width: 100% !important;
            max-width: none !important;
            margin: 0 !important;
            overflow-x: clip !important;
        }
    </style>
    <?php
}
add_action('wp_head', 'bodyenergy_print_pilates_full_bleed_css', 999);
