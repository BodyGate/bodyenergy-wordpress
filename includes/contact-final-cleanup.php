<?php
/**
 * Pulizia finale della pagina Contatti Platinum.
 *
 * Nasconde esclusivamente i controlli di modifica del tema che possono restare
 * visibili agli amministratori in fondo alla pagina. La barra amministrativa
 * WordPress resta invariata.
 *
 * @package BodyEnergyWordPress
 */

if (!defined('ABSPATH')) {
    exit;
}

function bodyenergy_print_contact_final_cleanup()
{
    if (!function_exists('bodyenergy_is_contact_page') || !bodyenergy_is_contact_page()) {
        return;
    }
    ?>
    <style id="bodyenergy-contact-final-cleanup">
    body.bodyenergy-contact-page .edit-link,
    body.bodyenergy-contact-page .post-edit-link,
    body.bodyenergy-contact-page .edit-post-link,
    body.bodyenergy-contact-page .entry-edit-link,
    body.bodyenergy-contact-page [class*="edit-link"],
    body.bodyenergy-contact-page [class*="post-edit"]{display:none!important}
    </style>
    <script id="bodyenergy-contact-final-cleanup-script">
    document.addEventListener('DOMContentLoaded',function(){
        document.querySelectorAll('a').forEach(function(link){
            if(link.closest('#wpadminbar')){return;}
            var href=(link.getAttribute('href')||'').toLowerCase();
            var text=(link.textContent||'').replace(/\s+/g,' ').trim().toLowerCase();
            var label=((link.getAttribute('aria-label')||'')+' '+(link.getAttribute('title')||'')).toLowerCase();
            var isEditHref=href.indexOf('post.php')!==-1&&href.indexOf('action=edit')!==-1;
            var isEditLabel=text==='modifica'||label.indexOf('modifica')!==-1||label.indexOf('edit')!==-1;
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
add_action('wp_footer', 'bodyenergy_print_contact_final_cleanup', 1001);
