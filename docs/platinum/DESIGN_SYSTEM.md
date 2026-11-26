# Platinum 2.0 — design system e frontend

Stato: proposta da prototipare. Valori numerici iniziali, non token già distribuiti.

## Fondazioni
Palette candidata: nero #08080A, carbon #161619, graphite #303036, avorio #F1EEE7, rosso #E5232E. Verificare il colore ufficiale del logo e i contrasti delle coppie reali. Il rosso non è automaticamente adatto come testo piccolo o sfondo con testo bianco; scegliere una variante accessibile quando necessario.
Tipografia: selezionare una famiglia display e una famiglia testo solo se necessario, con licenze e fallback verificati; preferire pochi pesi. Corpo indicativo 16–18 px, line-height 1.5–1.65; titoli fluidi con clamp e limiti su mobile. Non forzare interruzioni di riga che si rompono al variare del viewport.
Spaziatura candidata (px): 8, 16, 24, 32, 48, 64, 96, 128. Margini e gutter fluidi. Contenitore editoriale con max-width condivisa; larghezza di lettura orientativa 60–75 caratteri. Bordi discreti, raggi piccoli e coerenti; niente ombre se non funzionali.

## Componenti
Header desktop e mobile con navigazione semantica, indicazione pagina attiva, focus, menu apribile e chiudibile da tastiera; CTA primaria unica. Footer globale con dati confermati, link legali e social verificati. Hero con titolo, descrizione concreta, CTA e media indipendenti. Sezioni editoriali, griglie fotografiche, elenco servizi, orari e contatti. CTA primaria e secondaria coerenti in tutti gli stati (hover/focus/disabled). Moduli solo se funzionanti e approvati, con label, messaggi d'errore e conferma effettiva. 404 dedicata.

## Frontend
- HTML semantico generato lato server per i contenuti essenziali; JavaScript come progressive enhancement. Non generare elementi fondamentali cercando testi Elementor nel DOM.
- Layout CSS Grid/Flexbox, design token in custom properties condivise; CSS per componenti con specificità bassa e scope esplicito. Non usare !important come soluzione strutturale.
- Usare WordPress enqueue e caricare asset solo dove necessari. Nessun framework UI o libreria motion salvo motivazione, review e misure.
- Immagini responsive con dimensioni esplicite e formati ottimizzati; lazy loading solo sotto la piega. Per hero LCP evitare ritardi; video con poster, fallback e controllo pausa appropriato, evitare autoplay su connessioni o preferenze incompatibili.
- Motion opzionale: transizioni 150–250 ms come punto di partenza, niente blocco del contenuto; rispettare prefers-reduced-motion e focus da tastiera.
- Progettare a 320, 375, 430, 768, 1024, 1440 e 1920 px; verificare zoom, contenuti lunghi, orientamento e assenza di overflow. Target interattivi preferibilmente 44x44 px; verificare WCAG 2.2 AA e contrasto (4.5:1 testo normale, 3:1 testo grande e componenti applicabili).

## Definition of done visuale
Prototipo desktop/mobile approvato; immagini autentiche; titoli leggibili e contenuti concreti; stati interattivi completi; nessun elemento generico ereditato; nessuna regressione sulle pagine approvate. Screenshot e risultati dei test allegati alla PR. Non dichiarare conformità WCAG senza verifica.
