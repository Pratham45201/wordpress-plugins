<?php

/**
 * Plugin Name: WP Employees CRUD
 * Description: This plugin performs CRUD operations with employees table.
 * Version: 1.0
 * Author: Pratham Patel
 */

if (!defined("ABSPATH")) {
    exit;
}

define("WCE_DIR_PATH", plugin_dir_path(__FILE__));
define("WCE_DIR_URL", plugin_dir_url(__FILE__));

include_once WCE_DIR_PATH . "MyEmployees.php";

// Create a class object
$employeeObject = new MyEmployees;

// Upon activation the hook will call the method given in the quotes
// using the provided object of that class from which the method belongs.
register_activation_hook(__FILE__, [$employeeObject, "callPluginActivationFunctions"]);

// Deleting table upong deactivation
register_deactivation_hook(__FILE__, [$employeeObject, "deleteEmployeesTable"]);

// Register shortcode
add_shortcode("wp-employee-form", [$employeeObject, "createEmployeesForm"]);

add_action("wp_enqueue_scripts", [$employeeObject, "addAssetsToPlugin"]);

// Process ajax request (only works when user is logged in)
add_action("wp_ajax_wce_add_employee", [$employeeObject, "handleAddEmployeeFormData"]);
add_action("wp_ajax_wce_load_employees_data", [$employeeObject, "handleLoadEmployeesData"]);
add_action("wp_ajax_wce_delete_employee", [$employeeObject, "handleDeleteEmployee"]);
add_action("wp_ajax_wce_get_employee_data", [$employeeObject, "handleGetSingleEmployee"]);
add_action("wp_ajax_wce_edit_employee", [$employeeObject, "handleUpdateEmployeeData"]);

// Make it available to non logged-in users
// add_action("wp_ajax_nonpriv_wce_add_employee", [$employeeObject, "handleAddEmployeeFormData"]);
// add_action("wp_ajax_nonpriv_wce_load_employees_data", [$employeeObject, "handleLoadEmployeesData"]);
// add_action("wp_ajax_nonpriv_wce_delete_employee", [$employeeObject, "handleDeleteEmployee"]);
// add_action("wp_ajax_nonpriv_wce_get_employee_data", [$employeeObject, "handleGetSingleEmployee"]);