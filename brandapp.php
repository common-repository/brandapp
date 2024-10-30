<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://brandapp.io/
 * @since             1.0.0
 * @package           BrandApp
 *
 * @wordpress-plugin
 * Plugin Name:       BrandApp
 * Plugin URI:        https://brandapp.io/brandapp-for-wordpress
 * Description:       Design images for blog posts, social media, posters and ads, right here inside Wordpress Admin. We believe anyone can master design and if you are stuck, you can reach out to our partners for help. Try BrandApp for free.
 * Version:           1.0.0
 * Author:            BrandApp
 * Author URI:        https://brandapp.io/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'BRANDAPP_VERSION', '1.0.0' );


/**
 * Plugin Compatibility (START)
 * 
 */

    /**
     * Elementor Support
     */
    add_action('elementor/editor/before_enqueue_scripts', function() {
        wp_enqueue_style( 'brandapp', plugin_dir_url( __FILE__ ) . 'admin/css/brandapp-admin.css', array(), BRANDAPP_VERSION, 'all' );
        wp_enqueue_script( 'brandapp', plugin_dir_url( __FILE__ ) . 'admin/js/brandapp-admin.js', array( 'jquery' ), BRANDAPP_VERSION, false );
    });
	
    /**
     * Beaver Builder Support
     */
    add_action('fl_builder_layout_style_dependencies', function() {
        wp_enqueue_style( 'brandapp', plugin_dir_url( __FILE__ ) . 'admin/css/brandapp-admin.css', array(), BRANDAPP_VERSION, 'all' );
        wp_enqueue_script( 'brandapp', plugin_dir_url( __FILE__ ) . 'admin/js/brandapp-admin.js', array( 'jquery' ), BRANDAPP_VERSION, false );
    });

    /**
     * Divi Builder Visual Builder
     */
    add_action('et_fb_framework_loaded', function() {
        wp_enqueue_style( 'brandapp', plugin_dir_url( __FILE__ ) . 'admin/css/brandapp-admin.css', array(), BRANDAPP_VERSION, 'all' );
        wp_enqueue_script( 'brandapp', plugin_dir_url( __FILE__ ) . 'admin/js/brandapp-admin.js', array( 'jquery' ), BRANDAPP_VERSION, false );
    });

    /**
     * Oxygen
     */
    if (isset($_GET['ct_builder']) === true) {
        wp_enqueue_style( 'brandapp', plugin_dir_url( __FILE__ ) . 'admin/css/brandapp-admin.css', array(), BRANDAPP_VERSION, 'all' );
        wp_enqueue_script( 'brandapp', plugin_dir_url( __FILE__ ) . 'admin/js/brandapp-admin.js', array( 'jquery' ), BRANDAPP_VERSION, false );
    }

/*
 * Plugin Compatibility (END)
 * 
 */

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-brandapp-activator.php
 */
function activate_brandapp() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-brandapp-activator.php';
	Brandapp_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-brandapp-deactivator.php
 */
function deactivate_brandapp() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-brandapp-deactivator.php';
	Brandapp_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_brandapp' );
register_deactivation_hook( __FILE__, 'deactivate_brandapp' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-brandapp.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_brandapp() {
	$plugin = new Brandapp();
	$plugin->run();

}
run_brandapp();
