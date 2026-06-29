<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PostController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::view('/about', 'about')->name('about');

Route::get('/category/{slug}', [CategoryController::class, 'show'])->name('category.show');

Route::get('/search', [PostController::class, 'search'])->name('search');

Route::get('/post/{slug}', [PostController::class, 'show'])->name('posts.show');
