---
title: "Cestino per Dolibarr - recupera i file eliminati per errore"
short: "Un cestino per Dolibarr: i file eliminati in qualsiasi area documenti vengono conservati e possono essere ripristinati o eliminati definitivamente."
---
**Cestino per Dolibarr** evita di perdere documenti. Ogni file eliminato da un'area documenti (terze parti, proposte, ordini, fatture, progetti...) viene spostato in un cestino invece di sparire, con chi lo ha eliminato, quando e da quale scheda.

## Funzionalità

- **Automatico**: nulla da configurare sugli oggetti, i consueti pulsanti di eliminazione inviano il file al cestino.
- **Pagina Cestino** nel menu ECM: file eliminati con tipo, utente, data e collegamento alla scheda di origine.
- **Ripristino** di un file nella posizione originale con un clic (l'indice ECM viene aggiornato). Un file che esiste di nuovo non viene mai sovrascritto.
- **Eliminazione definitiva** con conferma, secondo i permessi dell'utente (lettura, ripristino, eliminazione).
- **Pulizia automatica**: i file più vecchi del numero di giorni scelto (365 per impostazione predefinita, 0 per conservarli sempre) vengono eliminati definitivamente.
- I file temporanei e le miniature non vengono conservati: nel cestino finiscono solo i veri documenti.
- Sicuro: se un file non può essere messo nel cestino, non viene eliminato.

## Requisiti

Dolibarr da 20 a 24, PHP da 7.4 a 8.5, modulo ECM attivo. Lingue: inglese, francese, tedesco, italiano e spagnolo.

Installa lo zip da **Home → Impostazioni → Moduli → Installa un modulo esterno**, attiva **DoliTrashCan** e assegna i permessi agli utenti. Il tempo di conservazione si imposta nella pagina di configurazione del modulo.
