<?php
/**
 * LXD Provisioning Module
 * 
 * This module provides the basic functionality needed to provision containers
 * using the LXD API.
 *
 * @see https://github.com/hackman/whmcs_lxd
 *
 * @copyright GPL v2 Marian Marinov 2023
 * @license https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html
 */

if (!defined("WHMCS")) {
    die("This file cannot be accessed directly");
}

require_once __DIR__ . '/lib/lxd.php';

/**
 * Define module related meta data.
 *
 * @return array
 */
function lxd_MetaData() {
    return array(
        'DisplayName' => 'Demo Provisioning Module',
        'APIVersion' => '1.1', // Use API Version 1.1
        'RequiresServer' => true, // Set true if module requires a server to work
        'DefaultNonSSLPort' => '1111', // Default Non-SSL Connection Port
        'DefaultSSLPort' => '1112', // Default SSL Connection Port
        'ServiceSingleSignOnLabel' => 'Login to Panel as User',
        'AdminSingleSignOnLabel' => 'Login to Panel as Admin',
    );
}

/**
 * Define product configuration options.
 *
 * Required fields are:
 * * Hostname
 * * CPU Cores
 * * Memory
 * * Storage
 *
 * @return array
 */
function lxd_ConfigOptions() {
    return array(
        'Hostname' => array(
            'Type' => 'text',
            'Size' => '30',
            'Default' => 'cloud',
            'Description' => 'Enter in desired hostname',
        ),
        'CPU Cores' => array(
            'Type' => 'dropdown',
            'Options' => array(
                '1' => '1',
                '2' => '2',
                '4' => '4',
                '8' => '8',
                '16' => '16',
            ),
            'Description' => 'Number of CPU cores that will be assigned to the container',
        ),
        'Memory' => array(
            'Type' => 'dropdown',
            'Options' => array(
                '1' => '1 GB',
                '2' => '2 GB',
                '3' => '3 GB',
                '4' => '4 GB',
                '8' => '8 GB',
            ),
            'Description' => 'Ammount of memory assigned to the container',
        ),
        'Storage' => array(
            'Type' => 'dropdown',
            'Options' => array(
                '10' => '10 GB',
                '20' => '20 GB',
                '30' => '30 GB',
                '40' => '40 GB',
                '80' => '80 GB',
            ),
            'Description' => 'Ammount of memory assigned to the container',
        )
    );
}

/**
 * Provision a new container instance
 *
 * Attempt to provision a new instance of a given product/service. This is
 * called any time provisioning is requested inside of WHMCS.
 *
 * @return string "success" or an error message
 */
function lxd_CreateAccount(array $params) {
    try {
		lxd_api('create', $params);
    } catch (Exception $e) {
        // Record the error in WHMCS's module log.
        logModuleCall(
            'lxd',
            __FUNCTION__,
            $params,
            $e->getMessage(),
            $e->getTraceAsString()
        );

        return $e->getMessage();
    }

    return 'success';
}

/**
 * Suspend an instance of a product/service.
 *
 * @return string "success" or an error message
 */
function lxd_SuspendAccount(array $params) {
    try {
        // Call the service's suspend function, using the values provided by
        // WHMCS in `$params`.
		lxd_api('suspend', $params);
    } catch (Exception $e) {
        // Record the error in WHMCS's module log.
        logModuleCall(
            'lxd',
            __FUNCTION__,
            $params,
            $e->getMessage(),
            $e->getTraceAsString()
        );

        return $e->getMessage();
    }

    return 'success';
}

/**
 * Un-suspend instance of a product/service.
 *
 * @return string "success" or an error message
 */
function lxd_UnsuspendAccount(array $params) {
    try {
		lxd_api('unsuspend', $params);
    } catch (Exception $e) {
        // Record the error in WHMCS's module log.
        logModuleCall(
            'lxd',
            __FUNCTION__,
            $params,
            $e->getMessage(),
            $e->getTraceAsString()
        );

        return $e->getMessage();
    }

    return 'success';
}

/**
 * Terminate instance of a product/service.
 *
 * @return string "success" or an error message
 */
function lxd_TerminateAccount(array $params) {
    try {
		lxd_api('terminate', $params);
    } catch (Exception $e) {
        // Record the error in WHMCS's module log.
        logModuleCall(
            'lxd',
            __FUNCTION__,
            $params,
            $e->getMessage(),
            $e->getTraceAsString()
        );

        return $e->getMessage();
    }

    return 'success';
}

/**
 * Change the password for an instance of a product/service.
 *
 * @return string "success" or an error message
 */
function lxd_ChangePassword(array $params) {
    try {
        // Call the service's change password function, using the values
        // provided by WHMCS in `$params`.
        //
        // A sample `$params` array may be defined as:
        //
        // ```
        // array(
        //     'username' => 'The service username',
        //     'password' => 'The new service password',
        // )
        // ```
		lxd_api('password', $params);
    } catch (Exception $e) {
        // Record the error in WHMCS's module log.
        logModuleCall(
            'lxd',
            __FUNCTION__,
            $params,
            $e->getMessage(),
            $e->getTraceAsString()
        );

        return $e->getMessage();
    }

    return 'success';
}

