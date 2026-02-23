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

/* Ajax Calls */
add_action('wp_ajax_br_get_labels', 'wp_ajax_br_get_labels');
add_action('wp_ajax_nopriv_br_get_labels', 'wp_ajax_br_get_labels');
add_action('wp_ajax_br_update_labels', 'wp_ajax_br_update_labels');
add_action('wp_ajax_br_get_events', 'wp_ajax_br_get_events');
add_action('wp_ajax_nopriv_br_get_events', 'wp_ajax_br_get_events');
add_action('wp_ajax_br_registration', 'wp_ajax_br_registration');
add_action('wp_ajax_nopriv_br_registration', 'wp_ajax_br_registration');
add_action('wp_ajax_br_get_registrations', 'wp_ajax_br_get_registrations');

/* Initialize Booker table requirements upon plugin activation */
register_activation_hook(__FILE__, 'booker_activate');

function booker_activate() {
    if (!wp_ajax_br_init_options(BR_OPTIONS) || !wp_ajax_br_init_registrations()) {
        die('Required Data Initialization Failed!');
    }
}

/* Initialize Booker custom post type & taxonomies */
add_action('init', 'booker_event_init');

function booker_event_init() : void {
    register_post_type('event', BR_POST_TYPE_ARGS);

    register_taxonomy('BookerLocation', 'event',BR_POST_LOCATION_TAXONOMY_ARGS);
    register_taxonomy('BookerType', 'event', BR_POST_TYPE_TAXONOMY_ARGS);
    //register_taxonomy('BookerDate', 'event', BR_POST_DATE_TAXONOMY_ARGS);
    register_taxonomy('BookerCategory', 'event', BR_POST_CATEGORY_TAXONOMY_ARGS);
}

/* Initialize Booker custom post type meta box */
add_action('add_meta_boxes', 'booker_add_meta_boxes');

function booker_add_meta_boxes() {
    add_meta_box(
            'booker_event_date',
            'booker-event-meta-date',
            'booker_event_meta_box',
            'event',
            'normal',
            'high'
    );
}

function booker_event_meta_box () {
    global $post;
    $meta = get_post_meta( $post->ID, 'booker-event-meta-date', true );
    ?>
        <div class="row">
            <div class="col-md-4">
                <input type="hidden" name="booker_meta_box_nonce" value="<?php echo wp_create_nonce( basename(__FILE__) ); ?>">
                <label for="booker-event-meta-date">Event Date</label>
                <input type="date" id="booker-event-meta-date" style="margin-left: 15px; margin-right: 15px;" name="booker-event-meta-date" required value="<?php echo $meta; ?>">
            </div>
        </div>
        <?php
}

function save_your_fields_meta( $post_id ) {
    // verify nonce
    if ( !wp_verify_nonce( $_POST['booker_meta_box_nonce'], basename(__FILE__) ) ) {
        return $post_id;
    }
    // check autosave
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return $post_id;
    }
    // check permissions
    if ( 'page' === $_POST['post_type'] ) {
        if ( !current_user_can( 'edit_page', $post_id ) ) {
            return $post_id;
        } elseif ( !current_user_can( 'edit_post', $post_id ) ) {
            return $post_id;
        }
    }

    $old = get_post_meta( $post_id, 'booker-event-meta-date', true );
    $new = $_POST['booker-event-meta-date'];

    if ( $new && $new !== $old ) {
        update_post_meta( $post_id, 'booker-event-meta-date', $new );
    } elseif ( '' === $new && $old ) {
        delete_post_meta( $post_id, 'booker-event-meta-date', $old );
    }
}
add_action( 'save_post', 'save_your_fields_meta' );

/* Initialize Booker registration form */
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

/* Initialize Booker back end administration page */
add_action('admin_menu', 'booker_admin_menu');

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

/* Initialize Booker shortcode */
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