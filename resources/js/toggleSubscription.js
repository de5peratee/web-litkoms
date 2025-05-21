import $ from 'jquery';
$(document).ready(function() {
    $('#subscribeBtn').click(function(e) {
        e.preventDefault();

        const $btn = $(this);
        const nickname = $btn.data('nickname');
        const isSub = $btn.hasClass('subscribed-btn');
        const url = isSub ? '/unsubscribe/' + nickname : '/subscribe/' + nickname;

        // Получаем CSRF-токен из мета-тега
        const token = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            url: url,
            method: 'POST',
            dataType: 'json',
            data: {
                _token: token  // Явно передаем токен в данных
            },
            success: function(data) {
                if (data.status) {
                    $btn.toggleClass('subscribed-btn');

                    if (data.isSub) {
                        $btn.html('<img src="/images/icons/check-gray.svg" class="icon-24"><p>Вы подписаны</p>');
                    } else {
                        $btn.html('<p>Подписаться</p>');
                    }

                    $('.user-subscribers').text('Подписчиков: ' + data.subscribersCount);
                }
            },
            error: function(xhr) {
                if (xhr.status === 419) { // Код ошибки CSRF
                    alert('Сессия истекла. Пожалуйста, перезагрузите страницу.');
                    location.reload();
                } else {
                    console.error('Error:', xhr.responseText);
                }
            }
        });
    });
});
