<?php

namespace WhatsAppChatChitavo\Inc\Ajax;

defined( 'ABSPATH' ) || exit; // Prevent direct access

if ( !class_exists( 'WACCDelete' ) ) {

    class WACCDelete {

        /**
         * Delete data from db ajax callback
         *
         * @return void
         */
        public static function handler(): void
        {
            ! check_ajax_referer( 'wacc_action', 'nonce', false )
                AND wp_send_json_error( array( 'message' => __( 'Security error!', 'wacc' ) ) );

            if ( ! isset( $_POST['id'] ) )
            {
                wp_send_json_error( array( 'message' => __( 'Id not set!', 'wacc' ) ) );
            }

            global $wpdb;

            $table = $wpdb->prefix . "wacc_agents";

            $result = $wpdb->delete(
                $table,
                array( 'ID' => $_POST['id'] )
            );

            if( ! $result )
            {
                wp_send_json_error( array( 'message' => __( 'An error occurred on deleting data!', 'wacc' ) ) );
            }

            $agents = $wpdb->get_results(
                "SELECT `ID`, `name`, `position`, `phone`, `avatar`, `working_time` FROM {$table}"
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

            wp_send_json_success( array( 'message' => __( 'Data successfully deleted!', 'wacc' ), 'agents' => $agents_table_html, 'agents_working_time' => $agents_working_time, 'script' => $script ) );

        }
    }
}