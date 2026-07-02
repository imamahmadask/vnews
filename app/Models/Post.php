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
        'image_360',
        'image_360_caption',
        'image_captions',
        'images_with_captions',
        'user_id',
        'category_id',
        'status',
        'is_featured',
        'views_count',
        'published_at',
    ];

    protected $casts = [
        'image' => 'array',
        'image_captions' => 'array',
        'published_at' => 'datetime',
        'is_featured' => 'boolean',
    ];

    protected $appends = [
        'images_with_captions',
    ];

    public function getImagesWithCaptionsAttribute()
    {
        $images = $this->image ?? [];
        $captions = $this->image_captions ?? [];
        
        $result = [];
        foreach ($images as $path) {
            $result[] = [
                'path' => $path,
                'caption' => $captions[$path] ?? '',
            ];
        }
        return $result;
    }

    public function setImagesWithCaptionsAttribute($value)
    {
        $images = [];
        $captions = [];
        
        if (is_array($value)) {
            foreach ($value as $item) {
                $path = $item['path'] ?? null;
                if ($path) {
                    if (is_array($path)) {
                        $path = reset($path);
                    }
                    if (is_string($path)) {
                        $images[] = $path;
                        $captions[$path] = $item['caption'] ?? '';
                    }
                }
            }
        }
        
        $this->image = $images;
        $this->image_captions = $captions;
    }

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

            // Hapus gambar 360
            if ($post->image_360) {
                if (\Illuminate\Support\Facades\Storage::disk('public')->exists($post->image_360)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($post->image_360);
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
