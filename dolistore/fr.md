---
title: "Corbeille pour Dolibarr - récupérez les fichiers supprimés par erreur"
short: "Une corbeille pour Dolibarr : les fichiers supprimés de toute zone de documents sont conservés et peuvent être restaurés."
start: "Le module apparaît sous le nom **DoliTrashCan** ; réglez le délai de conservation sur sa page de configuration."
---
**Corbeille pour Dolibarr** vous évite de perdre des documents. Chaque fichier supprimé d'une zone de documents (tiers, propositions, commandes, factures, projets...) est déplacé dans une corbeille au lieu de disparaître, avec qui l'a supprimé, quand et depuis quelle fiche.

## Fonctionnalités

- **Automatique** : rien à faire sur les objets, les boutons de suppression habituels envoient le fichier dans la corbeille.
- **Page Corbeille** dans le menu GED : fichiers supprimés avec leur type, l'utilisateur, la date et un lien vers la fiche d'origine.
- **Restauration** d'un fichier à son emplacement d'origine en un clic (l'index GED est mis à jour). Un fichier qui existe de nouveau n'est jamais écrasé.
- **Suppression définitive** avec confirmation, selon les permissions de l'utilisateur (lecture, restauration, suppression).
- **Purge automatique** : les fichiers plus anciens que le nombre de jours choisi (365 par défaut, 0 pour les conserver indéfiniment) sont supprimés définitivement.
- **Seulement les vrais documents** : les fichiers temporaires et les miniatures ne vont pas dans la corbeille.
- **Sécurisé** : si un fichier ne peut pas être mis dans la corbeille, il n'est pas supprimé.
