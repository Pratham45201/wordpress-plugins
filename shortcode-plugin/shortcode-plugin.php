<?php

/**
 * Plugin Name: Plugin with Shortcodes
 * Description: Plugin with shortcodes
 * Author: Pratham Patel
 * Version: 1.0
 * Author URI: https://google.com
 * Plugin URI: https://google.com
 */

// Simple shortcode
function sp_show_static_message()
{
    return "<div><h1>Hello I am a simple shortcode message</h1></div>";
}
add_shortcode("message", "sp_show_static_message");


// Parameterized shortcode
function sp_show_dynamic_message($attributes)
{
    $attributes = shortcode_atts(array(
        "name" => "Default student",
        "email" => "Default email"
    ), $attributes, "student");

    return "<h2>Student data: {$attributes['name']}, {$attributes['email']}</h2>";
}

add_shortcode("student", "sp_show_dynamic_message");


// Shortcode with DB operation
function sp_handle_list_posts()
{
    global $wpdb;
    $table_prefix = $wpdb->prefix;
    $table_name = $table_prefix . "posts";
    $posts = $wpdb->get_results(
        "SELECT post_title from {$table_name} WHERE post_type = 'post' AND post_status = 'publish'"
    );

    if (count($posts) > 0) {
        $outputhtml = "<ul>";
        foreach ($posts as $post) {
            $outputhtml .= "<li>{$post->post_title}</li>";
        }
        $outputhtml .= "<ul>";
        return $outputhtml;
    }
    return 'No post found';
}
add_shortcode("list-posts", "sp_handle_list_posts_wp_query_class");

function sp_handle_list_posts_wp_query_class($attributes){
    $attributes = shortcode_atts(array(
        "number" => 5
    ), $attributes, "list-posts");

    $query = new WP_Query(array(
        "posts_per_page" => $attributes['number'],
        "post_status" => "publish",
    ));

    if($query -> have_posts()){
        $outputhtml = "<ul>";
        while($query->have_posts()){
            $query->the_post(); 
            $outputhtml .= "<li>".get_the_date()."</li>";
        }
        $outputhtml .= "</ul>";
        return $outputhtml;
    }
    return "No posts found";
}
