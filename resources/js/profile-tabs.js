import $ from 'jquery';

$(document).ready(function () {
    $('.profile-tab').on('click', function () {
        const selectedTab = $(this).data('tab');

        // Переключение активного таба
        $('.profile-tab').removeClass('active-tab');
        $(this).addClass('active-tab');

        // Переключение отображаемого контента
        $('.tab-content').hide();
        $(`.tab-content[data-content="${selectedTab}"]`).show();

        // Смена иконок
        $('.profile-tab').each(function () {
            const $img = $(this).find('img');
            const src = $img.attr('src');

            // Извлекаем базовое имя (например, comics-icon)
            const baseNameMatch = src.match(/([^/]+)-(?:primary|white)\.svg$/);
            if (!baseNameMatch) return;

            const baseName = baseNameMatch[1];
            const newSuffix = $(this).hasClass('active-tab') ? 'white' : 'primary';

            $img.attr('src', `/images/icons/${baseName}-${newSuffix}.svg`);
        });
    });

    // Показать активный таб по умолчанию
    $('.profile-tab.active-tab').trigger('click');
});
