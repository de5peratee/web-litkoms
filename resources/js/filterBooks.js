import $ from 'jquery';

$(document).ready(function () {
    const $categories = $('.library-categories');
    const scrollAmount = 240;

    function updateScrollButtons() {
        const scrollLeft = $categories.scrollLeft();
        const scrollWidth = $categories[0].scrollWidth;
        const clientWidth = $categories.outerWidth();

        if (scrollLeft <= 0) {
            $('.scroll-left').addClass('hidden');
        } else {
            $('.scroll-left').removeClass('hidden');
        }

        if (scrollLeft + clientWidth >= scrollWidth - 1) {
            $('.scroll-right').addClass('hidden');
        } else {
            $('.scroll-right').removeClass('hidden');
        }
    }

    // Обновляем после загрузки, скролла и ресайза
    updateScrollButtons();
    $categories.on('scroll', updateScrollButtons);
    $(window).on('resize', updateScrollButtons);

    $('.scroll-left').click(function () {
        $categories.animate({ scrollLeft: '-=' + scrollAmount }, 100, updateScrollButtons);
    });

    $('.scroll-right').click(function () {
        $categories.animate({ scrollLeft: '+=' + scrollAmount }, 100, updateScrollButtons);
    });

    $('.category-tag').click(function () {
        const $this = $(this);
        const categoryId = $this.data('category');

        if (categoryId === 'all') {
            $('.category-tag').removeClass('active');
            $this.addClass('active');
        } else {
            $('.category-tag[data-category="all"]').removeClass('active');
            $this.toggleClass('active');

            if ($('.category-tag.active').length === 0) {
                $('.category-tag[data-category="all"]').addClass('active');
            }
        }

        const selectedGenres = [];
        $('.category-tag.active').each(function () {
            selectedGenres.push($(this).data('category'));
        });

        filterBooks(selectedGenres);
    });

    function filterBooks(genres = []) {
        $.ajax({
            url: '/library',
            type: 'GET',
            data: {
                genres: genres,
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

                updateScrollButtons(); // на всякий случай
            },
            error: function (xhr) {
                console.error('Ошибка загрузки:', xhr.responseText);
            }
        });
    }
});
