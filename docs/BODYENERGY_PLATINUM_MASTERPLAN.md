# Body Energy Platinum Website — Master Plan

## 1. Visione

Realizzare il nuovo sito ufficiale Body Energy ASD come piattaforma premium, locale e orientata alla conversione, coordinata con BodyGate ma tecnicamente separata dal gestionale.

Principio guida:

- il **tema** gestisce identità visiva, layout, navigazione, pagine e responsive design;
- il **plugin Body Energy Integration** gestisce lead, form, API, BodyGate, prenotazioni e funzioni operative;
- lo **staging WordPress.com** resta l'ambiente di sviluppo e approvazione;
- la produzione non viene modificata finché l'intero progetto non supera controlli funzionali, mobile, SEO, privacy e prestazioni.

## 2. Posizionamento

Body Energy deve essere percepita come:

- palestra storica e concreta di Palermo;
- centro con cultura reale dell'allenamento;
- ambiente professionale ma accessibile;
- struttura con attenzione personale;
- realtà innovativa grazie a BodyGate;
- nuovo riferimento locale per Pilates Reformer a piccoli gruppi.

## 3. Direzione creativa

### Identità

- nero profondo;
- rosso Body Energy;
- bianco ad alto contrasto;
- grigi tecnici;
- fotografia autentica della struttura;
- titoli forti, compatti e leggibili;
- animazioni leggere e funzionali;
- nessun effetto da tema WordPress generico.

### Tono

- autorevole;
- diretto;
- umano;
- preciso;
- locale;
- orientato al risultato.

## 4. Architettura informativa

### Navigazione principale

1. Home
2. La palestra
3. Servizi
4. Pilates Reformer
5. Abbonamenti
6. Orari e contatti
7. Area soci

CTA persistente:

- Richiedi una prova

CTA secondaria:

- Accedi a BodyGate

## 5. Pagine

### Home

Sezioni previste:

1. Hero con foto/video reale e doppia CTA
2. Numeri Body Energy
3. Aree e servizi
4. Perché scegliere Body Energy
5. Focus Pilates Reformer
6. Abbonamenti e promozioni
7. BodyGate e servizi digitali
8. Risultati, testimonianze e recensioni
9. FAQ
10. Contatti, mappa e orari

### La palestra

- identità e storia;
- filosofia;
- struttura;
- attrezzature;
- staff;
- galleria fotografica;
- valori e metodo.

### Servizi

- sala pesi;
- cardio e fitness;
- personal training;
- preparazione atletica;
- bodybuilding e agonismo;
- Pilates Reformer;
- corsi futuri.

### Pilates Reformer

- landing premium già prototipata;
- quattro postazioni;
- benefici;
- insegnanti;
- palinsesto;
- prova gratuita;
- lista prioritaria;
- promo lancio;
- futura prenotazione BodyGate.

### Abbonamenti

- formule palestra;
- quota associativa;
- family pack;
- promozioni;
- modalità di pagamento;
- FAQ economiche;
- richiesta informazioni.

### Orari e contatti

- orari aggiornabili;
- telefono;
- WhatsApp;
- email;
- indirizzo;
- mappa;
- indicazioni;
- modulo contatti;
- parcheggio e accessibilità.

### Area soci

Fase progressiva:

1. link sicuro a BodyGate;
2. stato abbonamento;
3. ricevute;
4. Mobile Pass;
5. prenotazioni Pilates;
6. notifiche;
7. rinnovi e pagamenti.

### Shop

Il sito Body Energy non duplica l'e-commerce. Prevede una sezione editoriale e commerciale con collegamento a Future Fitness Food.

## 6. Sistema componenti

Componenti globali:

- header desktop;
- header mobile;
- menu overlay;
- footer;
- hero;
- card servizio;
- card promozione;
- card testimonial;
- card FAQ;
- card orari;
- CTA bar;
- form lead;
- badge stato;
- breadcrumb;
- banner privacy.

## 7. Integrazione BodyGate

### Fase 1 — Lead

- form prova gratuita;
- form Pilates;
- provenienza lead;
- consenso privacy;
- stato commerciale;
- sincronizzazione con BodyGate quando l'endpoint sarà online.

### Fase 2 — Prenotazioni

- palinsesto;
- disponibilità;
- capacità massima;
- lista d'attesa;
- annullamento;
- notifiche.

### Fase 3 — Area personale

- autenticazione;
- profilo;
- abbonamento;
- ricevute;
- prenotazioni;
- Mobile Pass.

## 8. Requisiti tecnici

- tema custom separato dal plugin;
- nessuna modifica diretta al tema Fitness Elementor;
- nessuna dipendenza obbligatoria da Elementor per i layout principali;
- accessibilità semantica;
- responsive mobile-first;
- immagini ottimizzate;
- CSS e JavaScript minimi;
- compatibilità WordPress.com Business;
- HTTPS;
- staging prima della produzione;
- nessuna chiave BodyGate o Supabase nel frontend;
- API protette lato server.

## 9. SEO locale

Pagine e contenuti mirati a:

- palestra Palermo;
- sala pesi Palermo;
- personal trainer Palermo;
- Pilates Reformer Palermo;
- palestra Viale Amedeo D'Aosta;
- bodybuilding Palermo.

Elementi:

- title e meta description dedicati;
- dati strutturati LocalBusiness / SportsActivityLocation;
- FAQ schema dove corretto;
- Open Graph;
- sitemap;
- immagini con alt text;
- pagine legali indicizzate correttamente.

## 10. Qualità e collaudo

### Design QA

- desktop;
- tablet;
- smartphone;
- contrasto;
- font;
- spaziature;
- coerenza CTA;
- assenza di overflow.

### Functional QA

- form;
- email;
- WhatsApp;
- mappa;
- menu;
- link BodyGate;
- WooCommerce residuo;
- cookie e privacy.

### Performance QA

- peso immagini;
- caricamento font;
- script terzi;
- Core Web Vitals;
- cache;
- test anonimo e amministratore.

### Release gate

La produzione viene aggiornata solo dopo:

1. approvazione grafica;
2. approvazione testi;
3. test mobile;
4. test form;
5. controllo legale/privacy;
6. backup;
7. piano di rollback.

## 11. Roadmap

### Sprint 0 — Fondazioni

- repository tema;
- struttura theme;
- design tokens;
- header/footer;
- ambiente staging.

### Sprint 1 — Home

- hero;
- numeri;
- servizi;
- Pilates;
- BodyGate;
- CTA e contatti.

### Sprint 2 — Pagine istituzionali

- palestra;
- servizi;
- abbonamenti;
- contatti;
- FAQ.

### Sprint 3 — Pilates

- migrazione landing nel sistema tema;
- insegnanti;
- palinsesto;
- lead.

### Sprint 4 — Conversione e BodyGate

- form strutturati;
- tracking sorgente;
- API;
- dashboard lead.

### Sprint 5 — SEO, performance e release

- SEO locale;
- accessibilità;
- ottimizzazione;
- test;
- passaggio in produzione.

## 12. Regole permanenti

- non toccare BodyGate access control, Bridge, DNake, KT02 o tornello;
- non salvare segreti nel repository;
- non modificare produzione durante lo sviluppo;
- non duplicare dati clienti tra WordPress e BodyGate senza una logica approvata;
- mantenere la distribuzione automatica disattivata fino alla stabilizzazione;
- ogni modifica passa da staging e verifica visiva/funzionale.
