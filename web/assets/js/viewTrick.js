$(document).ready(function () {

    $('.btn-comments').click(function () {

        $('.pagination').children().removeClass('active');
        $(this).parent().addClass('active');

        let ele = document.getElementById('commentaires');
        let ancre = $(ele).offset().top;
        const url = $(this).data('href');

        $.ajax({
            type: 'post',
            url: url,
            success: function (data) {
                $('#commentaires').html(data);
                $("HTML, BODY").animate({scrollTop: ancre - 60}, 1000);
            }
        });
    });
    $('#media-visible').click(function () {

        $('#media').removeClass('hidden-sm hidden-xs');
        $(this).addClass('hidden');
    });

});