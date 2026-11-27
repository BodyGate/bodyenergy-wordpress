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
 * Le pagine riservate non possono essere pubblicate o programmate.
 * Non modifica articoli, prodotti, pagine con altri slug o contenuti esistenti.
 */
function bodyenergy_memberships_force_draft_on_save($data, $postarr)
{
    if ('page' !== ($data['post_type'] ?? '') || !in_array($data['post_status'] ?? '', array('publish', 'future'), true)) {
        return $data;
    }

    $slug = isset($data['post_name']) ? wp_unslash($data['post_name']) : '';
    if ('' === $slug && isset($data['post_title'])) {
        $slug = sanitize_title(wp_unslash($data['post_title']));
    }
    $existing = !empty($postarr['ID']) ? get_post((int) $postarr['ID']) : null;
    if (in_array($slug, array('abbonamenti', 'formule'), true)
        || ($existing instanceof WP_Post && 'page' === $existing->post_type
            && in_array($existing->post_name, array('abbonamenti', 'formule'), true))) {
        $data['post_status'] = 'draft';
    }
    return $data;
}
add_filter('wp_insert_post_data', 'bodyenergy_memberships_force_draft_on_save', 99, 2);

/**
 * Mantiene le pagine formule/abbonamenti non pubbliche senza cancellarle.
 */
function bodyenergy_keep_memberships_private()
{
    if (!is_admin() || !current_user_can('manage_options')) {
        return;
    }

    foreach (bodyenergy_memberships_private_pages() as $page) {
        if (!in_array(get_post_status($page), array('publish', 'future'), true)) {
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
 * Difesa per pubblicazioni programmate pregresse (wp_publish_post può
 * bypassare wp_insert_post_data). Il passaggio a draft non ricorre.
 */
function bodyenergy_memberships_prevent_scheduled_publish($new_status, $old_status, $post)
{
    if ('publish' !== $new_status || !($post instanceof WP_Post)
        || 'page' !== $post->post_type
        || !in_array($post->post_name, array('abbonamenti', 'formule'), true)) {
        return;
    }
    wp_update_post(array('ID' => (int) $post->ID, 'post_status' => 'draft'));
}
add_action('transition_post_status', 'bodyenergy_memberships_prevent_scheduled_publish', 1, 3);

/** IDs da escludere; gli editor autorizzati conservano l'accesso REST. */
function bodyenergy_memberships_excluded_ids($respect_edit_permissions = false)
{
    $ids = array();
    foreach (bodyenergy_memberships_private_pages() as $page) {
        if ($respect_edit_permissions && current_user_can('edit_post', $page->ID)) {
            continue;
        }
        $ids[] = (int) $page->ID;
    }
    return $ids;
}

/** WP_Query non combina post__in e post__not_in: filtrare anche include. */
function bodyenergy_memberships_exclude_from_args($args, $ids)
{
    if (!$ids) {
        return $args;
    }
    $args['post__not_in'] = array_values(array_unique(array_merge(
        (array) ($args['post__not_in'] ?? array()), $ids
    )));
    if (!empty($args['post__in'])) {
        $included = array_values(array_diff((array) $args['post__in'], $ids));
        $args['post__in'] = $included ?: array(0);
    }
    return $args;
}

function bodyenergy_memberships_filter_rest_query($args)
{
    return bodyenergy_memberships_exclude_from_args($args, bodyenergy_memberships_excluded_ids(true));
}
add_filter('rest_page_query', 'bodyenergy_memberships_filter_rest_query', 99);
add_filter('rest_post_search_query', 'bodyenergy_memberships_filter_rest_query', 99);

/** Evita l'esposizione tramite endpoint individuali REST di WordPress. */
function bodyenergy_memberships_block_rest_page($result, $server, $request)
{
    if (!preg_match('~^/wp/v2/pages/([0-9]+)(?:/|$)~', $request->get_route(), $matches)) {
        return $result;
    }
    if (in_array((int) $matches[1], bodyenergy_memberships_excluded_ids(true), true)) {
        return new WP_Error('rest_post_invalid_id', 'Pagina non disponibile.', array('status' => 404));
    }
    return $result;
}
add_filter('rest_pre_dispatch', 'bodyenergy_memberships_block_rest_page', 99, 3);

/** Sitemap nativa: non includere le pagine economiche, anche se pubblicate per errore. */
function bodyenergy_memberships_filter_sitemap($args, $post_type)
{
    return 'page' === $post_type
        ? bodyenergy_memberships_exclude_from_args($args, bodyenergy_memberships_excluded_ids())
        : $args;
}
add_filter('wp_sitemaps_posts_query_args', 'bodyenergy_memberships_filter_sitemap', 99, 2);

/** Esclude le pagine riservate da ricerca e feed pubblici nativi. */
function bodyenergy_memberships_filter_public_queries($query)
{
    if (is_admin() || (!$query->is_search() && !$query->is_feed())) {
        return;
    }
    $ids = bodyenergy_memberships_excluded_ids();
    if (!$ids) {
        return;
    }
    $args = bodyenergy_memberships_exclude_from_args(array(
        'post__in' => $query->get('post__in'),
        'post__not_in' => $query->get('post__not_in'),
    ), $ids);
    foreach ($args as $key => $value) {
        $query->set($key, $value);
    }
}
add_action('pre_get_posts', 'bodyenergy_memberships_filter_public_queries', 99);

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

    nocache_headers();
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
