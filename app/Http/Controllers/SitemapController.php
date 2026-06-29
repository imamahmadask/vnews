<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;

class SitemapController extends Controller
{
    public function index()
    {
        $posts = Post::where('status', 'Published')->orderBy('created_at', 'desc')->get();
        $categories = Category::all();

        return response()->view('sitemap', [
            'posts' => $posts,
            'categories' => $categories
        ])->header('Content-Type', 'text/xml');
    }
}
