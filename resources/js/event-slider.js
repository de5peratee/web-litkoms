import $ from 'jquery';

$(document).ready(function() {
    let currentIndex = 0;
    const $slider = $('.slides-wrapper');
    const $slides = $slider.children('.event-slide');
    const totalSlides = $slides.length;

    function updateSlider() {
        const offset = -currentIndex * 100;
        $slider.css('transform', `translateX(${offset}%)`);
    }

    $('.slider-next').on('click', function() {
        if (currentIndex < totalSlides - 1) {
            currentIndex++;
            updateSlider();
        }
    });

    $('.slider-prev').on('click', function() {
        if (currentIndex > 0) {
            currentIndex--;
            updateSlider();
        }
    });
});
