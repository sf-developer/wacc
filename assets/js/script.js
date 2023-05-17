
jQuery( function($) {
    // Variables
    const { __, _x, _n, _nx } = wp.i18n,
        $numItems = $('li.fancyTab').length,
        $tabLink = $( '#tabs-section .tab-link' ),
        $tabBody = $( '#tabs-section .tab-body' ),
        $settingsTabLink = $( '#settings-tabs-section .tab-link' ),
        $settingsTabBody = $( '#settings-tabs-section .tab-body' );
	var timerOpacity;

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

    Notiflix.Notify.init({
        position: 'center-bottom'
    });

    switch($numItems) {
        case 12:
            $("li.fancyTab").width('8.3%');
            break;
        case 11:
            $("li.fancyTab").width('9%');
            break;
        case 10:
            $("li.fancyTab").width('10%');
            break;
        case 9:
            $("li.fancyTab").width('11.1%');
            break;
        case 8:
            $("li.fancyTab").width('12.5%');
            break;
        case 7:
            $("li.fancyTab").width('14.2%');
            break;
        case 6:
            $("li.fancyTab").width('16.666666666666667%');
            break;
        case 5:
            $("li.fancyTab").width('20%');
            break;
        case 4:
            $("li.fancyTab").width('25%');
            break;
        case 3:
            $("li.fancyTab").width('33.3%');
            break;
        default:
            $("li.fancyTab").width('50%');
            break;
    }

	// Toggle Class
	const init = () => {
		// Menu Click
		$tabLink.off( 'click' ).on( 'click', function( e ) {
			// Prevent Default
			e.preventDefault();
			e.stopPropagation();

			// Clear Timers
			window.clearTimeout( timerOpacity );

			// Toggle Class Logic
			// Remove Active Classes
			$tabLink.removeClass( 'active ' );
			$tabBody.removeClass( 'active ' );
			$tabBody.removeClass( 'active-content' );

			// Add Active Classes
			$( this ).addClass( 'active' );
			$( $( this ).attr( 'href' ) ).addClass( 'active' );

			// Opacity Transition Class
			timerOpacity = setTimeout( () => {
				$( $( this ).attr( 'href' ) ).addClass( 'active-content' );
			}, 50 );
		} );

        // Menu Click
		$settingsTabLink.off( 'click' ).on( 'click', function( e ) {
			// Prevent Default
			e.preventDefault();
			e.stopPropagation();

			// Clear Timers
			window.clearTimeout( timerOpacity );

			// Toggle Class Logic
			// Remove Active Classes
			$settingsTabLink.removeClass( 'active ' );
			$settingsTabBody.removeClass( 'active ' );
			$settingsTabBody.removeClass( 'active-content' );

			// Add Active Classes
			$( this ).addClass( 'active' );
			$( $( this ).attr( 'href' ) ).addClass( 'active' );

			// Opacity Transition Class
			timerOpacity = setTimeout( () => {
				$( $( this ).attr( 'href' ) ).addClass( 'active-content' );
			}, 50 );
		} );
	};
    init();

    $('.wacc-chat-icon img').on('click', function(){
        $('.wacc-chat-icon img.active').removeClass('active');
        $(this).addClass('active');
    });
    $('select[name="wacc-date[0][agent]"').addClass("selectpicker").selectpicker();
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

    $(".drag").sortable({
        axis: "y",
        cursor: 'pointer',
        opacity: 0.5,
        forcePlaceholderSize: true,
        placeholder: "sortable-placeholder",
        delay: 150
    }).disableSelection();

    $(document).on('click', '.wacc-chat-header-logo', function(e){
        e.preventDefault();
        let logo = $(this);
        const logo_uploader = wp.media({
            title: __('Chat header logo', 'wacc'),
            button: {
                text: __('Use this image', 'wacc')
            },
            library: {
                type: 'image'
            },
            multiple: false,

        }).on('select', function() {
            var attachment = logo_uploader.state().get('selection').first().toJSON();
            logo.closest('#tab-2').find('#wacc-chat-header-logo > img').attr('src', attachment.url);
        })
        .open();
    });

    $(document).on('click', '.upload-avatar', function(e){
        e.preventDefault();
        let avatar = $(this);
        const avatar_uploader = wp.media({
            title: __('Agent avatar', 'wacc'),
            button: {
                text: __('Use this image', 'wacc')
            },
            library: {
                type: 'image'
            },
            multiple: false,

        }).on('select', function() {
            var attachment = avatar_uploader.state().get('selection').first().toJSON();
            avatar.closest('div').find('.agent-avatar').attr('src', attachment.url);
        })
        .open();
    });

    if($("#wacc-pages").is(":checked")){
        $(".wacc-pages").css(
            {
                "filter": "blur(1px)",
                "pointer-events": "none"
            }
        );
    }

    if($("#wacc-devices").is(":checked")){
        $(".wacc-devices").css(
            {
                "filter": "blur(1px)",
                "pointer-events": "none"
            }
        );
    }

    if($("#wacc-visitors").is(":checked")){
        $(".wacc-visitors").css(
            {
                "filter": "blur(1px)",
                "pointer-events": "none"
            }
        );
    }

    if($("#wacc-days").is(":checked")){
        $(".wacc-days").css(
            {
                "filter": "blur(1px)",
                "pointer-events": "none"
            }
        );
    }

    $(document).on('click', '.wacc-all-content', function () {
        if($(this).is(":checked")){
            $(this).closest(".row.mt-3").find(".wacc-toggle").css(
                {
                    "filter": "blur(1px)",
                    "pointer-events": "none"
                }
            );
        }else{
            $(this).closest(".row.mt-3").find(".wacc-toggle").css(
                {
                    "filter": "none",
                    "pointer-events": "all"
                }
            );
        }
    });

    if(!$("#wacc-email-button").is(":checked")){
        $(".wacc-email").css(
            {
                "filter": "blur(1px)",
                "pointer-events": "none"
            }
        );
    }

    if($("#wacc-play-alert").is(":checked")) {
        $("#audioPanel").show();
    }else{
        $("#audioPanel").hide();
    }

    $(document).on("change", "#wacc-play-alert", function() {
        if($(this).is(":checked")) {
            $("#audioPanel").show();
        }else{
            $("#audioPanel").hide();
        }
    });

    if(!$("#wacc-faq-button").is(":checked")){
        $(".wacc-toggle-faqs").css(
            {
                "filter": "blur(1px)",
                "pointer-events": "none"
            }
        );
    }

    $(document).on('click', '.wacc-faq-content', function () {
        if($(this).is(":checked")){
            $(this).closest(".row.mt-3").find(".wacc-faq-toggle").css(
                {
                    "filter": "none",
                    "pointer-events": "all"
                }
            );
        }else{
            $(this).closest(".row.mt-3").find(".wacc-faq-toggle").css(
                {
                    "filter": "blur(1px)",
                    "pointer-events": "none"
                }
            );
        }
    });

    new Quill('#wacc-text', {
        modules: {
            toolbar: toolbarOptions
        },
        theme: 'snow'
    });

    new Quill('#wacc-chat-header', {
        modules: {
            toolbar: toolbarOptions
        },
        theme: 'snow'
    });

    new Quill('#wacc-chat-footer', {
        modules: {
            toolbar: toolbarOptions
        },
        theme: 'snow'
    });

    $(document).on("click", "#show-icon-text", function() {
        $(".wacc-icon-text").toggle();
    });

    $(".theme-preview").text(__( 'Preview', 'wacc' ) + ' ' + $("#wacc-theme option:selected").text() + ':');

    $(document).on("change", "#wacc-theme", function() {
        let optionSelected = $("option:selected", this);
        $(".theme-preview").text(__( 'Preview', 'wacc' ) + ' ' + optionSelected.text() + ':');
        $(this).closest(".row").find("img").attr("src", optionSelected.data("src"));
    });

});

