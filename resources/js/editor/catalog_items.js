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
                if (response.catalogs.length > 0) {
                    const $catalogList = $('#catalog-list');
                    const html = response.catalogs.map((catalog, index) => `
                        <div class="catalog-item">
                            <div class="comic-item-left">
                                <div class="item-sell num-cell">${(page - 1) * 10 + index + 1}</div>
                                <div class="item-cell comic-preview-cell">
                                    <div class="comic-cover-wrapper">
                                        <img src="${catalog.cover ? `/storage/${catalog.cover}` : '/images/default_template/comics.svg'}" class="comic-cover" alt="icon">
                                    </div>
                                    <div class="comic-preview-text-wrapper">
                                        <p class="text-big">${catalog.name}</p>
                                        <p class="text-hint author-text">${catalog.author}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="catalog-actions">
                                <a href="#" class="list-action-btn edit-catalog-btn"
                                   data-catalog-id="${catalog.id}"
                                   data-catalog-name="${catalog.name}"
                                   data-catalog-author="${catalog.author}"
                                   data-catalog-description="${catalog.description}"
                                   data-catalog-release_year="${catalog.release_year}"
                                   data-catalog-genres="${catalog.genres.map(genre => genre.name).join(', ')}"
                                   data-catalog-cover="${catalog.cover ? `/storage/${catalog.cover}` : ''}">
                                    <img src="/images/icons/edit-primary.svg" class="icon-24" alt="edit-icon">
                                </a>
                                <a href="#" class="list-action-btn delete-catalog-btn"
                                   data-catalog-id="${catalog.id}"
                                   data-catalog-name="${catalog.name}">
                                    <img src="/images/icons/trash-primary-red.svg" class="icon-24" alt="delete-icon">
                                </a>
                            </div>
                        </div>
                    `).join('');

                    $catalogList.append(html);

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
