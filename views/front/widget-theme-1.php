<?php

defined( 'ABSPATH' ) || exit; // Prevent direct access

global $wpdb;

$plugin_settings = get_option( 'wacc-settings' );
$popup_sound = ( ! empty( $plugin_settings ) && isset( $plugin_settings['popup_sound'] ) ) ? WACC_URL . 'assets/audios/' . $plugin_settings['popup_sound'] : '';

$agents_table = $wpdb->prefix . 'wacc_agents';
$agents = $wpdb->get_results( "SELECT `name`, `position`, `phone`, `avatar`, `working_time` FROM {$agents_table}" );

wp_enqueue_style( 'wacc-fontawesome' );
wp_enqueue_style( 'wacc' );

wp_enqueue_script( 'jquery' );
wp_enqueue_script( 'wacc' );
wp_enqueue_script( 'howler' );
if( isset( $plugin_settings['play_sound_on_popup'] ) &&
    $plugin_settings['play_sound_on_popup'] == 1 )
{
    wp_add_inline_script( 'howler', 'jQuery(function($) {
            var sound = new Howl({
                src: ["' . $popup_sound . '"],
                onplayerror: function() {
                sound.once(\'unlock\', function() {
                    sound.play();
                });
                }
            });
            sound.play();

            if('. count($agents) .' > 5){
                $(".wacc-body").css("overflow-y", "auto");
            }
        });'
    );
}
?>

