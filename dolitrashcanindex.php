<?php
/* Copyright (C) 2001-2005 Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2004-2015 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2005-2012 Regis Houssin        <regis.houssin@inodbox.com>
 * Copyright (C) 2015      Jean-François Ferry	<jfefe@aternatik.fr>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <https://www.gnu.org/licenses/>.
 */

/**
 *	\file       dolitrashcan/dolitrashcanindex.php
 *	\ingroup    dolitrashcan
 *	\brief      Home page of dolitrashcan top menu
 */

// Load Dolibarr environment
include 'config.php';

require_once DOL_DOCUMENT_ROOT . '/core/class/html.formfile.class.php';

// Load translation files required by the page
$langs->loadLangs(["dolitrashcan@dolitrashcan"]);

$action = GETPOST('action', 'aZ09');


// Security check
// if (! $user->rights->dolitrashcan->myobject->read) {
// 	accessforbidden();
// }
$socid = GETPOST('socid', 'int');
if (isset($user->socid) && $user->socid > 0) {
	$action = '';
	$socid = $user->socid;
}

$max = 5;
$now = dol_now();


/*
 * Actions
 */

// None


/*
 * View
 */

$form = new Form($db);
$formfile = new FormFile($db);

llxHeader("", $langs->trans("DoliTrashCanArea"));

print load_fiche_titre($langs->trans("DoliTrashCanArea"), '', 'object_dolitrashcan_32.png@dolitrashcan');

// Draft MyObject
if (!empty($user->rights->dolitrashcan->read)) {
	$langs->load("dolitrashcan@dolitrashcan");

	$sql = "SELECT ";
	$sql .= 'original_filename';
	$sql .= ', original_created_at';
	$sql .= ', mimetype';
	$sql .= ', deleted_at';
	$sql .= ', deleted_by';
	$sql .= ', element';
	$sql .= ', fk_element';
	$sql .= ', trashcan_filename';
	$sql .= ' FROM ' . MAIN_DB_PREFIX . 'dolitrashcan as dtc';
	$sql .= ' ORDER BY deleted_at DESC';

	$resql = $db->query($sql);
	print '<table class="noborder centpercent">';
	print '<tr class="liste_titre">';
	print '<th>' . $langs->trans("DoliTrashCanDeletedFiles") . '</th>';
	print '<th>' . $langs->trans("DoliTrashCanMimetype") . '</th>';
	print '<th>' . $langs->trans("DoliTrashCanDeletedBy") . '</th>';
	print '<th>' . $langs->trans("DoliTrashCanContext") . '</th>';
	print '</tr>';
	while ($resql && $obj = $db->fetch_object($resql)) {
		print '<tr class="oddeven">';
		print '<td class="nowrap">';
		print $obj->original_filename;
		print '</td>';
		print '<td>';
		print $obj->mimetype;
		print '</td>';
		print '<td>';
		print $obj->deleted_by;
		print '</td>';
		print '<td>';
		print $obj->element.'/'.$obj->fk_element;
		print '</td>';
		print '</tr>';
	}
}
print "</table><br>";

$db->free($resql);


// End of page
llxFooter();
$db->close();
