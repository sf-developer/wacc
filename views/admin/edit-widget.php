<?php

defined( 'ABSPATH' ) || exit; // Prevent direct access


wp_enqueue_style( 'wacc' );

$license_validity = get_option( 'dFjYsaWNfaXN' );

if( empty( $license_validity ) || $license_validity == false )
{
    include_once WACC_PATH . 'views/admin/lic-error.php';
}else {

    wp_enqueue_media();
    wp_enqueue_style( 'wacc-fontawesome' );
    wp_enqueue_style( 'jquery-timepicker' );
    wp_enqueue_style( 'jquery-ui' );
    wp_enqueue_style( 'bootstrap' );
    wp_enqueue_style( 'bootstrap-select' );
    wp_enqueue_style( 'quill-snow' );

    wp_enqueue_script( 'jquery' );
    wp_enqueue_script( 'jquery-ui' );
    wp_enqueue_script( 'jquery-repeater' );
    wp_enqueue_script( 'jquery-timepicker' );
    wp_enqueue_script( 'bootstrap' );
    wp_enqueue_script( 'bootstrap-select' );
    wp_enqueue_script( 'quill' );
    wp_enqueue_script( 'notiflix' );
    wp_enqueue_script( 'wacc-ajax' );
    wp_enqueue_script( 'wacc' );

    global $wpdb;
    $agents_table_name = $wpdb->prefix . 'wacc_agents';
    $agents = $wpdb->get_results(
        "SELECT `ID`, `name`, `position`, `phone`, `avatar`, `working_time` FROM {$agents_table_name}"
    );

    $plugin_settings = maybe_unserialize( get_option( 'wacc-settings' ) );

    if( ! empty( $plugin_settings ) && is_array( $plugin_settings ) )
    {
        $settings_icon = isset( $plugin_settings['chat_icon'] ) ? $plugin_settings['chat_icon'] : '';
        $settings_show_icon_text = isset( $plugin_settings['show_icon_text'] ) ? $plugin_settings['show_icon_text'] : '';
        $settings_icon_text = isset( $plugin_settings['icon_text'] ) ? $plugin_settings['icon_text'] : '';
        $settings_icon_position = isset( $plugin_settings['icon_position'] ) ? $plugin_settings['icon_position'] : 'right';
        $settings_chat_header_logo = isset( $plugin_settings['chat_header_logo'] ) ? $plugin_settings['chat_header_logo'] : '';
        $settings_chat_header_text = isset( $plugin_settings['chat_header_text'] ) ? $plugin_settings['chat_header_text'] : '';
        $settings_chat_footer_text = isset( $plugin_settings['chat_footer_text'] ) ? $plugin_settings['chat_footer_text'] : '';
        $settings_popup_sound = isset( $plugin_settings['popup_sound'] ) ? $plugin_settings['popup_sound'] : '';
        $settings_enable_email_button = isset( $plugin_settings['email_button'] ) ? $plugin_settings['email_button'] : '';
        $settings_email = isset( $plugin_settings['email_address'] ) ? $plugin_settings['email_address'] : '';
        $settings_enable_faq_button = isset( $plugin_settings['faq_button'] ) ? $plugin_settings['faq_button'] : '';
        $settings_faq_url = isset( $plugin_settings['faq_url'] ) ? $plugin_settings['faq_url'] : '';
        $settings_theme = isset( $plugin_settings['theme'] ) ? $plugin_settings['theme'] : '';
        $settings_visible_date = isset( $plugin_settings['visible_date'] ) ? $plugin_settings['visible_date'] : '';
        $settings_visible_for_device_type = isset( $plugin_settings['visible_for_device_type'] ) ? $plugin_settings['visible_for_device_type'] : '';
        $settings_visible_for_visitors = isset( $plugin_settings['visible_for_visitors'] ) ? $plugin_settings['visible_for_visitors'] : '';
        $settings_visible_in_pages = isset( $plugin_settings['visible_in_pages'] ) ? $plugin_settings['visible_in_pages'] : '';
    }

    $days = [
        __('Monday', 'wacc'),
        __('Tuesday', 'wacc'),
        __('Wednesday', 'wacc'),
        __('Thursday', 'wacc'),
        __('Friday', 'wacc'),
        __('Saturday', 'wacc'),
        __('Sunday', 'wacc')
    ];
    $week_days = '';
    $empty_work_time = true;
    foreach ($days as $day)
    {
        $week_days .= '<tr>
            <td class="day">' . $day . '</td>
            <td class="start-time"><input class="time ui-timepicker-input" type="text" /></td>
            <td class="finish-time"><input class="time ui-timepicker-input" type="text" /></td>
        </tr>';
    }
    if( ! empty( $agents ) && is_array( $agents ) )
    {
        $options =  '<option value="">' . __( 'Choose...', 'wacc' ) . '</option>';
        foreach( $agents as $agent )
        {
            $options .= '<option value="' . $agent->ID . '">' . $agent->name . '</option>';
        }
    }
    ?>
    <div class="container mt-3">
        <section id="fancyTabWidget" class="tabs t-tabs">
            <ul class="nav nav-tabs fancyTabs" role="tablist">
                <li class="nav-item fancyTab active" data-bs-toggle="tab" data-bs-target="#tabBody0" role="tab" aria-controls="tabBody0" aria-selected="true">
                    <div class="arrow-down">
                        <div class="arrow-down-inner"></div>
                    </div>
                    <div class="wacc">
                        <i class="fa-solid fa-pen-to-square"></i>
                        <span class="hidden-xs"><?php _e( 'Appearance', 'wacc' ); ?></span>
                    </div>
                    <div class="whiteBlock"></div>
                </li>
                <li class="nav-item fancyTab" data-bs-toggle="tab" data-bs-target="#tabBody1" role="tab" aria-controls="tabBody1" aria-selected="false">
                    <div class="arrow-down">
                        <div class="arrow-down-inner"></div>
                    </div>
                    <div class="wacc">
                        <i class="fa-solid fa-gear"></i>
                        <span class="hidden-xs"><?php _e( 'Configuration', 'wacc' ); ?></span>
                    </div>
                    <div class="whiteBlock"></div>
                </li>
            </ul>
            <div id="myTabContent" class="tab-content fancyTabContent" aria-live="polite">
                <div class="tab-pane fade active show" id="tabBody0" role="navpanel" aria-labelledby="tab0" aria-hidden="false" tabindex="0">
                    <div class="site-wrapper">
                        <section class="tabs-wrapper">
                            <div class="tabs-container">
                                <div class="tabs-block">
                                    <div id="tabs-section" class="wacc-tabs">
                                        <ul class="tab-head">
                                            <li>
                                                <a href="#tab-1" class="tab-link active"> <i class="fa-solid fa-pencil"></i> <span class="tab-label"><?php _e( 'Content & Icon', 'wacc' ); ?></span></a>
                                            </li>
                                            <li>
                                                <a href="#tab-2" class="tab-link"> <i class="fa-solid fa-user-tie"></i> <span class="tab-label"><?php _e( 'Agents', 'wacc' ); ?></span></a>
                                            </li>
                                            <li>
                                                <a href="#tab-3" class="tab-link"> <i class="fa-regular fa-comments"></i> <span class="tab-label"><?php _e( 'Welcome Message', 'wacc' ); ?></span></a>
                                            </li>
                                            <li>
                                                <a href="#tab-4" class="tab-link"> <i class="fa-solid fa-circle-question"></i> <span class="tab-label"><?php _e( 'Contact buttons', 'wacc' ); ?></span></a>
                                            </li>
                                            <li>
                                                <a href="#tab-5" class="tab-link"> <i class="fa-solid fa-palette"></i> <span class="tab-label"><?php _e( 'Theme', 'wacc' ); ?></span></a>
                                            </li>
                                        </ul>
                                        <section id="tab-1" class="tab-body entry-content active active-content">
                                            <h2 class="mb-0"><?php _e( 'Content', 'wacc' ); ?></h2>
                                            <div class="w-100 mt-3">
                                                <input type="checkbox" name="show_icon_text" id="show-icon-text" <?php echo $settings_show_icon_text == '1' ? 'checked' : ''; ?>/>
                                                <label for="show-icon-text"><?php _e( 'Show icon text', 'wacc' ); ?></label>
                                            </div>
                                            <div class="wacc-icon-text mt-3" style="<?php echo $settings_show_icon_text == '0' ? 'display: none;' : ''; ?>">
                                                <small class="mb-3"><?php _e( 'This content will be displayed next to the icon.', 'wacc' ); ?></small>
                                                <div id="wacc-text" class="mb-3">
                                                    <?php echo $settings_icon_text; ?>
                                                </div>
                                            </div>
                                            <h2 class="mb-0"><?php _e( 'Icon', 'wacc' ); ?></h2>
                                            <small class="mb-3"><?php _e( 'Choose your favorite icon.', 'wacc' ); ?></small>
                                            <div class="mt-3 wacc-chat-icon">
                                                <?php
                                                $path = WACC_PATH . 'assets/img/icons/';
                                                $icons = glob( $path . '*.svg' );

                                                foreach ( $icons as $icon ) {
                                                    $active = basename( $icon ) === $settings_icon ? 'active' : '';
                                                    echo '<img src="' . WACC_URL . 'assets/img/icons/' . basename( $icon ) . '" data-name="' . basename( $icon ) . '" class="m-2 wacc-icon ' . $active . '" />';
                                                }
                                                ?>
                                            </div>
                                            <h2 class="mb-0"><?php _e( 'Widget position', 'wacc' ); ?></h2>
                                            <small class="mb-3"><?php _e( 'Choose position of widget.', 'wacc' ); ?></small>
                                            <select id="widget-position" name="widget_position" class="selectpicker w-100">
                                                <option value=""><?php _e( 'Choose...', 'wacc' ); ?></option>
                                                <option value="right" <?php echo $settings_icon_position === 'right' ? 'selected' : ''; ?>><?php _e( 'Right', 'wacc' ); ?></option>
                                                <option value="left" <?php echo $settings_icon_position === 'left' ? 'selected' : ''; ?>><?php _e( 'Left', 'wacc' ); ?></option>
                                            </select>
                                        </section>
                                        <section id="tab-2" class="tab-body entry-content">
                                            <h2><?php _e( 'Chat Header Logo', 'wacc' ); ?></h2>
                                            <small class="mb-3"><?php _e( 'Add a logo to display in the chat header.', 'wacc' ); ?></small>
                                            <div id="wacc-chat-header-logo" class="mb-3">
                                                <img src="<?php echo $settings_chat_header_logo; ?>" alt="<?php __( 'Chat header logo', 'wacc' ); ?>" width="150" >
                                            </div>
                                            <button class="btn btn-success wacc-chat-header-logo"><?php _e( 'Upload logo', 'wacc' ); ?></button>
                                            <h2><?php _e( 'Chat Header Text', 'wacc' ); ?></h2>
                                            <small class="mb-3"><?php _e( 'Add a text to display in the chat header.', 'wacc' ); ?></small>
                                            <div id="wacc-chat-header" class="mb-3">
                                                <?php echo $settings_chat_header_text; ?>
                                            </div>
                                            <h2><?php _e( 'Agents', 'wacc' ); ?></h2>
                                            <small class="mb-3"><?php _e( 'Add or edit agents accounts.', 'wacc' ); ?></small>
                                            <div class="wacc-repeater">
                                                <div data-repeater-list="agent" class="drag wacc-agent-items">
                                                    <?php
                                                    if( ! empty( $agents ) && is_array( $agents ) )
                                                    {
                                                        foreach( $agents as $key => $agent )
                                                        {
                                                            echo '<div data-repeater-item class="wacc-agents" data-id="' . $agent->ID . '">
                                                                <div class="row form-group d-flex align-items-center">
                                                                    <div class="col-md-2 my-3">
                                                                        <img src="' . $agent->avatar . '" alt="' . $agent->name . '-' . __( 'avatar', 'wacc' ) . '" name="agent[' . $key . '][avatar]" class="d-flex align-items-center m-auto agent-avatar" width="100" />
                                                                        <button type="button" class="btn btn-sm btn-success w-100 upload-avatar">' . __( 'Change avatar', 'wacc' ) . '</button>
                                                                    </div>
                                                                    <div class="col-md-3 my-3">
                                                                        <label class="w-100 control-label mb-1">' . __( 'Name', 'wacc' ) . '</label>
                                                                        <input type="text" name="agent[' . $key . '][name]" value="' . $agent->name . '" class="form-control">
                                                                    </div>
                                                                    <div class="col-md-3 my-3">
                                                                        <label class="w-100 control-label mb-1">' . __( 'Position', 'wacc' ) . '</label>
                                                                        <input type="text" name="agent[' . $key . '][position]" value="' . $agent->position . '" class="form-control">
                                                                    </div>
                                                                    <div class="col-md-3 my-3">
                                                                        <label class="w-100 control-label mb-1">' . __( 'Phone', 'wacc' ) . '</label>
                                                                        <input type="text" name="agent[' . $key . '][phone]" value="' . $agent->phone . '" class="form-control">
                                                                    </div>
                                                                    <div class="col-md-1 mt-4">
                                                                        <span data-repeater-delete class="btn btn-danger btn-sm">
                                                                            <i class="fa-solid fa-trash"></i>
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            </div>';
                                                        }
                                                    }else {
                                                    ?>
                                                        <div data-repeater-item class="wacc-agents">
                                                            <div class="row form-group d-flex align-items-center">
                                                                <div class="col-md-2 my-3">
                                                                    <img src="<?php echo WACC_URL . 'assets/img/avatars/avatar.png' ?>" alt="<?php __( 'Support avatar', 'wacc' ); ?>" name="agent[0][avatar]" class="d-flex align-items-center m-auto agent-avatar" width="100" />
                                                                    <button type="button" class="btn btn-sm btn-success w-100 upload-avatar"><?php _e( 'Choose avatar', 'wacc' ); ?></button>
                                                                </div>
                                                                <div class="col-md-3 my-3">
                                                                    <label class="w-100 control-label mb-1"><?php _e( 'Name', 'wacc' ); ?></label>
                                                                    <input type="text" name="agent[0][name]" value="<?php _e( 'Support', 'wacc' ); ?>" class="form-control">
                                                                </div>
                                                                <div class="col-md-3 my-3">
                                                                    <label class="w-100 control-label mb-1"><?php _e( 'Position', 'wacc' ); ?></label>
                                                                    <input type="text" name="agent[0][position]" value="<?php _e( 'Sale manager', 'wacc' ); ?>" class="form-control">
                                                                </div>
                                                                <div class="col-md-3 my-3">
                                                                    <label class="w-100 control-label mb-1"><?php _e( 'Phone', 'wacc' ); ?></label>
                                                                    <input type="text" name="agent[0][phone]" value="<?php _e( '+15551234', 'wacc' ); ?>" class="form-control">
                                                                </div>
                                                                <div class="col-md-1 mt-4">
                                                                    <span data-repeater-delete class="btn btn-danger btn-sm">
                                                                        <i class="fa-solid fa-trash"></i>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php } ?>
                                                </div>
                                                <div class="form-group row">
                                                    <div class="col-md-offset-1 col-md-11">
                                                        <span data-repeater-create class="btn btn-info btn-md">
                                                            <i class="fa-solid fa-plus"></i> <?php _e( 'Add', 'wacc' ); ?>
                                                        </span>
                                                    </div>
                                                </div>
                                                <hr>
                                            </div>
                                            <h2><?php _e( 'Chat Footer Text', 'wacc' ); ?></h2>
                                            <small class="mb-3"><?php _e( 'Add a text to display in the chat footer.', 'wacc' ); ?></small>
                                            <div id="wacc-chat-footer" class="mb-3">
                                                <?php echo $settings_chat_footer_text; ?>
                                            </div>
                                        </section>
                                        <section id="tab-3" class="tab-body entry-content">
                                            <h2><?php _e( 'Welcome Alert', 'wacc' ); ?></h2>
                                            <small class="mb-3"><?php _e( 'Select audio to play on page load.', 'wacc' ); ?></small>
                                            <div class="row mb-3">
                                                <div class="col-12">
                                                    <input type="checkbox" name="wacc_play_alert" id="wacc-play-alert" class="m-0" checked>
                                                    <label for="wacc-play-alert"><?php _e( 'Play alert when page loads', 'wacc' ) ?></label>
                                                </div>
                                            </div>
                                            <div id="audioPanel">
                                                <button id="playprev" class="btn btn-sm btn-success playerBtn"><i class="fa fa-arrow-left"></i></button>
                                                <select id="playlist">
                                                    <?php
                                                    $path = WACC_PATH . 'assets/audios/';
                                                    $audios = glob( $path . '*.mp3' );

                                                    foreach ( $audios as $audio ) {
                                                        $selected = basename( $audio ) === $settings_popup_sound ? 'selected' : '';
                                                        echo '<option data-mp3="' . WACC_URL . 'assets/audios/' . basename( $audio ) . '" data-name="' . basename( $audio ) . '" ' . $selected . '>' . basename( $audio ) . '</option>';
                                                    }
                                                    ?>
                                                </select>
                                                <button id="playnext" class="btn btn-sm btn-success playerBtn"><i class="fa fa-arrow-right"></i></button>
                                                <div id="playerWrap" class="playerWrap mt-3">
                                                    <audio id="audio" controls class="w-100"></audio>
                                                </div>
                                            </div>
                                        </section>
                                        <section id="tab-4" class="tab-body entry-content">
                                            <h2><?php _e( 'Email', 'wacc' ); ?></h2>
                                            <small class="mb-3"><?php _e( 'Add Email button to the footer of the chat widget.', 'wacc' ); ?></small>
                                            <div class="row mt-3">
                                                <div class="col-md-12 mt-3">
                                                    <input type="checkbox" name="wacc_email_button" id="wacc-email-button" class="m-0 wacc-faq-content" <?php echo $settings_enable_email_button === '1' ? 'checked' : ''; ?>>
                                                    <label for="wacc-email-button"><?php _e( 'Display email button', 'wacc' ); ?></label>
                                                </div>
                                                <div class="col-md-12 mt-3 wacc-faq-toggle wacc-email">
                                                    <label for="wacc-mail"><?php _e( 'Enter your personal/company email address to show in the footer of the chat widget.', 'wacc' ) ?></label>
                                                    <input type="email" name="wacc_mail" id="wacc-mail" class="w-100" placeholder="<?php _e( 'Please input your email here', 'wacc' ); ?>" value="<?php echo $settings_email; ?>">
                                                </div>
                                            </div>
                                            <h2><?php _e( 'FAQs', 'wacc' ); ?></h2>
                                            <small class="mb-3"><?php _e( 'Add Q&A button to the footer of the chat widget.', 'wacc' ); ?></small>
                                            <div class="row mt-3">
                                                <div class="col-md-12 mt-3">
                                                    <input type="checkbox" name="wacc_faq_button" id="wacc-faq-button" class="m-0 wacc-faq-content" <?php echo $settings_enable_faq_button === '1' ? 'checked' : ''; ?>>
                                                    <label for="wacc-faq-button"><?php _e( 'Display faq button', 'wacc' ); ?></label>
                                                </div>
                                                <div class="col-md-12 mt-3 wacc-faq-toggle wacc-toggle-faqs">
                                                    <label for="wacc-faq"><?php _e( 'Enter FAQ page url to show in the footer of the chat widget.', 'wacc' ) ?></label>
                                                    <input type="url" name="wacc_faq" id="wacc-faq" class="w-100" placeholder="<?php _e( 'Please input FAQ page url here', 'wacc' ); ?>" value="<?php echo $settings_faq_url; ?>">
                                                </div>
                                            </div>
                                        </section>
                                        <section id="tab-5" class="tab-body entry-content">
                                            <h2><?php _e( 'Select theme', 'wacc' ); ?></h2>
                                            <small class="mb-3"><?php _e( 'Select widget theme.', 'wacc' ); ?></small>
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <select name="wacc_theme" id="wacc-theme" class="mt-2 mb-3 selectpicker" data-live-search="true" data-width="100%" required>
                                                        <option value="theme-1" <?php echo $settings_theme === 'theme-1' ? 'selected' : ''; ?> data-src="<?php echo WACC_URL . 'assets/img/themes/theme-1.gif'; ?>"><?php _e( 'Theme 1', 'wacc' ); ?></option>
                                                        <option value="theme-2" <?php echo $settings_theme === 'theme-2' ? 'selected' : ''; ?> data-src="<?php echo WACC_URL . 'assets/img/themes/theme-2.gif'; ?>"><?php _e( 'Theme 2', 'wacc' ); ?></option>
                                                        <option value="theme-3" <?php echo $settings_theme === 'theme-3' ? 'selected' : ''; ?> data-src="<?php echo WACC_URL . 'assets/img/themes/theme-3.gif'; ?>"><?php _e( 'Theme 3', 'wacc' ); ?></option>
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="mb-2 theme-preview"></div>
                                                    <img src="<?php echo WACC_URL . 'assets/img/themes/' . $settings_theme . '.gif'; ?>" alt="<?php $settings_theme; ?>" width="100%">
                                                </div>
                                            </div>
                                        </section>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
                <div class="tab-pane fade" id="tabBody1" role="navpanel" aria-labelledby="tab1" aria-hidden="true" tabindex="0">
                    <h4 class="mb-3"><?php _e( 'In this section you can configure the visibility of the widget.', 'wacc' ); ?></h4>
                    <div class="site-wrapper">
                        <section class="tabs-wrapper">
                            <div class="tabs-container">
                                <div class="tabs-block">
                                    <div id="settings-tabs-section" class="wacc-tabs">
                                        <ul class="tab-head">
                                            <li>
                                                <a href="#settings-tab-1" class="tab-link active"> <i class="fa-solid fa-file"></i> <span class="tab-label"><?php _e( 'Pages', 'wacc' ); ?></span></a>
                                            </li>
                                            <li>
                                                <a href="#settings-tab-2" class="tab-link"> <i class="fa-solid fa-desktop"></i> <span class="tab-label"><?php _e( 'Devices', 'wacc' ); ?></span></a>
                                            </li>
                                            <li>
                                                <a href="#settings-tab-3" class="tab-link"> <i class="fa-solid fa-user"></i> <span class="tab-label"><?php _e( 'Visitors', 'wacc' ); ?></span></a>
                                            </li>
                                            <li>
                                                <a href="#settings-tab-4" class="tab-link"> <i class="fa-solid fa-calendar-days"></i> <span class="tab-label"><?php _e( 'Date & Time', 'wacc' ); ?></span></a>
                                            </li>
                                        </ul>
                                        <section id="settings-tab-1" class="tab-body entry-content active active-content">
                                            <h2 class="mb-0"><?php _e( 'Pages', 'wacc' ); ?></h2>
                                            <small class="mb-3"><?php _e( 'Choose to display the chat widget on all or specific page(s).', 'wacc' ); ?></small>
                                            <div class="row mt-3">
                                                <div class="col-md-12">
                                                    <input type="checkbox" name="wacc_pages" id="wacc-pages" class="m-0 wacc-all-content" <?php echo $settings_visible_in_pages === 'all' ? 'checked' : ''; ?>>
                                                    <label for="wacc-pages"><?php _e( 'Display on all pages', 'wacc' ); ?></label>
                                                </div>
                                                <h6 class="my-3"><?php _e( 'Or choose specific page(s):', 'wacc' ); ?></h6>
                                                <div class="col-md-12">
                                                    <div class="row wacc-toggle wacc-pages">
                                                        <?php
                                                        $pages = get_pages();
                                                        foreach( $pages as $page )
                                                        {
                                                            $checked = str_contains( $settings_visible_in_pages, $page->ID ) ? "checked" : "";
                                                            echo '<div class="col-md-4">
                                                                <input type="checkbox" name="wacc_page_' . $page->ID . '" id="wacc-page-' . $page->ID . '" class="m-0" value="' . $page->ID . '" ' . $checked . '>
                                                                <label for="wacc-page-' . $page->ID . '">' . $page->post_title . '</label>
                                                            </div>';
                                                        }
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </section>
                                        <section id="settings-tab-2" class="tab-body entry-content">
                                            <h2><?php _e( 'Devices', 'wacc' ); ?></h2>
                                            <small class="mb-3"><?php _e( 'Choose to display the chat widget on all or specific device(s).', 'wacc' ); ?></small>
                                            <div class="row mt-3">
                                                <div class="col-md-12">
                                                    <input type="checkbox" name="wacc_devices" id="wacc-devices" class="m-0 wacc-all-content" <?php echo $settings_visible_for_device_type === 'all' ? 'checked' : ''; ?>>
                                                    <label for="wacc-devices"><?php _e( 'Display on all devices', 'wacc' ); ?></label>
                                                </div>
                                                <h6 class="my-3"><?php _e( 'Or choose specific device(s):', 'wacc' ); ?></h6>
                                                <div class="col-md-12">
                                                    <div class="row wacc-toggle wacc-devices">
                                                        <div class="col-md-6">
                                                            <input type="checkbox" name="wacc_mobile" id="wacc-mobile" class="m-0" value="mobile" <?php echo str_contains( $settings_visible_for_device_type, 'mobile' ) ? "checked" : ""; ?>>
                                                            <label for="wacc-mobile"><?php _e( 'Mobile & Tablet', 'wacc' ); ?></label>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <input type="checkbox" name="wacc_pc" id="wacc-pc" class="m-0" value="pc" <?php echo str_contains( $settings_visible_for_device_type, 'pc' ) ? "checked" : ""; ?>>
                                                            <label for="wacc-pc"><?php _e( 'PC', 'wacc' ); ?></label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </section>
                                        <section id="settings-tab-3" class="tab-body entry-content">
                                            <h2><?php _e( 'Visitors', 'wacc' ); ?></h2>
                                            <small class="mb-3"><?php _e( 'Choose to display the chat widget on all or specific visitor(s).', 'wacc' ); ?></small>
                                            <div class="row mt-3">
                                                <div class="col-md-12">
                                                    <input type="checkbox" name="wacc_visitors" id="wacc-visitors" class="m-0 wacc-all-content" <?php echo $settings_visible_for_visitors === 'all' ? 'checked' : ''; ?>>
                                                    <label for="wacc-visitors"><?php _e( 'Display for all visitors', 'wacc' ); ?></label>
                                                </div>
                                                <h6 class="my-3"><?php _e( 'Or choose specific visitor(s):', 'wacc' ); ?></h6>
                                                <div class="col-md-12">
                                                    <div class="row wacc-toggle wacc-visitors">
                                                        <?php
                                                        global $wp_roles;
                                                        $all_roles = $wp_roles->roles;
                                                        foreach( $all_roles as $key => $roles )
                                                        {
                                                            $wacc_roles[$key] = $roles;
                                                        }
                                                        foreach( $wacc_roles as $key => $role )
                                                        {
                                                            $checked = str_contains( $settings_visible_for_visitors, $key ) ? "checked" : "";
                                                            echo '<div class="col-md-4">
                                                                <input type="checkbox" name="wacc_role_' . $key . '" id="wacc-role-' . $key . '" class="m-0" value="' . $key . '" ' . $checked . '>
                                                                <label for="wacc-role-' . $key . '">' . $role['name'] . '</label>
                                                            </div>';
                                                        }
                                                        ?>
                                                        <div class="col-md-4">
                                                            <input type="checkbox" name="wacc_role_guest" id="wacc-role-guest" class="m-0" value="guest" <?php echo str_contains( $settings_visible_for_visitors, 'guest' ) ? "checked" : ""; ?>>
                                                            <label for="wacc-role-guest"><?php _e( 'Guest', 'wacc' ); ?></label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </section>
                                        <section id="settings-tab-4" class="tab-body entry-content">
                                            <h2><?php _e( 'Date & Time', 'wacc' ); ?></h2>
                                            <small class="mb-3"><?php _e( 'Choose to display the chat widget on all or specific day(s).', 'wacc' ); ?></small>
                                            <div class="row mt-3">
                                                <div class="col-md-12">
                                                    <input type="checkbox" name="wacc_days" id="wacc-days" class="m-0 wacc-all-content" <?php echo $settings_visible_date === 'all' ? 'checked' : ''; ?>>
                                                    <label for="wacc-days"><?php _e( 'Display on all days and hours', 'wacc' ); ?></label>
                                                </div>
                                                <h6 class="my-3"><?php _e( 'Or choose specific day(s) and hour(s) per agent(s):', 'wacc' ); ?></h6>
                                                <div class="wacc-toggle wacc-days">
                                                    <div class="col-md-12 wacc-days-repeater">
                                                        <div data-repeater-list="wacc-date" class="drag">
                                                            <?php
                                                            if( ! empty( $agents ) && is_array( $agents ) && $settings_visible_date !== 'all' )
                                                            {
                                                                $script = '<script>';
                                                                foreach( $agents as $key => $agent )
                                                                {
                                                                    $hide = ( $key === 0 ) ? 'd-none' : '';
                                                                    $working_time = maybe_unserialize( $agent->working_time );
                                                                    if( ! empty( $working_time ) )
                                                                    {
                                                                        $empty_work_time = false;
                                                                        echo '<div class="row wacc-days-item" data-repeater-item>
                                                                            <div class="col-md-11">
                                                                                <div class="select-agents">
                                                                                    <label>' . __( 'Agent name:', 'wacc' ) . '</label>
                                                                                    <select name="wacc-date[' . $key . '][agent]" class="mt-2 mb-3" data-live-search="true" data-width="100%" required>
                                                                                        ' . $options . '
                                                                                    </select>
                                                                                </div>
                                                                                <div class="table-responsive">
                                                                                    <table class="table table-dark table-striped table-hover" data-id="' . $agent->ID . '">
                                                                                        <thead>
                                                                                            <tr>
                                                                                                <th>' . __( 'Day', 'wacc') . '</th>
                                                                                                <th>' . __( 'Start Time', 'wacc') . '</th>
                                                                                                <th>' . __( 'Finish Time', 'wacc') . '</th>
                                                                                            </tr>
                                                                                        </thead>
                                                                                        <tbody id="tBody">
                                                                                            ' . $week_days . '
                                                                                        </tbody>
                                                                                    </table>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-1 d-flex justify-content-end align-items-center ' . $hide . '">
                                                                                <span data-repeater-delete class="btn btn-danger btn-sm">
                                                                                    <i class="fa-solid fa-trash"></i>
                                                                                </span>
                                                                            </div>
                                                                        </div>';
                                                                        $script .= 'jQuery(\'select[name="wacc-date[' . $key . '][agent]"]\').addClass("selectpicker").selectpicker("val", "' . $agent->ID . '");
                                                                        jQuery(".start-time input").timepicker({ "timeFormat": "H:i:s", "step": 15, "scrollDefault": "09:00" });
                                                                        jQuery(".finish-time input").timepicker({ "timeFormat": "H:i:s", "step": 15, "scrollDefault": "17:00" });
                                                                        jQuery(\'table[data-id="' . $agent->ID . '"] > #tBody tr\').each(function(){';
                                                                        foreach( $working_time as $time )
                                                                        {
                                                                            $script .= 'if( jQuery(this).find(".day").text() == \'' . $time['day'] . '\' ){
                                                                                jQuery(this).find(".start-time input").timepicker(\'setTime\', \'' . $time['start'] . '\');
                                                                                jQuery(this).find(".finish-time input").timepicker(\'setTime\', \'' . $time['end'] . '\');
                                                                            }';
                                                                        }
                                                                        $script .= '});';
                                                                    }
                                                                }
                                                                $script .= '</script>';
                                                                wp_add_inline_script( 'wacc', $script );
                                                            }
                                                            if( $empty_work_time ) { ?>
                                                                <div class="row wacc-days-item" data-repeater-item>
                                                                    <div class="col-md-11">
                                                                        <div class="select-agents">
                                                                            <label><?php _e( 'Agent name:', 'wacc' ); ?></label>
                                                                            <select name="wacc-date[0][agent]" class="mt-2 mb-3" data-live-search="true" data-width="100%" required>
                                                                                <?php echo $options; ?>
                                                                            </select>
                                                                        </div>
                                                                        <div class="table-responsive">
                                                                            <table class="table table-dark table-striped table-hover">
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th><?php _e( 'Day', 'wacc'); ?></th>
                                                                                        <th><?php _e( 'Start Time', 'wacc'); ?></th>
                                                                                        <th><?php _e( 'Finish Time', 'wacc'); ?></th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody id="tBody">
                                                                                    <?php echo $week_days; ?>
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-1 d-flex justify-content-end align-items-center d-none">
                                                                        <span data-repeater-delete class="btn btn-danger btn-sm">
                                                                            <i class="fa-solid fa-trash"></i>
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            <?php } ?>
                                                        </div>
                                                        <div class="form-group row">
                                                            <div class="col-md-offset-1 col-md-11">
                                                                <span data-repeater-create class="btn btn-info btn-md">
                                                                    <i class="fa-solid fa-plus"></i> <?php _e( 'Add', 'wacc' ); ?>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </section>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
                <button class="btn btn-success wacc-save w-100 mt-3"><?php _e( 'Save', 'wacc' ); ?></button>
            </div>

        </section>

    </div>

<?php } ?>