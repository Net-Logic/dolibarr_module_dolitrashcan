<?php
/* Copyright (C) 2004-2018  Laurent Destailleur     <eldy@users.sourceforge.net>
 * Copyright (C) 2018-2019  Nicolas ZABOURI         <info@inovea-conseil.com>
 * Copyright (C) 2019-2022  Frédéric France         <frederic.france@netlogic.fr>
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
 * 	\defgroup   dolitrashcan     Module DoliTrashCan
 *  \brief      DoliTrashCan module descriptor.
 *
 *  \file       htdocs/dolitrashcan/core/modules/modDoliTrashCan.class.php
 *  \ingroup    dolitrashcan
 *  \brief      Description and activation file for module DoliTrashCan
 */
include_once DOL_DOCUMENT_ROOT . '/core/modules/DolibarrModules.class.php';

// phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps
/**
 *  Description and activation class for module DoliTrashCan
 */
class modDoliTrashCan extends DolibarrModules
{
	// phpcs:enable
	/**
	 * Constructor. Define names, constants, directories, boxes, permissions
	 *
	 * @param DoliDB $db Database handler
	 */
	public function __construct($db)
	{
		global $langs, $conf;
		$this->db = $db;

		// Id for module (must be unique).
		// Use here a free id (See in Home -> System information -> Dolibarr for list of used modules id).
		$this->numero = 135410; // TODO Go on page https://wiki.dolibarr.org/index.php/List_of_modules_id to reserve an id number for your module

		// Key text used to identify module (for permissions, menus, etc...)
		$this->rights_class = 'dolitrashcan';

		// Family can be 'base' (core modules),'crm','financial','hr','projects','products','ecm','technic' (transverse modules),'interface' (link with external tools),'other','...'
		// It is used to group modules by family in module setup page
		$this->family = 'Net-Logic';

		// Module position in the family on 2 digits ('01', '10', '20', ...)
		$this->module_position = '90';

		// Gives the possibility for the module, to provide his own family info and position of this family (Overwrite $this->family and $this->module_position. Avoid this)
		// $this->familyinfo = ['myownfamily' => ['position' => '01', 'label' => $langs->trans("MyOwnFamily")));
		// Module label (no space allowed), used if translation string 'ModuleDoliTrashCanName' not found (DoliTrashCan is name of module).
		$this->name = preg_replace('/^mod/i', '', get_class($this));

		// Module description, used if translation string 'ModuleDoliTrashCanDesc' not found (DoliTrashCan is name of module).
		$this->description = "DoliTrashCanDescription";
		// Used only if file README.md and README-LL.md not found.
		$this->descriptionlong = "DoliTrashCanDescription";

		// Author
		$this->editor_name = 'Net Logic';
		$this->editor_url = 'https://netlogic.fr';

		// Possible values for version are: 'development', 'experimental', 'dolibarr', 'dolibarr_deprecated' or a version string like 'x.y.z'
		$this->version = '1.0.2';
		// Url to the file with your last numberversion of this module
		$this->url_last_version = 'https://wiki.netlogic.fr/versionmodule.php?module=dolitrashcan';

		// Key used in llx_const table to save module status enabled/disabled (where DOLITRASHCAN is value of property name of module in uppercase)
		$this->const_name = 'MAIN_MODULE_' . strtoupper($this->name);

		// Name of image file used for this module.
		// If file is in theme/yourtheme/img directory under name object_pictovalue.png, use this->picto='pictovalue'
		// If file is in module/img directory under name object_pictovalue.png, use this->picto='pictovalue@module'
		// To use a supported fa-xxx css style of font awesome, use this->picto='xxx'
		$this->picto = 'dolitrashcan@dolitrashcan';

		// Define some features supported by module (triggers, login, substitutions, menus, css, etc...)
		$this->module_parts = [
			// Set this to 1 if module has its own trigger directory (core/triggers)
			'triggers' => 0,
			// Set this to 1 if module has its own login method file (core/login)
			'login' => 0,
			// Set this to 1 if module has its own substitution function file (core/substitutions)
			'substitutions' => 0,
			// Set this to 1 if module has its own menus handler directory (core/menus)
			'menus' => 0,
			// Set this to 1 if module overwrite template dir (core/tpl)
			'tpl' => 0,
			// Set this to 1 if module has its own barcode directory (core/modules/barcode)
			'barcode' => 0,
			// Set this to 1 if module has its own models directory (core/modules/xxx)
			'models' => 0,
			// Set this to 1 if module has its own printing directory (core/modules/printing)
			'printing' => 0,
			// Set this to 1 if module has its own theme directory (theme)
			'theme' => 0,
			// Set this to relative path of css file if module has its own css file
			'css' => [
				// '/dolitrashcan/css/dolitrashcan.css.php',
			],
			// Set this to relative path of js file if module must load a js on all pages
			'js' => [
				// '/dolitrashcan/js/dolitrashcan.js.php',
			],
			// Set here all hooks context managed by module. To find available hook context, make a "grep -r '>initHooks(' *" on source code. You can also set hook context to 'all'
			'hooks' => [
				'data' => [
					'fileslib',
				],
				'entity' => $conf->entity,
			],
			// Set this to 1 if features of module are opened to external users
			'moduleforexternal' => 0,
		];

		// Data directories to create when module is enabled.
		// Example: this->dirs = ["/dolitrashcan/temp","/dolitrashcan/subdir");
		$this->dirs = ["/dolitrashcan/temp"];

		// Config pages. Put here list of php page, stored into dolitrashcan/admin directory, to use to setup module.
		$this->config_page_url = ["setup.php@dolitrashcan"];

		// Dependencies
		// A condition to hide module
		$this->hidden = false;
		// List of module class names as string that must be enabled if this module is enabled. Example: ['always1'=>'modModuleToEnable1','always2'=>'modModuleToEnable2', 'FR1'=>'modModuleToEnableFR'...)
		$this->depends = [
			'always1' => 'modECM',
		];
		$this->requiredby = []; // List of module class names as string to disable if this one is disabled. Example: ['modModuleToDisable1', ...)
		$this->conflictwith = []; // List of module class names as string this module is in conflict with. Example: ['modModuleToDisable1', ...)

		// The language file dedicated to your module
		$this->langfiles = ["dolitrashcan@dolitrashcan"];

		// Prerequisites
		$this->phpmin = [7, 0]; // Minimum version of PHP required by module
		$this->need_dolibarr_version = [10, -3]; // Minimum version of Dolibarr required by module

		// Messages at activation
		$this->warnings_activation = []; // Warning to show when we activate module. ['always'='text') or ['FR'='textfr','MX'='textmx'...)
		$this->warnings_activation_ext = []; // Warning to show when we activate an external module. ['always'='text') or ['FR'='textfr','MX'='textmx'...)
		//$this->automatic_activation = ['FR'=>'DoliTrashCanWasAutomaticallyActivatedBecauseOfYourCountryChoice');
		//$this->always_enabled = true;								// If true, can't be disabled

		// Constants
		// List of particular constants to add when module is enabled (key, 'chaine', value, desc, visible, 'current' or 'allentities', deleteonunactive)
		$this->const = [
			// 1 => ['DOLITRASHCAN_MYNEWCONST1', 'chaine', 'myvalue', 'This is a constant to add', 1],
			// 2 => ['DOLITRASHCAN_MYNEWCONST2', 'chaine', 'myvalue', 'This is another constant to add', 0, 'current', 1],
		];
		$this->const = [];

		if (!isset($conf->dolitrashcan) || !isset($conf->dolitrashcan->enabled)) {
			$conf->dolitrashcan = new stdClass();
			$conf->dolitrashcan->enabled = 0;
		}

		// Array to add new pages in new tabs
		$this->tabs = [];
		// Example:
		// To add a new tab identified by code tabname1
		// $this->tabs[] = ['data'=>'objecttype:+tabname1:Title1:mylangfile@dolitrashcan:$user->rights->dolitrashcan->read:/dolitrashcan/mynewtab1.php?id=__ID__');
		// To add another new tab identified by code tabname2. Label will be result of calling all substitution functions on 'Title2' key.
		// $this->tabs[] = ['data'=>'objecttype:+tabname2:SUBSTITUTION_Title2:mylangfile@dolitrashcan:$user->rights->othermodule->read:/dolitrashcan/mynewtab2.php?id=__ID__',
		// To remove an existing tab identified by code tabname
		// $this->tabs[] = ['data'=>'objecttype:-tabname:NU:conditiontoremove');
		//
		// Where objecttype can be
		// 'categories_x'	  to add a tab in category view (replace 'x' by type of category (0=product, 1=supplier, 2=customer, 3=member)
		// 'contact'          to add a tab in contact view
		// 'contract'         to add a tab in contract view
		// 'group'            to add a tab in group view
		// 'intervention'     to add a tab in intervention view
		// 'invoice'          to add a tab in customer invoice view
		// 'invoice_supplier' to add a tab in supplier invoice view
		// 'member'           to add a tab in fundation member view
		// 'opensurveypoll'	  to add a tab in opensurvey poll view
		// 'order'            to add a tab in customer order view
		// 'order_supplier'   to add a tab in supplier order view
		// 'payment'		  to add a tab in payment view
		// 'payment_supplier' to add a tab in supplier payment view
		// 'product'          to add a tab in product view
		// 'propal'           to add a tab in propal view
		// 'project'          to add a tab in project view
		// 'stock'            to add a tab in stock view
		// 'thirdparty'       to add a tab in third party view
		// 'user'             to add a tab in user view

		// Dictionaries
		$this->dictionaries = [];
		/* Example:
		$this->dictionaries=[
			'langs'=>'dolitrashcan@dolitrashcan',
			// List of tables we want to see into dictonnary editor
			'tabname'=>[MAIN_DB_PREFIX."table1", MAIN_DB_PREFIX."table2", MAIN_DB_PREFIX."table3"],
			// Label of tables
			'tablib'=>["Table1", "Table2", "Table3"],
			// Request to select fields
			'tabsql'=>['SELECT f.rowid as rowid, f.code, f.label, f.active FROM '.MAIN_DB_PREFIX.'table1 as f', 'SELECT f.rowid as rowid, f.code, f.label, f.active FROM '.MAIN_DB_PREFIX.'table2 as f', 'SELECT f.rowid as rowid, f.code, f.label, f.active FROM '.MAIN_DB_PREFIX.'table3 as f'],
			// Sort order
			'tabsqlsort'=>["label ASC", "label ASC", "label ASC"],
			// List of fields (result of select to show dictionary)
			'tabfield'=>["code,label", "code,label", "code,label"],
			// List of fields (list of fields to edit a record)
			'tabfieldvalue'=>["code,label", "code,label", "code,label"],
			// List of fields (list of fields for insert)
			'tabfieldinsert'=>["code,label", "code,label", "code,label"],
			// Name of columns with primary key (try to always name it 'rowid')
			'tabrowid'=>["rowid", "rowid", "rowid"],
			// Condition to show each dictionary
			'tabcond'=>[$conf->dolitrashcan->enabled, $conf->dolitrashcan->enabled, $conf->dolitrashcan->enabled)
		);
		*/

		// Boxes/Widgets
		// Add here list of php file(s) stored in dolitrashcan/core/boxes that contains a class to show a widget.
		$this->boxes = [
			// 0 => [
			// 	'file' => 'dolitrashcanwidget1.php@dolitrashcan',
			// 	'note' => 'Widget provided by DoliTrashCan',
			// 	'enabledbydefaulton' => 'Home',
			// ],
		];

		// Cronjobs (List of cron jobs entries to add when module is enabled)
		// unit_frequency must be 60 for minute, 3600 for hour, 86400 for day, 604800 for week
		$this->cronjobs = [
			//  0 => [
			//      'label' => 'MyJob label',
			//      'jobtype' => 'method',
			//      'class' => '/dolitrashcan/class/myobject.class.php',
			//      'objectname' => 'MyObject',
			//      'method' => 'doScheduledJob',
			//      'parameters' => '',
			//      'comment' => 'Comment',
			//      'frequency' => 2,
			//      'unitfrequency' => 3600,
			//      'status' => 0,
			//      'test' => '$conf->dolitrashcan->enabled',
			//      'priority' => 50,
			//  ],
		];
		// Example:
		// $this->cronjobs = [
		// 	0 => [
		// 	   'label'=>'My label',
		// 	   'jobtype'=>'method',
		// 	   'class'=>'/dir/class/file.class.php',
		// 	   'objectname'=>'MyClass',
		// 	   'method'=>'myMethod',
		// 	   'parameters' => 'param1, param2',
		// 	   'comment' => 'Comment',
		// 	   'frequency' => 2,
		// 	   'unitfrequency'=>3600,
		// 	   'status'=>0,
		// 	   'test'=>'$conf->dolitrashcan->enabled',
		// 	   'priority'=>50,
		// 	],
		// 	1 => [
		// 		'label'=>'My label',
		// 		'jobtype'=>'command', 'command'=>'', 'parameters'=>'param1, param2', 'comment'=>'Comment',
		// 		'frequency'=>1,
		// 		'unitfrequency'=>3600*24,
		// 		'status'=>0, 'test'=>'$conf->dolitrashcan->enabled',
		// 		'priority'=>50,
		// 	],
		// ];

		// Permissions provided by this module
		$this->rights = [];
		$r = 0;
		// Add here entries to declare new permissions
		// Permission id (must not be already used)
		$this->rights[$r][0] = $this->numero . sprintf("%02d", $r + 1);
		// Permission label
		$this->rights[$r][1] = 'Read objects of DoliTrashCan';
		$this->rights[$r][4] = 'read';
		// In php code, permission will be checked by test if ($user->rights->dolitrashcan->myobject->read)
		$this->rights[$r][5] = '';
		$r++;
		$this->rights[$r][0] = $this->numero . sprintf("%02d", $r + 1);
		$this->rights[$r][1] = 'Create/Update objects of DoliTrashCan';
		$this->rights[$r][4] = 'write';
		$this->rights[$r][5] = '';
		$r++;
		$this->rights[$r][0] = $this->numero . sprintf("%02d", $r + 1);
		$this->rights[$r][1] = 'Delete objects of DoliTrashCan';
		$this->rights[$r][4] = 'delete';
		$this->rights[$r][5] = '';
		$r++;

		// Main menu entries to add
		$this->menu = [];
		$r = 0;
		// Add here entries to declare new menus
		$this->menu[$r++] = [
			// '' if this is a top menu. For left menu, use 'fk_mainmenu=xxx' or 'fk_mainmenu=xxx,fk_leftmenu=yyy' where xxx is mainmenucode and yyy is a leftmenucode
			'fk_menu' => 'fk_mainmenu=ecm,fk_leftmenu=ecm',
			// This is a Top menu entry
			'type' => 'left',
			'titre' => 'DoliTrashCan',
			'prefix' => img_picto('', $this->picto, 'class="paddingright pictofixedwidth valignmiddle"'),
			'mainmenu' => 'ecm',
			'leftmenu' => 'ecm',
			'url' => '/dolitrashcan/dolitrashcanindex.php',
			// Lang file to use (without .lang) by module. File must be in langs/code_CODE/ directory.
			'langs' => 'dolitrashcan@dolitrashcan',
			'position' => 1000 + $r,
			// Define condition to show or hide menu entry. Use '$conf->dolitrashcan->enabled' if entry must be visible if module is enabled.
			'enabled' => '$conf->dolitrashcan->enabled',
			// Use 'perms'=>'$user->rights->dolitrashcan->level1->level2' if you want your menu with a permission rules
			'perms' => '$user->rights->dolitrashcan->read',
			'target' => '',
			// 0=Menu for internal users, 1=external users, 2=both
			'user' => 0,
		];
	}

