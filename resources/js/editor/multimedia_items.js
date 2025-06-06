import $ from 'jquery';

$(document).ready(function () {
    // Настройка CSRF-токена для AJAX
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Показ/скрытие крестика в поле поиска
    const $searchInput = $('#search-input');
    const $clearSearch = $('.clear-search');

    function toggleClearButton() {
        if ($searchInput.val().length > 0) {
            $clearSearch.removeClass('hidden');
        } else {
            $clearSearch.addClass('hidden');
        }
    }

    toggleClearButton();
    $searchInput.on('input', toggleClearButton);

    // Очистка поля поиска
    $clearSearch.on('click', function () {
        $searchInput.val('');
        toggleClearButton();
        $('#search-form').submit();
    });

    // Поиск при нажатии Enter
    $searchInput.on('keypress', function (e) {
        if (e.which === 13) { // Код клавиши Enter
            e.preventDefault();
            $('#search-form').submit();
        }
    });

    // Загрузка дополнительных постов
    $(document).on('click', '#load-more', function () {
        const $button = $(this);
        const page = $button.data('page');
        const search = $button.data('search');
        const url = $button.data('url');

        $.ajax({
            url: url,
            method: 'GET',
            data: {
                page: page,
                search: search
            },
            success: function (response) {
                if (response.html && response.html.trim().length > 0) {
                    $('#multimedia-list').append(response.html);

                    if (response.hasMore) {
                        $button.data('page', response.nextPage);
                    } else {
                        $button.parent().remove();
                    }
                } else {
                    $button.parent().remove();
                }
            },
            error: function () {
                alert('Ошибка при загрузке постов.');
            }
        });
    });
});
