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

function booker_activate() {

}

function booker_deactivate() {

}

function booker_admin() {

}

function booker_shortcode() {

}