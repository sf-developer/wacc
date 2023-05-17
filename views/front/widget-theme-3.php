<?php

defined( 'ABSPATH' ) || exit; // Prevent direct access

global $wpdb;

$plugin_settings = get_option( 'wacc-settings' );
$popup_sound = ( ! empty( $plugin_settings ) && isset( $plugin_settings['popup_sound'] ) ) ? WACC_URL . 'assets/audios/' . $plugin_settings['popup_sound'] : '';

$agents_table = $wpdb->prefix . 'wacc_agents';
$agents = $wpdb->get_results( "SELECT `name`, `position`, `phone`, `avatar`, `working_time` FROM {$agents_table}" );

wp_enqueue_style( 'wacc-fontawesome' );
wp_enqueue_style( 'wacc' );
wp_add_inline_style( 'wacc', '.nta-wabutton{
        width: 60px;
        height: 60px;
        margin: 0 auto;
        transition: all .3s ease-in-out;
    }
    .nta-wabutton > a {
        width: 60px;
        height: 60px;
    }
    .nta-wabutton .wa__cs_img {
        left: 0 !important;
    }
    .nta-wabutton .wa__btn_txt {
        opacity: 0;
    }
    .nta-wabutton.wacc-hover .wa__cs_img {
        left: -15px !important;
    }
    .nta-wabutton.wacc-hover .wa__btn_txt {
        opacity: 1;
    }
    .nta-wabutton.wacc-hover > a {
        width: auto;
    }
    .nta-wabutton.wacc-hover {
        width: 100%;
    }' );

wp_enqueue_script( 'jquery' );
wp_enqueue_script( 'howler' );
if( isset( $plugin_settings['play_sound_on_popup'] ) &&
    $plugin_settings['play_sound_on_popup'] == 1 )
{
    wp_add_inline_script( 'howler', 'jQuery(document).ready(function($) {
            var sound = new Howl({
                src: ["' . $popup_sound . '"],
                onplayerror: function() {
                sound.once(\'unlock\', function() {
                    sound.play();
                });
                }
            });
            sound.play();
        });'
    );
}
wp_enqueue_script( 'wacc' );
?>

<div class="theme3 <?php echo ! empty( $plugin_settings ) && isset( $plugin_settings['icon_position'] ) ? $plugin_settings['icon_position'] : ''; ?>">
    <div id="w-400" class="wacc-widget-box">
        <div class="wacc-header">
            <div class="wacc-logo">
                <img src="<?php echo isset( $plugin_settings['chat_header_logo'] ) ? $plugin_settings['chat_header_logo'] : ''; ?>" alt="whatsapp widget logo">
            </div>
            <div class="wacc-text">
                <?php echo ( isset( $plugin_settings['chat_header_text'] ) ) ? $plugin_settings['chat_header_text'] : ''; ?>
            </div>
        </div>
        <div class="wacc-body">
            <?php if( ! empty( $agents ) && is_array( $agents ) ): ?>
                <?php foreach( $agents as $key => $agent ):
                    if( $plugin_settings['visible_date'] === 'all' )
                    { ?>
                        <div class="wacc-mb-2 nta-wabutton">
                            <a target="_blank" href="https://api.whatsapp.com/send?phone=<?php echo str_replace( '+' , '', $agent->phone ); ?>" class="wa__button wa__r_button wa__stt_online wa__btn_w_img online">
                                <div class="wa__cs_img online">
                                    <div class="wa__cs_img_wrap" style="background: #fff url(<?php echo $agent->avatar; ?>) center center no-repeat; background-size: cover;"></div>
                                </div>
                                <div class="wa__btn_txt">
                                    <div class="wa__cs_info">
                                        <div class="wa__cs_name"><?php echo $agent->name; ?></div>
                                        <div class="wa__cs_status online">
                                            <?php _e( 'Online', 'wacc' );?>
                                        </div>
                                    </div>
                                    <div class="wa__btn_title"><?php echo $agent->position; ?></div>
                                </div>
                            </a>
                        </div>
                    <?php
                    continue;
                    }else {
                        $online = false;
                        $working_time = maybe_unserialize( $agent->working_time );
                        if( ! empty( $working_time ) && is_array( $working_time ) )
                        {
                            foreach( $working_time as $day_time )
                            {
                                if( strtolower( $day_time['day'] ) == strtolower( date( "l" ) ) &&
                                    time() >= strtotime( $day_time['start'] ) && time() <= strtotime( $day_time['end'] ) )
                                {
                                    $online = true;
                                }
                            }
                        }
                    } ?>
                    <div class="wacc-mb-2 nta-wabutton">
                        <a target="_blank" href="https://api.whatsapp.com/send?phone=<?php echo str_replace( '+' , '', $agent->phone ); ?>" class="wa__button wa__r_button wa__stt_online wa__btn_w_img <?php echo $online ? 'online' : 'offline'; ?>">
                            <div class="wa__cs_img <?php echo $online ? 'online' : 'offline'; ?>">
                                <div class="wa__cs_img_wrap" style="background: #fff url(<?php echo $agent->avatar; ?>) center center no-repeat; background-size: cover;"></div>
                            </div>
                            <div class="wa__btn_txt">
                                <div class="wa__cs_info">
                                    <div class="wa__cs_name"><?php echo $agent->name; ?></div>
                                    <div class="wa__cs_status <?php echo $online ? 'online' : 'offline'; ?>">
                                        <?php echo $online ? 'Online' : 'Offline'; ?>
                                    </div>
                                </div>
                                <div class="wa__btn_title"><?php echo $agent->position; ?></div>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <div class="wacc-footer">
            <?php if( isset( $plugin_settings['faq_button'] ) && $plugin_settings['faq_button'] === '1' ): ?>
                <div class="wacc-faq-button">
                    <div class="wacc-faq-icon">
                        <i class="fa-regular fa-circle-question"></i>
                    </div>
                    <div class="wacc-text">
                        <a href="<?php echo ( isset( $plugin_settings['faq_url'] ) ) ? $plugin_settings['faq_url'] : ''; ?>">Most popular articles - FAQ</a>
                    </div>
                </div>
            <?php endif; ?>
            <?php if( isset( $plugin_settings['email_button'] ) && $plugin_settings['email_button'] === '1' ): ?>
                <div class="wacc-email-button">
                    <div class="wacc-email-icon">
                        <i class="fa-regular fa-envelope"></i>
                    </div>
                    <div class="wacc-text">
                    <a href="mailto:<?php echo ( isset( $plugin_settings['email_address'] ) ) ? $plugin_settings['email_address'] : ''; ?>">Send us an email directly</a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <div class="wacc-text-center wacc-mb-0 wacc-text-secondary wacc-position-relative wacc-w-100 wacc-mt-1 wacc-footer-text" style="left: 0; right: 0; bottom: 0;"><?php echo ( ! empty( $plugin_settings ) && isset( $plugin_settings['chat_footer_text'] ) ) ? $plugin_settings['chat_footer_text'] : ''; ?></div>
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