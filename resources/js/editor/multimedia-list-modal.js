import $ from 'jquery';

$(function() {
    function openModal($modal) {
        $modal.removeClass('hidden').addClass('show');
        $('body').css('overflow', 'hidden');
    }

    function closeModal($modal) {
        $modal.removeClass('show').addClass('hidden');
        $('body').css('overflow', 'auto');
    }

    // Открыть модалку удаления
    $('.delete-post-btn').on('click', function(e) {
        e.preventDefault();
        const postName = $(this).data('post-name');

        $('#delete-modal-text').text(`Вы уверены, что хотите удалить пост "${postName}"?`);

        openModal($('#delete-modal'));
    });

    // Открыть модалку редактирования
    $('.edit-post-btn').on('click', function(e) {
        e.preventDefault();
        const postId = $(this).data('post-id');
        const postName = $(this).data('post-name');
        const postDesc = $(this).data('post-description');
        const postMedias = $(this).data('post-medias');

        $('#edit-post-id').val(postId);
        $('#edit-post-name').val(postName);
        $('#edit-post-description').val(postDesc);

        // Очистим старый превью
        const $preview = $('#edit-post-media-preview');
        $preview.empty();

        if (postMedias && postMedias.length) {
            postMedias.forEach(function(media) {
                if (media.type === 'image') {
                    $preview.append(`<img src="${media.url}" alt="media_image" style="max-width: 100%; margin-bottom: 10px; border-radius: 12px;">`);
                } else if (media.type === 'video') {
                    $preview.append(`<video controls style="max-width: 100%; margin-bottom: 10px; border-radius: 12px;"><source src="${media.url}" type="video/${media.ext}"></video>`);
                } else if (media.type === 'audio') {
                    $preview.append(`<audio controls style="width: 100%; margin-bottom: 10px;"><source src="${media.url}" type="audio/${media.ext}"></audio>`);
                } else {
                    $preview.append(`<p>Файл (${media.ext}) не поддерживается для предпросмотра.</p>`);
                }
            });
        } else {
            $preview.append('<p>Нет загруженных медиафайлов.</p>');
        }

        openModal($('#edit-modal'));
    });

    // Закрыть модалку удаления
    $('#delete-modal-close, #cancel-delete').on('click', function() {
        closeModal($('#delete-modal'));
    });

    // Закрыть модалку редактирования
    $('#edit-modal-close, #cancel-edit').on('click', function() {
        closeModal($('#edit-modal'));
    });
});
