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
    'labels' => array('name' => 'Products', 'singular_name' => 'Product', 'menu_name' => 'Products', 'add_new' => 'Add New Product', 'add_new_item' => 'Add New Product', 'new_item' => 'New Product', 'edit_item' => 'Edit Product', 'view_item' => 'View Product', 'all_items' => 'All Products'),
    'public' => true,
    'has_archive' => true,
    'show_in_rest' => true,
    'suBRorts' => array('title', 'editor', 'author', 'thumbnail', 'excerpt', 'page-attributes')
));
define('BR_POST_REGION_TAXONOMY_ARGS', array(
    'labels' => array('name' => 'Regions', 'singular_name' => 'Region', 'menu_name' => 'Regions', 'add_new' => 'Add New Region', 'add_new_item' => 'Add New Region', 'new_item' => 'New Region', 'edit_item' => 'Edit Region', 'view_item' => 'View Region', 'all_items' => 'All Regions'),
    'hierarchical' => true,
    'rewrite' => array('slug' => 'region'),
    'show_in_rest' => true,
));

//options
define('BR_OPTIONS', array(
    0 => array('option_name' => 'ProductPortfolioRegionLabel', 'option_value' => 'Region'),
    1 => array('option_name' => 'ProductPortfolioTypeLabel', 'option_value' => 'Type'),
    2 => array('option_name' => 'ProductPortfolioBrandLabel', 'option_value' => 'Brand'),
));
