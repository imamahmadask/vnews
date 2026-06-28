<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'content',
        'image',
        'user_id',
        'category_id',
        'status',
        'views_count',
        'published_at',
    ];

    protected $casts = [
        'image' => 'array',
        'published_at' => 'datetime',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($post) {
            // Hapus gambar utama (hero/thumbnail)
            if ($post->image) {
                $images = is_array($post->image) ? $post->image : [$post->image];
                foreach ($images as $image) {
                    if (\Illuminate\Support\Facades\Storage::disk('public')->exists($image)) {
                        \Illuminate\Support\Facades\Storage::disk('public')->delete($image);
                    }
                }
            }

            // Hapus gambar-gambar yang disisipkan di dalam teks artikel (Rich Editor)
            if ($post->content) {
                preg_match_all('/src="([^"]+)"/', $post->content, $matches);
                if (!empty($matches[1])) {
                    foreach ($matches[1] as $url) {
                        if (str_contains($url, '/storage/news-images/')) {
                            $pathParts = explode('/storage/', $url);
                            if (count($pathParts) > 1) {
                                $relativePath = $pathParts[1];
                                if (\Illuminate\Support\Facades\Storage::disk('public')->exists($relativePath)) {
                                    \Illuminate\Support\Facades\Storage::disk('public')->delete($relativePath);
                                }
                            }
                        }
                    }
                }
            }
        });
    }
}
