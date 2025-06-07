import $ from 'jquery';
$(document).ready(function() {
    // Плейлист с путями к аудиофайлам в public/audio/
    const playlist = [
        { title: "sampleb 1", src: "/audio/sampleb.mp3" },
        { title: "sampleb 2", src: "/audio/sampleb.mp3" }
    ];

    let currentTrackIndex = 0; // Индекс текущего трека
    let isLooping = false; // Флаг зацикливания
    let sound = null; // Объект Howler.js для текущего трека

    // Обновление иконки громкости
    function updateVolumeIcon(volume) {
        $('.volume-icon').removeClass('active');
        if (volume === 0) {
            $('.volume-off').addClass('active');
        } else if (volume <= 0.75) {
            $('.volume-down').addClass('active');
        } else {
            $('.volume-up').addClass('active');
        }
    }

    // Функция загрузки трека
    function loadTrack(index) {
        if (!playlist.length) {
            $('.track-title').text('Плейлист пуст');
            $('.play-btn').prop('disabled', true);
            return;
        }

        if (sound) {
            sound.stop();
            sound.unload();
        }

        try {
            sound = new Howl({
                src: [playlist[index].src],
                html5: true,
                loop: isLooping && playlist.length === 1,
                volume: parseFloat($('.volume-slider').val()),
                onload: function() {
                    updateTrackInfo();
                    $('.play-btn').prop('disabled', false);
                },
                onloaderror: function(id, error) {
                    $('.track-title').text('Ошибка загрузки: ' + playlist[index].title);
                    console.error('Ошибка загрузки аудио:', error);
                    $('.play-btn').prop('disabled', true);
                },
                onplay: function() {
                    $('.play-btn .play-icon').removeClass('active');
                    $('.play-btn .pause-icon').addClass('active');
                    updateTrackInfo();
                    requestAnimationFrame(updateProgress);
                },
                onpause: function() {
                    $('.play-btn .pause-icon').removeClass('active');
                    $('.play-btn .play-icon').addClass('active');
                },
                onend: function() {
                    if (!isLooping || playlist.length > 1) {
                        nextTrack();
                    }
                }
            });
        } catch (error) {
            $('.track-title').text('Ошибка инициализации плеера');
            console.error('Ошибка Howler.js:', error);
        }
    }

    // Обновление информации о треке
    function updateTrackInfo() {
        if (!playlist.length) return;
        $('.track-title').text(playlist[currentTrackIndex].title);
        const duration = sound && sound.duration() ? formatTime(sound.duration()) : '0:00';
        $('.time').text('0:00 / ' + duration);
    }

    // Форматирование времени в mm:ss
    function formatTime(seconds) {
        if (!seconds || isNaN(seconds)) return '0:00';
        const min = Math.floor(seconds / 60);
        const sec = Math.floor(seconds % 60);
        return min + ':' + (sec < 10 ? '0' : '') + sec;
    }

    // Обновление прогресс-бара
    function updateProgress() {
        if (sound && sound.playing()) {
            const seek = sound.seek() || 0;
            const duration = sound.duration() || 1;
            const progress = (seek / duration) * 100;
            $('.progress').css('width', progress + '%');
            $('.time').text(formatTime(seek) + ' / ' + formatTime(duration));
            requestAnimationFrame(updateProgress);
        }
    }

    // Кнопка воспроизведения/паузы
    $('.play-btn').click(function() {
        if (!sound || !playlist.length) {
            loadTrack(currentTrackIndex);
            if (sound) sound.play();
        } else if (sound.playing()) {
            sound.pause();
        } else {
            sound.play();
        }
    });

    // Следующий трек
    function nextTrack() {
        if (!playlist.length) return;
        currentTrackIndex = (currentTrackIndex + 1) % playlist.length;
        loadTrack(currentTrackIndex);
        sound.play();
    }
    $('.next-btn').click(nextTrack);

    // Предыдущий трек
    $('.prev-btn').click(function() {
        if (!playlist.length) return;
        currentTrackIndex = (currentTrackIndex - 1 + playlist.length) % playlist.length;
        loadTrack(currentTrackIndex);
        sound.play();
    });

    // Переключение зацикливания
    $('.loop-btn').click(function() {
        isLooping = !isLooping;
        $(this).toggleClass('active', isLooping);
        if (sound) {
            sound.loop(isLooping && playlist.length === 1);
        }
    });

    // Управление громкостью
    $('.volume-slider').on('input', function() {
        const volume = parseFloat($(this).val());
        if (sound) sound.volume(volume);
        updateVolumeIcon(volume);
    });

    // Перемотка при клике на прогресс-бар
    $('.progress-bar').click(function(e) {
        if (sound && sound.duration()) {
            const rect = this.getBoundingClientRect();
            const clickX = e.clientX - rect.left;
            const width = rect.width;
            const seekTime = (clickX / width) * sound.duration();
            sound.seek(seekTime);
            updateProgress();
        }
    });

    // Инициализация плеера
    if (playlist.length > 0) {
        $('.play-btn .play-icon').addClass('active');
        loadTrack(currentTrackIndex);
        $('.play-btn').prop('disabled', false);
        $('.next-btn, .prev-btn, .loop-btn').prop('disabled', false);
        updateVolumeIcon(1); // Инициализация иконки громкости
    } else {
        $('.track-title').text('Плейлист пуст');
        $('.play-btn, .next-btn, .prev-btn, .loop-btn').prop('disabled', true);
    }
});
