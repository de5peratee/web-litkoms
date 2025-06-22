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

    // Обработчик отправки формы редактирования
    $('#edit-event-form').on('submit', function (e) {
        e.preventDefault();
        clearErrors();

        let isValid = true;
        const name = $('#edit-event-name').val().trim();
        const description = $('#edit-event-description').val().trim();
        const startDate = $('#edit-event-start_date').val();
        const startTime = $('#edit-event-start_time').val();
        const endDate = $('#edit-event-end_date').val();
        const endTime = $('#edit-event-end_time').val();
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
            showError('edit-event-start_date', 'Дата начала обязательна');
            isValid = false;
        }
        if (!startTime) {
            showError('edit-event-start_time', 'Время начала обязательно');
            isValid = false;
        }
        if (!endDate) {
            showError('edit-event-end_date', 'Дата окончания обязательна');
            isValid = false;
        }
        if (!endTime) {
            showError('edit-event-end_time', 'Время окончания обязательно');
            isValid = false;
        }

        if (isValid) {
            $.ajax({
                url: `/dashboard/events/${eventId}`,
                method: 'POST',
                data: new FormData(this),
                processData: false,
                contentType: false,
                success: function () {
                    window.location.href = '/dashboard/events';
                },
                error: function (xhr) {
                    const errors = xhr.responseJSON.errors || {};
                    for (const [field, messages] of Object.entries(errors)) {
                        showError(`edit-event-${field.replace('.', '_')}`, messages[0]);
                    }
                }
            });
        }
    });

    // Делегирование событий для кнопки удаления
    $(document).on('click', '.delete-event-btn', function (e) {
        e.preventDefault();
        const eventId = $(this).data('event-id');
        const eventName = $(this).data('event-name');

        $('#delete-event-modal-text').text(`Вы уверены, что хотите удалить мероприятие "${eventName}"?`);
        $('#delete-event-form').attr('action', `/dashboard/events/${eventId}`);

        openModal($('#delete-event-modal'));
    });

    // Делегирование событий для кнопки редактирования
    $(document).on('click', '.edit-event-btn', function (e) {
        e.preventDefault();

        const eventId = $(this).data('event-id');
        const eventName = $(this).data('event-name');
        const eventDescription = $(this).data('event-description');
        const eventStartDate = $(this).data('event-start_date');
        const eventStartTime = $(this).data('event-start_time');
        const eventEndDate = $(this).data('event-end_date');
        const eventEndTime = $(this).data('event-end_time');
        const eventGuests = $(this).data('event-guests');
        const eventTags = $(this).data('event-tags');
        const eventCover = $(this).data('event-cover');

        $('#edit-event-id').val(eventId);
        $('#edit-event-name').val(eventName);
        $('#edit-event-description').val(eventDescription);
        $('#edit-event-start_date').val(eventStartDate);
        $('#edit-event-start_time').val(eventStartTime);
        $('#edit-event-end_date').val(eventEndDate);
        $('#edit-event-end_time').val(eventEndTime);
        $('#edit-event-guests').val(eventGuests);
        $('#edit-event-tags').val(eventTags);
        $('#edit-event-form').attr('action', `/dashboard/events/${eventId}`);

        if (eventCover) {
            $('#edit-event-cover-preview').html(`<img src="${eventCover}" alt="Текущая обложка" style="max-width: 100%; border-radius: 16px;">`);
        } else {
            $('#edit-event-cover-preview').html('');
        }

        openModal($('#edit-event-modal'));
    });

    // Закрытие модальных окон
    $('#delete-event-modal-close, #cancel-delete-event').on('click', function () {
        closeModal($('#delete-event-modal'));
    });

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
