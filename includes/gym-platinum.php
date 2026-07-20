<?php
/**
 * Pagina Platinum "La palestra".
 *
 * Crea e aggiorna esclusivamente la bozza di staging.
 *
 * @package BodyEnergyWordPress
 */

if (!defined('ABSPATH')) {
    exit;
}

function bodyenergy_gym_page_version()
{
    return '1.0.0';
}

function bodyenergy_gym_page_url()
{
    return home_url('/palestra-palermo/');
}

function bodyenergy_bootstrap_gym_page()
{
    if (!is_admin() || !current_user_can('manage_options') || !bodyenergy_is_staging_site()) {
        return;
    }

    if (bodyenergy_gym_page_version() === get_option('bodyenergy_gym_page_version')) {
        return;
    }

    $page = bodyenergy_find_blueprint_page(array('palestra-palermo', 'palestra'));
    $post = array(
        'post_type' => 'page',
        'post_status' => 'draft',
        'post_title' => 'La palestra',
        'post_name' => 'palestra-palermo',
        'post_content' => '[bodyenergy_gym_landing]',
        'post_excerpt' => 'Gli spazi, le attrezzature e l’esperienza Body Energy ASD a Palermo.',
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
        update_option('bodyenergy_gym_page_id', (int) $page_id, false);
        update_option('bodyenergy_gym_page_version', bodyenergy_gym_page_version(), false);
    }
}
add_action('admin_init', 'bodyenergy_bootstrap_gym_page', 55);

function bodyenergy_is_gym_page()
{
    if (!is_singular('page')) {
        return false;
    }

    $post = get_post(get_queried_object_id());

    return $post instanceof WP_Post
        && (
            in_array($post->post_name, array('palestra-palermo', 'palestra'), true)
            || has_shortcode((string) $post->post_content, 'bodyenergy_gym_landing')
        );
}

function bodyenergy_gym_body_class($classes)
{
    if (bodyenergy_is_gym_page()) {
        $classes[] = 'bodyenergy-gym-page';
    }

    return $classes;
}
add_filter('body_class', 'bodyenergy_gym_body_class');

