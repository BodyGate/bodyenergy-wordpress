<?php
/**
 * Pagina Platinum "Servizi".
 *
 * Sostituisce la bozza segnaposto Attività con la pagina dedicata ai servizi
 * realmente confermati di Body Energy ASD, senza pubblicarla automaticamente.
 *
 * @package BodyEnergyWordPress
 */

if (!defined('ABSPATH')) {
    exit;
}

function bodyenergy_services_page_version()
{
    return '1.0.0';
}

function bodyenergy_bootstrap_services_page()
{
    if (!is_admin() || !current_user_can('manage_options') || !bodyenergy_is_staging_site()) {
        return;
    }

    if (bodyenergy_services_page_version() === get_option('bodyenergy_services_page_version')) {
        return;
    }

    $page = bodyenergy_find_blueprint_page(array('servizi', 'attivita'));
    $status = $page instanceof WP_Post && 'publish' === get_post_status($page) ? 'publish' : 'draft';

    $post = array(
        'post_type' => 'page',
        'post_status' => $status,
        'post_title' => 'Servizi',
        'post_name' => 'servizi',
        'post_content' => '[bodyenergy_services_landing]',
        'post_excerpt' => 'Fitness, bodybuilding, powerlifting, corsi fitness di gruppo e Pilates Reformer presso Body Energy ASD Palermo.',
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
        update_option('bodyenergy_services_page_id', (int) $page_id, false);
        update_option('bodyenergy_services_page_version', bodyenergy_services_page_version(), false);
    }
}
add_action('admin_init', 'bodyenergy_bootstrap_services_page', 62);

function bodyenergy_is_services_page()
{
    if (!is_singular('page')) {
        return false;
    }

    $post = get_post(get_queried_object_id());

    return $post instanceof WP_Post
        && (
            in_array($post->post_name, array('servizi', 'attivita'), true)
            || has_shortcode((string) $post->post_content, 'bodyenergy_services_landing')
        );
}

function bodyenergy_services_body_class($classes)
{
    if (bodyenergy_is_services_page()) {
        $classes[] = 'bodyenergy-services-page';
    }

    return $classes;
}
add_filter('body_class', 'bodyenergy_services_body_class');

function bodyenergy_services_route_url($key, $fallback = '/')
{
    if (function_exists('bodyenergy_navigation_resolved_routes')) {
        $routes = bodyenergy_navigation_resolved_routes();
        if (!empty($routes[$key]['url'])) {
            return (string) $routes[$key]['url'];
        }
    }

    return home_url($fallback);
}

