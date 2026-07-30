<?php
/**
 * Collegamenti coerenti della navigazione pubblica Body Energy.
 *
 * Risolve i percorsi storici verso le pagine reali del progetto Platinum,
 * usa i link di anteprima per gli amministratori quando una pagina e ancora
 * in bozza e impedisce ai visitatori di raggiungere collegamenti non pronti.
 *
 * @package BodyEnergyWordPress
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Mappa centrale delle destinazioni interne del sito.
 *
 * @return array<string, array<string, mixed>>
 */
function bodyenergy_navigation_routes()
{
    return array(
        'home' => array(
            'label' => 'Home',
            'labels' => array('home'),
            'paths' => array('/'),
            'aliases' => array(),
            'home' => true,
        ),
        'gym' => array(
            'label' => 'La palestra',
            'labels' => array('la palestra', 'palestra'),
            'paths' => array('/la-palestra/', '/palestra/', '/palestra-palermo/'),
            'aliases' => array('palestra-palermo', 'palestra', 'la-palestra'),
        ),
        'services' => array(
            'label' => 'Servizi',
            'labels' => array('servizi', 'attivita', 'attività'),
            'paths' => array('/servizi/', '/attivita/'),
            'aliases' => array('servizi', 'attivita'),
        ),
        'pilates' => array(
            'label' => 'Pilates Reformer',
            'labels' => array('pilates reformer'),
            'paths' => array('/pilates-reformer/', '/pilates-reformer-palermo/'),
            'aliases' => array('pilates-reformer-palermo', 'pilates-reformer'),
        ),
        'pilates-request' => array(
            'label' => 'Richiesta Pilates Reformer',
            'labels' => array(),
            'paths' => array(
                '/pilates-reformer/richiesta/',
                '/pilates-reformer-palermo/richiesta/',
            ),
            'aliases' => array(
                'pilates-reformer-palermo/richiesta',
                'pilates-reformer/richiesta',
            ),
        ),
        'pilates-thanks' => array(
            'label' => 'Grazie Pilates Reformer',
            'labels' => array(),
            'paths' => array(
                '/pilates-reformer/grazie/',
                '/pilates-reformer-palermo/grazie/',
            ),
            'aliases' => array(
                'pilates-reformer-palermo/grazie',
                'pilates-reformer/grazie',
            ),
        ),
        'memberships' => array(
            'label' => 'Abbonamenti',
            'labels' => array('abbonamenti', 'formule'),
            'paths' => array('/abbonamenti/', '/formule/'),
            'aliases' => array('abbonamenti', 'formule'),
        ),
        'contacts' => array(
            'label' => 'Contatti',
            'labels' => array('contatti'),
            'paths' => array('/contatti/'),
            'aliases' => array('contatti'),
        ),
        'members-area' => array(
            'label' => 'Area soci',
            'labels' => array('area soci'),
            'paths' => array('/area-soci/'),
            'aliases' => array('area-soci'),
        ),
    );
}

/**
 * Normalizza una label per il confronto.
 *
 * @param string $value Testo da normalizzare.
 * @return string
 */
function bodyenergy_navigation_normalize_text($value)
{
    $value = wp_strip_all_tags((string) $value);
    $value = preg_replace('/\s+/u', ' ', $value);
    $value = trim((string) $value);

    return function_exists('mb_strtolower')
        ? mb_strtolower($value, 'UTF-8')
        : strtolower($value);
}

/**
 * Normalizza il percorso di un URL interno.
 *
 * @param string $url URL o percorso.
 * @return string
 */
function bodyenergy_navigation_normalize_path($url)
{
    $path = wp_parse_url((string) $url, PHP_URL_PATH);
    $path = is_string($path) ? $path : '/';
    $path = '/' . trim($path, '/');

    return '/' === $path ? '/' : trailingslashit($path);
}

/**
 * Trova la prima pagina esistente tra gli alias indicati.
 *
 * @param array<int, string> $aliases Slug o percorsi pagina.
 * @return WP_Post|null
 */
function bodyenergy_navigation_find_page($aliases)
{
    foreach ((array) $aliases as $alias) {
        $page = get_page_by_path((string) $alias, OBJECT, 'page');
        if ($page instanceof WP_Post) {
            return $page;
        }
    }

    return null;
}

