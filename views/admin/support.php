<?php

defined( 'ABSPATH' ) || exit; // Prevent direct access

$license_validity = get_option( 'dFjYsaWNfaXN' );

if( empty( $license_validity ) || $license_validity == false )
{
    include_once WACC_PATH . 'views/admin/lic-error.php';
}else {
    wp_enqueue_style( 'wacc' );
    wp_add_inline_style( 'wacc', 'body {
        background-color: hsl(233, 47%, 7%);
        font-size: 15px;
        font-family: \'Inter\', sans-serif;
    }
    #wpfooter {
        background-color: #fff;
    }' );
    ?>


<div class="wacc-container">
        <div class="wacc-section wacc-section1">
            <h2>Get <span style="color: #25D366;">insights</span> that help your business grow.</h2>
            <p>Discover the benefits of data analytics and make better decisions regarding revenue, customer experience, and overall efficiency.</p>
            <div>
                <div>
                    <a href="#" class="btn" style="background-color: #25D366;">Have a question? Contact us</a>
                </div>
            </div>
        </div>
        <div class="wacc-section wacc-section2"></div>
    </div>
<?php } ?>