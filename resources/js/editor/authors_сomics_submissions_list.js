import $ from 'jquery';

$(document).ready(function () {
    let currentComicId = null;
    let isDislike = false;

    // Функция для обновления иконок в табах
    function updateIcons($container) {
        $container.find('.submission-action-tab img').each(function () {
            const $img = $(this);
            const src = $img.attr('src');
            const baseNameMatch = src.match(/([^/]+)-(?:primary|white)\.svg$/);
            if (baseNameMatch) {
                const baseName = baseNameMatch[1];
                const newSuffix = $img.closest('.submission-action-tab').hasClass('active-submission-tab') ? 'white' : 'primary';
                $img.attr('src', `/images/icons/${baseName}-${newSuffix}.svg`);
            }
        });
    }

    // Открытие модалки
    $('.submission-action-tab').on('click', function () {
        const $tab = $(this);
        const $submissionItem = $tab.closest('.submission-item');
        isDislike = $tab.hasClass('dislike-tab');
        currentComicId = $submissionItem.data('comic-id'); // Убедитесь, что data-comic-id есть в HTML

        // Стилизация активного таба
        $submissionItem.find('.submission-action-tab').removeClass('active-submission-tab');
        $tab.addClass('active-submission-tab');
        updateIcons($submissionItem);

        // Показ/скрытие поля комментария
        const $commentField = $('#edit-submission-comment').closest('.lit-field');
        $commentField.toggle(isDislike);

        // Обновление текста и экшена в модалке
        $('#review-submission-modal-title').text(isDislike ? 'Отклонить заявку' : 'Принять заявку');
        $('#review-submission-modal .primary-btn').text(isDislike ? 'Да, отклонить' : 'Да, принять');
        $('#review-submission-form').attr('action', `/editor/comics/${currentComicId}/${isDislike ? 'reject' : 'accept'}`);

        // Показ модалки
        $('#review-submission-modal').addClass('show').removeClass('hidden');
    });

    // Закрытие модалки (иконка или кнопка "Отмена")
    $('#cancel-review-modal, #review-submission-modal-close').on('click', function () {
        closeModal();
    });

    // Закрытие при клике вне контента
    $('#review-submission-modal').on('click', function (e) {
        if ($(e.target).is('#review-submission-modal')) {
            closeModal();
        }
    });

    // Отправка формы
    $('#review-submission-form').on('submit', function (e) {
        e.preventDefault();
        const formData = $(this).serialize();

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            success: function () {
                closeModal();
                $(`.submission-item[data-comic-id="${currentComicId}"]`).remove();
            },
            error: function (xhr) {
                const errorMessage = xhr.responseJSON?.message || 'Произошла ошибка';
                $('#review-submission-error').text(errorMessage).addClass('error');
            }
        });
    });

    // Закрытие и сброс состояния
    function closeModal() {
        $('#review-submission-modal').removeClass('show').addClass('hidden');
        $('.submission-action-tab').removeClass('active-submission-tab');
        updateIcons($('.submission-item'));
        $('#edit-submission-comment').val('');
        $('#review-submission-error').text('').removeClass('error');
        $('#review-submission-form').attr('action', '');
        currentComicId = null;
        isDislike = false;
    }
});
