<?php
/**
 * Mappa contenuti e integrazioni in sola lettura.
 *
 * @package BodyEnergyWordPress
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Registra la pagina di mappatura contenuti.
 */
function bodyenergy_register_content_map_page()
{
    add_submenu_page(
        'bodyenergy-bodygate',
        'Mappa contenuti',
        'Mappa contenuti',
        'manage_options',
        'bodyenergy-content-map',
        'bodyenergy_render_content_map_page'
    );
}
add_action('admin_menu', 'bodyenergy_register_content_map_page', 30);

/**
 * Determina se una pagina usa Elementor.
 *
 * @param int $post_id ID pagina.
 * @return bool
 */
function bodyenergy_page_uses_elementor($post_id)
{
    $edit_mode = (string) get_post_meta($post_id, '_elementor_edit_mode', true);
    $elementor_data = (string) get_post_meta($post_id, '_elementor_data', true);

    return 'builder' === $edit_mode || '' !== trim($elementor_data);
}

/**
 * Cerca riferimenti tecnici senza mostrare il contenuto della pagina.
 *
 * @param WP_Post $page Pagina WordPress.
 * @param string  $needle Testo da cercare.
 * @return bool
 */
function bodyenergy_page_contains_reference($page, $needle)
{
    $sources = array(
        (string) $page->post_content,
        (string) get_post_meta($page->ID, '_elementor_data', true),
    );

    foreach ($sources as $source) {
        if ('' !== $source && false !== stripos($source, $needle)) {
            return true;
        }
    }

    return false;
}

/**
 * Restituisce il ruolo tecnico della pagina.
 *
 * @param int $post_id ID pagina.
 * @return string
 */
function bodyenergy_get_page_role($post_id)
{
    $roles = array();

    if ((int) get_option('page_on_front') === $post_id) {
        $roles[] = 'Homepage';
    }

    if ((int) get_option('page_for_posts') === $post_id) {
        $roles[] = 'Pagina articoli';
    }

    if (function_exists('wc_get_page_id')) {
        $wc_pages = array(
            'shop' => 'Shop',
            'cart' => 'Carrello',
            'checkout' => 'Pagamento',
            'myaccount' => 'Account cliente',
        );

        foreach ($wc_pages as $key => $label) {
            if ((int) wc_get_page_id($key) === $post_id) {
                $roles[] = $label;
            }
        }
    }

    return empty($roles) ? 'Pagina standard' : implode(' · ', $roles);
}

/**
 * Crea una pillola di stato.
 *
 * @param string $label Etichetta.
 * @param string $type Tipo grafico.
 * @return string
 */
function bodyenergy_content_map_badge($label, $type = 'neutral')
{
    $allowed = array('ok', 'warning', 'neutral');
    $safe_type = in_array($type, $allowed, true) ? $type : 'neutral';

    return sprintf(
        '<span class="bodyenergy-map__badge bodyenergy-map__badge--%1$s">%2$s</span>',
        esc_attr($safe_type),
        esc_html($label)
    );
}

/**
 * Mostra la mappa dei contenuti senza esporre dati cliente.
 */
