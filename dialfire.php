<?php

require_once 'dialfire.civix.php';
$autoload = __DIR__ . '/vendor/autoload.php';

if (file_exists($autoload)) {
  require_once $autoload;
}

// phpcs:disable
use CRM_Dialfire_ExtensionUtil as E;
// phpcs:enable

Civi::dispatcher()->addListener('civi.invoke.auth', function($e) {
  // dialfire does not support custom headers or POST parameters for webhooks.
  // as authx only takes the token from $_POST when the request method is POST,
  // we need to pretend _authx was sent as a POST parameter
  if (!empty($_GET['_authx']) && implode('/', $e->args) === 'civicrm/dialfire/webhook') {
    $_POST['_authx'] = $_GET['_authx'];
  }
}, 1);

/**
 * Implements hook_civicrm_config().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_config/
 */
function dialfire_civicrm_config(&$config) {
  _dialfire_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_xmlMenu().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_xmlMenu
 */
function dialfire_civicrm_xmlMenu(&$files) {
  _dialfire_civix_civicrm_xmlMenu($files);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_install
 */
function dialfire_civicrm_install() {
  _dialfire_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_postInstall().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_postInstall
 */
function dialfire_civicrm_postInstall() {
  _dialfire_civix_civicrm_postInstall();
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_uninstall
 */
function dialfire_civicrm_uninstall() {
  _dialfire_civix_civicrm_uninstall();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_enable
 */
function dialfire_civicrm_enable() {
  _dialfire_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_disable
 */
function dialfire_civicrm_disable() {
  _dialfire_civix_civicrm_disable();
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_upgrade
 */
function dialfire_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _dialfire_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_managed().
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_managed
 */
function dialfire_civicrm_managed(&$entities) {
  _dialfire_civix_civicrm_managed($entities);
}

/**
 * Implements hook_civicrm_caseTypes().
 *
 * Generate a list of case-types.
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_caseTypes
 */
function dialfire_civicrm_caseTypes(&$caseTypes) {
  _dialfire_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implements hook_civicrm_angularModules().
 *
 * Generate a list of Angular modules.
 *
 * Note: This hook only runs in CiviCRM 4.5+. It may
 * use features only available in v4.6+.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_angularModules
 */
function dialfire_civicrm_angularModules(&$angularModules) {
  _dialfire_civix_civicrm_angularModules($angularModules);
}

/**
 * Implements hook_civicrm_alterSettingsFolders().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_alterSettingsFolders
 */
function dialfire_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _dialfire_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

/**
 * Implements hook_civicrm_entityTypes().
 *
 * Declare entity types provided by this module.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_entityTypes
 */
function dialfire_civicrm_entityTypes(&$entityTypes) {
  _dialfire_civix_civicrm_entityTypes($entityTypes);
}

/**
 * Implements hook_civicrm_themes().
 */
function dialfire_civicrm_themes(&$themes) {
  _dialfire_civix_civicrm_themes($themes);
}

function dialfire_civicrm_permission(&$permissions) {
  $permissions['receive Dialfire webhooks'] = [
    E::ts('Dialfire: Receive Webhooks'),
    E::ts('Receive Webhooks sent by Dialfire'),
  ];
}

// --- Functions below this ship commented out. Uncomment as required. ---

/**
 * Implements hook_civicrm_preProcess().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_preProcess
 */
//function dialfire_civicrm_preProcess($formName, &$form) {
//
//}

/**
 * Implements hook_civicrm_navigationMenu().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_navigationMenu
 */
//function dialfire_civicrm_navigationMenu(&$menu) {
//  _dialfire_civix_insert_navigation_menu($menu, 'Mailings', array(
//    'label' => E::ts('New subliminal message'),
//    'name' => 'mailing_subliminal_message',
//    'url' => 'civicrm/mailing/subliminal',
//    'permission' => 'access CiviMail',
//    'operator' => 'OR',
//    'separator' => 0,
//  ));
//  _dialfire_civix_navigationMenu($menu);
//}
