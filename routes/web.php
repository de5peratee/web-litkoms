<?php

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

Route::get('/auth', function () {
    return view('auth');
})->name('auth');

Route::post('/auth/login', function () {
    return view('auth');
})->name('login');


Route::post('/auth/register', function () {
    return view('auth');
})->name('register');

Route::get('/profile', function () {
    return view('user.profile');
})->name('profile');
