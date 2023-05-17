<?php
/*
Plugin Name: Whatsapp chat
Plugin URI: https://chitavo.com
Requires PHP: 7.4
Description: This plugin create a whatsapp chat widget on your website
Version: 1.0.0
Tested up to: 6.2
Author: Chitavo.com
Author URI: https://chitavo.com
Text Domain: wacc
Domain Path: /languages
License: GPL2
*/

namespace WhatsAppChatChitavo;

use WhatsAppChatChitavo\Inc\WACCLoader;

defined( 'ABSPATH' ) || exit; // Prevent direct access

if ( !class_exists( 'WACCInit' ) ) {
    final class WACCInit {

        public function __construct()
        {
            defined( 'WACC_PATH' ) or define( 'WACC_PATH', plugin_dir_path( __FILE__ ) ); // Plugin path
            defined( 'WACC_URL' ) or define( 'WACC_URL', plugin_dir_url( __FILE__ ) ); // Plugin url

            include_once( WACC_PATH . 'inc/main/wacc-database.php' );
            register_activation_hook( __FILE__, array( 'WhatsAppChatChitavo\Inc\Main\WACCDataBase', 'add_tables' ) );
            register_activation_hook( __FILE__, array( 'WhatsAppChatChitavo\Inc\Main\WACCDataBase', 'add_settings' ) );

            add_action( 'plugins_loaded', array( $this, 'i18n' ) ); // Localization
            add_action( 'plugins_loaded', array( $this, 'init' ) ); // Initializing
        }

        /**
         * Localize plugin
         *
         * @return void
         */
        public function i18n(): void
        {
            load_plugin_textdomain( 'wacc', false, plugin_basename( __DIR__ ) . '/languages' );
        }

        /**
         * Initializing the plugin
         *
         * @return void
         */
        public function init(): void
        {
            include_once WACC_PATH . 'inc/wacc-loader.php';
            new WACCLoader();
        }
    }
}
new WACCInit();