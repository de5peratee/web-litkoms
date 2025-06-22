import $ from 'jquery';

function handleCategoryTags() {
    $('.event-categories').each(function () {
        const $container = $(this);
        const $tags = $container.find('.event-tag').not('.more-categories');
        $container.find('.more-categories').remove();
        $tags.removeClass('hidden');

        const containerWidth = $container.width();
        const moreTagWidth = 52;
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
                class: 'event-tag more-categories',
                text: `+${count}`,
                title: $hiddenTags.map(function () { return $(this).text(); }).get().join(', ')
            }).appendTo($container);
        }
    });
}

function loadMoreEvents() {
    $(document).on('click', '#load-more', function (e) {
        e.preventDefault();
        const $btn = $(this);
        const url = window.location.pathname;
        const data = {
            page: $btn.data('page'),
            search: $btn.data('search') || '',
            categories: $btn.data('categories') || [],
            sort: $btn.data('sort') || 'asc'
        };

        $btn.prop('disabled', true).text('Загрузка...');

        $.get(url, data)
            .done(response => {
                $('#events-grid').append(response.html);
                $btn.data('page', data.page + 1);
                if (!response.has_more) $btn.remove();
                handleCategoryTags();
            })
            .fail(xhr => console.error('Ошибка загрузки:', xhr.responseText))
            .always(() => $btn.prop('disabled', false).text('Загрузить ещё'));
    });
}

function updateFilterCount(categories, sort) {
    const count = categories.length + (sort !== 'asc' ? 1 : 0);
    $('#filter-count, #filter-count-modal').text(count).toggleClass('hidden', count === 0);
}

function initFilters() {
    let selectedCategories = $('#load-more').data('categories') || [];
    let sortOrder = $('#load-more').data('sort') || 'asc';
    let tempCategories = [...selectedCategories];
    let tempSortOrder = sortOrder;
    const allCategories = window.allCategories || [];

    $('#filter-btn').on('click', function () {
        tempCategories = [...selectedCategories];
        tempSortOrder = sortOrder;
        $('#filter-modal').addClass('show');
        renderSelectedCategories();
        $('#sort-select').val(tempSortOrder);
        updateFilterCount(tempCategories, tempSortOrder);
    });

    $('#cancel-filter, #modal-close').on('click', function () {
        $('#filter-modal').removeClass('show');
    });

    $('#filter-modal').on('click', function (e) {
        if ($(e.target).hasClass('modal')) {
            $('#filter-modal').removeClass('show');
        }
    });

    $('#category-search').on('input', function () {
        const query = $(this).val().toLowerCase();
        const filtered = allCategories
            .filter(category => category.toLowerCase().includes(query))
            .sort((a, b) => {
                const aStarts = a.toLowerCase().startsWith(query);
                const bStarts = b.toLowerCase().startsWith(query);
                if (aStarts && !bStarts) return -1;
                if (!aStarts && bStarts) return 1;
                return a.localeCompare(b);
            });
        showCategorySuggestions(filtered);
    });

    function showCategorySuggestions(list) {
        let $box = $('#category-suggestion-box');
        if ($box.length === 0) {
            $box = $('<div>', { id: 'category-suggestion-box', class: 'suggestion-box' }).insertAfter('#category-search');
        }
        $box.empty();

        if (list.length === 0 || $('#category-search').val().trim() === '') {
            $box.hide();
            return;
        }

        list.forEach(category => {
            $('<div>', {
                class: 'suggestion-item',
                text: category,
                click: function () {
                    if (!tempCategories.includes(category)) {
                        tempCategories.push(category);
                        renderSelectedCategories();
                        updateFilterCount(tempCategories, tempSortOrder);
                    }
                    $('#category-search').val('');
                    $box.hide();
                }
            }).appendTo($box);
        });

        $box.show();
    }

    $(document).on('click', function (e) {
        if (!$(e.target).closest('#category-search, #category-suggestion-box').length) {
            $('#category-suggestion-box').hide();
        }
    });

    $(document).on('click', '.remove-category', function () {
        const category = $(this).parent().data('category');
        tempCategories = tempCategories.filter(c => c !== category);
        renderSelectedCategories();
        updateFilterCount(tempCategories, tempSortOrder);
    });

    $('#sort-select').on('change', function () {
        tempSortOrder = $(this).val();
        updateFilterCount(tempCategories, tempSortOrder);
    });

    $('#save-filter').on('click', function () {
        selectedCategories = [...tempCategories];
        sortOrder = tempSortOrder;
        $('#load-more').data({ categories: selectedCategories, sort: sortOrder, page: 1 });
        updateFilterCount(selectedCategories, sortOrder);
        updateHiddenFilters();
        $('#filter-modal').removeClass('show');
    });

    function renderSelectedCategories() {
        const $wrapper = $('.selected-categories-wrapper').empty();
        tempCategories.forEach(category => {
            $('<div>', {
                class: 'selected-category-tag',
                'data-category': category,
                html: `<p class="text-hint">${category}</p><span class="remove-category text-medium">×</span>`
            }).appendTo($wrapper);
        });
    }

    function updateHiddenFilters() {
        $('#search-form input[name="categories[]"]').remove();
        selectedCategories.forEach(function (category) {
            $('<input>', {
                type: 'hidden',
                name: 'categories[]',
                value: category
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
    handleCategoryTags();
    loadMoreEvents();
    initFilters();
    initSearchClear();
});
