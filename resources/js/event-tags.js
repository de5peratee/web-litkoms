import $ from 'jquery';

export function handleEventTags() {
    $('.event-categories').each(function() {
        const $container = $(this);
        const $tags = $container.find('.event-tag:not(.more-tags)');
        $container.find('.more-tags').remove();
        $tags.removeClass('hidden');

        const containerWidth = $container.width();
        const moreTagWidth = 60; // Ширина плашки "+N"
        let totalWidth = 0;
        let cutoffIndex = $tags.length;

        $tags.each(function(index) {
            const $tag = $(this);
            totalWidth += $tag.outerWidth(true);

            if (totalWidth + moreTagWidth > containerWidth) {
                cutoffIndex = index;
                return false; // Выходим из цикла
            }
        });

        if (cutoffIndex < $tags.length) {
            const hiddenCount = $tags.length - cutoffIndex;
            const hiddenTags = $tags.slice(cutoffIndex);

            hiddenTags.addClass('hidden');

            const allTags = $container.data('tags').split(',');
            const hiddenTagNames = allTags.slice(cutoffIndex).join(', ');

            $container.append(
                $('<span>')
                    .addClass('event-tag more-tags')
                    .text(`+${hiddenCount}`)
                    .attr('title', hiddenTagNames)
            );
        }
    });
}

// Инициализация и обработка ресайза
$(document).ready(function() {
    handleEventTags();

    // Оптимизированный обработчик ресайза
    let resizeTimer;
    $(window).on('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(handleEventTags, 100);
    });
});
