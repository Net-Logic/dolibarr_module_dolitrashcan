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
require_once DOL_DOCUMENT_ROOT . '/core/lib/files.lib.php';

// Load translation files required by the page
$langs->loadLangs(["dolitrashcan@dolitrashcan"]);

$action = GETPOST('action', 'aZ09');
$id = GETPOST('id', 'int');


// Security check
if (empty($user->rights->dolitrashcan->read)) {
	accessforbidden();
}
$socid = GETPOST('socid', 'int');
if (isset($user->socid) && $user->socid > 0) {
	$action = '';
	$socid = $user->socid;
}

/*
 * Actions
 */

if (($action == 'restorefile' || $action == 'destroyfile') && $id > 0) {
	// restore the file
	$sql = "SELECT rowid";
	$sql .= ', original_filename';
	$sql .= ', original_created_at';
	$sql .= ', mimetype';
	$sql .= ', deleted_at';
	$sql .= ', deleted_by';
	$sql .= ', element';
	$sql .= ', fk_element';
	$sql .= ', trashcan_filename';
	$sql .= ' FROM ' . MAIN_DB_PREFIX . 'dolitrashcan';
	$sql .= ' WHERE rowid=' . (int) $id;

	$resql = $db->query($sql);
	if ($resql && ($db->num_rows($resql) > 0)) {
		$file = $db->fetch_object($resql);
		$tmpuser = new User($db);
		$tmpuser->firstname = 'John';
		$tmpuser->lastname = "Doe";
		if (!empty($file->deleted_by) && $tmpuser->fetch($file->deleted_by)) {
			// print $tmpuser->getNomUrl(1);
		}
		$mesg = 'DoliTrashCanFileDestroyed';
		if ($action == 'restorefile') {
			$mesg = 'DoliTrashCanFileRestored';
			dol_copy(DOL_DATA_ROOT . '/dolitrashcan/' . $file->trashcan_filename, DOL_DATA_ROOT . $file->original_filename);
		}
		@unlink(DOL_DATA_ROOT . '/dolitrashcan/' . $file->trashcan_filename);
		$sql = 'DELETE FROM ' . MAIN_DB_PREFIX . 'dolitrashcan';
		$sql .= ' WHERE rowid=' . (int) $id;
		$resql = $db->query($sql);
		setEventMessage($langs->trans($mesg, $file->original_filename, $tmpuser->getFullName($langs)));
	} elseif ($resql && ($db->num_rows($resql) == 0)) {
		setEventMessage($langs->trans('DoliTrashCanSomethingNotFound'));
	} elseif (!$resql) {
		setEventMessage($langs->trans('DoliTrashCanSomethingWentWrong'));
	}
}


/*
 * View
 */

$form = new Form($db);
$formfile = new FormFile($db);

llxHeader("", $langs->trans("DoliTrashCanArea"));

print load_fiche_titre($langs->trans("DoliTrashCanArea"), '', 'object_dolitrashcan_32.png@dolitrashcan');

// DoliTrashCan Objects
if (!empty($user->rights->dolitrashcan->read)) {
	$langs->load("dolitrashcan@dolitrashcan");

	$sql = "SELECT rowid";
	$sql .= ', original_filename';
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
	print '<th></th>';
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
		$tmpuser = new User($db);
		if (!empty($obj->deleted_by) && $tmpuser->fetch($obj->deleted_by)) {
			print $tmpuser->getNomUrl(1);
		}
		print '</td>';
		print '<td>';
		$tmpobject = fetchObjectByElement($obj->fk_element, $obj->element);
		if (is_object($tmpobject)) {
			print $tmpobject->getNomUrl(1);
		}
		print '</td>';
		print '<td>';
		// fontawesome_envelope-open-text_fas_red_1em
		print '<a href="' . $_SERVER['PHP_SELF'] . '?action=restorefile&id=' . $obj->rowid . '">' . img_picto($langs->trans('DoliTrashCanRestoreFile'), 'fontawesome_recycle_fa_green_1em') . '</a>';
		print '&nbsp;<a href="' . $_SERVER['PHP_SELF'] . '?action=destroyfile&id=' . $obj->rowid . '">' . img_picto($langs->trans('DoliTrashCanDestroy'), 'fontawesome_trash_fa_red_1em') . '</a>';
		print '</td>';
		print '</tr>';
	}
}
print "</table><br>";

// End of page
llxFooter();
$db->close();
