<?php

require_once 'paypalfields.civix.php';

function paypalfields_civicrm_alterPaymentProcessorParams($paymentObj, &$rawParams, &$cookedParams) {
  CRM_Core_Error::debug_log_message(__FUNCTION__ . ' started.');
  $timestamp = microtime();
  CRM_Core_Error::debug_log_message(__FUNCTION__ . ' timestamp: ' . $timestamp);
  $args_log = func_get_args();
  $args_log[1]['credit_card_number'] = '[REDACTED]';
  $args_log[1]['cvv2'] = '[REDACTED]';
  $args_log[1]['credit_card_exp_date'] = '[REDACTED]';
  $args_log[2]['acct'] = '[REDACTED]';
  $args_log[2]['expDate'] = '[REDACTED]';
  $args_log[2]['cvv2'] = '[REDACTED]';
  CRM_Core_Error::debug_log_message(__FUNCTION__ . ' timestamp: ' . $timestamp . '; parameters: ' . json_encode($args_log));

  if (get_class($paymentObj) == 'CRM_Core_Payment_PayPalImpl') {
    $cookedParams['custom'] = $rawParams['financialType_name'];
    $cookedParams_log = $cookedParams;
    $cookedParams_log['acct'] = '[REDACTED]';
    $cookedParams_log['expDate'] = '[REDACTED]';
    $cookedParams_log['cvv2'] = '[REDACTED]';
    CRM_Core_Error::debug_log_message(__FUNCTION__ . ' timestamp: ' . $timestamp . '; altered cookedParams: ' . json_encode($cookedParams_log));
  }
  CRM_Core_Error::debug_log_message(__FUNCTION__ . ' timestamp: ' . $timestamp . '; ended.');
}

/**
 * Implements hook_civicrm_config().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function paypalfields_civicrm_config(&$config) {
  _paypalfields_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_xmlMenu().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_xmlMenu
 */
function paypalfields_civicrm_xmlMenu(&$files) {
  _paypalfields_civix_civicrm_xmlMenu($files);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function paypalfields_civicrm_install() {
  _paypalfields_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_postInstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_postInstall
 */
function paypalfields_civicrm_postInstall() {
  _paypalfields_civix_civicrm_postInstall();
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_uninstall
 */
function paypalfields_civicrm_uninstall() {
  _paypalfields_civix_civicrm_uninstall();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function paypalfields_civicrm_enable() {
  _paypalfields_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_disable
 */
function paypalfields_civicrm_disable() {
  _paypalfields_civix_civicrm_disable();
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_upgrade
 */
function paypalfields_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _paypalfields_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_managed().
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_managed
 */
function paypalfields_civicrm_managed(&$entities) {
  _paypalfields_civix_civicrm_managed($entities);
}

/**
 * Implements hook_civicrm_caseTypes().
 *
 * Generate a list of case-types.
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function paypalfields_civicrm_caseTypes(&$caseTypes) {
  _paypalfields_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implements hook_civicrm_angularModules().
 *
 * Generate a list of Angular modules.
 *
 * Note: This hook only runs in CiviCRM 4.5+. It may
 * use features only available in v4.6+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function paypalfields_civicrm_angularModules(&$angularModules) {
  _paypalfields_civix_civicrm_angularModules($angularModules);
}

/**
 * Implements hook_civicrm_alterSettingsFolders().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_alterSettingsFolders
 */
function paypalfields_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _paypalfields_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

// --- Functions below this ship commented out. Uncomment as required. ---

/**
 * Implements hook_civicrm_preProcess().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_preProcess
 *
  function paypalfields_civicrm_preProcess($formName, &$form) {

  } // */
/**
 * Implements hook_civicrm_navigationMenu().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_navigationMenu
 *
  function paypalfields_civicrm_navigationMenu(&$menu) {
  _paypalfields_civix_insert_navigation_menu($menu, NULL, array(
  'label' => ts('The Page', array('domain' => 'com.joineryhq.paypalfields')),
  'name' => 'the_page',
  'url' => 'civicrm/the-page',
  'permission' => 'access CiviReport,access CiviContribute',
  'operator' => 'OR',
  'separator' => 0,
  ));
  _paypalfields_civix_navigationMenu($menu);
  } // */
