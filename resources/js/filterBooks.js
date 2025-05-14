import $ from 'jquery';

$(document).ready(function () {
    // Показать/скрыть поле ввода жанра
    $('#toggle-genre-input').on('click', function () {
        $('#genre-input-wrapper').toggleClass('hidden');
        $('#genre-input').focus();
    });

    // Добавление нового жанра по Enter
    $('#genre-input').on('keypress', function (e) {
        if (e.which === 13) {
            e.preventDefault();
            const inputVal = $(this).val().trim();

            if (inputVal && $(`.genre-tag[data-value="${inputVal}"]`).length === 0) {
                const $newTag = $(`
                    <div class="genre-tag" data-value="${inputVal}">
                        ${inputVal}
                        <img src="/images/icons/small-close.svg" class="icon-24 remove-genre-tag">
                    </div>
                `);
                $('#genre-tags').append($newTag);
                $(this).val('');
                updateSliderScroll();
            }
        }
    });

    // Удаление жанра
    $(document).on('click', '.remove-genre-tag', function (e) {
        e.stopPropagation();
        $(this).closest('.genre-tag').remove();
        updateSliderScroll();
    });

    // Отправка поиска
    $('#search-form').on('submit', function (e) {
        e.preventDefault();
        triggerFilter();
    });

    function triggerFilter() {
        const selectedGenres = $('.genre-tag').map(function () {
            return $(this).data('value');
        }).get();

        $.ajax({
            url: '/library',
            type: 'GET',
            data: {
                genres: selectedGenres,
                search: $('input[name="search"]').val()
            },
            traditional: true,
            success: function (data) {
                $('.library-grid').html(data.html);
                if (data.has_more) {
                    $('.load-more-container').show();
                    $('#load-more').data('page', 2);
                } else {
                    $('.load-more-container').hide();
                }
            },
            error: function (xhr) {
                console.error('Ошибка загрузки:', xhr.responseText);
            }
        });
    }

    // Слайдер тегов
    function updateSliderScroll() {
        const wrapper = document.querySelector('.genre-tags-wrapper');
        const showSlider = wrapper.scrollWidth > 640;
        document.querySelector('.genre-slider').classList.toggle('scrollable', showSlider);
    }

    $('.slider-btn.prev').on('click', function () {
        $('.genre-tags-wrapper').animate({ scrollLeft: '-=100' }, 200);
    });

    $('.slider-btn.next').on('click', function () {
        $('.genre-tags-wrapper').animate({ scrollLeft: '+=100' }, 200);
    });

    updateSliderScroll();
});
