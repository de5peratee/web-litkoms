import $ from 'jquery';
$(document).ready(function () {
    const button = $('#floating-blob');
    const mainContainer = $('main');
    let isDragging = false;
    let currentX;
    let currentY;
    let initialX;
    let initialY;

    // Начало перетаскивания
    button.on('mousedown', function (e) {
        initialX = e.clientX - currentX;
        initialY = e.clientY - currentY;
        isDragging = true;
    });

    // Перетаскивание
    $(document).on('mousemove', function (e) {
        if (isDragging) {
            e.preventDefault();
            currentX = e.clientX - initialX;
            currentY = e.clientY - initialY;

            // Получаем границы <main>
            const mainRect = mainContainer[0].getBoundingClientRect();
            const buttonWidth = button.outerWidth();
            const buttonHeight = button.outerHeight();

            // Ограничиваем перемещение внутри <main>
            currentX = Math.max(mainRect.left, Math.min(currentX, mainRect.right - buttonWidth));
            currentY = Math.max(mainRect.top, Math.min(currentY, mainRect.bottom - buttonHeight));

            // Позиционирование относительно <main>
            button.css({
                left: currentX - mainRect.left + 'px',
                top: currentY - mainRect.top + 'px'
            });
        }
    });

    // Конец перетаскивания
    $(document).on('mouseup', function () {
        if (isDragging) {
            isDragging = false;
            snapToCorner();
        }
    });

    // Прилипание к ближайшему углу внутри <main>
    function snapToCorner() {
        const mainRect = mainContainer[0].getBoundingClientRect();
        const buttonWidth = button.outerWidth();
        const buttonHeight = button.outerHeight();

        // Определяем углы внутри <main>
        const corners = [
            { x: 0, y: 0 }, // Левый верхний угол <main>
            { x: mainRect.width - buttonWidth, y: 0 }, // Правый верхний
            { x: 0, y: mainRect.height - buttonHeight }, // Левый нижний
            { x: mainRect.width - buttonWidth, y: mainRect.height - buttonHeight } // Правый нижний
        ];

        let closestCorner = corners[0];
        let minDistance = Number.MAX_VALUE;

        // Вычисляем расстояние до каждого угла
        corners.forEach(corner => {
            const distance = Math.sqrt(
                Math.pow((currentX - mainRect.left) - corner.x, 2) +
                Math.pow((currentY - mainRect.top) - corner.y, 2)
            );
            if (distance < minDistance) {
                minDistance = distance;
                closestCorner = corner;
            }
        });

        // Анимация прилипания
        button.animate({
            left: closestCorner.x + 'px',
            top: closestCorner.y + 'px'
        }, 300);
        currentX = closestCorner.x + mainRect.left;
        currentY = closestCorner.y + mainRect.top;
    }

    // Инициализация позиции (правый нижний угол <main>)
    function initPosition() {
        const mainRect = mainContainer[0].getBoundingClientRect();
        const buttonWidth = button.outerWidth();
        const buttonHeight = button.outerHeight();
        currentX = mainRect.right - buttonWidth;
        currentY = mainRect.bottom - buttonHeight;
        button.css({
            left: mainRect.width - buttonWidth + 'px',
            top: mainRect.height - buttonHeight + 'px'
        });
    }

    // Вызов инициализации
    initPosition();

    // Обновление позиции при изменении размеров окна
    $(window).on('resize', initPosition);

    // Обработчик клика (опционально)
    button.on('click', function () {
        console.log('Кнопка нажата!');
        // Добавьте здесь нужное действие
    });
});
