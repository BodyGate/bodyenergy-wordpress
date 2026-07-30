<?php
/**
 * Pagina Platinum "Contatti".
 *
 * Crea e aggiorna esclusivamente la pagina di staging, mantenendola in bozza
 * finche non viene pubblicata esplicitamente dal proprietario.
 *
 * @package BodyEnergyWordPress
 */

if (!defined('ABSPATH')) {
    exit;
}

function bodyenergy_contact_page_version()
{
    return '1.0.0';
}

function bodyenergy_bootstrap_contact_page()
{
    if (!is_admin() || !current_user_can('manage_options') || !bodyenergy_is_staging_site()) {
        return;
    }

    if (bodyenergy_contact_page_version() === get_option('bodyenergy_contact_page_version')) {
        return;
    }

    $page = bodyenergy_find_blueprint_page(array('contatti'));
    $status = $page instanceof WP_Post && 'publish' === get_post_status($page) ? 'publish' : 'draft';

    $post = array(
        'post_type' => 'page',
        'post_status' => $status,
        'post_title' => 'Contatti',
        'post_name' => 'contatti',
        'post_content' => '[bodyenergy_contact_landing]',
        'post_excerpt' => 'Contatti, orari e indicazioni per raggiungere Body Energy ASD a Palermo.',
        'comment_status' => 'closed',
        'ping_status' => 'closed',
    );

    if ($page instanceof WP_Post) {
        $post['ID'] = (int) $page->ID;
        $page_id = wp_update_post($post, true);
    } else {
        $page_id = wp_insert_post($post, true);
    }

    if (!is_wp_error($page_id)) {
        update_option('bodyenergy_contact_page_id', (int) $page_id, false);
        update_option('bodyenergy_contact_page_version', bodyenergy_contact_page_version(), false);
    }
}
add_action('admin_init', 'bodyenergy_bootstrap_contact_page', 60);

function bodyenergy_is_contact_page()
{
    if (!is_singular('page')) {
        return false;
    }

    $post = get_post(get_queried_object_id());

    return $post instanceof WP_Post
        && (
            'contatti' === $post->post_name
            || has_shortcode((string) $post->post_content, 'bodyenergy_contact_landing')
        );
}

function bodyenergy_contact_body_class($classes)
{
    if (bodyenergy_is_contact_page()) {
        $classes[] = 'bodyenergy-contact-page';
    }

    return $classes;
}
add_filter('body_class', 'bodyenergy_contact_body_class');

