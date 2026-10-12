<?php

/* Copyright (C) 2001-2005 Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2004-2015 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2005-2012 Regis Houssin        <regis.houssin@inodbox.com>
 * Copyright (C) 2015      Jean-François Ferry	<jfefe@aternatik.fr>
 * Copyright (C) 2019-2022  Frédéric France     <frederic.france@netlogic.fr>
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
require_once DOL_DOCUMENT_ROOT . "/core/lib/functions2.lib.php";

// Load translation files required by the page
$langs->loadLangs(["dolitrashcan@dolitrashcan"]);

$action = GETPOST('action', 'aZ09');
$confirm = GETPOST('confirm', 'alpha');
$id = GETPOSTINT('id');
$limit = GETPOSTINT('limit') ? GETPOSTINT('limit') : getDolGlobalInt('MAIN_SIZE_LISTE_LIMIT', 25);
$page = max(0, GETPOSTINT('page'));
$offset = $limit * $page;

// Security check
if (!$user->hasRight('dolitrashcan', 'read')) {
	accessforbidden();
}
if (!empty($user->socid)) {
	accessforbidden();
}

/*
 * Actions
 */

if ($id > 0 && $action == 'restorefile' && $_SERVER['REQUEST_METHOD'] === 'POST' && $user->hasRight('dolitrashcan', 'write')) {
	// POST only (the form carries the CSRF token): the file goes back to its place, the trash entry is removed only when it worked
	$resql = $db->query('SELECT rowid, original_filename, trashcan_filename FROM ' . MAIN_DB_PREFIX . 'dolitrashcan WHERE rowid = ' . ((int) $id));
	$file = $resql ? $db->fetch_object($resql) : null;
	if (!$file) {
		setEventMessages($langs->trans($resql ? 'DoliTrashCanSomethingNotFound' : 'DoliTrashCanSomethingWentWrong'), null, 'warnings');
	} else {
		$source = DOL_DATA_ROOT . '/dolitrashcan/' . $file->trashcan_filename;
		$target = DOL_DATA_ROOT . $file->original_filename;
		if (strpos($file->original_filename, '/') !== 0 || preg_match('/\.\./', $file->original_filename)) {
			// old rows of another server (full path) are never restored outside the documents directory
			setEventMessages($langs->trans('DoliTrashCanSomethingWentWrong'), null, 'errors');
		} elseif (dol_is_file($target)) {
			// never overwrite a file that exists again
			setEventMessages($langs->trans('DoliTrashCanRestoreTargetExists', $file->original_filename), null, 'errors');
		} elseif (dol_mkdir(dirname($target)) < 0 || dol_copy($source, $target, '0', 0, 0, 0) <= 0) {
			setEventMessages($langs->trans('DoliTrashCanSomethingWentWrong'), null, 'errors');
		} else {
			// The standard deletion also removed the ECM entry: index the restored file again
			addFileIntoDatabaseIndex(dirname($target), basename($target), '', 'uploaded');
			@unlink($source);
			$db->query('DELETE FROM ' . MAIN_DB_PREFIX . 'dolitrashcan WHERE rowid = ' . ((int) $file->rowid));
			setEventMessages($langs->trans('DoliTrashCanFileRestored', $file->original_filename), null, 'mesgs');
		}
	}
	$action = '';
}

if ($id > 0 && $action == 'confirm_destroyfile' && $confirm == 'yes' && $user->hasRight('dolitrashcan', 'delete')) {
	$resql = $db->query('SELECT rowid, original_filename, trashcan_filename FROM ' . MAIN_DB_PREFIX . 'dolitrashcan WHERE rowid = ' . ((int) $id));
	$file = $resql ? $db->fetch_object($resql) : null;
	if (!$file) {
		setEventMessages($langs->trans($resql ? 'DoliTrashCanSomethingNotFound' : 'DoliTrashCanSomethingWentWrong'), null, 'warnings');
	} else {
		@unlink(DOL_DATA_ROOT . '/dolitrashcan/' . $file->trashcan_filename);
		$db->query('DELETE FROM ' . MAIN_DB_PREFIX . 'dolitrashcan WHERE rowid = ' . ((int) $file->rowid));
		setEventMessages($langs->trans('DoliTrashCanFileDestroyed', $file->original_filename), null, 'mesgs');
	}
	$action = '';
}


/*
 * View
 */

$form = new Form($db);

llxHeader("", $langs->trans("DoliTrashCanArea"));

