import $ from 'jquery';

$(document).ready(function () {
    const filledStar = window.filledStar;
    const outlineStar = window.outlineStar;

    $('.rating-stars .star-icon').on('mouseenter', function () {
        const index = $(this).data('index');
        $(this).parent().children().each(function () {
            const currentIndex = $(this).data('index');
            $(this).attr('src', currentIndex <= index ? filledStar : outlineStar);
        });
    });

    $('.rating-stars .star-icon').on('mouseleave', function () {
        const selected = $(this).parent().data('selected');
        $(this).parent().children().each(function () {
            const currentIndex = $(this).data('index');
            $(this).attr('src', currentIndex <= selected ? filledStar : outlineStar);
        });
    });

    $('.rating-stars .star-icon').on('click', function () {
        const selected = $(this).data('index');
        $(this).parent().data('selected', selected);
        $('#comic-rating').val(selected);
        $(this).trigger('mouseleave'); // Обновить отображение
    });
});