	/**
	 *  Function called when module is enabled.
	 *  The init function add constants, boxes, permissions and menus (defined in constructor) into Dolibarr database.
	 *  It also creates data directories
	 *
	 *  @param      string  $options    Options when enabling module ('', 'noboxes')
	 *  @return     int             	1 if OK, 0 if KO
	 */
	public function init($options = '')
	{
		global $conf, $langs;

		//$result = $this->_load_tables('/install/mysql/tables/', 'dolitrashcan');
		$result = $this->_load_tables('/dolitrashcan/sql/');
		if ($result < 0) {
			return -1; // Do not activate module if error 'not allowed' returned when loading module SQL queries (the _load_table run sql with run_sql with the error allowed parameter set to 'default')
		}

		// Create extrafields during init
		//include_once DOL_DOCUMENT_ROOT.'/core/class/extrafields.class.php';
		//$extrafields = new ExtraFields($this->db);
		//$result1=$extrafields->addExtraField('dolitrashcan_myattr1', "New Attr 1 label", 'boolean', 1,  3, 'thirdparty',   0, 0, '', '', 1, '', 0, 0, '', '', 'dolitrashcan@dolitrashcan', '$conf->dolitrashcan->enabled');
		//$result2=$extrafields->addExtraField('dolitrashcan_myattr2', "New Attr 2 label", 'varchar', 1, 10, 'project',      0, 0, '', '', 1, '', 0, 0, '', '', 'dolitrashcan@dolitrashcan', '$conf->dolitrashcan->enabled');
		//$result3=$extrafields->addExtraField('dolitrashcan_myattr3', "New Attr 3 label", 'varchar', 1, 10, 'bank_account', 0, 0, '', '', 1, '', 0, 0, '', '', 'dolitrashcan@dolitrashcan', '$conf->dolitrashcan->enabled');
		//$result4=$extrafields->addExtraField('dolitrashcan_myattr4', "New Attr 4 label", 'select',  1,  3, 'thirdparty',   0, 1, '', ['options'=>['code1'=>'Val1','code2'=>'Val2','code3'=>'Val3')], 1,'', 0, 0, '', '', 'dolitrashcan@dolitrashcan', '$conf->dolitrashcan->enabled');
		//$result5=$extrafields->addExtraField('dolitrashcan_myattr5', "New Attr 5 label", 'text',    1, 10, 'user',         0, 0, '', '', 1, '', 0, 0, '', '', 'dolitrashcan@dolitrashcan', '$conf->dolitrashcan->enabled');

		// Permissions
		$this->remove($options);

		$sql = [];

		return $this->_init($sql, $options);
	}

	/**
	 *  Function called when module is disabled.
	 *  Remove from database constants, boxes and permissions from Dolibarr database.
	 *  Data directories are not deleted
	 *
	 *  @param      string	$options    Options when enabling module ('', 'noboxes')
	 *  @return     int                 1 if OK, 0 if KO
	 */
	public function remove($options = '')
	{
		$sql = [];
		return $this->_remove($sql, $options);
	}
}
