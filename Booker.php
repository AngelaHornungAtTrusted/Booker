<?php
/*
 * Plugin Name: Booker
 * Description: Event Management & Booking System
 * Version 0.1
 * Requires at least: 6.0
 * Requires PHP: 8.0.0
 * Author: Angela Hornung
 * Prefix: br
 */

require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'BookerConfig.php');
require_once(BR_ROOT_DIR_PATH . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php');
require_once(BR_UTIL_DIR_PATH . DIRECTORY_SEPARATOR . 'br-ajax.php');

/* Plugin Activation & Installation Management Hooks */
register_activation_hook(__FILE__, 'booker_activate');

/* Actions */
add_action('admin_menu', 'booker_admin_menu');

/* Ajax Calls */
add_action('wp_ajax_br_get_labels', 'wp_ajax_br_get_labels');
add_action('wp_ajax_nopriv_br_get_labels', 'wp_ajax_br_get_labels');
add_action('wp_ajax_br_update_labels', 'wp_ajax_br_update_labels');
add_action('wp_ajax_br_get_events', 'wp_ajax_br_get_events');
add_action('wp_ajax_nopriv_br_get_events', 'wp_ajax_br_get_events');

/* Shortcodes */
add_shortcode('booker', 'booker_shortcode');

function booker_activate() {
    if (!wp_ajax_br_init(BR_OPTIONS)) {
        die('Fatal initialization error');
    }
}

function booker_deactivate() {

}

function booker_admin_menu() {
    add_menu_page(
        'Booker Management',
        'Booker Management',
        'manage_options',
        'br-page',
        'booker_admin_page'
    );
}

function booker_admin_page() {
    ?>
<div class="wrap">
    <?php wp_enqueue_style('boostrap-css', BR_ASSETS_URL . 'css/bootstrap.css'); ?>
    <?php wp_enqueue_script('boostrap-js', BR_ASSETS_URL . 'js/bootstrap.js'); ?>
    <?php include(plugin_dir_path(__FILE__) . 'Admin/admin.php'); ?>
</div>
<?php
}

function booker_shortcode($atts = [], $content = null) : void {
    //embed bootstrap scripts
    wp_enqueue_style('bootstrap-css', PP_ASSETS_URL . '/bootstrap/css/bootstrap.css"');
    wp_enqueue_script('bootstrap-js', PP_ASSETS_URL . '/bootstrap/js/bootstrap.js"');
    ?>
        <div class="wrap">
            <?php include (plugin_dir_path(__FILE__) . 'Shortcode/shortcode.php'); ?>
            <?php wp_enqueue_script('shortcode-js', BR_SHORTCODE_URL . '/shortcode.js'); ?>
        </div>
<?php
}