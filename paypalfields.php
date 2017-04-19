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

  // If this is paypal, add the financial type name to the 'custom' parameter.
  if (get_class($paymentObj) == 'CRM_Core_Payment_PayPalImpl') {
    // If this is a priceset, we should concat the distinct financial types
    // for each selected price field.
    if (!empty($rawParams['priceSetId'])) {
      $financial_type_ids = array();
      // Find params named price_([0-9]+).
      foreach ($rawParams as $paramKey => $paramValue) {
        if (!empty($paramValue) && preg_match('/^price_([0-9]+)$/', $paramKey, $matches)) {
          $price_field_id = $matches[1];
          // If it's an array, treat each array member as a Price Field Value and
          // get its financial type id.
          if (is_array($paramValue)) {
            foreach (array_keys($paramValue) as $price_field_value_id) {
              $result = civicrm_api3('PriceFieldValue', 'get', array(
                'sequential' => 1,
                'id' => $price_field_value_id,
              ));
            }
          }
          // If it's not an array, treat it as a Price Field with a single Price
          // Field Value (e.g., a "Text/Numeric" field, and get the financial
          // type id of that Price Field Value.
          else {
            $result = civicrm_api3('PriceFieldValue', 'get', array(
              'sequential' => 1,
              'price_field_id' => $price_field_id,
            ));
          }
          if (!empty($result['values'][0]['financial_type_id'])) {
            $financial_type_ids[] = $result['values'][0]['financial_type_id'];
          }
        }
      }
      $financial_type_names = array();
      foreach (array_unique($financial_type_ids) as $financial_type_id) {
        $result = civicrm_api3('FinancialType', 'get', array(
          'sequential' => 1,
          'id' => $financial_type_id,
        ));
        if (!empty($result['values'][0]['name'])) {
          $financial_type_names[] = $result['values'][0]['name'];
        }
      }
      sort($financial_type_names);
      $financial_type_name = implode(', ', $financial_type_names);
    }
    // If it's not using a price set, it may simply include its financial type
    // name. If so, use that.
    elseif ($rawParams['financialType_name']) {
      $financial_type_name = $rawParams['financialType_name'];
    }
    // Alternatively, it may include the financial type ID, so get the name
    // based on on that.
    elseif (!empty($rawParams['financial_type_id'])) {
      $result = civicrm_api3('FinancialType', 'get', array(
        'sequential' => 1,
        'id' => $rawParams['financial_type_id'],
      ));
      if (!empty($result['values'][0]['name'])) {
        $financial_type_name = $result['values'][0]['name'];
      }
    }

    $cookedParams['custom'] = $financial_type_name;
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
