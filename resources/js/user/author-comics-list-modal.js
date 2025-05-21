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

    $('#edit-comic-form').on('submit', function (e) {
        e.preventDefault();
        clearErrors();

        let isValid = true;
        const form = $(this);
        const name = $('#edit-comic-name').val().trim();
        const description = $('#edit-comic-description').val().trim();
        const ageRestriction = $('#edit-comic-age_restriction').val();
        const comicId = $('#edit-comic-id').val();

        if (!name) {
            showError('edit-comic-name', 'Название обязательно');
            isValid = false;
        }
        if (!description) {
            showError('edit-comic-description', 'Описание обязательно');
            isValid = false;
        }
        if (!ageRestriction) {
            showError('edit-comic-age_restriction', 'Возрастное ограничение обязательно');
            isValid = false;
        }

        if (isValid) {
            $.ajax({
                url: `/profile/comics/${comicId}`,
                method: 'POST',
                data: new FormData(this),
                processData: false,
                contentType: false,
                success: function (response) {
                    window.location.href = '/profile/comics';
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

    $('.delete-comic-btn').on('click', function (e) {
        e.preventDefault();
        const comicId = $(this).data('comic-id');
        const comicName = $(this).data('comic-name');

        $('#delete-comic-modal-text').text(`Вы уверены, что хотите удалить комикс "${comicName}"?`);
        $('#delete-comic-form').attr('action', `/profile/comics/${comicId}`);

        openModal($('#delete-comic-modal'));
    });

    $('.edit-comic-btn').on('click', function (e) {
        e.preventDefault();

        const comicId = $(this).data('comic-id');
        const comicName = $(this).data('comic-name');
        const comicDescription = $(this).data('comic-description');
        const comicAgeRestriction = $(this).data('comic-age_restriction');
        const comicGenres = $(this).data('comic-genres');
        const comicCover = $(this).data('comic-cover');
        const comicFileName = $(this).data('comic-file-name');

        $('#edit-comic-id').val(comicId);
        $('#edit-comic-name').val(comicName);
        $('#edit-comic-description').val(comicDescription);
        $('#edit-comic-age_restriction').val(comicAgeRestriction || '0+'); // Fallback to '0+' if undefined
        $('#edit-comic-genres').val(comicGenres);
        $('#edit-comic-form').attr('action', `/profile/comics/${comicId}`);

        if (comicCover) {
            $('#edit-comic-cover-preview').html(`<img src="${comicCover}" alt="Текущая обложка" style="max-width: 100%; border-radius: 16px;">`);
        } else {
            $('#edit-comic-cover-preview').html('');
        }

        if (comicFileName) {
            $('#edit-comic-file-label').text(comicFileName);
            $('#edit-comic-file-preview').html(`<p>Текущий файл: ${comicFileName}</p>`);
        } else {
            $('#edit-comic-file-label').text('Выберите файл');
            $('#edit-comic-file-preview').html('<p>Файл не прикреплен</p>');
        }

        openModal($('#edit-comic-modal'));
    });

    $('#delete-comic-modal-close, #cancel-delete-comic').on('click', function () {
        closeModal($('#delete-comic-modal'));
    });

    $('#edit-comic-modal-close, #cancel-edit-comic').on('click', function () {
        closeModal($('#edit-comic-modal'));
    });

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

    $('#edit-comic-file').on('change', function () {
        const file = this.files[0];
        if (file) {
            $('#edit-comic-file-label').text(file.name);
            $('#edit-comic-file-preview').html(`<p>Выбран файл: ${file.name}</p>`);
        } else {
            $('#edit-comic-file-label').text('Выберите файл');
            $('#edit-comic-file-preview').html('<p>Файл не прикреплен</p>');
        }
    });
});
