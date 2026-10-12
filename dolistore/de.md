---
title: "Papierkorb für Dolibarr - versehentlich gelöschte Dateien wiederherstellen"
short: "Ein Papierkorb für Dolibarr: In beliebigen Dokumentbereichen gelöschte Dateien werden aufbewahrt und können wiederhergestellt oder endgültig gelöscht werden."
---
**Papierkorb für Dolibarr** schützt vor Dokumentenverlust. Jede in einem Dokumentbereich gelöschte Datei (Geschäftspartner, Angebote, Bestellungen, Rechnungen, Projekte...) landet in einem Papierkorb, statt zu verschwinden, mit Angabe, wer sie wann und von welchem Datensatz aus gelöscht hat.

## Funktionen

- **Automatisch**: An den Objekten ist nichts einzustellen, die üblichen Löschen-Schaltflächen schicken die Datei in den Papierkorb.
- **Papierkorb-Seite** im ECM-Menü: gelöschte Dateien mit Typ, Benutzer, Datum und Link zum ursprünglichen Datensatz.
- **Wiederherstellung** an den ursprünglichen Ort mit einem Klick (der ECM-Index wird aktualisiert). Eine Datei, die erneut existiert, wird nie überschrieben.
- **Endgültig löschen** mit Bestätigung, gemäß den Berechtigungen des Benutzers (Lesen, Wiederherstellen, Löschen).
- **Automatische Bereinigung**: Dateien, die älter sind als die gewählte Anzahl von Tagen (standardmäßig 365, 0 behält sie dauerhaft), werden endgültig entfernt.
- Temporäre Dateien und Vorschaubilder werden nicht aufbewahrt: nur echte Dokumente gelangen in den Papierkorb.
- Sicher: Kann eine Datei nicht in den Papierkorb gelegt werden, wird sie nicht gelöscht.

## Voraussetzungen

Dolibarr 20 bis 24, PHP 7.4 bis 8.5, ECM-Modul aktiviert. Sprachen: Englisch, Französisch, Deutsch, Italienisch und Spanisch.

Installieren Sie die ZIP-Datei unter **Start → Einstellungen → Module → Externes Modul installieren**, aktivieren Sie **DoliTrashCan** und vergeben Sie die Berechtigungen an Ihre Benutzer. Die Aufbewahrungsdauer stellen Sie auf der Einstellungsseite des Moduls ein.
