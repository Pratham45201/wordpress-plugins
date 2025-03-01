<?php
class MyEmployees
{
    private $wpdb;
    private $table_name;
    private $table_prefix;
    public function __construct()
    {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->table_prefix = $this->wpdb->prefix; // wp_
        $this->table_name = $this->table_prefix . "employees_table";
    }

    // Create DB Table + WordPress page
    public function callPluginActivationFunctions()
    {
        $collate = $this->wpdb->get_charset_collate();
        $createCommand  = "
             CREATE TABLE `" . $this->table_name . "` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `name` varchar(50) NOT NULL,
                `email` varchar(50) NOT NULL,
                `designation` varchar(50) DEFAULT NULL,
                `profile_image` varchar(220) DEFAULT NULL,
                PRIMARY KEY (`id`)
                ) " . $collate . "";

        require_once(ABSPATH . "/wp-admin/includes/upgrade.php");
        dbDelta($createCommand);

        // WP Page
        $page_title = "Employee CRUD System";
        $page_content = "[wp-employee-form]";

        if (!get_page_by_title($page_title)) {
            wp_insert_post(array(
                "post_title" => $page_title,
                "post_content" => $page_content,
                "post_type" => "page",
                "post_status" => "publish",
            ));
        }
    }

    // Delete DB Table
    public function deleteEmployeesTable()
    {
        $deleteCommand = "
            DROP TABLE IF EXISTS " . $this->table_name . "
        ";

        // DB-Delta command does not support dropping tables it only
        // supports creating and altering tables.
        $this->wpdb->query($deleteCommand);
    }

    // Render employee form layout
    public function createEmployeesForm()
    {
        ob_start();
        include_once WCE_DIR_PATH . "template/employee_form.php";
        $template = ob_get_contents();
        ob_clean();
        return $template;
    }

    // Add CSS / JS
    public function addAssetsToPlugin()
    {
        // Add css file
        wp_enqueue_style("employee-crud-css", WCE_DIR_URL . "assets/style.css");

        // Add validate.min.js
        wp_enqueue_script("employee-crud-validation", WCE_DIR_URL . "assets/validate.min.js", array("jquery"));

        // Add js file
        wp_enqueue_script("employee-crud-js", WCE_DIR_URL . "assets/script.js", array("jquery"), "3.0");

        wp_localize_script("employee-crud-js", "wce_object", array(
            "ajax_url" => admin_url("admin-ajax.php")
        ));
    }

    // Handle AJAX request
    public function handleAddEmployeeFormData()
    {

        $name = sanitize_text_field($_POST["name"]);
        $email = sanitize_text_field($_POST["email"]);
        $designation = sanitize_text_field($_POST["designation"]);

        // Check for file
        $profile_url = "";
        if (isset($_FILES['profile_image']['name'])) {
            // wp_handle_upload returns a url after uploading the file.
            $fileUploaded = wp_handle_upload($_FILES['profile_image'], array("test_form" => false));
            $profile_url = $fileUploaded['url'];
        }

        $this->wpdb->insert($this->table_name, [
            "name" => $name,
            "email" => $email,
            "designation" => $designation,
            "profile_image" => $profile_url, // passing the file url to the database
        ]);

        $employee_id = $this->wpdb->insert_id;

        if ($employee_id > 0) {
            echo json_encode([
                "status" => 1,
                "message" => "Success",
            ]);
        } else {
            echo json_encode([
                "status" => 0,
                "message" => "Failure",
            ]);
        }

        die;
    }

    // Load employees table
    public function handleLoadEmployeesData()
    {
        $employees = $this->wpdb->get_results(
            "SELECT * FROM " . $this->table_name,
            ARRAY_A // Return data from DB in associative array format.
        );

        return wp_send_json([
            "status" => true,
            "message" => "Employees Data",
            "employees" => $employees,
        ]);
    }

    // Delete employee
    public function handleDeleteEmployee()
    {
        $employee_id = $_GET["empId"];
        $this->wpdb->delete($this->table_name, [
            "id" => $employee_id
        ]);

        return wp_send_json([
            "status" => true,
            "message" => "Employee Deleted successfully"
        ]);
    }

    // Get single employee data (for editing)
    public function handleGetSingleEmployee()
    {
        $employee_id = $_GET["empId"];
        if ($employee_id > 0) {
            $employeeData = $this->wpdb->get_row(
                "SELECT * FROM " . $this->table_name . " WHERE id = " . $employee_id,
                ARRAY_A
            );

            return wp_send_json([
                "status" => 1,
                "message" => "Success",
                "data" => $employeeData
            ]);
        } else {
            return wp_send_json([
                "status" => false,
                "message" => "Please pass employee ID",
            ]);
        }
    }

    // Update employee data
    public function handleUpdateEmployeeData()
    {
        $name = sanitize_text_field($_POST["e_name"]);
        $email = sanitize_text_field($_POST["e_email"]);
        $designation = sanitize_text_field($_POST["e_designation"]);
        $id = sanitize_text_field($_POST["e_id"]);

        // Delete original image
        $result = $this->wpdb->get_results(
            "SELECT profile_image FROM " . $this->table_name . " WHERE id=" . $id,
            ARRAY_A
        );

        if (!empty($result) && isset($result[0]["profile_image"])) {
            $currProfileImageUrl = $result[0]['profile_image'];
            if (!empty($currProfileImageUrl)) {
                $path = parse_url($currProfileImageUrl, PHP_URL_PATH); // remove localhost or domain name
                $fullPath = get_home_path() . $path;
                if (file_exists($fullPath)) {
                    unlink($fullPath); // delete image
                }
            }
        }

        // upload image and update db with new url
        $profile_url = "";
        if (isset($_FILES["e_profile_image"]["name"])) {
            $fileUploaded = wp_handle_upload($_FILES['e_profile_image'], array("test_form" => false));
            $profile_url = $fileUploaded["url"];
        }

        $this->wpdb->update($this->table_name, [
            "name" => $name,
            "email" => $email,
            "designation" => $designation,
            "profile_image" => $profile_url,
        ], [
            "id" => $id
        ]);

        return wp_send_json([
            "status" => true,
            "message" => "Employee updated",
        ]);
    }
}
