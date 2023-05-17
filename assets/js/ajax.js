jQuery(function($) {
    const { __, _x, _n, _nx } = wp.i18n;

    var toolbarOptions = [
        ['bold', 'italic', 'underline', 'strike'],        // toggled buttons
        ['blockquote', 'code-block'],

        [{ 'header': 1 }, { 'header': 2 }],               // custom button values
        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
        [{ 'script': 'sub'}, { 'script': 'super' }],      // superscript/subscript
        [{ 'indent': '-1'}, { 'indent': '+1' }],          // outdent/indent
        [{ 'direction': 'rtl' }],                         // text direction

        [{ 'size': ['small', false, 'large', 'huge'] }],  // custom dropdown
        [{ 'header': [1, 2, 3, 4, 5, 6, false] }],

        [{ 'color': [] }, { 'background': [] }],          // dropdown with defaults from theme
        [{ 'font': [] }],
        [{ 'align': [] }],

        ['clean']                                         // remove formatting button
    ];

    $(document).on("click", ".wacc-save", function(e){
        e.preventDefault();
        let data = [],
            agents = [],
            pages = [],
            devices = [],
            visitors = [],
            day_time = [];

        $(".wacc-agents").each(function(k, v){
            let agent_id = $(this).data("id"),
                agent_avatar = $('img[name="agent['+k+'][avatar]"]').attr("src"),
                agent_name = $('input[name="agent['+k+'][name]"]').val(),
                agent_position = $('input[name="agent['+k+'][position]"]').val(),
                agent_phone = $('input[name="agent['+k+'][phone]"]').val();

            if(agent_avatar && agent_name && agent_position && agent_phone) {
                agents.push({
                    id: agent_id,
                    avatar: agent_avatar,
                    name: agent_name,
                    position: agent_position,
                    phone: agent_phone
                });
            }
        });

        if($("#wacc-pages").is(":checked")){
            pages.push("all");
        }else{
            $(".wacc-pages input").each(function(k, v){
                if($(this).is(":checked")) {
                    pages.push($(this).val());
                }
            });
        }

        if($("#wacc-devices").is(":checked")){
            devices.push("all");
        }else{
            $(".wacc-devices input").each(function(k, v){
                if($(this).is(":checked")) {
                    devices.push($(this).val());
                }
            });
        }

        if($("#wacc-visitors").is(":checked")){
            visitors.push("all");
        }else{
            $(".wacc-visitors input").each(function(k, v){
                if($(this).is(":checked")) {
                    visitors.push($(this).val());
                }
            });
        }

        if($("#wacc-days").is(":checked")){
            day_time.push("all");
        }else{
            $(".wacc-days-item").each(function(k, v){
                let days_agent_id = $('select[name="wacc-date['+k+'][agent]"] option:selected').val(),
                    days = [];

                $(this).find("#tBody tr").each(function(){
                    let start = $(this).find(".start-time > .time").val(),
                        end = $(this).find(".finish-time > .time").val();
                    if(start && end) {
                        days.push({
                            day: $.trim($(this).text()),
                            start: start,
                            end: end
                        });
                    }
                });

                if(days_agent_id && days) {
                    day_time.push({
                        agent_id: days_agent_id,
                        days: days
                    });
                }
            });
        }

        data.push({
            show_icon_text: $("#show-icon-text").is(":checked") ? '1' : '0',
            icon_text: $("#wacc-text").children().first().html(),
            icon: $(".wacc-chat-icon img.active").data("name"),
            icon_position: $("#widget-position option:selected").val(),
            header_logo: $("#wacc-chat-header-logo > img").attr("src"),
            header_text: $("#wacc-chat-header").children().first().html(),
            footer_text: $("#wacc-chat-footer").children().first().html(),
            agents: agents,
            play_sound: $("#wacc-play-alert").is(":checked") ? "1" : "0",
            welcome_alert: $("#wacc-play-alert").is(":checked") ? $("#playlist option:selected").data("name") : "",
            email_button: $("#wacc-email-button").is(":checked") ? "1" : "0",
            email_address: $("#wacc-mail").val(),
            faq_button: $("#wacc-faq-button").is(":checked") ? "1" : "0",
            faq_url: $("#wacc-faq").val(),
            theme: $("#wacc-theme option:selected").val(),
            pages: pages,
            devices: devices,
            visitors: visitors,
            day_time: day_time
        });
        $.ajax({
            type: "POST",
            url: wacc_ajax.ajaxurl,
            data: {
                action: "wacc_save",
                nonce: wacc_ajax._ajax_nonce,
                data: data
            },
            beforeSend: function () {
                Notiflix.Loading.standard();
            },
            success: function (response) {
                Notiflix.Loading.remove();
                if(response.success){
                    $(".wacc-agent-items").html(response.data.agents);
                    $("#settings-tab-4").html(response.data.agents_working_time);
                    $("#wacc-js-after").remove();
                    $("#wacc-js-footer").remove();
                    $("body").append(response.data.script);
                    $(".drag").sortable({
                        axis: "y",
                        cursor: 'pointer',
                        opacity: 0.5,
                        forcePlaceholderSize: true,
                        placeholder: "sortable-placeholder",
                        delay: 150
                    }).disableSelection();
                    $('.wacc-days-repeater .drag').each(function(k, v) {
                        $(this).find('select[name="wacc-date[' + k + '][agent]"').addClass("selectpicker").selectpicker();
                    });
                    $('.start-time input').timepicker({ 'timeFormat': 'H:i:s', 'step': 15, 'scrollDefault': '09:00' });
                    $('.finish-time input').timepicker({ 'timeFormat': 'H:i:s', 'step': 15, 'scrollDefault': '17:00' });
                    $('.wacc-days-repeater').repeater({
                        isFirstItemUndeletable: true,
                        show: function () {
                            $(this).find("select").first().addClass("selectpicker").selectpicker();
                            $('.start-time input').timepicker({ 'timeFormat': 'H:i:s', 'step': 15, 'scrollDefault': '09:00' });
                            $('.finish-time input').timepicker({ 'timeFormat': 'H:i:s', 'step': 15, 'scrollDefault': '17:00' });
                            $(this).find('.start-time input').timepicker('setTime', '');
                            $(this).find('.finish-time input').timepicker('setTime', '');
                            let select_options_html = $(this).find('select').html(),
                                index = $(".wacc-days-item").length - 1;
                            $(this).find('.select-agents').html('<label>' + __('Agent name:', 'wacc') + '</label><select name="wacc-date['+index+'][agent]" class="mt-2 mb-3" data-live-search="true" data-width="100%" required="">' + select_options_html + '<select>');
                            $(this).find('select').addClass('selectpicker').selectpicker();
                            $(this).find('.col-md-1.d-flex.justify-content-end.align-items-center').removeClass('d-none');
                            $(this).slideDown();
                        },
                        hide: function (deleteElement) {
                            let $this = $(this);
                            Notiflix.Confirm.show(
                                __( 'Confirm deletion', 'wacc' ),
                                __( 'Are you sure you want to delete this element?', 'wacc' ),
                                __( 'Yes', 'wacc' ),
                                __( 'No', 'wacc' ),
                                function okCb() {
                                    $this.slideUp(deleteElement);
                                },
                                function cancelCb() {}
                            );
                        }
                    });
                    if($("#wacc-days").is(":checked")){
                        $(".wacc-days").css(
                            {
                                "filter": "blur(1px)",
                                "pointer-events": "none"
                            }
                        );
                    }
                    Notiflix.Notify.success(response.data.message);
                }else{
                    Notiflix.Notify.warning(response.data.message);
                }
            },
            error: function (t, e, n) {
                Notiflix.Loading.remove();
                Notiflix.Notify.failure(e);
            }
        });
    });

    if ($(".wacc-repeater")[0]) {
        $('.wacc-repeater').repeater({
            isFirstItemUndeletable: true,
            show: function () {
                $(this).attr("data-id", 0);
                $(this).find(".agent-avatar").attr("src", window.location.origin + "/wp-content/plugins/whatsapp-chat-chitavo/assets/img/avatars/avatar.png");
                $(this).slideDown();
            },
            hide: function (deleteElement) {
                let $this = $(this);
                Notiflix.Confirm.show(
                    __( 'Confirm deletion', 'wacc' ),
                    __( 'Are you sure you want to delete this element?', 'wacc' ),
                    __( 'Yes', 'wacc' ),
                    __( 'No', 'wacc' ),
                    function okCb() {
                        if($this.data("id") == 0){
                            $this.slideUp(deleteElement);
                        }else{
                            $.ajax({
                                type: "POST",
                                url: wacc_ajax.ajaxurl,
                                data: {
                                    action: "wacc_delete",
                                    nonce: wacc_ajax._ajax_nonce,
                                    id: $this.data("id")
                                },
                                beforeSend: function() {
                                    Notiflix.Loading.standard();
                                },
                                success: function (response) {
                                    Notiflix.Loading.remove();
                                    if(response.success) {
                                        $(".wacc-agent-items").html(response.data.agents);
                                        $("#settings-tab-4").html(response.data.agents_working_time);
                                        $("#wacc-js-after").remove();
                                        $("#wacc-js-footer").remove();
                                        $("body").append(response.data.script);
                                        $(".drag").sortable({
                                            axis: "y",
                                            cursor: 'pointer',
                                            opacity: 0.5,
                                            forcePlaceholderSize: true,
                                            placeholder: "sortable-placeholder",
                                            delay: 150
                                        }).disableSelection();
                                        $('.wacc-days-repeater .drag').each(function(k, v) {
                                            $(this).find('select[name="wacc-date[' + k + '][agent]"').addClass("selectpicker").selectpicker();
                                        });
                                        $('.start-time input').timepicker({ 'timeFormat': 'H:i:s', 'step': 15, 'scrollDefault': '09:00' });
                                        $('.finish-time input').timepicker({ 'timeFormat': 'H:i:s', 'step': 15, 'scrollDefault': '17:00' });
                                        $('.wacc-days-repeater').repeater({
                                            isFirstItemUndeletable: true,
                                            show: function () {
                                                $(this).find("select").first().addClass("selectpicker").selectpicker();
                                                $('.start-time input').timepicker({ 'timeFormat': 'H:i:s', 'step': 15, 'scrollDefault': '09:00' });
                                                $('.finish-time input').timepicker({ 'timeFormat': 'H:i:s', 'step': 15, 'scrollDefault': '17:00' });
                                                $(this).find('.start-time input').timepicker('setTime', '');
                                                $(this).find('.finish-time input').timepicker('setTime', '');
                                                let select_options_html = $(this).find('select').html(),
                                                    index = $(".wacc-days-item").length - 1;
                                                $(this).find('.select-agents').html('<label>' + __('Agent name:', 'wacc') + '</label><select name="wacc-date['+index+'][agent]" class="mt-2 mb-3" data-live-search="true" data-width="100%" required="">' + select_options_html + '<select>');
                                                $(this).find('select').addClass('selectpicker').selectpicker();
                                                $(this).find('.col-md-1.d-flex.justify-content-end.align-items-center').removeClass('d-none');
                                                $(this).slideDown();
                                            },
                                            hide: function (deleteElement) {
                                                let $this = $(this);
                                                Notiflix.Confirm.show(
                                                    __( 'Confirm deletion', 'wacc' ),
                                                    __( 'Are you sure you want to delete this element?', 'wacc' ),
                                                    __( 'Yes', 'wacc' ),
                                                    __( 'No', 'wacc' ),
                                                    function okCb() {
                                                        $this.slideUp(deleteElement);
                                                    },
                                                    function cancelCb() {}
                                                );
                                            }
                                        });
                                        if($("#wacc-days").is(":checked")){
                                            $(".wacc-days").css(
                                                {
                                                    "filter": "blur(1px)",
                                                    "pointer-events": "none"
                                                }
                                            );
                                        }
                                        Notiflix.Notify.success(response.data.message);
                                    }else {
                                        Notiflix.Notify.warning(response.data.message);
                                    }
                                },
                                error: function (t, e, n) {
                                    Notiflix.Loading.remove();
                                    Notiflix.Notify.failure(e);
                                }
                            });
                        }

                    },
                    function cancelCb() {}
                );
            },
        });
    }

    $('#verify-envato-purchase').submit(function(e) {
        e.preventDefault();

        const regex = /^(\w{8})-((\w{4})-){3}(\w{12})$/;

        if ( regex.test( $("#input-purchase-code").val() ) ) {
            $.ajax({
                type: "POST",
                url: wacc_ajax.ajaxurl,
                data: {
                    action: "verify_purchase_code",
                    nonce: wacc_ajax._ajax_nonce,
                    purchase_code: $("#input-purchase-code").val()
                },
                beforeSend: function () {
                    Notiflix.Loading.standard();
                },
                success: function(response) {
                    Notiflix.Loading.remove();
                    if(response.success) {
                        Notiflix.Report.success(
                            __('Success', 'wacc'),
                            response.data.message,
                            __('Okay', 'wacc')
                        );
                    }else {
                        Notiflix.Notify.warning(response.data.message);
                    }
                },
                error: function(t, e, n) {
                    Notiflix.Loading.remove();
                    Notiflix.Notify.failure(e);
                }
            });
        }else {
            Notiflix.Notify.warning(__('The purchase code is not valid!', 'wacc'));
        }
    });
});