---
title: "Cestino per Dolibarr - recupera i file eliminati per errore"
short: "Un cestino per Dolibarr: i file eliminati in qualsiasi area documenti vengono conservati e possono essere ripristinati."
start: "Il modulo compare come **DoliTrashCan**; il tempo di conservazione si imposta nella sua pagina di configurazione."
---
**Cestino per Dolibarr** evita di perdere documenti. Ogni file eliminato da un'area documenti (terze parti, proposte, ordini, fatture, progetti...) viene spostato in un cestino invece di sparire, con chi lo ha eliminato, quando e da quale scheda.

## Funzionalità

- **Automatico**: nulla da configurare sugli oggetti, i consueti pulsanti di eliminazione inviano il file al cestino.
- **Pagina Cestino** nel menu ECM: file eliminati con tipo, utente, data e collegamento alla scheda di origine.
- **Ripristino** di un file nella posizione originale con un clic (l'indice ECM viene aggiornato). Un file che esiste di nuovo non viene mai sovrascritto.
- **Eliminazione definitiva** con conferma, secondo i permessi dell'utente (lettura, ripristino, eliminazione).
- **Pulizia automatica**: i file più vecchi del numero di giorni scelto (365 per impostazione predefinita, 0 per conservarli sempre) vengono eliminati definitivamente.
- **Solo veri documenti**: i file temporanei e le miniature non finiscono nel cestino.
- **Sicuro**: se un file non può essere messo nel cestino, non viene eliminato.
