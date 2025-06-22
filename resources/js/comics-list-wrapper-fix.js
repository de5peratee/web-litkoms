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
                const moreTagWidth = 52;
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
                        text: `+${count}`,
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

            if ($container.length === 0) {
                return; // Пропускаем, если контейнер не найден
            }

            // Удаляем существующие градиенты, если они есть
            $wrapper.find('.gradient-left, .gradient-right').remove();

            const canScroll = $container[0].scrollWidth > $container[0].clientWidth;

            if (canScroll) {
                // Показываем кнопки
                $scrollLeftBtn.show();
                $scrollRightBtn.show();

                // Добавляем градиенты только при наличии скролла
                $('<div>', {
                    class: 'gradient-left',
                    css: {
                        position: 'absolute',
                        top: 0,
                        bottom: 0,
                        left: 0,
                        width: '40px',
                        background: 'linear-gradient(to right, var(--white), transparent)',
                        zIndex: 1,
                        pointerEvents: 'none'
                    }
                }).appendTo($wrapper);

                $('<div>', {
                    class: 'gradient-right',
                    css: {
                        position: 'absolute',
                        top: 0,
                        bottom: 0,
                        right: 0,
                        width: '40px',
                        background: 'linear-gradient(to left, var(--white), transparent)',
                        zIndex: 1,
                        pointerEvents: 'none'
                    }
                }).appendTo($wrapper);

                // Обновляем видимость кнопок в зависимости от положения скролла
                function updateButtons() {
                    const scrollLeft = $container.scrollLeft();
                    const maxScroll = $container[0].scrollWidth - $container[0].clientWidth;

                    $scrollLeftBtn.toggle(scrollLeft > 0);
                    $scrollRightBtn.toggle(scrollLeft < maxScroll - 1);

                    // Показываем/скрываем градиенты в зависимости от положения скролла
                    $wrapper.find('.gradient-left').toggle(scrollLeft > 0);
                    $wrapper.find('.gradient-right').toggle(scrollLeft < maxScroll - 1);
                }

                updateButtons();
                $container.on('scroll', updateButtons);

                // Обработчики кнопок
                $scrollLeftBtn.on('click', function() {
                    $container[0].scrollBy({ left: -220, behavior: 'smooth' });
                });

                $scrollRightBtn.on('click', function() {
                    $container[0].scrollBy({ left: 220, behavior: 'smooth' });
                });
            } else {
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
