import $ from 'jquery';

$(document).ready(function() {
    $('#subscribeBtn').click(function(e) {
        e.preventDefault();

        const $btn = $(this);
        const nickname = $btn.data('nickname');
        const isSub = $btn.hasClass('subscribed-btn');
        const url = isSub ? '/unsubscribe/' + nickname : '/subscribe/' + nickname;

        const token = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            url: url,
            method: 'POST',
            dataType: 'json',
            data: {
                _token: token
            },
            success: function(data) {
                if (data.redirect_url) {
                    window.location.href = data.redirect_url;
                    return;
                }

                if (data.status) {
                    $btn.toggleClass('subscribed-btn');

                    if (data.isSub) {
                        $btn.html('<p>Вы подписаны</p><img src="/images/icons/check-gray.svg" class="icon-24">');
                    } else {
                        $btn.html('<p>Подписаться</p>');
                    }

                    $('.user-subscribers').text('Подписчиков: ' + data.subscribersCount);
                }
            },
            error: function(xhr) {
                let json;
                try {
                    json = xhr.responseJSON || JSON.parse(xhr.responseText);
                } catch (e) {
                    json = null;
                }

                if (json && json.redirect_url) {
                    window.location.href = json.redirect_url;
                    return;
                }

                if (xhr.status === 419) {
                    alert('Сессия истекла. Пожалуйста, перезагрузите страницу.');
                    location.reload();
                } else {
                    console.error('Error:', xhr.responseText);
                }
            }
        });
    });
});
