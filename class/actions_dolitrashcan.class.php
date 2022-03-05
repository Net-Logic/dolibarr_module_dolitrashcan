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
 * \brief   DoliTrasCan hook overload.
 *
 * Put detailed description here.
 */


/**
 * Class ActionsDoliTrasCan
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
	 * Overloading the deleteFile function : replacing the parent's function with the one below
	 *
	 * @param   array           $parameters     Hook metadatas (context, etc...)
	 * @param   CommonObject    $object         The object to process (an invoice if you are in invoice module, a propale in propale's module, etc...)
	 * @param   string          $action         Current action (if set). Generally create or edit or null
	 * @param   HookManager     $hookmanager    Hook manager propagated to allow calling another hook
	 * @return  int                             < 0 on error, 0 on success, 1 to replace standard code
	 */
	public function deleteFile($parameters, &$object, &$action, $hookmanager)
	{
		global $user, $langs;

		// Error counter
		$error = 0;

		// $parameters = [
		// 	'GET' => $_GET,
		// 	'file' => $file,
		// 	'disableglob'=> $disableglob,
		// 	'nophperrors' => $nophperrors
		// ];

		if (in_array($parameters['currentcontext'], ['fileslib'])) {
			// TODO paranoiac check if already exist
			$movetodir = self::getRandomDir(4);
			$movetofilename = $movetodir . self::getUuid() . '.trash';

			// TODO HERE IS LA PLACE FOR DAS MAGIE 🥁
			// MOVE FILE INTO TRASHCAN DIRECTORY WITH PHP MOVE NOT dol_move (restore will be done with dol_move to recreate ecm data)
			if (!mkdir(DOL_DATA_ROOT . '/dolitrashcan/' . $movetodir, 0777, true)) {
				$error++;
			};
			if (!$error && !copy($parameters['file'], DOL_DATA_ROOT . '/dolitrashcan/' . $movetofilename)) {
				$error++;
			}
			if (!$error) {
				$mimetype = dol_mimetype($parameters['file']);
				$filelastmod = filemtime($parameters['file']);
				// On success save info into db
				// id (rowid...)
				// original filename (remove DOL_DATA_ROOT)
				// mimetype
				// original_created_at
				// deleted_at
				// deleted_by (he's fired)
				// element
				// fk_element
				// filename in trashcan A/B/C/D/uuid.trash so we can delete several time the same file
				$now = dol_now();
				$sql = 'INSERT INTO ' . MAIN_DB_PREFIX . 'dolitrashcan (';
				$sql .= 'original_filename';
				$sql .= ', original_created_at';
				$sql .= ', mimetype';
				$sql .= ', deleted_at';
				$sql .= ', deleted_by';
				$sql .= ', element';
				$sql .= ', fk_element';
				$sql .= ', trashcan_filename';
				$sql .= ') VALUES (';
				$sql .= '"' . $this->db->escape(str_replace(DOL_DATA_ROOT, '', $parameters['file'])) . '"';
				$sql .= ' , ' . ($filelastmod ? '"' . $this->db->idate($filelastmod) . '"' : "null");
				$sql .= ' , "' . $this->db->escape($mimetype) . '"';
				$sql .= ' , "' . $this->db->idate($now) . '"';
				$sql .= ' , ' . (is_object($user) ? (int) $user->id : "null");
				$sql .= ' , ' . (is_object($object) ? ('"' . $this->db->escape($object->element) . '"') : "null");
				$sql .= ' , ' . (is_object($object) ? (int) $object->id : "null");
				$sql .= ' , "' . $this->db->escape($movetofilename) . '"';
				$sql .= ')';

				$this->db->query($sql);
				$langs->loadLangs(["other", "dolitrashcan@dolitrashcan"]);
				// replace translation 'on the fly' to change next message only
				$langs->tab_translate['FileWasRemoved'] = $langs->tab_translate['DoliTrashCanFileWasMovedTo'];
			}
		}
		if (!$error) {
			// or return 1 to replace standard code
			return 0;
		} else {
			$this->errors[] = $langs->trans('DolitrashcanErrorMovingFileToTrashcan');
			return -1;
		}
	}

	/**
	 * generate random dir
	 * @param int $num number of subdirectories
	 * @return string
	 */
	private function getRandomDir($num)
	{
		$char = '0123456789abcdefghijklmnopqrstuvwxyz';
		$ret = '';
		for ($j = 0; $j < $num; $j++) {
			$i = rand(0, strlen($char) - 1);
			$ret .= $char[$i] . '/';
		}
		return $ret;
	}

	/**
	 * generate uuid
	 * @return string
	 */
	private function getUuid()
	{
		try {
			$data = random_bytes(16);
		} catch (Exception $e) {
			// empty catch if not enough entropy
		}
		$data[6] = chr(ord($data[6]) & 0x0f | 0x40);
		$data[8] = chr(ord($data[8]) & 0x3f | 0x80);
		return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
	}
}