$nbtotalofrecords = 0;
$resql = $db->query('SELECT COUNT(rowid) as nb FROM ' . MAIN_DB_PREFIX . 'dolitrashcan');
if ($resql && ($obj = $db->fetch_object($resql))) {
	$nbtotalofrecords = (int) $obj->nb;
}

$sql = 'SELECT rowid, original_filename, mimetype, deleted_at, deleted_by, element, fk_element';
$sql .= ' FROM ' . MAIN_DB_PREFIX . 'dolitrashcan';
$sql .= ' ORDER BY deleted_at DESC, rowid DESC';
$sql .= $db->plimit($limit + 1, $offset);
$resql = $db->query($sql);
$num = $resql ? min($db->num_rows($resql), $limit) : 0;

print_barre_liste($langs->trans("DoliTrashCanArea"), $page, $_SERVER["PHP_SELF"], '', '', '', '', $num, $nbtotalofrecords, 'object_dolitrashcan_32.png@dolitrashcan', 0, '', '', $limit);

if ($action == 'destroyfile' && $id > 0 && $user->hasRight('dolitrashcan', 'delete')) {
	print $form->formconfirm($_SERVER["PHP_SELF"] . '?id=' . ((int) $id) . '&page=' . $page, $langs->trans('DoliTrashCanDestroy'), $langs->trans('DoliTrashCanReallyDestroy'), 'confirm_destroyfile', '', 0, 1);
}

print '<div class="div-table-responsive">';
print '<table class="noborder centpercent">';
print '<tr class="liste_titre">';
print '<th>' . $langs->trans("DoliTrashCanDeletedFiles") . '</th>';
print '<th>' . $langs->trans("DoliTrashCanMimetype") . '</th>';
print '<th>' . $langs->trans("DoliTrashCanDeletedBy") . '</th>';
print '<th class="right">' . $langs->trans("DoliTrashCanDeletedAt") . '</th>';
print '<th class="right">' . $langs->trans("DoliTrashCanContext") . '</th>';
print '<th></th>';
print '</tr>';
$i = 0;
while ($resql && $i < $num && ($obj = $db->fetch_object($resql))) {
	$i++;
	print '<tr class="oddeven">';
	print '<td class="tdoverflowmax300" title="' . dol_escape_htmltag($obj->original_filename) . '">' . dol_escape_htmltag($obj->original_filename) . '</td>';
	print '<td>' . dol_escape_htmltag($obj->mimetype) . '</td>';
	print '<td>';
	if (!empty($obj->deleted_by)) {
		$tmpuser = new User($db);
		if ($tmpuser->fetch($obj->deleted_by) > 0) {
			print $tmpuser->getNomUrl(1);
		}
	}
	print '</td>';
	print '<td class="right">' . dol_print_date($db->jdate($obj->deleted_at), 'dayhour') . '</td>';
	print '<td class="right">';
	if (!empty($obj->element) && $obj->fk_element > 0) {
		print dolGetElementUrl($obj->fk_element, $obj->element, 1);
	}
	print '</td>';
	print '<td class="right nowraponall">';
	if ($user->hasRight('dolitrashcan', 'write')) {
		// POST + token: a link must not change anything
		print '<form method="POST" action="' . $_SERVER["PHP_SELF"] . '" class="inline-block">';
		print '<input type="hidden" name="token" value="' . newToken() . '">';
		print '<input type="hidden" name="action" value="restorefile">';
		print '<input type="hidden" name="id" value="' . ((int) $obj->rowid) . '">';
		print '<input type="hidden" name="page" value="' . $page . '">';
		print '<button type="submit" class="reposition buttonwithnoborder" title="' . dol_escape_htmltag($langs->trans('DoliTrashCanRestoreFile')) . '"><span class="fa fa-recycle" style="color: green;"></span></button>';
		print '</form>';
	}
	if ($user->hasRight('dolitrashcan', 'delete')) {
		print '&nbsp;<a class="reposition" href="' . $_SERVER["PHP_SELF"] . '?action=destroyfile&token=' . newToken() . '&id=' . ((int) $obj->rowid) . '&page=' . $page . '"><span class="fa fa-fire" style="color: red;" title="' . dol_escape_htmltag($langs->trans('DoliTrashCanDestroy')) . '"></span></a>';
	}
	print '</td>';
	print '</tr>';
}
if ($i == 0) {
	print '<tr class="oddeven"><td colspan="6"><span class="opacitymedium">' . $langs->trans("NoRecordFound") . '</span></td></tr>';
}
print '</table>';
print '</div>';

// End of page
llxFooter();
$db->close();
