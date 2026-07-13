<?php
/**
 * Centro di controllo amministrativo Body Energy / BodyGate.
 *
 * @package BodyEnergyWordPress
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Registra il menu principale del plugin.
 */
function bodyenergy_register_admin_page()
{
    add_menu_page(
        'Body Energy / BodyGate',
        'Body Energy',
        'manage_options',
        'bodyenergy-bodygate',
        'bodyenergy_render_admin_page',
        'dashicons-heart',
        58
    );
}
add_action('admin_menu', 'bodyenergy_register_admin_page');

/**
 * Crea un badge di stato sicuro.
 *
 * @param string $label Testo del badge.
 * @param string $status Stato grafico.
 * @return string
 */
function bodyenergy_status_badge($label, $status = 'neutral')
{
    $allowed_statuses = array('ok', 'warning', 'neutral');
    $safe_status = in_array($status, $allowed_statuses, true) ? $status : 'neutral';

    return sprintf(
        '<span class="bodyenergy-badge bodyenergy-badge--%1$s">%2$s</span>',
        esc_attr($safe_status),
        esc_html($label)
    );
}

/**
 * Mostra la dashboard tecnica del collegamento.
 */
function bodyenergy_render_admin_page()
{
    if (!current_user_can('manage_options')) {
        wp_die(esc_html__('Non hai i permessi per visualizzare questa pagina.', 'bodyenergy-wordpress'));
    }

    $host = wp_parse_url(home_url('/'), PHP_URL_HOST);
    $environment = function_exists('wp_get_environment_type') ? wp_get_environment_type() : 'production';

    if (is_string($host) && false !== strpos($host, 'wpcomstaging.com')) {
        $environment = 'staging';
    }

    $environment_label = 'staging' === $environment ? 'Staging' : ucfirst((string) $environment);
    $environment_status = 'staging' === $environment ? 'ok' : 'warning';
    $woocommerce_active = class_exists('WooCommerce');
    $elementor_active = defined('ELEMENTOR_VERSION') || did_action('elementor/loaded');
    $audit_url = admin_url('admin.php?page=bodyenergy-site-audit');
    $content_map_url = admin_url('admin.php?page=bodyenergy-content-map');
    ?>
    <div class="wrap bodyenergy-admin">
        <div class="bodyenergy-hero">
            <div>
                <p class="bodyenergy-eyebrow">BODY ENERGY ASD × BODYGATE</p>
                <h1>Centro di controllo integrazione</h1>
                <p class="bodyenergy-subtitle">Stato tecnico del sito WordPress e preparazione del collegamento sicuro con BodyGate.</p>
            </div>
            <?php echo bodyenergy_status_badge('Plugin operativo', 'ok'); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        </div>

        <div class="bodyenergy-grid">
            <section class="bodyenergy-card">
                <span class="bodyenergy-card__label">Ambiente</span>
                <strong><?php echo esc_html($environment_label); ?></strong>
                <?php echo bodyenergy_status_badge('Ambiente rilevato', $environment_status); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            </section>
            <section class="bodyenergy-card">
                <span class="bodyenergy-card__label">Versione plugin</span>
                <strong><?php echo esc_html(BODYENERGY_WORDPRESS_VERSION); ?></strong>
                <?php echo bodyenergy_status_badge('Aggiornato', 'ok'); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            </section>
            <section class="bodyenergy-card">
                <span class="bodyenergy-card__label">WordPress</span>
                <strong><?php echo esc_html(get_bloginfo('version')); ?></strong>
                <span class="bodyenergy-meta">PHP <?php echo esc_html(PHP_VERSION); ?></span>
            </section>
            <section class="bodyenergy-card">
                <span class="bodyenergy-card__label">Connessione BodyGate</span>
                <strong>Non configurata</strong>
                <?php echo bodyenergy_status_badge('Nessuna credenziale salvata', 'neutral'); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            </section>
        </div>

        <div class="bodyenergy-panel">
            <div class="bodyenergy-panel__header">
                <div>
                    <p class="bodyenergy-eyebrow">COMPATIBILITÀ</p>
                    <h2>Servizi WordPress rilevati</h2>
                </div>
                <span class="bodyenergy-panel__note">Controllo in sola lettura</span>
            </div>
            <div class="bodyenergy-service-list">
                <div class="bodyenergy-service">
                    <div><strong>WooCommerce</strong><span>Shop, ordini e pagamenti WordPress</span></div>
                    <?php echo bodyenergy_status_badge($woocommerce_active ? 'Attivo' : 'Non rilevato', $woocommerce_active ? 'ok' : 'warning'); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                </div>
                <div class="bodyenergy-service">
                    <div><strong>Elementor</strong><span>Gestione grafica delle pagine pubbliche</span></div>
                    <?php echo bodyenergy_status_badge($elementor_active ? 'Attivo' : 'Non rilevato', $elementor_active ? 'ok' : 'warning'); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                </div>
                <div class="bodyenergy-service">
                    <div><strong>BodyGate API</strong><span>Lead, prenotazioni e area clienti saranno collegati tramite API protette</span></div>
                    <?php echo bodyenergy_status_badge('Da configurare', 'neutral'); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                </div>
            </div>
        </div>

        <div class="bodyenergy-actions">
            <a class="bodyenergy-action" href="<?php echo esc_url($audit_url); ?>">
                <span class="dashicons dashicons-analytics"></span>
                <div><strong>Mappatura tecnica</strong><small>Tema, ambiente e plugin attivi</small></div>
            </a>
            <a class="bodyenergy-action" href="<?php echo esc_url($content_map_url); ?>">
                <span class="dashicons dashicons-layout"></span>
                <div><strong>Mappa contenuti</strong><small>Pagine, Elementor, Amelia e WooCommerce</small></div>
            </a>
        </div>

        <div class="bodyenergy-safety">
            <span class="dashicons dashicons-shield-alt"></span>
            <div>
                <strong>Configurazione sicura</strong>
                <p>Questa versione non legge clienti, ordini o pagamenti e non contiene chiavi Supabase o credenziali amministrative di BodyGate.</p>
            </div>
        </div>
    </div>

    <style>
        .bodyenergy-admin { max-width: 1180px; margin-top: 24px; color: #f4f4f5; }
        .bodyenergy-admin * { box-sizing: border-box; }
        .bodyenergy-admin h1, .bodyenergy-admin h2, .bodyenergy-admin p { margin-top: 0; }
        .bodyenergy-hero, .bodyenergy-panel, .bodyenergy-safety, .bodyenergy-card, .bodyenergy-action { border: 1px solid #2d2d33; background: #111114; box-shadow: 0 18px 50px rgba(0,0,0,.18); }
        .bodyenergy-hero { display: flex; align-items: flex-start; justify-content: space-between; gap: 32px; padding: 34px; border-radius: 18px; border-top: 3px solid #e3262e; }
        .bodyenergy-hero h1 { margin-bottom: 10px; color: #fff; font-size: 30px; line-height: 1.15; }
        .bodyenergy-eyebrow { margin-bottom: 9px; color: #ef4444; font-size: 11px; font-weight: 800; letter-spacing: .16em; }
        .bodyenergy-subtitle { max-width: 700px; margin-bottom: 0; color: #a1a1aa; font-size: 15px; }
        .bodyenergy-grid { display: grid; grid-template-columns: repeat(4,minmax(0,1fr)); gap: 16px; margin-top: 16px; }
        .bodyenergy-card { min-height: 172px; padding: 24px; border-radius: 15px; }
        .bodyenergy-card__label, .bodyenergy-meta { display: block; color: #8f8f99; font-size: 12px; }
        .bodyenergy-card strong { display: block; margin: 13px 0 18px; color: #fff; font-size: 22px; }
        .bodyenergy-panel { margin-top: 16px; padding: 28px; border-radius: 18px; }
        .bodyenergy-panel__header, .bodyenergy-service { display: flex; align-items: center; justify-content: space-between; gap: 24px; }
        .bodyenergy-panel h2 { margin-bottom: 0; color: #fff; }
        .bodyenergy-panel__note { color: #8f8f99; font-size: 12px; }
        .bodyenergy-service-list { margin-top: 24px; border-top: 1px solid #2d2d33; }
        .bodyenergy-service { padding: 19px 0; border-bottom: 1px solid #2d2d33; }
        .bodyenergy-service strong, .bodyenergy-service span { display: block; }
        .bodyenergy-service strong { margin-bottom: 5px; color: #fff; font-size: 14px; }
        .bodyenergy-service span:not(.bodyenergy-badge) { color: #8f8f99; font-size: 12px; }
        .bodyenergy-badge { display: inline-flex; align-items: center; min-height: 25px; padding: 2px 10px; border-radius: 999px; font-size: 11px; font-weight: 700; white-space: nowrap; }
        .bodyenergy-badge--ok { border: 1px solid rgba(34,197,94,.32); background: rgba(34,197,94,.12); color: #86efac; }
        .bodyenergy-badge--warning { border: 1px solid rgba(245,158,11,.32); background: rgba(245,158,11,.12); color: #fcd34d; }
        .bodyenergy-badge--neutral { border: 1px solid rgba(239,68,68,.25); background: rgba(239,68,68,.09); color: #fca5a5; }
        .bodyenergy-actions { display: grid; grid-template-columns: repeat(2,minmax(0,1fr)); gap: 16px; margin-top: 16px; }
        .bodyenergy-action { display: flex; align-items: center; gap: 16px; padding: 22px 24px; border-radius: 15px; text-decoration: none; }
        .bodyenergy-action:hover { border-color: #ef4444; background: #17171b; }
        .bodyenergy-action .dashicons { width: 30px; height: 30px; color: #ef4444; font-size: 30px; }
        .bodyenergy-action strong, .bodyenergy-action small { display: block; }
        .bodyenergy-action strong { color: #fff; margin-bottom: 5px; }
        .bodyenergy-action small { color: #8f8f99; }
        .bodyenergy-safety { display: flex; align-items: flex-start; gap: 16px; margin-top: 16px; padding: 22px 25px; border-radius: 15px; }
        .bodyenergy-safety .dashicons { width: 28px; height: 28px; color: #ef4444; font-size: 28px; }
        .bodyenergy-safety strong { color: #fff; }
        .bodyenergy-safety p { margin: 5px 0 0; color: #8f8f99; }
        @media (max-width: 1100px) { .bodyenergy-grid { grid-template-columns: repeat(2,minmax(0,1fr)); } }
        @media (max-width: 700px) { .bodyenergy-hero, .bodyenergy-panel__header, .bodyenergy-service { align-items: flex-start; flex-direction: column; } .bodyenergy-grid, .bodyenergy-actions { grid-template-columns: 1fr; } }
    </style>
    <?php
}
