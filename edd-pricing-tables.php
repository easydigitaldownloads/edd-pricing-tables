<?php
/*
Plugin Name: Easy Digital Downloads - Pricing Tables
Plugin URI: https://easydigitaldownloads.com/
Description: Easily create pricing tables for your downloads
Version: 1.0
Author: Easy Digital Downloads
Author URI: https://easydigitaldownloads.com/
License: GPL-2.0+
License URI: http://www.opensource.org/licenses/gpl-license.php
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;


if ( ! class_exists( 'EDD_Pricing_Tables' ) ) {

	class EDD_Pricing_Tables {

		/**
		 * Holds the instance
		 *
		 * Ensures that only one instance exists in memory at any one
		 * time and it also prevents needing to define globals all over the place.
		 *
		 * TL;DR This is a static property property that holds the singleton instance.
		 *
		 * @var object
		 * @static
		 * @since 1.0
		 */
		private static $instance;

		/**
		 * Plugin Version
		 */
		private $version = '1.0';

		/**
		 * Plugin Title
		 */
		public $title = 'EDD Pricing Tables';

		/**
		 * The frontend instance variable.
		 *
		 * @access public
		 * @since  1.0
		 * @var    object
		 */
		public $frontend;

		/**
		 * Main Instance
		 *
		 * Ensures that only one instance exists in memory at any one
		 * time. Also prevents needing to define globals all over the place.
		 *
		 * @since 1.0
		 *
		 */
		public static function get_instance() {
			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof EDD_Pricing_Tables ) ) {
				self::$instance = new EDD_Pricing_Tables;
				self::$instance->setup_constants();
				self::$instance->includes();
				self::$instance->setup_actions();
				self::$instance->licensing();
				self::$instance->load_textdomain();
				self::$instance->frontend = new EDD_Pricing_Tables_Frontend;
			}

			return self::$instance;
		}


		/**
		 * Constructor Function
		 *
		 * @since 1.0
		 * @access private
		 */
		private function __construct() {
			self::$instance = $this;
		}

		/**
		 * Setup plugin constants
		 *
		 * @access private
		 * @since  1.0
		 *
		 * @return void
		 */
		private function setup_constants() {

			// Plugin version
			if ( ! defined( 'EDD_PT_VERSION' ) ) {
				define( 'EDD_PT_VERSION', $this->version );
			}


			// Plugin Folder Path
			if ( ! defined( 'EDD_PT_PLUGIN_DIR' ) ) {
				define( 'EDD_PT_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
			}

			// Plugin Folder URL
			if ( ! defined( 'EDD_PT_PLUGIN_URL' ) ) {
				define( 'EDD_PT_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
			}

			// Plugin Root File
			if ( ! defined( 'EDD_PT_PLUGIN_FILE' ) ) {
				define( 'EDD_PT_PLUGIN_FILE', __FILE__ );
			}

		}

		/**
		 * Setup the default hooks and actions
		 *
		 * @since 1.0
		 *
		 * @return void
		 */
		private function setup_actions() {

			add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'settings_link' ), 10, 2 );

			do_action( 'edd_pricing_tables_setup_actions' );

		}

		/**
		 * Licensing
		 *
		 * @since 1.0
		*/
		private function licensing() {
			// check if EDD_License class exists
			if ( class_exists( 'EDD_License' ) ) {
				$license = new EDD_License( __FILE__, $this->title, $this->version, 'Andrew Munro' );
			}
		}

		/**
		 * Loads the plugin language files
		 *
		 * @access public
		 * @since 1.0.4
		 * @return void
		 */
		public function load_textdomain() {

			// Set filter for plugin's languages directory
			$lang_dir = dirname( plugin_basename( __FILE__ ) ) . '/languages/';
			$lang_dir = apply_filters( 'edd_pricing_tables_languages_directory', $lang_dir );

			// Traditional WordPress plugin locale filter
			$locale        = apply_filters( 'plugin_locale',  get_locale(), 'edd-pricing-tables' );
			$mofile        = sprintf( '%1$s-%2$s.mo', 'edd-pricing-tables', $locale );

			// Setup paths to current locale file
			$mofile_local  = $lang_dir . $mofile;
			$mofile_global = WP_LANG_DIR . '/edd-pricing-tables/' . $mofile;

			if ( file_exists( $mofile_global ) ) {
				// Look in global /wp-content/languages/edd-pricing-tables/ folder
				load_textdomain( 'edd-pricing-tables', $mofile_global );
			} elseif ( file_exists( $mofile_local ) ) {
				// Look in local /wp-content/plugins/edd-pricing-tables/languages/ folder
				load_textdomain( 'edd-pricing-tables', $mofile_local );
			} else {
				// Load the default language files
				load_plugin_textdomain( 'edd-pricing-tables', false, $lang_dir );
			}
		}

		/**
		 * Include required files.
		 *
		 * @since 1.0
		 *
		 * @return void
		 */
		private function includes() {

			require_once( EDD_PT_PLUGIN_DIR . 'includes/class-shortcodes.php' );
			require_once( EDD_PT_PLUGIN_DIR . 'includes/class-frontend.php' );
			require_once( EDD_PT_PLUGIN_DIR . 'includes/scripts.php' );
			require_once( EDD_PT_PLUGIN_DIR . 'includes/functions.php' );

			if ( is_admin() ) {
				require_once( EDD_PT_PLUGIN_DIR . 'includes/class-admin.php' );
			}

		}

		/**
		 * Plugin settings link
		 *
		 * @since 1.1
		*/
		public function settings_link( $links ) {
			$plugin_links = array(
				'<a href="' . admin_url( 'edit.php?post_type=download&page=edd-settings&tab=extensions' ) . '">' . __( 'Settings', 'edd-pricing-tables' ) . '</a>',
			);

			return array_merge( $plugin_links, $links );
		}

	}

	/**
	 * Loads a single instance
	 *
	 * This follows the PHP singleton design pattern.
	 *
	 * Use this function like you would a global variable, except without needing
	 * to declare the global.
	 *
	 * @example <?php $edd_pricing_tables = edd_pricing_tables(); ?>
	 *
	 * @since 1.0
	 *
	 * @see EDD_Pricing_Tables::get_instance()
	 *
	 * @return object Returns an instance of the EDD_Pricing_Tables class
	 */
	function edd_pricing_tables() {

	    if ( ! class_exists( 'Easy_Digital_Downloads' ) ) {

	        if ( ! class_exists( 'EDD_Extension_Activation' ) ) {
	            require_once 'includes/class-activation.php';
	        }

	        $activation = new EDD_Extension_Activation( plugin_dir_path( __FILE__ ), basename( __FILE__ ) );
	        $activation = $activation->run();

	    } else {
	        return EDD_Pricing_Tables::get_instance();
	    }
	}
	add_action( 'plugins_loaded', 'edd_pricing_tables', apply_filters( 'edd_pricing_tables_action_priority', 10 ) );

}
