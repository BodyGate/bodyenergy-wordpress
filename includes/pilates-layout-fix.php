<?php
/**
 * Integrazione Platinum delle pagine Pilates con il tema.
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

function bodyenergy_pilates_body_class($classes)
{
    if (bodyenergy_is_pilates_landing_request()) {
        $classes[] = 'bodyenergy-pilates-page';
    }

    return $classes;
}
add_filter('body_class', 'bodyenergy_pilates_body_class');

function bodyenergy_print_pilates_full_bleed_css()
{
    if (!bodyenergy_is_pilates_landing_request()) {
        return;
    }
    ?>
    <style id="bodyenergy-pilates-full-bleed">
        html,body{max-width:none!important;margin:0!important;padding:0!important;overflow-x:clip!important;background:#070709!important}
        body.bodyenergy-pilates-page .site-content,body.bodyenergy-pilates-page #content,body.bodyenergy-pilates-page #primary,body.bodyenergy-pilates-page main.site-main,body.bodyenergy-pilates-page .content-area,body.bodyenergy-pilates-page .entry-content,body.bodyenergy-pilates-page .page-content,body.bodyenergy-pilates-page article.page,body.bodyenergy-pilates-page .inside-article,body.bodyenergy-pilates-page .wp-site-blocks,body.bodyenergy-pilates-page .elementor,body.bodyenergy-pilates-page .elementor-section-wrap,body.bodyenergy-pilates-page .elementor-element.e-con,body.bodyenergy-pilates-page .e-con-inner,body.bodyenergy-pilates-page .elementor-widget-shortcode,body.bodyenergy-pilates-page .elementor-widget-shortcode>.elementor-widget-container{width:100%!important;max-width:none!important;margin:0!important;padding:0!important;overflow:visible!important}
        body.bodyenergy-pilates-page .entry-header,body.bodyenergy-pilates-page .page-header,body.bodyenergy-pilates-page .page-hero,body.bodyenergy-pilates-page .featured-image,body.bodyenergy-pilates-page .post-thumbnail,body.bodyenergy-pilates-page .entry-title,body.bodyenergy-pilates-page .entry-meta,body.bodyenergy-pilates-page .page-header-image,body.bodyenergy-pilates-page .header-image-container,body.bodyenergy-pilates-page .breadcrumb,body.bodyenergy-pilates-page .breadcrumbs,body.bodyenergy-pilates-page .breadcrumb-area,body.bodyenergy-pilates-page .breadcrumb-section,body.bodyenergy-pilates-page .page-title-area,body.bodyenergy-pilates-page .page-title-section,body.bodyenergy-pilates-page .inner-banner,body.bodyenergy-pilates-page .inner-page-banner,body.bodyenergy-pilates-page .banner-area,body.bodyenergy-pilates-page .sub-banner,body.bodyenergy-pilates-page .elementor-page-title{display:none!important}

        body.bodyenergy-pilates-page .breadcumb-area,
        body.bodyenergy-pilates-page .breadcrumb-bg,
        body.bodyenergy-pilates-page .breadcrumb-wrapper,
        body.bodyenergy-pilates-page .page-banner,
        body.bodyenergy-pilates-page .page-banner-area,
        body.bodyenergy-pilates-page .page-title-wrapper,
        body.bodyenergy-pilates-page .page-title-banner,
        body.bodyenergy-pilates-page .inner-header,
        body.bodyenergy-pilates-page .inner-header-area{display:none!important}
        body.bodyenergy-pilates-page article.page>.post-thumbnail,body.bodyenergy-pilates-page article.page>.entry-header{display:none!important}
        body.bodyenergy-pilates-page footer,body.bodyenergy-pilates-page #colophon,body.bodyenergy-pilates-page .site-footer,body.bodyenergy-pilates-page .footer-widgets,body.bodyenergy-pilates-page .footer-area,body.bodyenergy-pilates-page .footer-widget-area,body.bodyenergy-pilates-page .edit-link{display:none!important}
        body.bodyenergy-pilates-page .elementor-element.e-con{--content-width:100%;--padding-top:0;--padding-right:0;--padding-bottom:0;--padding-left:0}
        body.bodyenergy-pilates-page .be-pilates,body.bodyenergy-pilates-page .be-request{position:relative!important;left:50%!important;width:100vw!important;max-width:100vw!important;margin-left:-50vw!important;margin-right:-50vw!important;overflow-x:clip!important}
    </style>
    <?php
}
add_action('wp_head', 'bodyenergy_print_pilates_full_bleed_css', 999);


function bodyenergy_remove_pilates_theme_banner()
{
    if (!bodyenergy_is_pilates_landing_request()) {
        return;
    }
    ?>
    <script id="bodyenergy-pilates-banner-cleanup">
    document.addEventListener('DOMContentLoaded',function(){
        var content=document.querySelector('.be-request');
        if(!content){return;}
        var titles=document.querySelectorAll('h1,h2');
        titles.forEach(function(title){
            var text=(title.textContent||'').replace(/\\s+/g,' ').trim().toLowerCase();
            if(text!=='richiesta pilates reformer'&&text!=='grazie'){return;}
            var block=title;
            while(block.parentElement&&block.parentElement!==document.body&&!block.parentElement.querySelector('.be-request')){
                block=block.parentElement;
            }
            if(!block.contains(content)&&!block.querySelector('header.site-header,nav')){
                block.style.setProperty('display','none','important');
            }
        });
    });
    </script>
    <?php
}
add_action('wp_footer', 'bodyenergy_remove_pilates_theme_banner', 999);
