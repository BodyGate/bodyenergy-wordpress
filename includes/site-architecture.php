<?php
/**
 * Architettura iniziale del sito pubblico Body Energy.
 *
 * Crea soltanto pagine in bozza e un menu non assegnato, esclusivamente
 * nell'ambiente WordPress.com di staging. L'operazione è idempotente.
 *
 * @package BodyEnergyWordPress
 */

if (!defined('ABSPATH')) {
    exit;
}

function bodyenergy_site_architecture_version()
{
    return '1.0.0';
}

function bodyenergy_is_staging_site()
{
    $host = (string) wp_parse_url(home_url('/'), PHP_URL_HOST);
    $environment = function_exists('wp_get_environment_type') ? wp_get_environment_type() : '';

    return 'staging' === $environment || false !== strpos($host, 'wpcomstaging.com');
}

function bodyenergy_site_blueprint()
{
    return array(
        'palestra' => array(
            'title' => 'Palestra',
            'slug' => 'palestra-palermo',
            'aliases' => array('palestra-palermo', 'palestra'),
            'eyebrow' => 'BODY ENERGY ASD · PALERMO',
            'description' => 'Sala fitness, attrezzature, ambienti, spogliatoi e climatizzazione completa. La pagina sarà completata con fotografie reali della struttura.',
        ),
        'attivita' => array(
            'title' => 'Attività',
            'slug' => 'attivita',
            'aliases' => array('attivita'),
            'eyebrow' => 'ALLENAMENTO E BENESSERE',
            'description' => 'La panoramica delle attività realmente disponibili presso Body Energy, organizzata in modo chiaro e senza servizi generici o non presenti.',
        ),
        'pilates' => array(
            'title' => 'Pilates Reformer',
            'slug' => 'pilates-reformer-palermo',
            'aliases' => array('pilates-reformer-palermo', 'pilates-reformer'),
            'eyebrow' => 'CINQUE POSTAZIONI · PALERMO',
            'description' => 'La pagina dedicata al Pilates Reformer con cinque postazioni, piccoli gruppi e richiesta informazioni.',
            'content' => '[bodyenergy_pilates_landing]',
        ),
        'formule' => array(
            'title' => 'Formule',
            'slug' => 'formule',
            'aliases' => array('formule', 'abbonamenti'),
            'eyebrow' => 'SOLUZIONI BODY ENERGY',
            'description' => 'Formule di frequenza, servizi inclusi, quota associativa, promozioni e risposte alle domande più frequenti.',
        ),
        'chi-siamo' => array(
            'title' => 'Chi siamo',
            'slug' => 'chi-siamo',
            'aliases' => array('chi-siamo'),
            'eyebrow' => 'IDENTITÀ BODY ENERGY',
            'description' => 'La storia, i valori, il metodo di lavoro e il team di Body Energy ASD.',
        ),
        'contatti' => array(
            'title' => 'Contatti',
            'slug' => 'contatti',
            'aliases' => array('contatti'),
            'eyebrow' => 'VIENI A CONOSCERCI',
            'description' => 'Indirizzo, orari, telefono, WhatsApp, email, mappa e richiesta prova presso Viale Amedeo D’Aosta 3, Palermo.',
        ),
        'area-soci' => array(
            'title' => 'Area soci',
            'slug' => 'area-soci',
            'aliases' => array('area-soci'),
            'eyebrow' => 'BODY ENERGY × BODYGATE',
            'description' => 'Accesso futuro a Mobile Pass, iscrizione, ricevute, notifiche e prenotazioni. La pagina resterà in bozza finché BodyGate non sarà raggiungibile in sicurezza.',
        ),
    );
}

function bodyenergy_find_blueprint_page($aliases)
{
    foreach ((array) $aliases as $slug) {
        $page = get_page_by_path($slug, OBJECT, 'page');
        if ($page instanceof WP_Post) {
            return $page;
        }
    }

    return null;
}

