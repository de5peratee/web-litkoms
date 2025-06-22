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

$(function () {
    handleCategoryTags();
});
