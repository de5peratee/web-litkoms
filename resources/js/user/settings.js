import $ from 'jquery';

$(document).ready(function () {
    // Переключение аватарок
    $('.default-avatar-pick-wrapper-flex').on('click', function () {
        const $wrapper = $(this);
        const avatarPath = $wrapper.data('avatar');
        const $pickWrapper = $wrapper.find('.default-avatar-pick-wrapper');
        const $textTag = $wrapper.find('.active-avatar-text-tag');

        // Удаляем active-default-avatar и скрываем "Выбрано" у всех
        $('.default-avatar-pick-wrapper').removeClass('active-default-avatar');
        $('.active-avatar-text-tag').addClass('hidden');

        // Добавляем active-default-avatar и показываем "Выбрано" для выбранной аватарки
        $pickWrapper.addClass('active-default-avatar');
        $textTag.removeClass('hidden');

        // Обновляем скрытое поле формы
        $('#selected-icon').val(avatarPath);

        // Обновляем основную аватарку
        $('.user-settings-avatar-wrapper')
            .removeClass('custom')
            .attr('data-default-avatar', 'default');
        $('.user-settings-avatar-wrapper .avatar-image').attr('src', `${window.assetBasePath}/${avatarPath}`);

        // Создаём временный File объект для встроенной аватарки
        fetch(`${window.assetBasePath}/${avatarPath}`)
            .then(response => response.blob())
            .then(blob => {
                const file = new File([blob], avatarPath.split('/').pop(), { type: 'image/png' });
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                $('#custom-icon')[0].files = dataTransfer.files;
            })
            .catch(error => console.error('Ошибка загрузки аватарки:', error));
    });

    // Обработка загрузки кастомного изображения
    const $customIconInput = $('#custom-icon');
    $('.user-settings-avatar-wrapper').on('click', function (e) {
        e.preventDefault();
        if (e.target.tagName !== 'INPUT') {
            $customIconInput.val('');
            $customIconInput.click();
        }
    });

    // Обработка изменения файла
    $customIconInput.on('change', function (e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                $('.user-settings-avatar-wrapper')
                    .addClass('custom')
                    .attr('data-default-avatar', 'custom');
                $('.user-settings-avatar-wrapper .avatar-image').attr('src', e.target.result);

                $('#selected-icon').val(''); // Сбрасываем, так как файл отправляется через input

                $('.default-avatar-pick-wrapper').removeClass('active-default-avatar');
                $('.active-avatar-text-tag').addClass('hidden');
            };
            reader.readAsDataURL(file);
        }
    });

    // Тостер сообщение
    const $toaster = $('#toaster');
    if ($toaster.length && $.trim($toaster.text()) !== '') {
        setTimeout(() => $toaster.addClass('show'), 100); // плавное появление
        setTimeout(() => $toaster.removeClass('show'), 3100); // скрытие через 3 секунды
    }
});
