<?php

namespace WhatsAppChatChitavo\Inc\Ajax;

defined( 'ABSPATH' ) || exit; // Prevent direct access

use WhatsAppChatChitavo\Inc\WACCVerifyPurchase;

if ( !class_exists( 'WACCLicense' ) ) {

    class WACCLicense {

        /**
         * Verify envato purchase code ajax callback
         *
         * @return void
         */
        public static function handler(): void
        {
            ! check_ajax_referer( 'wacc_action', 'nonce', false )
                AND wp_send_json_error( array( 'message' => __( 'Security error!', 'wacc' ) ) );

            if ( ! isset( $_POST['purchase_code'] ) )
            {
                wp_send_json_error( array( 'message' => __( 'Purchase code not set!', 'wacc' ) ) );
            }

            require_once( WACC_PATH . 'inc/wacc-verify-purchase.php' );

            $purchase_code = htmlspecialchars( $_POST['purchase_code'] );

            $o = WACCVerifyPurchase::verify_purchase( $purchase_code );

            if ( is_object( $o ) && $o->item->id === '' )
            {

                update_option( 'dFjYsaWNfaXN', 1 );

                $args = array(
                    'item_id' => $o->item->id,
                    'item_name' => $o->item->name,
                    'purchase_date' => $o->sold_at,
                    'buyer_name' => $o->buyer,
                    'license_type' => $o->license,
                    'support_expiration_date' => $o->supported_until,
                    'purchase_code' => $_POST['purchase_code']
                );
                update_option( 'wacc-purchase-details', $args );

                wp_send_json_success( array( 'message' => __( 'License successfully activated! Please click the button below and wait for the page to reload.', 'wacc' ) ) );
            }

            wp_send_json_error( array( 'message' => __( 'The license code is not valid or there was an error in license activation!', 'wacc' ) ) );
        }
    }
}