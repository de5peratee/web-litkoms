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

    $('#edit-catalog-form').on('submit', function (e) {
        e.preventDefault();
        clearErrors();

        let isValid = true;
        const form = $(this);
        const name = $('#edit-catalog-name').val().trim();
        const author = $('#edit-catalog-author').val().trim();
        const catalogId = $('#edit-catalog-id').val();

        if (!name) {
            showError('edit-catalog-name', 'Название обязательно');
            isValid = false;
        }
        if (!author) {
            showError('edit-catalog-author', 'Автор обязателен');
            isValid = false;
        }

        if (isValid) {
            $.ajax({
                url: `/dashboard/catalogs/${catalogId}`,
                method: 'POST',
                data: new FormData(this),
                processData: false,
                contentType: false,
                success: function (response) {
                    window.location.href = '/dashboard/catalogs';
                },
                error: function (xhr) {
                    const errors = xhr.responseJSON.errors || {};
                    for (const [field, messages] of Object.entries(errors)) {
                        showError(`edit-catalog-${field}`, messages[0]);
                    }
                }
            });
        }
    });

    $('.delete-catalog-btn').on('click', function (e) {
        e.preventDefault();
        const catalogId = $(this).data('catalog-id');
        const catalogName = $(this).data('catalog-name');

        $('#delete-catalog-modal-text').text(`Вы уверены, что хотите удалить "${catalogName}" из каталога?`);
        $('#delete-catalog-form').attr('action', `/dashboard/catalogs/${catalogId}`);

        openModal($('#delete-catalog-modal'));
    });

    $('.edit-catalog-btn').on('click', function (e) {
        e.preventDefault();

        const catalogId = $(this).data('catalog-id');
        const catalogName = $(this).data('catalog-name');
        const catalogAuthor = $(this).data('catalog-author');
        const catalogDescription = $(this).data('catalog-description');
        const catalogReleaseYear = $(this).data('catalog-release_year');
        const catalogGenres = $(this).data('catalog-genres');
        const catalogCover = $(this).data('catalog-cover');

        $('#edit-catalog-id').val(catalogId);
        $('#edit-catalog-name').val(catalogName);
        $('#edit-catalog-author').val(catalogAuthor);
        $('#edit-catalog-description').val(catalogDescription);
        $('#edit-catalog-release_year').val(catalogReleaseYear);
        $('#edit-catalog-genres').val(catalogGenres);
        $('#edit-catalog-form').attr('action', `/catalogs/${catalogId}`);

        if (catalogCover) {
            $('#edit-catalog-cover-preview').html(`<img src="${catalogCover}" alt="Текущая обложка" style="max-width: 100%; border-radius: 16px;">`);
        } else {
            $('#edit-catalog-cover-preview').html('');
        }

        openModal($('#edit-catalog-modal'));
    });

    $('#delete-catalog-modal-close, #cancel-delete-catalog').on('click', function () {
        closeModal($('#delete-catalog-modal'));
    });

    $('#edit-catalog-modal-close, #cancel-edit-catalog').on('click', function () {
        closeModal($('#edit-catalog-modal'));
    });

    $('#edit-catalog-cover').on('change', function () {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                $('#edit-catalog-cover-preview').html(`<img src="${e.target.result}" alt="Предпросмотр" style="max-width: 100%; border-radius: 16px;">`);
            };
            reader.readAsDataURL(file);
        }
    });
});