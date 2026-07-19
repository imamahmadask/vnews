<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;

class SitemapController extends Controller
{
    public function index()
    {
        try {
            $posts = Post::where('status', 'Published')->orderBy('created_at', 'desc')->get();
            $categories = Category::all();

            return response()->view('sitemap', [
                'posts' => $posts,
                'categories' => $categories
            ])->header('Content-Type', 'text/xml');
        } catch (\Throwable $e) {
            // Tampilkan error ke layar (Hanya untuk debugging sementara)
            return response($e->getMessage() . ' in ' . $e->getFile() . ' line ' . $e->getLine(), 500)
                ->header('Content-Type', 'text/plain');
        }
    }
}
