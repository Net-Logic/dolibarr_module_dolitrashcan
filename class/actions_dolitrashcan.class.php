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

		$error = 0; // Error counter
		// $parameters = [
		// 	'GET' => $_GET,
		// 	'file' => $file,
		// 	'disableglob'=> $disableglob,
		// 	'nophperrors' => $nophperrors
		// ];

		setEventMessage('TRASHCAN ' . $action . ' Context: ' . $parameters['currentcontext'] . ' File: ' . $parameters['file']);
		if (is_object($object)) {
			setEventMessage('TRASHCAN ' . $action . ' Element: ' . $object->element . ' Id: ' . $object->id);
		}
		if (in_array($parameters['currentcontext'], ['fileslib'])) {
			// TODO HERE IS LA PLACE FOR MAGIE
		}
		if (!$error) {
			// or return 1 to replace standard code
			return 0;
		} else {
			$this->errors[] = 'Error message';
			return -1;
		}
	}
}
