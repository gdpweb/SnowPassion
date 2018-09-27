$(document).ready(function () {

    $('.delete').click(function () {

        let titre;

        if ($(this).attr('title')===null) {
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

    let index = {};

   initializeMedia('video');

   initializeMedia('image');


    function initializeMedia(mediaType) {

        const container = $('div#appbundle_trick_' + mediaType + 's');

        index[mediaType] = container.find(':input').length;

        $('#add_' + mediaType).click(function () {
            addMedia(container, mediaType);
        });

        if (index[mediaType] === 0) {
            addMedia(container, mediaType);
        } else {
            container.children('div').each(function () {
                addDeleteLink($(this));
            });
        }
    }

    function addMedia(container, mediaType) {

        const titreMedia = mediaType.substr(0, 1).toUpperCase() + mediaType.substr(1, mediaType.length).toLowerCase();

        let template = container.attr('data-prototype')
            .replace(/__name__label__/g, titreMedia + ' n°' + (index[mediaType] + 1))
            .replace(/__name__/g, index[mediaType])
            .replace("form-group", 'form-group well-white');

        let prototype = $(template);

        addDeleteLink(prototype);
        container.append(prototype);
        index[mediaType]++;
    }

    function addDeleteLink(prototype) {

        const deleteLink = $('<input type="button" class="btn btn-danger" value="Supprimer"/>');
        prototype.append(deleteLink);

        deleteLink.click(function () {
            prototype.remove();
        });
    }
});