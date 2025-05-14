import $ from 'jquery';

$(function () {
    function openModal($modal) {
        $modal.removeClass('hidden').addClass('show');
        $('body').css('overflow', 'hidden');
    }

    function closeModal($modal) {
        $modal.removeClass('show').addClass('hidden');
        $('body').css('overflow', 'auto');
        clearErrors();
    }

    function clearErrors() {
        $('.input-error').text('').removeClass('error');
        $('input, textarea').removeClass('is-invalid');
    }

    function showError(fieldId, message) {
        $(`#${fieldId}-error`).text(message).addClass('error');
        $(`#${fieldId}`).addClass('is-invalid');
    }

    // Валидация формы редактирования
    $('#edit-post-form').on('submit', function (e) {
        e.preventDefault();
        clearErrors();

        let isValid = true;
        const form = $(this);
        const name = $('#edit-post-name').val().trim();
        const description = $('#edit-post-description').val().trim();
        const postId = $('#edit-post-id').val();

        if (!name) {
            showError('edit-post-name', 'Название обязательно');
            isValid = false;
        }
        if (!description) {
            showError('edit-post-description', 'Описание обязательно');
            isValid = false;
        }

        if (isValid) {
            $.ajax({
                url: `/dashboard/mediaposts/${postId}`,
                method: 'POST',
                data: new FormData(this),
                processData: false,
                contentType: false,
                success: function (response) {
                    window.location.href = '/dashboard/mediaposts';
                },
                error: function (xhr) {
                    const errors = xhr.responseJSON.errors || {};
                    for (const [field, messages] of Object.entries(errors)) {
                        showError(`edit-post-${field}`, messages[0]);
                    }
                }
            });
        }
    });

    $('.delete-post-btn').on('click', function (e) {
        e.preventDefault();
        const postId = $(this).data('post-id');
        const postName = $(this).data('post-name');

        $('#delete-modal-text').text(`Вы уверены, что хотите удалить пост "${postName}"?`);
        $('#delete-post-form').attr('action', `/dashboard/mediaposts/${postId}`);

        openModal($('#delete-modal'));
    });

    $('.edit-post-btn').on('click', function (e) {
        e.preventDefault();

        const postId = $(this).data('post-id');
        const postName = $(this).data('post-name');
        const postDescription = $(this).data('post-description');
        const postMedias = $(this).data('post-medias');

        $('#edit-post-id').val(postId);
        $('#edit-post-name').val(postName);
        $('#edit-post-description').val(postDescription);
        $('#edit-post-form').attr('action', `/dashboard/mediaposts/${postId}`);

        $('#edit-post-media-preview').empty();

        if (postMedias && postMedias.length > 0) {
            postMedias.forEach(media => {
                let previewHtml;
                if (media.type === 'image') {
                    previewHtml = `<img src="${media.url}" alt="Media" style="max-width: 100px; margin: 5px; border-radius: 8px;">`;
                } else if (media.type === 'video') {
                    previewHtml = `<video src="${media.url}" controls style="max-width: 100px; margin: 5px;"></video>`;
                } else if (media.type === 'audio') {
                    previewHtml = `<audio src="${media.url}" controls style="margin: 5px;"></audio>`;
                } else {
                    previewHtml = `<p>Неподдерживаемый тип: ${media.ext}</p>`;
                }
                $('#edit-post-media-preview').append(previewHtml);
            });
        }

        openModal($('#edit-modal'));
    });

    $('#delete-modal-close, #cancel-delete-post').on('click', function () {
        closeModal($('#delete-modal'));
    });

    $('#edit-modal-close, #cancel-edit-post').on('click', function () {
        closeModal($('#edit-modal'));
    });

    $('#edit-post-media').on('change', function () {
        const files = this.files;
        $('#edit-post-media-preview').empty();
        if (files.length > 0) {
            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                const reader = new FileReader();
                reader.onload = function (e) {
                    const previewHtml = `<img src="${e.target.result}" alt="Предпросмотр" style="max-width: 100px; margin: 5px; border-radius: 8px;">`;
                    $('#edit-post-media-preview').append(previewHtml);
                };
                reader.readAsDataURL(file);
            }
        }
    });
});