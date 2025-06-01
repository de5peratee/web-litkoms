import $ from 'jquery';
$(document).ready(function() {
    let timeLeft = 60;
    const $resendBtn = $('#resend-btn');
    const $timer = $('#resend-timer');

    if ($resendBtn.length && $timer.length) {
        $resendBtn.prop('disabled', true);
        $timer.css('display', 'block');

        const interval = setInterval(function() {
            timeLeft--;
            $timer.text(`Повторить через ${timeLeft} секунд`);

            if (timeLeft <= 0) {
                clearInterval(interval);
                $timer.hide();
                $resendBtn.prop('disabled', false);
            }
        }, 1000);
    }
});