/**
 * Risolve URL e visibilita di una destinazione.
 *
 * @param array<string, mixed> $route Specifica della destinazione.
 * @return array{url:string, visible:bool, published:bool, page_id:int, status:string}
 */
function bodyenergy_navigation_resolve_route($route)
{
    if (!empty($route['home'])) {
        return array(
            'url' => home_url('/'),
            'visible' => true,
            'published' => true,
            'page_id' => 0,
            'status' => 'publish',
        );
    }

    $page = bodyenergy_navigation_find_page(isset($route['aliases']) ? $route['aliases'] : array());
    if (!($page instanceof WP_Post)) {
        return array(
            'url' => '',
            'visible' => false,
            'published' => false,
            'page_id' => 0,
            'status' => 'missing',
        );
    }

    $status = (string) get_post_status($page);
    if ('publish' === $status) {
        return array(
            'url' => (string) get_permalink($page),
            'visible' => true,
            'published' => true,
            'page_id' => (int) $page->ID,
            'status' => $status,
        );
    }

    if (is_user_logged_in() && current_user_can('edit_post', $page->ID)) {
        $preview_url = get_preview_post_link($page);
        if (!is_string($preview_url) || '' === $preview_url) {
            $preview_url = add_query_arg('preview', 'true', get_permalink($page));
        }

        return array(
            'url' => (string) $preview_url,
            'visible' => true,
            'published' => false,
            'page_id' => (int) $page->ID,
            'status' => $status,
        );
    }

    return array(
        'url' => '',
        'visible' => false,
        'published' => false,
        'page_id' => (int) $page->ID,
        'status' => $status,
    );
}

/**
 * Costruisce la mappa risolta usata da menu, redirect e fallback JavaScript.
 *
 * @return array<string, array<string, mixed>>
 */
function bodyenergy_navigation_resolved_routes()
{
    $resolved = array();

    foreach (bodyenergy_navigation_routes() as $key => $route) {
        $resolved[$key] = array_merge($route, bodyenergy_navigation_resolve_route($route));
    }

    return $resolved;
}

/**
 * Individua una destinazione in base a label e URL correnti.
 *
 * @param string $label Label del collegamento.
 * @param string $url   URL corrente.
 * @param array<string, array<string, mixed>> $routes Mappa risolta.
 * @return string
 */
function bodyenergy_navigation_match_route($label, $url, $routes)
{
    $normalized_label = bodyenergy_navigation_normalize_text($label);
    $normalized_path = bodyenergy_navigation_normalize_path($url);

    foreach ($routes as $key => $route) {
        foreach ((array) $route['paths'] as $path) {
            if (bodyenergy_navigation_normalize_path($path) === $normalized_path) {
                return (string) $key;
            }
        }
    }

    foreach ($routes as $key => $route) {
        foreach ((array) $route['labels'] as $candidate) {
            if (bodyenergy_navigation_normalize_text($candidate) === $normalized_label) {
                return (string) $key;
            }
        }
    }

    if ('richiedi una prova' === $normalized_label || 'richiedi informazioni' === $normalized_label) {
        return 'contacts';
    }

    return '';
}

/**
 * Corregge i menu WordPress prima del rendering.
 *
 * Le voci che puntano a pagine ancora in bozza restano disponibili agli
 * amministratori tramite anteprima e vengono rimosse per i visitatori.
 *
 * @param array<int, WP_Post> $items Voci menu.
 * @return array<int, WP_Post>
 */
function bodyenergy_filter_navigation_items($items)
{
    $routes = bodyenergy_navigation_resolved_routes();

    foreach ((array) $items as $index => $item) {
        $key = bodyenergy_navigation_match_route(
            isset($item->title) ? (string) $item->title : '',
            isset($item->url) ? (string) $item->url : '',
            $routes
        );

        if ('' === $key || !isset($routes[$key])) {
            continue;
        }

        $route = $routes[$key];
        if (empty($route['visible']) || empty($route['url'])) {
            unset($items[$index]);
            continue;
        }

        $item->url = (string) $route['url'];

        $current_title = bodyenergy_navigation_normalize_text(isset($item->title) ? $item->title : '');
        if (in_array($current_title, array('palestra', 'attivita', 'attività', 'formule', 'richiedi una prova'), true)) {
            if ('contacts' === $key) {
                $item->title = 'Richiedi informazioni';
            } elseif (!empty($route['label'])) {
                $item->title = (string) $route['label'];
            }
        }
    }

    return array_values($items);
}
add_filter('wp_nav_menu_objects', 'bodyenergy_filter_navigation_items', 1000);

