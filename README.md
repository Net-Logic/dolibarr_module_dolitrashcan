# DoliTrashCan: a trash for [Dolibarr ERP CRM](https://www.dolibarr.org) by [Net Logic](https://netlogic.fr)

Files deleted from the document areas of Dolibarr (third parties, proposals, orders, invoices, projects...) are kept in a trash instead of disappearing. You see who deleted them, when and from which record, and you can restore them or delete them for good.

## Features

| Feature | What it does |
|---|---|
| **Automatic** | The standard delete buttons of Dolibarr send the file to the trash: nothing to set up on the objects. |
| **Trash page** | In the ECM menu: deleted files with their type, the user, the date and a link to the record they belonged to. |
| **Restore** | Puts the file back where it was in one click and indexes it again in the ECM. A file that exists again is never overwritten. |
| **Delete permanently** | Asks for a confirmation and removes the file for good. |
| **Automatic purge** | Files older than the number of days you choose (365 by default, 0 keeps them for ever) are removed for good. |
| **Safe** | If a file cannot be put in the trash, it is not deleted. Temporary files and thumbnails are not kept. |

## Requirements

| Requirement | Version |
|---|---|
| Dolibarr | 20.0 or later |
| PHP | 7.4 or later |
| Module | ECM enabled |

## Installation

1. Download `module_dolitrashcan-x.y.z.zip` from DoliStore or the GitHub releases.
2. Use **Home → Setup → Modules → Deploy an external module** to upload it.
3. Enable **DoliTrashCan** in **Home → Setup → Modules**, then give the permissions to your users.

## Setup

Open the setup page of the module (the wrench icon in the module list). The only setting is **Days before permanent deletion**: files that have been in the trash for that many days are deleted for good when a new file is deleted. Use 0 to keep them for ever.

## Using the trash

The **Trash** entry is in the ECM menu. For each deleted file you see its type, who deleted it, when, and the record it belonged to.

| Permission | Allows |
|---|---|
| Read | See the trash |
| Create/Update | Restore a file |
| Delete | Delete a file permanently |

Restoring puts the file back in its original folder, which is created again if needed. If a file with the same name exists again, it is not overwritten: the file stays in the trash and a message tells you.

## What goes to the trash

Every file deleted through the standard Dolibarr functions, except temporary files, thumbnails, and files outside the documents directory.

## Translations

English, French, German, Italian and Spanish. Translations can be completed by editing the files in *langs*.

## Licenses

### Main code

GPLv3 or (at your option) any later version. See file COPYING for more information.

### Documentation

All texts and readmes are licensed under GFDL.
