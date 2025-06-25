import $ from 'jquery';

$(document).ready(function () {
    pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.9.359/pdf.worker.min.js';

    const url = window.pdfUrl;
    const canvas = document.getElementById('pdf-canvas');
    const ctx = canvas.getContext('2d');
    let pdfDoc = null;
    let pageNum = 1;
    let pageRendering = false;
    let pageNumPending = null;
    let scale = 1.0;
    const minScale = 0.3;
    const maxScale = 5.0;
    const zoomStep = 0.2;

    const pageNumDisplay = $('#page-num');
    const pageCountDisplay = $('#page-count');

    function renderPage(num) {
        pageRendering = true;
        pdfDoc.getPage(num).then(function (page) {
            const viewport = page.getViewport({ scale: scale });

            canvas.width = viewport.width;
            canvas.height = viewport.height;

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

    function fitToContainer() {
        pdfDoc.getPage(pageNum).then(function (page) {
            const containerWidth = $('#pdf-canvas-wrapper').width() || window.innerWidth;
            const viewport = page.getViewport({ scale: 1.0 });
            const naturalWidth = viewport.width;
            const newScale = containerWidth / naturalWidth;

            scale = Math.min(newScale, maxScale);
            renderPage(pageNum);
        });
    }

    pdfjsLib.getDocument(url).promise.then(function (pdf) {
        pdfDoc = pdf;
        pageCountDisplay.text(pdfDoc.numPages);
        fitToContainer();
    }).catch(function (error) {
        console.error('Ошибка загрузки PDF:', error);
        $('.pdf-view').html('<p class="text-medium">Не удалось загрузить комикс.</p>');
    });

    $('#prev-page').on('click', function () {
        if (pageNum <= 1) return;
        pageNum--;
        renderPage(pageNum);
    });

    $('#next-page').on('click', function () {
        if (pageNum >= pdfDoc.numPages) return;
        pageNum++;
        renderPage(pageNum);
    });

    $('#zoom-in').on('click', function () {
        if (scale + zoomStep <= maxScale) {
            scale += zoomStep;
            renderPage(pageNum);
        }
    });

    $('#zoom-out').on('click', function () {
        if (scale - zoomStep >= minScale) {
            scale -= zoomStep;
            renderPage(pageNum);
        }
    });

    $(window).on('resize', function () {
        fitToContainer();
    });
});
