<?php

namespace WhatsAppChatChitavo\Inc\Main;

defined( 'ABSPATH' ) || exit; // Prevent direct access

if ( !class_exists( 'WACCDataBase' ) ) {

    class WACCDataBase {

        /**
         * Create database tables on plugin activation
         *
         * @return void
         */
        public static function add_tables(): void
        {
            global $wpdb;
            $table_prefix = $wpdb->prefix; // Get tables prefix
            $charset_collate = $wpdb->get_charset_collate(); // Get table charset collate
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php'); // For calling dbDelta function

            /* <------------------------------- Create agents table -------------------------------> */
            $table_name = $table_prefix . 'wacc_agents';
            $agents_table = "CREATE TABLE IF NOT EXISTS $table_name (

                `ID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
                `name` varchar(255) NOT NULL DEFAULT '',
                `position` varchar(255) NOT NULL DEFAULT '',
                `phone` varchar(20) NOT NULL DEFAULT '',
                `avatar` varchar(255) DEFAULT '" . WACC_URL . 'assets/img/avatars/avatar.png' . "',
                `working_time` longtext NOT NULL,
                `registrar` bigint(20) NOT NULL DEFAULT 0,
                `updater` bigint(20) NOT NULL DEFAULT 0,
                `creation_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
                `update_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',

                PRIMARY KEY  (`ID`)
            ) $charset_collate;";

            dbDelta($agents_table); // Create agents table
        }

        /**
         * Add initialze settings on plugin activation
         *
         * @return void
         */
        public static function add_settings(): void
        {
            $settings = get_option('wacc-settings');
            $license_validation = get_option('dFjYsaWNfaXN');
            if( empty( $settings ) )
            {
                $admin_email = get_option( 'admin_email' );
                $args = array(
                    'chat_icon' => 'icon12.svg',
                    'show_icon_text' => '1',
                    'icon_text' => __( 'Text us', 'wacc' ),
                    'icon_position' => 'right',
                    'chat_header_logo' => WACC_URL . 'assets/img/wacc-logo.png',
                    'chat_header_text' => __( 'Hi Click one of our members below to chat on <b>WhatsApp</b> ;)', 'wacc' ),
                    'chat_footer_text' => __( 'Powered by Chitavo.com', 'wacc' ),
                    'play_sound_on_popup' => true,
                    'popup_sound' => 'welcome-audio-1.mp3',
                    'email_button' => '1',
                    'email_address' => isset( $admin_email ) ? $admin_email : '',
                    'faq_button' => '1',
                    'faq_url' => '',
                    'theme' => 'theme-1',
                    'visible_date' => 'all',
                    'visible_for_device_type' => 'all',
                    'visible_for_visitors' => 'all',
                    'visible_in_pages' => 'all'
                );
                update_option( 'wacc-settings', $args );
            }

            if( empty( $license_validation ) )
            {
                update_option( 'dFjYsaWNfaXN', 0 );
            }
        }
    }
}