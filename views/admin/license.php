<?php

defined( 'ABSPATH' ) || exit; // Prevent direct access

wp_enqueue_style( 'bootstrap' );
wp_enqueue_style( 'wacc' );
wp_add_inline_style( 'wacc', 'body {
    background-image: linear-gradient(to bottom right, #70c4c5, #232638);
    font-family: sans-serif;
    font-weight: 200;
}
@media (min-width: 40rem) {
    body {
    display: flex;
    align-items: center;
    justify-content: center;
    }
}
#wpfooter {
    background-color: #fff;
}' );

$license_validity = get_option( 'dFjYsaWNfaXN' );
$purchase_details = maybe_unserialize( get_option( 'wacc-purchase-details' ) );

if( ! empty( $license_validity ) &&
    $license_validity == true &&
    ! empty( $purchase_details ) &&
    is_array( $purchase_details )
    )
{

    ?>
    <div class="container mt-5 text-center">
        <div class="alert alert-success mx-auto" role="alert">
            <?php _e( 'The product has been successfully activated.', 'wacc' ); ?>
        </div>
        <dl class="mx-auto">
            <div class="dl-set">
                <dt><?php _e( 'Product Name', 'wacc' ) ?></dt>
                <dd><strong><?php echo $purchase_details['item_name']; ?></strong></dd>
            </div>
            <div class="dl-set">
                <dt><?php _e( 'Product ID', 'wacc' ); ?></dt>
                <dd><?php echo $purchase_details['item_id']; ?></dd>
            </div>
            <div class="dl-set">
                <dt><?php _e( 'Purchase Date', 'wacc' ); ?></dt>
                <dd><?php echo date( "d F Y", strtotime( $purchase_details['purchase_date'] ) ); ?></dd>
            </div>
            <div class="dl-set">
                <dt><?php _e( 'Buyer Name', 'wacc' ); ?></dt>
                <dd>
                    <?php echo $purchase_details['buyer_name']; ?>
                </dd>
            </div>
            <div class="dl-set">
                <dt><?php _e( 'License Type', 'wacc' ); ?></dt>
                <dd><?php echo $purchase_details['license_type']; ?></dd>
            </div>
            <div class="dl-set">
                <dt><?php _e( 'Supported Until', 'wacc' ); ?></dt>
                <dd><?php echo date( "d F Y", strtotime( $purchase_details['support_expiration_date'] ) ); ?></dd>
            </div>
            <div class="dl-set">
                <dt><?php _e( 'Purchase code', 'wacc' ); ?></dt>
                <dd>
                    <div class="tag"><?php echo $purchase_details['purchase_code']; ?></div>
                </dd>
            </div>
        </dl>
    </div>
<?php }else {
    wp_enqueue_script( 'jquery' );
    wp_enqueue_script( 'notiflix' );
    wp_enqueue_script( 'wacc-ajax' );
    ?>
    <div class="container mt-3 text-center wacc-verify-purchase-form p-3 col-md-8 col-sm-12">
        <h1><?php _e( 'Verify Envato Purchase Code', 'wacc' ); ?></h1>
        <p class="lead"><?php _e( 'provide purchase code in the input below and get the data.', 'wacc' ); ?></p>
        <div class="row">
            <div class="col-md-6 col-sm-12 mx-auto">
                <form action="verify.php" method="POST" id="verify-envato-purchase">
                <div class="row">
                    <div class="col-md-12">
                        <input type="text" name="purchase_code" value="" class="form-control" id="input-purchase-code" placeholder="<?php _e( 'Enter Purchase Code', 'wacc' ); ?>" />
                    </div>
                </div>
                <br>
                <input type="submit" value="<?php _e( 'Verify Purchase', 'wacc' ); ?>" class="btn btn-success">
                </form>
                <div id="show-result"></div>
            </div>
        </div>
    </div><!-- /.container -->
<?php } ?>