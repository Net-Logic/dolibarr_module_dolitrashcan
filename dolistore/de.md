---
title: "Papierkorb für Dolibarr - versehentlich gelöschte Dateien wiederherstellen"
short: "Ein Papierkorb für Dolibarr: in Dokumentbereichen gelöschte Dateien werden aufbewahrt und können wiederhergestellt werden."
start: "Das Modul heißt in der Modulliste **DoliTrashCan**; die Aufbewahrungsdauer stellen Sie auf seiner Einstellungsseite ein."
---
**Papierkorb für Dolibarr** schützt vor Dokumentenverlust. Jede in einem Dokumentbereich gelöschte Datei (Geschäftspartner, Angebote, Bestellungen, Rechnungen, Projekte...) landet in einem Papierkorb, statt zu verschwinden, mit Angabe, wer sie wann und von welchem Datensatz aus gelöscht hat.

## Funktionen

- **Automatisch**: An den Objekten ist nichts einzustellen, die üblichen Löschen-Schaltflächen schicken die Datei in den Papierkorb.
- **Papierkorb-Seite** im ECM-Menü: gelöschte Dateien mit Typ, Benutzer, Datum und Link zum ursprünglichen Datensatz.
- **Wiederherstellung** an den ursprünglichen Ort mit einem Klick (der ECM-Index wird aktualisiert). Eine Datei, die erneut existiert, wird nie überschrieben.
- **Endgültig löschen** mit Bestätigung, gemäß den Berechtigungen des Benutzers (Lesen, Wiederherstellen, Löschen).
- **Automatische Bereinigung**: Dateien, die älter sind als die gewählte Anzahl von Tagen (standardmäßig 365, 0 behält sie dauerhaft), werden endgültig entfernt.
- **Nur echte Dokumente**: temporäre Dateien und Vorschaubilder gelangen nicht in den Papierkorb.
- **Sicher**: Kann eine Datei nicht in den Papierkorb gelegt werden, wird sie nicht gelöscht.
