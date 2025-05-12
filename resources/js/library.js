import $ from 'jquery';

// Обработка тегов жанров для карточек книг
function handleGenreTags() {
    document.querySelectorAll('.book-categories').forEach(container => {
        const tags = Array.from(container.querySelectorAll('.book-category-tag:not(.more-genres)'));
        container.querySelectorAll('.more-genres').forEach(el => el.remove());

        tags.forEach(tag => tag.classList.remove('hidden'));

        const containerWidth = container.clientWidth;
        const moreTagWidth = 88; // Ширина тега "+N жанров"
        let totalWidth = 0;
        let cutoffIndex = tags.length;

        for (let i = 0; i < tags.length; i++) {
            totalWidth += tags[i].offsetWidth + 4; // 4px — gap
            if (totalWidth + moreTagWidth > containerWidth) {
                cutoffIndex = i;
                break;
            }
        }

        if (cutoffIndex < tags.length) {
            const hiddenTags = tags.slice(cutoffIndex);
            const count = hiddenTags.length;

            hiddenTags.forEach(tag => tag.classList.add('hidden'));

            const moreTag = document.createElement('span');
            moreTag.className = 'book-category-tag more-genres';
            moreTag.textContent = `+${count} жанра`;
            moreTag.title = hiddenTags.map(t => t.textContent).join(', ');
            container.appendChild(moreTag);
        }
    });
}

// Подгрузка дополнительных книг
function loadMoreBooks() {
    $(document).on('click', '#load-more', function (e) {
        e.preventDefault();

        const $button = $(this);
        const page = $button.data('page');
        const search = $button.data('search') || '';
        const genres = $button.data('genres') || [];
        const sort = $button.data('sort') || 'desc';
        const url = window.location.pathname;

        $button.prop('disabled', true).text('Загрузка...');

        $.get({
            url: url,
            data: {
                page: page,
                search: search,
                genres: genres,
                sort: sort
            },
            success: function (response) {
                $('.library-grid').append(response.html);
                $button.data('page', page + 1);

                if (!response.has_more) {
                    $button.remove();
                }

                handleGenreTags();
            },
            error: function (xhr) {
                console.error('Ошибка загрузки:', xhr.responseText);
            },
            complete: function () {
                $button.prop('disabled', false).text('Загрузить еще');
            }
        });
    });
}

// Обновление счетчика активных фильтров
function updateFilterCount(selectedGenres, sortOrder) {
    const count = selectedGenres.length + (sortOrder !== 'desc' ? 1 : 0);
    $('#filter-count, #filter-count-modal').text(count);
}

// Инициализация фильтров в модальном окне
function initFilters() {
    let selectedGenres = [];
    let sortOrder = 'desc';

    // Попытка загрузить genres и sort из data-атрибутов
    try {
        const genresData = $('#load-more').data('genres');
        selectedGenres = genresData ? JSON.parse(JSON.stringify(genresData)) : [];
        sortOrder = $('#load-more').data('sort') || 'desc';
    } catch (e) {
        console.error('Ошибка парсинга genres:', e);
        selectedGenres = [];
        sortOrder = 'desc';
    }

    // Открытие модального окна
    $('#filter-btn').on('click', function () {
        $('#filter-modal').addClass('show');
        renderSelectedGenres();
        $('#sort-select').val(sortOrder);
        updateFilterCount(selectedGenres, sortOrder);
    });

    // Закрытие модального окна при клике на крестик
    $('#modal-close, #cancel-filter').on('click', function () {
        $('#filter-modal').removeClass('show');
    });

    // Закрытие модального окна при клике вне контента
    $('#filter-modal').on('click', function (e) {
        if ($(e.target).hasClass('modal')) {
            $('#filter-modal').removeClass('show');
        }
    });

    // Добавление жанра по Enter или выбору из datalist
    $('#genre-search').on('keypress', function (e) {
        if (e.which === 13) { // Enter key
            e.preventDefault();
            const genre = $(this).val().trim();
            if (genre && !selectedGenres.includes(genre)) {
                selectedGenres.push(genre);
                $(this).val(''); // Очищаем поле
                renderSelectedGenres();
                updateFilterCount(selectedGenres, sortOrder);
            }
        }
    }).on('change', function () {
        const genre = $(this).val().trim();
        if (genre && !selectedGenres.includes(genre)) {
            selectedGenres.push(genre);
            $(this).val(''); // Очищаем поле
            renderSelectedGenres();
            updateFilterCount(selectedGenres, sortOrder);
        }
    });

    // Удаление жанра
    $(document).on('click', '.remove-genre', function () {
        const genre = $(this).parent().data('genre');
        selectedGenres = selectedGenres.filter(g => g !== genre);
        renderSelectedGenres();
        updateFilterCount(selectedGenres, sortOrder);
    });

    // Изменение сортировки
    $('#sort-select').on('change', function () {
        sortOrder = $(this).val();
        updateFilterCount(selectedGenres, sortOrder);
    });

    // Сохранение фильтров
    $('#save-filter').on('click', function () {
        const search = $('input[name="search"]').val();
        const url = window.location.pathname;

        // Обновляем данные кнопки подгрузки
        $('#load-more').data('genres', selectedGenres);
        $('#load-more').data('sort', sortOrder);
        $('#load-more').data('page', 1);

        // Обновляем счетчик фильтров
        updateFilterCount(selectedGenres, sortOrder);

        // Выполняем новый запрос
        $.get({
            url: url,
            data: {
                search: search,
                genres: selectedGenres,
                sort: sortOrder,
                page: 1
            },
            success: function (response) {
                $('.library-grid').html(response.html);
                if (response.has_more) {
                    $('#load-more').show();
                } else {
                    $('#load-more').hide();
                }
                $('#filter-modal').removeClass('show');
                handleGenreTags();
            },
            error: function (xhr) {
                console.error('Ошибка применения фильтров:', xhr.responseText);
            }
        });
    });

    // Рендеринг выбранных жанров
    function renderSelectedGenres() {
        const $wrapper = $('.selected-genres-wrapper');
        $wrapper.empty();
        selectedGenres.forEach(genre => {
            $wrapper.append(`
                <div class="selected-genre-tag" data-genre="${genre}">
                    <p class="text-hint">${genre}</p>
<!--                    <img src="{{ asset('images/icons/close-icon-white.svg') }}" class="icon-16 remove-genre" alt="icon">-->
                    <span class="remove-genre text-medium">×</span>
                </div>
            `);
        });
    }
}

// Инициализация всех функций при загрузке страницы
$(document).ready(function () {
    handleGenreTags();
    loadMoreBooks();
    initFilters();
});
