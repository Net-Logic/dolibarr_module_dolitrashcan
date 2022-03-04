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
		global $conf, $user, $langs;

		// Error counter
		$error = 0;

		// $parameters = [
		// 	'GET' => $_GET,
		// 	'file' => $file,
		// 	'disableglob'=> $disableglob,
		// 	'nophperrors' => $nophperrors
		// ];

		if (in_array($parameters['currentcontext'], ['fileslib'])) {
			setEventMessage('TRASHCAN ' . $action . ' Context: ' . $parameters['currentcontext'] . ' File: ' . $parameters['file'], 'warnings');
			setEventMessage('TRASHCAN Filename to store: ' . str_replace(DOL_DATA_ROOT . '/', '', $parameters['file']), 'warnings');
			if (is_object($object)) {
				setEventMessage('TRASHCAN ' . $action . ' Element: ' . $object->element . ' Id: ' . $object->id, 'warnings');
			}
			// TODO paranoiac check if already exist
			$movetodir = DOL_DATA_ROOT . '/dolitrashcan/' . self::getRandomDir(4);
			$movetofilename = $movetodir . self::getUuid() . '.trash';

			$langs->loadLangs(["dolitrashcan@dolitrashcan"]);
			// TODO HERE IS LA PLACE FOR DAS MAGIE 🥁
			// MOVE FILE INTO TRASHCAN DIRECTORY WITH PHP MOVE NOT dol_move (restore will be done with dol_move to recreate ecm data)
			dol_mkdir($movetodir);
			if (!copy($parameters['file'], $movetofilename)) {
				$error++;
			}
			// On success save info into db
			// id (rowid...)
			// original filename (remove DOL_DATA_ROOT)
			// mimetype
			// original_created_at
			// deleted_at
			// deleted_by (he's fired)
			// element
			// fk_element
			// filename in trashcan A/B/C/D/uuid.trash (create function to generate random) so we can delete several time the same file
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
