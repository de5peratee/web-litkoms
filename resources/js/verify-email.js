document.addEventListener('DOMContentLoaded', () => {
    const resendBtn = document.getElementById('resend-btn');
    const timer = document.getElementById('resend-timer');
    const form = document.getElementById('resend-form');

    if (!resendBtn || !timer || !form) {
        console.warn('Элементы #resend-btn, #resend-timer или #resend-form не найдены');
        return;
    }

    const cooldown = 60; // Время ожидания в секундах
    const lastSentKey = 'lastSentTimestamp'; // Ключ для localStorage

    // Функция для вычисления оставшегося времени
    const getRemainingTime = () => {
        const lastSent = localStorage.getItem(lastSentKey);
        if (!lastSent) return 0;

        const elapsed = Math.floor((Date.now() - parseInt(lastSent)) / 1000);
        return Math.max(cooldown - elapsed, 0);
    };

    let timeLeft = getRemainingTime();

    if (timeLeft > 0) {
        resendBtn.disabled = true;
        timer.style.display = 'block';
        timer.textContent = `Повторить через ${timeLeft} секунд`;

        const interval = setInterval(() => {
            timeLeft--;
            timer.textContent = `Повторить через ${timeLeft} секунд`;
            if (timeLeft <= 0) {
                clearInterval(interval);
                timer.style.display = 'none';
                resendBtn.disabled = false;
            }
        }, 1000);
    } else {
        resendBtn.disabled = false;
        timer.style.display = 'none';
    }

    form.addEventListener('submit', async (event) => {
        event.preventDefault();
        resendBtn.disabled = true;
        timer.style.display = 'block';
        timeLeft = cooldown;
        timer.textContent = `Повторить через ${timeLeft} секунд`;
        localStorage.setItem(lastSentKey, Date.now().toString());

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: new FormData(form),
            });

            if (!response.ok) {
                throw new Error('Ошибка при отправке запроса');
            }

            const interval = setInterval(() => {
                timeLeft--;
                timer.textContent = `Повторить через ${timeLeft} секунд`;
                if (timeLeft <= 0) {
                    clearInterval(interval);
                    timer.style.display = 'none';
                    resendBtn.disabled = false;
                }
            }, 1000);
        } catch (error) {
            console.error('Ошибка:', error);
            timer.textContent = 'Ошибка при отправке письма. Попробуйте снова.';
            timer.style.color = 'red';
            resendBtn.disabled = false;
        }
    });
});