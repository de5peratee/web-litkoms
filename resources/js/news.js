import $ from 'jquery';

$(document).ready(function () {
    // Переключение вкладок
    $('.news-tab').on('click', function () {
        const selectedTab = $(this).data('tab');

        // Переключение активного таба
        $('.news-tab').removeClass('active-tab');
        $(this).addClass('active-tab');

        // Переключение отображаемого контента
        $('.tab-content').hide();
        $(`.tab-content[data-content="${selectedTab}"]`).show();

        // Обновление кнопки подгрузки
        $('#load-more').data('tab', selectedTab);
        $('#load-more').data('page', 2); // Сбрасываем страницу при смене вкладки

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

    // Подгрузка дополнительных элементов
    $(document).on('click', '#load-more', function () {
        const $button = $(this);
        const page = $button.data('page');
        const tab = $button.data('tab');

        $button.prop('disabled', true).text('Загрузка...');

        let url = '/news';
        let data = { page: page };

        if (tab !== 'all') {
            data.tab = tab;
        }

        $.ajax({
            url: url,
            type: 'GET',
            data: data,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                const $newItems = $(response);
                $(`.tab-content[data-content="${tab}"]`).append($newItems);

                // Проверяем, есть ли еще страницы
                const hasMore = $newItems.find('.post-wrapper').length > 0;
                if (hasMore) {
                    $button.data('page', page + 1);
                    $button.prop('disabled', false).text('Загрузить еще');
                } else {
                    $button.closest('.load-more-container').remove();
                }

                // Пересчет тегов для новых элементов
                handleTags();
            },
            error: function (xhr) {
                console.error('Ошибка при загрузке:', xhr.responseText);
                $button.prop('disabled', false).text('Загрузить еще');
            }
        });
    });

    // Показать активный таб по умолчанию
    $('.news-tab.active-tab').trigger('click');

    // Обработка жанров и категорий
    function handleTags() {
        // Обработка жанров комиксов
        $('.comic-genres').each(function () {
            const $container = $(this);
            const $tags = $container.find('.comic-genre-tag').not('.more-genres');
            $container.find('.more-genres').remove();
            $tags.removeClass('hidden');

            const containerWidth = $container.width();
            const moreTagWidth = 88; // Предполагаемая ширина тега "+n жанров"
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

                $('<p>', {
                    class: 'comic-genre-tag text-hint more-genres',
                    text: `+${count} жанров`,
                    title: $hiddenTags.map(function () { return $(this).text(); }).get().join(', ')
                }).appendTo($container);
            }
        });

        // Обработка категорий мероприятий
        $('.event-categories').each(function () {
            const $container = $(this);
            const $tags = $container.find('.event-category-tag').not('.more-categories');
            $container.find('.more-categories').remove();
            $tags.removeClass('hidden');

            const containerWidth = $container.width();
            const moreTagWidth = 88; // Предполагаемая ширина тега "+n категорий"
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

                $('<p>', {
                    class: 'event-category-tag text-hint more-categories',
                    text: `+${count} категорий`,
                    title: $hiddenTags.map(function () { return $(this).text(); }).get().join(', ')
                }).appendTo($container);
            }
        });
    }
});