/**
 * Reindirizza i vecchi percorsi soltanto quando WordPress li considera 404.
 */
function bodyenergy_redirect_legacy_navigation_paths()
{
    if (!is_404()) {
        return;
    }

    $request_uri = isset($_SERVER['REQUEST_URI']) ? wp_unslash($_SERVER['REQUEST_URI']) : '/';
    $request_path = bodyenergy_navigation_normalize_path((string) $request_uri);
    $routes = bodyenergy_navigation_resolved_routes();

    foreach ($routes as $route) {
        foreach ((array) $route['paths'] as $legacy_path) {
            if (bodyenergy_navigation_normalize_path($legacy_path) !== $request_path) {
                continue;
            }

            if (empty($route['visible']) || empty($route['url'])) {
                return;
            }

            $status = !empty($route['published']) ? 301 : 302;
            wp_safe_redirect((string) $route['url'], $status);
            exit;
        }
    }
}
add_action('template_redirect', 'bodyenergy_redirect_legacy_navigation_paths', 1);

/**
 * Applica lo stesso controllo anche agli header costruiti dal tema o da
 * Elementor, che possono non passare attraverso wp_nav_menu().
 */
function bodyenergy_print_navigation_polish()
{
    $routes = bodyenergy_navigation_resolved_routes();
    $by_text = array();
    $by_path = array();

    foreach ($routes as $key => $route) {
        $target = array(
            'key' => (string) $key,
            'label' => isset($route['label']) ? (string) $route['label'] : '',
            'url' => isset($route['url']) ? (string) $route['url'] : '',
            'visible' => !empty($route['visible']),
        );

        foreach ((array) $route['labels'] as $label) {
            $by_text[bodyenergy_navigation_normalize_text($label)] = $target;
        }

        foreach ((array) $route['paths'] as $path) {
            $by_path[bodyenergy_navigation_normalize_path($path)] = $target;
        }
    }

    $by_text['richiedi una prova'] = array(
        'key' => 'contacts',
        'label' => 'Richiedi informazioni',
        'url' => isset($routes['contacts']['url']) ? (string) $routes['contacts']['url'] : '',
        'visible' => !empty($routes['contacts']['visible']),
    );
    $by_text['richiedi informazioni'] = $by_text['richiedi una prova'];
    ?>
    <script id="bodyenergy-navigation-polish">
    document.addEventListener('DOMContentLoaded',function(){
        var byText=<?php echo wp_json_encode($by_text, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>;
        var byPath=<?php echo wp_json_encode($by_path, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>;
        var normalizeText=function(value){return String(value||'').replace(/\s+/g,' ').trim().toLowerCase();};
        var normalizePath=function(value){
            try{
                var url=new URL(String(value||''),window.location.origin);
                var path='/' + String(url.pathname||'/').replace(/^\/+|\/+$/g,'');
                return path==='/'?'/':path+'/';
            }catch(error){return '';}
        };
        var hideElement=function(element){
            var wrapper=element.closest('li,.menu-item,.elementor-button-wrapper');
            (wrapper||element).style.setProperty('display','none','important');
            (wrapper||element).setAttribute('aria-hidden','true');
        };

        document.querySelectorAll('a,button').forEach(function(element){
            var text=normalizeText(element.textContent);
            var path=element.tagName==='A'?normalizePath(element.getAttribute('href')):'';
            var target=(path&&byPath[path])||byText[text];
            if(!target){return;}

            if(!target.visible||!target.url){
                hideElement(element);
                return;
            }

            if(element.tagName==='A'){
                element.setAttribute('href',target.url);
            }

            if(text==='richiedi una prova'){
                element.textContent='Richiedi informazioni';
            }else if(element.children.length===0){
                if(text==='palestra'||text==='attivita'||text==='attività'||text==='formule'){
                    element.textContent=target.label;
                }
            }
        });
    });
    </script>
    <?php
}
add_action('wp_footer', 'bodyenergy_print_navigation_polish', 1000);
