<?php
/**
 * Migrazione controllata delle impostazioni del tema genitore verso il child theme.
 *
 * @package BodyEnergyWordPress
 */

if (!defined('ABSPATH')) {
    exit;
}

const BODYENERGY_PARENT_THEME_STYLESHEET = 'fitness-elementor';
const BODYENERGY_CHILD_THEME_STYLESHEET = 'bodyenergy-platinum-child';
const BODYENERGY_THEME_MIGRATION_BACKUP_OPTION = 'bodyenergy_theme_migration_backup';

/**
 * Registra la pagina amministrativa sotto il menu Body Energy.
 */
function bodyenergy_register_theme_migration_page()
{
    add_submenu_page(
        'bodyenergy-bodygate',
        'Allineamento child theme',
        'Allineamento child theme',
        'manage_options',
        'bodyenergy-theme-migration',
        'bodyenergy_render_theme_migration_page'
    );
}
add_action('admin_menu', 'bodyenergy_register_theme_migration_page', 40);

/**
 * Restituisce le theme mods associate a uno stylesheet.
 *
 * @param string $stylesheet Nome tecnico della cartella tema.
 * @return array<string, mixed>
 */
function bodyenergy_get_theme_mods_for_stylesheet($stylesheet)
{
    $mods = get_option('theme_mods_' . $stylesheet, array());

    return is_array($mods) ? $mods : array();
}

/**
 * Calcola un hash breve per confronti visivi senza esporre il contenuto.
 *
 * @param mixed $value Valore da sintetizzare.
 * @return string
 */
function bodyenergy_theme_migration_hash($value)
{
    return substr(hash('sha256', wp_json_encode($value)), 0, 12);
}

/**
 * Visualizza lo stato e il comando di allineamento.
 */
function bodyenergy_render_theme_migration_page()
{
    if (!current_user_can('manage_options')) {
        wp_die(esc_html__('Non hai i permessi per visualizzare questa pagina.', 'bodyenergy-wordpress'));
    }

    $parent_theme = wp_get_theme(BODYENERGY_PARENT_THEME_STYLESHEET);
    $child_theme = wp_get_theme(BODYENERGY_CHILD_THEME_STYLESHEET);
    $parent_mods = bodyenergy_get_theme_mods_for_stylesheet(BODYENERGY_PARENT_THEME_STYLESHEET);
    $child_mods = bodyenergy_get_theme_mods_for_stylesheet(BODYENERGY_CHILD_THEME_STYLESHEET);
    $parent_css = function_exists('wp_get_custom_css') ? (string) wp_get_custom_css(BODYENERGY_PARENT_THEME_STYLESHEET) : '';
    $child_css = function_exists('wp_get_custom_css') ? (string) wp_get_custom_css(BODYENERGY_CHILD_THEME_STYLESHEET) : '';
    $migration_done = isset($_GET['bodyenergy_migration']) && 'done' === sanitize_key(wp_unslash($_GET['bodyenergy_migration']));
    ?>
    <div class="wrap bodyenergy-theme-migration">
        <h1>Allineamento child theme</h1>
        <p class="description">Copia esclusivamente le impostazioni grafiche del tema Fitness Elementor nel child theme Body Energy Platinum Child. Non modifica pagine, utenti, ordini, pagamenti o dati BodyGate.</p>

        <?php if ($migration_done) : ?>
            <div class="notice notice-success is-dismissible"><p><strong>Allineamento completato.</strong> Ora puoi riaprire l’anteprima del child theme senza attivarlo.</p></div>
        <?php endif; ?>

        <table class="widefat striped" style="max-width: 960px; margin-top: 24px;">
            <tbody>
                <tr><th style="width: 280px;">Tema genitore</th><td><?php echo esc_html($parent_theme->exists() ? $parent_theme->get('Name') . ' ' . $parent_theme->get('Version') : 'Non trovato'); ?></td></tr>
                <tr><th>Child theme</th><td><?php echo esc_html($child_theme->exists() ? $child_theme->get('Name') . ' ' . $child_theme->get('Version') : 'Non trovato'); ?></td></tr>
                <tr><th>Impostazioni genitore</th><td><?php echo esc_html((string) count($parent_mods)); ?> voci · hash <?php echo esc_html(bodyenergy_theme_migration_hash($parent_mods)); ?></td></tr>
                <tr><th>Impostazioni child</th><td><?php echo esc_html((string) count($child_mods)); ?> voci · hash <?php echo esc_html(bodyenergy_theme_migration_hash($child_mods)); ?></td></tr>
                <tr><th>CSS aggiuntivo genitore</th><td><?php echo esc_html((string) strlen($parent_css)); ?> caratteri</td></tr>
                <tr><th>CSS aggiuntivo child</th><td><?php echo esc_html((string) strlen($child_css)); ?> caratteri</td></tr>
            </tbody>
        </table>

        <div style="max-width: 960px; margin-top: 24px; padding: 20px; border-left: 4px solid #d63638; background: #fff;">
            <h2 style="margin-top: 0;">Operazione controllata</h2>
            <p>Prima della copia viene salvato un backup delle attuali impostazioni del child theme. Fitness Elementor resta attivo e il sito pubblico non viene modificato.</p>

            <?php if (!$parent_theme->exists() || !$child_theme->exists()) : ?>
                <p><strong>Operazione bloccata:</strong> entrambi i temi devono essere installati.</p>
            <?php else : ?>
                <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post">
                    <input type="hidden" name="action" value="bodyenergy_migrate_theme_settings">
                    <?php wp_nonce_field('bodyenergy_migrate_theme_settings'); ?>
                    <?php submit_button('Copia configurazione nel child theme', 'primary', 'submit', false); ?>
                </form>
            <?php endif; ?>
        </div>
    </div>
    <?php
}

