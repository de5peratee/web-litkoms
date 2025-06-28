import $ from 'jquery';

$(document).ready(function() {
    $('.audio-player, #floating-blob').addClass('no-transition'); // Отключаем анимации при загрузке

    const playlist = [
        { title: "Интернет-радио", src: "/audio/litcoms-radio.mp3" },
        { title: "Интернет-радио", src: "/audio/litcoms-radio.mp3" }
    ];

    let currentTrackIndex = 0;
    let sound = null;
    let isCollapsed = true; // Изначально свёрнут

    function preloadNextTrack() {
        if (playlist.length > 1) {
            const nextIndex = (currentTrackIndex + 1) % playlist.length;
            const nextSound = new Howl({
                src: [playlist[nextIndex].src],
                html5: true,
                preload: true,
                volume: 0
            });
            nextSound.once('load', () => {
                console.log('Next track preloaded:', playlist[nextIndex].title);
            });
            nextSound.once('loaderror', (id, error) => {
                console.error('Preload error for', playlist[nextIndex].title, error);
            });
        }
    }

    function restorePlayerState() {
        const savedTrackIndex = localStorage.getItem('playerTrackIndex');
        const savedVolume = localStorage.getItem('playerVolume');
        const savedPlaying = localStorage.getItem('playerPlaying') === 'true';
        const savedSeek = localStorage.getItem('playerSeek');
        const savedCollapsed = localStorage.getItem('playerCollapsed');

        if (savedTrackIndex !== null) {
            currentTrackIndex = parseInt(savedTrackIndex);
        }
        const volume = savedVolume !== null ? parseFloat(savedVolume) : 1.0;
        $('.volume-slider').val(volume);
        updateVolumeIcon(volume);

        // Устанавливаем isCollapsed по умолчанию true
        isCollapsed = savedCollapsed !== null ? savedCollapsed === 'true' : true;

        if (isCollapsed) {
            $('.audio-player').removeClass('expanded').addClass('collapsed');
            $('#floating-blob').removeClass('hidden');
            if (savedPlaying && $('.expand-icon').length) {
                $('.expand-icon').addClass('playing');
            }
        } else {
            $('.audio-player').removeClass('collapsed').addClass('expanded');
            $('#floating-blob').addClass('hidden');
            if ($('.expand-icon').length) {
                $('.expand-icon').removeClass('playing');
            }
        }

        // Включаем анимации после восстановления
        setTimeout(() => {
            $('.audio-player, #floating-blob').removeClass('no-transition');
        }, 0);

        return { isPlaying: savedPlaying, seek: savedSeek ? parseFloat(savedSeek) : 0 };
    }

    function savePlayerState() {
        localStorage.setItem('playerTrackIndex', currentTrackIndex);
        localStorage.setItem('playerVolume', $('.volume-slider').val());
        localStorage.setItem('playerPlaying', sound && sound.playing() ? 'true' : 'false');
        localStorage.setItem('playerSeek', sound ? sound.seek() : 0);
        localStorage.setItem('playerCollapsed', isCollapsed);
    }

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

    function loadTrack(index, autoPlay = false) {
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
            const savedVolume = parseFloat(localStorage.getItem('playerVolume')) || 1.0;
            sound = new Howl({
                src: [playlist[index].src],
                html5: true,
                loop: playlist.length === 1,
                pool: 1,
                volume: savedVolume,
                onload: function() {
                    updateTrackInfo();
                    $('.play-btn').prop('disabled', false);
                    const state = restorePlayerState();
                    if (state.isPlaying && !isCollapsed && autoPlay) {
                        sound.seek(state.seek);
                        sound.play();
                    }
                    $('.volume-slider').val(savedVolume);
                    updateVolumeIcon(savedVolume);
                    preloadNextTrack();
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
                    if (isCollapsed && $('.expand-icon').length) {
                        $('.expand-icon').addClass('playing');
                    }
                    savePlayerState();
                },
                onpause: function() {
                    $('.play-btn .pause-icon').removeClass('active');
                    $('.play-btn .play-icon').addClass('active');
                    if ($('.expand-icon').length) {
                        $('.expand-icon').removeClass('playing');
                    }
                    savePlayerState();
                },
                onend: function() {
                    if (playlist.length > 1) {
                        currentTrackIndex = (currentTrackIndex + 1) % playlist.length;
                        loadTrack(currentTrackIndex, true);
                    }
                }
            });
        } catch (error) {
            $('.track-title').text('Ошибка инициализации плеера');
            console.error('Ошибка Howler.js:', error);
        }
    }

    function updateTrackInfo() {
        if (!playlist.length) return;
        $('.track-title').text(playlist[currentTrackIndex].title);
        const duration = sound && sound.duration() ? formatTime(sound.duration()) : '0:00';
        $('.time').text('0:00 / ' + duration);
    }

    function formatTime(seconds) {
        if (!seconds || isNaN(seconds)) return '0:00';
        const min = Math.floor(seconds / 60);
        const sec = Math.floor(seconds % 60);
        return min + ':' + (sec < 10 ? '0' : '') + sec;
    }

    function updateProgress() {
        if (sound && sound.playing()) {
            const seek = sound.seek() || 0;
            const duration = sound.duration() || 1;
            const progress = (seek / duration) * 100;
            $('.progress').css('width', progress + '%');
            $('.time').text(formatTime(seek) + ' / ' + formatTime(duration));
            savePlayerState();
            requestAnimationFrame(updateProgress);
        }
    }

    $('.play-btn').click(function() {
        if (!sound || !playlist.length) {
            loadTrack(currentTrackIndex, true);
        } else if (sound.playing()) {
            sound.pause();
        } else {
            sound.play();
        }
    });

    $('.volume-slider').on('input', function() {
        const volume = parseFloat($(this).val());
        if (sound) {
            sound.volume(volume);
        }
        updateVolumeIcon(volume);
        savePlayerState();
    });

    $('.collapse-btn').click(function() {
        isCollapsed = !isCollapsed;
        if (isCollapsed) {
            $('.audio-player').removeClass('expanded').addClass('collapsed');
            $('#floating-blob').removeClass('hidden');
            if (sound && sound.playing() && $('.expand-icon').length) {
                $('.expand-icon').addClass('playing');
            }
        } else {
            $('.audio-player').removeClass('collapsed').addClass('expanded');
            $('#floating-blob').addClass('hidden');
            if ($('.expand-icon').length) {
                $('.expand-icon').removeClass('playing');
            }
        }
        savePlayerState();
    });

    $('#floating-blob').click(function() {
        isCollapsed = !isCollapsed;
        if (isCollapsed) {
            $('.audio-player').removeClass('expanded').addClass('collapsed');
            $('#floating-blob').removeClass('hidden');
        } else {
            $('.audio-player').removeClass('collapsed').addClass('expanded');
            $('#floating-blob').addClass('hidden');
        }
        savePlayerState();
    });

    // Initialize player
    if (playlist.length > 0) {
        restorePlayerState(); // Восстанавливаем состояние первым
        loadTrack(currentTrackIndex, true);
        $('.play-btn').prop('disabled', false);
        const initialVolume = parseFloat(localStorage.getItem('playerVolume')) || 1.0;
        $('.volume-slider').val(initialVolume);
        updateVolumeIcon(initialVolume);
        preloadNextTrack();
    } else {
        $('.track-title').text('Плейлист пуст');
        $('.play-btn').prop('disabled', true);
    }
});
