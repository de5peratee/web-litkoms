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

    $('#edit-event-form').on('submit', function (e) {
        e.preventDefault();
        clearErrors();

        let isValid = true;
        const form = $(this);
        const name = $('#edit-event-name').val().trim();
        const description = $('#edit-event-description').val().trim();
        const startDate = $('#edit-event-start_date').val();
        const time = $('#edit-event-time').val();
        const eventId = $('#edit-event-id').val();

        if (!name) {
            showError('edit-event-name', 'Название обязательно');
            isValid = false;
        }
        if (!description) {
            showError('edit-event-description', 'Описание обязательно');
            isValid = false;
        }
        if (!startDate) {
            showError('edit-event-start_date', 'Дата проведения обязательна');
            isValid = false;
        }
        if (!time) {
            showError('edit-event-time', 'Время начала обязательно');
            isValid = false;
        }

        if (isValid) {
            $.ajax({
                url: `/dashboard/events/${eventId}`,
                method: 'POST',
                data: new FormData(this),
                processData: false,
                contentType: false,
                success: function (response) {
                    window.location.href = '/dashboard/events';
                },
                error: function (xhr) {
                    const errors = xhr.responseJSON.errors || {};
                    for (const [field, messages] of Object.entries(errors)) {
                        showError(`edit-event-${field}`, messages[0]);
                    }
                }
            });
        }
    });

    $('.delete-event-btn').on('click', function (e) {
        e.preventDefault();
        const eventId = $(this).data('event-id');
        const eventName = $(this).data('event-name');

        $('#delete-event-modal-text').text(`Вы уверены, что хотите удалить мероприятие "${eventName}"?`);
        $('#delete-event-form').attr('action', `/dashboard/events/${eventId}`);

        openModal($('#delete-event-modal'));
    });

    $('.edit-event-btn').on('click', function (e) {
        e.preventDefault();

        const eventId = $(this).data('event-id');
        const eventName = $(this).data('event-name');
        const eventDescription = $(this).data('event-description');
        const eventStartDate = $(this).data('event-start_date');
        const eventTime = $(this).data('event-time');
        const eventGuests = $(this).data('event-guests');
        const eventTags = $(this).data('event-tags');
        const eventCover = $(this).data('event-cover');

        $('#edit-event-id').val(eventId);
        $('#edit-event-name').val(eventName);
        $('#edit-event-description').val(eventDescription);
        $('#edit-event-start_date').val(eventStartDate);
        $('#edit-event-time').val(eventTime);
        $('#edit-event-guests').val(eventGuests);
        $('#edit-event-tags').val(eventTags);
        $('#edit-event-form').attr('action', `/events/${eventId}`);

        if (eventCover) {
            $('#edit-event-cover-preview').html(`<img src="${eventCover}" alt="Текущая обложка" style="max-width: 100%; border-radius: 16px;">`);
        } else {
            $('#edit-event-cover-preview').html('');
        }

        openModal($('#edit-event-modal'));
    });

    // Закрыть модалку удаления
    $('#delete-event-modal-close, #cancel-delete-event').on('click', function () {
        closeModal($('#delete-event-modal'));
    });

    // Закрыть модалку редактирования
    $('#edit-event-modal-close, #cancel-edit-event').on('click', function () {
        closeModal($('#edit-event-modal'));
    });

    // Обработка выбора новой обложки
    $('#edit-event-cover').on('change', function () {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                $('#edit-event-cover-preview').html(`<img src="${e.target.result}" alt="Предпросмотр" style="max-width: 100%; border-radius: 16px;">`);
            };
            reader.readAsDataURL(file);
        }
    });
});