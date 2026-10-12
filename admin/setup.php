<?php

/* Copyright (C) 2004-2017  Laurent Destailleur  	<eldy@users.sourceforge.net>
 * Copyright (C) 2022       Frédéric France    		<frederic.france@netlogic.fr>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

/**
 * \file    dolitrashcan/admin/setup.php
 * \ingroup dolitrashcan
 * \brief   DoliTrashCan setup page.
 */

// Load Dolibarr environment
include '../config.php';

// Libraries
require_once DOL_DOCUMENT_ROOT . "/core/lib/admin.lib.php";
require_once DOL_DOCUMENT_ROOT . '/core/class/html.formsetup.class.php';
require_once '../lib/dolitrashcan.lib.php';

// Translations
$langs->loadLangs(["admin", "dolitrashcan@dolitrashcan"]);

// Initialize technical object to manage hooks of page. Note that conf->hooks_modules contains array of hook context
$hookmanager->initHooks(['dolitrashcansetup', 'globalsetup']);

// Access control
if (!$user->admin) {
	accessforbidden();
}

// Parameters
$action = GETPOST('action', 'aZ09');
$backtopage = GETPOST('backtopage', 'alpha');
// Used by actions_setmoduleoptions.inc.php
$modulepart = GETPOST('modulepart', 'aZ09');

$error = 0;
$setupnotempty = 0;

$formSetup = new FormSetup($db);

$item = $formSetup->newItem('DOLITRASHCAN_DAYS_BEFORE_DELETE');
$item->defaultFieldValue = '365';
$item->cssClass = 'width75';
$item->fieldAttr['type'] = 'number';
$item->fieldAttr['min'] = '0';
$item->helpText = $langs->transnoentities('DOLITRASHCAN_DAYS_BEFORE_DELETETooltip');

/*
 * Actions
 */

include DOL_DOCUMENT_ROOT . '/core/actions_setmoduleoptions.inc.php';

/*
 * View
 */

$help_url = '';
$page_name = "DoliTrashCanSetup";

llxHeader('', $langs->trans($page_name), $help_url);

// Subheader
$linkback = '<a href="' . ($backtopage ? $backtopage : DOL_URL_ROOT . '/admin/modules.php?restore_lastsearch_values=1') . '">' . $langs->trans("BackToModuleList") . '</a>';

print load_fiche_titre($langs->trans($page_name), $linkback, 'title_setup');

// Configuration header
$head = dolitrashcanAdminPrepareHead();
print dol_get_fiche_head($head, 'settings', $langs->trans($page_name), -1, "dolitrashcan@dolitrashcan");

echo '<span class="opacitymedium">' . $langs->trans("DoliTrashCanSetupPage") . '</span><br><br>';

if ($action == 'edit') {
	print $formSetup->generateOutput(true);
	print '<br>';
} elseif (!empty($formSetup->items)) {
	print $formSetup->generateOutput();
	print '<div class="tabsAction">';
	print '<a class="butAction" href="' . $_SERVER["PHP_SELF"] . '?action=edit&token=' . newToken() . '">' . $langs->trans("Modify") . '</a>';
	print '</div>';
}

// Page end
print dol_get_fiche_end();

llxFooter();
$db->close();
