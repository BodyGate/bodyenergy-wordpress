# Body Energy WordPress — istruzioni permanenti di progetto

## Autorità e perimetro
Il sito è il canale istituzionale di Body Energy ASD a Palermo; il gestionale BodyGate è un sistema separato. Le decisioni esplicite del titolare prevalgono sulle proposte grafiche e sul Master Plan storico. Questo documento e docs/platinum/ costituiscono la proposta di protocollo Platinum 2.0 fino all'approvazione della relativa PR. Non autorizzano da soli modifiche alla produzione.

## Vincoli non negoziabili
- Niente listini, prezzi, formule o promozioni pubbliche; nessuna prova gratuita senza approvazione. Nessun Powerlifting. Fitness, Bodybuilding, corsi fitness e Pilates Reformer (cinque postazioni) sono le categorie attualmente concordate; verificare ogni dettaglio aggiuntivo.
- Fotografie e video autentici e autorizzati; mai presentare immagini sintetiche o stock come ambienti reali. Niente recensioni, numeri, qualifiche o orari inventati.
- Nessuna pubblicazione, attivazione di moduli, tracking, automazioni, API o modifica del dominio senza autorizzazione esplicita.
- Non toccare BodyGate, Bridge, DNake, KT02.3, TK-01, Mobile Pass, controllo accessi o relative pipeline. Nessun accesso diretto a Supabase dal sito; eventuale futura integrazione solo server-to-server autenticata e approvata.
- Non inserire credenziali o dati dei soci in repository, log o frontend.

## Workflow
1. Leggere i documenti Platinum e il codice effettivo, verificare main e ambiente. Distinguere evidenze da ipotesi.
2. Definire perimetro, criteri di accettazione e rollback; una modifica coerente per branch e PR. Non introdurre dipendenze superflue.
3. Preservare la baseline funzionante e gli stati pubblicati; evitare modifiche distruttive e correzioni globali indiscriminate. Non modificare il tema padre.
4. Verificare diff, controlli statici, test funzionali e screenshot desktop/mobile pertinenti; dichiarare esattamente i test non eseguiti. Non confondere merge, deploy e pubblicazione.
5. Review e approvazione prima del merge secondo il perimetro autorizzato. Deploy solo nel target corretto (WordPress per il plugin, Theme per il tema quando verificato), con verifica successiva. Nessun rilascio pubblico implicito.

## Documenti
- [Direzione artistica e regole editoriali](docs/platinum/ART_DIRECTION.md)
- [Design system e standard frontend](docs/platinum/DESIGN_SYSTEM.md)
- [Fondazioni tecniche e collaudo](docs/platinum/TECHNICAL_FOUNDATION.md)

Il Master Plan originale resta uno storico; le decisioni più recenti sopra riportate prevalgono sui punti incompatibili.