function bodyenergy_render_gym_landing()
{
    $image_url = function_exists('bodyenergy_home_platinum_image_url')
        ? bodyenergy_home_platinum_image_url()
        : '';

    ob_start();
    ?>
    <main class="be-gym">
        <section class="be-gym__hero">
            <div class="be-gym__grid" aria-hidden="true"></div>
            <div class="be-gym__shell be-gym__hero-layout">
                <div class="be-gym__hero-copy">
                    <p class="be-gym__eyebrow">BODY ENERGY ASD · PALERMO</p>
                    <h1>Spazi reali.<br><span>Energia concreta.</span></h1>
                    <p class="be-gym__lead">Una palestra completa, curata e accessibile. Attrezzature selezionate, ambienti organizzati e un team presente per accompagnare ogni percorso.</p>
                    <div class="be-gym__actions">
                        <a class="be-gym__button" href="#be-gym-spaces">Esplora la palestra <b>↓</b></a>
                        <a class="be-gym__text-link" href="<?php echo esc_url(home_url('/contatti/')); ?>">Come raggiungerci</a>
                    </div>
                    <div class="be-gym__signature">
                        <span><strong>PA</strong> Palermo</span>
                        <span><strong>01</strong> centro completo</span>
                        <span><strong>BE</strong> Body Energy</span>
                    </div>
                </div>
                <div class="be-gym__visual"<?php if ($image_url) : ?> style="--be-gym-image:url('<?php echo esc_url($image_url); ?>')"<?php endif; ?>>
                    <div class="be-gym__visual-shade"></div>
                    <span class="be-gym__visual-number">BE</span>
                    <div class="be-gym__visual-card">
                        <small>LA PALESTRA</small>
                        <strong>Allenamento.<br>Benessere.<br>Continuità.</strong>
                        <p>Viale Amedeo D’Aosta 3 · Palermo</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="be-gym__manifesto">
            <div class="be-gym__shell be-gym__manifesto-layout">
                <div>
                    <p class="be-gym__eyebrow">L’ESPERIENZA BODY ENERGY</p>
                    <h2>Tutto ciò che serve.<br><span>Nel posto giusto.</span></h2>
                </div>
                <p>Ogni ambiente è pensato per rendere l’allenamento semplice, ordinato e concreto: dalla sala fitness ai percorsi personalizzati, fino al comfort degli spazi comuni.</p>
            </div>
        </section>

        <section class="be-gym__features" id="be-gym-spaces">
            <div class="be-gym__shell be-gym__feature-grid">
                <article><span>01</span><i aria-hidden="true">＋</i><h3>Sala fitness completa</h3><p>Attrezzature selezionate e spazi organizzati per lavorare con qualità, dal primo ingresso agli obiettivi più evoluti.</p></article>
                <article><span>02</span><i aria-hidden="true">◇</i><h3>Ambienti curati</h3><p>Un’identità forte, ordine e attenzione ai dettagli per vivere la palestra con concentrazione e comfort.</p></article>
                <article><span>03</span><i aria-hidden="true">◎</i><h3>Team presente</h3><p>Persone a cui rivolgersi, indicazioni chiare e un’assistenza concreta durante il proprio percorso.</p></article>
                <article><span>04</span><i aria-hidden="true">✣</i><h3>Comfort completo</h3><p>Climatizzazione estesa anche agli spogliatoi, per mantenere una qualità costante in ogni stagione.</p></article>
            </div>
        </section>

        <section class="be-gym__focus">
            <div class="be-gym__shell be-gym__focus-layout">
                <div class="be-gym__focus-art">
                    <span>BODY<br>ENERGY</span>
                    <small>ASD · PALERMO</small>
                    <b>01</b>
                </div>
                <div class="be-gym__focus-copy">
                    <p class="be-gym__eyebrow">OLTRE LE ATTREZZATURE</p>
                    <h2>Il centro del percorso sei tu.</h2>
                    <p>Un ambiente efficace non è fatto soltanto di macchine. È fatto di presenza, organizzazione e continuità: gli elementi che permettono a ogni persona di costruire il proprio standard.</p>
                    <ul>
                        <li><span>01</span> Spazi adatti a livelli ed esigenze differenti</li>
                        <li><span>02</span> Un team disponibile e riconoscibile</li>
                        <li><span>03</span> Un’esperienza coerente, dall’ingresso all’allenamento</li>
                    </ul>
                </div>
            </div>
        </section>

        <section class="be-gym__location">
            <div class="be-gym__shell be-gym__location-layout">
                <div>
                    <p class="be-gym__eyebrow">NEL CUORE DI PALERMO</p>
                    <h2>Il tuo spazio.<br><span>Ogni giorno.</span></h2>
                </div>
                <div class="be-gym__location-card">
                    <small>BODY ENERGY ASD</small>
                    <strong>Viale Amedeo D’Aosta 3</strong>
                    <p>Palermo</p>
                    <a href="<?php echo esc_url(home_url('/contatti/')); ?>">Indicazioni e contatti <b>→</b></a>
                </div>
            </div>
        </section>
    </main>
    <style>
    .be-gym{--bg:#070709;--panel:#111114;--line:rgba(255,255,255,.11);--text:#f7f7f8;--muted:#a5a5af;--red:#e3262e;--bright:#ff3b43;width:100%;overflow:hidden;background:var(--bg);color:var(--text);font-family:Inter,ui-sans-serif,system-ui,-apple-system,BlinkMacSystemFont,"Segoe UI",sans-serif}.be-gym *,.be-gym *:before,.be-gym *:after{box-sizing:border-box}.be-gym h1,.be-gym h2,.be-gym h3,.be-gym p{margin-top:0}.be-gym a{text-decoration:none}.be-gym__shell{width:min(1180px,calc(100% - 40px));margin:auto}.be-gym__eyebrow{margin-bottom:18px;color:var(--bright);font-size:11px;font-weight:850;letter-spacing:.19em}.be-gym__hero{position:relative;padding:76px 0 82px;background:radial-gradient(circle at 85% 12%,rgba(227,38,46,.17),transparent 29%),linear-gradient(135deg,rgba(227,38,46,.07),transparent 43%),#08080a}.be-gym__grid{position:absolute;inset:0;opacity:.18;background-image:linear-gradient(rgba(255,255,255,.04) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.04) 1px,transparent 1px);background-size:56px 56px;mask-image:linear-gradient(to bottom,#000,transparent 95%)}.be-gym__hero-layout{position:relative;display:grid;grid-template-columns:1.08fr .92fr;gap:68px;align-items:center}.be-gym__hero h1{max-width:760px;margin-bottom:26px;color:#fff;font-size:clamp(56px,5.8vw,80px);line-height:.95;letter-spacing:-.06em}.be-gym__hero h1 span,.be-gym h2 span{color:var(--bright)}.be-gym__lead{max-width:620px;margin-bottom:32px;color:var(--muted);font-size:17px;line-height:1.7}.be-gym__actions{display:flex;align-items:center;gap:26px}.be-gym__button{display:inline-flex;min-height:56px;align-items:center;justify-content:space-between;gap:34px;padding:0 22px;border-radius:9px;background:var(--red);box-shadow:0 18px 42px rgba(227,38,46,.23);font-size:13px;font-weight:850}.be-gym a.be-gym__button,.be-gym a.be-gym__button:link,.be-gym a.be-gym__button:visited,.be-gym a.be-gym__button:hover,.be-gym a.be-gym__button:focus,.be-gym a.be-gym__button:active,.be-gym a.be-gym__button b{color:#fff!important;-webkit-text-fill-color:#fff!important;opacity:1!important}.be-gym__text-link{padding-bottom:4px;border-bottom:1px solid rgba(255,255,255,.25);color:#dddde2!important;font-size:13px;font-weight:750}.be-gym__signature{display:flex;margin-top:42px;border-top:1px solid var(--line);border-bottom:1px solid var(--line)}.be-gym__signature span{padding:17px 22px 17px 0;color:#777781;font-size:10px;font-weight:750;text-transform:uppercase;letter-spacing:.08em}.be-gym__signature span+span{padding-left:22px;border-left:1px solid var(--line)}.be-gym__signature strong{margin-right:6px;color:#fff;font-size:15px}.be-gym__visual{position:relative;min-height:500px;overflow:hidden;border:1px solid rgba(255,255,255,.13);border-radius:26px;background-image:linear-gradient(180deg,rgba(7,7,9,.05),rgba(7,7,9,.88)),var(--be-gym-image,linear-gradient(145deg,#35151a,#111114 58%));background-position:center;background-size:cover;box-shadow:0 38px 100px rgba(0,0,0,.48)}.be-gym__visual-shade{position:absolute;inset:0;background:linear-gradient(145deg,rgba(255,255,255,.08),transparent 40%)}.be-gym__visual-number{position:absolute;right:24px;top:14px;color:rgba(255,255,255,.07);font-size:120px;font-weight:900;line-height:1}.be-gym__visual-card{position:absolute;left:26px;right:26px;bottom:26px;padding:25px;border:1px solid rgba(255,255,255,.12);border-radius:17px;background:rgba(8,8,10,.9);backdrop-filter:blur(12px)}.be-gym__visual-card small{color:var(--bright);font-weight:850;letter-spacing:.15em}.be-gym__visual-card strong{display:block;margin:10px 0;color:#fff;font-size:31px;line-height:1.03;letter-spacing:-.04em}.be-gym__visual-card p{margin:0;color:#a7a7af;font-size:12px}.be-gym__manifesto{padding:96px 0 55px;background:#0b0b0e}.be-gym__manifesto-layout,.be-gym__focus-layout,.be-gym__location-layout{display:grid;grid-template-columns:1.05fr .95fr;gap:88px;align-items:center}.be-gym h2{margin-bottom:0;color:#fff;font-size:clamp(40px,4.5vw,62px);line-height:1.02;letter-spacing:-.052em}.be-gym__manifesto-layout>p{padding-left:32px;border-left:2px solid var(--red);color:var(--muted);font-size:17px;line-height:1.78}.be-gym__features{padding:0 0 96px;background:#0b0b0e}.be-gym__feature-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:14px}.be-gym__feature-grid article{position:relative;min-height:310px;padding:26px;border:1px solid var(--line);border-radius:17px;background:linear-gradient(145deg,rgba(255,255,255,.025),transparent),#111114}.be-gym__feature-grid article>span{position:absolute;right:20px;top:18px;color:#5e5e67;font-size:10px;font-weight:850}.be-gym__feature-grid i{display:grid;width:46px;height:46px;margin-bottom:70px;place-items:center;border:1px solid rgba(255,255,255,.12);border-radius:13px;color:var(--bright);font-style:normal;font-size:22px}.be-gym__feature-grid h3{margin-bottom:12px;color:#fff;font-size:22px;line-height:1.1}.be-gym__feature-grid p{margin:0;color:var(--muted);font-size:14px;line-height:1.65}.be-gym__focus{padding:100px 0;background:#111114}.be-gym__focus-art{position:relative;display:flex;min-height:440px;flex-direction:column;justify-content:flex-end;overflow:hidden;padding:38px;border:1px solid var(--line);border-radius:24px;background:radial-gradient(circle at 80% 15%,rgba(227,38,46,.22),transparent 30%),linear-gradient(145deg,rgba(255,255,255,.06),transparent 45%),#09090c}.be-gym__focus-art span{color:#fff;font-size:56px;font-weight:900;line-height:.82;letter-spacing:-.06em}.be-gym__focus-art small{margin-top:17px;color:var(--bright);font-weight:850;letter-spacing:.2em}.be-gym__focus-art b{position:absolute;right:18px;top:-10px;color:rgba(255,255,255,.045);font-size:180px}.be-gym__focus-copy h2{margin-bottom:24px}.be-gym__focus-copy>p:not(.be-gym__eyebrow){color:var(--muted);font-size:16px;line-height:1.75}.be-gym__focus-copy ul{margin:28px 0 0;padding:0;list-style:none}.be-gym__focus-copy li{padding:16px 0;border-top:1px solid var(--line);color:#dedee3;font-size:14px}.be-gym__focus-copy li:last-child{border-bottom:1px solid var(--line)}.be-gym__focus-copy li span{margin-right:18px;color:var(--bright);font-size:10px;font-weight:850}.be-gym__location{padding:96px 0;background:radial-gradient(circle at 100% 0,rgba(227,38,46,.18),transparent 34%),#09090c}.be-gym__location-card{padding:34px;border:1px solid var(--line);border-radius:20px;background:#121216}.be-gym__location-card small{color:var(--bright);font-weight:850;letter-spacing:.15em}.be-gym__location-card strong{display:block;margin:20px 0 5px;color:#fff;font-size:27px}.be-gym__location-card p{color:var(--muted)}.be-gym__location-card a{display:inline-flex;gap:28px;margin-top:18px;color:#fff!important;font-size:13px;font-weight:850}.be-gym__location-card a b{color:var(--bright)}@media(max-width:960px){.be-gym__hero-layout,.be-gym__manifesto-layout,.be-gym__focus-layout,.be-gym__location-layout{grid-template-columns:1fr}.be-gym__feature-grid{grid-template-columns:repeat(2,1fr)}}@media(max-width:620px){.be-gym__shell{width:min(100% - 28px,1180px)}.be-gym__hero,.be-gym__manifesto,.be-gym__focus,.be-gym__location{padding:70px 0}.be-gym__hero h1{font-size:clamp(48px,14vw,64px)}.be-gym__actions{align-items:stretch;flex-direction:column}.be-gym__signature{flex-direction:column}.be-gym__signature span+span{padding-left:0;border-left:0;border-top:1px solid var(--line)}.be-gym__visual{min-height:430px}.be-gym__feature-grid{grid-template-columns:1fr}.be-gym__feature-grid article{min-height:auto}.be-gym__feature-grid i{margin-bottom:40px}.be-gym__manifesto-layout>p{padding:26px 0 0;border-left:0;border-top:2px solid var(--red)}}    
    </style>
    <?php
    return (string) ob_get_clean();
}
add_shortcode('bodyenergy_gym_landing', 'bodyenergy_render_gym_landing');

function bodyenergy_print_gym_layout_css()
{
    if (!bodyenergy_is_gym_page()) {
        return;
    }
    ?>
    <style id="bodyenergy-gym-layout">
    html,body{max-width:none!important;margin:0!important;padding:0!important;overflow-x:clip!important;background:#070709!important}
    body.bodyenergy-gym-page .site-content,body.bodyenergy-gym-page #content,body.bodyenergy-gym-page #primary,body.bodyenergy-gym-page main.site-main,body.bodyenergy-gym-page .content-area,body.bodyenergy-gym-page .entry-content,body.bodyenergy-gym-page .page-content,body.bodyenergy-gym-page article.page,body.bodyenergy-gym-page .inside-article,body.bodyenergy-gym-page .elementor,body.bodyenergy-gym-page .elementor-section-wrap,body.bodyenergy-gym-page .elementor-element.e-con,body.bodyenergy-gym-page .e-con-inner,body.bodyenergy-gym-page .elementor-widget-shortcode,body.bodyenergy-gym-page .elementor-widget-shortcode>.elementor-widget-container{width:100%!important;max-width:none!important;margin:0!important;padding:0!important;overflow:visible!important}
    body.bodyenergy-gym-page .entry-header,body.bodyenergy-gym-page .page-header,body.bodyenergy-gym-page .page-hero,body.bodyenergy-gym-page .featured-image,body.bodyenergy-gym-page .post-thumbnail,body.bodyenergy-gym-page .entry-title,body.bodyenergy-gym-page .entry-meta,body.bodyenergy-gym-page .breadcrumb,body.bodyenergy-gym-page .breadcrumbs,body.bodyenergy-gym-page .breadcrumb-area,body.bodyenergy-gym-page .breadcrumb-section,body.bodyenergy-gym-page .breadcumb-area,body.bodyenergy-gym-page .breadcrumb-bg,body.bodyenergy-gym-page .page-title-area,body.bodyenergy-gym-page .page-title-section,body.bodyenergy-gym-page .inner-banner,body.bodyenergy-gym-page .page-banner,body.bodyenergy-gym-page .banner-area,body.bodyenergy-gym-page .sub-banner,body.bodyenergy-gym-page .elementor-page-title,body.bodyenergy-gym-page footer,body.bodyenergy-gym-page #colophon,body.bodyenergy-gym-page .site-footer,body.bodyenergy-gym-page .footer-widgets,body.bodyenergy-gym-page .edit-link{display:none!important}
    body.bodyenergy-gym-page .be-gym{position:relative!important;left:50%!important;width:100vw!important;max-width:100vw!important;margin-left:-50vw!important;margin-right:-50vw!important}
    </style>
    <?php
}
add_action('wp_head', 'bodyenergy_print_gym_layout_css', 999);

function bodyenergy_remove_gym_theme_banner()
{
    if (!bodyenergy_is_gym_page()) {
        return;
    }
    ?>
    <script id="bodyenergy-gym-banner-cleanup">
    document.addEventListener('DOMContentLoaded',function(){
        var content=document.querySelector('.be-gym');
        if(!content){return;}
        document.querySelectorAll('h1,h2').forEach(function(title){
            var text=(title.textContent||'').replace(/\s+/g,' ').trim().toLowerCase();
            if(text!=='palestra'&&text!=='la palestra'){return;}
            var block=title;
            while(block.parentElement&&block.parentElement!==document.body&&!block.parentElement.querySelector('.be-gym')){
                block=block.parentElement;
            }
            if(!block.contains(content)&&!block.querySelector('header.site-header,nav')){
                block.style.setProperty('display','none','important');
            }
        });
    });
    </script>
    <?php
}
add_action('wp_footer', 'bodyenergy_remove_gym_theme_banner', 999);
