import $ from 'jquery';

$(document).ready(function () {
    // Настройка CSRF-токена для AJAX
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Показ/скрытие крестика в поле поиска
    const $searchInput = $('#search-input');
    const $clearSearch = $('.clear-search');

    function toggleClearButton() {
        if ($searchInput.val().length > 0) {
            $clearSearch.removeClass('hidden');
        } else {
            $clearSearch.addClass('hidden');
        }
    }

    toggleClearButton();
    $searchInput.on('input', toggleClearButton);

    // Очистка поля поиска
    $clearSearch.on('click', function () {
        $searchInput.val('');
        toggleClearButton();
        $('#search-form').submit();
    });

    // Поиск при нажатии Enter
    $searchInput.on('keypress', function (e) {
        if (e.which === 13) { // Код клавиши Enter
            e.preventDefault();
            $('#search-form').submit();
        }
    });

    // Загрузка дополнительных мероприятий
    $(document).on('click', '#load-more', function () {
        const $button = $(this);
        const page = $button.data('page');
        const search = $button.data('search');
        const url = $button.data('url');

        $.ajax({
            url: url,
            method: 'GET',
            data: {
                page: page,
                search: search
            },
            success: function (response) {
                if (response.events && response.events.length > 0) {
                    const $eventList = $('#event-list');
                    const html = response.events.map((event, index) => {
                        const guests = event.guests && event.guests.length > 0
                            ? event.guests.map(guest => `${guest.name} ${guest.surname}`).join(' · ')
                            : '';
                        const tags = event.tags && event.tags.length > 0
                            ? event.tags.map(tag => tag.name).join(', ')
                            : '';
                        const startDate = event.start_date
                            ? new Date(event.start_date).toLocaleDateString('ru-RU', { day: 'numeric', month: 'long', year: 'numeric' })
                            : '';
                        const startTime = event.start_date
                            ? new Date(event.start_date).toLocaleTimeString('ru-RU', { hour: '2-digit', minute: '2-digit' })
                            : '';

                        return `
                            <div class="event-item">
                                <div class="event-item-left">
                                    <div class="item-cell num-cell">${(page - 1) * 10 + index + 1}</div>
                                    <div class="item-cell event-preview-cell">
                                        <div class="event-cover-wrapper">
                                            <img src="${event.cover ? `/storage/${event.cover}` : '/images/default_template/event-cover.svg'}" class="event-cover" alt="icon">
                                        </div>
                                        <div class="event-preview-text-wrapper">
                                            <p class="text-big">${event.name}</p>
                                            <p class="text-hint event-text">${guests}</p>
                                            <div class="event-datetime-wrapper">
                                                <img src="/images/icons/calendar-tertiary.svg" class="icon-20" alt="icon">
                                                <p class="slide-event-card-date">${startDate}</p>
                                                <p>·</p>
                                                <p class="slide-event-card-date">${startTime}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="item-cell">
                                        <a href="/events/${event.id}" target="_blank" class="tertiary-btn">
                                            Подробнее
                                            <img src="/images/icons/blue-arrow-link.svg" class="icon-24" alt="icon">
                                        </a>
                                    </div>
                                </div>
                                <div class="event-actions">
                                    <a href="#" class="list-action-btn edit-event-btn"
                                       data-event-id="${event.id}"
                                       data-event-name="${event.name}"
                                       data-event-description="${event.description || ''}"
                                       data-event-start_date="${event.start_date ? new Date(event.start_date).toISOString().split('T')[0] : ''}"
                                       data-event-time="${startTime}"
                                       data-event-guests="${guests.replace(/ · /g, ', ')}"
                                       data-event-tags="${tags}"
                                       data-event-cover="${event.cover ? `/storage/${event.cover}` : ''}">
                                        <img src="/images/icons/edit-primary.svg" class="icon-24" alt="edit-icon">
                                    </a>
                                    <a href="#" class="list-action-btn delete-event-btn"
                                       data-event-id="${event.id}"
                                       data-event-name="${event.name}">
                                        <img src="/images/icons/trash-primary-red.svg" class="icon-24" alt="delete-icon">
                                    </a>
                                </div>
                            </div>
                        `;
                    }).join('');

                    $eventList.append(html);

                    if (response.hasMore) {
                        $button.data('page', response.nextPage);
                    } else {
                        $button.parent().remove();
                    }
                } else {
                    $button.parent().remove();
                }
            },
            error: function () {
                alert('Ошибка при загрузке мероприятий.');
            }
        });
    });
});
