$(document).ready(function () {
    $('.add,.update').click(function () {
        let titre;
        if ($(this).attr('title') === null) {
            titre = 'SnowPassion';
        } else {
            titre = $(this).attr('title');
        }
        $('.modal-title').html(titre);
        let url = $(this).data('href');
        $.ajax({
            type: 'post',
            url: url,
            success: function (data) {
                $('.modal-body').html(data);
                $('.modal').modal("show");
                $('#form').submit(function (e) {
                    e.preventDefault();
                    url = $(this).attr('action');
                    submitForm(url, this);
                });
            }
        });
    });


    function submitForm(url, dataForm) {
        $.ajax({
            type: 'post',
            url: url,
            data: new FormData(dataForm),
            contentType: false,
            cache: false,
            processData: false,
            success: function (data) {
                let result = $(data).find('#form-container');
                let error = $(data).find('.has-error');

                if (error.length) {
                    $('#form-container').html(result).addClass('has-error');
                } else {
                    window.location.reload();
                }
            }
        });
    }
});