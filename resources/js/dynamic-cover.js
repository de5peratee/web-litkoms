document.getElementById('cover').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('coverPreview');
    const defaultIcon = preview.querySelector('.default-icon');
    let loadedImage = preview.querySelector('.loaded-image');
    const maxSizeMB = 50; // Максимальный размер в мегабайтах
    const maxSizeBytes = maxSizeMB * 1024 * 1024; // Конвертация в байты
    const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp']; // Допустимые форматы

    // Очистка предыдущих ошибок
    const existingError = preview.querySelector('.error-message');
    if (existingError) {
        existingError.remove();
    }

    if (file) {
        // Проверка размера файла
        if (file.size > maxSizeBytes) {
            const errorMessage = document.createElement('div');
            errorMessage.classList.add('input-error');
            errorMessage.textContent = `Файл слишком большой. Максимальный размер: ${maxSizeMB} МБ.`;
            preview.appendChild(errorMessage);
            this.value = ''; // Сбрасываем input
            return;
        }

        // Проверка формата файла
        if (!allowedTypes.includes(file.type)) {
            const errorMessage = document.createElement('div');
            errorMessage.classList.add('input-error');
            errorMessage.textContent = 'Недопустимый формат файла. Допустимы: JPEG, PNG, GIF, WebP.';
            preview.appendChild(errorMessage);
            this.value = ''; // Сбрасываем input
            return;
        }

        const reader = new FileReader();

        reader.onload = function(event) {
            if (!loadedImage) {
                loadedImage = document.createElement('img');
                loadedImage.classList.add('loaded-image');
                preview.appendChild(loadedImage);
            }
            loadedImage.src = event.target.result;
            loadedImage.style.display = 'block';
            defaultIcon.style.opacity = '0';
            preview.classList.add('hovered');
        };

        reader.onerror = function() {
            const errorMessage = document.createElement('div');
            errorMessage.classList.add('input-error');
            errorMessage.textContent = 'Ошибка при чтении файла.';
            preview.appendChild(errorMessage);
            console.error('Ошибка при чтении файла:', reader.error);
        };

        reader.readAsDataURL(file);
    } else {
        if (loadedImage) {
            loadedImage.style.display = 'none';
        }
        defaultIcon.style.opacity = '1';
        preview.classList.remove('hovered');
    }
});

// Обработчик клика на обертку (без изменений)
document.getElementById('coverPreview').addEventListener('click', function(e) {
    const input = document.getElementById('cover');
    if (e.target.tagName !== 'INPUT') {
        input.value = '';
        input.click();
    }
});
