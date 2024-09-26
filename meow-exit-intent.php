<?php
/*
Plugin Name: Meow Exit Intent
Plugin URI: https://github.com/jordymeow/meow-exit-intent
Description: A simple and customizable exit intent popup for WordPress.
Version: 0.0.1
Author: Jordy Meow
Author URI: https://meowapps.com
License: MIT
License URI: https://opensource.org/licenses/MIT
Text Domain: meow-exit-intent
Domain Path: /languages
*/

if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}

// Include core and admin files
include_once plugin_dir_path( __FILE__ ) . 'core.php';
include_once plugin_dir_path( __FILE__ ) . 'admin.php';
