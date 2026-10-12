<?php

/*
 * Copyright (C) 2020-2022  Frédéric FRANCE <frederic.france@netlogic.fr>
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
 * \file    dolitrashcan/class/actions_dolitrashcan.class.php
 * \ingroup dolitrashcan
 * \brief   DoliTrashCan hook overload: files deleted by Dolibarr are moved into the trashcan.
 */


/**
 * Class ActionsDoliTrashCan
 */
class ActionsDoliTrashCan
{
	/**
	 * @var DoliDB Database handler.
	 */
	public $db;

	/**
	 * @var string Error code (or message)
	 */
	public $error = '';

	/**
	 * @var array Errors
	 */
	public $errors = [];

	/**
	 * @var array Hook results. Propagated to $hookmanager->resArray for later reuse
	 */
	public $results = [];

	/**
	 * @var string String displayed by executeHook() immediately after return
	 */
	public $resprints;

	/**
	 * Constructor
	 *
	 *  @param  DoliDB  $db     Database handler
	 */
	public function __construct($db)
	{
		$this->db = $db;
	}

	/**
	 * Overloading the deleteFile function: the file is copied into the trashcan before the standard deletion
	 *
	 * @param   array           $parameters     Hook metadatas (context, file, disableglob, nophperrors)
	 * @param   CommonObject    $object         The object to process (an invoice if you are in invoice module, a propale in propale's module, etc...)
	 * @param   string          $action         Current action (if set). Generally create or edit or null
	 * @param   HookManager     $hookmanager    Hook manager propagated to allow calling another hook
	 * @return  int                             < 0 on error (the file is not deleted), 0 to let the standard code delete the file
	 */
	public function deleteFile($parameters, &$object, &$action, $hookmanager)
	{
		global $langs, $user, $lastTrashcanId;

		if (($parameters['currentcontext'] ?? '') !== 'fileslib' || !self::isTrashable($parameters['file'] ?? '')) {
			return 0;
		}

		$langs->loadLangs(["other", "dolitrashcan@dolitrashcan"]);

		$file = $parameters['file'];
		$movetodir = $this->getRandomDir(4);
		$movetofilename = $movetodir . $this->getUuid() . '.trash';
		$trashdir = DOL_DATA_ROOT . '/dolitrashcan/';

		// Copy (not move): the standard code deletes the original file and its ECM entry afterwards
		if (dol_mkdir($trashdir . $movetodir) < 0 || dol_copy($file, $trashdir . $movetofilename, '0', 1, 0, 0) < 0) {
			$this->errors[] = $langs->trans('DolitrashcanErrorMovingFileToTrashcan');
			return -1;
		}

		$now = dol_now();
		$filelastmod = filemtime($file);
		$fk_parent = $lastTrashcanId ?? null;
		$sql = 'INSERT INTO ' . MAIN_DB_PREFIX . 'dolitrashcan (';
		$sql .= 'original_filename, original_created_at, mimetype, deleted_at, deleted_by, element, fk_element, trashcan_filename, fk_parent';
		$sql .= ") VALUES ('" . $this->db->escape(substr($file, strlen(DOL_DATA_ROOT))) . "'";
		$sql .= ', ' . ($filelastmod ? "'" . $this->db->idate($filelastmod) . "'" : 'NULL');
		$sql .= ", '" . $this->db->escape(dol_mimetype($file)) . "'";
		$sql .= ", '" . $this->db->idate($now) . "'";
		$sql .= ', ' . (is_object($user) && $user->id > 0 ? (int) $user->id : 'NULL');
		$sql .= ', ' . (is_object($object) && !empty($object->element) ? "'" . $this->db->escape($object->element) . "'" : 'NULL');
		$sql .= ', ' . (is_object($object) && !empty($object->id) ? (int) $object->id : 'NULL');
		$sql .= ", '" . $this->db->escape($movetofilename) . "'";
		$sql .= ', ' . (!empty($fk_parent) ? (int) $fk_parent : 'NULL');
		$sql .= ')';
		if (!$this->db->query($sql)) {
			// No trace in the database: the copy is useless, the file must not be deleted without being recoverable
			@unlink($trashdir . $movetofilename);
			$this->errors[] = $langs->trans('DolitrashcanErrorMovingFileToTrashcan');
			return -1;
		}
		// Files deleted by the same action are grouped under the first one
		if (empty($lastTrashcanId)) {
			$lastTrashcanId = $this->db->last_insert_id(MAIN_DB_PREFIX . 'dolitrashcan');
		}
		// Replace the translation 'on the fly' to change the next message only
		$langs->tab_translate['FileWasRemoved'] = $langs->tab_translate['DoliTrashCanFileWasMovedTo'];

		$this->purgeOldFiles($now);

		return 0;
	}

	/**
	 * Tell if a file must go to the trashcan: an existing file of the documents directory, but neither
	 * a temporary file, a thumbnail nor a file of the trashcan itself.
	 *
	 * @param   string  $file   Full path of the file
	 * @return  bool
	 */
	private static function isTrashable($file)
	{
		if ($file === '' || strpos($file, DOL_DATA_ROOT . '/') !== 0 || !is_file($file)) {
			return false;
		}
		$root = realpath(DOL_DATA_ROOT);
		$real = realpath($file);
		if ($root === false || $real === false || strpos($real, $root . DIRECTORY_SEPARATOR) !== 0) {
			return false;
		}
		$relative = str_replace('\\', '/', substr($real, strlen($root)));
		return !preg_match('#/(temp|thumbs|dolitrashcan)/#', $relative);
	}

	/**
	 * Permanently delete the files kept longer than DOLITRASHCAN_DAYS_BEFORE_DELETE days (0 = keep for ever)
	 *
	 * @param   int     $now    Current timestamp
	 * @return  void
	 */
	private function purgeOldFiles($now)
	{
		$days = getDolGlobalInt('DOLITRASHCAN_DAYS_BEFORE_DELETE', 365);
		if ($days <= 0) {
			return;
		}
		$sql = 'SELECT rowid, trashcan_filename FROM ' . MAIN_DB_PREFIX . 'dolitrashcan';
		$sql .= " WHERE deleted_at < '" . $this->db->idate($now - 86400 * $days) . "'";
		$resql = $this->db->query($sql);
		while ($resql && ($obj = $this->db->fetch_object($resql))) {
			@unlink(DOL_DATA_ROOT . '/dolitrashcan/' . $obj->trashcan_filename);
			$this->db->query('DELETE FROM ' . MAIN_DB_PREFIX . 'dolitrashcan WHERE rowid = ' . ((int) $obj->rowid));
		}
	}

	/**
	 * Generate a random directory, 4 levels of one character (a/b/c/d/)
	 *
	 * @param   int     $num    Number of subdirectories
	 * @return  string
	 */
	private function getRandomDir($num)
	{
		$char = '0123456789abcdefghijklmnopqrstuvwxyz';
		$ret = '';
		for ($j = 0; $j < $num; $j++) {
			$ret .= $char[random_int(0, strlen($char) - 1)] . '/';
		}
		return $ret;
	}

	/**
	 * Generate a version 4 uuid
	 *
	 * @return  string
	 */
	private function getUuid()
	{
		try {
			$data = random_bytes(16);
		} catch (Exception $e) {
			// not enough entropy: still unique enough for a file name
			$data = hex2bin(md5(uniqid((string) mt_rand(), true)));
		}
		$data[6] = chr(ord($data[6]) & 0x0f | 0x40);
		$data[8] = chr(ord($data[8]) & 0x3f | 0x80);
		return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
	}
}
