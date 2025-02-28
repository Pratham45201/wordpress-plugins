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
}
