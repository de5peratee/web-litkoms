import $ from 'jquery';

document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('#comment-form');
    const input = document.querySelector('#comment-input');
    const errorEl = document.querySelector('#comment-error');
    const commentList = document.querySelector('#comment-list');
    const commentsCount = document.querySelector('#comments-count');
    const noCommentsText = document.querySelector('#no-comments');

    if (!form) return;

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        errorEl.style.display = 'none';

        const comment = input.value.trim();
        if (!comment) return;

        try {
            const response = await fetch(form.getAttribute('action') || window.location.pathname, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ comment }),
            });

            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.message || 'Ошибка при добавлении комментария.');
            }

            const temp = document.createElement('div');
            temp.innerHTML = data.commentHtml;

            if (noCommentsText) {
                noCommentsText.remove();
            }

            const newComment = temp.firstElementChild;

            // Добавляем анимацию
            newComment.classList.add('animate-in');

            // Удаляем класс после завершения анимации
            newComment.addEventListener('animationend', () => {
                newComment.classList.remove('animate-in');
            });

            commentList.prepend(newComment);
            input.value = '';
            commentsCount.textContent = parseInt(commentsCount.textContent) + 1;
        } catch (err) {
            errorEl.textContent = err.message;
            errorEl.style.display = 'flex';
        }
    });
});
