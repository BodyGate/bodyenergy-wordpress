<?php
/**
 * Selettore amministrativo del video Hero Platinum.
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Registra la pagina sotto il Centro di controllo Body Energy.
 */
function bodyenergy_register_home_video_admin_page()
{
    add_submenu_page(
        'bodyenergy-bodygate',
        'Video Hero Home Platinum',
        'Video Hero Home',
        'manage_options',
        'bodyenergy-home-video',
        'bodyenergy_render_home_video_admin_page'
    );
}
add_action('admin_menu', 'bodyenergy_register_home_video_admin_page', 20);

/**
 * Carica la Libreria Media esclusivamente nella pagina del selettore.
 *
 * @param string $hook_suffix Hook pagina amministrativa.
 */
function bodyenergy_home_video_admin_assets($hook_suffix)
{
    if ('body-energy_page_bodyenergy-home-video' !== $hook_suffix) {
        return;
    }

    wp_enqueue_media();
}
add_action('admin_enqueue_scripts', 'bodyenergy_home_video_admin_assets');

/**
 * Salva la scelta verificando permessi, nonce e allegato.
 */
function bodyenergy_handle_home_video_admin_save()
{
    if (!is_admin() || !current_user_can('manage_options')) {
        return;
    }

    if (empty($_POST['bodyenergy_home_video_action'])) {
        return;
    }

    check_admin_referer('bodyenergy_save_home_video', 'bodyenergy_home_video_nonce');

    $action = sanitize_key(wp_unslash($_POST['bodyenergy_home_video_action']));

    if ('remove' === $action) {
        delete_option('bodyenergy_home_platinum_video_id');
        wp_safe_redirect(add_query_arg(array(
            'page' => 'bodyenergy-home-video',
            'updated' => 'removed',
        ), admin_url('admin.php')));
        exit;
    }

    $attachment_id = isset($_POST['bodyenergy_home_platinum_video_id'])
        ? absint(wp_unslash($_POST['bodyenergy_home_platinum_video_id']))
        : 0;
    $video = bodyenergy_home_platinum_attachment_video_data($attachment_id);

    if (!is_array($video)) {
        wp_safe_redirect(add_query_arg(array(
            'page' => 'bodyenergy-home-video',
            'updated' => 'invalid',
        ), admin_url('admin.php')));
        exit;
    }

    update_option('bodyenergy_home_platinum_video_id', $attachment_id, false);

    wp_safe_redirect(add_query_arg(array(
        'page' => 'bodyenergy-home-video',
        'updated' => 'saved',
    ), admin_url('admin.php')));
    exit;
}
add_action('admin_init', 'bodyenergy_handle_home_video_admin_save');

/**
 * Renderizza il selettore video Platinum.
 */
