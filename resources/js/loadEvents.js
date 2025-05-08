import $ from 'jquery';

$(document).ready(function() {
    $(document).on('click', '#load-more', function(e) {
        e.preventDefault();

        const $button = $(this);
        const page = $button.data('page');
        const search = $('input[name="search"]').val() || '';
        const url = window.location.pathname;

        $button.prop('disabled', true).text('Загрузка...');

        $.get({
            url: url,
            data: {
                page: page,
                search: search
            },
            success: function(response) {
                // Ответ должен содержать кусок HTML с новыми карточками и info об is_last
                $('#events-grid').append($(response.html)); // append html из blade

                // Инкрементим номер страницы
                $button.data('page', page + 1);

                if (!response.has_more) {
                    $button.remove(); // если больше нет — убираем кнопку
                }
            },
            error: function(xhr) {
                console.error('Ошибка загрузки мероприятий:', xhr.responseText);
                $button.text('Ошибка. Повторить?');
            },
            complete: function() {
                $button.prop('disabled', false).text('Загрузить ещё');
            }
        });
    });
});
