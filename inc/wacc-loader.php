<?php

namespace WhatsAppChatChitavo\Inc;

defined( 'ABSPATH' ) || exit; // Prevent direct access

if ( !class_exists( 'WACCLoader' ) ) {
    class WACCLoader {

        public function __construct()
        {
            include_once( WACC_PATH . 'inc/main/wacc-menu.php' );
            include_once( WACC_PATH . 'inc/main/wacc-resources.php' );
            include_once( WACC_PATH . 'inc/wacc-helper.php' );

            // Ajax
            include_once( WACC_PATH . 'inc/ajax/wacc-license.php' );
            include_once( WACC_PATH . 'inc/ajax/wacc-save.php' );
            include_once( WACC_PATH . 'inc/ajax/wacc-delete.php' );

            add_action( 'admin_menu', array( 'WhatsAppChatChitavo\Inc\Main\WACCMenu', 'add_menu' ) ); // Add plugin menus
            add_action( 'admin_enqueue_scripts', array( 'WhatsAppChatChitavo\Inc\Main\WACCResources', 'admin_resources' ) ); // Register plugin admin resources
            add_action( 'admin_enqueue_scripts', array( 'WhatsAppChatChitavo\Inc\Main\WACCResources', 'resources_translation' ) ); // Register plugin resources translation
            add_action( 'wp_enqueue_scripts', array( 'WhatsAppChatChitavo\Inc\Main\WACCResources', 'front_resources' ) ); // Register plugin front resources
            add_action( 'wp_enqueue_scripts', array( 'WhatsAppChatChitavo\Inc\Main\WACCResources', 'resources_translation' ) ); // Register plugin resources translation

            // Ajax
            add_action( 'wp_ajax_verify_purchase_code', array( 'WhatsAppChatChitavo\Inc\Ajax\WACCLicense', 'handler' ) ); // Verify envato purchase code ajax callback
            add_action( 'wp_ajax_wacc_save', array( 'WhatsAppChatChitavo\Inc\Ajax\WACCSave', 'handler' ) ); // Save data to db ajax callback
            add_action( 'wp_ajax_wacc_delete', array( 'WhatsAppChatChitavo\Inc\Ajax\WACCDelete', 'handler' ) ); // Delete data from db ajax callback

            $license_validity = get_option( 'dFjYsaWNfaXN' );
            if( empty( $license_validity ) || $license_validity == false )
            {
                add_action( 'admin_notices', array( self::class, 'admin_notice_error' ) );
            }else {
                add_action( 'wp_footer', array( self::class, 'show_widget' ) );
            }
        }

        public static function admin_notice_error()
        {
            if( isset( $_GET['page'] ) && str_contains( $_GET['page'], 'wacc' ) )
                return;

            $class = 'notice notice-error';
            $message = sprintf(
                __( 'WathApp Chat Chitavo(WACC) not activated yet. Click <a href="%s">here</a> to activate', 'wacc' ),
                admin_url( 'admin.php?page=wacc-license' )
            );

            printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), $message );
        }

        public static function show_widget()
        {
            $plugin_settings = get_option( 'wacc-settings' );

            if( self::check_page_condition( $plugin_settings ) &&
                self::check_device_condition( $plugin_settings ) &&
                self::check_visitor_condition( $plugin_settings ) )
            {
                include_once( WACC_PATH . 'views/front/widget-' . $plugin_settings['theme'] . '.php' );
            }
        }

        private static function check_page_condition( $plugin_settings )
        {
            return ( ! empty( $plugin_settings ) &&
                    ( isset( $plugin_settings['visible_in_pages'] ) &&
                    ( $plugin_settings['visible_in_pages'] === 'all' ||
                    in_array( get_the_ID(), explode( ',', $plugin_settings['visible_in_pages'] ) ) ) ) );
        }

        private static function check_device_condition( $plugin_settings )
        {
            return ( isset( $plugin_settings['visible_for_device_type'] ) &&
                    ( $plugin_settings['visible_for_device_type'] === 'all' ||
                    ( in_array( 'mobile', explode( ',', $plugin_settings['visible_for_device_type'] ) ) && wp_is_mobile() ) ||
                    ( in_array( 'pc', explode( ',', $plugin_settings['visible_for_device_type'] ) ) && ! wp_is_mobile() ) ) );
        }

        private static function check_visitor_condition( $plugin_settings )
        {
            $role_exist = false;

            $visitor_roles = self::wacc_get_current_user_roles();

            foreach( $visitor_roles as $visitor_role )
            {
                if( in_array( $visitor_role, explode( ',', $plugin_settings['visible_for_visitors'] ) ) )
                {
                    $role_exist = true;
                }
            }
            return ( isset( $plugin_settings['visible_for_visitors'] ) &&
                    ( $plugin_settings['visible_for_visitors'] === 'all' ||
                    $role_exist ) );
        }

        private static function wacc_get_current_user_roles()
        {
            if( is_user_logged_in() )
            {
                $user = wp_get_current_user();
                $roles = ( array ) $user->roles;
                return $roles;
            }
            return array( 'guest' );
        }
    }
}