import $ from 'jquery';
$(document).ready(function() {
    function handleGenres() {
        $('.comics-genres').each(function() {
            const $container = $(this);
            const $tags = $container.find('.comics-genre-tag').not('.more-genres');
            $container.find('.more-genres').remove();
            $tags.removeClass('hidden');

            const containerWidth = $container.width();
            let totalWidth = 0;
            let cutoffIndex = $tags.length;

            // Временный тег для измерения ширины "+n жанров"
            let $tempMoreTag = $('<span>', {
                class: 'comics-genre-tag more-genres',
                text: '+0 жанра',
                css: { position: 'absolute', visibility: 'hidden' }
            }).appendTo($container);
            let moreTagWidth = $tempMoreTag.outerWidth(true);
            $tempMoreTag.remove();

            // Считаем, сколько тегов помещается, учитывая ширину тега "+n жанров"
            $tags.each(function(i) {
                totalWidth += $(this).outerWidth(true);
                if (totalWidth + moreTagWidth > containerWidth) {
                    cutoffIndex = i;
                    return false;
                }
            });

            // Если есть теги, которые не помещаются
            if (cutoffIndex < $tags.length) {
                const $hiddenTags = $tags.slice(cutoffIndex);
                const count = $hiddenTags.length;
                $hiddenTags.addClass('hidden');

                // Создаем тег "+n жанров" с правильной шириной
                let $moreTag = $('<span>', {
                    class: 'comics-genre-tag more-genres',
                    text: `+${count} жанра`,
                    title: $hiddenTags.map(function() { return $(this).text(); }).get().join(', ')
                }).appendTo($container);

                // Проверяем, помещается ли тег "+n жанров" после добавления
                totalWidth = 0;
                $tags.slice(0, cutoffIndex).each(function() {
                    totalWidth += $(this).outerWidth(true);
                });
                moreTagWidth = $moreTag.outerWidth(true);

                // Если итоговая ширина все еще превышает контейнер, скрываем еще теги
                while (totalWidth + moreTagWidth > containerWidth && cutoffIndex > 0) {
                    cutoffIndex--;
                    $tags.slice(cutoffIndex).addClass('hidden');
                    totalWidth = 0;
                    $tags.slice(0, cutoffIndex).each(function() {
                        totalWidth += $(this).outerWidth(true);
                    });
                    $moreTag.text(`+${$tags.length - cutoffIndex} жанра`).attr('title', $tags.slice(cutoffIndex).map(function() { return $(this).text(); }).get().join(', '));
                    moreTagWidth = $moreTag.outerWidth(true);
                }
            }
        });
    }

    handleGenres();
    $(window).resize(handleGenres);
});
