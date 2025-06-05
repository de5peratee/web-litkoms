import $ from 'jquery';

$(document).ready(function () {
    // Настройка CSRF-токена для AJAX
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Показ/скрытие крестика в поле поиска
    const $searchInput = $('#search-input');
    const $clearSearch = $('.clear-search');

    function toggleClearButton() {
        if ($searchInput.val().length > 0) {
            $clearSearch.removeClass('hidden');
        } else {
            $clearSearch.addClass('hidden');
        }
    }

    toggleClearButton();
    $searchInput.on('input', toggleClearButton);

    // Очистка поля поиска
    $clearSearch.on('click', function () {
        $searchInput.val('');
        toggleClearButton();
        $('#search-form').submit();
    });

    // Поиск при нажатии Enter
    $searchInput.on('keypress', function (e) {
        if (e.which === 13) { // Код клавиши Enter
            e.preventDefault();
            $('#search-form').submit();
        }
    });

    // Загрузка дополнительных постов
    $(document).on('click', '#load-more', function () {
        const $button = $(this);
        const page = $button.data('page');
        const search = $button.data('search');
        const url = $button.data('url');

        $.ajax({
            url: url,
            method: 'GET',
            data: {
                page: page,
                search: search
            },
            success: function (response) {
                if (response.mediaPosts && response.mediaPosts.length > 0) {
                    const $multimediaList = $('#multimedia-list');
                    const html = response.mediaPosts.map((post, index) => {
                        const medias = post.medias && post.medias.length > 0 ? post.medias.map(media => {
                            const fileExtension = media.file.split('.').pop().toLowerCase();
                            let type = 'unsupported';
                            if (['jpg', 'jpeg', 'png', 'gif'].includes(fileExtension)) {
                                type = 'image';
                            } else if (['mp4', 'webm', 'ogg'].includes(fileExtension)) {
                                type = 'video';
                            } else if (['mp3', 'wav', 'ogg'].includes(fileExtension)) {
                                type = 'audio';
                            }
                            return {
                                url: `/storage/${media.file}`,
                                type: type,
                                ext: fileExtension
                            };
                        }) : [];

                        return `
                            <div class="multimedia-item">
                                <div class="multimedia-item-left">
                                    <div class="item-cell num-cell">${(page - 1) * 10 + index + 1}</div>
                                    <div class="item-cell multimedia-preview-cell">
                                        <div class="multimedia-cover-wrapper">
                                            <img src="/images/icons/hw/media-form-icon.svg" class="event-cover" alt="icon">
                                        </div>
                                        <div class="multimedia-preview-text-wrapper">
                                            <p class="text-big">${post.name}</p>
                                            <p class="text-hint description-text">${post.description || 'Нет описания'}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="multimedia-actions">
                                    <a href="#" class="list-action-btn edit-post-btn"
                                       data-post-id="${post.id}"
                                       data-post-name="${post.name}"
                                       data-post-description="${post.description || ''}"
                                       data-post-medias='${JSON.stringify(medias)}'>
                                        <img src="/images/icons/edit-primary.svg" class="icon-24" alt="edit-icon">
                                    </a>
                                    <a href="#" class="list-action-btn delete-post-btn"
                                       data-post-id="${post.id}"
                                       data-post-name="${post.name}">
                                        <img src="/images/icons/trash-primary-red.svg" class="icon-24" alt="delete-icon">
                                    </a>
                                </div>
                            </div>
                        `;
                    }).join('');

                    $multimediaList.append(html);

                    if (response.hasMore) {
                        $button.data('page', response.nextPage);
                    } else {
                        $button.parent().remove();
                    }
                } else {
                    $button.parent().remove();
                }
            },
            error: function () {
                alert('Ошибка при загрузке постов.');
            }
        });
    });
});
