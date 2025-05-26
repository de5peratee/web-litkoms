document.getElementById('cover').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('coverPreview');
    const defaultIcon = preview.querySelector('.default-icon');
    let loadedImage = preview.querySelector('.loaded-image');

    if (!loadedImage) {
        loadedImage = document.createElement('img');
        loadedImage.classList.add('loaded-image');
        preview.appendChild(loadedImage);
    }

    if (file) {
        const reader = new FileReader();

        reader.onload = function(event) {
            loadedImage.src = event.target.result;
            loadedImage.style.display = 'block';
            defaultIcon.style.opacity = '0';
            preview.classList.add('hovered');
        };

        reader.onerror = function() {
            console.error('Ошибка при чтении файла:', reader.error);
        };

        reader.readAsDataURL(file);
    } else {
        loadedImage.style.display = 'none';
        defaultIcon.style.opacity = '1';
        preview.classList.remove('hovered');
    }
});

// Обработчик клика на обертку
document.getElementById('coverPreview').addEventListener('click', function(e) {
    const input = document.getElementById('cover');
    // Если клик не на самом input, вызываем клик на input
    if (e.target.tagName !== 'INPUT') {
        // Сбрасываем значение input, чтобы можно было выбрать тот же файл
        input.value = '';
        // Программно вызываем клик на input для открытия диалога выбора файла
        input.click();
    }
});
