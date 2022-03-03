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
require_once '../lib/dolitrashcan.lib.php';

// Translations
$langs->loadLangs(array("admin", "dolitrashcan@dolitrashcan"));

// Initialize technical object to manage hooks of page. Note that conf->hooks_modules contains array of hook context
$hookmanager->initHooks(array('dolitrashcansetup', 'globalsetup'));

// Access control
if (!$user->admin) {
	accessforbidden();
}

// Parameters
$action = GETPOST('action', 'aZ09');
$backtopage = GETPOST('backtopage', 'alpha');
// Used by actions_setmoduleoptions.inc.php
$modulepart = GETPOST('modulepart', 'aZ09');

$value = GETPOST('value', 'alpha');
$label = GETPOST('label', 'alpha');

$type = 'myobject';

$arrayofparameters = array(
	'DOLITRASHCAN_MYPARAM1' => array('type' => 'string', 'css' => 'minwidth500', 'enabled' => 1),
	'DOLITRASHCAN_MYPARAM2' => array('type' => 'textarea', 'enabled' => 1),
	//'DOLITRASHCAN_MYPARAM3'=>array('type'=>'category:'.Categorie::TYPE_CUSTOMER, 'enabled'=>1),
	//'DOLITRASHCAN_MYPARAM4'=>array('type'=>'emailtemplate:thirdparty', 'enabled'=>1),
	//'DOLITRASHCAN_MYPARAM5'=>array('type'=>'yesno', 'enabled'=>1),
	//'DOLITRASHCAN_MYPARAM5'=>array('type'=>'thirdparty_type', 'enabled'=>1),
	//'DOLITRASHCAN_MYPARAM6'=>array('type'=>'securekey', 'enabled'=>1),
	//'DOLITRASHCAN_MYPARAM7'=>array('type'=>'product', 'enabled'=>1),
);

$error = 0;
$setupnotempty = 0;

/*
 * Actions
 */

include DOL_DOCUMENT_ROOT . '/core/actions_setmoduleoptions.inc.php';

/*
 * View
 */

$form = new Form($db);

$help_url = '';
$page_name = "DoliTrashCanSetup";

llxHeader('', $langs->trans($page_name), $help_url);

// Subheader
$linkback = '<a href="' . ($backtopage ? $backtopage : DOL_URL_ROOT . '/admin/modules.php?restore_lastsearch_values=1') . '">' . $langs->trans("BackToModuleList") . '</a>';

print load_fiche_titre($langs->trans($page_name), $linkback, 'title_setup');

// Configuration header
$head = dolitrashcanAdminPrepareHead();
print dol_get_fiche_head($head, 'settings', $langs->trans($page_name), -1, "dolitrashcan@dolitrashcan");

// Setup page goes here
echo '<span class="opacitymedium">' . $langs->trans("DoliTrashCanSetupPage") . '</span><br><br>';