jQuery(window).load(function() {
    jQuery('.fancyTabs').each(function() {
        var highestBox = 0;
        jQuery('.fancyTab a', this).each(function() {
            if (jQuery(this).height() > highestBox)
                highestBox = jQuery(this).height();
        });
        jQuery('.fancyTab a', this).height(highestBox);
    });
});


// globals
var
_player = document.getElementById("audio"),
_playlist = document.getElementById("playlist"),
_playprev = document.getElementById("playprev"),
_playnext = document.getElementById("playnext");

var isPlaying = false;

// functions
function setIsPlaying( audioEl, isPlaying ) {
    audioEl.setAttribute('playstate', isPlaying);
}

function getIsPlaying( audioEl ) {
  return audioEl.getAttribute('playstate');
}

function playPrev( selectEl ) {
  var prevIdx = selectEl.selectedIndex - 1;

  if (prevIdx!==-1){
    selectEl.selectedIndex = prevIdx;
    selectionChanged(_playlist);
    playOption(selectEl.options[selectEl.selectedIndex]);
  }
}

function nextOptionIdx( selectEl ) {
  var lastIdx = selectEl.options.length - 1;
  var nextIdx = selectEl.selectedIndex + 1;

  if (nextIdx>lastIdx) return -1;
  else return nextIdx;
}

function playNext( selectEl ) {
  var nextIdx = nextOptionIdx( selectEl );

  if (nextIdx!==-1){
    selectEl.selectedIndex = nextIdx;
    selectionChanged(_playlist);
    playOption(selectEl.options[selectEl.selectedIndex]);
  }
}

function playOption(option) {
  _player.src = option.getAttribute("data-mp3");

  if (getIsPlaying(_playlist)==="true")
    _player.play();
}

function selectionChanged(selectEl) {
  var curId = selectEl.selectedIndex;
  var lastIdx = selectEl.options.length - 1;

  _playprev.disabled = (curId === 0);
  _playnext.disabled = (curId === lastIdx);
}

_player.addEventListener("play", function(e) {
  setIsPlaying(_playlist, true);
});

_player.addEventListener("pause", function(e) {
  setIsPlaying(_playlist, false);
});

_player.addEventListener("ended", function(e) {
  setIsPlaying(_playlist, true);
});

_playprev.addEventListener("click", function(e) {
  playPrev(_playlist);
});

_playnext.addEventListener("click", function(e) {
  playNext(_playlist);
});

_playlist.addEventListener("change", function(e) {
  if (e.target && e.target.nodeName === "SELECT") {
    selectionChanged(_playlist);
    playOption(e.target.options[e.target.selectedIndex], !getIsPlaying(_player));
  }
});

selectionChanged(_playlist);
//Get the first song loaded but paused
playOption(_playlist.options[_playlist.selectedIndex], true);