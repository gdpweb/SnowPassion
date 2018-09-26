$(document).ready(function () {

    let index = 5;

    $('#btn-comments').click(function () {

        const url = $(this).data('href') + '/' + index;
        $.ajax({
            type: 'post',
            url: url,
            success: function (data) {
                $('#commentaires').html(data);
                index = index + 5;
            }
        });
    });

});