if ($action == 'edit') {
	if ($useFormSetup && (float) DOL_VERSION >= 15) {
		print $formSetup->generateOutput(true);
	} else {
		print '<form method="POST" action="' . $_SERVER["PHP_SELF"] . '">';
		print '<input type="hidden" name="token" value="' . newToken() . '">';
		print '<input type="hidden" name="action" value="update">';

		print '<table class="noborder centpercent">';
		print '<tr class="liste_titre"><td class="titlefield">' . $langs->trans("Parameter") . '</td><td>' . $langs->trans("Value") . '</td></tr>';

		foreach ($arrayofparameters as $constname => $val) {
			if ($val['enabled'] == 1) {
				$setupnotempty++;
				print '<tr class="oddeven"><td>';
				$tooltiphelp = (($langs->trans($constname . 'Tooltip') != $constname . 'Tooltip') ? $langs->trans($constname . 'Tooltip') : '');
				print '<span id="helplink' . $constname . '" class="spanforparamtooltip">' . $form->textwithpicto($langs->trans($constname), $tooltiphelp, 1, 'info', '', 0, 3, 'tootips' . $constname) . '</span>';
				print '</td><td>';

				if ($val['type'] == 'textarea') {
					print '<textarea class="flat" name="' . $constname . '" id="' . $constname . '" cols="50" rows="5" wrap="soft">' . "\n";
					print $conf->global->{$constname};
					print "</textarea>\n";
				} elseif ($val['type'] == 'html') {
					require_once DOL_DOCUMENT_ROOT . '/core/class/doleditor.class.php';
					$doleditor = new DolEditor($constname, $conf->global->{$constname}, '', 160, 'dolibarr_notes', '', false, false, $conf->fckeditor->enabled, ROWS_5, '90%');
					$doleditor->Create();
				} elseif ($val['type'] == 'yesno') {
					print $form->selectyesno($constname, $conf->global->{$constname}, 1);
				} elseif (preg_match('/emailtemplate:/', $val['type'])) {
					include_once DOL_DOCUMENT_ROOT . '/core/class/html.formmail.class.php';
					$formmail = new FormMail($db);

					$tmp = explode(':', $val['type']);
					$nboftemplates = $formmail->fetchAllEMailTemplate($tmp[1], $user, null, 1); // We set lang=null to get in priority record with no lang
					//$arraydefaultmessage = $formmail->getEMailTemplate($db, $tmp[1], $user, null, 0, 1, '');
					$arrayofmessagename = [];
					if (is_array($formmail->lines_model)) {
						foreach ($formmail->lines_model as $modelmail) {
							//var_dump($modelmail);
							$moreonlabel = '';
							if (!empty($arrayofmessagename[$modelmail->label])) {
								$moreonlabel = ' <span class="opacitymedium">(' . $langs->trans("SeveralLangugeVariatFound") . ')</span>';
							}
							// The 'label' is the key that is unique if we exclude the language
							$arrayofmessagename[$modelmail->id] = $langs->trans(preg_replace('/\(|\)/', '', $modelmail->label)) . $moreonlabel;
						}
					}
					print $form->selectarray($constname, $arrayofmessagename, $conf->global->{$constname}, 'None', 0, 0, '', 0, 0, 0, '', '', 1);
				} elseif (preg_match('/category:/', $val['type'])) {
					require_once DOL_DOCUMENT_ROOT . '/categories/class/categorie.class.php';
					require_once DOL_DOCUMENT_ROOT . '/core/class/html.formother.class.php';
					$formother = new FormOther($db);

					$tmp = explode(':', $val['type']);
					print img_picto('', 'category', 'class="pictofixedwidth"');
					print $formother->select_categories($tmp[1], $conf->global->{$constname}, $constname, 0, $langs->trans('CustomersProspectsCategoriesShort'));
				} elseif (preg_match('/thirdparty_type/', $val['type'])) {
					require_once DOL_DOCUMENT_ROOT . '/core/class/html.formcompany.class.php';
					$formcompany = new FormCompany($db);
					print $formcompany->selectProspectCustomerType($conf->global->{$constname}, $constname);
				} elseif ($val['type'] == 'securekey') {
					print '<input required="required" type="text" class="flat" id="' . $constname . '" name="' . $constname . '" value="' . (GETPOST($constname, 'alpha') ? GETPOST($constname, 'alpha') : $conf->global->{$constname}) . '" size="40">';
					if (!empty($conf->use_javascript_ajax)) {
						print '&nbsp;' . img_picto($langs->trans('Generate'), 'refresh', 'id="generate_token' . $constname . '" class="linkobject"');
					}
					if (!empty($conf->use_javascript_ajax)) {
						print "\n" . '<script type="text/javascript">';
						print '$(document).ready(function () {
						$("#generate_token' . $constname . '").click(function() {
                	        $.get( "' . DOL_URL_ROOT . '/core/ajax/security.php", {
                		      action: \'getrandompassword\',
                		      generic: true
    				        },
    				        function(token) {
    					       $("#' . $constname . '").val(token);
            				});
                         });
                    });';
						print '</script>';
					}
				} elseif ($val['type'] == 'product') {
					if (!empty($conf->product->enabled) || !empty($conf->service->enabled)) {
						$selected = (empty($conf->global->$constname) ? '' : $conf->global->$constname);
						$form->select_produits($selected, $constname, '', 0);
					}
				} else {
					print '<input name="' . $constname . '"  class="flat ' . (empty($val['css']) ? 'minwidth200' : $val['css']) . '" value="' . $conf->global->{$constname} . '">';
				}
				print '</td></tr>';
			}
		}
		print '</table>';

		print '<br><div class="center">';
		print '<input class="button button-save" type="submit" value="' . $langs->trans("Save") . '">';
		print '</div>';

		print '</form>';
	}

	print '<br>';
} else {
	if (!empty($arrayofparameters)) {
		print '<table class="noborder centpercent">';
		print '<tr class="liste_titre"><td class="titlefield">' . $langs->trans("Parameter") . '</td><td>' . $langs->trans("Value") . '</td></tr>';

		foreach ($arrayofparameters as $constname => $val) {
			if ($val['enabled'] == 1) {
				$setupnotempty++;
				print '<tr class="oddeven"><td>';
				$tooltiphelp = (($langs->trans($constname . 'Tooltip') != $constname . 'Tooltip') ? $langs->trans($constname . 'Tooltip') : '');
				print $form->textwithpicto($langs->trans($constname), $tooltiphelp);
				print '</td><td>';

				if ($val['type'] == 'textarea') {
					print dol_nl2br($conf->global->{$constname});
				} elseif ($val['type'] == 'html') {
					print  $conf->global->{$constname};
				} elseif ($val['type'] == 'yesno') {
					print ajax_constantonoff($constname);
				} elseif (preg_match('/emailtemplate:/', $val['type'])) {
					include_once DOL_DOCUMENT_ROOT . '/core/class/html.formmail.class.php';
					$formmail = new FormMail($db);

					$tmp = explode(':', $val['type']);

					$template = $formmail->getEMailTemplate($db, $tmp[1], $user, $langs, $conf->global->{$constname});
					if ($template < 0) {
						setEventMessages(null, $formmail->errors, 'errors');
					}
					print $langs->trans($template->label);
				} elseif (preg_match('/category:/', $val['type'])) {
					$c = new Categorie($db);
					$result = $c->fetch($conf->global->{$constname});
					if ($result < 0) {
						setEventMessages(null, $c->errors, 'errors');
					} elseif ($result > 0) {
						$ways = $c->print_all_ways(' &gt;&gt; ', 'none', 0, 1); // $ways[0] = "ccc2 >> ccc2a >> ccc2a1" with html formated text
						$toprint = [];
						foreach ($ways as $way) {
							$toprint[] = '<li class="select2-search-choice-dolibarr noborderoncategories"' . ($c->color ? ' style="background: #' . $c->color . ';"' : ' style="background: #bbb"') . '>' . $way . '</li>';
						}
						print '<div class="select2-container-multi-dolibarr" style="width: 90%;"><ul class="select2-choices-dolibarr">' . implode(' ', $toprint) . '</ul></div>';
					}
				} elseif (preg_match('/thirdparty_type/', $val['type'])) {
					if ($conf->global->{$constname} == 2) {
						print $langs->trans("Prospect");
					} elseif ($conf->global->{$constname} == 3) {
						print $langs->trans("ProspectCustomer");
					} elseif ($conf->global->{$constname} == 1) {
						print $langs->trans("Customer");
					} elseif ($conf->global->{$constname} == 0) {
						print $langs->trans("NorProspectNorCustomer");
					}
				} elseif ($val['type'] == 'product') {
					$product = new Product($db);
					$resprod = $product->fetch($conf->global->{$constname});
					if ($resprod > 0) {
						print $product->ref;
					} elseif ($resprod < 0) {
						setEventMessages(null, $object->errors, "errors");
					}
				} else {
					print $conf->global->{$constname};
				}
				print '</td></tr>';
			}
		}

		print '</table>';
	}


	if ($setupnotempty) {
		print '<div class="tabsAction">';
		print '<a class="butAction" href="' . $_SERVER["PHP_SELF"] . '?action=edit&token=' . newToken() . '">' . $langs->trans("Modify") . '</a>';
		print '</div>';
	} else {
		print '<br>' . $langs->trans("NothingToSetup");
	}
}



if (empty($setupnotempty)) {
	print '<br>' . $langs->trans("NothingToSetup");
}

// Page end
print dol_get_fiche_end();

llxFooter();
$db->close();