/**
 * Esegue la migrazione con backup preventivo.
 */
function bodyenergy_handle_theme_settings_migration()
{
    if (!current_user_can('manage_options')) {
        wp_die(esc_html__('Operazione non autorizzata.', 'bodyenergy-wordpress'));
    }

    check_admin_referer('bodyenergy_migrate_theme_settings');

    $parent_theme = wp_get_theme(BODYENERGY_PARENT_THEME_STYLESHEET);
    $child_theme = wp_get_theme(BODYENERGY_CHILD_THEME_STYLESHEET);

    if (!$parent_theme->exists() || !$child_theme->exists()) {
        wp_die(esc_html__('Tema genitore o child theme non disponibile.', 'bodyenergy-wordpress'));
    }

    $parent_mods = bodyenergy_get_theme_mods_for_stylesheet(BODYENERGY_PARENT_THEME_STYLESHEET);
    $child_mods = bodyenergy_get_theme_mods_for_stylesheet(BODYENERGY_CHILD_THEME_STYLESHEET);
    $parent_css = function_exists('wp_get_custom_css') ? (string) wp_get_custom_css(BODYENERGY_PARENT_THEME_STYLESHEET) : '';
    $child_css = function_exists('wp_get_custom_css') ? (string) wp_get_custom_css(BODYENERGY_CHILD_THEME_STYLESHEET) : '';

    update_option(
        BODYENERGY_THEME_MIGRATION_BACKUP_OPTION,
        array(
            'created_at' => current_time('mysql'),
            'target_stylesheet' => BODYENERGY_CHILD_THEME_STYLESHEET,
            'theme_mods' => $child_mods,
            'custom_css' => $child_css,
        ),
        false
    );

    update_option('theme_mods_' . BODYENERGY_CHILD_THEME_STYLESHEET, $parent_mods, false);

    if (function_exists('wp_update_custom_css_post')) {
        wp_update_custom_css_post(
            $parent_css,
            array('stylesheet' => BODYENERGY_CHILD_THEME_STYLESHEET)
        );
    }

    wp_safe_redirect(
        add_query_arg(
            'bodyenergy_migration',
            'done',
            admin_url('admin.php?page=bodyenergy-theme-migration')
        )
    );
    exit;
}
add_action('admin_post_bodyenergy_migrate_theme_settings', 'bodyenergy_handle_theme_settings_migration');
