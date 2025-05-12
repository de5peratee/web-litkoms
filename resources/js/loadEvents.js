import $ from 'jquery';
import { handleEventTags } from './event-tags';

$(document).ready(function() {
    // Обработка тегов
    handleEventTags();

    // Загрузка дополнительных мероприятий
    $(document).on('click', '#load-more', function(e) {
        e.preventDefault();
        const $button = $(this);
        const page = $button.data('page');
        const search = $('input[name="search"]').val() || '';
        const url = window.location.pathname;

        $button.prop('disabled', true).text('Загрузка...');

        $.get({
            url: url,
            data: { page, search },
            success: function(response) {
                $('#events-grid').append($(response.html));
                $button.data('page', page + 1);

                if (!response.has_more) {
                    $button.remove();
                }

                // Обрабатываем теги для новых элементов
                handleEventTags();
            },
            error: function(xhr) {
                console.error('Ошибка загрузки:', xhr.responseText);
                $button.text('Ошибка. Повторить?');
            },
            complete: function() {
                $button.prop('disabled', false).text('Загрузить ещё');
            }
        });
    });
});
