import $ from 'jquery';

function handleGenreTags() {
    $('.comic-genres').each(function () {
        const $container = $(this);
        const $tags = $container.find('.comic-genre-tag').not('.more-genres');
        $container.find('.more-genres').remove();
        $tags.removeClass('hidden');

        const containerWidth = $container.width();
        const moreTagWidth = 96;
        let totalWidth = 0;
        let cutoffIndex = $tags.length;

        $tags.each(function (i) {
            totalWidth += $(this).outerWidth(true);
            if (totalWidth + moreTagWidth > containerWidth) {
                cutoffIndex = i;
                return false;
            }
        });

        if (cutoffIndex < $tags.length) {
            const $hiddenTags = $tags.slice(cutoffIndex);
            const count = $hiddenTags.length;
            $hiddenTags.addClass('hidden');

            $('<span>', {
                class: 'comic-genre-tag more-genres',
                text: `+${count} жанра`,
                title: $hiddenTags.map(function () { return $(this).text(); }).get().join(', ')
            }).appendTo($container);
        }
    });
}

function loadMoreComics() {
    $(document).on('click', '#load-more', function (e) {
        e.preventDefault();
        const $btn = $(this);
        const url = window.location.pathname;
        const data = {
            page: $btn.data('page'),
            search: $btn.data('search') || '',
            genres: $btn.data('genres') || [],
            sort: $btn.data('sort') || 'date-desc'
        };

        $btn.prop('disabled', true).text('Загрузка...');

        $.get(url, data)
            .done(response => {
                $('.comics-grid').append(response.html);
                $btn.data('page', data.page + 1);
                if (!response.has_more) $btn.remove();
                handleGenreTags();
            })
            .fail(xhr => console.error('Ошибка загрузки:', xhr.responseText))
            .always(() => $btn.prop('disabled', false).text('Загрузить еще'));
    });
}

function updateFilterCount(genres, sort) {
    const count = genres.length + (sort !== 'date-desc' ? 1 : 0);
    $('#filter-count, #filter-count-modal').text(count).toggleClass('hidden', count === 0);
}

function initFilters() {
    let selectedGenres = $('#load-more').data('genres') || [];
    let sortOrder = $('#load-more').data('sort') || 'date-desc';
    let tempGenres = [...selectedGenres];
    let tempSortOrder = sortOrder;
    const allGenres = window.allGenres || [];

    $('#filter-btn').on('click', function () {
        tempGenres = [...selectedGenres];
        tempSortOrder = sortOrder;
        $('#filter-modal').addClass('show');
        renderSelectedGenres();
        $('#sort-select').val(tempSortOrder);
        updateFilterCount(tempGenres, tempSortOrder);
    });

    $('#cancel-filter, #modal-close').on('click', function () {
        $('#filter-modal').removeClass('show');
    });

    $('#filter-modal').on('click', function (e) {
        if ($(e.target).hasClass('modal')) {
            $('#filter-modal').removeClass('show');
        }
    });

    $('#genre-search').on('input', function () {
        const query = $(this).val().toLowerCase();
        const filtered = allGenres
            .filter(genre => genre.toLowerCase().includes(query))
            .sort((a, b) => {
                const aStarts = a.toLowerCase().startsWith(query);
                const bStarts = b.toLowerCase().startsWith(query);
                if (aStarts && !bStarts) return -1;
                if (!aStarts && bStarts) return 1;
                return a.localeCompare(b);
            });
        showGenreSuggestions(filtered);
    });

    function showGenreSuggestions(list) {
        let $box = $('#genre-suggestion-box');
        if ($box.length === 0) {
            $box = $('<div>', { id: 'genre-suggestion-box', class: 'suggestion-box' }).insertAfter('#genre-search');
        }
        $box.empty();

        if (list.length === 0 || $('#genre-search').val().trim() === '') {
            $box.hide();
            return;
        }

        list.forEach(genre => {
            $('<div>', {
                class: 'suggestion-item',
                text: genre,
                click: function () {
                    if (!tempGenres.includes(genre)) {
                        tempGenres.push(genre);
                        renderSelectedGenres();
                        updateFilterCount(tempGenres, tempSortOrder);
                    }
                    $('#genre-search').val('');
                    $box.hide();
                }
            }).appendTo($box);
        });

        $box.show();
    }

    $(document).on('click', function (e) {
        if (!$(e.target).closest('#genre-search, #genre-suggestion-box').length) {
            $('#genre-suggestion-box').hide();
        }
    });

    $(document).on('click', '.remove-genre', function () {
        const genre = $(this).parent().data('genre');
        tempGenres = tempGenres.filter(g => g !== genre);
        renderSelectedGenres();
        updateFilterCount(tempGenres, tempSortOrder);
    });

    $('#sort-select').on('change', function () {
        tempSortOrder = $(this).val();
        updateFilterCount(tempGenres, tempSortOrder);
    });

    $('#save-filter').on('click', function () {
        selectedGenres = [...tempGenres];
        sortOrder = tempSortOrder;
        $('#load-more').data({ genres: selectedGenres, sort: sortOrder, page: 1 });
        updateFilterCount(selectedGenres, sortOrder);
        updateHiddenFilters();
        $('#filter-modal').removeClass('show');
    });

    function renderSelectedGenres() {
        const $wrapper = $('.selected-genres-wrapper').empty();
        tempGenres.forEach(genre => {
            $('<div>', {
                class: 'selected-genre-tag',
                'data-genre': genre,
                html: `<p class="text-hint">${genre}</p><span class="remove-genre text-medium">×</span>`
            }).appendTo($wrapper);
        });
    }

    function updateHiddenFilters() {
        $('#search-form input[name="genres[]"]').remove();
        selectedGenres.forEach(function (genre) {
            $('<input>', {
                type: 'hidden',
                name: 'genres[]',
                value: genre
            }).appendTo('#search-form');
        });

        if ($('#search-form input[name="sort"]').length === 0) {
            $('<input>', {
                type: 'hidden',
                name: 'sort',
                value: sortOrder
            }).appendTo('#search-form');
        } else {
            $('#search-form input[name="sort"]').val(sortOrder);
        }
    }

    $('#search-form').on('submit', function () {
        updateHiddenFilters();
    });
}

function initSearchClear() {
    const $input = $('.search-input-wrapper input[name="search"]');
    const $clear = $('.clear-search');

    function toggleClear() {
        $clear.toggleClass('hidden', !$input.val().trim());
    }

    $input.on('input', toggleClear);

    $clear.on('click', function () {
        $input.val('').focus();
        toggleClear();
    });

    toggleClear();
}

$(function () {
    handleGenreTags();
    loadMoreComics();
    initFilters();
    initSearchClear();
});
