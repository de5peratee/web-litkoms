<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AuthorComics\AuthorComicsController;
use App\Http\Controllers\AuthorComics\AuthorComicsLandingController;
use App\Http\Controllers\AuthorComics\AuthorComicsListController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\Editor\EditorCatalogController;
use App\Http\Controllers\Editor\EditorEventController;
use App\Http\Controllers\Editor\EditorModerationController;
use App\Http\Controllers\Editor\EditorPostController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\NewsFeedController;
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
Route::get('/news', [NewsFeedController::class, 'index'])->name('mediaposts');

// авторский комикс
Route::prefix('authors_comics')->group(function () {
    Route::get('/', [AuthorComicsListController::class, 'library'])->name('authors_comics_library');
    Route::get('/{authorComic:slug}', [AuthorComicsListController::class, 'show'])->name('author_comic');
    Route::post('/{authorComic:slug}/rate', [AuthorComicsListController::class, 'rate'])->name('author_comic.rate')->middleware('authorized');
    Route::post('/{authorComic:slug}/comment', [AuthorComicsListController::class, 'comment'])->name('author_comic.comment')->middleware('authorized');
    Route::get('/{authorComic:slug}/comments', [AuthorComicsListController::class, 'getComments'])->name('author_comic.comments');
    Route::get('/{authorComic:slug}/download', [AuthorComicsListController::class, 'download'])->name('author_comic.download');
});

Route::get('/authors_comics_landing', [AuthorComicsLandingController::class, 'index'])->name('authors_comics_landing');

// Лендинг Litar
Route::get('/litar_landing', function () {
    return view('litar_landing');
})->name('litar_landing');

// Комиксы пользователя
Route::prefix('/profile/comics')
    ->middleware('authorized')
    ->group(function () {
        Route::get('/', [AuthorComicsController::class, 'index'])->name('user.author_comics');

        Route::get('/create', [AuthorComicsController::class, 'create'])->name('user.create_author_comics');
        Route::post('/', [AuthorComicsController::class, 'store'])->name('user.store_author_comics');

        Route::patch('/{comic:id}', [AuthorComicsController::class, 'update'])->name('user.update_author_comics');

        Route::delete('/{comic:id}', [AuthorComicsController::class, 'destroy'])->name('user.delete_author_comics');

        Route::get('/{comic}/moderation', [AuthorComicsController::class, 'showModerationConfirm'])->name('user.moderation-confirm-comics');

        Route::post('/{comic}/publish', [AuthorComicsController::class, 'publish'])->name('user.publish_comic');
    });

// Подписки
Route::post('/subscribe/{nickname}', [SubscriptionController::class, 'subscribe'])->name('subscribe');
Route::post('/unsubscribe/{nickname}', [SubscriptionController::class, 'unsubscribe'])->name('unsubscribe');

// Панель редактора
Route::prefix('dashboard')->middleware('editor')->group(function () {
    Route::view('/', 'editor.dashboard')->name('editor.dashboard');


    Route::get('/comics_submissions', [EditorModerationController::class, 'index'])->name('editor.comics_submissions_index');
    Route::get('/editor/moderation/{slug}', [EditorModerationController::class, 'show'])->name('editor.comic_moderation');
    Route::put('/editor/moderation/{slug}', [EditorModerationController::class, 'update'])->name('editor.comic_moderation');

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

// Профиль пользователя
Route::get('/{nickname}', [ProfileController::class, 'index'])->name('profile.index');
