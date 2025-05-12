import $ from 'jquery';

function handleGenreTags() {
    document.querySelectorAll('.book-categories').forEach(container => {
        const tags = Array.from(container.querySelectorAll('.book-category-tag:not(.more-genres)'));
        container.querySelectorAll('.more-genres').forEach(el => el.remove());

        tags.forEach(tag => tag.classList.remove('hidden'));

        const containerWidth = container.clientWidth;
        const moreTagWidth = 88; // запас на +N жанров, можешь измерить точно
        let totalWidth = 0;
        let cutoffIndex = tags.length;

        for (let i = 0; i < tags.length; i++) {
            const tag = tags[i];
            totalWidth += tag.offsetWidth + 4; // 4px — это gap

            if (totalWidth + moreTagWidth > containerWidth) {
                cutoffIndex = i;
                break;
            }
        }

        if (cutoffIndex < tags.length) {
            const hiddenTags = tags.slice(cutoffIndex);
            const count = hiddenTags.length;

            hiddenTags.forEach(tag => tag.classList.add('hidden'));

            const moreTag = document.createElement('span');
            moreTag.className = 'book-category-tag more-genres';
            moreTag.textContent = `+${count} жанра`;
            moreTag.title = hiddenTags.map(t => t.textContent).join(', ');

            container.appendChild(moreTag);
        }
    });
}



$(document).ready(function() {
    $(document).on('click', '#load-more', function(e) {
        e.preventDefault();

        const $button = $(this);
        const page = $button.data('page');
        const search = $button.data('search') || '';
        const url = window.location.pathname;

        $button.prop('disabled', true).text('Загрузка...');

        $.get({
            url: url,
            data: {
                page: page,
                search: search
            },
            success: function(response) {
                $('.library-grid').append(response.html);
                $button.data('page', page + 1);

                if (!response.has_more) {
                    $button.remove();
                }

                // Важно! Обновляем жанры для новых карточек
                handleGenreTags();
            },
            error: function(xhr) {
                console.log('Ошибка загрузки', xhr.responseText);
            },
            complete: function() {
                $button.prop('disabled', false).text('Загрузить еще');
            }
        });
    });

    // И сразу при загрузке страницы
    handleGenreTags();
});

