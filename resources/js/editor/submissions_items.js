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

    // Делегирование событий для динамически добавленных элементов
    $(document).on('click', '.submission-action-tab', function () {
        const $tab = $(this);
        const $submissionItem = $tab.closest('.submission-item');
        currentComicSlug = $submissionItem.data('comic-slug');
        isDislike = $tab.hasClass('dislike-tab');

        $submissionItem.find('.submission-action-tab').removeClass('active-submission-tab');
        $tab.addClass('active-submission-tab');
        updateIcons($submissionItem);

        $('#review-submission-modal-title').text(isDislike ? 'Отклонить заявку' : 'Принять заявку');
        $('#submit-review-btn').text(isDislike ? 'Да, отклонить' : 'Да, принять');
        $('#moderation-status').val(isDislike ? 'unsuccessful' : 'successful');
        $('#feedback-field').toggle(isDislike);
        $('#review-submission-form').attr('action', `/dashboard/editor/moderation/${currentComicSlug}`);
        $('#age-restriction').val($submissionItem.data('age-restriction'));

        $('#review-submission-modal').addClass('show').removeClass('hidden');
    });

    // Закрытие модалки
    $('#cancel-review-btn, #review-submission-modal-close').on('click', function () {
        closeModal();
    });

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
            },
            error: function (xhr) {
                const errorMessage = xhr.responseJSON?.message || 'Произошла ошибка';
                $('#review-submission-error').text(errorMessage).addClass('error');
            }
        });
    });

    // Загрузка дополнительных комиксов
    $(document).on('click', '#load-more', function () {
        const $button = $(this);
        const page = $button.data('page');
        const search = $button.data('search');
        const status = $button.data('status');

        $.ajax({
            url: '/dashboard/comics_submissions',
            type: 'GET',
            data: { page, search, status },
            success: function (response) {
                $('.submissions-list').append(response);
                $button.data('page', page + 1);
                if ($(response).find('.load-more-container').length === 0) {
                    $button.parent().remove();
                }
            },
            error: function (xhr) {
                console.error('Ошибка при загрузке комиксов:', xhr.responseText);
            }
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
        $('#age-restriction').val('');
        currentComicSlug = null;
        isDislike = false;
    }
});