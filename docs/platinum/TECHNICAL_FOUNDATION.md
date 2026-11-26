# Platinum 2.0 — fondazioni tecniche, SEO e QA

Baseline verificata: main 05bc46f30c903f5979cdc6f8de4432387845f425, plugin 0.11.3. L'audit statico non equivale a collaudo runtime. Nessuna correzione implementata in questa fase.

## Backlog prioritario (prima del restyling)
| ID | Evidenza | Azione proposta e verifica |
|---|---|---|
| BE-001 | includes/pilates-request-flow.php:40–87 esegue admin_init senza guardia versione e forza draft | Migrazione idempotente, preservare stati, gestire errori e testare accessi admin ripetuti. |
| BE-002 | Modulo Pilates ha onsubmit=false e pulsante type=button; non gestisce invio | Tenere richiesta/grazie non pubbliche e indirizzare CTA verso Contatti o canale approvato funzionante; niente attivazione form senza autorizzazione. |
| BE-003 | includes/site-navigation-polish.php normalizza path senza origine e scansiona tutti a/button | Limitare a navigazione interna esplicita, preservare mailto/tel/WhatsApp/URL esterni/ancore/parametri, testare stati draft e publish. |
| BE-004 | Home is_page(113), DOM marker Elementor, MutationObserver | Risoluzione pagina stabile, template server-side, compatibilità con pagina approvata e migrazione; non cambiare hero senza review. |
| BE-005 | Video admin salva option ma frontend usa GUID fisso; helper di validazione da verificare | Verificare esistenza helper e collegare selettore alla sorgente con fallback, autorizzazioni e test save/remove. |
| BE-006 | Video autoplay loop controlli nascosti, iframe eager | Poster, pausa, reduced motion, budget media e misurazione. |
| BE-007 | Staging inferito anche da hostname; bootstrap vincolato allo staging, palestra forza draft al cambio versione | Identificare ambiente effettivo, migrazione esplicita e preservare stati; verificare associazione dominio e rollback. |
| BE-008/009 | Override CSS/JS, layout/footer duplicati | Inventariare tema effettivo e repository separato prima di scegliere child/custom theme; estrarre componenti condivisi progressivamente. |
| BE-010 | Master Plan e checklist storici non aggiornati | Applicare AGENTS.md e documenti approvati, conservare storico. |
| BE-011 | main non protetto nel dato esaminato; nessun workflow nel tree baseline | Proporre CI lint PHP/JS, smoke test e protezione branch; autorizzare separatamente configurazione. |
| BE-012 | Blocco abbonamenti frontend + draft su admin_init | Verificare stato non pubblico, REST, ricerca, sitemap, cache e link; nessuna esposizione dei prezzi. |

Priorità: BE-001/002/003/012, poi BE-004/005/006/007 e fondazioni componenti. PR piccole, criteri e test specifici; nessuna correzione indiscriminata. Non assumere che il tema separato o i dati WordPress siano nel repository plugin.

## Architettura
Tema: presentazione, template, header/footer, design token e layout. WordPress CMS: contenuti e media aggiornabili; centralizzare dati sede, orari, canali e route. Plugin: funzionalità operative e policy, senza dipendenza dalla struttura HTML del tema. BodyGate separato, integrazione futura server-to-server autenticata solo dopo autorizzazione. Vietati segreti frontend e accesso diretto a Supabase.
Mantenere il tema padre intatto; niente sostituzione del tema attivo senza verifica compatibilità, piano migrazione e approvazione.

## SEO / privacy
Titoli e description specifici, H1 coerente, canonical, sitemap, Open Graph e dati strutturati solo verificati. Evitare duplicazioni con plugin SEO esistenti; mantenere staging non indicizzabile e verificare il dominio definitivo. Contatti e NAP coerenti con profilo ufficiale. Privacy/cookie, dati ASD e servizi terzi da censire e validare; niente tracking o moduli attivi senza approvazione. Video embed e altri servizi terzi da verificare anche sul piano privacy.

## QA e release gate
- Statico: sintassi PHP/JS, escaping, permessi/nonce, diff, dipendenze, stato migrazioni. Non dichiarare test eseguiti se non disponibili.
- Funzionale: menu, CTA, contatti, WhatsApp, tel/mail, mappe, 404, URL legacy, pagine draft, abbonamenti non pubblici, link esterni e ancore. Area soci solo se verificata. Verificare come visitatore anonimo e amministratore.
- Browser: Safari/iOS, Chrome/Android, Chrome, Firefox, Edge; viewport indicati nel Design System. Tastiera, focus, screen reader, zoom, contrasto, reduced motion.
- Performance: obiettivi CWV p75 LCP <=2.5s, INP <=200ms, CLS <=0.1. Misure lab separate da dati reali; misurare peso media, errori e layout shift. Nessun punteggio inventato.
- Release: inventario tema/plugin e contenuti dal back office, backup DB/media/config, staging effettivo, verifica link e privacy, approvazione grafica e funzionale, dominio e rollback verificati. Nessun deploy automatico o pubblicazione senza autorizzazione.
- Ogni PR riporta perimetro, evidenze, rischi, verifiche eseguite/non eseguite, istruzioni deploy quando applicabili e rollback. Non cambiare versione plugin per sole modifiche documentali.
