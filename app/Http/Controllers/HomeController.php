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

        // Other posts: next 8 after excluding hero posts
        $otherPosts = $allPosts->whereNotIn('id', $heroPosts->pluck('id'))->take(8);

        // Politics: top 8 by views
        $politicsCategory = Category::where('slug', 'politik')->first();
        $politicsPosts = $politicsCategory
            ? $publishedBase()->where('category_id', $politicsCategory->id)->orderByDesc('views_count')->take(8)->get()
            : collect();

        // Sports: top 8 by views
        $sportsCategory = Category::where('slug', 'olahraga')->first();
        $sportsPosts = $sportsCategory
            ? $publishedBase()->where('category_id', $sportsCategory->id)->orderByDesc('views_count')->take(8)->get()
            : collect();

        // Entertainment: top 8 by views
        $entertainmentCategory = Category::where('slug', 'hiburan')->first();
        $entertainmentPosts = $entertainmentCategory
            ? $publishedBase()->where('category_id', $entertainmentCategory->id)->orderByDesc('views_count')->take(8)->get()
            : collect();

        return view('welcome', compact(
            'categories', 'heroPosts', 'topPosts', 'otherPosts',
            'politicsPosts', 'politicsCategory',
            'sportsPosts', 'sportsCategory',
            'entertainmentPosts', 'entertainmentCategory'
        ));
    }
}
