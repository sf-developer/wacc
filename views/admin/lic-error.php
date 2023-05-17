<?php

defined( 'ABSPATH' ) || exit; // Prevent direct access

wp_enqueue_style( 'bootstrap' );
wp_enqueue_style( 'wacc' );
wp_add_inline_style( 'wacc',
    'body {
        width: 100vw;
        background-color: #2D3748;
        overflow-x: hidden;
    }
    #wpfooter {
        background-color: #fff;
    }'
);
?>
<div class="container">
    <div class="wrapper mt-3">
        <article class="wacc-alert error">
            <div class="wacc-alert__wrapper">
                <div class="wacc-alert__header">
                    <h3>
                        <span>
                            <i class="fa fa-exclamation-circle"></i>
                        </span>
                        <span><?php _e( 'Oh No! an Error.', 'wacc' ); ?></span>
                    </h3>
                </div>
                <div class="wacc-alert__body">
                    <p>
                        <?php
                        echo sprintf(
                            __( '<b>Support section is only available for CodeCanyon clients with activated license.</b> To unlock the in-plugin support, please, activate your license by entering CodeCanyon purchase code of the <a href="%1$s" class="%2$s">plugin</a>.', 'wacc' ),
                            '#',
                            'text-warning'
                        );
                        ?>
                    </p>
                </div>
                <a href="<?php echo admin_url( 'admin.php?page=wacc-license' ); ?>" class="btn btn-danger w-100 text-decoration-none"><?php _e( 'Active plugin', 'wacc' ); ?></a>
            </div>
        </article>
    </div>
</div>