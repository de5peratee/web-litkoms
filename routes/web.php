<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CatalogController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/library', function () {
    return view('library');
})->name('library');

Route::get('/news', function () {
    return view('news');
})->name('news');

Route::get('/events', function () {
    return view('events');
})->name('events');

Route::get('/authors_comics_landing', function () {
    return view('authors_comics_landing');
})->name('authors_comics_landing');

Route::get('/litar_landing', function () {
    return view('litar_landing');
})->name('litar_landing');

//Route::get('/auth', function () {
//    return view('auth');
//})->name('auth');



Route::get('/auth', [RegisterController::class, 'index'])
    ->name('auth.index')
    ->middleware('guest');

Route::post('/auth/register', [RegisterController::class, 'store'])->name('register.store');

Route::post('/auth/login', [LoginController::class, 'loginUser'])->name('login');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/profile', function () {
    return view('user.profile');
})->name('profile');

Route::get('/catalog', [CatalogController::class, 'index'])->name('catalog.index');
Route::get('/catalog/{id}', [CatalogController::class, 'show'])->name('catalog.show');