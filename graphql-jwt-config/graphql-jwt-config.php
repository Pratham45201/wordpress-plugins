<?php

/**
 * Plugin Name: WPGraphQL JWT Config
 * Description: Sets the secret key and token expiry for WPGraphQL JWT authentication.
 * Version: 1.0
 * Author: YOUR_NAME
 */

if (!defined('ABSPATH')) {
    exit;
}

// Set secret key
add_filter('graphql_jwt_auth_secret_key', function () {
    return 'your_secret';
});

// Set token expiry in seconds
add_filter('graphql_jwt_auth_expire', function () {
    return 3600;
});
