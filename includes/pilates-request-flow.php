<?php
/**
 * Flusso Platinum, esclusivamente grafico, per le richieste Pilates Reformer.
 *
 * @package BodyEnergyWordPress
 */

if (!defined('ABSPATH')) {
    exit;
}

function bodyenergy_pilates_request_flow_version()
{
    return '1.1.0';
}

function bodyenergy_pilates_request_page_url()
{
    return home_url('/pilates-reformer-palermo/richiesta/');
}

function bodyenergy_pilates_request_flow_pages()
{
    return array(
        'richiesta' => array(
            'title' => 'Richiesta Pilates Reformer',
            'slug' => 'richiesta',
            'content' => '[bodyenergy_pilates_request_form]',
            'excerpt' => 'Richiedi di essere ricontattato oppure entra nella lista Pilates Reformer Body Energy.',
        ),
        'grazie' => array(
            'title' => 'Grazie',
            'slug' => 'grazie',
            'content' => '[bodyenergy_pilates_request_thanks]',
            'excerpt' => 'Conferma della futura richiesta Pilates Reformer.',
        ),
    );
}

function bodyenergy_bootstrap_pilates_request_flow()
{
    if (!is_admin() || !current_user_can('manage_options') || !bodyenergy_is_staging_site()) {
        return;
    }

    $parent = get_page_by_path('pilates-reformer-palermo', OBJECT, 'page');
    if (!($parent instanceof WP_Post)) {
        return;
    }

    $ids = array();
    foreach (bodyenergy_pilates_request_flow_pages() as $key => $spec) {
        $existing = get_page_by_path('pilates-reformer-palermo/' . $spec['slug'], OBJECT, 'page');

        if ($existing instanceof WP_Post) {
            wp_update_post(array(
                'ID' => (int) $existing->ID,
                'post_status' => 'draft',
                'post_parent' => (int) $parent->ID,
                'post_content' => $spec['content'],
                'post_excerpt' => $spec['excerpt'],
            ));
            $ids[$key] = (int) $existing->ID;
            continue;
        }

        $page_id = wp_insert_post(array(
            'post_type' => 'page',
            'post_status' => 'draft',
            'post_parent' => (int) $parent->ID,
            'post_title' => $spec['title'],
            'post_name' => $spec['slug'],
            'post_content' => $spec['content'],
            'post_excerpt' => $spec['excerpt'],
            'comment_status' => 'closed',
            'ping_status' => 'closed',
        ), true);

        if (!is_wp_error($page_id)) {
            $ids[$key] = (int) $page_id;
        }
    }

    update_option('bodyenergy_pilates_request_flow_state', array('pages' => $ids), false);
    update_option('bodyenergy_pilates_request_flow_version', bodyenergy_pilates_request_flow_version(), false);
}
add_action('admin_init', 'bodyenergy_bootstrap_pilates_request_flow', 45);

