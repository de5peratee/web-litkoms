document.addEventListener('DOMContentLoaded', function() {
    const slider = document.querySelector('.slider');
    const slides = document.querySelectorAll('.slide');
    const prevButton = document.querySelector('.prev-button');
    const nextButton = document.querySelector('.next-button');
    const dotsContainer = document.querySelector('.dots');

    let currentSlide = 0;
    const slideCount = slides.length;

    // Создаем точки навигации
    slides.forEach((_, index) => {
        const dot = document.createElement('div');
        dot.classList.add('dot');
        dot.addEventListener('click', () => goToSlide(index));
        dotsContainer.appendChild(dot);
    });

    const dots = document.querySelectorAll('.dot');

    function updateDots() {
        dots.forEach((dot, index) => {
            dot.classList.toggle('active', index === currentSlide);
        });
    }

    function goToSlide(n) {
        currentSlide = (n + slideCount) % slideCount;
        slider.style.transform = `translateX(-${currentSlide * 100}%)`;
        updateDots();
    }

    prevButton.addEventListener('click', () => goToSlide(currentSlide - 1));
    nextButton.addEventListener('click', () => goToSlide(currentSlide + 1));

    // Автоматическое переключение
    setInterval(() => goToSlide(currentSlide + 1), 5000);

    // Инициализация
    updateDots();
});
