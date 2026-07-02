<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::all();

        $publishedBase = fn() => Post::with(['user', 'category'])
            ->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());

        $allPosts = $publishedBase()->latest('published_at')->get();

        // Hero section: up to 6 featured posts, fallback to top viewed
        $heroPosts = $allPosts->where('is_featured', true)->take(6);
        if ($heroPosts->count() < 6) {
            $needed = 6 - $heroPosts->count();
            $additionalPosts = $publishedBase()
                ->whereNotIn('id', $heroPosts->pluck('id'))
                ->orderByDesc('views_count')
                ->take($needed)
                ->get();
            $heroPosts = $heroPosts->concat($additionalPosts);
        }

        // Top posts by views_count
        $topPosts = $publishedBase()->orderByDesc('views_count')->take(6)->get();

        // Other posts: next 6 after excluding hero posts
        $otherPosts = $allPosts->whereNotIn('id', $heroPosts->pluck('id'))->take(6);

        $categorySections = [];

        foreach ($categories as $cat) {
            // Fetch top 4 latest posts for each category
            $posts = $publishedBase()->where('category_id', $cat->id)->latest('published_at')->take(4)->get();
            if ($posts->isNotEmpty()) {
                $categorySections[] = (object)[
                    'category' => $cat,
                    'posts' => $posts
                ];
            }
        }

        return view('welcome', compact(
            'categories', 'heroPosts', 'topPosts', 'otherPosts',
            'categorySections'
        ));
    }
}
