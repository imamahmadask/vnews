<?php

use Illuminate\Support\Facades\Route;
use App\Models\Post;
use App\Models\Category;

Route::get('/', function () {
    $categories = Category::all();
    $allPosts = Post::with(['user', 'category'])
        ->where('status', 'published')
        ->whereNotNull('published_at')
        ->where('published_at', '<=', now())
        ->latest('published_at')
        ->get();

    // Hero section: first 6 posts for carousel (3 slides × 2 posts)
    $heroPosts = $allPosts->take(6);
    
    // Top posts by views_count
    $topPosts = Post::with(['user', 'category'])
        ->where('status', 'published')
        ->whereNotNull('published_at')
        ->where('published_at', '<=', now())
        ->orderByDesc('views_count')
        ->take(5)
        ->get();
        
    $otherPosts = $allPosts->slice(6);

    return view('welcome', compact('categories', 'heroPosts', 'topPosts', 'otherPosts'));
})->name('home');

Route::view('/about', 'about')->name('about');

Route::get('/category/{slug}', function ($slug) {
    $category = Category::where('slug', $slug)->firstOrFail();
    $categories = Category::all();
    
    $posts = Post::with(['user', 'category'])
        ->where('category_id', $category->id)
        ->where('status', 'published')
        ->whereNotNull('published_at')
        ->where('published_at', '<=', now())
        ->latest('published_at')
        ->paginate(12);
        
    return view('category', compact('category', 'categories', 'posts'));
})->name('category.show');

Route::get('/search', function () {
    $query = request('q');
    $categories = Category::all();
    
    $posts = Post::with(['user', 'category'])
        ->where('status', 'published')
        ->whereNotNull('published_at')
        ->where('published_at', '<=', now())
        ->where(function($q) use ($query) {
            $q->where('title', 'like', "%{$query}%")
              ->orWhere('content', 'like', "%{$query}%");
        })
        ->latest('published_at')
        ->get();
        
    return view('search', compact('posts', 'query', 'categories'));
})->name('search');

Route::get('/post/{slug}', function ($slug) {
    $post = Post::with(['user', 'category', 'tags'])
        ->where('slug', $slug)
        ->where('status', 'published')
        ->whereNotNull('published_at')
        ->where('published_at', '<=', now())
        ->firstOrFail();
        
    // Increment views counter
    $post->increment('views_count');
        
    // Related Posts based on Tags first, fallback to Category
    $tagIds = $post->tags->pluck('id');
    $relatedPosts = collect();
    
    if ($tagIds->count() > 0) {
        $relatedPosts = Post::whereHas('tags', function($q) use ($tagIds) {
            $q->whereIn('tags.id', $tagIds);
        })
        ->where('id', '!=', $post->id)
        ->where('status', 'published')
        ->whereNotNull('published_at')
        ->where('published_at', '<=', now())
        ->latest('published_at')
        ->take(3)
        ->get();
    }
    
    // If not enough related posts from tags, get from category
    if ($relatedPosts->count() < 3) {
        $limit = 3 - $relatedPosts->count();
        $excludeIds = $relatedPosts->pluck('id')->push($post->id);
        
        $categoryPosts = Post::where('category_id', $post->category_id)
            ->whereNotIn('id', $excludeIds)
            ->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->latest('published_at')
            ->take($limit)
            ->get();
            
        $relatedPosts = $relatedPosts->merge($categoryPosts);
    }
        
    return view('posts.show', compact('post', 'relatedPosts'));
})->name('posts.show');
