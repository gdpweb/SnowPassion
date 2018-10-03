$(document).ready(function () {

    $('.delete').click(function () {

        let titre;

        if ($(this).attr('title') === null) {
            titre = 'SnowPassion';
        } else {
            titre = $(this).attr('title');
        }
        $('.modal-title').html(titre);
        const url = $(this).data('href');
        $.ajax({
            type: 'post',
            url: url,
            success: function (data) {
                $('.modal-body').html(data);
                $('.modal').modal("show");
            }
        });
    });

    $(".msg").delay(2000).fadeOut(1000, function () {
        $(this).remove();
    });


});