<?php

/**
 * Plugin Name: Northeastern University Library BB Modules
 * Plugin URI:
 * Description: Custom modules for the Beaver Builder Plugin.
 * Version: 1.0
 * Author: Jeanine Nicole Rodriguez
 * Author URI:
 */
define( 'NULIB_BB_MODULES_DIR', plugin_dir_path( __FILE__ ) );
define( 'NULIB_BB_MODULES_URL', plugins_url( '/', __FILE__ ) );

add_action( 'init', 'nulib_load_modules' );

function nulib_load_modules() {
    if ( class_exists( 'FLBuilder' ) ) {
        require_once 'NuLibBBModules/tables/tables.php';
    }

}
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
