<?php
/**
 * Rifinitura compatta della pagina Contatti Platinum.
 *
 * Riduce esclusivamente gli spazi verticali della pagina Contatti e rimuove
 * il banner duplicato generato dal tema senza modificare le altre pagine.
 *
 * @package BodyEnergyWordPress
 */

if (!defined('ABSPATH')) {
    exit;
}

function bodyenergy_print_contact_compact_polish()
{
    if (!function_exists('bodyenergy_is_contact_page') || !bodyenergy_is_contact_page()) {
        return;
    }
    ?>
    <style id="bodyenergy-contact-compact-polish">
    body.bodyenergy-contact-page .be-contact__hero{padding:66px 0 70px!important}
    body.bodyenergy-contact-page .be-contact__hero-layout{gap:52px!important}
    body.bodyenergy-contact-page .be-contact__hero-note{margin-top:30px!important;padding-top:17px!important}
    body.bodyenergy-contact-page .be-contact__direct-head{padding:20px 23px!important}
    body.bodyenergy-contact-page .be-contact__direct-row{padding:18px 23px!important}
    body.bodyenergy-contact-page .be-contact__location{padding:76px 0!important}
    body.bodyenergy-contact-page .be-contact__location-layout{gap:60px!important}
    body.bodyenergy-contact-page .be-contact__map{min-height:380px!important}
    body.bodyenergy-contact-page .be-contact__map-card{left:22px!important;bottom:22px!important;width:min(360px,calc(100% - 44px))!important;padding:20px!important}
    body.bodyenergy-contact-page .be-contact__hours{padding:72px 0!important}
    body.bodyenergy-contact-page .be-contact__hours-layout{gap:68px!important}
    body.bodyenergy-contact-page .be-contact__schedule>div{padding:18px 0!important}
    body.bodyenergy-contact-page .be-contact__closing{padding:72px 0!important}
    body.bodyenergy-contact-page .be-contact__closing-layout{gap:64px!important}
    body.bodyenergy-contact-page .be-contact__footer{padding:20px 0!important}

    body.bodyenergy-contact-page .page-title-wrapper,
    body.bodyenergy-contact-page .page-title-banner,
    body.bodyenergy-contact-page .theme-page-header,
    body.bodyenergy-contact-page .inner-page-banner,
    body.bodyenergy-contact-page .breadcrumb-wrapper,
    body.bodyenergy-contact-page .page-breadcrumb-area,
    body.bodyenergy-contact-page .page-title-bg{display:none!important}

    @media(max-width:980px){
        body.bodyenergy-contact-page .be-contact__hero,
        body.bodyenergy-contact-page .be-contact__location,
        body.bodyenergy-contact-page .be-contact__hours,
        body.bodyenergy-contact-page .be-contact__closing{padding:60px 0!important}
        body.bodyenergy-contact-page .be-contact__hero-layout,
        body.bodyenergy-contact-page .be-contact__location-layout,
        body.bodyenergy-contact-page .be-contact__hours-layout,
        body.bodyenergy-contact-page .be-contact__closing-layout{gap:40px!important}
        body.bodyenergy-contact-page .be-contact__map{min-height:360px!important}
    }

    @media(max-width:620px){
        body.bodyenergy-contact-page .be-contact__hero,
        body.bodyenergy-contact-page .be-contact__location,
        body.bodyenergy-contact-page .be-contact__hours,
        body.bodyenergy-contact-page .be-contact__closing{padding:50px 0!important}
        body.bodyenergy-contact-page .be-contact__map{min-height:330px!important}
        body.bodyenergy-contact-page .be-contact__map-card{left:16px!important;bottom:16px!important;width:calc(100% - 32px)!important;padding:18px!important}
        body.bodyenergy-contact-page .be-contact__footer{padding:22px 0!important}
    }
    </style>
    <?php
}
add_action('wp_head', 'bodyenergy_print_contact_compact_polish', 1001);

function bodyenergy_remove_contact_theme_banner()
{
    if (!function_exists('bodyenergy_is_contact_page') || !bodyenergy_is_contact_page()) {
        return;
    }
    ?>
    <script id="bodyenergy-contact-banner-cleanup">
    document.addEventListener('DOMContentLoaded',function(){
        var content=document.querySelector('.be-contact');
        if(!content){return;}
        document.querySelectorAll('h1,h2').forEach(function(title){
            var text=(title.textContent||'').replace(/\s+/g,' ').trim().toLowerCase();
            if(text!=='contatti'){return;}
            var block=title;
            while(block.parentElement&&block.parentElement!==document.body&&!block.parentElement.querySelector('.be-contact')){
                block=block.parentElement;
            }
            if(!block.contains(content)&&!block.querySelector('header.site-header,header,nav')){
                block.style.setProperty('display','none','important');
                block.setAttribute('aria-hidden','true');
            }
        });
    });
    </script>
    <?php
}
add_action('wp_footer', 'bodyenergy_remove_contact_theme_banner', 999);
