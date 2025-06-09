import $ from 'jquery';

$(document).ready(function() {
    var $slider = $('#media-slider');
    var $sliderImage = $('#slider-image');
    var $prevBtn = $('.slider-prev-btn');
    var $nextBtn = $('.slider-next-btn');
    var $closeBtn = $('.slider-close-btn');
    var $sliderOverlay = $('.media-slider-overlay');
    var $sliderCounter = $('#slider-counter');

    var currentImages = [];
    var currentIndex = 0;

    // Открытие слайдера при клике на post-media-grid
    $('.post-media-grid').on('click', function() {
        var images = $(this).data('images') || [];
        if (images.length === 0) return;

        currentImages = images;
        currentIndex = 0;
        updateSlider();
        $slider.css('display', 'flex');
    });

    // Закрытие слайдера
    $closeBtn.on('click', closeSlider);
    $sliderOverlay.on('click', closeSlider);

    function closeSlider() {
        $slider.css('display', 'none');
        currentImages = [];
        currentIndex = 0;
    }

    // Навигация по слайдеру
    $prevBtn.on('click', function() {
        if (currentIndex > 0) {
            currentIndex--;
            updateSlider();
        }
    });

    $nextBtn.on('click', function() {
        if (currentIndex < currentImages.length - 1) {
            currentIndex++;
            updateSlider();
        }
    });

    function updateSlider() {
        $sliderImage.attr('src', currentImages[currentIndex]);
        $prevBtn.toggleClass('disabled', currentIndex === 0);
        $nextBtn.toggleClass('disabled', currentIndex === currentImages.length - 1);
        $sliderCounter.text((currentIndex + 1) + '/' + currentImages.length);
    }
});