function bodyenergy_placeholder_shortcode($atts)
{
    $atts = shortcode_atts(array('key' => ''), $atts, 'bodyenergy_platinum_placeholder');
    $blueprint = bodyenergy_site_blueprint();
    $key = sanitize_key((string) $atts['key']);

    if (!isset($blueprint[$key])) {
        return '';
    }

    $page = $blueprint[$key];

    ob_start();
    ?>
    <main class="be-platinum-placeholder">
        <div class="be-platinum-placeholder__glow" aria-hidden="true"></div>
        <div class="be-platinum-placeholder__inner">
            <span><?php echo esc_html($page['eyebrow']); ?></span>
            <h1><?php echo esc_html($page['title']); ?></h1>
            <p><?php echo esc_html($page['description']); ?></p>
            <div class="be-platinum-placeholder__status">Pagina in progettazione · Bozza Platinum</div>
            <a href="<?php echo esc_url(home_url('/')); ?>">Torna alla Home</a>
        </div>
    </main>
    <style>
        .be-platinum-placeholder{position:relative;display:grid;min-height:72vh;place-items:center;overflow:hidden;padding:100px 24px;background:#08080a;color:#f5f5f7;font-family:Inter,ui-sans-serif,system-ui,-apple-system,BlinkMacSystemFont,"Segoe UI",sans-serif}.be-platinum-placeholder *{box-sizing:border-box}.be-platinum-placeholder__glow{position:absolute;right:-100px;top:0;width:360px;height:360px;border-radius:50%;background:rgba(227,38,46,.16);filter:blur(100px)}.be-platinum-placeholder__inner{position:relative;width:min(920px,100%);padding:60px;border:1px solid rgba(255,255,255,.1);border-radius:24px;background:linear-gradient(145deg,rgba(24,24,29,.96),rgba(10,10,13,.98));box-shadow:0 30px 90px rgba(0,0,0,.45)}.be-platinum-placeholder__inner>span{display:block;margin-bottom:18px;color:#ff3b43;font-size:12px;font-weight:800;letter-spacing:.18em}.be-platinum-placeholder h1{margin:0 0 22px;font-size:clamp(52px,8vw,94px);line-height:.94;letter-spacing:-.055em}.be-platinum-placeholder p{max-width:700px;margin:0;color:#aaaab4;font-size:18px;line-height:1.75}.be-platinum-placeholder__status{display:inline-flex;margin-top:32px;padding:9px 13px;border:1px solid rgba(255,59,67,.24);border-radius:999px;background:rgba(227,38,46,.09);color:#ff8a90;font-size:12px;font-weight:700}.be-platinum-placeholder a{display:inline-flex;margin-top:34px;color:#fff;font-weight:800;text-decoration:none}.be-platinum-placeholder a:before{content:"←";margin-right:10px;color:#ff3b43}@media(max-width:650px){.be-platinum-placeholder__inner{padding:36px 25px}.be-platinum-placeholder p{font-size:16px}}
    </style>
    <?php

    return (string) ob_get_clean();
}
add_shortcode('bodyenergy_platinum_placeholder', 'bodyenergy_placeholder_shortcode');

function bodyenergy_bootstrap_site_architecture()
{
    if (!is_admin() || !current_user_can('manage_options') || !bodyenergy_is_staging_site()) {
        return;
    }

    if (bodyenergy_site_architecture_version() === get_option('bodyenergy_site_architecture_version')) {
        return;
    }

    $page_ids = array();
    $blueprint = bodyenergy_site_blueprint();

    foreach ($blueprint as $key => $spec) {
        $existing = bodyenergy_find_blueprint_page($spec['aliases']);

        if ($existing instanceof WP_Post) {
            $page_ids[$key] = (int) $existing->ID;
            continue;
        }

        $content = isset($spec['content'])
            ? (string) $spec['content']
            : '[bodyenergy_platinum_placeholder key="' . esc_attr($key) . '"]';

        $page_id = wp_insert_post(
            array(
                'post_type' => 'page',
                'post_status' => 'draft',
                'post_title' => $spec['title'],
                'post_name' => $spec['slug'],
                'post_content' => $content,
                'post_excerpt' => $spec['description'],
                'comment_status' => 'closed',
                'ping_status' => 'closed',
            ),
            true
        );

        if (!is_wp_error($page_id)) {
            $page_ids[$key] = (int) $page_id;
        }
    }

    $menu_name = 'Body Energy Platinum – Bozza';
    $menu = wp_get_nav_menu_object($menu_name);
    $menu_id = $menu ? (int) $menu->term_id : wp_create_nav_menu($menu_name);

    if (!is_wp_error($menu_id) && $menu_id) {
        $existing_items = wp_get_nav_menu_items((int) $menu_id);
        $existing_labels = array();

        foreach ((array) $existing_items as $item) {
            $existing_labels[] = (string) $item->title;
        }

        $menu_keys = array('palestra', 'attivita', 'pilates', 'formule', 'chi-siamo', 'contatti', 'area-soci');

        foreach ($menu_keys as $position => $key) {
            if (empty($page_ids[$key])) {
                continue;
            }

            $label = $blueprint[$key]['title'];
            if (in_array($label, $existing_labels, true)) {
                continue;
            }

            wp_update_nav_menu_item(
                (int) $menu_id,
                0,
                array(
                    'menu-item-title' => $label,
                    'menu-item-object-id' => $page_ids[$key],
                    'menu-item-object' => 'page',
                    'menu-item-type' => 'post_type',
                    'menu-item-position' => $position + 1,
                    'menu-item-status' => 'publish',
                )
            );
        }
    }

    update_option('bodyenergy_site_architecture_state', array('pages' => $page_ids, 'menu_id' => is_wp_error($menu_id) ? 0 : (int) $menu_id), false);
    update_option('bodyenergy_site_architecture_version', bodyenergy_site_architecture_version(), false);
}
add_action('admin_init', 'bodyenergy_bootstrap_site_architecture', 40);

function bodyenergy_register_site_architecture_page()
{
    add_submenu_page(
        'bodyenergy-bodygate',
        'Architettura sito',
        'Architettura sito',
        'manage_options',
        'bodyenergy-site-architecture',
        'bodyenergy_render_site_architecture_page'
    );
}
add_action('admin_menu', 'bodyenergy_register_site_architecture_page', 35);

function bodyenergy_render_site_architecture_page()
{
    if (!current_user_can('manage_options')) {
        wp_die(esc_html__('Non hai i permessi per visualizzare questa pagina.', 'bodyenergy-wordpress'));
    }

    $state = (array) get_option('bodyenergy_site_architecture_state', array());
    $page_ids = isset($state['pages']) ? (array) $state['pages'] : array();
    ?>
    <div class="wrap bodyenergy-architecture">
        <h1>Architettura sito Platinum</h1>
        <p>Pagine create in bozza e menu di progetto non assegnato. Nessun contenuto viene pubblicato automaticamente.</p>
        <div class="bodyenergy-architecture__grid">
            <?php foreach (bodyenergy_site_blueprint() as $key => $spec) :
                $page_id = isset($page_ids[$key]) ? (int) $page_ids[$key] : 0;
                $page = $page_id ? get_post($page_id) : bodyenergy_find_blueprint_page($spec['aliases']);
                ?>
                <article>
                    <span><?php echo esc_html($spec['eyebrow']); ?></span>
                    <h2><?php echo esc_html($spec['title']); ?></h2>
                    <p><?php echo esc_html($spec['description']); ?></p>
                    <?php if ($page instanceof WP_Post) : ?>
                        <a href="<?php echo esc_url(get_edit_post_link($page->ID)); ?>">Apri la bozza</a>
                    <?php else : ?>
                        <strong>Da creare</strong>
                    <?php endif; ?>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
    <style>
        .bodyenergy-architecture{max-width:1180px;margin-top:24px;color:#f4f4f5}.bodyenergy-architecture>p{color:#9b9ba6}.bodyenergy-architecture__grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:16px;margin-top:24px}.bodyenergy-architecture article{padding:26px;border:1px solid #2d2d33;border-radius:17px;background:#111114;box-shadow:0 18px 50px rgba(0,0,0,.18)}.bodyenergy-architecture article>span{color:#ef4444;font-size:10px;font-weight:800;letter-spacing:.14em}.bodyenergy-architecture h2{margin:12px 0;color:#fff}.bodyenergy-architecture article p{min-height:48px;color:#9696a1}.bodyenergy-architecture a{color:#fff;font-weight:700}.bodyenergy-architecture a:hover{color:#ff3b43}@media(max-width:760px){.bodyenergy-architecture__grid{grid-template-columns:1fr}}
    </style>
    <?php
}
