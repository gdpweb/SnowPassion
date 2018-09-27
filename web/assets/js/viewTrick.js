$(document).ready(function () {

    $('.btn-comments').click(function () {

        $('.pagination').children().removeClass('active');
        $(this).parent().addClass('active');

        let ele = document.getElementById('commentaires');

        const url = $(this).data('href');

        $.ajax({
            type: 'post',
            url: url,
            success: function (data) {
                $('#commentaires').html(data);
                $("HTML, BODY").animate({scrollTop: ele}, 1000);
            }
        });


    });
});