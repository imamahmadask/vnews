<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PostController;

Route::get('/', [HomeController::class, 'index'])->name('home');

use App\Models\StaticPage;

Route::get('/about', function () {
    $page = StaticPage::where('slug', 'about')->firstOrFail();
    return view('about', compact('page'));
})->name('about');

Route::get('/page/{slug}', function ($slug) {
    if ($slug === 'about') {
        return redirect()->route('about');
    }
    $page = StaticPage::where('slug', $slug)->firstOrFail();
    return view('about', compact('page'));
})->name('static-page.show');

Route::get('/category/{slug}', [CategoryController::class, 'show'])->name('category.show');

Route::get('/search', [PostController::class, 'search'])->name('search');

Route::get('/post/{slug}', [PostController::class, 'show'])->name('posts.show');

Route::get('/posts/embed-360', function (\Illuminate\Http\Request $request) {
    $path = $request->query('path');
    if (!$path || !\Illuminate\Support\Facades\Storage::disk('public')->exists($path)) {
        abort(404);
    }
    return view('posts.embed_360', ['url' => \Illuminate\Support\Facades\Storage::url($path)]);
})->name('posts.embed-360');

Route::get('/sitemap.xml', [\App\Http\Controllers\SitemapController::class, 'index'])->name('sitemap');

// Helper route for Shared Hosting to link storage
Route::get('/setup-storage-link', function () {
    \Illuminate\Support\Facades\Artisan::call('storage:link');
    return 'Storage link has been created successfully! You can now safely remove this route.';
});
