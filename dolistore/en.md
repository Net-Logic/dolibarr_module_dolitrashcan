---
title: "Trash for Dolibarr - recover the files you delete by mistake"
short: "A recycle bin for Dolibarr: files deleted from any document area are kept and can be restored or deleted for good."
start: "The module is listed as **DoliTrashCan**; set the retention delay on its setup page."
---
**Trash for Dolibarr** stops you from losing documents. Every file deleted from a document area (third parties, proposals, orders, invoices, projects...) is moved to a trash instead of disappearing, with who deleted it, when and from which record.

## Features

- **Automatic**: nothing to do on the objects, the standard delete buttons now send the file to the trash.
- **Trash page** in the ECM menu: deleted files with their type, the user, the date and a link to the record they belonged to.
- **Restore** a file to its original place in one click (the ECM index is updated). A file that exists again is never overwritten.
- **Delete permanently** with a confirmation, according to the user's permissions (read, restore, delete).
- **Automatic purge**: files older than the number of days you choose (365 by default, 0 keeps them for ever) are removed for good.
- **Only real documents**: temporary files and thumbnails are not kept in the trash.
- **Safe**: if a file cannot be put in the trash, it is not deleted.
