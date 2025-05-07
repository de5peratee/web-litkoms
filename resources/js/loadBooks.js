import $ from 'jquery';

$(document).ready(function() {
    $(document).on('click', '#load-more', function(e) {
        e.preventDefault();

        const $button = $(this);
        const page = $button.data('page');
        const search = $button.data('search') || '';
        const url = window.location.pathname;

        $button.prop('disabled', true).text('Загрузка...');

        $.get({
            url: url,
            data: {
                page: page,
                search: search
            },
            success: function(response) {
                $('.library-grid').append(response.html);
                $button.data('page', page + 1);

                if (!response.has_more) {
                    $button.remove();
                }
            },
            error: function(xhr) {
                console.log('Ошибка загрузки', xhr.responseText);
            },
            complete: function() {
                $button.prop('disabled', false).text('Загрузить еще');
            }
        });
    });
});
