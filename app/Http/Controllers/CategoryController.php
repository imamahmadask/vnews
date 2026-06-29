<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;

class CategoryController extends Controller
{
    public function show($slug)
    {
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
    }
}
