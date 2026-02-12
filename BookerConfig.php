<?php
/* Global General Config */
define('BR_PLUGIN_NAME', 'Booker');
define('BR_PLUGIN_SLUG', 'booker');

/* Global File Paths */
define('BR_ROOT_DIR_NAME', 'booker');
define('BR_ROOT_DIR_PATH', plugin_dir_path(__FILE__));
define('BR_ADMIN_DIR_PATH', BR_ROOT_DIR_PATH . 'Admin');
define('BR_ASSETS_DIR_PATH', BR_ROOT_DIR_PATH . 'Assets');
define('BR_SHORTCODE_DIR_PATH', BR_ROOT_DIR_PATH . 'Shortcode');
define('BR_UTIL_DIR_PATH', BR_ROOT_DIR_PATH . 'Util');

/* Global Directory Urls */
define('BR_ROOT_DIR_URL', plugin_dir_url(__FILE__));
define('BR_ADMIN_URL', BR_ROOT_DIR_URL . 'Admin');
define('BR_ASSETS_URL', BR_ROOT_DIR_URL . 'Assets');
define('BR_SHORTCODE_URL', BR_ROOT_DIR_URL . 'Shortcode');
define('BR_UTIL_URL', BR_ROOT_DIR_URL . 'Util');

/* Global Database Details */
global $wpdb;
define('BR_PLUGIN_PREFIX', 'br');
define('BR_DB_PREFIX', 'br_');

//data constants
define('BR_POST_TYPE_ARGS', array(
    'labels' => array('name' => 'Events', 'singular_name' => 'Event', 'menu_name' => 'Events', 'add_new' => 'Plan An Event', 'add_new_item' => 'Plan An Event', 'new_item' => 'New Event', 'edit_item' => 'Edit Event', 'view_item' => 'View Event', 'all_items' => 'All Events'),
    'public' => true,
    'has_archive' => true,
    'show_in_rest' => true,
    'suBRorts' => array('title', 'editor', 'author', 'thumbnail', 'excerpt', 'page-attributes')
));
define('BR_POST_LOCATION_TAXONOMY_ARGS', array(
    'labels' => array('name' => 'Locations', 'singular_name' => 'Location', 'menu_name' => 'Locations', 'add_new' => 'Add New Location', 'add_new_item' => 'Add New Location', 'new_item' => 'New Location', 'edit_item' => 'Edit Location', 'view_item' => 'View Location', 'all_items' => 'All Locations'),
    'hierarchical' => true,
    'rewrite' => array('slug' => 'location'),
    'show_in_rest' => true,
));
define('BR_POST_TYPE_TAXONOMY_ARGS', array(
    'labels' => array('name' => 'Type', 'singular_name' => 'Type', 'menu_name' => 'Type', 'add_new' => 'Add New Type', 'add_new_item' => 'Add New Type', 'new_item' => 'New Type', 'edit_item' => 'Edit Type', 'view_item' => 'View Type', 'all_items' => 'All Type'),
    'hierarchical' => true,
    'rewrite' => array('slug' => 'type'),
    'show_in_rest' => true,
));
define('BR_POST_DATE_TAXONOMY_ARGS', array(
    'labels' => array('name' => 'Dates', 'singular_name' => 'Date', 'menu_name' => 'Type', 'add_new' => 'Add New Date', 'add_new_item' => 'Add New Date', 'new_item' => 'New Date', 'edit_item' => 'Edit Date', 'view_item' => 'View Date', 'all_items' => 'All Dates'),
    'hierarchical' => true,
    'rewrite' => array('slug' => 'date'),
    'show_in_rest' => true,
));
define('BR_POST_CATEGORY_TAXONOMY_ARGS', array(
    'labels' => array('name' => 'Categories', 'singular_name' => 'Category', 'menu_name' => 'Type', 'add_new' => 'Add New Category', 'add_new_item' => 'Add New Category', 'new_item' => 'New Category', 'edit_item' => 'Edit Category', 'view_item' => 'View Category', 'all_items' => 'All Categories'),
    'hierarchical' => true,
    'rewrite' => array('slug' => 'category'),
    'show_in_rest' => true,
));

//options
define('BR_OPTIONS', array(
    0 => array('option_name' => 'BookerLabelLocation', 'option_value' => 'Location'),
    1 => array('option_name' => 'BookerLabelType', 'option_value' => 'Type'),
    2 => array('option_name' => 'BookerLabelDate', 'option_value' => 'Date'),
));
