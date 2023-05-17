<?php

namespace WhatsAppChatChitavo\Inc\Ajax;

defined( 'ABSPATH' ) || exit; // Prevent direct access

use WhatsAppChatChitavo\Inc\WACCHelper;

if ( !class_exists( 'WACCSave' ) ) {

    class WACCSave {

        /**
         * Save data ajax callback
         *
         * @return void
         */
        public static function handler(): void
        {
            ! check_ajax_referer( 'wacc_action', 'nonce', false )
                AND wp_send_json_error( array( 'message' => __( 'Security error!', 'wacc' ) ) );

            if ( ! isset( $_POST['data'] ) || empty( $_POST['data'] ) )
            {
                wp_send_json_error( array( 'message' => __( 'Data not set or empty!', 'wacc' ) ) );
            }

            global $wpdb;

            $agents_table = $wpdb->prefix . "wacc_agents";

            $data = $_POST['data'][0];

            $options_result = update_option( 'wacc-settings', array(
                    'chat_icon' => $data['icon'],
                    'show_icon_text' => $data['show_icon_text'],
                    'icon_text' => $data['icon_text'],
                    'icon_position' => $data['icon_position'],
                    'chat_header_logo' => $data['header_logo'],
                    'chat_header_text' => $data['header_text'],
                    'chat_footer_text' => $data['footer_text'],
                    'play_sound_on_popup' => $data['play_sound'] == '1' ? 1 : 0,
                    'popup_sound' => $data['welcome_alert'],
                    'email_button' => $data['email_button'],
                    'email_address' => $data['email_address'],
                    'faq_button' => $data['faq_button'],
                    'faq_url' => $data['faq_url'],
                    'theme' => $data['theme'],
                    'visible_date' => ( $data['day_time'] === ['all'] || empty( $data['day_time'] ) ) ? 'all' : '',
                    'visible_for_device_type' => ! empty( $data['devices'] ) ? implode( ",", $data['devices'] ) : '',
                    'visible_for_visitors' => ! empty( $data['visitors'] ) ? implode( ",", $data['visitors'] ) : '',
                    'visible_in_pages' => ! empty( $data['pages'] ) ? implode( ",", $data['pages'] ) : ''
                )
            );

            $existence_agents = $wpdb->get_results(
                "SELECT `ID` FROM {$agents_table}"
            );
            $agents = $data['agents'];
            $insert = $update = 0;
            $insert_result = $update_result = false;
            $queries = $insert_data = [];
            $current_user_id = get_current_user_id();
            $day_times = $data['day_time'];

            if( !empty( $agents ) )
            {
                foreach( $agents as $key => $agent )
                {
                    $working_time = [];
                    if( $day_times === ['all'] || empty( $day_times ) )
                    {
                        $working_time = ['all'];
                    }else {
                        foreach( $day_times as $day_time )
                        {
                            if( $day_time['agent_id'] == $agent['id'] )
                            {
                                foreach( $day_time['days'] as $day )
                                {
                                    $working_time[] = array(
                                        'day' => $day['day'],
                                        'start' => $day['start'],
                                        'end' => $day['end']
                                    );
                                }
                            }
                        }
                    }
                    if( ! empty( $existence_agents ) && is_array( $existence_agents ) )
                    {
                        foreach( $existence_agents as $existence_agent )
                        {
                            if( $existence_agent->ID == $agent['id'] )
                            {
                                $update = 1;
                                $queries[] = sprintf(
                                    "UPDATE {$agents_table} SET `name` = '%s', `position` = '%s', `phone` = '%s', `avatar` = '%s', `working_time` = '%s', `updater` = %d, `update_date` = '%s' WHERE `ID` = %d;",
                                    $agent['name'],
                                    $agent['position'],
                                    $agent['phone'],
                                    $agent['avatar'],
                                    maybe_serialize( $working_time ),
                                    $current_user_id,
                                    date( 'Y-m-d H:i:s' ),
                                    $agent['id']
                                );
                                continue 2;
                            }
                        }
                    }
                    unset( $agents[$key]['id'] );
                    $agents[$key]['working_time'] = maybe_serialize( $working_time );
                    $agents[$key]['registrar'] = $current_user_id;
                    $agents[$key]['updater'] = $current_user_id;
                    $agents[$key]['creation_date'] = date( 'Y-m-d H:i:s' );
                    $agents[$key]['update_date'] = date( 'Y-m-d H:i:s' );
                    $insert_data[] = $agents[$key];
                    $insert = 1;
                }
            }

            $wacc_helper = new WACCHelper();

            if( $insert === 1 )
            {
                $insert_result = $wacc_helper->insert_multiple( $agents_table, $insert_data );
            }

            if( $update === 1 )
            {
                $update_result = $wacc_helper->update_multiple( $queries );
            }

            if( ! $options_result && ! $insert_result && ! empty( $update_result ) )
            {
                wp_send_json_error( array( 'message' => __( 'An error occurred on saving data!', 'wacc' ) ) );
            }

            $agents = $wpdb->get_results(
                "SELECT `ID`, `name`, `position`, `phone`, `avatar`, `working_time` FROM {$agents_table}"
            );

            $agents_table_html =  '';

            if( ! empty( $agents ) && is_array( $agents ) )
            {
                foreach( $agents as $key => $agent )
                {
                    $hide = ( $key === 0 ) ? 'd-none' : '';
                    $agents_table_html .= '<div data-repeater-item="" class="wacc-agents" data-id="' . $agent->ID . '">
                        <div class="row form-group d-flex align-items-center">
                            <div class="col-md-2 my-3">
                                <img src="' . $agent->avatar . '" alt="' . $agent->name . '-' . __( 'avatar', 'wacc' ) . '" name="agent['.$key.'][avatar]" class="d-flex align-items-center m-auto agent-avatar" width="100">
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
                            <div class="col-md-1 mt-4 ' . $hide . '">
                                <span data-repeater-delete class="btn btn-danger btn-sm">
                                    <i class="fa-solid fa-trash"></i>
                                </span>
                            </div>
                        </div>
                    </div>';
                }
            }else {
                $agents_table_html .= '<div data-repeater-item class="wacc-agents">
                    <div class="row form-group d-flex align-items-center">
                        <div class="col-md-2 my-3">
                            <img src="' . WACC_URL . 'assets/img/avatars/avatar.png' . '" alt="' . __( 'Support avatar', 'wacc' ) . '" name="agent[0][avatar]" class="d-flex align-items-center m-auto agent-avatar" width="100" />
                            <button type="button" class="btn btn-sm btn-success w-100 upload-avatar">' . __( 'Choose avatar', 'wacc' ) . '</button>
                        </div>
                        <div class="col-md-3 my-3">
                            <label class="w-100 control-label mb-1">' . __( 'Name', 'wacc' ) . '</label>
                            <input type="text" name="agent[0][name]" value="' . __( 'Support', 'wacc' ) . '" class="form-control">
                        </div>
                        <div class="col-md-3 my-3">
                            <label class="w-100 control-label mb-1">' . __( 'Position', 'wacc' ) . '</label>
                            <input type="text" name="agent[0][position]" value="' . __( 'Sale manager', 'wacc' ) . '" class="form-control">
                        </div>
                        <div class="col-md-3 my-3">
                            <label class="w-100 control-label mb-1">' . __( 'Phone', 'wacc' ) . '</label>
                            <input type="text" name="agent[0][phone]" value="' . __( '+15551234', 'wacc' ) . '" class="form-control">
                        </div>
                        <div class="col-md-1 mt-4 d-none">
                            <span data-repeater-delete class="btn btn-danger btn-sm">
                                <i class="fa-solid fa-trash"></i>
                            </span>
                        </div>
                    </div>
                </div>';
            }

            $plugin_settings = get_option( 'wacc-settings' );
            $settings_visible_date = isset( $plugin_settings['visible_date'] ) ? $plugin_settings['visible_date'] : '';
            $checked = $settings_visible_date === 'all' ? 'checked' : '';
            $empty_work_time = true;
            $script = '';
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

            $agents_working_time = '<h2>' . __( 'Date & Time', 'wacc' ) . '</h2>
            <small class="mb-3">' . __( 'Choose to display the chat widget on all or specific day(s).', 'wacc' ) . '</small>
            <div class="row mt-3">
                <div class="col-md-12">
                    <input type="checkbox" name="wacc_days" id="wacc-days" class="m-0 wacc-all-content" ' . $checked . '>
                    <label for="wacc-days">' . __( 'Display on all days and hours', 'wacc' ) . '</label>
                </div>
                <h6 class="my-3">' . __( 'Or choose specific day(s) and hour(s) per agent(s):', 'wacc' ) . '</h6>
                <div class="wacc-toggle wacc-days">
                    <div class="col-md-12 wacc-days-repeater">
                        <div data-repeater-list="wacc-date" class="drag">';
                            if( ! empty( $agents ) && is_array( $agents ) && $settings_visible_date !== 'all' )
                            {
                                $script .= '<script id="wacc-js-footer">';
                                foreach( $agents as $key => $agent )
                                {
                                    $hide = ( $key === 0 ) ? 'd-none' : '';
                                    $working_time = maybe_unserialize( $agent->working_time );
                                    if( ! empty( $working_time ) )
                                    {
                                        $empty_work_time = false;
                                        $agents_working_time .= '<div class="row wacc-days-item" data-repeater-item>
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
                            }
                            if( $empty_work_time ) {
                                $agents_working_time .= '<div class="row wacc-days-item" data-repeater-item>
                                    <div class="col-md-11">
                                        <div class="select-agents">
                                            <label>' . __( 'Agent name:', 'wacc' ) . '</label>
                                            <select name="wacc-date[0][agent]" class="mt-2 mb-3" data-live-search="true" data-width="100%" required>
                                                ' . $options . '
                                            </select>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table table-dark table-striped table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>' . __( 'Day', 'wacc') . '</th>
                                                        <th>' . __( 'Start Time', 'wacc') . '</th>
                                                        <th>' . __( 'Finish Time', 'wacc'). '</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="tBody">
                                                    ' . $week_days . '
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="col-md-1 d-flex justify-content-end align-items-center d-none">
                                        <span data-repeater-delete class="btn btn-danger btn-sm">
                                            <i class="fa-solid fa-trash"></i>
                                        </span>
                                    </div>
                                </div>';
                            }
                        $agents_working_time .= '</div>
                        <div class="form-group row">
                            <div class="col-md-offset-1 col-md-11">
                                <span data-repeater-create class="btn btn-info btn-md">
                                    <i class="fa-solid fa-plus"></i> ' . __( 'Add', 'wacc' ) . '
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>';

            wp_send_json_success( array( 'message' => __( 'Data saved successfully.', 'wacc' ), 'agents' => $agents_table_html, 'agents_working_time' => $agents_working_time, 'script' => $script ) );

        }
    }
}