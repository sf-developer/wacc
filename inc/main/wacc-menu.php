<?php

namespace WhatsAppChatChitavo\Inc\Main;

defined( 'ABSPATH' ) || exit; // Prevent direct access

if ( !class_exists( 'WACCMenu' ) ) {

    class WACCMenu {

        /**
         * Add plugin menu
         *
         * @return void
         */
        public static function add_menu(): void
        {
            global $wacc_page_hook_suffix;

            // Edit widget menu
            $wacc_page_hook_suffix = add_menu_page(
                __( 'WhatsApp Chat Chitavo', 'wacc' ),
                __( 'WACC', 'wacc' ),
                'publish_posts',
                'wacc-edit-widget',
                array( self::class, 'wacc_render_page' ),
                'dashicons-whatsapp',
                999
            );
            // Support submenu
            add_submenu_page(
                'wacc-edit-widget',
                __( 'Support', 'wacc' ),
                __( 'Support', 'wacc' ),
                'publish_posts',
                'wacc-support',
                array( self::class, 'wacc_render_page' )
            );
            // License submenu
            add_submenu_page(
                'wacc-edit-widget',
                __( 'License', 'wacc' ),
                __( 'License', 'wacc' ),
                'publish_posts',
                'wacc-license',
                array( self::class, 'wacc_render_page' )
            );
        }

        /**
         * Remove plugin namespace
         *
         * @param string plugin namespace
         * @return string remove wacc- from plugin namespace and return it
         */
        private static function wacc_remove_plugin_namespace( $string ): string
        {
            return str_replace( 'wacc-', '', $string );
        }

        /**
         * Render setting page based on plugin namespace
         *
         * @return void
         */
        public static function wacc_render_page(): void
        {
            $page = $_GET['page']; // Get current setting page
            $page = self::wacc_remove_plugin_namespace( $page ); // Remove plugin namespace form page name
            include( sprintf( "%sviews/admin/%s.php", WACC_PATH, $page ) ); // Include views
        }
    }
}