function bodyenergy_render_content_map_page()
{
    if (!current_user_can('manage_options')) {
        wp_die(esc_html__('Non hai i permessi per visualizzare questa pagina.', 'bodyenergy-wordpress'));
    }

    $pages = get_posts(
        array(
            'post_type' => 'page',
            'post_status' => array('publish', 'draft', 'private', 'pending'),
            'numberposts' => -1,
            'orderby' => 'menu_order title',
            'order' => 'ASC',
        )
    );

    $templates = post_type_exists('elementor_library')
        ? get_posts(
            array(
                'post_type' => 'elementor_library',
                'post_status' => array('publish', 'draft'),
                'numberposts' => -1,
                'orderby' => 'title',
                'order' => 'ASC',
            )
        )
        : array();

    $elementor_pages = 0;
    $amelia_pages = 0;
    $woocommerce_pages = 0;

    foreach ($pages as $page) {
        if (bodyenergy_page_uses_elementor($page->ID)) {
            ++$elementor_pages;
        }

        if (bodyenergy_page_contains_reference($page, 'amelia')) {
            ++$amelia_pages;
        }

        if (
            bodyenergy_page_contains_reference($page, 'woocommerce') ||
            bodyenergy_page_contains_reference($page, 'product') ||
            false !== stripos(bodyenergy_get_page_role($page->ID), 'Shop') ||
            false !== stripos(bodyenergy_get_page_role($page->ID), 'Carrello') ||
            false !== stripos(bodyenergy_get_page_role($page->ID), 'Pagamento') ||
            false !== stripos(bodyenergy_get_page_role($page->ID), 'Account cliente')
        ) {
            ++$woocommerce_pages;
        }
    }
    ?>
    <div class="wrap bodyenergy-map">
        <div class="bodyenergy-map__hero">
            <div>
                <p class="bodyenergy-map__eyebrow">STRUTTURA PUBBLICA IN SOLA LETTURA</p>
                <h1>Mappa contenuti e prenotazioni</h1>
                <p>Identifica come sono costruite le pagine, dove compare Elementor e se esistono riferimenti tecnici ad Amelia o WooCommerce.</p>
            </div>
            <?php echo bodyenergy_content_map_badge('Nessun dato cliente aperto', 'ok'); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        </div>

        <div class="bodyenergy-map__stats">
            <section><span>Pagine rilevate</span><strong><?php echo esc_html((string) count($pages)); ?></strong><small>Pubblicate e bozze</small></section>
            <section><span>Pagine Elementor</span><strong><?php echo esc_html((string) $elementor_pages); ?></strong><small>Builder rilevato</small></section>
            <section><span>Riferimenti Amelia</span><strong><?php echo esc_html((string) $amelia_pages); ?></strong><small>Nelle pagine o nei dati Elementor</small></section>
            <section><span>Riferimenti WooCommerce</span><strong><?php echo esc_html((string) $woocommerce_pages); ?></strong><small>Pagine o ruoli tecnici</small></section>
        </div>

        <section class="bodyenergy-map__panel">
            <div class="bodyenergy-map__panel-head">
                <div>
                    <p class="bodyenergy-map__eyebrow">PAGINE</p>
                    <h2>Architettura dei contenuti</h2>
                </div>
                <span class="bodyenergy-map__muted">Titolo, ruolo e tecnologia</span>
            </div>

            <div class="bodyenergy-map__table-wrap">
                <table class="bodyenergy-map__table">
                    <thead>
                        <tr>
                            <th>Pagina</th>
                            <th>Stato</th>
                            <th>Ruolo</th>
                            <th>Builder</th>
                            <th>Amelia</th>
                            <th>WooCommerce</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pages as $page) :
                            $uses_elementor = bodyenergy_page_uses_elementor($page->ID);
                            $uses_amelia = bodyenergy_page_contains_reference($page, 'amelia');
                            $role = bodyenergy_get_page_role($page->ID);
                            $uses_woocommerce =
                                bodyenergy_page_contains_reference($page, 'woocommerce') ||
                                bodyenergy_page_contains_reference($page, 'product') ||
                                'Pagina standard' !== $role;
                            $template_slug = (string) get_page_template_slug($page->ID);
                            $template_label = '' === $template_slug ? 'Predefinito' : $template_slug;
                            ?>
                            <tr>
                                <td>
                                    <strong><?php echo esc_html(get_the_title($page)); ?></strong>
                                    <span>/<?php echo esc_html($page->post_name); ?> · <?php echo esc_html($template_label); ?></span>
                                </td>
                                <td><?php echo bodyenergy_content_map_badge(ucfirst((string) $page->post_status), 'publish' === $page->post_status ? 'ok' : 'warning'); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
                                <td><?php echo esc_html($role); ?></td>
                                <td><?php echo bodyenergy_content_map_badge($uses_elementor ? 'Elementor' : 'Editor WordPress', $uses_elementor ? 'ok' : 'neutral'); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
                                <td><?php echo bodyenergy_content_map_badge($uses_amelia ? 'Rilevato' : 'No', $uses_amelia ? 'ok' : 'neutral'); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
                                <td><?php echo bodyenergy_content_map_badge($uses_woocommerce ? 'Rilevato' : 'No', $uses_woocommerce ? 'ok' : 'neutral'); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <section class="bodyenergy-map__panel">
            <div class="bodyenergy-map__panel-head">
                <div>
                    <p class="bodyenergy-map__eyebrow">ELEMENTOR</p>
                    <h2>Template salvati</h2>
                </div>
                <span class="bodyenergy-map__muted"><?php echo esc_html((string) count($templates)); ?> elementi</span>
            </div>

            <?php if (empty($templates)) : ?>
                <div class="bodyenergy-map__empty">Nessun template Elementor salvato rilevato.</div>
            <?php else : ?>
                <div class="bodyenergy-map__template-grid">
                    <?php foreach ($templates as $template) :
                        $template_type = (string) get_post_meta($template->ID, '_elementor_template_type', true);
                        ?>
                        <article>
                            <strong><?php echo esc_html(get_the_title($template)); ?></strong>
                            <span><?php echo esc_html('' !== $template_type ? $template_type : 'template'); ?> · <?php echo esc_html($template->post_status); ?></span>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>

        <div class="bodyenergy-map__notice">
            <span class="dashicons dashicons-shield-alt"></span>
            <div>
                <strong>Analisi non invasiva</strong>
                <p>La pagina verifica solo struttura, metadati tecnici e presenza di riferimenti. Non apre prenotazioni Amelia, ordini WooCommerce, utenti, email o pagamenti.</p>
            </div>
        </div>
    </div>

    <style>
        .bodyenergy-map { max-width: 1180px; margin-top: 24px; color: #f4f4f5; }
        .bodyenergy-map * { box-sizing: border-box; }
        .bodyenergy-map h1, .bodyenergy-map h2, .bodyenergy-map p { margin-top: 0; }
        .bodyenergy-map__hero, .bodyenergy-map__panel, .bodyenergy-map__stats section, .bodyenergy-map__notice { border: 1px solid #2d2d33; background: #111114; box-shadow: 0 18px 50px rgba(0,0,0,.18); }
        .bodyenergy-map__hero { display: flex; align-items: flex-start; justify-content: space-between; gap: 28px; padding: 32px 34px; border-top: 3px solid #e3262e; border-radius: 18px; }
        .bodyenergy-map__hero h1 { margin-bottom: 10px; color: #fff; font-size: 30px; line-height: 1.15; }
        .bodyenergy-map__hero p:not(.bodyenergy-map__eyebrow) { max-width: 790px; margin-bottom: 0; color: #a1a1aa; font-size: 14px; }
        .bodyenergy-map__eyebrow { margin-bottom: 9px; color: #ef4444; font-size: 11px; font-weight: 800; letter-spacing: .16em; }
        .bodyenergy-map__badge { display: inline-flex; align-items: center; min-height: 27px; padding: 2px 11px; border-radius: 999px; font-size: 11px; font-weight: 700; white-space: nowrap; }
        .bodyenergy-map__badge--ok { border: 1px solid rgba(34,197,94,.32); background: rgba(34,197,94,.12); color: #86efac; }
        .bodyenergy-map__badge--warning { border: 1px solid rgba(245,158,11,.32); background: rgba(245,158,11,.12); color: #fcd34d; }
        .bodyenergy-map__badge--neutral { border: 1px solid rgba(239,68,68,.22); background: rgba(239,68,68,.08); color: #fca5a5; }
        .bodyenergy-map__stats { display: grid; grid-template-columns: repeat(4,minmax(0,1fr)); gap: 16px; margin-top: 16px; }
        .bodyenergy-map__stats section { min-height: 145px; padding: 23px; border-radius: 15px; }
        .bodyenergy-map__stats span, .bodyenergy-map__stats small { display: block; color: #8f8f99; font-size: 12px; }
        .bodyenergy-map__stats strong { display: block; margin: 12px 0 16px; color: #fff; font-size: 27px; }
        .bodyenergy-map__panel { margin-top: 16px; padding: 28px; border-radius: 18px; }
        .bodyenergy-map__panel-head { display: flex; align-items: center; justify-content: space-between; gap: 20px; }
        .bodyenergy-map__panel h2 { margin-bottom: 0; color: #fff; }
        .bodyenergy-map__muted { color: #8f8f99; font-size: 12px; }
        .bodyenergy-map__table-wrap { overflow-x: auto; margin-top: 22px; border: 1px solid #2d2d33; border-radius: 14px; }
        .bodyenergy-map__table { width: 100%; border-collapse: collapse; min-width: 940px; }
        .bodyenergy-map__table th, .bodyenergy-map__table td { padding: 16px; border-bottom: 1px solid #2d2d33; text-align: left; vertical-align: middle; }
        .bodyenergy-map__table th { color: #8f8f99; font-size: 11px; text-transform: uppercase; letter-spacing: .08em; }
        .bodyenergy-map__table td { color: #e4e4e7; font-size: 13px; }
        .bodyenergy-map__table td:first-child strong, .bodyenergy-map__table td:first-child span { display: block; }
        .bodyenergy-map__table td:first-child strong { color: #fff; margin-bottom: 5px; }
        .bodyenergy-map__table td:first-child span { color: #8f8f99; font-size: 11px; }
        .bodyenergy-map__table tbody tr:last-child td { border-bottom: 0; }
        .bodyenergy-map__template-grid { display: grid; grid-template-columns: repeat(3,minmax(0,1fr)); gap: 12px; margin-top: 22px; }
        .bodyenergy-map__template-grid article { padding: 18px; border: 1px solid #2d2d33; border-radius: 13px; background: #17171b; }
        .bodyenergy-map__template-grid strong, .bodyenergy-map__template-grid span { display: block; }
        .bodyenergy-map__template-grid strong { color: #fff; margin-bottom: 7px; }
        .bodyenergy-map__template-grid span, .bodyenergy-map__empty { color: #8f8f99; font-size: 12px; }
        .bodyenergy-map__empty { margin-top: 22px; padding: 22px; border: 1px dashed #3b3b42; border-radius: 13px; }
        .bodyenergy-map__notice { display: flex; align-items: flex-start; gap: 16px; margin-top: 16px; padding: 22px 25px; border-radius: 15px; }
        .bodyenergy-map__notice .dashicons { width: 28px; height: 28px; color: #ef4444; font-size: 28px; }
        .bodyenergy-map__notice strong { color: #fff; }
        .bodyenergy-map__notice p { margin: 5px 0 0; color: #8f8f99; }
        @media (max-width: 1050px) { .bodyenergy-map__stats { grid-template-columns: repeat(2,minmax(0,1fr)); } .bodyenergy-map__template-grid { grid-template-columns: repeat(2,minmax(0,1fr)); } }
        @media (max-width: 700px) { .bodyenergy-map__hero, .bodyenergy-map__panel-head { align-items: flex-start; flex-direction: column; } .bodyenergy-map__stats, .bodyenergy-map__template-grid { grid-template-columns: 1fr; } }
    </style>
    <?php
}
