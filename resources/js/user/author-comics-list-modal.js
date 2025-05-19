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
        $('input, textarea, select').removeClass('is-invalid');
    }

    function showError(fieldId, message) {
        $(`#${fieldId}-error`).text(message).addClass('error');
        $(`#${fieldId}`).addClass('is-invalid');
    }

    // Удаление комикса
    $('.delete-comic-btn').on('click', function (e) {
        e.preventDefault();
        const comicSlug = $(this).data('comic-slug');
        const comicName = $(this).data('comic-name');

        $('#delete-comic-modal-text').text(`Вы уверены, что хотите удалить авторский комикс "${comicName}"?`);
        $('#delete-comic-form').attr('action', `/profile/comics/${comicSlug}`);

        openModal($('#delete-comic-modal'));
    });

    // Закрыть модалку удаления
    $('#delete-comic-modal-close, #cancel-delete-comic').on('click', function () {
        closeModal($('#delete-comic-modal'));
    });

    // Редактирование комикса
    $('.edit-comic-btn').on('click', function (e) {
        e.preventDefault();

        const comicSlug = $(this).data('comic-slug');
        const comicName = $(this).data('comic-name');
        const comicDescription = $(this).data('comic-description');
        const comicGenres = $(this).data('comic-genres');
        const comicAgeRestriction = $(this).data('comic-age-restriction');
        const comicCover = $(this).data('comic-cover');


        $('#edit-comic-slug').val(comicSlug);
        $('#edit-comic-name').val(comicName);
        $('#edit-comic-description').val(comicDescription);
        $('#edit-comic-genres').val(comicGenres);
        $('#edit-comic-age-restriction').val(comicAgeRestriction || '0+');
        $('#edit-comic-form').attr('action', `/profile/comics/${comicSlug}`);

        if (comicCover) {
            $('#edit-comic-cover-preview').html(`<img src="${comicCover}" alt="Текущая обложка" style="max-width: 100%; border-radius: 16px;">`);
        } else {
            $('#edit-comic-cover-preview').html('');
        }

        openModal($('#edit-comic-modal'));
    });

    // Закрыть модалку редактирования
    $('#edit-comic-modal-close, #cancel-edit-comic').on('click', function () {
        closeModal($('#edit-comic-modal'));
    });

    // Обработка формы редактирования
    $('#edit-comic-form').on('submit', function (e) {
        e.preventDefault();
        clearErrors();

        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: new FormData(this),
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function () {
                window.location.reload();
            },
            error: function (xhr) {
                const errors = xhr.responseJSON?.errors || {};
                for (const [field, messages] of Object.entries(errors)) {
                    showError(`edit-comic-${field}`, messages[0]);
                }
            }
        });
    });

    // Обработка выбора новой обложки
    $('#edit-comic-cover').on('change', function () {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                $('#edit-comic-cover-preview').html(`<img src="${e.target.result}" alt="Предпросмотр" style="max-width: 100%; border-radius: 16px;">`);
            };
            reader.readAsDataURL(file);
        }
    });

    // Обработка кнопки публикации
    $('.publish-comic-btn').on('click', function (e) {
        e.preventDefault();
        const comicSlug = $(this).data('comic-slug');

        $.ajax({
            url: `/profile/comics/${comicSlug}/publish`,
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function () {
                window.location.reload();
            },
            error: function (xhr) {
                alert('Ошибка при публикации комикса: ' + (xhr.responseJSON?.message || 'Неизвестная ошибка'));
            }
        });
    });
});