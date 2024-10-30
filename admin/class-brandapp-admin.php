<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://brandapp.io/
 * @since      1.0.0
 *
 * @package    Brandapp
 * @subpackage Brandapp/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Brandapp
 * @subpackage Brandapp/admin
 * @author     Brandapp <wordpress@brandapp.io>
 */
class Brandapp_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		
		add_action( 'admin_menu', 'brandapp_menu' );
		
		/**
		 * @see https://www.inmotionhosting.com/support/edu/wordpress/save-wordpress-plugin-settings-with-form/
		 */
		function brandapp_page_contents() {
			?>
				<iframe 
					src="https://app.brandapp.io"
					frameborder="0" 
					allowfullscreen>
				</iframe>
			<?php
		}

		function brandapp_page_about(){ 
			?>
				<iframe 
					src="https://brandapp.io" 
					frameborder="0" 
					allowfullscreen>
				</iframe>
			<?php 
		}
	   
		function brandapp_menu(){    
			$page_title = 'BrandApp';
			$menu_title = 'BrandApp';   
			$capability = 'manage_options';
			$menu_slug  = 'brandapp';
			$function   = 'brandapp_page_contents';
			$icon_url   = plugin_dir_url( __FILE__ ) . 'css/brandapp_logo_white.svg';
			$position   = 11;
			
			add_menu_page( 
				$page_title,
				$menu_title,
				$capability,
				$menu_slug,
				$function,
				$icon_url,
				$position
			);
			
			add_submenu_page( $menu_slug, 'About', 'About','manage_options', 'brandapp-about', 'brandapp_page_about');
		}
		
		
		add_action( 'wp_loaded', array( $this, 'show_brandapp_installed_warning_on_upload' ) );
	}
	
	

	public function show_brandapp_installed_warning_on_upload() {
		add_action( 'admin_notices', array( $this, 'warning_on_upload' ) );
	}

	public function warning_on_upload() {
		global $pagenow;
		if ( $pagenow != 'upload.php' ) {
			return;
		}

		echo '
			<div class="wrap">
				<div data-dismissible="disable-media-notice-forever" class="notice notice-warning is-dismissible">
					<div style="text-align: center; margin: 2rem auto;">
					
						<h2 style="">Want to create designs?</h2>
						<p style="max-width: 30rem; margin: 1rem auto;">
							The <a href="https://brandapp.io/?utm_source=wp-admin&utm_medium=link&utm_campaign=upload" target="_blank">BrandApp Wordpress Plugin</a> is already installed. 
							Just create / open an existing post or page, and look for the navigation tabs on top. Here you will find "BrandApp".
						</p>
					</div>
				</div>
			</div>
		';
	}

	
	
	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/brandapp-admin.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/brandapp-admin.js', array( 'jquery' ), $this->version, false );

	}

}
