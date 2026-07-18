<?php
/**
 * Integrazione full-viewport delle pagine Pilates con il tema.
 *
 * @package BodyEnergyWordPress
 */

if (!defined('ABSPATH')) {
    exit;
}

function bodyenergy_is_pilates_landing_request()
{
    if (!is_singular('page')) {
        return false;
    }

    $post = get_post(get_queried_object_id());
    if (!($post instanceof WP_Post)) {
        return false;
    }

    $parent = $post->post_parent ? get_post($post->post_parent) : null;
    if (
        in_array($post->post_name, array('pilates-reformer', 'pilates-reformer-palermo'), true)
        || ($parent instanceof WP_Post && 'pilates-reformer-palermo' === $parent->post_name)
    ) {
        return true;
    }

    foreach (array('bodyenergy_pilates_landing', 'bodyenergy_pilates_request_form', 'bodyenergy_pilates_request_thanks') as $shortcode) {
        if (has_shortcode((string) $post->post_content, $shortcode)) {
            return true;
        }
    }

    return false;
}

function bodyenergy_print_pilates_full_bleed_css()
{
    if (!bodyenergy_is_pilates_landing_request()) {
        return;
    }
    ?>
    <style id="bodyenergy-pilates-full-bleed">
        html,body{max-width:none!important;margin:0!important;padding:0!important;overflow-x:clip!important;background:#070709!important}
        body .site-content,body #content,body #primary,body main.site-main,body .content-area,body .entry-content,body .page-content,body article.page,body .inside-article,body .wp-site-blocks,body .elementor,body .elementor-section-wrap,body .elementor-element.e-con,body .e-con-inner,body .elementor-widget-shortcode,body .elementor-widget-shortcode>.elementor-widget-container{width:100%!important;max-width:none!important;margin:0!important;padding:0!important;overflow:visible!important}
        body .entry-header,body .page-header,body .page-hero,body .featured-image,body .post-thumbnail,body .entry-title,body .entry-meta,body .page-header-image,body .header-image-container{display:none!important}
        body .elementor-element.e-con{--content-width:100%;--padding-top:0;--padding-right:0;--padding-bottom:0;--padding-left:0}
        body .be-pilates,body .be-request{position:relative!important;left:50%!important;width:100vw!important;max-width:100vw!important;margin-left:-50vw!important;margin-right:-50vw!important;overflow-x:clip!important}
    </style>
    <?php
}
add_action('wp_head', 'bodyenergy_print_pilates_full_bleed_css', 999);
