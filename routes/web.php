<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CatalogController;

use App\Http\Controllers\Editor\EditorEventController;
use App\Http\Controllers\Editor\EditorPostController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SubscriptionController;
use Illuminate\Support\Facades\Route;

//Главная
Route::get('/', function () {
    return view('home');
})->name('home');


Route::get('/library', [CatalogController::class, 'index'])
    ->name('library.index');
Route::get('/library/{id}', [CatalogController::class, 'get_book'])
    ->name('library.get_book');


Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/events/{event}', [EventController::class, 'get_event'])->name('events.get_event');


//Аутентификация
Route::get('/auth', [AuthController::class, 'index'])->name('auth')->middleware('guest');;
Route::post('/auth/login', [AuthController::class, 'login'])->name('auth.login');
Route::post('/auth/register', [AuthController::class, 'register'])->name('auth.register');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


Route::get('/mediaposts', function () {
    return view('news');
})->name('mediaposts');

Route::get('/authors_comics_landing', function () {
    return view('authors_comics_landing');
})->name('authors_comics_landing');

Route::get('/litar_landing', function () {
    return view('litar_landing');
})->name('litar_landing');


Route::get('/profile/{nickname}', [ProfileController::class, 'index'])->name('profile.index');

Route::prefix('dashboard')->middleware('editor')->group(function () {
    Route::view('/', 'editor.dashboard')->name('editor.dashboard');

    Route::get('/event', [EditorEventController::class, 'index'])->name('editor.events_index');
    Route::get('/event/create', [EditorEventController::class, 'create'])->name('editor.create_event');
    Route::post('/event/store', [EditorEventController::class, 'store'])->name('editor.store_event');

    Route::get('/mediapost', [EditorPostController::class, 'index'])->name('editor.mediapost_index');
    Route::get('/mediapost/create', [EditorPostController::class, 'create'])->name('editor.create_mediapost');
    Route::post('/mediapost/store', [EditorPostController::class, 'store'])->name('editor.store_mediapost');
});



Route::post('/subscribe/{nickname}', [SubscriptionController::class, 'subscribe'])->name('subscribe');
Route::post('/unsubscribe/{nickname}', [SubscriptionController::class, 'unsubscribe'])->name('unsubscribe');
