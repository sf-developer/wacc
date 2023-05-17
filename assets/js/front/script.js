jQuery(function($){
    $(document).on("click", "#main-button", function() {
        $(this).toggleClass("open");
        $(this).find(".wacc-close-icon").first().toggleClass("wacc-d-none");
        $(this).find(".wacc-chat-icon").toggleClass("wacc-d-none");
        $("#w-400").toggleClass("active");
    });

    $(".theme3 .nta-wabutton").hover(
        function() {
            $(this).addClass("wacc-hover");
        }, function() {
            $(this).removeClass("wacc-hover");
        }
    );
});
