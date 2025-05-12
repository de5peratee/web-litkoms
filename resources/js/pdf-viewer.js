import $ from 'jquery';

$(document).ready(function () {
    // Настройка PDF.js
    pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.9.359/pdf.worker.min.js';

    const url = window.pdfUrl; // Путь к PDF из Blade
    const canvas = document.getElementById('pdf-canvas');
    const ctx = canvas.getContext('2d');
    let pdfDoc = null;
    let pageNum = 1;
    let pageRendering = false;
    let pageNumPending = null;
    let scale = 1.5; // Начальный масштаб
    let maxScale = 1.5; // Будет вычислен позже

    const pageNumDisplay = $('#page-num');
    const pageCountDisplay = $('#page-count');

    // Функция рендеринга страницы
    function renderPage(num) {
        pageRendering = true;
        pdfDoc.getPage(num).then(function (page) {
            const viewport = page.getViewport({ scale: scale });
            canvas.height = viewport.height;
            canvas.width = viewport.width;

            const renderContext = {
                canvasContext: ctx,
                viewport: viewport
            };

            page.render(renderContext).promise.then(function () {
                pageRendering = false;
                if (pageNumPending !== null) {
                    renderPage(pageNumPending);
                    pageNumPending = null;
                }
            });

            pageNumDisplay.text(num);
        });
    }

    // Загрузка PDF
    pdfjsLib.getDocument(url).promise.then(function (pdf) {
        pdfDoc = pdf;
        pageCountDisplay.text(pdfDoc.numPages);

        // Вычисляем максимальный масштаб на основе ширины страницы
        pdfDoc.getPage(1).then(function (page) {
            const viewport = page.getViewport({ scale: 1.0 });
            const pageWidth = viewport.width; // Оригинальная ширина страницы
            maxScale = 600 / pageWidth; // Максимальный масштаб, чтобы ширина была <= 600px
            scale = Math.min(scale, maxScale); // Устанавливаем начальный масштаб
            renderPage(pageNum);
        });
    }).catch(function (error) {
        console.error('Ошибка загрузки PDF:', error);
        $('.pdf-view').html('<p class="text-medium">Не удалось загрузить комикс.</p>');
    });

    // Навигация по страницам
    $('#prev-page').on('click', function () {
        if (pageNum <= 1) return;
        pageNum--;
        if (pageRendering) {
            pageNumPending = pageNum;
        } else {
            renderPage(pageNum);
        }
    });

    $('#next-page').on('click', function () {
        if (pageNum >= pdfDoc.numPages) return;
        pageNum++;
        if (pageRendering) {
            pageNumPending = pageNum;
        } else {
            renderPage(pageNum);
        }
    });

    // Масштабирование
    $('#zoom-in').on('click', function () {
        const newScale = scale + 0.2;
        if (newScale <= maxScale) { // Ограничиваем масштаб максимальным значением
            scale = newScale;
            if (pageRendering) {
                pageNumPending = pageNum;
            } else {
                renderPage(pageNum);
            }
        }
    });

    $('#zoom-out').on('click', function () {
        if (scale <= 0.5) return;
        scale -= 0.2;
        if (pageRendering) {
            pageNumPending = pageNum;
        } else {
            renderPage(pageNum);
        }
    });

    // Адаптивность canvas
    function resizeCanvas() {
        if (pdfDoc) {
            renderPage(pageNum);
        }
    }

    $(window).on('resize', resizeCanvas);
});
