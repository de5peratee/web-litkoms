@extends('layouts.app')

@section('title', 'Лит-AR')

@section('content')
    @vite(['resources/css/litar.css'])

    <div class="litar-container">
        <div class="hero-block fade-in">
            <div class="hero-content">
                <span class="subtitle">
                    <img src="/images/litar/scan.svg" alt="AR icon" class="icon-ar" loading="lazy"/>
                    Приложение дополненной реальности
                </span>

                <h1>
                    Лит–AR приложение доступно
                    <span class="highlight">прямо сейчас!</span>
                </h1>
                <p>Попробуйте погрузиться и узнать больше о библиотеке с помощью нашего приложения.</p>
                <a href="https://disk.yandex.ru/d/-n7EoWQshMwN2g" class="primary-btn">
                    Скачать установщик
                    <img src="/images/litar/download.svg" alt="download-icon" loading="lazy"/>
                </a>
                <p class="platform">платформа Android</p>
            </div>
            <div class="hero-image">
                <picture>
                    <img src="/images/litar/devices.png" alt="Лит-AR приложение" loading="lazy"/>
                </picture>
            </div>
        </div>

        <div class="qr-block zoom-in">
            <h3 class="qr-title">Скачайте приложение по <span class="marker">QR-коду</span></h3>
            <p class="text-medium">Наведите телефон на код</p>
            <div class="cta-qr-wrapper">
                <div class="qr-info">
                    <div class="qr-info-text">
                        <p class="text-big">Для Android</p>
                        <p class="text-small version-text">Android 6.0+</p>
                    </div>
                    <a href="https://disk.yandex.ru/d/-n7EoWQshMwN2g" class="primary-btn">Скачать установщик</a>
                </div>
                <img src="{{ asset('images/lit-ar_qr.svg') }}" alt="QR code" class="qr-code" loading="lazy">
            </div>
        </div>

        <section class="characters fade-in">
            <h2>Персонажи <span class="count">4</span></h2>
            <div class="character-list">
                @foreach ([
                    ['img' => 'musician.png', 'title' => 'Музыкант'],
                    ['img' => 'chemist.png', 'title' => 'Химик'],
                    ['img' => 'artist.png', 'title' => 'Художник'],
                    ['img' => 'writer.png', 'title' => 'Писатель'],
                ] as $character)
                    <div class="character-card">
                        <img src="{{ asset('images/litar/characters/' . $character['img']) }}"
                             alt="{{ $character['title'] }}" loading="lazy">
                        <h3>{{ $character['title'] }}</h3>
                        <p class="description">Помощник, который добавляет юмора и решает забавные задачи</p>
                    </div>
                @endforeach
            </div>
        </section>
        <img src="{{ asset('images/litar/scan2.svg') }}" alt="Scan2" loading="lazy" class="scan-icon zoom-in">

        <section class="marks fade-in">
            <p>Используйте
                <span class="marker_blue">метки</span>
                для сканирования,
                <span class="marker_grey">разбросанные, но не хаотично,</span>
                по библиотеке, чтобы «призвать» персонажа на ваше мобильное устройство.</p>
        </section>

        <section class="frog-section zoom-in">
            <div class="frog-content">
                <img src="{{ asset('images/litar/frog.svg') }}" alt="Лягушонок Фрогги" class="frog-img" loading="lazy">
                <div class="frog-desc">
                    <div class="frog-text">
                        <h3>Лягушонок «Фрогги»</h3>
                        <p class="sub">Главный герой игры, маскот</p>
                        <span class="subtitle_frog">
                            Исследуйте библиотеку с главным из персонажей — лягушонком Фрогги.
                        </span>

                    </div>
                </div>
            </div>
        </section>

        <section class="team fade-in">

            <span class="froggers">FROGGERS</span>
            <h2>Команда разработчиков</h2>
            <div class="team-members">
                <div class="member">
                    <img src="images/litar/razrabi.jpg" alt="Ларин А.А." class="member-photo" />
                    <div class="member-info">
                        <p><strong>Ларин А.А.</strong></p>
                        <span>PM</span>
                    </div>
                </div>
                <div class="member">
                    <img src="images/litar/razrabi.jpg" alt="Мельничук В.В." class="member-photo" />
                    <div class="member-info">
                        <p><strong>Мельничук В.В.</strong></p>
                        <span>UX/UI дизайнер</span>
                    </div>
                </div>
                <div class="member">
                    <img src="images/litar/razrabi.jpg" alt="Польбицев П.П." class="member-photo" />
                    <div class="member-info">
                        <p><strong>Польбицев П.П.</strong></p>
                        <span>Backend разработчик</span>
                    </div>
                </div>
                <div class="member">
                    <img src="images/litar/razrabi.jpg" alt="Пасечник В.В." class="member-photo" />
                    <div class="member-info">
                        <p><strong>Пасечник В.В.</strong></p>
                        <span>3D дизайнер</span>
                    </div>
                </div>
            </div>

            <img src="{{ asset('images/litar/teamwork.svg') }}" alt="Teamwork" loading="lazy" class="scan-icon zoom-in">
            <p class="team-note">Исследуйте мир природы, общайтесь и развлекайтесь с главным героем — лягушонком Фрогги.</p>
        </section>
    </div>
@endsection