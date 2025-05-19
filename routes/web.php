<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AuthorComicsController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\Editor\EditorCatalogController;
use App\Http\Controllers\Editor\EditorEventController;
use App\Http\Controllers\Editor\EditorPostController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SubscriptionController;
use Illuminate\Support\Facades\Route;

// Главная страница
Route::get('/', function () {
    return view('home');
})->name('home');

// Библиотека
Route::get('/library', [CatalogController::class, 'index'])->name('library.index');
Route::get('/library/{id}', [CatalogController::class, 'get_book'])->name('library.get_book');

// События
Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/events/{event}', [EventController::class, 'get_event'])->name('events.get_event');

// Аутентификация
Route::middleware('guest')->group(function () {
    Route::get('/auth', [AuthController::class, 'index'])->name('auth');
    Route::post('/auth/login', [AuthController::class, 'login'])->name('auth.login');
    Route::post('/auth/register', [AuthController::class, 'register'])->name('auth.register');
});
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Медиапосты
Route::get('/mediaposts', function () {
    return view('news');
})->name('mediaposts');

// авторский комикс
Route::get('/authors_comics', function () {
    return view('authors_comics.landing');
})->name('authors_comics_landing');

Route::get('/authors_comics/library', function () {
    return view('authors_comics.library');
})->name('authors_comics_library');

Route::get('/authors_comics/author_comics', function () {
    return view('authors_comics.comic');
})->name('author_comic');

// Лендинг Litar
Route::get('/litar_landing', function () {
    return view('litar_landing');
})->name('litar_landing');

// Профиль пользователя
Route::get('/{nickname}', [ProfileController::class, 'index'])->name('profile.index');

// Комиксы пользователя
Route::prefix('/profile/comics')
    ->middleware('authorized')
    ->group(function () {

        Route::get('/', [AuthorComicsController::class, 'index'])->name('user.author_comics');

        Route::get('/create', [AuthorComicsController::class, 'create'])->name('user.create_author_comics');
        Route::post('/', [AuthorComicsController::class, 'store'])->name('user.store_author_comics');

        Route::get('/{comic}/edit', [AuthorComicsController::class, 'edit'])->name('user.edit_author_comics');
        Route::patch('/{comic}', [AuthorComicsController::class, 'update'])->name('user.update_author_comics');

        Route::delete('/{comic}', [AuthorComicsController::class, 'destroy'])->name('user.delete_author_comics');

        Route::get('/{comic}/moderation', [AuthorComicsController::class, 'showModerationConfirm'])->name('user.moderation-confirm-comics');

        Route::post('/{comic}/publish', [AuthorComicsController::class, 'publish'])->name('user.publish_comic');
    });

// Подписки
Route::post('/subscribe/{nickname}', [SubscriptionController::class, 'subscribe'])->name('subscribe');
Route::post('/unsubscribe/{nickname}', [SubscriptionController::class, 'unsubscribe'])->name('unsubscribe');

// Панель редактора
Route::prefix('dashboard')->middleware('editor')->group(function () {
    Route::view('/', 'editor.dashboard')->name('editor.dashboard');

    // События
    Route::get('/events', [EditorEventController::class, 'index'])->name('editor.events_index');
    Route::get('/events/create', [EditorEventController::class, 'create'])->name('editor.create_event');
    Route::post('/events/store', [EditorEventController::class, 'store'])->name('editor.store_event');
    Route::patch('/events/{event}', [EditorEventController::class, 'update'])->name('editor.update_event');
    Route::delete('/events/{event}', [EditorEventController::class, 'destroy'])->name('editor.delete_event');

    // Медиапосты
    Route::get('/mediaposts', [EditorPostController::class, 'index'])->name('editor.mediapost_index');
    Route::get('/mediaposts/create', [EditorPostController::class, 'create'])->name('editor.create_mediapost');
    Route::post('/mediaposts/store', [EditorPostController::class, 'store'])->name('editor.store_mediapost');
    Route::patch('/mediaposts/{mediaPost}', [EditorPostController::class, 'update'])->name('editor.update_mediapost');
    Route::delete('/mediaposts/{mediaPost}', [EditorPostController::class, 'destroy'])->name('editor.delete_mediapost');

    // Каталоги
    Route::get('/catalogs', [EditorCatalogController::class, 'index'])->name('editor.catalogs_index');
    Route::get('/catalogs/create', [EditorCatalogController::class, 'create'])->name('editor.create_catalog');
    Route::post('/catalogs/store', [EditorCatalogController::class, 'store'])->name('editor.store_catalog');
    Route::patch('/catalogs/{catalog}', [EditorCatalogController::class, 'update'])->name('editor.update_catalog');
    Route::delete('/catalogs/{catalog}', [EditorCatalogController::class, 'destroy'])->name('editor.delete_catalog');
});

// Политики и руководства
Route::get('/manuals/policy', function () {
    return view('manuals.policy');
})->name('manuals.policy');