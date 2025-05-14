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

    // Открыть модалку удаления мероприятия
    $('.delete-event-btn').on('click', function(e) {
        e.preventDefault();
        const eventName = $(this).data('event-name');

        $('#delete-event-modal-text').text(`Вы уверены, что хотите удалить мероприятие "${eventName}"?`);

        openModal($('#delete-event-modal'));
    });

    // Открыть модалку редактирования мероприятия
    $('.edit-event-btn').on('click', function(e) {
        e.preventDefault();

        // Получаем данные из data-атрибутов
        const eventId = $(this).data('event-id');
        const eventName = $(this).data('event-name');
        const eventDescription = $(this).data('event-description');
        const eventStartDate = $(this).data('event-start_date');
        const eventTime = $(this).data('event-time');
        const eventGuests = $(this).data('event-guests');
        const eventTags = $(this).data('event-tags');
        const eventCover = $(this).data('event-cover');

        // Заполняем поля
        $('#edit-event-id').val(eventId);
        $('#edit-event-name').val(eventName);
        $('#edit-event-description').val(eventDescription);
        $('#edit-event-start_date').val(eventStartDate);
        $('#edit-event-time').val(eventTime);
        $('#edit-event-guests').val(eventGuests);
        $('#edit-event-tags').val(eventTags);

        // Показываем текущее превью обложки, если есть
        if (eventCover) {
            $('#edit-event-cover-preview').html(`<img src="${eventCover}" alt="Текущая обложка" style="max-width: 100%; border-radius: 16px;">`);
        } else {
            $('#edit-event-cover-preview').html('');
        }

        // Открываем модалку
        openModal($('#edit-event-modal'));
    });



    // Закрыть модалку удаления мероприятия
    $('#delete-event-modal-close, #cancel-delete-event').on('click', function() {
        closeModal($('#delete-event-modal'));
    });

    // Закрыть модалку редактирования мероприятия
    $('#edit-event-modal-close, #cancel-edit-event').on('click', function() {
        closeModal($('#edit-event-modal'));
    });
});
