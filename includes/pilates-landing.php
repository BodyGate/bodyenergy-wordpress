<?php
/**
 * Landing page pubblica Pilates Reformer.
 *
 * @package BodyEnergyWordPress
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Renderizza la landing Pilates Reformer.
 *
 * Uso: [bodyenergy_pilates_landing]
 *
 * @return string
 */
function bodyenergy_render_pilates_landing()
{
    $email = antispambot('bodyenergy.asd@gmail.com');
    $email_url = 'mailto:' . $email . '?subject=' . rawurlencode('Lista prioritaria Pilates Reformer');
    $phone_url = 'tel:+390917785001';

    ob_start();
    ?>
    <main class="be-pilates" id="be-pilates-top">
        <section class="be-pilates__hero">
            <div class="be-pilates__glow be-pilates__glow--one" aria-hidden="true"></div>
            <div class="be-pilates__glow be-pilates__glow--two" aria-hidden="true"></div>

            <div class="be-pilates__shell be-pilates__hero-grid">
                <div class="be-pilates__hero-copy">
                    <p class="be-pilates__eyebrow">BODY ENERGY ASD · PALERMO</p>
                    <h1>Pilates Reformer.<br><span>Solo quattro postazioni.</span></h1>
                    <p class="be-pilates__lead">
                        Un percorso preciso, controllato e realmente seguito. Piccoli gruppi, attenzione individuale e una nuova sala dedicata al movimento di qualità.
                    </p>

                    <div class="be-pilates__actions">
                        <a class="be-pilates__button be-pilates__button--primary" href="#be-pilates-contact">Entra nella lista prioritaria</a>
                        <a class="be-pilates__button be-pilates__button--ghost" href="#be-pilates-experience">Scopri l’esperienza</a>
                    </div>

                    <div class="be-pilates__chips" aria-label="Informazioni principali">
                        <span>Prova gratuita</span>
                        <span>Posti limitati</span>
                        <span>Palermo</span>
                    </div>
                </div>

                <div class="be-pilates__visual" aria-label="Anteprima sala Pilates Reformer">
                    <div class="be-pilates__visual-frame">
                        <div class="be-pilates__visual-line"></div>
                        <span class="be-pilates__visual-number">04</span>
                        <div class="be-pilates__visual-copy">
                            <small>POSTAZIONI</small>
                            <strong>Più spazio.<br>Più attenzione.<br>Più qualità.</strong>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="be-pilates__section" id="be-pilates-experience">
            <div class="be-pilates__shell">
                <div class="be-pilates__section-head">
                    <div>
                        <p class="be-pilates__eyebrow">UN METODO PIÙ PERSONALE</p>
                        <h2>Non una sala affollata.<br>Un’esperienza costruita intorno a te.</h2>
                    </div>
                    <p>
                        Ogni lezione nasce per offrire controllo, continuità e assistenza. Il numero ridotto di partecipanti permette all’insegnante di seguire davvero ogni movimento.
                    </p>
                </div>

                <div class="be-pilates__features">
                    <article>
                        <span>01</span>
                        <h3>Massimo quattro persone</h3>
                        <p>Lezioni raccolte e ordinate, senza dispersione e senza confusione.</p>
                    </article>
                    <article>
                        <span>02</span>
                        <h3>Attenzione individuale</h3>
                        <p>Correzioni puntuali, progressione controllata e lavoro adatto al tuo livello.</p>
                    </article>
                    <article>
                        <span>03</span>
                        <h3>Precisione e continuità</h3>
                        <p>Un percorso pensato per postura, forza, mobilità e consapevolezza del movimento.</p>
                    </article>
                </div>
            </div>
        </section>

        <section class="be-pilates__manifesto">
            <div class="be-pilates__shell be-pilates__manifesto-grid">
                <div>
                    <p class="be-pilates__eyebrow">NUOVA SALA · PROSSIMA APERTURA</p>
                    <h2>La qualità non ha bisogno di grandi numeri.</h2>
                </div>
                <div class="be-pilates__manifesto-copy">
                    <p>
                        Stiamo preparando il nuovo spazio Pilates Reformer di Body Energy. La lista prioritaria riceverà per prima disponibilità, prova gratuita e condizioni di lancio.
                    </p>
                </div>
            </div>
        </section>

        <section class="be-pilates__contact" id="be-pilates-contact">
            <div class="be-pilates__shell be-pilates__contact-card">
                <div>
                    <p class="be-pilates__eyebrow">LISTA PRIORITARIA</p>
                    <h2>Ricevi in anteprima orari, prova gratuita e promo lancio.</h2>
                    <p>Scrivici indicando “PILATES”. Ti inseriremo nella lista prioritaria senza alcun impegno.</p>
                </div>
                <div class="be-pilates__contact-actions">
                    <a class="be-pilates__button be-pilates__button--primary" href="<?php echo esc_url($email_url); ?>">Scrivici via email</a>
                    <a class="be-pilates__button be-pilates__button--ghost" href="<?php echo esc_url($phone_url); ?>">Chiama Body Energy</a>
                    <small>Viale Amedeo D’Aosta 3, Palermo</small>
                </div>
            </div>
        </section>
    </main>

    <style>
        .be-pilates {
            --be-bg: #070709;
            --be-panel: #111114;
            --be-panel-soft: #17171b;
            --be-line: rgba(255,255,255,.12);
            --be-text: #f7f7f8;
            --be-muted: #aaaab4;
            --be-red: #e3262e;
            --be-red-bright: #ff3b43;
            width: 100%;
            overflow: hidden;
            background: var(--be-bg);
            color: var(--be-text);
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }

        .be-pilates *, .be-pilates *::before, .be-pilates *::after { box-sizing: border-box; }
        .be-pilates h1, .be-pilates h2, .be-pilates h3, .be-pilates p { margin-top: 0; }
        .be-pilates a { text-decoration: none; }
        .be-pilates__shell { width: min(1180px, calc(100% - 40px)); margin: 0 auto; }

        .be-pilates__hero {
            position: relative;
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 90px 0 72px;
            background:
                linear-gradient(135deg, rgba(227,38,46,.12), transparent 42%),
                radial-gradient(circle at 78% 30%, rgba(227,38,46,.18), transparent 28%),
                #08080a;
        }

        .be-pilates__hero::after {
            content: "";
            position: absolute;
            inset: 0;
            pointer-events: none;
            opacity: .22;
            background-image: linear-gradient(rgba(255,255,255,.04) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,.04) 1px, transparent 1px);
            background-size: 52px 52px;
            mask-image: linear-gradient(to bottom, #000, transparent 88%);
        }

        .be-pilates__glow { position: absolute; border-radius: 999px; filter: blur(70px); opacity: .22; }
        .be-pilates__glow--one { width: 260px; height: 260px; right: 12%; top: 16%; background: var(--be-red); }
        .be-pilates__glow--two { width: 190px; height: 190px; left: 8%; bottom: 10%; background: #7f1d1d; }

        .be-pilates__hero-grid {
            position: relative;
            z-index: 1;
            display: grid;
            grid-template-columns: 1.08fr .92fr;
            gap: 68px;
            align-items: center;
        }

        .be-pilates__eyebrow {
            margin-bottom: 18px;
            color: var(--be-red-bright);
            font-size: 12px;
            font-weight: 800;
            letter-spacing: .18em;
        }

        .be-pilates__hero h1 {
            margin-bottom: 25px;
            font-size: clamp(48px, 7vw, 92px);
            line-height: .96;
            letter-spacing: -.055em;
            color: #fff;
        }

        .be-pilates__hero h1 span { color: var(--be-red-bright); }
        .be-pilates__lead { max-width: 650px; margin-bottom: 34px; color: var(--be-muted); font-size: clamp(17px, 2vw, 21px); line-height: 1.65; }
        .be-pilates__actions { display: flex; flex-wrap: wrap; gap: 12px; }

        .be-pilates__button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 52px;
            padding: 0 22px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 800;
            transition: transform .2s ease, border-color .2s ease, background .2s ease;
        }
        .be-pilates__button:hover { transform: translateY(-2px); }
        .be-pilates__button--primary { background: var(--be-red); color: #fff; box-shadow: 0 15px 40px rgba(227,38,46,.25); }
        .be-pilates__button--primary:hover { background: var(--be-red-bright); color: #fff; }
        .be-pilates__button--ghost { border: 1px solid var(--be-line); background: rgba(255,255,255,.03); color: #fff; }
        .be-pilates__button--ghost:hover { border-color: rgba(255,255,255,.3); color: #fff; }

        .be-pilates__chips { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 28px; }
        .be-pilates__chips span { padding: 8px 12px; border: 1px solid var(--be-line); border-radius: 999px; color: #d7d7dc; font-size: 12px; }

        .be-pilates__visual { display: flex; justify-content: flex-end; }
        .be-pilates__visual-frame {
            position: relative;
            width: min(100%, 460px);
            aspect-ratio: 4 / 5;
            overflow: hidden;
            border: 1px solid var(--be-line);
            border-radius: 26px;
            background:
                linear-gradient(145deg, rgba(255,255,255,.09), transparent 45%),
                linear-gradient(180deg, rgba(227,38,46,.2), transparent 48%),
                #121216;
            box-shadow: 0 35px 90px rgba(0,0,0,.5);
        }
        .be-pilates__visual-frame::before,
        .be-pilates__visual-frame::after {
            content: "";
            position: absolute;
            left: 12%;
            right: 12%;
            height: 2px;
            background: linear-gradient(90deg, transparent, var(--be-red-bright), transparent);
            box-shadow: 0 0 28px var(--be-red);
        }
        .be-pilates__visual-frame::before { top: 34%; transform: rotate(-18deg); }
        .be-pilates__visual-frame::after { top: 62%; transform: rotate(14deg); }
        .be-pilates__visual-line { position: absolute; inset: 12%; border: 1px solid rgba(255,255,255,.08); border-radius: 18px; }
        .be-pilates__visual-number { position: absolute; top: 24px; right: 28px; color: rgba(255,255,255,.08); font-size: 110px; font-weight: 900; line-height: 1; }
        .be-pilates__visual-copy { position: absolute; left: 34px; right: 34px; bottom: 38px; z-index: 2; }
        .be-pilates__visual-copy small { display: block; margin-bottom: 12px; color: var(--be-red-bright); font-weight: 800; letter-spacing: .16em; }
        .be-pilates__visual-copy strong { display: block; color: #fff; font-size: clamp(30px, 4vw, 48px); line-height: 1.06; letter-spacing: -.035em; }

        .be-pilates__section { padding: 110px 0; background: #0b0b0e; }
        .be-pilates__section-head { display: grid; grid-template-columns: 1.1fr .9fr; gap: 70px; align-items: end; }
        .be-pilates__section h2, .be-pilates__manifesto h2, .be-pilates__contact h2 { color: #fff; font-size: clamp(36px, 5vw, 62px); line-height: 1.04; letter-spacing: -.045em; }
        .be-pilates__section-head > p { margin-bottom: 4px; color: var(--be-muted); font-size: 17px; line-height: 1.75; }

        .be-pilates__features { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-top: 58px; }
        .be-pilates__features article { min-height: 300px; padding: 30px; border: 1px solid var(--be-line); border-radius: 18px; background: var(--be-panel); }
        .be-pilates__features article > span { display: block; margin-bottom: 80px; color: var(--be-red-bright); font-size: 13px; font-weight: 900; }
        .be-pilates__features h3 { margin-bottom: 14px; color: #fff; font-size: 24px; line-height: 1.15; }
        .be-pilates__features p { margin-bottom: 0; color: var(--be-muted); line-height: 1.65; }

        .be-pilates__manifesto { padding: 110px 0; border-top: 1px solid var(--be-line); border-bottom: 1px solid var(--be-line); background: linear-gradient(135deg, rgba(227,38,46,.13), transparent 45%), #111114; }
        .be-pilates__manifesto-grid { display: grid; grid-template-columns: 1.1fr .9fr; gap: 70px; align-items: center; }
        .be-pilates__manifesto-copy { padding-left: 32px; border-left: 2px solid var(--be-red); }
        .be-pilates__manifesto-copy p { margin-bottom: 0; color: var(--be-muted); font-size: 18px; line-height: 1.8; }

        .be-pilates__contact { padding: 100px 0; background: #070709; }
        .be-pilates__contact-card { display: grid; grid-template-columns: 1.15fr .85fr; gap: 70px; align-items: center; padding: 56px; border: 1px solid rgba(227,38,46,.3); border-radius: 24px; background: radial-gradient(circle at 100% 0, rgba(227,38,46,.18), transparent 35%), var(--be-panel); }
        .be-pilates__contact h2 { margin-bottom: 20px; }
        .be-pilates__contact p:not(.be-pilates__eyebrow) { margin-bottom: 0; color: var(--be-muted); font-size: 17px; line-height: 1.7; }
        .be-pilates__contact-actions { display: flex; flex-direction: column; gap: 12px; }
        .be-pilates__contact-actions small { margin-top: 5px; color: #85858f; text-align: center; }

        @media (max-width: 900px) {
            .be-pilates__hero { min-height: auto; }
            .be-pilates__hero-grid, .be-pilates__section-head, .be-pilates__manifesto-grid, .be-pilates__contact-card { grid-template-columns: 1fr; }
            .be-pilates__visual { justify-content: flex-start; }
            .be-pilates__features { grid-template-columns: 1fr; }
            .be-pilates__features article { min-height: 230px; }
            .be-pilates__features article > span { margin-bottom: 45px; }
            .be-pilates__manifesto-copy { padding-left: 0; padding-top: 24px; border-left: 0; border-top: 2px solid var(--be-red); }
        }

        @media (max-width: 600px) {
            .be-pilates__shell { width: min(100% - 28px, 1180px); }
            .be-pilates__hero { padding: 68px 0 54px; }
            .be-pilates__hero-grid, .be-pilates__section-head, .be-pilates__manifesto-grid, .be-pilates__contact-card { gap: 38px; }
            .be-pilates__actions { flex-direction: column; }
            .be-pilates__button { width: 100%; }
            .be-pilates__section, .be-pilates__manifesto, .be-pilates__contact { padding: 72px 0; }
            .be-pilates__contact-card { padding: 30px 22px; }
        }
    </style>
    <?php

    return (string) ob_get_clean();
}
add_shortcode('bodyenergy_pilates_landing', 'bodyenergy_render_pilates_landing');
