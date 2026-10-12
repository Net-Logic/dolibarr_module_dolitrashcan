# DoliTrashCan : une corbeille pour [Dolibarr ERP CRM](https://www.dolibarr.org) par [Net Logic](https://netlogic.fr)

Les fichiers supprimés dans les zones de documents de Dolibarr (tiers, propositions, commandes, factures, projets...) sont conservés dans une corbeille au lieu de disparaître. Vous voyez qui les a supprimés, quand et depuis quelle fiche, et vous pouvez les restaurer ou les supprimer définitivement.

## Fonctionnalités

| Fonctionnalité | Ce qu'elle fait |
|---|---|
| **Automatique** | Les boutons de suppression habituels de Dolibarr envoient le fichier dans la corbeille : rien à configurer sur les objets. |
| **Page Corbeille** | Dans le menu GED : fichiers supprimés avec leur type, l'utilisateur, la date et un lien vers la fiche d'origine. |
| **Restauration** | Remet le fichier à sa place en un clic et le réindexe dans la GED. Un fichier qui existe de nouveau n'est jamais écrasé. |
| **Suppression définitive** | Demande une confirmation puis supprime le fichier pour de bon. |
| **Purge automatique** | Les fichiers plus anciens que le nombre de jours choisi (365 par défaut, 0 pour les conserver indéfiniment) sont supprimés définitivement. |
| **Sécurisé** | Si un fichier ne peut pas être mis dans la corbeille, il n'est pas supprimé. Les fichiers temporaires et les miniatures ne sont pas conservés. |

## Prérequis

| Prérequis | Version |
|---|---|
| Dolibarr | 20.0 ou supérieur |
| PHP | 7.4 ou supérieur |
| Module | GED activé |

## Installation

1. Téléchargez `module_dolitrashcan-x.y.z.zip` depuis DoliStore ou les releases GitHub.
2. Utilisez **Accueil → Configuration → Modules → Déployer un module externe** pour le téléverser.
3. Activez **DoliTrashCan** dans **Accueil → Configuration → Modules**, puis donnez les permissions à vos utilisateurs.

## Configuration

Ouvrez la page de configuration du module (la clé à molette dans la liste des modules). Le seul paramètre est **Jours avant la suppression définitive** : les fichiers présents dans la corbeille depuis ce nombre de jours sont supprimés définitivement à la prochaine suppression d'un fichier. Mettez 0 pour les conserver indéfiniment.

## Utiliser la corbeille

L'entrée **Corbeille** se trouve dans le menu GED. Pour chaque fichier supprimé, vous voyez son type, qui l'a supprimé, quand, et la fiche à laquelle il appartenait.

| Permission | Autorise |
|---|---|
| Lire | Voir la corbeille |
| Créer/Modifier | Restaurer un fichier |
| Supprimer | Supprimer un fichier définitivement |

La restauration remet le fichier dans son dossier d'origine, recréé si besoin. Si un fichier du même nom existe de nouveau, il n'est pas écrasé : le fichier reste dans la corbeille et un message vous l'indique.

## Ce qui va dans la corbeille

Tout fichier supprimé par les fonctions standard de Dolibarr, sauf les fichiers temporaires, les miniatures et les fichiers situés hors du répertoire des documents.

## Traductions

Anglais, français, allemand, italien et espagnol. Les traductions peuvent être complétées en modifiant les fichiers du dossier *langs*.

## Licences

### Code principal

GPLv3 ou (à votre convenance) toute version ultérieure. Voir le fichier COPYING.

### Documentation

Tous les textes et fichiers readme sont sous licence GFDL.
