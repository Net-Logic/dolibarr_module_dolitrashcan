## 1.1.2

#### Documentation
* DoliStore page: Net Logic banner at the top of the description (dolistore-sync v0.5.0). No code change.

## 1.1.1

#### Documentation
* DoliStore page in the shared Net-Logic layout (dolistore-sync v0.4.4). No code change.

## 1.1.0

#### Compatibility
* Minimum Dolibarr version: 20. Supports Dolibarr 20 to 25 and PHP 7.4 to 8.5.
* Permissions and menu use `hasRight()` and `isModEnabled()`; setup page rebuilt on `FormSetup`.

#### Fixes
* A file could not be deleted when the random trash folder already existed (the delete was refused): the folder is created with `dol_mkdir()`.
* Restoring a file destroyed the trash copy even when the restore failed (missing folder, no write permission): the copy is only removed once the restore worked, a file that exists again is never overwritten, and the restored file is indexed again in the ECM.
* Dates of the trash were stored in GMT but compared in server time: the purge removed files at the wrong time on servers not in UTC.
* A deletion is refused when the trash entry cannot be written to the database (the file was lost before).
* Temporary files, thumbnails and files outside the documents directory are no longer put in the trash.
* Restore and permanent deletion needed only the read permission: they now need the create/update and delete permissions, restore is a POST with the CSRF token, and permanent deletion asks for a confirmation (GET links changed the data).
* Rows saved with a full path of another server (old installations) are never restored outside the documents directory.
* File names and types are escaped in the list (XSS), the list is paginated.
* "Days before permanent deletion": 0 keeps the files for ever (it used to purge every file at the next deletion).
* Missing translations (error messages, "not found") and wrong ones ("Delete it" for "Deleted on", "Basket"...): all five languages rewritten.

#### Packaging
* Packaging script and automatic update of the DoliStore product page on each release.
* Removed the automatic translation tool, the Selenium test template and the unused XML files.


## v1.0.2 (2022-11-30)

#### :rocket: Enhancement
* [#27](https://github.com/Net-Logic/dolibarr_module_dolitrashcan/pull/27) Changelog auto ([@frederic34](https://github.com/frederic34))
* [#24](https://github.com/Net-Logic/dolibarr_module_dolitrashcan/pull/24) test ([@frederic34](https://github.com/frederic34))
* [#23](https://github.com/Net-Logic/dolibarr_module_dolitrashcan/pull/23) Workb ([@frederic34](https://github.com/frederic34))

#### :memo: Documentation
* [#28](https://github.com/Net-Logic/dolibarr_module_dolitrashcan/pull/28) Work on changelog ([@frederic34](https://github.com/frederic34))
* [#22](https://github.com/Net-Logic/dolibarr_module_dolitrashcan/pull/22) auto translation ([@frederic34](https://github.com/frederic34))

#### Committers: 1
- Frédéric FRANCE ([@frederic34](https://github.com/frederic34))
