<?php
/**
 * Pulizia finale della pagina Servizi Platinum.
 *
 * Rimuove il banner e il breadcrumb generati dal tema quando non vengono
 * intercettati dai selettori standard, senza modificare header o admin bar.
 *
 * @package BodyEnergyWordPress
 */

if (!defined('ABSPATH')) {
    exit;
}

function bodyenergy_print_services_theme_cleanup()
{
    if (!function_exists('bodyenergy_is_services_page') || !bodyenergy_is_services_page()) {
        return;
    }
    ?>
    <style id="bodyenergy-services-theme-cleanup">
    body.bodyenergy-services-page .page-title-wrapper,
    body.bodyenergy-services-page .page-title-banner,
    body.bodyenergy-services-page .theme-page-header,
    body.bodyenergy-services-page .inner-page-banner,
    body.bodyenergy-services-page .breadcrumb-wrapper,
    body.bodyenergy-services-page .page-breadcrumb-area,
    body.bodyenergy-services-page .page-title-bg,
    body.bodyenergy-services-page [class*="page-title"][class*="banner"],
    body.bodyenergy-services-page [class*="breadcrumb"][class*="area"]{display:none!important}

    body.bodyenergy-services-page .edit-link,
    body.bodyenergy-services-page .post-edit-link,
    body.bodyenergy-services-page .edit-post-link,
    body.bodyenergy-services-page .entry-edit-link,
    body.bodyenergy-services-page [class*="edit-link"],
    body.bodyenergy-services-page [class*="post-edit"]{display:none!important}
    </style>
    <script id="bodyenergy-services-theme-cleanup-script">
    document.addEventListener('DOMContentLoaded',function(){
        var landing=document.querySelector('.be-services');
        if(!landing){return;}

        document.querySelectorAll('h1,h2').forEach(function(title){
            var text=(title.textContent||'').replace(/\s+/g,' ').trim().toLowerCase();
            if(text!=='servizi'){return;}
            if(title.closest('.be-services')){return;}
            if(title.closest('header,nav,#wpadminbar')){return;}

            var block=title;
            var depth=0;
            while(block.parentElement&&block.parentElement!==document.body&&depth<6){
                var parent=block.parentElement;
                if(parent.contains(landing)||parent.querySelector('header.site-header,header,nav,#wpadminbar')){break;}
                block=parent;
                depth+=1;
            }
            block.style.setProperty('display','none','important');
            block.setAttribute('aria-hidden','true');
        });

        document.querySelectorAll('a').forEach(function(link){
            if(link.closest('#wpadminbar')){return;}
            var href=(link.getAttribute('href')||'').toLowerCase();
            var text=(link.textContent||'').replace(/\s+/g,' ').trim().toLowerCase();
            var label=((link.getAttribute('aria-label')||'')+' '+(link.getAttribute('title')||'')).toLowerCase();
            var isEditHref=href.indexOf('post.php')!==-1&&href.indexOf('action=edit')!==-1;
            var isEditLabel=text==='modifica'||text==='edit'||label.indexOf('modifica')!==-1||label.indexOf('edit')!==-1;
            if(isEditHref||isEditLabel){
                var wrapper=link.closest('.edit-link,.post-edit-link,.edit-post-link,.entry-edit-link');
                (wrapper||link).style.setProperty('display','none','important');
                (wrapper||link).setAttribute('aria-hidden','true');
            }
        });
    });
    </script>
    <?php
}
add_action('wp_footer', 'bodyenergy_print_services_theme_cleanup', 1001);
