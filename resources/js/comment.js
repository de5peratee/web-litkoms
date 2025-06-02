document.addEventListener('DOMContentLoaded', function () {
    const commentForm = document.getElementById('comment-form');
    const commentList = document.getElementById('comment-list');
    const commentsCount = document.getElementById('comments-count');
    const loadMoreBtn = document.getElementById('load-more-comments');
    const noComments = document.getElementById('no-comments');

    if (commentForm) {
        commentForm.addEventListener('submit', async function (e) {
            e.preventDefault();
            const formData = new FormData(commentForm);
            const commentInput = document.getElementById('comment-input');
            const errorMessage = document.getElementById('comment-error');

            try {
                const response = await fetch(commentForm.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                });

                if (!response.ok) {
                    const data = await response.json();

                    if (data.redirect_url) {
                        // Если есть URL для редиректа, перенаправляем пользователя
                        window.location.href = data.redirect_url;
                    } else {
                        errorMessage.textContent = data.message || 'Ошибка при добавлении комментария';
                        errorMessage.style.display = 'block';
                    }
                    return;
                }

                const data = await response.json();
                commentList.insertAdjacentHTML('afterbegin', data.commentHtml);
                commentsCount.textContent = parseInt(commentsCount.textContent) + 1;

                if (noComments) {
                    noComments.style.display = 'none';
                }

                commentInput.value = '';
                errorMessage.style.display = 'none';
            } catch (error) {
                errorMessage.textContent = 'Произошла ошибка. Попробуйте позже.';
                errorMessage.style.display = 'block';
            }
        });
    }

    if (loadMoreBtn) {
        loadMoreBtn.addEventListener('click', async function (e) {
            e.preventDefault();
            const url = loadMoreBtn.getAttribute('data-url');

            try {
                const response = await fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                });

                const data = await response.json();

                if (response.ok) {
                    commentList.insertAdjacentHTML('beforeend', data.commentsHtml);

                    if (data.nextPageUrl) {
                        loadMoreBtn.setAttribute('data-url', data.nextPageUrl);
                    } else {
                        loadMoreBtn.style.display = 'none';
                    }

                    commentsCount.textContent = data.totalComments;

                    if (noComments && data.totalComments > 0) {
                        noComments.style.display = 'none';
                    }
                } else {
                    console.error('Ошибка при загрузке комментариев:', data.message);
                    loadMoreBtn.textContent = 'Ошибка загрузки';
                    loadMoreBtn.disabled = true;
                }
            } catch (error) {
                console.error('Ошибка при загрузке комментариев:', error);
                loadMoreBtn.textContent = 'Ошибка загрузки';
                loadMoreBtn.disabled = true;
            }
        });
    }
});
