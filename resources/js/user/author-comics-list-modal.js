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

    // Удаление комикса
    $('.delete-comic-btn').on('click', function (e) {
        e.preventDefault();
        const comicId = $(this).data('comic-id');
        const comicName = $(this).data('comic-name');

        $('#delete-comic-modal-text').text(`Вы уверены, что хотите удалить авторский комикс "${comicName}"?`);
        $('#delete-comic-form').attr('action', `/dashboard/author_comics/${comicId}`);

        openModal($('#delete-comic-modal'));
    });

    // Закрыть модалку удаления
    $('#delete-comic-modal-close, #cancel-delete-comic').on('click', function () {
        closeModal($('#delete-comic-modal'));
    });

    // Редактирование комикса
    $('.edit-comic-btn').on('click', function (e) {
        e.preventDefault();

        const comicId = $(this).data('comic-id');
        const comicName = $(this).data('comic-name');
        const comicDescription = $(this).data('comic-description');
        const comicGenres = $(this).data('comic-genres');
        const comicCover = $(this).data('comic-cover');

        $('#edit-comic-id').val(comicId);
        $('#edit-comic-name').val(comicName);
        $('#edit-comic-description').val(comicDescription);
        $('#edit-comic-genres').val(comicGenres);
        $('#edit-comic-form').attr('action', `/dashboard/author_comics/${comicId}`);

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

        let isValid = true;
        const form = $(this);
        const name = $('#edit-comic-name').val().trim();
        const description = $('#edit-comic-description').val().trim();

        if (!name) {
            showError('edit-comic-name', 'Название обязательно');
            isValid = false;
        }
        if (!description) {
            showError('edit-comic-description', 'Описание обязательно');
            isValid = false;
        }

        if (isValid) {
            $.ajax({
                url: `/dashboard/author_comics/${$('#edit-comic-id').val()}`,
                method: 'POST',
                data: new FormData(this),
                processData: false,
                contentType: false,
                success: function () {
                    window.location.reload();
                },
                error: function (xhr) {
                    const errors = xhr.responseJSON.errors || {};
                    for (const [field, messages] of Object.entries(errors)) {
                        showError(`edit-comic-${field}`, messages[0]);
                    }
                }
            });
        }
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
});
