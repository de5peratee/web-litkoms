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

    // Загрузка дополнительных комиксов
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
                if (response.comics && response.comics.length > 0) {
                    const $comicList = $('#comic-list');
                    const html = response.comics.map((comic, index) => {
                        const genresString = comic.genres && comic.genres.length > 0
                            ? comic.genres.map(genre => genre.name).join(', ')
                            : 'Не указаны';
                        const statusIcon = comic.is_moderated === 'successful'
                            ? 'success-icon.svg'
                            : (comic.is_moderated === 'unsuccessful' ? 'reject-icon.svg' : 'hold-on-icon.svg');

                        return `
                            <div href="/author-comics/${comic.slug}" class="comic-item">
                                <div class="comic-item-left-part">
                                    <div class="item-sell num-cell">${(page - 1) * 10 + index + 1}</div>
                                    <div class="item-cell comic-preview-cell">
                                        <div class="comic-cover-wrapper">
                                            <img src="${comic.cover ? `/storage/${comic.cover}` : '/images/default_template/comics.svg'}" class="comic-cover" alt="icon">
                                        </div>
                                        <div class="comic-preview-text-wrapper">
                                            <div class="comic-title-flex">
                                                <p class="text-big">${comic.name}</p>
                                                ${comic.age_restriction >= 18 ? '<p class="text-hint age-restriction-tag">18+</p>' : ''}
                                            </div>
                                            <p class="text-hint comic-datetime-tag">
                                                ${new Date(comic.updated_at).toLocaleString('ru-RU', { day: 'numeric', month: 'long', year: 'numeric', hour: '2-digit', minute: '2-digit' })}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="item-cell status-cell">
                                        <img src="/images/icons/moderation/${statusIcon}" class="icon-24" alt="icon">
                                        <p>${comic.status}</p>
                                    </div>
                                    <div class="comic-links">
                                        ${comic.is_published ? `
                                            <a href="/author-comics/${comic.slug}" target="_blank" class="tertiary-btn">
                                                Читать комикс
                                                <img src="/images/icons/blue-arrow-link.svg" class="icon-24" alt="icon">
                                            </a>
                                        ` : ''}
                                        ${comic.is_moderated !== 'successful' || !comic.is_published ? `
                                            <a href="/user/moderation-confirm-comics/${comic.slug}" target="_blank" class="tertiary-btn">
                                                Модерация
                                                <img src="/images/icons/blue-arrow-link.svg" class="icon-24" alt="icon">
                                            </a>
                                        ` : ''}
                                    </div>
                                </div>
                                <div class="comic-actions">
                                    ${!comic.is_published ? `
                                        <a href="#" class="list-action-btn edit-comic-btn"
                                           data-comic-id="${comic.id}"
                                           data-comic-name="${comic.name}"
                                           data-comic-description="${comic.description || ''}"
                                           data-comic-age_restriction="${comic.age_restriction ? comic.age_restriction + '+' : '0+'}"
                                           data-comic-genres="${genresString}"
                                           data-comic-cover="${comic.cover ? `/storage/${comic.cover}` : ''}"
                                           data-comic-file="${comic.comics_file ? `/storage/${comic.comics_file}` : ''}"
                                           data-comic-file-name="${comic.comics_file ? comic.comics_file.split('/').pop() : ''}">
                                            <img src="/images/icons/edit-primary.svg" class="icon-24" alt="edit-icon">
                                        </a>
                                    ` : ''}
                                    <a href="#" class="list-action-btn delete-comic-btn"
                                       data-comic-id="${comic.id}"
                                       data-comic-name="${comic.name}">
                                        <img src="/images/icons/trash-primary-red.svg" class="icon-24" alt="delete-icon">
                                    </a>
                                </div>
                            </div>
                        `;
                    }).join('');

                    $comicList.append(html);

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
                alert('Ошибка при загрузке комиксов.');
            }
        });
    });
});
