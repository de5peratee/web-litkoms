import $ from 'jquery';

$(document).ready(function() {
    // Обработка тегов
    function handleTags() {
        $('.comics-list-wrapper').each(function() {
            $(this).find('.comic-tags-wrapper').each(function() {
                const $container = $(this);
                const $tags = $container.find('.comic-tag').not('.more-tag');
                $container.find('.more-tag').remove();
                $tags.removeClass('hidden');

                const containerWidth = $container.width();
                const moreTagWidth = 88;
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
                        class: 'text-hint comic-tag more-tag',
                        text: `+${count} жанров`,
                        title: $hiddenTags.map(function() { return $(this).text(); }).get().join(', ')
                    }).appendTo($container);
                }
            });
        });
    }

    // Управление скроллом
    function handleScroll() {
        $('.comics-list-wrapper').each(function() {
            const $wrapper = $(this);
            const $container = $wrapper.find('.comics-scroll-container');
            const $scrollLeftBtn = $wrapper.find('.scroll-left');
            const $scrollRightBtn = $wrapper.find('.scroll-right');

            // Проверяем, есть ли скролл
            const canScroll = $container[0].scrollWidth > $container[0].clientWidth;

            if (canScroll) {
                // Показываем кнопки, если есть скролл
                $scrollLeftBtn.show();
                $scrollRightBtn.show();

                // Обновляем видимость кнопок в зависимости от положения скролла
                function updateButtons() {
                    const scrollLeft = $container.scrollLeft();
                    const maxScroll = $container[0].scrollWidth - $container[0].clientWidth;

                    $scrollLeftBtn.toggle(scrollLeft > 0);
                    $scrollRightBtn.toggle(scrollLeft < maxScroll - 1); // -1 для учета погрешности
                }

                updateButtons();
                $container.on('scroll', updateButtons);

                // Обработчики кнопок
                $scrollLeftBtn.on('click', function() {
                    $container[0].scrollBy({ left: -220, behavior: 'smooth' }); // 220px — ширина комикса
                });

                $scrollRightBtn.on('click', function() {
                    $container[0].scrollBy({ left: 220, behavior: 'smooth' });
                });
            } else {
                // Скрываем кнопки, если скролл не нужен
                $scrollLeftBtn.hide();
                $scrollRightBtn.hide();
            }
        });
    }

    handleTags();
    handleScroll();
    $(window).resize(function() {
        handleTags();
        handleScroll();
    });
});
