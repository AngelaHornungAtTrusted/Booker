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
add_action('init', 'booker_event_init');

/* Actions */
add_action('admin_menu', 'booker_admin_menu');

/* Ajax Calls */
add_action('wp_ajax_br_get_labels', 'wp_ajax_br_get_labels');
add_action('wp_ajax_nopriv_br_get_labels', 'wp_ajax_br_get_labels');
add_action('wp_ajax_br_update_labels', 'wp_ajax_br_update_labels');
add_action('wp_ajax_br_get_events', 'wp_ajax_br_get_events');
add_action('wp_ajax_nopriv_br_get_events', 'wp_ajax_br_get_events');
add_action('wp_ajax_br_registration', 'wp_ajax_br_registration');
add_action('wp_ajax_nopriv_br_registration', 'wp_ajax_br_registration');
add_action('wp_ajax_br_get_registrations', 'wp_ajax_br_get_registrations');

/* Shortcodes */
add_shortcode('booker', 'booker_shortcode');

/* Filters */
add_filter('the_content', 'booker_append_registration', 20);

function booker_append_registration($content) {
    /* todo, stop from appending on every single page/plugin */
    $post_type = "event";

    if (is_singular('event') && in_the_loop() && is_main_query()) {
        /* Bootstrap */
        wp_enqueue_style('bootstrap-css', PP_ASSETS_URL . '/bootstrap/css/bootstrap.css"');
        wp_enqueue_script('bootstrap-js', PP_ASSETS_URL . '/bootstrap/js/bootstrap.js"');

        /* jQuery Validate */
        wp_enqueue_script('jquery-validate', PP_ASSETS_URL . '/validate/jquery.validate.min.js');

        /* Toastr.js */
        wp_enqueue_style('toastr-css', PP_ASSETS_URL . '/toastr/build/toastr.css');
        wp_enqueue_script('toastr-js', PP_ASSETS_URL . '/toastr/build/toastr.min.js');

        return $content . include(plugin_dir_path(__FILE__) . 'registration/registration.php');
    }

    return $content;
}

function booker_activate() {
    if (!wp_ajax_br_init_options(BR_OPTIONS) || !wp_ajax_br_init_registrations()) {
        die('Required Data Initialization Failed!');
    }
}

function booker_deactivate() {

}

function booker_event_init() : void {
    register_post_type('event', BR_POST_TYPE_ARGS);

    register_taxonomy('BookerLocation', 'event',BR_POST_LOCATION_TAXONOMY_ARGS);
    register_taxonomy('BookerType', 'event', BR_POST_TYPE_TAXONOMY_ARGS);
    register_taxonomy('BookerDate', 'event', BR_POST_DATE_TAXONOMY_ARGS);
    register_taxonomy('BookerCategory', 'event', BR_POST_CATEGORY_TAXONOMY_ARGS);
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
    <?php include(plugin_dir_path(__FILE__) . 'admin/admin.php'); ?>
</div>
<?php
}

add_shortcode('booker', 'br_shortcode');

function br_shortcode($atts = [], $content = null) : void {
    if (sizeOf($atts) > 5) {
        //used to determine what we load and present
        $locationId = intval($atts[0]);
        $typeId = intval($atts[1]);
        $dateId = intval($atts[2]);
        $categoryId = intval($atts[3]);
        $hideSelect  = intval($atts[4]);
        $style = intval($atts[5]);

        //todo fix so not needed in two places
        wp_enqueue_style('bootstrap-css', PP_ASSETS_URL . '/bootstrap/css/bootstrap.css"');
        wp_enqueue_script('bootstrap-js', PP_ASSETS_URL . '/bootstrap/js/bootstrap.js"');
        ?>
        <div class="wrap">
            <?php include (plugin_dir_path( __FILE__ ) . 'shortcode/shortcode.php'); ?>
            <?php wp_enqueue_script('shortcode-js', BR_SHORTCODE_URL . '/shortcode.js"', array('jquery')); ?>
        </div>
        <?php
    } else {
        ?>
        <div class="wrap">
            <h2>Missing Parameters</h2>
            <p>The shortcode template is as follows: [booker region_id type_id brand_id custom_id, hide_select]</p>
            <p>If you want all regions, types and or brands to present and an associated select for filtering, insert
                a zero for the id in question.</p>
        </div>
        <?php
    }
}