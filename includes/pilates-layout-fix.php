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
 * Rimuove i margini del canvas Elementor soltanto dalla pagina Pilates.
 */
function bodyenergy_print_pilates_full_bleed_css()
{
    if (!is_singular('page')) {
        return;
    }

    $post = get_queried_object();

    if (!($post instanceof WP_Post) || 'pilates-reformer' !== $post->post_name) {
        return;
    }
    ?>
    <style id="bodyenergy-pilates-full-bleed">
        html,
        body {
            margin: 0 !important;
            padding: 0 !important;
            overflow-x: hidden;
            background: #070709 !important;
        }

        body.elementor-template-canvas .elementor,
        body.elementor-template-canvas .elementor-section-wrap,
        body.elementor-template-canvas .elementor-widget-shortcode,
        body.elementor-template-canvas .elementor-widget-shortcode > .elementor-widget-container {
            width: 100% !important;
            max-width: none !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        body.elementor-template-canvas .be-pilates {
            width: 100vw !important;
            max-width: none !important;
            margin-left: calc(50% - 50vw) !important;
            margin-right: calc(50% - 50vw) !important;
        }
    </style>
    <?php
}
add_action('wp_head', 'bodyenergy_print_pilates_full_bleed_css', 99);