function bodyenergy_render_contact_landing()
{
    $whatsapp_url = 'https://wa.me/393533406254';
    $phone_url = 'tel:+390917785001';
    $email_url = 'mailto:bodyenergy.asd@gmail.com';
    $maps_url = 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode("Viale Amedeo D'Aosta 3, Palermo");

    ob_start();
    ?>
    <main class="be-contact">
        <section class="be-contact__hero">
            <div class="be-contact__grid" aria-hidden="true"></div>
            <div class="be-contact__shell be-contact__hero-layout">
                <div class="be-contact__hero-copy">
                    <p class="be-contact__eyebrow">BODY ENERGY ASD · PALERMO</p>
                    <h1>Parliamone.<br><span>Siamo qui.</span></h1>
                    <p class="be-contact__lead">Per informazioni sulla palestra, sugli abbonamenti o sul Pilates Reformer, scegli il canale più comodo. La reception è a disposizione per aiutarti in modo semplice e diretto.</p>
                    <div class="be-contact__actions">
                        <a class="be-contact__button" href="<?php echo esc_url($whatsapp_url); ?>" target="_blank" rel="noopener noreferrer">Scrivici su WhatsApp <b>→</b></a>
                        <a class="be-contact__button be-contact__button--ghost" href="<?php echo esc_url($phone_url); ?>">Chiamaci</a>
                    </div>
                    <div class="be-contact__hero-note">
                        <span>Viale Amedeo D’Aosta 3</span>
                        <span>Palermo</span>
                    </div>
                </div>

                <aside class="be-contact__direct" aria-label="Contatti diretti Body Energy">
                    <div class="be-contact__direct-head">
                        <span>CONTATTI DIRETTI</span>
                        <b>BE</b>
                    </div>
                    <a class="be-contact__direct-row" href="<?php echo esc_url($whatsapp_url); ?>" target="_blank" rel="noopener noreferrer">
                        <span class="be-contact__direct-icon" aria-hidden="true">
                            <svg viewBox="0 0 32 32" fill="none"><path d="M16 27a11 11 0 1 0-9.2-5L5 28l6.2-1.7A11 11 0 0 0 16 27Z" stroke="currentColor" stroke-width="1.7"/><path d="M12.1 10.8c.5-.5 1.2-.3 1.5.2l1.1 2c.2.4.2.8-.1 1.2l-.8 1c1 2 2.6 3.6 4.7 4.6l1-.8c.4-.3.8-.3 1.2-.1l2 1.1c.5.3.7 1 .2 1.5-.8.9-2 1.4-3.2 1.2-5.7-.9-10.2-5.4-11.1-11.1-.2-1.2.3-2.4 1.2-3.2Z" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </span>
                        <span><small>WhatsApp</small><strong>+39 353 340 6254</strong></span>
                        <b>↗</b>
                    </a>
                    <a class="be-contact__direct-row" href="<?php echo esc_url($phone_url); ?>">
                        <span class="be-contact__direct-icon" aria-hidden="true">
                            <svg viewBox="0 0 32 32" fill="none"><path d="M10.3 6.5 7.7 8.2c-1.2.8-1.6 2.3-1 3.6 3.2 6.9 6.6 10.3 13.5 13.5 1.3.6 2.8.2 3.6-1l1.7-2.6c.5-.8.3-1.9-.5-2.5l-3.6-2.5c-.7-.5-1.7-.4-2.3.2l-1.5 1.5a17.8 17.8 0 0 1-4-4l1.5-1.5c.6-.6.7-1.6.2-2.3L12.8 7c-.6-.8-1.7-1-2.5-.5Z" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </span>
                        <span><small>Telefono</small><strong>091 778 5001</strong></span>
                        <b>→</b>
                    </a>
                    <a class="be-contact__direct-row" href="<?php echo esc_url($email_url); ?>">
                        <span class="be-contact__direct-icon" aria-hidden="true">
                            <svg viewBox="0 0 32 32" fill="none"><rect x="5" y="8" width="22" height="16" rx="2.5" stroke="currentColor" stroke-width="1.7"/><path d="m7 10 9 7 9-7" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </span>
                        <span><small>Email</small><strong>bodyenergy.asd@gmail.com</strong></span>
                        <b>→</b>
                    </a>
                </aside>
            </div>
        </section>

        <section class="be-contact__location">
            <div class="be-contact__shell be-contact__location-layout">
                <div class="be-contact__location-copy">
                    <p class="be-contact__eyebrow">DOVE TROVARCI</p>
                    <h2>Nel cuore di<br><span>Palermo.</span></h2>
                    <p>Body Energy si trova in Viale Amedeo D’Aosta 3, in una posizione facilmente raggiungibile. Apri le indicazioni e raggiungici direttamente.</p>
                    <a class="be-contact__text-link" href="<?php echo esc_url($maps_url); ?>" target="_blank" rel="noopener noreferrer">Apri in Google Maps <b>↗</b></a>
                </div>

                <div class="be-contact__map" aria-label="Indicazione grafica della sede Body Energy">
                    <div class="be-contact__map-grid" aria-hidden="true"></div>
                    <span class="be-contact__map-road be-contact__map-road--one" aria-hidden="true"></span>
                    <span class="be-contact__map-road be-contact__map-road--two" aria-hidden="true"></span>
                    <span class="be-contact__map-road be-contact__map-road--three" aria-hidden="true"></span>
                    <span class="be-contact__map-pin" aria-hidden="true"><i></i></span>
                    <div class="be-contact__map-card">
                        <small>BODY ENERGY ASD</small>
                        <strong>Viale Amedeo D’Aosta 3</strong>
                        <span>Palermo</span>
                    </div>
                </div>
            </div>
        </section>

        <section class="be-contact__hours">
            <div class="be-contact__shell be-contact__hours-layout">
                <div>
                    <p class="be-contact__eyebrow">ORARI</p>
                    <h2>Trova il momento<br><span>giusto per te.</span></h2>
                    <p class="be-contact__hours-copy">La struttura è interamente climatizzata, compresi gli spogliatoi, per offrire comfort durante tutto l’anno.</p>
                </div>

                <div class="be-contact__schedule" aria-label="Orari di apertura Body Energy">
                    <div><span>Lunedì – Venerdì</span><strong>07:00 – 22:30</strong></div>
                    <div><span>Sabato</span><strong>09:00 – 13:00</strong></div>
                    <div><span>Domenica</span><strong>Chiuso</strong></div>
                    <small>Gli orari possono variare in occasione delle festività.</small>
                </div>
            </div>
        </section>

        <section class="be-contact__closing">
            <div class="be-contact__shell be-contact__closing-layout">
                <div>
                    <p class="be-contact__eyebrow">PARLIAMONE DI PERSONA</p>
                    <h2>Ti aspettiamo<br>in reception.</h2>
                </div>
                <div class="be-contact__closing-actions">
                    <p>Passa a trovarci oppure scrivici su WhatsApp. Ti aiuteremo a trovare le informazioni giuste senza pressioni e senza percorsi standardizzati.</p>
                    <a class="be-contact__button" href="<?php echo esc_url($whatsapp_url); ?>" target="_blank" rel="noopener noreferrer">Contatta la reception <b>→</b></a>
                </div>
            </div>
        </section>

        <div class="be-contact__footer">
            <div class="be-contact__shell">
                <strong>BODY ENERGY ASD · PALERMO</strong>
                <span>Viale Amedeo D’Aosta 3 · Palermo</span>
                <span>© <?php echo esc_html(wp_date('Y')); ?> Body Energy ASD</span>
            </div>
        </div>
    </main>
    <style>
    .be-contact{--bg:#070709;--panel:#111114;--line:rgba(255,255,255,.11);--text:#f7f7f8;--muted:#aaaab4;--red:#e3262e;--bright:#ff3b43;--ivory:#f1eee7;width:100%;overflow:hidden;background:var(--bg);color:var(--text);font-family:Inter,ui-sans-serif,system-ui,-apple-system,BlinkMacSystemFont,"Segoe UI",sans-serif}.be-contact *,.be-contact *:before,.be-contact *:after{box-sizing:border-box}.be-contact h1,.be-contact h2,.be-contact p{margin-top:0}.be-contact a{text-decoration:none}.be-contact__shell{width:min(1180px,calc(100% - 40px));margin:auto}.be-contact__eyebrow{margin-bottom:18px;color:var(--bright)!important;font-size:11px;font-weight:850;line-height:1.2;letter-spacing:.19em;text-transform:uppercase}.be-contact__hero{position:relative;padding:92px 0 98px;background:radial-gradient(circle at 86% 11%,rgba(227,38,46,.18),transparent 28%),linear-gradient(135deg,rgba(227,38,46,.07),transparent 44%),#08080a}.be-contact__grid{position:absolute;inset:0;opacity:.16;background-image:linear-gradient(rgba(255,255,255,.045) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.045) 1px,transparent 1px);background-size:58px 58px;mask-image:linear-gradient(to bottom,#000,transparent 96%)}.be-contact__hero-layout{position:relative;display:grid;grid-template-columns:1.08fr .92fr;gap:74px;align-items:center}.be-contact__hero h1,.be-contact__location h2,.be-contact__hours h2,.be-contact__closing h2{margin-bottom:26px;color:#fff;font-size:clamp(58px,6vw,86px);font-weight:850;line-height:.94;letter-spacing:-.062em}.be-contact__hero h1 span,.be-contact__location h2 span,.be-contact__hours h2 span{color:#a8a8b2}.be-contact__lead{max-width:680px;margin-bottom:32px;color:var(--muted)!important;font-size:17px;line-height:1.75}.be-contact__actions{display:flex;flex-wrap:wrap;gap:14px}.be-contact__button{display:inline-flex;min-height:56px;align-items:center;justify-content:center;gap:32px;padding:0 22px;border:1px solid var(--red);border-radius:10px;background:var(--red);box-shadow:0 18px 42px rgba(227,38,46,.23);color:#fff!important;font-size:13px;font-weight:850;transition:transform .25s ease,box-shadow .25s ease,background .25s ease}.be-contact__button:hover{transform:translateY(-3px);background:#f02f38;box-shadow:0 24px 52px rgba(227,38,46,.3)}.be-contact__button--ghost{border-color:rgba(255,255,255,.18);background:transparent;box-shadow:none}.be-contact__button--ghost:hover{background:rgba(255,255,255,.06);box-shadow:none}.be-contact__hero-note{display:flex;gap:22px;margin-top:42px;padding-top:21px;border-top:1px solid var(--line);color:#777781;font-size:11px;font-weight:750;letter-spacing:.08em;text-transform:uppercase}.be-contact__direct{overflow:hidden;border:1px solid rgba(255,255,255,.12);border-radius:24px;background:linear-gradient(145deg,rgba(25,25,30,.97),rgba(9,9,12,.98));box-shadow:0 36px 90px rgba(0,0,0,.42)}.be-contact__direct-head{display:flex;align-items:center;justify-content:space-between;padding:25px 27px;border-bottom:1px solid var(--line);color:#8d8d98;font-size:10px;font-weight:850;letter-spacing:.17em}.be-contact__direct-head b{color:rgba(255,255,255,.08);font-size:54px;line-height:.8;letter-spacing:-.08em}.be-contact__direct-row{display:grid;grid-template-columns:52px minmax(0,1fr) auto;align-items:center;gap:17px;padding:23px 27px;border-bottom:1px solid var(--line);color:#fff!important;transition:background .24s ease}.be-contact__direct-row:last-child{border-bottom:0}.be-contact__direct-row:hover{background:rgba(255,255,255,.035)}.be-contact__direct-icon{display:grid;width:48px;height:48px;place-items:center;border:1px solid rgba(255,255,255,.11);border-radius:14px;background:rgba(255,255,255,.035);color:var(--bright)}.be-contact__direct-icon svg{width:27px;height:27px}.be-contact__direct-row small,.be-contact__direct-row strong{display:block}.be-contact__direct-row small{margin-bottom:5px;color:#7f7f89;font-size:10px;font-weight:800;letter-spacing:.14em;text-transform:uppercase}.be-contact__direct-row strong{overflow-wrap:anywhere;color:#f4f4f6;font-size:16px;line-height:1.25}.be-contact__direct-row>b{color:var(--bright);font-size:17px}.be-contact__location{padding:112px 0;background:#0c0c0f}.be-contact__location-layout{display:grid;grid-template-columns:.84fr 1.16fr;gap:88px;align-items:center}.be-contact__location h2,.be-contact__hours h2,.be-contact__closing h2{font-size:clamp(48px,5vw,72px)}.be-contact__location-copy>p:not(.be-contact__eyebrow){max-width:510px;color:var(--muted);font-size:16px;line-height:1.75}.be-contact__text-link{display:inline-flex;align-items:center;gap:30px;margin-top:20px;padding-bottom:5px;border-bottom:1px solid rgba(255,255,255,.25);color:#fff!important;font-size:13px;font-weight:850}.be-contact__text-link b{color:var(--bright)}.be-contact__map{position:relative;min-height:500px;overflow:hidden;border:1px solid rgba(255,255,255,.12);border-radius:26px;background:#111116;box-shadow:0 34px 80px rgba(0,0,0,.34)}.be-contact__map-grid{position:absolute;inset:0;background:radial-gradient(circle at 75% 18%,rgba(227,38,46,.18),transparent 25%),linear-gradient(rgba(255,255,255,.045) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.045) 1px,transparent 1px);background-size:auto,52px 52px,52px 52px}.be-contact__map-road{position:absolute;height:13px;border:1px solid rgba(255,255,255,.075);border-left:0;border-right:0;background:rgba(255,255,255,.028);transform-origin:center}.be-contact__map-road--one{left:-12%;top:34%;width:124%;transform:rotate(-12deg)}.be-contact__map-road--two{left:-10%;top:65%;width:126%;transform:rotate(17deg)}.be-contact__map-road--three{left:39%;top:-5%;width:112%;transform:rotate(78deg)}.be-contact__map-pin{position:absolute;left:63%;top:38%;display:grid;width:54px;height:54px;place-items:center;border:1px solid rgba(255,59,67,.42);border-radius:50% 50% 50% 8px;background:var(--red);box-shadow:0 0 0 14px rgba(227,38,46,.12),0 20px 50px rgba(227,38,46,.36);transform:rotate(-45deg)}.be-contact__map-pin i{width:15px;height:15px;border-radius:50%;background:#fff}.be-contact__map-card{position:absolute;left:28px;bottom:28px;width:min(390px,calc(100% - 56px));padding:24px;border:1px solid rgba(255,255,255,.12);border-radius:18px;background:rgba(8,8,10,.9);backdrop-filter:blur(14px)}.be-contact__map-card small{color:var(--bright);font-size:10px;font-weight:850;letter-spacing:.16em}.be-contact__map-card strong{display:block;margin:13px 0 5px;color:#fff;font-size:25px;line-height:1.05}.be-contact__map-card span{color:#9999a4;font-size:13px}.be-contact__hours{padding:108px 0;background:var(--ivory);color:#111116}.be-contact__hours-layout{display:grid;grid-template-columns:1fr .92fr;gap:100px;align-items:start}.be-contact__hours .be-contact__eyebrow{color:var(--red)!important}.be-contact__hours h2{color:#111116}.be-contact__hours h2 span{color:#77777f}.be-contact__hours-copy{max-width:560px;color:#55555d;font-size:16px;line-height:1.75}.be-contact__schedule{border-top:1px solid rgba(17,17,22,.18)}.be-contact__schedule>div{display:flex;align-items:center;justify-content:space-between;gap:30px;padding:24px 0;border-bottom:1px solid rgba(17,17,22,.18)}.be-contact__schedule span{color:#55555d;font-size:14px;font-weight:700}.be-contact__schedule strong{color:#111116;font-size:18px}.be-contact__schedule small{display:block;margin-top:17px;color:#77777f;font-size:11px}.be-contact__closing{padding:104px 0;background:radial-gradient(circle at 92% 10%,rgba(227,38,46,.18),transparent 32%),#09090c}.be-contact__closing-layout{display:grid;grid-template-columns:1.02fr .98fr;gap:90px;align-items:end}.be-contact__closing h2{margin-bottom:0}.be-contact__closing-actions p{max-width:580px;margin-bottom:28px;color:var(--muted);font-size:16px;line-height:1.75}.be-contact__footer{padding:25px 0;border-top:1px solid var(--line);background:#070709}.be-contact__footer .be-contact__shell{display:flex;align-items:center;justify-content:space-between;gap:24px;color:#70707a;font-size:10px;font-weight:700;letter-spacing:.08em;text-transform:uppercase}.be-contact__footer strong{color:#f3f3f5}.be-contact__footer span:last-child{text-align:right}@media(max-width:980px){.be-contact__hero-layout,.be-contact__location-layout,.be-contact__hours-layout,.be-contact__closing-layout{grid-template-columns:1fr;gap:52px}.be-contact__direct{max-width:720px}.be-contact__map{min-height:440px}.be-contact__footer .be-contact__shell{align-items:flex-start;flex-direction:column}.be-contact__footer span:last-child{text-align:left}}@media(max-width:620px){.be-contact__shell{width:min(100% - 28px,1180px)}.be-contact__hero,.be-contact__location,.be-contact__hours,.be-contact__closing{padding:72px 0}.be-contact__hero h1{font-size:clamp(50px,15vw,66px)}.be-contact__location h2,.be-contact__hours h2,.be-contact__closing h2{font-size:clamp(43px,13vw,56px)}.be-contact__actions{align-items:stretch;flex-direction:column}.be-contact__button{width:100%}.be-contact__hero-note{align-items:flex-start;flex-direction:column;gap:8px}.be-contact__direct-head,.be-contact__direct-row{padding-left:19px;padding-right:19px}.be-contact__direct-row{grid-template-columns:46px minmax(0,1fr) auto;gap:12px}.be-contact__direct-icon{width:43px;height:43px}.be-contact__direct-row strong{font-size:14px}.be-contact__map{min-height:410px}.be-contact__map-pin{left:66%;top:31%}.be-contact__schedule>div{align-items:flex-start;flex-direction:column;gap:8px}.be-contact__footer{padding:28px 0}.be-contact__footer .be-contact__shell{gap:12px}}@media(prefers-reduced-motion:reduce){.be-contact__button,.be-contact__direct-row{transition:none}.be-contact__button:hover{transform:none}}
    </style>
    <?php

    return (string) ob_get_clean();
}
add_shortcode('bodyenergy_contact_landing', 'bodyenergy_render_contact_landing');

function bodyenergy_print_contact_layout_css()
{
    if (!bodyenergy_is_contact_page()) {
        return;
    }
    ?>
    <style id="bodyenergy-contact-layout">
    html,body{max-width:none!important;margin:0!important;padding:0!important;overflow-x:clip!important;background:#070709!important}
    body.bodyenergy-contact-page .site-content,body.bodyenergy-contact-page #content,body.bodyenergy-contact-page #primary,body.bodyenergy-contact-page main.site-main,body.bodyenergy-contact-page .content-area,body.bodyenergy-contact-page .entry-content,body.bodyenergy-contact-page .page-content,body.bodyenergy-contact-page article.page,body.bodyenergy-contact-page .inside-article,body.bodyenergy-contact-page .elementor,body.bodyenergy-contact-page .elementor-section-wrap,body.bodyenergy-contact-page .elementor-element.e-con,body.bodyenergy-contact-page .e-con-inner,body.bodyenergy-contact-page .elementor-widget-shortcode,body.bodyenergy-contact-page .elementor-widget-shortcode>.elementor-widget-container{width:100%!important;max-width:none!important;margin:0!important;padding:0!important;overflow:visible!important}
    body.bodyenergy-contact-page .entry-header,body.bodyenergy-contact-page .page-header,body.bodyenergy-contact-page .page-hero,body.bodyenergy-contact-page .featured-image,body.bodyenergy-contact-page .post-thumbnail,body.bodyenergy-contact-page .entry-title,body.bodyenergy-contact-page .entry-meta,body.bodyenergy-contact-page .breadcrumb,body.bodyenergy-contact-page .breadcrumbs,body.bodyenergy-contact-page .breadcrumb-area,body.bodyenergy-contact-page .breadcrumb-section,body.bodyenergy-contact-page .breadcumb-area,body.bodyenergy-contact-page .breadcrumb-bg,body.bodyenergy-contact-page .page-title-area,body.bodyenergy-contact-page .page-title-section,body.bodyenergy-contact-page .inner-banner,body.bodyenergy-contact-page .page-banner,body.bodyenergy-contact-page .banner-area,body.bodyenergy-contact-page .sub-banner,body.bodyenergy-contact-page .elementor-page-title,body.bodyenergy-contact-page footer,body.bodyenergy-contact-page #colophon,body.bodyenergy-contact-page .site-footer,body.bodyenergy-contact-page .footer-widgets,body.bodyenergy-contact-page .edit-link,body.bodyenergy-contact-page .post-edit-link,body.bodyenergy-contact-page a.post-edit-link,body.bodyenergy-contact-page .entry-footer{display:none!important}
    body.bodyenergy-contact-page .be-contact{position:relative!important;left:50%!important;width:100vw!important;max-width:100vw!important;margin-left:-50vw!important;margin-right:-50vw!important}
    </style>
    <?php
}
add_action('wp_head', 'bodyenergy_print_contact_layout_css', 999);
