$(document).ready(function () {
    $(".page-scroll").bind(
        "click", function (event) {
            var anchor = $(this);
            $("html, body").stop().animate(
                {
                    scrollTop: ($(anchor.attr("href")).offset().top - 0)
                }, 1250
            );
            event.preventDefault();
        }
    );

    $(window).scroll(function () {

        if ($(this).scrollTop() > 400) {
            $('#back-bottom').fadeOut();
            $('#back-top').fadeIn();
        } else {
            $('#back-bottom').fadeIn();
            $('#back-top').fadeOut();
        }


    });


});