function bodyenergy_render_home_video_admin_page()
{
    if (!current_user_can('manage_options')) {
        wp_die(esc_html__('Non hai i permessi per visualizzare questa pagina.', 'bodyenergy-wordpress'));
    }

    $attachment_id = absint(get_option('bodyenergy_home_platinum_video_id', 0));
    $selected_video = bodyenergy_home_platinum_attachment_video_data($attachment_id);
    $selected_title = $attachment_id ? get_the_title($attachment_id) : '';
    $status = isset($_GET['updated']) ? sanitize_key(wp_unslash($_GET['updated'])) : '';
    $preview_url = is_array($selected_video) ? $selected_video['url'] : '';
    ?>
    <div class="wrap bodyenergy-video-admin">
        <div class="bodyenergy-video-hero">
            <div>
                <p class="bodyenergy-video-eyebrow">HOME BODY ENERGY PLATINUM</p>
                <h1>Video Hero della Home</h1>
                <p>Seleziona direttamente dalla Libreria Media il video già caricato su WordPress. La scelta viene applicata alla card destra dell’Hero della pagina in staging.</p>
            </div>
            <span class="bodyenergy-video-version">Plugin <?php echo esc_html(BODYENERGY_WORDPRESS_VERSION); ?></span>
        </div>

        <?php if ('saved' === $status) : ?>
            <div class="notice notice-success is-dismissible"><p><strong>Video salvato.</strong> Aggiorna l’anteprima della Home per verificarlo.</p></div>
        <?php elseif ('removed' === $status) : ?>
            <div class="notice notice-warning is-dismissible"><p>Selezione rimossa. La Home userà nuovamente il rilevamento automatico.</p></div>
        <?php elseif ('invalid' === $status) : ?>
            <div class="notice notice-error is-dismissible"><p>Il file scelto non è stato riconosciuto come video valido.</p></div>
        <?php endif; ?>

        <div class="bodyenergy-video-panel">
            <div class="bodyenergy-video-panel__copy">
                <span class="bodyenergy-video-label">VIDEO SELEZIONATO</span>
                <h2 id="bodyenergy-video-title"><?php echo $selected_title ? esc_html($selected_title) : 'Nessun video selezionato'; ?></h2>
                <p id="bodyenergy-video-url"><?php echo $preview_url ? esc_html($preview_url) : 'Clicca “Seleziona dalla Libreria Media” e scegli il filmato della palestra già presente su WordPress.'; ?></p>

                <form method="post" action="">
                    <?php wp_nonce_field('bodyenergy_save_home_video', 'bodyenergy_home_video_nonce'); ?>
                    <input type="hidden" id="bodyenergy-home-video-id" name="bodyenergy_home_platinum_video_id" value="<?php echo esc_attr($attachment_id); ?>">
                    <input type="hidden" id="bodyenergy-home-video-action" name="bodyenergy_home_video_action" value="save">

                    <div class="bodyenergy-video-actions">
                        <button type="button" class="button button-primary button-hero" id="bodyenergy-select-video">Seleziona dalla Libreria Media</button>
                        <button type="submit" class="button button-secondary button-hero" id="bodyenergy-save-video" <?php disabled(!$attachment_id); ?>>Salva e usa nella Home</button>
                    </div>
                </form>

                <?php if ($attachment_id) : ?>
                    <form method="post" action="" class="bodyenergy-video-remove-form">
                        <?php wp_nonce_field('bodyenergy_save_home_video', 'bodyenergy_home_video_nonce'); ?>
                        <input type="hidden" name="bodyenergy_home_video_action" value="remove">
                        <button type="submit" class="button-link-delete">Rimuovi selezione</button>
                    </form>
                <?php endif; ?>
            </div>

            <div class="bodyenergy-video-preview" id="bodyenergy-video-preview">
                <?php if ($preview_url) : ?>
                    <video controls muted playsinline preload="metadata" src="<?php echo esc_url($preview_url); ?>"></video>
                <?php else : ?>
                    <div class="bodyenergy-video-placeholder">
                        <span class="dashicons dashicons-format-video"></span>
                        <strong>Anteprima video</strong>
                        <small>Il file scelto comparirà qui prima del salvataggio.</small>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="bodyenergy-video-note">
            <span class="dashicons dashicons-shield-alt"></span>
            <div><strong>Intervento controllato</strong><p>La selezione modifica soltanto il video della nuova Home. Non tocca Elementor, WooCommerce, utenti, pagamenti o BodyGate.</p></div>
        </div>
    </div>

    <style>
        .bodyenergy-video-admin{max-width:1180px;margin-top:24px;color:#f5f5f7}.bodyenergy-video-admin *{box-sizing:border-box}.bodyenergy-video-admin h1,.bodyenergy-video-admin h2,.bodyenergy-video-admin p{margin-top:0}.bodyenergy-video-hero,.bodyenergy-video-panel,.bodyenergy-video-note{border:1px solid #2d2d33;background:#111114;box-shadow:0 18px 50px rgba(0,0,0,.18)}.bodyenergy-video-hero{display:flex;align-items:flex-start;justify-content:space-between;gap:32px;padding:34px;border-top:3px solid #e3262e;border-radius:18px}.bodyenergy-video-hero h1{margin-bottom:10px;color:#fff;font-size:30px;line-height:1.15}.bodyenergy-video-hero p{max-width:760px;margin-bottom:0;color:#a1a1aa;font-size:15px}.bodyenergy-video-eyebrow,.bodyenergy-video-label{margin-bottom:9px;color:#ff3b43;font-size:11px;font-weight:800;letter-spacing:.16em}.bodyenergy-video-version{display:inline-flex;padding:8px 12px;border:1px solid rgba(34,197,94,.32);border-radius:999px;background:rgba(34,197,94,.12);color:#86efac;font-size:11px;font-weight:700;white-space:nowrap}.bodyenergy-video-panel{display:grid;grid-template-columns:minmax(0,1fr) minmax(360px,.8fr);gap:30px;margin-top:16px;padding:30px;border-radius:18px}.bodyenergy-video-panel__copy{padding:8px}.bodyenergy-video-panel h2{margin:9px 0 10px;color:#fff;font-size:24px}.bodyenergy-video-panel p{color:#9f9faa;line-height:1.65;word-break:break-word}.bodyenergy-video-actions{display:flex;flex-wrap:wrap;gap:12px;margin-top:24px}.bodyenergy-video-actions .button-hero{min-height:46px;padding:7px 18px;border-radius:9px;font-weight:700}.bodyenergy-video-actions .button-primary{border-color:#e3262e;background:#e3262e}.bodyenergy-video-actions .button-primary:hover{border-color:#ff3b43;background:#ff3b43}.bodyenergy-video-remove-form{margin-top:18px}.bodyenergy-video-preview{display:flex;align-items:center;justify-content:center;min-height:330px;overflow:hidden;border:1px solid #303038;border-radius:16px;background:#070709}.bodyenergy-video-preview video{display:block;width:100%;height:100%;max-height:430px;object-fit:cover}.bodyenergy-video-placeholder{display:flex;flex-direction:column;align-items:center;gap:10px;color:#8f8f99;text-align:center}.bodyenergy-video-placeholder .dashicons{width:48px;height:48px;color:#ff3b43;font-size:48px}.bodyenergy-video-placeholder strong{color:#fff;font-size:18px}.bodyenergy-video-note{display:flex;align-items:flex-start;gap:16px;margin-top:16px;padding:22px 25px;border-radius:15px}.bodyenergy-video-note .dashicons{width:28px;height:28px;color:#ef4444;font-size:28px}.bodyenergy-video-note strong{color:#fff}.bodyenergy-video-note p{margin:5px 0 0;color:#8f8f99}@media(max-width:800px){.bodyenergy-video-hero{flex-direction:column}.bodyenergy-video-panel{grid-template-columns:1fr}.bodyenergy-video-preview{min-height:240px}}
    </style>

    <script>
        (function($){
            'use strict';
            var frame;
            var selectButton=$('#bodyenergy-select-video');
            var saveButton=$('#bodyenergy-save-video');
            var idField=$('#bodyenergy-home-video-id');
            var title=$('#bodyenergy-video-title');
            var url=$('#bodyenergy-video-url');
            var preview=$('#bodyenergy-video-preview');

            selectButton.on('click',function(event){
                event.preventDefault();

                if(frame){
                    frame.open();
                    return;
                }

                frame=wp.media({
                    title:'Seleziona il video della palestra',
                    button:{text:'Usa questo video'},
                    library:{type:'video'},
                    multiple:false
                });

                frame.on('select',function(){
                    var attachment=frame.state().get('selection').first().toJSON();
                    var attachmentUrl=attachment.url||'';
                    idField.val(attachment.id||'');
                    title.text(attachment.title||attachment.filename||'Video selezionato');
                    url.text(attachmentUrl);
                    preview.html($('<video>',{
                        controls:true,
                        muted:true,
                        playsinline:true,
                        preload:'metadata',
                        src:attachmentUrl
                    }));
                    saveButton.prop('disabled',!attachment.id);
                });

                frame.open();
            });
        }(jQuery));
    </script>
    <?php
}
