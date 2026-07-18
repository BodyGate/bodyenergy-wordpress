<?php
/**
 * Esperienza Platinum Pilates Reformer.
 *
 * @package BodyEnergyWordPress
 */

if (!defined('ABSPATH')) {
    exit;
}

function bodyenergy_render_pilates_landing()
{
    $request_url = function_exists('bodyenergy_pilates_request_page_url')
        ? bodyenergy_pilates_request_page_url()
        : home_url('/pilates-reformer-palermo/richiesta/');

    ob_start();
    ?>
    <main class="be-pilates" id="be-pilates-top">
        <section class="be-pilates__hero">
            <div class="be-pilates__mesh" aria-hidden="true"></div>
            <div class="be-pilates__shell be-pilates__hero-layout">
                <div class="be-pilates__hero-copy">
                    <p class="be-pilates__eyebrow">BODY ENERGY ASD · PALERMO</p>
                    <h1>Pilates Reformer.<br><span>Più attenzione a te.</span></h1>
                    <p class="be-pilates__lead">Cinque postazioni, piccoli gruppi e un ambiente dedicato. Un’esperienza precisa e seguita, pensata per dare qualità a ogni movimento.</p>
                    <div class="be-pilates__actions">
                        <a class="be-pilates__button be-pilates__button--primary" href="<?php echo esc_url($request_url); ?>">Richiedi informazioni <b>→</b></a>
                        <a class="be-pilates__text-link" href="#be-pilates-experience">Scopri l’esperienza</a>
                    </div>
                    <div class="be-pilates__signature">
                        <span><strong>05</strong> postazioni</span>
                        <span><strong>01</strong> esperienza seguita</span>
                        <span><strong>PA</strong> Palermo</span>
                    </div>
                </div>
                <div class="be-pilates__hero-art" aria-label="Cinque postazioni Pilates Reformer">
                    <div class="be-pilates__hero-art-inner">
                        <span class="be-pilates__number">05</span>
                        <div class="be-pilates__line"></div>
                        <small>PILATES REFORMER</small>
                        <strong>Spazio.<br>Controllo.<br>Qualità.</strong>
                    </div>
                </div>
            </div>
        </section>

        <section class="be-pilates__statement" id="be-pilates-experience">
            <div class="be-pilates__shell be-pilates__statement-layout">
                <div>
                    <p class="be-pilates__eyebrow">IL VALORE DEI PICCOLI GRUPPI</p>
                    <h2>Ogni movimento<br>merita attenzione.</h2>
                </div>
                <div class="be-pilates__statement-copy">
                    <p>Il numero contenuto di partecipanti permette all’insegnante di osservare, correggere e accompagnare il lavoro con maggiore continuità.</p>
                    <a href="<?php echo esc_url($request_url); ?>">Parla con il team Pilates <span>→</span></a>
                </div>
            </div>
        </section>

        <section class="be-pilates__principles">
            <div class="be-pilates__shell">
                <div class="be-pilates__principles-grid">
                    <article><span>01</span><div><h3>Piccoli gruppi</h3><p>Cinque postazioni per preservare ordine, spazio e qualità della lezione.</p></div></article>
                    <article><span>02</span><div><h3>Attenzione personale</h3><p>Indicazioni e correzioni più presenti durante ogni fase del percorso.</p></div></article>
                    <article><span>03</span><div><h3>Progressione</h3><p>Un lavoro costruito con precisione, continuità e rispetto del proprio livello.</p></div></article>
                </div>
            </div>
        </section>

        <section class="be-pilates__experience">
            <div class="be-pilates__shell be-pilates__experience-layout">
                <div class="be-pilates__experience-art" aria-hidden="true">
                    <span>BODY<br>ENERGY</span><b>REFORMER</b>
                </div>
                <div class="be-pilates__experience-copy">
                    <p class="be-pilates__eyebrow">UN’ESPERIENZA BODY ENERGY</p>
                    <h2>Forza, controllo e consapevolezza.</h2>
                    <p>Il Pilates Reformer accompagna il movimento attraverso un lavoro controllato e progressivo. La nuova sala nasce per chi cerca un ambiente raccolto e un’esperienza realmente seguita.</p>
                    <ul>
                        <li>Controllo e precisione del movimento</li>
                        <li>Lavoro su forza e mobilità</li>
                        <li>Attenzione alla continuità</li>
                    </ul>
                </div>
            </div>
        </section>

        <section class="be-pilates__audience">
            <div class="be-pilates__shell">
                <p class="be-pilates__eyebrow">PER CHI CERCA QUALCOSA DI PIÙ PERSONALE</p>
                <div class="be-pilates__audience-layout">
                    <h2>Il tuo spazio.<br>Il tuo ritmo.</h2>
                    <div class="be-pilates__audience-list">
                        <p><span>01</span> Per chi vuole migliorare controllo e consapevolezza.</p>
                        <p><span>02</span> Per chi preferisce gruppi raccolti e ordinati.</p>
                        <p><span>03</span> Per chi cerca un percorso seguito con continuità.</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="be-pilates__process">
            <div class="be-pilates__shell">
                <div class="be-pilates__section-title">
                    <p class="be-pilates__eyebrow">UN CONTATTO SEMPLICE E PERSONALE</p>
                    <h2>Da qui, pensiamo noi al prossimo passo.</h2>
                </div>
                <div class="be-pilates__process-grid">
                    <article><span>01</span><h3>Lascia la richiesta</h3><p>Inserisci le informazioni essenziali nel modulo dedicato.</p></article>
                    <article><span>02</span><h3>Scegli il contatto</h3><p>Indica se preferisci una chiamata oppure WhatsApp.</p></article>
                    <article><span>03</span><h3>Parla con noi</h3><p>Il team Body Energy ti ricontatterà nella fascia indicata.</p></article>
                </div>
            </div>
        </section>

        <section class="be-pilates__final">
            <div class="be-pilates__shell be-pilates__final-layout">
                <div>
                    <p class="be-pilates__eyebrow">PILATES REFORMER · BODY ENERGY</p>
                    <h2>Cinque postazioni.<br><span>Un’esperienza più personale.</span></h2>
                </div>
                <div>
                    <p>Raccontaci cosa stai cercando. Ti ricontatteremo nel modo che preferisci.</p>
                    <a class="be-pilates__button be-pilates__button--primary" href="<?php echo esc_url($request_url); ?>">Richiedi informazioni <b>→</b></a>
                </div>
            </div>
        </section>
    </main>

    <style>
    .be-pilates{--bg:#070709;--panel:#111114;--line:rgba(255,255,255,.11);--text:#f7f7f8;--muted:#a7a7b0;--red:#e3262e;--bright:#ff3b43;width:100%;overflow:hidden;background:var(--bg);color:var(--text);font-family:Inter,ui-sans-serif,system-ui,-apple-system,BlinkMacSystemFont,"Segoe UI",sans-serif}.be-pilates *,.be-pilates *:before,.be-pilates *:after{box-sizing:border-box}.be-pilates h1,.be-pilates h2,.be-pilates h3,.be-pilates p{margin-top:0}.be-pilates a{text-decoration:none}.be-pilates__shell{width:min(1180px,calc(100% - 40px));margin:auto}.be-pilates__eyebrow{margin-bottom:19px;color:var(--bright);font-size:11px;font-weight:850;letter-spacing:.19em}.be-pilates__hero{position:relative;display:flex;min-height:calc(100vh - 120px);align-items:center;overflow:hidden;padding:90px 0;background:radial-gradient(circle at 82% 18%,rgba(227,38,46,.18),transparent 29%),linear-gradient(135deg,rgba(227,38,46,.08),transparent 42%),#08080a}.be-pilates__mesh{position:absolute;inset:0;opacity:.19;background-image:linear-gradient(rgba(255,255,255,.04) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.04) 1px,transparent 1px);background-size:56px 56px;mask-image:linear-gradient(to bottom,#000,transparent 92%)}.be-pilates__hero-layout{position:relative;z-index:1;display:grid;grid-template-columns:1.05fr .95fr;gap:78px;align-items:center}.be-pilates__hero h1{margin-bottom:27px;color:#fff;font-size:clamp(58px,6.9vw,96px);line-height:.94;letter-spacing:-.062em}.be-pilates__hero h1 span,.be-pilates__final h2 span{color:var(--bright)}.be-pilates__lead{max-width:650px;margin-bottom:34px;color:var(--muted);font-size:19px;line-height:1.68}.be-pilates__actions{display:flex;align-items:center;gap:25px}.be-pilates__button{display:inline-flex;min-height:55px;align-items:center;justify-content:space-between;gap:35px;padding:0 21px;border-radius:9px;font-size:13px;font-weight:850}.be-pilates__button--primary{background:var(--red);color:#fff;box-shadow:0 18px 42px rgba(227,38,46,.23)}.be-pilates__text-link{padding-bottom:4px;border-bottom:1px solid rgba(255,255,255,.25);color:#d7d7dc;font-size:13px;font-weight:750}.be-pilates__signature{display:flex;gap:0;margin-top:45px;border-top:1px solid var(--line);border-bottom:1px solid var(--line)}.be-pilates__signature span{padding:17px 23px 17px 0;color:#777781;font-size:10px;font-weight:750;text-transform:uppercase;letter-spacing:.08em}.be-pilates__signature span+span{padding-left:23px;border-left:1px solid var(--line)}.be-pilates__signature strong{margin-right:6px;color:#fff;font-size:15px}.be-pilates__hero-art{display:flex;justify-content:flex-end}.be-pilates__hero-art-inner{position:relative;width:min(100%,460px);aspect-ratio:4/5;overflow:hidden;padding:35px;border:1px solid rgba(255,255,255,.13);border-radius:26px;background:linear-gradient(145deg,rgba(255,255,255,.08),transparent 42%),linear-gradient(180deg,rgba(227,38,46,.2),transparent 52%),#121216;box-shadow:0 38px 100px rgba(0,0,0,.5)}.be-pilates__number{position:absolute;right:25px;top:18px;color:rgba(255,255,255,.065);font-size:125px;font-weight:900;line-height:1}.be-pilates__line{position:absolute;left:12%;right:12%;top:48%;height:2px;transform:rotate(-17deg);background:linear-gradient(90deg,transparent,var(--bright),transparent);box-shadow:0 0 30px var(--red)}.be-pilates__hero-art small,.be-pilates__hero-art strong{position:absolute;left:35px;z-index:2}.be-pilates__hero-art small{bottom:150px;color:var(--bright);font-weight:850;letter-spacing:.16em}.be-pilates__hero-art strong{bottom:38px;color:#fff;font-size:41px;line-height:1.03;letter-spacing:-.04em}.be-pilates__statement{padding:120px 0;background:#0b0b0e}.be-pilates__statement-layout,.be-pilates__experience-layout,.be-pilates__audience-layout,.be-pilates__final-layout{display:grid;grid-template-columns:1.05fr .95fr;gap:90px;align-items:center}.be-pilates h2{margin-bottom:0;color:#fff;font-size:clamp(42px,5vw,67px);line-height:1.02;letter-spacing:-.052em}.be-pilates__statement-copy{padding-left:34px;border-left:2px solid var(--red)}.be-pilates__statement-copy p,.be-pilates__experience-copy>p,.be-pilates__final p{color:var(--muted);font-size:17px;line-height:1.78}.be-pilates__statement-copy a{display:inline-flex;gap:30px;margin-top:17px;color:#fff;font-size:13px;font-weight:800}.be-pilates__principles{padding:0 0 120px;background:#0b0b0e}.be-pilates__principles-grid{border-top:1px solid var(--line)}.be-pilates__principles article{display:grid;grid-template-columns:120px 1fr;gap:20px;padding:34px 0;border-bottom:1px solid var(--line)}.be-pilates__principles article>span{color:var(--bright);font-size:11px;font-weight:850}.be-pilates__principles h3{margin-bottom:9px;color:#fff;font-size:25px}.be-pilates__principles p{max-width:630px;margin-bottom:0;color:var(--muted);line-height:1.65}.be-pilates__experience{padding:125px 0;background:#111114}.be-pilates__experience-art{position:relative;display:flex;min-height:520px;flex-direction:column;justify-content:flex-end;overflow:hidden;padding:42px;border:1px solid var(--line);border-radius:25px;background:radial-gradient(circle at 80% 20%,rgba(227,38,46,.23),transparent 31%),linear-gradient(145deg,rgba(255,255,255,.07),transparent 45%),#0a0a0d}.be-pilates__experience-art:after{content:"05";position:absolute;right:20px;top:0;color:rgba(255,255,255,.045);font-size:190px;font-weight:900}.be-pilates__experience-art span{color:#fff;font-size:55px;font-weight:900;line-height:.82;letter-spacing:-.06em}.be-pilates__experience-art b{margin-top:17px;color:var(--bright);font-size:12px;letter-spacing:.22em}.be-pilates__experience-copy h2{margin-bottom:27px}.be-pilates__experience-copy ul{margin:31px 0 0;padding:0;list-style:none}.be-pilates__experience-copy li{padding:17px 0;border-top:1px solid var(--line);color:#dedee3;font-size:14px}.be-pilates__experience-copy li:last-child{border-bottom:1px solid var(--line)}.be-pilates__audience{padding:125px 0;background:#08080a}.be-pilates__audience-list p{display:flex;gap:22px;margin:0;padding:22px 0;border-top:1px solid var(--line);color:#c9c9cf;line-height:1.6}.be-pilates__audience-list p:last-child{border-bottom:1px solid var(--line)}.be-pilates__audience-list span{color:var(--bright);font-size:11px;font-weight:850}.be-pilates__process{padding:125px 0;background:#0d0d10}.be-pilates__section-title{max-width:780px;margin-bottom:57px}.be-pilates__process-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:15px}.be-pilates__process article{min-height:265px;padding:28px;border:1px solid var(--line);border-radius:17px;background:#121216}.be-pilates__process article>span{display:block;margin-bottom:75px;color:var(--bright);font-size:11px;font-weight:850}.be-pilates__process h3{margin-bottom:12px;color:#fff;font-size:22px}.be-pilates__process article p{margin:0;color:var(--muted);line-height:1.62}.be-pilates__final{padding:125px 0;background:radial-gradient(circle at 100% 0,rgba(227,38,46,.2),transparent 33%),#111114}.be-pilates__final h2{margin-bottom:0}.be-pilates__final .be-pilates__button{margin-top:25px}@media(max-width:900px){.be-pilates__hero{min-height:auto}.be-pilates__hero-layout,.be-pilates__statement-layout,.be-pilates__experience-layout,.be-pilates__audience-layout,.be-pilates__final-layout{grid-template-columns:1fr}.be-pilates__hero-art{justify-content:flex-start}.be-pilates__statement-copy{padding:28px 0 0;border-left:0;border-top:2px solid var(--red)}.be-pilates__process-grid{grid-template-columns:1fr}}@media(max-width:600px){.be-pilates__shell{width:min(100% - 28px,1180px)}.be-pilates__hero,.be-pilates__statement,.be-pilates__experience,.be-pilates__audience,.be-pilates__process,.be-pilates__final{padding:75px 0}.be-pilates__hero h1{font-size:clamp(52px,16vw,72px)}.be-pilates__actions{align-items:stretch;flex-direction:column}.be-pilates__signature{flex-direction:column}.be-pilates__signature span+span{padding-left:0;border-left:0;border-top:1px solid var(--line)}.be-pilates__hero-art-inner{aspect-ratio:4/5}.be-pilates__principles{padding-bottom:75px}.be-pilates__principles article{grid-template-columns:45px 1fr}.be-pilates__experience-art{min-height:410px}.be-pilates__process article{min-height:220px}.be-pilates__process article>span{margin-bottom:45px}}
    </style>
    <?php
    return (string) ob_get_clean();
}
add_shortcode('bodyenergy_pilates_landing', 'bodyenergy_render_pilates_landing');