function bodyenergy_render_pilates_request_form()
{
    ob_start();
    ?>
    <main class="be-request">
        <section class="be-request__hero">
            <div class="be-request__gridlines" aria-hidden="true"></div>
            <div class="be-request__orb" aria-hidden="true"></div>
            <div class="be-request__shell">
                <nav class="be-request__back" aria-label="Navigazione Pilates">
                    <a href="<?php echo esc_url(home_url('/pilates-reformer-palermo/')); ?>"><span>←</span> Torna a Pilates Reformer</a>
                    <small>RICHIESTA INFORMAZIONI</small>
                </nav>

                <div class="be-request__layout">
                    <header class="be-request__intro">
                        <p class="be-request__eyebrow">BODY ENERGY ASD · PALERMO</p>
                        <h1>Iniziamo dal<br><span>tuo obiettivo.</span></h1>
                        <p class="be-request__lead">Lasciaci le informazioni essenziali. Il team Body Energy potrà ricontattarti nel modo e nella fascia oraria che preferisci.</p>

                        <div class="be-request__facts" aria-label="Caratteristiche Pilates Reformer">
                            <article><strong>05</strong><span>postazioni<br>Reformer</span></article>
                            <article><strong>01</strong><span>esperienza<br>seguita</span></article>
                            <article><strong>PA</strong><span>Palermo<br>Body Energy</span></article>
                        </div>

                        <div class="be-request__assurance">
                            <span aria-hidden="true">◇</span>
                            <p><strong>Contatto personale.</strong><br>Nessun automatismo e nessun dato trasmesso in questa anteprima.</p>
                        </div>
                    </header>

                    <section class="be-request__panel" aria-label="Modulo richiesta Pilates">
                        <div class="be-request__panel-head">
                            <span>01</span>
                            <div><small>LA TUA RICHIESTA</small><h2>Come possiamo aiutarti?</h2></div>
                        </div>

                        <form class="be-request__form" aria-describedby="be-request-prototype" onsubmit="return false;">
                            <div class="be-request__intent" role="radiogroup" aria-label="Tipo di richiesta">
                                <label>
                                    <input type="radio" name="be_intent" value="ricontatto" checked>
                                    <span class="be-request__choice"><i>01</i><strong>Essere ricontattato</strong><small>Vorrei ricevere maggiori informazioni.</small></span>
                                </label>
                                <label>
                                    <input type="radio" name="be_intent" value="lista">
                                    <span class="be-request__choice"><i>02</i><strong>Entrare nella lista Pilates</strong><small>Desidero essere inserito nella lista dedicata.</small></span>
                                </label>
                            </div>

                            <div class="be-request__fields">
                                <label class="be-request__field be-request__wide">
                                    <span>Nome e cognome <b>*</b></span>
                                    <input type="text" name="be_name" autocomplete="name" placeholder="Inserisci il tuo nome" required>
                                </label>
                                <label class="be-request__field">
                                    <span>Telefono <b>*</b></span>
                                    <input type="tel" name="be_phone" autocomplete="tel" placeholder="+39 000 000 0000" required>
                                </label>
                                <label class="be-request__field">
                                    <span>Email <em>facoltativa</em></span>
                                    <input type="email" name="be_email" autocomplete="email" placeholder="nome@email.it">
                                </label>
                                <fieldset class="be-request__field">
                                    <legend>Contatto preferito <b>*</b></legend>
                                    <div class="be-request__pills">
                                        <label><input type="radio" name="be_channel" value="chiamata" required><span>Chiamata</span></label>
                                        <label><input type="radio" name="be_channel" value="whatsapp" required><span>WhatsApp</span></label>
                                    </div>
                                </fieldset>
                                <label class="be-request__field">
                                    <span>Fascia oraria <b>*</b></span>
                                    <select name="be_time" required>
                                        <option value="">Scegli una fascia</option>
                                        <option>Mattina · 09:00–13:00</option>
                                        <option>Pomeriggio · 13:00–18:00</option>
                                        <option>Sera · 18:00–20:00</option>
                                    </select>
                                </label>
                                <label class="be-request__field be-request__wide">
                                    <span>Messaggio</span>
                                    <textarea name="be_message" rows="4" placeholder="Raccontaci brevemente cosa vorresti sapere"></textarea>
                                </label>
                            </div>

                            <label class="be-request__privacy">
                                <input type="checkbox" name="be_privacy" required>
                                <span>Ho letto l’informativa privacy e acconsento al trattamento dei dati per essere ricontattato in merito alla richiesta. *</span>
                            </label>

                            <div class="be-request__submit">
                                <button type="button" aria-describedby="be-request-prototype"><span>Invia richiesta</span><b>→</b></button>
                                <p id="be-request-prototype">Anteprima grafica · nessun dato viene inviato o salvato.</p>
                            </div>
                        </form>
                    </section>
                </div>
            </div>
        </section>
    </main>
    <?php
    bodyenergy_render_pilates_request_styles();
    return (string) ob_get_clean();
}
add_shortcode('bodyenergy_pilates_request_form', 'bodyenergy_render_pilates_request_form');

