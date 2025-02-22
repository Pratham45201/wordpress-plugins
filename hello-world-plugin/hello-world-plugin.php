<?php

/**
 * Plugin Name: Hello World
 * Description: This is our first plugin which creates some information widget to admin dashboard as well as at admin notice
 * Author: Pratham Patel
 * Version: 1.0
 * Author URI: https://google.com
 * Plugin URI: https://google.com
 */

// Admin notices
function hw_show_message()
{
    echo '<div class="notice notice-warning is-dismissible"><p>Hello, I am a success message!</p></div>';
}
add_action("admin_notices", "hw_show_message");


// Admin dashboard widget
function hw_custom_admin_widget(){
    echo "This is a custom admin widget";
}
function hw_hello_world_dashboard_widget() {
    wp_add_dashboard_widget("hw_hello_world", "HW - Hello World Widget", "hw_custom_admin_widget");
}
add_action("wp_dashboard_setup", "hw_hello_world_dashboard_widget");