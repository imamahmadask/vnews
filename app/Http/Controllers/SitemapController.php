<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;

class SitemapController extends Controller
{
    public function index()
    {
        $posts = Post::where('status', 'Published')->orderBy('created_at', 'desc')->get();
        $categories = Category::all();

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        // Homepage
        $xml .= "  <url>\n";
        $xml .= '    <loc>' . url('/') . "</loc>\n";
        $xml .= '    <lastmod>' . now()->tz('UTC')->toAtomString() . "</lastmod>\n";
        $xml .= "    <changefreq>daily</changefreq>\n";
        $xml .= "    <priority>1.0</priority>\n";
        $xml .= "  </url>\n";

        // About page
        $xml .= "  <url>\n";
        $xml .= '    <loc>' . url('/about') . "</loc>\n";
        $xml .= '    <lastmod>' . now()->tz('UTC')->toAtomString() . "</lastmod>\n";
        $xml .= "    <changefreq>monthly</changefreq>\n";
        $xml .= "    <priority>0.5</priority>\n";
        $xml .= "  </url>\n";

        // Categories
        foreach ($categories as $category) {
            $xml .= "  <url>\n";
            $xml .= '    <loc>' . route('category.show', $category->slug) . "</loc>\n";
            $xml .= '    <lastmod>' . ($category->updated_at ?? now())->tz('UTC')->toAtomString() . "</lastmod>\n";
            $xml .= "    <changefreq>weekly</changefreq>\n";
            $xml .= "    <priority>0.8</priority>\n";
            $xml .= "  </url>\n";
        }

        // Posts
        foreach ($posts as $post) {
            $xml .= "  <url>\n";
            $xml .= '    <loc>' . route('posts.show', $post->slug) . "</loc>\n";
            $xml .= '    <lastmod>' . ($post->updated_at ?? now())->tz('UTC')->toAtomString() . "</lastmod>\n";
            $xml .= "    <changefreq>daily</changefreq>\n";
            $xml .= "    <priority>0.9</priority>\n";
            $xml .= "  </url>\n";
        }

        $xml .= '</urlset>';

        return response($xml, 200)->header('Content-Type', 'text/xml; charset=UTF-8');
    }
}
