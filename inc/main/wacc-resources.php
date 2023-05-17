<?php

namespace WhatsAppChatChitavo\Inc\Main;

defined( 'ABSPATH' ) || exit; // Prevent direct access

if ( !class_exists( 'WACCResources' ) ) {

    class WACCResources {

        /**
         * Register admin resources
         *
         * @return void
         */
        public static function admin_resources( $hook ): void
        {

            if ( ! is_admin() ) return; //make sure we are on the backend

            if( str_contains( $hook, 'wacc-' ) ) {

                wp_register_style( 'jquery-timepicker', WACC_URL . 'assets/css/libs/jquery/jquery.timepicker.css', array(), '1.10.0' );
                wp_register_style( 'jquery-ui', WACC_URL . 'assets/css/libs/jquery/jquery-ui.css', array(), '1.12.1' );
                is_rtl() ? wp_register_style( 'bootstrap', WACC_URL . 'assets/css/libs/bootstrap/bootstrap.rtl.min.css', array(), '5.3.0' ) : wp_register_style( 'bootstrap', WACC_URL . 'assets/css/libs/bootstrap/bootstrap.min.css', array(), '5.3.0' );
                wp_register_style( 'bootstrap-select', WACC_URL . 'assets/css/libs/bootstrap/bootstrap-select.min.css', array( 'bootstrap' ), '1.13.1' );
                wp_register_style( 'wacc-fontawesome', WACC_URL . 'assets/css/libs/fontawesome/all.min.css', array(), '6.3.0' );
                wp_register_style( 'quill-snow', WACC_URL . 'assets/css/libs/quill/themes/quill.snow.css', array(), '1.3.6' );
                wp_register_style( 'quill-bubble', WACC_URL . 'assets/css/libs/quill/themes/quill.bubble.css', array(), '1.3.6' );
                wp_register_style( 'wacc', WACC_URL . 'assets/css/style.css', array( 'bootstrap' ), '1.0.0' );

                wp_register_script( 'jquery', WACC_URL . 'assets/js/libs/jquery/jquery.min.js', array(), '3.6.1', true );
                wp_register_script( 'jquery-ui', WACC_URL . 'assets/js/libs/jquery/jquery-ui.min.js', array(), '1.12.1', true );
                wp_register_script( 'jquery-repeater', WACC_URL . 'assets/js/libs/jquery/jquery.repeater.js', array(), '1.2.1', true );
                wp_register_script( 'jquery-timepicker', WACC_URL . 'assets/js/libs/jquery/jquery.timepicker.js', array(), '1.10.0', true );
                wp_register_script( 'bootstrap', WACC_URL . 'assets/js/libs/bootstrap/bootstrap.bundle.min.js', array( 'jquery' ), '5.3.0', true );
                wp_register_script( 'bootstrap-select', WACC_URL . 'assets/js/libs/bootstrap/bootstrap-select.min.js', array( 'jquery', 'bootstrap' ), '1.14.0', true );
                wp_register_script( 'quill', WACC_URL . 'assets/js/libs/quill/quill.min.js', array( 'jquery' ), '1.3.6', true );
                wp_register_script( 'notiflix', WACC_URL . 'assets/js/libs/notiflix/notiflix-aio.min.js', array( 'jquery' ), '3.2.6', true );
                wp_register_script( 'wacc-ajax', WACC_URL . 'assets/js/ajax.js', array( 'jquery', 'notiflix' ), '1.0.0', true);
                wp_register_script( 'wacc', WACC_URL . 'assets/js/script.js', array( 'jquery', 'wp-i18n', 'bootstrap', 'notiflix' ), '1.0.0', true);

                wp_localize_script( 'wacc-ajax', 'wacc_ajax', array(
                    'ajaxurl' => admin_url( 'admin-ajax.php' ),
                    '_ajax_nonce' => wp_create_nonce( 'wacc_action' )
                ));
            }
        }

        /**
         * Register front resources
         *
         * @return void
         */
        public static function front_resources(): void
        {
            wp_register_style( 'wacc-fontawesome', WACC_URL . 'assets/css/libs/fontawesome/all.min.css', array(), '6.3.0' );
            wp_register_style( 'wacc', WACC_URL . 'assets/css/front/style.css', '1.0.0' );

            wp_register_script( 'jquery', WACC_URL . 'assets/js/libs/jquery/jquery.min.js', array(), '3.6.1', true );
            wp_register_script( 'howler', WACC_URL . 'assets/js/libs/howler/howler.min.js', array(), '2.2.3', true );
            wp_register_script( 'wacc', WACC_URL . 'assets/js/front/script.js', array( 'jquery' ), '1.0.0', true );
        }

        /**
         * Register resources translation
         *
         * @return void
         */
        public static function resources_translation(): void
        {
            wp_set_script_translations( 'wacc', 'wacc', WACC_PATH . 'languages' );
        }
    }
}