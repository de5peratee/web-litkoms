document.addEventListener('DOMContentLoaded', () => {
    const stars = document.querySelectorAll('.rating-stars .star-icon');
    const ratingInput = document.getElementById('comic-rating');
    const avgGradeElement = document.querySelector('.user-avg-grade p');

    if (!stars.length || !window.rateUrl || !avgGradeElement || !ratingInput) return;

    stars.forEach(star => {
        star.addEventListener('click', () => {
            const selected = parseInt(star.dataset.index);
            const current = parseInt(ratingInput.value || 0);

            let newRating = selected;
            if (current === selected) {
                newRating = 0;
            }

            // Обновление UI сразу (оптимистично)
            stars.forEach(s => {
                s.src = s.dataset.index <= newRating ? window.filledStar : window.outlineStar;
            });

            ratingInput.value = newRating;

            fetch(window.rateUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ rating: newRating }),
            })
                .then(async response => {
                    let data;
                    try {
                        data = await response.json();
                    } catch (e) {
                        window.location.reload();
                        return;
                    }

                    if (data.redirect_url) {
                        window.location.href = data.redirect_url;
                        return;
                    }

                    if (!response.ok) {
                        throw new Error(data.message || 'Ошибка при сохранении рейтинга');
                    }

                    avgGradeElement.textContent = data.average_rating;
                    ratingInput.value = data.user_rating;
                })
                .catch(error => {
                    console.error('Ошибка при отправке рейтинга:', error);
                    stars.forEach(s => {
                        s.src = s.dataset.index <= current ? window.filledStar : window.outlineStar;
                    });
                    ratingInput.value = current;
                });
        });
    });
});
