<?php
/**
 * Mappatura tecnica in sola lettura del sito WordPress.
 *
 * @package BodyEnergyWordPress
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Registra la pagina di mappatura tecnica sotto il menu Body Energy.
 */
function bodyenergy_register_site_audit_page()
{
    add_submenu_page(
        'bodyenergy-bodygate',
        'Mappatura tecnica',
        'Mappatura tecnica',
        'manage_options',
        'bodyenergy-site-audit',
        'bodyenergy_render_site_audit_page'
    );
}
add_action('admin_menu', 'bodyenergy_register_site_audit_page', 20);

/**
 * Recupera l'inventario dei plugin attivi senza leggere dati operativi.
 *
 * @return array<int, array{name: string, version: string, file: string}>
 */
function bodyenergy_get_active_plugin_inventory()
{
    if (!function_exists('get_plugins')) {
        require_once ABSPATH . 'wp-admin/includes/plugin.php';
    }

    $all_plugins = get_plugins();
    $active_files = (array) get_option('active_plugins', array());

    if (is_multisite()) {
        $network_plugins = array_keys((array) get_site_option('active_sitewide_plugins', array()));
        $active_files = array_unique(array_merge($active_files, $network_plugins));
    }

    $inventory = array();

    foreach ($active_files as $plugin_file) {
        if (!isset($all_plugins[$plugin_file])) {
            continue;
        }

        $inventory[] = array(
            'name' => (string) $all_plugins[$plugin_file]['Name'],
            'version' => (string) $all_plugins[$plugin_file]['Version'],
            'file' => (string) $plugin_file,
        );
    }

    usort(
        $inventory,
        static function ($left, $right) {
            return strcasecmp($left['name'], $right['name']);
        }
    );

    return $inventory;
}

/**
 * Restituisce un conteggio post sicuro.
 *
 * @param string $post_type Tipo di contenuto.
 * @param string $status Stato richiesto.
 * @return int
 */
function bodyenergy_get_post_count($post_type, $status)
{
    if (!post_type_exists($post_type)) {
        return 0;
    }

    $counts = wp_count_posts($post_type);

    return isset($counts->{$status}) ? (int) $counts->{$status} : 0;
}

/**
 * Visualizza la mappatura tecnica in sola lettura.
 */