function bodyenergy_render_services_landing()
{
    $contacts_url = bodyenergy_services_route_url('contacts', '/contatti/');
    $gym_url = bodyenergy_services_route_url('gym', '/palestra-palermo/');
    $pilates_url = bodyenergy_services_route_url('pilates', '/pilates-reformer-palermo/');

    $services = array(
        array(
            'number' => '01',
            'title' => 'Fitness',
            'label' => 'ALLENAMENTO COMPLETO',
            'copy' => 'Uno spazio organizzato per allenarsi con continuità, costruire una routine efficace e lavorare sul proprio benessere con attrezzature complete.',
        ),
        array(
            'number' => '02',
            'title' => 'Bodybuilding',
            'label' => 'FORZA E COSTRUZIONE MUSCOLARE',
            'copy' => 'Un ambiente adatto a chi vuole sviluppare massa muscolare, migliorare tecnica e controllo e seguire un percorso di allenamento strutturato.',
        ),
        array(
            'number' => '03',
            'title' => 'Powerlifting',
            'label' => 'PRESTAZIONE E TECNICA',
            'copy' => 'Allenamento dedicato ai fondamentali di forza, con attenzione alla tecnica, alla progressione e alla qualità dell’esecuzione.',
        ),
        array(
            'number' => '04',
            'title' => 'Corsi fitness',
            'label' => 'ATTIVITÀ DI GRUPPO',
            'copy' => 'Corsi fitness di gruppo pensati per allenarsi in un contesto dinamico, seguito e coinvolgente. Programmazione e disponibilità vengono comunicate dalla reception.',
        ),
        array(
            'number' => '05',
            'title' => 'Pilates Reformer',
            'label' => 'CINQUE POSTAZIONI',
            'copy' => 'Lezioni su Reformer in piccoli gruppi, con cinque postazioni e un lavoro attento, preciso e personalizzato.',
            'url' => $pilates_url,
            'link' => 'Scopri il Pilates Reformer',
        ),
    );

    ob_start();
    ?>
    <main class="be-services">
        <section class="be-services__hero">
            <div class="be-services__grid" aria-hidden="true"></div>
            <div class="be-services__shell be-services__hero-layout">
                <div>
                    <p class="be-services__eyebrow">ALLENAMENTO · FORZA · BENESSERE</p>
                    <h1>Il tuo modo<br>di <span>allenarti.</span></h1>
                </div>
                <div class="be-services__hero-copy">
                    <p>Body Energy riunisce attività diverse in un unico centro, con ambienti curati e una proposta concreta: fitness, bodybuilding, powerlifting, corsi fitness di gruppo e Pilates Reformer.</p>
                    <a class="be-services__button" href="<?php echo esc_url($contacts_url); ?>">Richiedi informazioni <b>→</b></a>
                </div>
            </div>
        </section>

        <section class="be-services__intro">
            <div class="be-services__shell be-services__intro-layout">
                <div>
                    <p class="be-services__eyebrow">SERVIZI REALI</p>
                    <h2>Una palestra.<br><span>Più percorsi.</span></h2>
                </div>
                <p>Ogni attività risponde a un obiettivo diverso, ma tutte condividono lo stesso standard: qualità degli spazi, continuità e attenzione all’esperienza di allenamento.</p>
            </div>
        </section>

        <section class="be-services__list" aria-label="Servizi Body Energy">
            <div class="be-services__shell">
                <?php foreach ($services as $service) : ?>
                    <article class="be-services__service">
                        <div class="be-services__service-number"><?php echo esc_html($service['number']); ?></div>
                        <div class="be-services__service-title">
                            <small><?php echo esc_html($service['label']); ?></small>
                            <h2><?php echo esc_html($service['title']); ?></h2>
                        </div>
                        <div class="be-services__service-copy">
                            <p><?php echo esc_html($service['copy']); ?></p>
                            <?php if (!empty($service['url']) && !empty($service['link'])) : ?>
                                <a href="<?php echo esc_url($service['url']); ?>"><?php echo esc_html($service['link']); ?> <b>↗</b></a>
                            <?php endif; ?>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>

        <section class="be-services__experience">
            <div class="be-services__shell be-services__experience-layout">
                <div>
                    <p class="be-services__eyebrow">BODY ENERGY EXPERIENCE</p>
                    <h2>Spazi completi.<br><span>Comfort reale.</span></h2>
                </div>
                <div>
                    <p>La struttura è interamente climatizzata, compresi gli spogliatoi. Gli ambienti sono progettati per offrire una fruizione ordinata e confortevole durante tutto l’anno.</p>
                    <a class="be-services__text-link" href="<?php echo esc_url($gym_url); ?>">Scopri la palestra <b>→</b></a>
                </div>
            </div>
        </section>

        <section class="be-services__closing">
            <div class="be-services__shell be-services__closing-layout">
                <div>
                    <p class="be-services__eyebrow">TROVA IL TUO PERCORSO</p>
                    <h2>Parliamone<br>in reception.</h2>
                </div>
                <div>
                    <p>Raccontaci come vuoi allenarti. Ti daremo le informazioni necessarie per orientarti tra le attività realmente disponibili.</p>
                    <a class="be-services__button" href="<?php echo esc_url($contacts_url); ?>">Contatta Body Energy <b>→</b></a>
                </div>
            </div>
        </section>

        <div class="be-services__footer">
            <div class="be-services__shell">
                <strong>BODY ENERGY ASD · PALERMO</strong>
                <span>Viale Amedeo D’Aosta 3 · Palermo</span>
                <span>© <?php echo esc_html(wp_date('Y')); ?> Body Energy ASD</span>
            </div>
        </div>
    </main>
    <style>
    .be-services{--bg:#070709;--panel:#101014;--line:rgba(255,255,255,.11);--text:#f7f7f8;--muted:#a7a7b1;--red:#e3262e;--bright:#ff3b43;--ivory:#f1eee7;width:100%;overflow:hidden;background:var(--bg);color:var(--text);font-family:Inter,ui-sans-serif,system-ui,-apple-system,BlinkMacSystemFont,"Segoe UI",sans-serif}.be-services *,.be-services *:before,.be-services *:after{box-sizing:border-box}.be-services h1,.be-services h2,.be-services p{margin-top:0}.be-services a{text-decoration:none}.be-services__shell{width:min(1180px,calc(100% - 40px));margin:auto}.be-services__eyebrow{margin-bottom:18px;color:var(--bright)!important;font-size:11px;font-weight:850;line-height:1.2;letter-spacing:.19em;text-transform:uppercase}.be-services__hero{position:relative;padding:96px 0 104px;background:radial-gradient(circle at 87% 12%,rgba(227,38,46,.18),transparent 31%),linear-gradient(135deg,rgba(227,38,46,.06),transparent 48%),#08080a}.be-services__grid{position:absolute;inset:0;opacity:.15;background-image:linear-gradient(rgba(255,255,255,.045) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.045) 1px,transparent 1px);background-size:58px 58px;mask-image:linear-gradient(to bottom,#000,transparent 94%)}.be-services__hero-layout{position:relative;display:grid;grid-template-columns:1.15fr .85fr;gap:92px;align-items:end}.be-services__hero h1,.be-services__intro h2,.be-services__experience h2,.be-services__closing h2{margin-bottom:0;color:#fff;font-size:clamp(58px,6.6vw,92px);font-weight:850;line-height:.92;letter-spacing:-.064em}.be-services__hero h1 span,.be-services__intro h2 span,.be-services__experience h2 span{color:#9999a3}.be-services__hero-copy p,.be-services__intro-layout>p,.be-services__experience-layout>div:last-child p,.be-services__closing-layout>div:last-child p{color:var(--muted);font-size:17px;line-height:1.75}.be-services__hero-copy p{margin-bottom:30px}.be-services__button{display:inline-flex;min-height:56px;align-items:center;justify-content:center;gap:34px;padding:0 23px;border:1px solid var(--red);border-radius:10px;background:var(--red);box-shadow:0 18px 42px rgba(227,38,46,.23);color:#fff!important;font-size:13px;font-weight:850;transition:transform .25s ease,background .25s ease,box-shadow .25s ease}.be-services__button:hover{transform:translateY(-3px);background:#f02f38;box-shadow:0 24px 52px rgba(227,38,46,.3)}.be-services__intro{padding:100px 0 76px;background:#0c0c0f}.be-services__intro-layout{display:grid;grid-template-columns:1fr .8fr;gap:100px;align-items:end}.be-services__intro h2,.be-services__experience h2,.be-services__closing h2{font-size:clamp(48px,5vw,72px)}.be-services__intro-layout>p{max-width:570px;margin-bottom:8px}.be-services__list{background:#0c0c0f}.be-services__service{display:grid;grid-template-columns:90px 1fr .9fr;gap:44px;align-items:center;padding:48px 0;border-top:1px solid var(--line)}.be-services__service:last-child{border-bottom:1px solid var(--line)}.be-services__service-number{align-self:start;padding-top:5px;color:#5f5f69;font-size:12px;font-weight:800;letter-spacing:.12em}.be-services__service-title small{display:block;margin-bottom:11px;color:var(--bright);font-size:10px;font-weight:850;letter-spacing:.17em}.be-services__service-title h2{margin:0;color:#fff;font-size:clamp(36px,4vw,58px);font-weight:830;line-height:.96;letter-spacing:-.045em}.be-services__service-copy p{max-width:520px;margin-bottom:0;color:var(--muted);font-size:15px;line-height:1.75}.be-services__service-copy a,.be-services__text-link{display:inline-flex;align-items:center;gap:24px;margin-top:17px;padding-bottom:5px;border-bottom:1px solid rgba(255,255,255,.24);color:#fff!important;font-size:12px;font-weight:850}.be-services__service-copy a b,.be-services__text-link b{color:var(--bright)}.be-services__experience{padding:100px 0;background:var(--ivory);color:#111116}.be-services__experience-layout{display:grid;grid-template-columns:1fr .82fr;gap:104px;align-items:end}.be-services__experience .be-services__eyebrow{color:var(--red)!important}.be-services__experience h2{color:#111116}.be-services__experience h2 span{color:#77777f}.be-services__experience-layout>div:last-child p{color:#55555d}.be-services__experience .be-services__text-link{border-color:rgba(17,17,22,.25);color:#111116!important}.be-services__closing{padding:96px 0;background:radial-gradient(circle at 91% 12%,rgba(227,38,46,.18),transparent 32%),#09090c}.be-services__closing-layout{display:grid;grid-template-columns:1fr .85fr;gap:96px;align-items:end}.be-services__closing-layout>div:last-child p{max-width:560px;margin-bottom:28px}.be-services__footer{padding:25px 0;border-top:1px solid var(--line);background:#070709}.be-services__footer .be-services__shell{display:flex;align-items:center;justify-content:space-between;gap:24px;color:#70707a;font-size:10px;font-weight:700;letter-spacing:.08em;text-transform:uppercase}.be-services__footer strong{color:#f3f3f5}.be-services__footer span:last-child{text-align:right}@media(max-width:980px){.be-services__hero-layout,.be-services__intro-layout,.be-services__experience-layout,.be-services__closing-layout{grid-template-columns:1fr;gap:48px}.be-services__service{grid-template-columns:54px 1fr;gap:28px}.be-services__service-copy{grid-column:2}.be-services__footer .be-services__shell{align-items:flex-start;flex-direction:column}.be-services__footer span:last-child{text-align:left}}@media(max-width:620px){.be-services__shell{width:min(100% - 28px,1180px)}.be-services__hero{padding:72px 0 76px}.be-services__intro{padding:72px 0 48px}.be-services__experience,.be-services__closing{padding:72px 0}.be-services__hero h1{font-size:clamp(50px,15vw,66px)}.be-services__intro h2,.be-services__experience h2,.be-services__closing h2{font-size:clamp(43px,13vw,56px)}.be-services__hero-copy p,.be-services__intro-layout>p,.be-services__experience-layout>div:last-child p,.be-services__closing-layout>div:last-child p{font-size:16px}.be-services__service{grid-template-columns:1fr;gap:15px;padding:36px 0}.be-services__service-number{padding-top:0}.be-services__service-copy{grid-column:auto}.be-services__service-title h2{font-size:42px}.be-services__button{width:100%}.be-services__footer{padding:28px 0}.be-services__footer .be-services__shell{gap:12px}}@media(prefers-reduced-motion:reduce){.be-services__button{transition:none}.be-services__button:hover{transform:none}}
    </style>
    <?php

    return (string) ob_get_clean();
}
add_shortcode('bodyenergy_services_landing', 'bodyenergy_render_services_landing');

function bodyenergy_print_services_layout_css()
{
    if (!bodyenergy_is_services_page()) {
        return;
    }
    ?>
    <style id="bodyenergy-services-layout">
    html,body{max-width:none!important;margin:0!important;padding:0!important;overflow-x:clip!important;background:#070709!important}
    body.bodyenergy-services-page .site-content,body.bodyenergy-services-page #content,body.bodyenergy-services-page #primary,body.bodyenergy-services-page main.site-main,body.bodyenergy-services-page .content-area,body.bodyenergy-services-page .entry-content,body.bodyenergy-services-page .page-content,body.bodyenergy-services-page article.page,body.bodyenergy-services-page .inside-article,body.bodyenergy-services-page .elementor,body.bodyenergy-services-page .elementor-section-wrap,body.bodyenergy-services-page .elementor-element.e-con,body.bodyenergy-services-page .e-con-inner,body.bodyenergy-services-page .elementor-widget-shortcode,body.bodyenergy-services-page .elementor-widget-shortcode>.elementor-widget-container{width:100%!important;max-width:none!important;margin:0!important;padding:0!important;overflow:visible!important}
    body.bodyenergy-services-page .entry-header,body.bodyenergy-services-page .page-header,body.bodyenergy-services-page .page-hero,body.bodyenergy-services-page .featured-image,body.bodyenergy-services-page .post-thumbnail,body.bodyenergy-services-page .entry-title,body.bodyenergy-services-page .entry-meta,body.bodyenergy-services-page .breadcrumb,body.bodyenergy-services-page .breadcrumbs,body.bodyenergy-services-page .breadcrumb-area,body.bodyenergy-services-page .breadcrumb-section,body.bodyenergy-services-page .breadcumb-area,body.bodyenergy-services-page .breadcrumb-bg,body.bodyenergy-services-page .page-title-area,body.bodyenergy-services-page .page-title-section,body.bodyenergy-services-page .inner-banner,body.bodyenergy-services-page .page-banner,body.bodyenergy-services-page .banner-area,body.bodyenergy-services-page .sub-banner,body.bodyenergy-services-page .elementor-page-title,body.bodyenergy-services-page footer,body.bodyenergy-services-page #colophon,body.bodyenergy-services-page .site-footer,body.bodyenergy-services-page .footer-widgets,body.bodyenergy-services-page .edit-link,body.bodyenergy-services-page .post-edit-link,body.bodyenergy-services-page a.post-edit-link,body.bodyenergy-services-page .entry-footer{display:none!important}
    body.bodyenergy-services-page .be-services{position:relative!important;left:50%!important;width:100vw!important;max-width:100vw!important;margin-left:-50vw!important;margin-right:-50vw!important}
    </style>
    <?php
}
add_action('wp_head', 'bodyenergy_print_services_layout_css', 999);
