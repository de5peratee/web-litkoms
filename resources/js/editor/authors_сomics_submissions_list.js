import $ from 'jquery';

$(document).ready(function () {
    let currentComicSlug = null;
    let isDislike = false;

    // Обновление иконок в табах действий
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
        currentComicSlug = $submissionItem.data('comic-slug');
        isDislike = $tab.hasClass('dislike-tab');

        // Стилизация активного таба
        $submissionItem.find('.submission-action-tab').removeClass('active-submission-tab');
        $tab.addClass('active-submission-tab');
        updateIcons($submissionItem);

        // Настройка модалки
        $('#review-submission-modal-title').text(isDislike ? 'Отклонить заявку' : 'Принять заявку');
        $('#submit-review-btn').text(isDislike ? 'Да, отклонить' : 'Да, принять');
        $('#moderation-status').val(isDislike ? 'unsuccessful' : 'successful');
        $('#feedback-field').toggle(isDislike);
        $('#review-submission-form').attr('action', `/dashboard/editor/moderation/${currentComicSlug}`);

        // Показ модалки
        $('#review-submission-modal').addClass('show').removeClass('hidden');
    });

    // Закрытие модалки
    $('#cancel-review-btn, #review-submission-modal-close').on('click', function () {
        closeModal();
    });

    // Закрытие при клике вне контента
    $('#review-submission-modal').on('click', function (e) {
        if ($(e.target).is('#review-submission-modal')) {
            closeModal();
        }
    });

    // Очистка поля поиска
    $('.clear-search').on('click', function () {
        $('input[name="search"]').val('');
        $(this).addClass('hidden');
        $('#search-form').submit();
    });

    // Отправка формы модерации
    $('#review-submission-form').on('submit', function (e) {
        e.preventDefault();
        const formData = $(this).serialize();

        $.ajax({
            url: $(this).attr('action'),
            type: 'PUT',
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                closeModal();
                $(`.submission-item[data-comic-slug="${currentComicSlug}"]`).remove();
                $('.submissions-count-text').text(parseInt($('.submissions-count-text').text()) - 1);
                location.reload(); // Перезагрузка страницы
            },
            error: function (xhr) {
                const errorMessage = xhr.responseJSON?.message || 'Произошла ошибка';
                $('#review-submission-error').text(errorMessage).addClass('error');
            }
        });
    });

    // Загрузка дополнительных комиксов
    $('#load-more').on('click', function () {
        const $button = $(this);
        const page = $button.data('page');
        const search = $button.data('search');
        const status = $button.data('status');

        $.ajax({
            url: '{{ route("editor.comics_submissions_index") }}',
            type: 'GET',
            data: { page, search, status },
            success: function (response) {
                $('.submissions-list').append($(response).find('.submissions-list').html());
                $button.data('page', page + 1);
                if (!$(response).find('.load-more-container').length) {
                    $button.hide();
                }
            },

        });
    });

    // Закрытие и сброс модалки
    function closeModal() {
        $('#review-submission-modal').removeClass('show').addClass('hidden');
        $('.submission-action-tab').removeClass('active-submission-tab');
        updateIcons($('.submission-item'));
        $('#edit-submission-comment').val('');
        $('#review-submission-error').text('').removeClass('error');
        $('#review-submission-form').attr('action', '');
        currentComicSlug = null;
        isDislike = false;
    }
});