function bodyenergy_render_site_audit_page()
{
    if (!current_user_can('manage_options')) {
        wp_die(esc_html__('Non hai i permessi per visualizzare questa pagina.', 'bodyenergy-wordpress'));
    }

    if (!function_exists('get_plugins')) {
        require_once ABSPATH . 'wp-admin/includes/plugin.php';
    }

    $theme = wp_get_theme();
    $parent_theme = $theme->parent();
    $active_plugins = bodyenergy_get_active_plugin_inventory();
    $all_plugins = get_plugins();
    $menus = wp_get_nav_menus();
    $permalink_structure = (string) get_option('permalink_structure');

    $pages_published = bodyenergy_get_post_count('page', 'publish');
    $pages_draft = bodyenergy_get_post_count('page', 'draft');
    $products_published = bodyenergy_get_post_count('product', 'publish');
    $products_draft = bodyenergy_get_post_count('product', 'draft');

    $environment = function_exists('wp_get_environment_type') ? wp_get_environment_type() : 'production';
    $host = wp_parse_url(home_url('/'), PHP_URL_HOST);

    if (is_string($host) && false !== strpos($host, 'wpcomstaging.com')) {
        $environment = 'staging';
    }

    $elementor_version = defined('ELEMENTOR_VERSION') ? ELEMENTOR_VERSION : 'Non rilevato';
    $woocommerce_version = defined('WC_VERSION') ? WC_VERSION : 'Non rilevato';
    ?>
    <div class="wrap bodyenergy-audit">
        <div class="bodyenergy-audit__hero">
            <div>
                <p class="bodyenergy-audit__eyebrow">ANALISI IN SOLA LETTURA</p>
                <h1>Mappatura tecnica del sito</h1>
                <p>Inventario di tema, plugin e struttura WordPress. Nessun dato cliente, ordine o pagamento viene aperto o modificato.</p>
            </div>
            <span class="bodyenergy-audit__badge bodyenergy-audit__badge--ok">Audit disponibile</span>
        </div>

        <div class="bodyenergy-audit__stats">
            <section>
                <span>Plugin attivi</span>
                <strong><?php echo esc_html((string) count($active_plugins)); ?></strong>
                <small><?php echo esc_html((string) count($all_plugins)); ?> installati</small>
            </section>
            <section>
                <span>Pagine pubblicate</span>
                <strong><?php echo esc_html((string) $pages_published); ?></strong>
                <small><?php echo esc_html((string) $pages_draft); ?> bozze</small>
            </section>
            <section>
                <span>Prodotti pubblicati</span>
                <strong><?php echo esc_html((string) $products_published); ?></strong>
                <small><?php echo esc_html((string) $products_draft); ?> bozze</small>
            </section>
            <section>
                <span>Menu registrati</span>
                <strong><?php echo esc_html((string) count($menus)); ?></strong>
                <small>Struttura navigazione</small>
            </section>
        </div>

        <div class="bodyenergy-audit__columns">
            <section class="bodyenergy-audit__panel">
                <div class="bodyenergy-audit__panel-head">
                    <div>
                        <p class="bodyenergy-audit__eyebrow">TEMA E AMBIENTE</p>
                        <h2>Configurazione principale</h2>
                    </div>
                    <span class="bodyenergy-audit__muted"><?php echo esc_html(current_time('d/m/Y H:i')); ?></span>
                </div>

                <dl class="bodyenergy-audit__details">
                    <div><dt>Ambiente</dt><dd><?php echo esc_html(ucfirst((string) $environment)); ?></dd></div>
                    <div><dt>Tema attivo</dt><dd><?php echo esc_html($theme->get('Name')); ?></dd></div>
                    <div><dt>Versione tema</dt><dd><?php echo esc_html($theme->get('Version')); ?></dd></div>
                    <div><dt>Child theme</dt><dd><?php echo esc_html(is_child_theme() ? 'Sì' : 'No'); ?></dd></div>
                    <div><dt>Tema genitore</dt><dd><?php echo esc_html($parent_theme ? $parent_theme->get('Name') : 'Nessuno'); ?></dd></div>
                    <div><dt>HTTPS</dt><dd><?php echo esc_html(is_ssl() ? 'Attivo' : 'Non rilevato'); ?></dd></div>
                    <div><dt>Permalink</dt><dd><?php echo esc_html('' !== $permalink_structure ? $permalink_structure : 'Semplici'); ?></dd></div>
                    <div><dt>WP_DEBUG</dt><dd><?php echo esc_html(defined('WP_DEBUG') && WP_DEBUG ? 'Attivo' : 'Disattivato'); ?></dd></div>
                </dl>
            </section>

            <section class="bodyenergy-audit__panel">
                <div class="bodyenergy-audit__panel-head">
                    <div>
                        <p class="bodyenergy-audit__eyebrow">SERVIZI CHIAVE</p>
                        <h2>Versioni rilevate</h2>
                    </div>
                </div>

                <div class="bodyenergy-audit__service">
                    <div><strong>WordPress</strong><span>CMS principale</span></div>
                    <b><?php echo esc_html(get_bloginfo('version')); ?></b>
                </div>
                <div class="bodyenergy-audit__service">
                    <div><strong>PHP</strong><span>Runtime del server</span></div>
                    <b><?php echo esc_html(PHP_VERSION); ?></b>
                </div>
                <div class="bodyenergy-audit__service">
                    <div><strong>Elementor</strong><span>Costruzione pagine</span></div>
                    <b><?php echo esc_html((string) $elementor_version); ?></b>
                </div>
                <div class="bodyenergy-audit__service">
                    <div><strong>WooCommerce</strong><span>E-commerce WordPress</span></div>
                    <b><?php echo esc_html((string) $woocommerce_version); ?></b>
                </div>
            </section>
        </div>

        <section class="bodyenergy-audit__panel bodyenergy-audit__panel--wide">
            <div class="bodyenergy-audit__panel-head">
                <div>
                    <p class="bodyenergy-audit__eyebrow">INVENTARIO</p>
                    <h2>Plugin attivi</h2>
                </div>
                <span class="bodyenergy-audit__muted">Solo nome e versione</span>
            </div>

            <div class="bodyenergy-audit__plugins">
                <?php foreach ($active_plugins as $plugin) : ?>
                    <div class="bodyenergy-audit__plugin">
                        <div>
                            <strong><?php echo esc_html($plugin['name']); ?></strong>
                            <span><?php echo esc_html($plugin['file']); ?></span>
                        </div>
                        <span class="bodyenergy-audit__version">v<?php echo esc_html($plugin['version']); ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

        <div class="bodyenergy-audit__notice">
            <span class="dashicons dashicons-shield-alt"></span>
            <div>
                <strong>Confine dati rispettato</strong>
                <p>L'audit non mostra nominativi, email, ordini, pagamenti, credenziali, chiavi API o informazioni provenienti da BodyGate.</p>
            </div>
        </div>
    </div>

    <style>
        .bodyenergy-audit { max-width: 1180px; margin-top: 24px; color: #f4f4f5; }
        .bodyenergy-audit * { box-sizing: border-box; }
        .bodyenergy-audit h1, .bodyenergy-audit h2, .bodyenergy-audit p { margin-top: 0; }
        .bodyenergy-audit__hero, .bodyenergy-audit__panel, .bodyenergy-audit__stats section, .bodyenergy-audit__notice { border: 1px solid #2d2d33; background: #111114; box-shadow: 0 18px 50px rgba(0,0,0,.18); }
        .bodyenergy-audit__hero { display: flex; align-items: flex-start; justify-content: space-between; gap: 28px; padding: 32px 34px; border-top: 3px solid #e3262e; border-radius: 18px; }
        .bodyenergy-audit__hero h1 { margin-bottom: 10px; color: #fff; font-size: 30px; line-height: 1.15; }
        .bodyenergy-audit__hero p:not(.bodyenergy-audit__eyebrow) { max-width: 760px; margin-bottom: 0; color: #a1a1aa; font-size: 14px; }
        .bodyenergy-audit__eyebrow { margin-bottom: 9px; color: #ef4444; font-size: 11px; font-weight: 800; letter-spacing: .16em; }
        .bodyenergy-audit__badge { display: inline-flex; align-items: center; min-height: 27px; padding: 2px 11px; border-radius: 999px; font-size: 11px; font-weight: 700; white-space: nowrap; }
        .bodyenergy-audit__badge--ok { border: 1px solid rgba(34,197,94,.32); background: rgba(34,197,94,.12); color: #86efac; }
        .bodyenergy-audit__stats { display: grid; grid-template-columns: repeat(4,minmax(0,1fr)); gap: 16px; margin-top: 16px; }
        .bodyenergy-audit__stats section { min-height: 148px; padding: 23px; border-radius: 15px; }
        .bodyenergy-audit__stats span, .bodyenergy-audit__stats small { display: block; color: #8f8f99; font-size: 12px; }
        .bodyenergy-audit__stats strong { display: block; margin: 12px 0 16px; color: #fff; font-size: 27px; }
        .bodyenergy-audit__columns { display: grid; grid-template-columns: 1.15fr .85fr; gap: 16px; margin-top: 16px; }
        .bodyenergy-audit__panel { padding: 28px; border-radius: 18px; }
        .bodyenergy-audit__panel--wide { margin-top: 16px; }
        .bodyenergy-audit__panel-head { display: flex; align-items: center; justify-content: space-between; gap: 20px; }
        .bodyenergy-audit__panel h2 { margin-bottom: 0; color: #fff; }
        .bodyenergy-audit__muted { color: #8f8f99; font-size: 12px; }
        .bodyenergy-audit__details { margin: 22px 0 0; border-top: 1px solid #2d2d33; }
        .bodyenergy-audit__details div { display: grid; grid-template-columns: 150px 1fr; gap: 16px; padding: 14px 0; border-bottom: 1px solid #2d2d33; }
        .bodyenergy-audit__details dt { color: #8f8f99; }
        .bodyenergy-audit__details dd { margin: 0; color: #fff; font-weight: 600; overflow-wrap: anywhere; }
        .bodyenergy-audit__service { display: flex; align-items: center; justify-content: space-between; gap: 20px; padding: 18px 0; border-bottom: 1px solid #2d2d33; }
        .bodyenergy-audit__service:first-of-type { margin-top: 17px; border-top: 1px solid #2d2d33; }
        .bodyenergy-audit__service strong, .bodyenergy-audit__service span { display: block; }
        .bodyenergy-audit__service strong { color: #fff; }
        .bodyenergy-audit__service span { margin-top: 4px; color: #8f8f99; font-size: 12px; }
        .bodyenergy-audit__service b { color: #fca5a5; font-size: 12px; }
        .bodyenergy-audit__plugins { display: grid; grid-template-columns: repeat(2,minmax(0,1fr)); gap: 0 24px; margin-top: 20px; border-top: 1px solid #2d2d33; }
        .bodyenergy-audit__plugin { display: flex; align-items: center; justify-content: space-between; gap: 18px; min-width: 0; padding: 16px 0; border-bottom: 1px solid #2d2d33; }
        .bodyenergy-audit__plugin strong, .bodyenergy-audit__plugin span { display: block; }
        .bodyenergy-audit__plugin strong { color: #fff; }
        .bodyenergy-audit__plugin div span { max-width: 430px; margin-top: 4px; color: #73737d; font-size: 11px; overflow-wrap: anywhere; }
        .bodyenergy-audit__version { flex: 0 0 auto; color: #86efac; font-size: 11px; font-weight: 700; }
        .bodyenergy-audit__notice { display: flex; align-items: flex-start; gap: 16px; margin-top: 16px; padding: 22px 25px; border-radius: 15px; }
        .bodyenergy-audit__notice .dashicons { width: 28px; height: 28px; color: #ef4444; font-size: 28px; }
        .bodyenergy-audit__notice strong { color: #fff; }
        .bodyenergy-audit__notice p { margin: 5px 0 0; color: #8f8f99; }
        @media (max-width: 1100px) { .bodyenergy-audit__stats { grid-template-columns: repeat(2,minmax(0,1fr)); } .bodyenergy-audit__columns { grid-template-columns: 1fr; } }
        @media (max-width: 700px) { .bodyenergy-audit__hero, .bodyenergy-audit__panel-head { align-items: flex-start; flex-direction: column; } .bodyenergy-audit__stats, .bodyenergy-audit__plugins { grid-template-columns: 1fr; } .bodyenergy-audit__details div { grid-template-columns: 1fr; gap: 5px; } }
    </style>
    <?php
}
