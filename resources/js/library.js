import $ from 'jquery';

$(function() {
    $(document).on('click', '#load-more', function() {
        const $button = $(this);
        const page = $button.data('page');
        const search = $button.data('search') || '';
        const url = window.location.pathname;

        $button.prop('disabled', true).text('Загрузка...');

        $.ajax({
            url: url,
            type: 'GET',
            data: {
                page: page,
                search: search
            },
            dataType: 'json',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            success: function(response) {
                if (response.success) {
                    $('.library-grid').append(response.html);
                    $button.data('page', page + 1);

                    if (!response.has_more) {
                        $button.remove();
                    }
                }
            },
            error: function(xhr) {
                console.error('AJAX Error:', xhr.status, xhr.statusText, xhr.responseText);
            },
            complete: function() {
                $button.prop('disabled', false).text('Посмотреть еще');
            }
        });
    });
});
