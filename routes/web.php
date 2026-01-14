<?php

Route::get('/', function () {
    return redirect()->route('login');
});

// Auth::routes();

Route::get('/login', 'Auth\LoginController@showLoginForm')->name('login');

// Proses login
Route::post('/login', 'Auth\LoginController@login');

// Logout
Route::post('/logout', 'Auth\LoginController@logout')->name('logout');

Route::get('/dashboard', 'DashboardController@index')->middleware('auth')->name('dashboard');

Route::resource('categories', CategoriesController::class)->middleware('auth');
Route::resource('articles', ArticleController::class)->middleware('auth');


