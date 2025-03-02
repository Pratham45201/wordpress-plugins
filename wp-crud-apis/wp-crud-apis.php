<?

/**
 * Plugin Name: WP API for CRUD
 * Description: This plugin enables API endpoints to perform CRUD operation
 * Version : 1.0
 * Author: Pratham Patel
 */

if (!defined("WPINC")) exit;


// Create table on plugin activation
register_activation_hook(__FILE__, "wcp_create_student_table");
function wcp_create_student_table()
{
    global $wpdb;
    $table_name = $wpdb->prefix . "students_table";
    $collate = $wpdb->get_charset_collate();
    $students_table = "
        CREATE TABLE `" . $table_name . "` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `name` varchar(50) NOT NULL,
        `email` varchar(50) NOT NULL,
        `phone` varchar(25) DEFAULT NULL,
        PRIMARY KEY (`id`)
        ) " . $collate . "
    ";

    include_once ABSPATH . "wp-admin/includes/upgrade.php";
    dbDelta($students_table);
}

add_action("rest_api_init", function () {


    // Add students (CREATE)
    register_rest_route("students/v1", "student", array(
        "methods" => "POST",
        "callback" => "wcp_handle_insert_students_routes",
        "args" => array(
            "name" => array(
                "type" => "string",
                "required" => true,
            ),
            "email" => array(
                "type" => "string",
                "required" => true,
            ),
            "phone" => array(
                "type" => "string",
                "required" => false,
            )
        )
    ));

    // List students (READ)
    register_rest_route("students/v1", "students", array(
        "methods" => "GET",
        "callback" => "wcp_handle_get_students_routes",
    ));

    // Update student (UPDATE)
    register_rest_route("students/v1", "student/(?P<id>\d+)", array(
        "methods" => "PUT",
        "callback" => "wcp_handle_update_student_route",
        "args" => array(
            "name" => array(
                "type" => "string",
                "required" => true,
            ),
            "email" => array(
                "type" => "string",
                "required" => true,
            ),
            "phone" => array(
                "type" => "string",
                "required" => false,
            )
        )
    ));

    // Delete student (DELETE)
    register_rest_route("students/v1", "student/(?P<id>\d+)", array(
        "methods" => "DELETE",
        "callback" => "wcp_handle_delete_student_route",
    ));
});

function wcp_handle_get_students_routes()
{

    global $wpdb;
    $table_name = $wpdb->prefix . "students_table";
    $students = $wpdb->get_results(
        "SELECT * FROM " . $table_name,
        ARRAY_A
    );

    return rest_ensure_response([
        "status" => true,
        "message" => "Students list",
        "data" => $students
    ]);
}

function wcp_handle_insert_students_routes($request)
{

    global $wpdb;
    $table_name = $wpdb->prefix . "students_table";
    $wpdb->insert($table_name, array(
        "name" => $request->get_param("name"),
        "email" => $request->get_param("email"),
        "phone" => $request->get_param("phone"),
    ));

    if ($wpdb->insert_id > 0) {

        return rest_ensure_response([
            "status" => true,
            "message" => "student Created Successfully",
            "data" => $request->get_params()
        ]);
    } else {

        return rest_ensure_response([
            "status" => false,
            "message" => "Failed to create student",
            "data" => $request->get_params()
        ]);
    }
}

function wcp_handle_update_student_route($request)
{
    global $wpdb;
    $table_name = $wpdb->prefix . "students_table";
    $id = $request["id"];
    $student = $wpdb->get_row(
        "SELECT * FROM " . $table_name . " WHERE id = " . $id
    );

    if (!empty($student)) {
        $wpdb->update($table_name, [
            "name" => $request->get_param("name"),
            "email" => $request->get_param("email"),
        ], [
            "id" => $id
        ]);

        return rest_ensure_response([
            "status" => true,
            "message" => "Student updated successfully"
        ]);
    } else {
        return rest_ensure_response([
            "status" => false,
            "message" => "Error updating student data"
        ]);
    }
}

function wcp_handle_delete_student_route($reqeust){
    global $wpdb;
    $table_name = $wpdb->prefix . "students_table";
    $id = $reqeust["id"];
    $student = $wpdb->get_row(
        "SELECT * FROM " . $table_name . " WHERE id = " . $id,
    );

    if(!empty($student)){
        $wpdb->delete($table_name, [
            "id" => $id
        ]);

        return rest_ensure_response([
            "status" => true,
            "message" => "Student deleted successfully",
        ]);
    } else {
        return rest_ensure_response([
            "status" => false,
            "message" => "Error deleting student data",
        ]);
    }
}
