<?php
/**
 * Flusso grafico di richiesta Pilates Reformer.
 *
 * Crea esclusivamente pagine figlie in bozza nello staging. Il modulo non
 * invia, salva o trasmette dati e non integra BodyGate o servizi esterni.
 *
 * @package BodyEnergyWordPress
 */

if (!defined('ABSPATH')) {
    exit;
}

function bodyenergy_pilates_request_flow_version()
{
    return '1.0.0';
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
            'excerpt' => 'Pagina di conferma del futuro flusso richieste Pilates Reformer.',
        ),
    );
}

function bodyenergy_bootstrap_pilates_request_flow()
{
    if (!is_admin() || !current_user_can('manage_options') || !bodyenergy_is_staging_site()) {
        return;
    }

    if (bodyenergy_pilates_request_flow_version() === get_option('bodyenergy_pilates_request_flow_version')) {
        return;
    }

    $parent = get_page_by_path('pilates-reformer-palermo', OBJECT, 'page');
    if (!($parent instanceof WP_Post)) {
        return;
    }

    $ids = array();
    foreach (bodyenergy_pilates_request_flow_pages() as $key => $spec) {
        $path = 'pilates-reformer-palermo/' . $spec['slug'];
        $existing = get_page_by_path($path, OBJECT, 'page');

        if ($existing instanceof WP_Post) {
            if ('draft' !== $existing->post_status || (int) $existing->post_parent !== (int) $parent->ID) {
                wp_update_post(array(
                    'ID' => (int) $existing->ID,
                    'post_status' => 'draft',
                    'post_parent' => (int) $parent->ID,
                ));
            }
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
        <div class="be-request__glow" aria-hidden="true"></div>
        <section class="be-request__shell">
            <header class="be-request__intro">
                <a href="<?php echo esc_url(home_url('/pilates-reformer-palermo/')); ?>">← Pilates Reformer</a>
                <p class="be-request__eyebrow">BODY ENERGY ASD · PALERMO</p>
                <h1>Il tuo percorso<br><span>inizia da qui.</span></h1>
                <p>Scegli come preferisci entrare in contatto con noi e lasciaci le informazioni essenziali. Cinque postazioni, attenzione reale, esperienza su misura.</p>
            </header>

            <form class="be-request__form" aria-describedby="be-request-prototype" onsubmit="return false;">
                <div class="be-request__intent" role="radiogroup" aria-label="Tipo di richiesta">
                    <label>
                        <input type="radio" name="be_intent" value="ricontatto" checked>
                        <span><strong>Essere ricontattato</strong><small>Desidero ricevere informazioni dal team Body Energy.</small></span>
                    </label>
                    <label>
                        <input type="radio" name="be_intent" value="lista">
                        <span><strong>Entrare nella lista Pilates</strong><small>Voglio essere inserito nella lista dedicata al Pilates Reformer.</small></span>
                    </label>
                </div>

                <div class="be-request__grid">
                    <label class="be-request__field be-request__field--wide">
                        <span>Nome e cognome *</span>
                        <input type="text" name="be_name" autocomplete="name" placeholder="Come possiamo chiamarti?" required>
                    </label>
                    <label class="be-request__field">
                        <span>Telefono *</span>
                        <input type="tel" name="be_phone" autocomplete="tel" placeholder="+39" required>
                    </label>
                    <label class="be-request__field">
                        <span>Email <em>facoltativa</em></span>
                        <input type="email" name="be_email" autocomplete="email" placeholder="nome@email.it">
                    </label>
                    <fieldset class="be-request__field">
                        <legend>Preferisco essere contattato via *</legend>
                        <div class="be-request__pills">
                            <label><input type="radio" name="be_channel" value="chiamata" required><span>Chiamata</span></label>
                            <label><input type="radio" name="be_channel" value="whatsapp" required><span>WhatsApp</span></label>
                        </div>
                    </fieldset>
                    <label class="be-request__field">
                        <span>Fascia oraria *</span>
                        <select name="be_time" required>
                            <option value="">Seleziona</option>
                            <option>Mattina · 09:00–13:00</option>
                            <option>Pomeriggio · 13:00–18:00</option>
                            <option>Sera · 18:00–20:00</option>
                        </select>
                    </label>
                    <label class="be-request__field be-request__field--wide">
                        <span>Messaggio</span>
                        <textarea name="be_message" rows="5" placeholder="Aggiungi una nota o una domanda"></textarea>
                    </label>
                </div>

                <label class="be-request__privacy">
                    <input type="checkbox" name="be_privacy" required>
                    <span>Ho letto l’informativa privacy e acconsento al trattamento dei dati per essere ricontattato in merito alla mia richiesta. *</span>
                </label>

                <button type="button" aria-describedby="be-request-prototype">Invia richiesta <span>→</span></button>
                <p id="be-request-prototype" class="be-request__note">Anteprima grafica: il modulo non invia né salva ancora alcun dato.</p>
            </form>
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
        <div class="be-request__glow" aria-hidden="true"></div>
        <section class="be-request__thanks-card">
            <span class="be-request__mark">✓</span>
            <p class="be-request__eyebrow">RICHIESTA COMPLETATA</p>
            <h1>Grazie.<br><span>Ci sentiamo presto.</span></h1>
            <p>Abbiamo predisposto questo spazio per la futura conferma della richiesta Pilates Reformer.</p>
            <a href="<?php echo esc_url(home_url('/pilates-reformer-palermo/')); ?>">Torna a Pilates Reformer</a>
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
        .be-request{--red:#e3262e;--bright:#ff3b43;--muted:#aaaab4;position:relative;min-height:100vh;overflow:hidden;padding:110px 20px;background:radial-gradient(circle at 85% 15%,rgba(227,38,46,.16),transparent 28%),#070709;color:#f7f7f8;font-family:Inter,ui-sans-serif,system-ui,-apple-system,BlinkMacSystemFont,"Segoe UI",sans-serif}.be-request *{box-sizing:border-box}.be-request__glow{position:absolute;left:-100px;bottom:10%;width:300px;height:300px;border-radius:50%;background:rgba(227,38,46,.14);filter:blur(90px)}.be-request__shell{position:relative;display:grid;grid-template-columns:.85fr 1.15fr;gap:70px;width:min(1180px,100%);margin:auto}.be-request__intro>a{display:inline-block;margin-bottom:70px;color:#d6d6da;font-size:13px;font-weight:750;text-decoration:none}.be-request__eyebrow{margin:0 0 18px;color:var(--bright);font-size:11px;font-weight:850;letter-spacing:.18em}.be-request h1{margin:0 0 25px;color:#fff;font-size:clamp(48px,6vw,78px);line-height:.96;letter-spacing:-.055em}.be-request h1 span{color:var(--bright)}.be-request__intro>p:last-child,.be-request__thanks-card>p:not(.be-request__eyebrow){max-width:520px;color:var(--muted);font-size:17px;line-height:1.75}.be-request__form{padding:38px;border:1px solid rgba(255,255,255,.11);border-radius:25px;background:linear-gradient(145deg,rgba(24,24,29,.97),rgba(12,12,15,.98));box-shadow:0 35px 100px rgba(0,0,0,.45)}.be-request__intent{display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:30px}.be-request__intent label{cursor:pointer}.be-request__intent input{position:absolute;opacity:0}.be-request__intent label>span{display:block;height:100%;padding:20px;border:1px solid rgba(255,255,255,.1);border-radius:15px;background:#101014;transition:.2s}.be-request__intent input:checked+span{border-color:rgba(255,59,67,.75);background:rgba(227,38,46,.1);box-shadow:inset 0 0 0 1px rgba(255,59,67,.15)}.be-request__intent strong,.be-request__intent small{display:block}.be-request__intent strong{margin-bottom:8px;color:#fff;font-size:15px}.be-request__intent small{color:#90909a;line-height:1.5}.be-request__grid{display:grid;grid-template-columns:1fr 1fr;gap:20px}.be-request__field{min-width:0;margin:0;padding:0;border:0}.be-request__field--wide{grid-column:1/-1}.be-request__field>span,.be-request__field legend{display:block;margin-bottom:9px;color:#dedee2;font-size:12px;font-style:normal;font-weight:750}.be-request__field em{color:#777780;font-style:normal;font-weight:500}.be-request input[type=text],.be-request input[type=tel],.be-request input[type=email],.be-request select,.be-request textarea{width:100%;min-height:50px;padding:0 15px;border:1px solid rgba(255,255,255,.12);border-radius:10px;outline:none;background:#0c0c0f;color:#fff;font:inherit}.be-request textarea{padding-top:14px;resize:vertical}.be-request input:focus,.be-request select:focus,.be-request textarea:focus{border-color:var(--bright);box-shadow:0 0 0 3px rgba(227,38,46,.12)}.be-request__pills{display:flex;gap:9px}.be-request__pills label{flex:1}.be-request__pills input{position:absolute;opacity:0}.be-request__pills span{display:flex;min-height:50px;align-items:center;justify-content:center;border:1px solid rgba(255,255,255,.12);border-radius:10px;background:#0c0c0f;color:#bdbdc5;font-size:13px;font-weight:750}.be-request__pills input:checked+span{border-color:var(--bright);background:rgba(227,38,46,.12);color:#fff}.be-request__privacy{display:flex;gap:12px;margin:24px 0 20px;color:#8f8f98;font-size:12px;line-height:1.55}.be-request__privacy input{margin-top:3px;accent-color:var(--red)}.be-request button,.be-request__thanks-card>a{display:flex;width:100%;min-height:56px;align-items:center;justify-content:space-between;padding:0 21px;border:0;border-radius:11px;background:var(--red);color:#fff;font-size:14px;font-weight:850;text-decoration:none;box-shadow:0 16px 40px rgba(227,38,46,.23)}.be-request button{cursor:default}.be-request__note{margin:12px 0 0;color:#777780;font-size:11px;text-align:center}.be-request--thanks{display:grid;place-items:center}.be-request__thanks-card{position:relative;width:min(720px,100%);padding:60px;border:1px solid rgba(255,255,255,.11);border-radius:26px;background:#111114;box-shadow:0 35px 100px rgba(0,0,0,.45)}.be-request__mark{display:grid;width:58px;height:58px;margin-bottom:34px;place-items:center;border:1px solid rgba(255,59,67,.45);border-radius:50%;background:rgba(227,38,46,.12);color:var(--bright);font-size:25px}.be-request__thanks-card>a{width:max-content;margin-top:34px;justify-content:center;padding:0 24px}@media(max-width:880px){.be-request__shell{grid-template-columns:1fr}.be-request__intro>a{margin-bottom:36px}}@media(max-width:620px){.be-request{padding:70px 14px}.be-request__form,.be-request__thanks-card{padding:25px 20px}.be-request__intent,.be-request__grid{grid-template-columns:1fr}.be-request__field--wide{grid-column:auto}.be-request__pills{flex-direction:column}}
    </style>
    <?php
}


/**
 * Collega la CTA principale della landing Pilates alla nuova pagina di richiesta.
 *
 * @param string $output Output dello shortcode.
 * @param string $tag    Nome shortcode.
 * @return string
 */
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
