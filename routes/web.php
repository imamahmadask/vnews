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

Route::get('/sitemap.xml', [\App\Http\Controllers\SitemapController::class, 'index'])->name('sitemap');

// Helper route for Shared Hosting to link storage
Route::get('/setup-storage-link', function () {
    \Illuminate\Support\Facades\Artisan::call('storage:link');
    return 'Storage link has been created successfully! You can now safely remove this route.';
});