<div class="theme1 <?php echo ! empty( $plugin_settings ) && isset( $plugin_settings['icon_position'] ) ? $plugin_settings['icon_position'] : ''; ?>">
    <div class="wacc-rounded-4 wacc-overflow-hidden wacc-shadow wacc-position-fixed w-400 wacc-fw-bold wacc-mx-auto" id="w-400">
        <div class="wacc-bg-blue wacc-text-white wacc-p-4 wacc-pb-5">
            <?php if( ! empty( $plugin_settings ) && isset( $plugin_settings['chat_header_logo'] ) ): ?>
                <img src="<?php echo isset( $plugin_settings['chat_header_logo'] ) ? $plugin_settings['chat_header_logo'] : ''; ?>" alt="<?php _e( 'logo', 'wacc' ); ?>" class="logow">
            <?php endif; ?>
            <p class="wacc-mt-3"><?php echo ( isset( $plugin_settings['chat_header_text'] ) ) ? $plugin_settings['chat_header_text'] : ''; ?></p>
        </div>
        <div class="wacc-bg-white wacc-px-4">
        <?php if( ! empty( $agents ) && is_array( $agents ) ): ?>
                <div class="agents">
                    <?php foreach( $agents as $key => $agent ):
                        if( $plugin_settings['visible_date'] === 'all' )
                        { ?>
                            <div class="wacc-position-relative wacc-mb-3 wacc-bg-white wacc-rounded-4" style="top: <?php echo ( $key === 0 ) ? -38 : -38 - ( $key * 10 ); ?>px;z-index: 33;">
                                <a href="https://api.whatsapp.com/send?phone=<?php echo str_replace( '+' , '', $agent->phone ); ?>" target="_blank" rel="noopener noreferrer">
                                    <div class="wacc-d-flex wacc-justify-content-between tabs wacc-align-items-center wacc-overflow-hidden wacc-shadow wacc-rounded-4 wacc-p-2">
                                        <div class="wacc-d-flex wacc-align-items-center wacc-gap-3">
                                            <img src="<?php echo $agent->avatar; ?>" alt="<?php echo $agent->name; ?>" class="avatarx" width="42px" height="42px">
                                            <div>
                                                <p class="wacc-mb-1 wacc-fw-bold"><?php echo $agent->name; ?></p>
                                                <h6 class="wacc-m-0"><?php echo $agent->position; ?></h6>
                                            </div>
                                        </div>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                            class="bi bi-chevron-right wacc-text-secondary" viewBox="0 0 16 16">
                                            <path fill-rule="evenodd"
                                                d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z" />
                                        </svg>
                                    </div>
                                </a>
                            </div>
                        <?php
                        continue;
                        }else {
                            $class = 'disable';
                            $working_time = maybe_unserialize( $agent->working_time );
                            if( ! empty( $working_time ) && is_array( $working_time ) )
                            {
                                foreach( $working_time as $day_time )
                                {
                                    if( strtolower( $day_time['day'] ) == strtolower( date( "l" ) ) &&
                                        time() >= strtotime( $day_time['start'] ) && time() <= strtotime( $day_time['end'] ) )
                                    {
                                        $class = 'enable';
                                    }
                                }
                            }
                        } ?>
                        <div class="wacc-position-relative wacc-mb-3 wacc-bg-white wacc-rounded-4 <?php echo $class; ?>" style="top: <?php echo ( $key === 0 ) ? -38 : -38 - ( $key * 10 ); ?>px;z-index: 33;">
                            <a href="https://api.whatsapp.com/send?phone=<?php echo str_replace( '+' , '', $agent->phone ); ?>" target="_blank" rel="noopener noreferrer">
                                <div class="wacc-d-flex wacc-justify-content-between tabs wacc-align-items-center wacc-overflow-hidden wacc-shadow wacc-rounded-4 wacc-p-2">
                                    <div class="wacc-d-flex wacc-align-items-center wacc-gap-3">
                                        <img src="<?php echo $agent->avatar; ?>" alt="<?php echo $agent->name; ?>" class="avatarx" width="42px" height="42px">
                                        <div>
                                            <p class="wacc-mb-1 wacc-fw-bold"><?php echo $agent->name; ?></p>
                                            <h6 class="wacc-m-0"><?php echo $agent->position; ?></h6>
                                        </div>
                                    </div>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                        class="bi bi-chevron-right wacc-text-secondary" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd"
                                            d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z" />
                                    </svg>
                                </div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <div class="wacc-position-relative wacc-mb-1 wacc-bg-white wacc-overflow-hidden wacc-shadow wacc-rounded-4 wacc-p-3"
                style="top: <?php echo -38 - ( count( $agents ) * 10 ); ?>px;z-index: 33;">
                <h6 class="wacc-m-0 wacc-fw-bold"><?php _e( 'What to get in touch?', 'wacc' ); ?></h6>
                <small class="wacc-text-black-50 wacc-mt-2 wacc-d-block"><?php _e( 'We\'re standing by to answer all of your quetions right now.', 'wacc' ); ?></small>
                <a href="<?php echo ( isset( $plugin_settings['faq_url'] ) ) ? $plugin_settings['faq_url'] : ''; ?>" class="wacc-btn bg-blue wacc-rounded-3 wacc-text-white wacc-fw-bold wacc-text-center wacc-w-100 wacc-mt-3 wacc-fs-5 bottomdown"><?php _e( 'FAQ', 'wacc' ); ?></a>
                <a href="mailto:<?php echo ( isset( $plugin_settings['email_address'] ) ) ? $plugin_settings['email_address'] : ''; ?>" class="wacc-btn bg-blue wacc-rounded-3 wacc-text-white wacc-fw-bold wacc-text-center wacc-w-100 wacc-mt-3 wacc-fs-5 bottomdown"><?php _e( 'Contact', 'wacc' ); ?></a>

            </div>
            <div class="wacc-text-center wacc-mb-0 wacc-pb-3 wacc-text-secondary" style="margin-top: -50px;"><?php echo ( ! empty( $plugin_settings ) && isset( $plugin_settings['chat_footer_text'] ) ) ? $plugin_settings['chat_footer_text'] : ''; ?></div>
        </div>
    </div>


    <?php if( ! empty( $plugin_settings ) && isset( $plugin_settings['chat_icon'] ) ): ?>
        <div id="main-button" class="wacc-animate open">
            <i class="fa-solid fa-xmark wacc-close-icon wacc-d-none"></i>
            <img src="<?php echo WACC_URL . 'assets/img/icons/' . $plugin_settings['chat_icon'] ?>" alt="logo" class="wacc-chat-icon" width="50">
        </div>
    <?php endif; ?>
    <?php if( ! empty( $plugin_settings ) && isset( $plugin_settings['show_icon_text'], $plugin_settings['icon_text'] ) && $plugin_settings['show_icon_text'] == '1' ): ?>
        <button class="wacc-d-flex wacc-align-items-center items-justified-center">
            <div class="wacc-me-1"><i class="fa-regular fa-comments"></i></div> <div class="wacc-mbp-0"><?php echo $plugin_settings['icon_text']; ?></div>
        </button>
    <?php endif; ?>
</div>

