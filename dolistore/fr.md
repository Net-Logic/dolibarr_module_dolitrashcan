---
title: "Corbeille pour Dolibarr - récupérez les fichiers supprimés par erreur"
short: "Une corbeille pour Dolibarr : les fichiers supprimés dans n'importe quelle zone de documents sont conservés et peuvent être restaurés ou supprimés définitivement."
---
**Corbeille pour Dolibarr** vous évite de perdre des documents. Chaque fichier supprimé d'une zone de documents (tiers, propositions, commandes, factures, projets...) est déplacé dans une corbeille au lieu de disparaître, avec qui l'a supprimé, quand et depuis quelle fiche.

## Fonctionnalités

- **Automatique** : rien à faire sur les objets, les boutons de suppression habituels envoient le fichier dans la corbeille.
- **Page Corbeille** dans le menu GED : fichiers supprimés avec leur type, l'utilisateur, la date et un lien vers la fiche d'origine.
- **Restauration** d'un fichier à son emplacement d'origine en un clic (l'index GED est mis à jour). Un fichier qui existe de nouveau n'est jamais écrasé.
- **Suppression définitive** avec confirmation, selon les permissions de l'utilisateur (lecture, restauration, suppression).
- **Purge automatique** : les fichiers plus anciens que le nombre de jours choisi (365 par défaut, 0 pour les conserver indéfiniment) sont supprimés définitivement.
- Les fichiers temporaires et les miniatures ne sont pas conservés : seuls les vrais documents vont dans la corbeille.
- Sécurisé : si un fichier ne peut pas être mis dans la corbeille, il n'est pas supprimé.

## Prérequis

Dolibarr 20 à 24, PHP 7.4 à 8.5, module GED activé. Langues : anglais, français, allemand, italien et espagnol.

Installez le zip avec **Accueil → Configuration → Modules → Déployer un module externe**, activez **DoliTrashCan**, puis donnez les permissions à vos utilisateurs. Le délai de conservation se règle sur la page de configuration du module.