function bodyenergy_render_pilates_request_thanks()
{
    ob_start();
    ?>
    <main class="be-request be-request--thanks">
        <section class="be-request__thanks">
            <div class="be-request__gridlines" aria-hidden="true"></div>
            <div class="be-request__thanks-inner">
                <span class="be-request__thanks-number">05</span>
                <p class="be-request__eyebrow">BODY ENERGY · PILATES REFORMER</p>
                <div class="be-request__check">✓</div>
                <h1>Grazie.<br><span>Il prossimo passo è nostro.</span></h1>
                <p>Questo spazio è pronto per la futura conferma della tua richiesta Pilates Reformer.</p>
                <a href="<?php echo esc_url(home_url('/pilates-reformer-palermo/')); ?>">Torna a Pilates Reformer <span>→</span></a>
            </div>
        </section>
    </main>
    <?php
    bodyenergy_render_pilates_request_styles();
    return (string) ob_get_clean();
}
add_shortcode('bodyenergy_pilates_request_thanks', 'bodyenergy_render_pilates_request_thanks');

function bodyenergy_render_pilates_request_styles()
{
    ?>
    <style>
    .be-request{--bg:#070709;--panel:#111114;--panel2:#17171b;--line:rgba(255,255,255,.11);--text:#f7f7f8;--muted:#a3a3ad;--red:#e3262e;--bright:#ff3b43;width:100%;background:var(--bg);color:var(--text);font-family:Inter,ui-sans-serif,system-ui,-apple-system,BlinkMacSystemFont,"Segoe UI",sans-serif}.be-request *,.be-request *:before,.be-request *:after{box-sizing:border-box}.be-request h1,.be-request h2,.be-request p{margin-top:0}.be-request a{text-decoration:none}.be-request__hero{position:relative;min-height:calc(100vh - 120px);overflow:hidden;padding:56px 0 90px;background:radial-gradient(circle at 86% 8%,rgba(227,38,46,.17),transparent 28%),linear-gradient(135deg,rgba(227,38,46,.07),transparent 42%),#08080a}.be-request__gridlines{position:absolute;inset:0;opacity:.2;background-image:linear-gradient(rgba(255,255,255,.035) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.035) 1px,transparent 1px);background-size:54px 54px;mask-image:linear-gradient(to bottom,#000,transparent 90%)}.be-request__orb{position:absolute;right:-140px;top:120px;width:420px;height:420px;border-radius:50%;background:rgba(227,38,46,.1);filter:blur(105px)}.be-request__shell{position:relative;z-index:1;width:min(1180px,calc(100% - 40px));margin:auto}.be-request__back{display:flex;align-items:center;justify-content:space-between;margin-bottom:64px;padding-bottom:20px;border-bottom:1px solid var(--line)}.be-request__back a{color:#dedee3;font-size:13px;font-weight:750}.be-request__back a span{margin-right:8px;color:var(--bright)}.be-request__back small{color:#707079;font-size:10px;font-weight:800;letter-spacing:.18em}.be-request__layout{display:grid;grid-template-columns:.84fr 1.16fr;gap:78px;align-items:start}.be-request__intro{position:sticky;top:30px;padding-top:18px}.be-request__eyebrow{margin-bottom:20px;color:var(--bright);font-size:11px;font-weight:850;letter-spacing:.19em}.be-request__intro h1,.be-request__thanks h1{margin-bottom:26px;color:#fff;font-size:clamp(52px,6.1vw,82px);line-height:.96;letter-spacing:-.058em}.be-request__intro h1 span,.be-request__thanks h1 span{color:var(--bright)}.be-request__lead{max-width:500px;margin-bottom:35px;color:var(--muted);font-size:17px;line-height:1.72}.be-request__facts{display:grid;grid-template-columns:repeat(3,1fr);margin:0 0 30px;border-top:1px solid var(--line);border-bottom:1px solid var(--line)}.be-request__facts article{padding:20px 12px 20px 0}.be-request__facts article+article{padding-left:16px;border-left:1px solid var(--line)}.be-request__facts strong,.be-request__facts span{display:block}.be-request__facts strong{margin-bottom:7px;color:#fff;font-size:24px;line-height:1}.be-request__facts span{color:#777781;font-size:10px;font-weight:750;line-height:1.45;text-transform:uppercase;letter-spacing:.08em}.be-request__assurance{display:flex;gap:13px;align-items:flex-start;color:#81818b}.be-request__assurance>span{color:var(--bright)}.be-request__assurance p{margin:0;font-size:12px;line-height:1.55}.be-request__assurance strong{color:#c8c8ce}.be-request__panel{overflow:hidden;border:1px solid var(--line);border-radius:24px;background:linear-gradient(145deg,rgba(24,24,29,.98),rgba(10,10,13,.99));box-shadow:0 40px 100px rgba(0,0,0,.48)}.be-request__panel-head{display:flex;gap:18px;align-items:center;padding:27px 34px;border-bottom:1px solid var(--line);background:rgba(255,255,255,.018)}.be-request__panel-head>span{display:grid;width:42px;height:42px;place-items:center;border:1px solid rgba(255,59,67,.35);border-radius:50%;color:var(--bright);font-size:11px;font-weight:850}.be-request__panel-head small{color:#777781;font-size:9px;font-weight:800;letter-spacing:.16em}.be-request__panel-head h2{margin:4px 0 0;color:#fff;font-size:24px;letter-spacing:-.025em}.be-request__form{padding:32px 34px 36px}.be-request__intent{display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:28px}.be-request__intent>label{cursor:pointer}.be-request__intent input{position:absolute;opacity:0}.be-request__choice{position:relative;display:flex;min-height:142px;flex-direction:column;padding:20px;border:1px solid var(--line);border-radius:15px;background:#0d0d10;transition:.2s}.be-request__choice i{position:absolute;right:16px;top:14px;color:#55555d;font-size:10px;font-style:normal;font-weight:850}.be-request__choice strong{max-width:190px;margin:25px 0 8px;color:#fff;font-size:15px}.be-request__choice small{color:#85858f;font-size:12px;line-height:1.5}.be-request__intent input:checked+.be-request__choice{border-color:rgba(255,59,67,.72);background:linear-gradient(145deg,rgba(227,38,46,.16),rgba(227,38,46,.05));box-shadow:inset 3px 0 0 var(--bright)}.be-request__fields{display:grid;grid-template-columns:1fr 1fr;gap:19px}.be-request__field{min-width:0;margin:0;padding:0;border:0}.be-request__wide{grid-column:1/-1}.be-request__field>span,.be-request__field legend{display:block;margin-bottom:9px;color:#d8d8dd;font-size:11px;font-weight:750}.be-request__field b{color:var(--bright)}.be-request__field em{color:#686871;font-style:normal;font-weight:500}.be-request input[type=text],.be-request input[type=tel],.be-request input[type=email],.be-request select,.be-request textarea{width:100%;min-height:50px;padding:0 15px;border:1px solid rgba(255,255,255,.12);border-radius:9px;outline:none;background:#09090c;color:#fff;font:inherit;font-size:14px}.be-request textarea{padding-top:14px;resize:vertical}.be-request input:focus,.be-request select:focus,.be-request textarea:focus{border-color:var(--bright);box-shadow:0 0 0 3px rgba(227,38,46,.1)}.be-request__pills{display:flex;gap:8px}.be-request__pills label{flex:1}.be-request__pills input{position:absolute;opacity:0}.be-request__pills span{display:flex;min-height:50px;align-items:center;justify-content:center;border:1px solid rgba(255,255,255,.12);border-radius:9px;background:#09090c;color:#aaaab3;font-size:12px;font-weight:750}.be-request__pills input:checked+span{border-color:var(--bright);background:rgba(227,38,46,.12);color:#fff}.be-request__privacy{display:flex;gap:12px;margin:23px 0;color:#85858f;font-size:11px;line-height:1.55}.be-request__privacy input{margin-top:3px;accent-color:var(--red)}.be-request__submit{display:grid;grid-template-columns:1fr auto;gap:18px;align-items:center}.be-request__submit button{display:flex;min-height:54px;align-items:center;justify-content:space-between;padding:0 20px;border:0;border-radius:9px;background:var(--red);color:#fff;font-size:13px;font-weight:850;box-shadow:0 16px 38px rgba(227,38,46,.22);cursor:default}.be-request__submit button b{font-size:19px}.be-request__submit p{max-width:150px;margin:0;color:#63636c;font-size:9px;line-height:1.5}.be-request__thanks{position:relative;display:grid;min-height:calc(100vh - 120px);place-items:center;overflow:hidden;padding:90px 20px;background:radial-gradient(circle at 75% 18%,rgba(227,38,46,.18),transparent 28%),#08080a}.be-request__thanks-inner{position:relative;z-index:1;width:min(820px,100%);padding:70px;border:1px solid var(--line);border-radius:26px;background:linear-gradient(145deg,rgba(24,24,29,.97),rgba(9,9,12,.98));box-shadow:0 40px 110px rgba(0,0,0,.5)}.be-request__thanks-number{position:absolute;right:30px;top:15px;color:rgba(255,255,255,.045);font-size:170px;font-weight:900;line-height:1}.be-request__check{display:grid;width:58px;height:58px;margin:34px 0;place-items:center;border:1px solid rgba(255,59,67,.45);border-radius:50%;background:rgba(227,38,46,.12);color:var(--bright);font-size:23px}.be-request__thanks h1{max-width:700px;font-size:clamp(48px,6vw,76px)}.be-request__thanks-inner>p:not(.be-request__eyebrow){max-width:560px;color:var(--muted);font-size:17px;line-height:1.7}.be-request__thanks a{display:inline-flex;min-height:52px;align-items:center;gap:36px;margin-top:22px;padding:0 20px;border-radius:9px;background:var(--red);color:#fff;font-size:13px;font-weight:850}@media(max-width:920px){.be-request__layout{grid-template-columns:1fr}.be-request__intro{position:relative;top:auto}.be-request__lead{max-width:700px}}@media(max-width:620px){.be-request__hero{padding:34px 0 60px}.be-request__shell{width:min(100% - 26px,1180px)}.be-request__back{margin-bottom:42px}.be-request__back small{display:none}.be-request__intro h1{font-size:clamp(48px,15vw,66px)}.be-request__facts{grid-template-columns:1fr}.be-request__facts article+article{padding-left:0;border-left:0;border-top:1px solid var(--line)}.be-request__panel{border-radius:18px}.be-request__panel-head,.be-request__form{padding:24px 20px}.be-request__intent,.be-request__fields{grid-template-columns:1fr}.be-request__wide{grid-column:auto}.be-request__submit{grid-template-columns:1fr}.be-request__submit p{max-width:none;text-align:center}.be-request__thanks-inner{padding:42px 24px}.be-request__thanks-number{font-size:105px}}
    </style>
    <?php
}

function bodyenergy_link_pilates_landing_to_request($output, $tag)
{
    if ('bodyenergy_pilates_landing' !== $tag) {
        return $output;
    }

    return str_replace(
        'href="#be-pilates-contact">Entra nella lista prioritaria</a>',
        'href="' . esc_url(bodyenergy_pilates_request_page_url()) . '">Richiedi informazioni</a>',
        $output
    );
}
add_filter('do_shortcode_tag', 'bodyenergy_link_pilates_landing_to_request', 30, 2);