/**
 * Upgrade or downgrade an instance of a product/service.
 *
 * @return string "success" or an error message
 */
function lxd_ChangePackage(array $params) {
    try {
		lxd_api('change', $params);
    } catch (Exception $e) {
        // Record the error in WHMCS's module log.
        logModuleCall(
            'lxd',
            __FUNCTION__,
            $params,
            $e->getMessage(),
            $e->getTraceAsString()
        );

        return $e->getMessage();
    }

    return 'success';
}

/**
 * Renew an instance of a product/service.
 *
 * @return string "success" or an error message
 */
function lxd_Renew(array $params) {
	// not implemented
    return 'success';
}

/**
 * Test connection with the given server parameters.
 *
 * @return array
 */
function lxd_TestConnection(array $params) {
    try {
		lxd_api('conn_test', $params);
        $success = true;
        $errorMsg = '';
    } catch (Exception $e) {
        // Record the error in WHMCS's module log.
        logModuleCall(
            'lxd',
            __FUNCTION__,
            $params,
            $e->getMessage(),
            $e->getTraceAsString()
        );

        $success = false;
        $errorMsg = $e->getMessage();
    }

    return array(
        'success' => $success,
        'error' => $errorMsg,
    );
}

/**
 * Additional actions an admin user can invoke.
 *
 * @return array
 */
function lxd_AdminCustomButtonArray() {
    return array(
        "List active containers" => "listActive",
        "List suspended containers" => "listInactive",
    );
}

/**
 * Additional actions a client user can invoke.
 *
 * @return array
 */
function lxd_ClientAreaCustomButtonArray() {
    return array();
}

/**
 * Admin services tab additional fields.
 *
 * @return array
 */
function lxd_AdminServicesTabFields(array $params) {
    try {
        // Call the service's function, using the values provided by WHMCS in
        // `$params`.
        $response = array();

        // Return an array based on the function's response.
        return array(
            'Number of Apples' => (int) $response['numApples'],
            'Number of Oranges' => (int) $response['numOranges'],
            'Last Access Date' => date("Y-m-d H:i:s", $response['lastLoginTimestamp']),
            'Something Editable' => '<input type="hidden" name="provisioningmodule_original_uniquefieldname" '
                . 'value="' . htmlspecialchars($response['textvalue']) . '" />'
                . '<input type="text" name="provisioningmodule_uniquefieldname"'
                . 'value="' . htmlspecialchars($response['textvalue']) . '" />',
        );
    } catch (Exception $e) {
        // Record the error in WHMCS's module log.
        logModuleCall(
            'lxd',
            __FUNCTION__,
            $params,
            $e->getMessage(),
            $e->getTraceAsString()
        );

        // In an error condition, simply return no additional fields to display.
    }

    return array();
}

/**
 * Execute actions upon save of an instance of a product/service.
 *
 * @see lxd_AdminServicesTabFields()
 */
function lxd_AdminServicesTabFieldsSave(array $params) {
    // Fetch form submission variables.
    $originalFieldValue = isset($_REQUEST['provisioningmodule_original_uniquefieldname'])
        ? $_REQUEST['provisioningmodule_original_uniquefieldname']
        : '';

    $newFieldValue = isset($_REQUEST['provisioningmodule_uniquefieldname'])
        ? $_REQUEST['provisioningmodule_uniquefieldname']
        : '';

    // Look for a change in value to avoid making unnecessary service calls.
    if ($originalFieldValue != $newFieldValue) {
        try {
            // Call the service's function, using the values provided by WHMCS
            // in `$params`.
        } catch (Exception $e) {
            // Record the error in WHMCS's module log.
            logModuleCall(
                'lxd',
                __FUNCTION__,
                $params,
                $e->getMessage(),
                $e->getTraceAsString()
            );

            // Otherwise, error conditions are not supported in this operation.
        }
    }
}


/**
 * Client area output logic handling.
 *
 * @return array
 */
function lxd_ClientArea(array $params) {
    // Determine the requested action and set service call parameters based on
    // the action.
    $requestedAction = isset($_REQUEST['customAction']) ? $_REQUEST['customAction'] : '';

    if ($requestedAction == 'manage') {
        $serviceAction = 'get_usage';
        $templateFile = 'templates/manage.tpl';
    } else {
        $serviceAction = 'get_stats';
        $templateFile = 'templates/overview.tpl';
    }

    try {
        // Call the service's function based on the request action, using the
        // values provided by WHMCS in `$params`.
        $response = array();

        $extraVariable1 = 'abc';
        $extraVariable2 = '123';

        return array(
            'tabOverviewReplacementTemplate' => $templateFile,
            'templateVariables' => array(
                'extraVariable1' => $extraVariable1,
                'extraVariable2' => $extraVariable2,
            ),
        );
    } catch (Exception $e) {
        // Record the error in WHMCS's module log.
        logModuleCall(
            'lxd',
            __FUNCTION__,
            $params,
            $e->getMessage(),
            $e->getTraceAsString()
        );

        // In an error condition, display an error page.
        return array(
            'tabOverviewReplacementTemplate' => 'error.tpl',
            'templateVariables' => array(
                'usefulErrorHelper' => $e->getMessage(),
            ),
        );
    }
}
