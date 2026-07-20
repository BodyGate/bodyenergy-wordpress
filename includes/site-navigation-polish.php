<?php
/**
 * Rifiniture coerenti della navigazione pubblica Body Energy.
 *
 * @package BodyEnergyWordPress
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Sostituisce esclusivamente la vecchia CTA del tema dedicata alla prova.
 * Non invia dati e non attiva moduli o automazioni.
 */
function bodyenergy_print_navigation_polish()
{
    ?>
    <script id="bodyenergy-navigation-polish">
    document.addEventListener('DOMContentLoaded',function(){
        document.querySelectorAll('a,button').forEach(function(element){
            var text=(element.textContent||'').replace(/\s+/g,' ').trim().toLowerCase();
            if(text!=='richiedi una prova'){return;}
            element.textContent='Richiedi informazioni';
            if(element.tagName==='A'){
                element.setAttribute('href','<?php echo esc_js(home_url('/contatti/')); ?>');
            }
        });
    });
    </script>
    <?php
}
add_action('wp_footer', 'bodyenergy_print_navigation_polish', 1000);
