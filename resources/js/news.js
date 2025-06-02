import $ from 'jquery';

$(document).ready(function () {
    $('.news-tab').on('click', function () {
        const selectedTab = $(this).data('tab');

        // Переключение активного таба
        $('.news-tab').removeClass('active-tab');
        $(this).addClass('active-tab');

        // Переключение отображаемого контента
        $('.tab-content').hide();
        $(`.tab-content[data-content="${selectedTab}"]`).show();

        // Смена иконок
        $('.news-tab').each(function () {
            const $img = $(this).find('img');
            if ($img.length === 0) return; // Пропускаем вкладки без иконок (например, "Все")

            const src = $img.attr('src');
            const baseNameMatch = src.match(/([^/]+)-(?:primary|white)\.svg$/);
            if (!baseNameMatch) return;

            const baseName = baseNameMatch[1];
            const newSuffix = $(this).hasClass('active-tab') ? 'white' : 'primary';
            $img.attr('src', `/images/icons/${baseName}-${newSuffix}.svg`);
        });

        // Пересчет тегов для нового активного таба
        handleTags();
    });

    // Показать активный таб по умолчанию
    $('.news-tab.active-tab').trigger('click');
});

// Импорт функции handleTags из news.js
function handleTags() {
    // Обработка жанров комиксов
    $('.comic-genres').each(function() {
        const $container = $(this);
        const $tags = $container.find('.comic-genre-tag').not('.more-genres');
        $container.find('.more-genres').remove();
        $tags.removeClass('hidden');

        const containerWidth = $container.width();
        const moreTagWidth = 88; // Предполагаемая ширина тега "+n жанров"
        let totalWidth = 0;
        let cutoffIndex = $tags.length;

        $tags.each(function(i) {
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

            $('<p>', {
                class: 'comic-genre-tag text-hint more-genres',
                text: `+${count} жанров`,
                title: $hiddenTags.map(function() { return $(this).text(); }).get().join(', ')
            }).appendTo($container);
        }
    });

    // Обработка категорий мероприятий
    $('.event-categories').each(function() {
        const $container = $(this);
        const $tags = $container.find('.event-category-tag').not('.more-categories');
        $container.find('.more-categories').remove();
        $tags.removeClass('hidden');

        const containerWidth = $container.width();
        const moreTagWidth = 88; // Предполагаемая ширина тега "+n категорий"
        let totalWidth = 0;
        let cutoffIndex = $tags.length;

        $tags.each(function(i) {
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

            $('<p>', {
                class: 'event-category-tag text-hint more-categories',
                text: `+${count} категорий`,
                title: $hiddenTags.map(function() { return $(this).text(); }).get().join(', ')
            }).appendTo($container);
        }
    });
}
