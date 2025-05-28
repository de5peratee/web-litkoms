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

            stars.forEach(s => {
                s.src = s.dataset.index <= newRating ? window.filledStar : window.outlineStar;
            });

            ratingInput.value = newRating;

            fetch(window.rateUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                body: JSON.stringify({ rating: newRating }),
            })
                .then(response => {
                    if (!response.ok) throw new Error('Ошибка при сохранении рейтинга');
                    return response.json();
                })
                .then(data => {
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