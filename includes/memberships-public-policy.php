<?php
/**
 * Politica pubblica per formule e abbonamenti Body Energy.
 *
 * Body Energy ASD non espone pubblicamente prezzi, formule o listini del centro
 * fitness. Le relative pagine restano disponibili nel back office, ma vengono
 * mantenute fuori dalla navigazione e non sono accessibili ai visitatori.
 *
 * @package BodyEnergyWordPress
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Restituisce le pagine storicamente usate per formule/abbonamenti.
 *
 * @return array<int, WP_Post>
 */
function bodyenergy_memberships_private_pages()
{
    $pages = array();

    foreach (array('abbonamenti', 'formule') as $slug) {
        $page = get_page_by_path($slug, OBJECT, 'page');
        if ($page instanceof WP_Post) {
            $pages[(int) $page->ID] = $page;
        }
    }

    return array_values($pages);
}

/**
 * Mantiene le pagine formule/abbonamenti non pubbliche senza cancellarle.
 */
function bodyenergy_keep_memberships_private()
{
    if (!is_admin() || !current_user_can('manage_options')) {
        return;
    }

    foreach (bodyenergy_memberships_private_pages() as $page) {
        if ('publish' !== get_post_status($page)) {
            continue;
        }

        wp_update_post(array(
            'ID' => (int) $page->ID,
            'post_status' => 'draft',
        ));
    }
}
add_action('admin_init', 'bodyenergy_keep_memberships_private', 80);

/**
 * Riconosce una voce pubblica riferita a formule/abbonamenti.
 */
function bodyenergy_is_memberships_public_link($label, $url)
{
    $label = function_exists('bodyenergy_navigation_normalize_text')
        ? bodyenergy_navigation_normalize_text($label)
        : strtolower(trim(wp_strip_all_tags((string) $label)));

    if (in_array($label, array('abbonamenti', 'formule'), true)) {
        return true;
    }

    $path = wp_parse_url((string) $url, PHP_URL_PATH);
    $path = '/' . trim(is_string($path) ? $path : '', '/') . '/';

    return in_array($path, array('/abbonamenti/', '/formule/'), true);
}

/**
 * Rimuove la voce Abbonamenti/Formule dai menu WordPress per tutti gli utenti.
 *
 * @param array<int, WP_Post> $items Voci menu.
 * @return array<int, WP_Post>
 */
function bodyenergy_remove_memberships_from_navigation($items)
{
    foreach ((array) $items as $index => $item) {
        if (bodyenergy_is_memberships_public_link(
            isset($item->title) ? (string) $item->title : '',
            isset($item->url) ? (string) $item->url : ''
        )) {
            unset($items[$index]);
        }
    }

    return array_values($items);
}
add_filter('wp_nav_menu_objects', 'bodyenergy_remove_memberships_from_navigation', 1200);

/**
 * Impedisce l'accesso pubblico anche in caso di pubblicazione accidentale.
 */
function bodyenergy_block_public_memberships_pages()
{
    if (is_admin() || !is_singular('page')) {
        return;
    }

    $post = get_post(get_queried_object_id());
    if (!($post instanceof WP_Post) || !in_array($post->post_name, array('abbonamenti', 'formule'), true)) {
        return;
    }

    if (is_user_logged_in() && current_user_can('edit_post', $post->ID)) {
        return;
    }

    $contacts = get_page_by_path('contatti', OBJECT, 'page');
    $target = $contacts instanceof WP_Post && 'publish' === get_post_status($contacts)
        ? get_permalink($contacts)
        : home_url('/');

    wp_safe_redirect((string) $target, 302);
    exit;
}
add_action('template_redirect', 'bodyenergy_block_public_memberships_pages', 0);

/**
 * Copre anche header/menu costruiti dal tema o da Elementor.
 */
function bodyenergy_print_memberships_public_cleanup()
{
    ?>
    <script id="bodyenergy-memberships-public-cleanup">
    document.addEventListener('DOMContentLoaded',function(){
        var normalize=function(value){return String(value||'').replace(/\s+/g,' ').trim().toLowerCase();};
        var isMembership=function(element){
            var text=normalize(element.textContent);
            if(text==='abbonamenti'||text==='formule'){return true;}
            if(element.tagName!=='A'){return false;}
            try{
                var url=new URL(element.getAttribute('href')||'',window.location.origin);
                var path='/' + String(url.pathname||'').replace(/^\/+|\/+$/g,'') + '/';
                return path==='/abbonamenti/'||path==='/formule/';
            }catch(error){return false;}
        };
        document.querySelectorAll('a,button').forEach(function(element){
            if(element.closest('#wpadminbar')||!isMembership(element)){return;}
            var wrapper=element.closest('li,.menu-item,.elementor-button-wrapper');
            (wrapper||element).style.setProperty('display','none','important');
            (wrapper||element).setAttribute('aria-hidden','true');
        });
    });
    </script>
    <?php
}
add_action('wp_footer', 'bodyenergy_print_memberships_public_cleanup', 1200);
