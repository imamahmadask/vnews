@extends('layouts.public')

@section('title', $post->title . ' - vnews.id')

@section('nav-back')
<span class="mr-2 text-xl">&larr;</span> 
@endsection

@section('meta_tags')
<meta name="description" content="{{ Str::limit(strip_tags($post->content), 150) }}">
<meta property="og:title" content="{{ $post->title }}">
<meta property="og:description" content="{{ Str::limit(strip_tags($post->content), 150) }}">
<meta property="og:url" content="{{ request()->fullUrl() }}">
<meta property="og:type" content="article">
@php
    $ogImage = null;
    if ($post->image) {
        $ogImages = is_array($post->image) ? $post->image : [$post->image];
        if (count($ogImages) > 0) {
            $ogImage = url(Storage::url($ogImages[0]));
        }
    }
@endphp
@if($ogImage)
<meta property="og:image" content="{{ $ogImage }}">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:image" content="{{ $ogImage }}">
@endif
<meta name="twitter:title" content="{{ $post->title }}">
<meta name="keywords" content="{{ $post->tags->pluck('name')->implode(', ') }}">
@endsection

@section('extra_css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<style>
    .prose img { border-radius: 0.75rem; margin-top: 2rem; margin-bottom: 2rem; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); }
    /* Custom swiper arrows */
    .swiper-button-next, .swiper-button-prev { color: #1f2937; background: rgba(255, 255, 255, 0.8); border-radius: 9999px; width: 48px; height: 48px; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); backdrop-filter: blur(4px); transition: all 0.3s; opacity: 1; }
    @media (min-width: 768px) {
        .swiper-button-next, .swiper-button-prev { opacity: 0; }
    }
    .group:hover .swiper-button-next, .group:hover .swiper-button-prev { opacity: 1; }
    .swiper-button-next:hover, .swiper-button-prev:hover { background: #ffffff; }
    .swiper-button-next:after, .swiper-button-prev:after { font-size: 1.25rem; font-weight: bold; }
</style>
@endsection

@section('content')
<main class="flex-grow w-full pb-20">
    <!-- Hero Section -->
    <div class="max-w-4xl mx-auto px-4 pt-12 pb-10">
        @if($post->category)
            <div class="flex flex-wrap items-center gap-3 mb-6">
                <a href="{{ route('category.show', $post->category->slug) }}" class="text-sm font-bold tracking-widest text-rose-600 uppercase bg-rose-50 hover:bg-rose-100 transition-colors px-3 py-1 rounded-full">
                    {{ $post->category->name }}
                </a>
                @if($post->tags && $post->tags->count() > 0)
                    @foreach($post->tags as $tag)
                        <span class="text-xs font-semibold tracking-wider text-gray-500 uppercase bg-gray-100 px-3 py-1 rounded-full">
                            #{{ $tag->name }}
                        </span>
                    @endforeach
                @endif
            </div>
        @endif
        
        <h1 class="text-4xl md:text-6xl font-extrabold leading-tight text-gray-900 mb-8">{{ $post->title }}</h1>
        
        <div class="flex items-center gap-4 text-gray-500">
            <div class="w-12 h-12 rounded-full bg-rose-500 flex items-center justify-center text-lg text-white font-bold shadow-sm">
                {{ substr($post->user->name, 0, 1) }}
            </div>
            <div>
                <p class="font-semibold text-gray-900 text-lg">{{ $post->user->name }}</p>
                <p class="text-sm">{{ $post->created_at->format('M d, Y') }} &middot; {{ $post->created_at->diffForHumans() }}</p>
            </div>
        </div>
    </div>

    @if($post->image)
        <div class="max-w-6xl mx-auto px-4 mb-12">
            @php $images = is_array($post->image) ? $post->image : [$post->image]; @endphp
            @if(count($images) > 1)
                <!-- Infinite Swiper.js Layout -->
                <div class="relative group">
                    <div class="swiper mySwiper w-full aspect-video rounded-3xl shadow-xl">
                        <div class="swiper-wrapper">
                            @foreach($images as $img)
                                <div class="swiper-slide w-full aspect-video relative bg-gray-100">
                                    <img src="{{ Storage::url($img) }}" alt="{{ $post->title }}" class="absolute inset-0 w-full h-full object-cover">
                                </div>
                            @endforeach
                        </div>
                        <!-- Navigation Arrows -->
                        <div class="swiper-button-next"></div>
                        <div class="swiper-button-prev"></div>
                    </div>
                </div>
                
                <div class="text-center mt-4">
                    <span class="text-xs font-semibold tracking-wider text-gray-500 uppercase bg-gray-100 px-4 py-2 rounded-full shadow-sm">&larr; Swipe infinitely or use arrows &rarr;</span>
                </div>
                
                <!-- Swiper Initialization -->
                <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        var swiper = new Swiper('.mySwiper', {
                            loop: true,
                            grabCursor: true,
                            navigation: {
                                nextEl: '.swiper-button-next',
                                prevEl: '.swiper-button-prev',
                            },
                        });
                    });
                </script>
            @else
                <img src="{{ Storage::url($images[0]) }}" alt="{{ $post->title }}" class="w-full aspect-video object-cover rounded-3xl shadow-xl">
            @endif
        </div>
    @endif

    <!-- Content -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <article class="prose prose-lg md:prose-xl max-w-none text-gray-700 prose-headings:text-gray-900 prose-a:text-rose-600 hover:prose-a:text-rose-500">
            {!! $post->content !!}
        </article>
        
        @if($relatedPosts && $relatedPosts->count() > 0)
        <!-- Related Posts -->
        <div class="mt-16 pt-12 border-t border-gray-100">
            <h3 class="text-2xl font-bold mb-6 text-gray-900">You might also like</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                @foreach($relatedPosts as $related)
                    <a href="{{ route('posts.show', $related->slug) }}" class="group block relative rounded-2xl overflow-hidden bg-gray-100 h-48">
                        @if($related->image)
                            @php $relImage = is_array($related->image) ? $related->image[0] : $related->image; @endphp
                            <img src="{{ Storage::url($relImage) }}" alt="{{ $related->title }}" class="absolute inset-0 w-full h-full object-cover transform transition-transform duration-500 group-hover:scale-105">
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent"></div>
                        <div class="absolute bottom-0 left-0 right-0 p-4">
                            <h4 class="text-white font-bold leading-tight group-hover:text-rose-400 transition-colors">{{ Str::limit($related->title, 50) }}</h4>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
        @endif
        
        <div class="mt-16 pt-8 border-t border-gray-100 flex flex-col sm:flex-row justify-between items-center gap-6">
            <!-- Social Share -->
            <div class="flex items-center gap-4">
                <span class="text-sm font-bold text-gray-500 uppercase tracking-wider">Share:</span>
                <!-- Facebook -->
                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->fullUrl()) }}" target="_blank" class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-gray-600 hover:bg-rose-50 hover:text-rose-600 transition-colors" aria-label="Share on Facebook">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"/></svg>
                </a>
                <!-- Twitter/X -->
                <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->fullUrl()) }}&text={{ urlencode($post->title) }}" target="_blank" class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-gray-600 hover:bg-rose-50 hover:text-rose-600 transition-colors" aria-label="Share on X">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg>
                </a>
                <!-- WhatsApp -->
                <a href="https://api.whatsapp.com/send?text={{ urlencode($post->title . ' ' . request()->fullUrl()) }}" target="_blank" class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-gray-600 hover:bg-rose-50 hover:text-rose-600 transition-colors" aria-label="Share on WhatsApp">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 0c-6.627 0-12 5.373-12 12 0 2.19.596 4.252 1.636 6.021l-1.667 6.084 6.223-1.632c1.725.952 3.708 1.496 5.808 1.496 6.627 0 12-5.373 12-12s-5.373-12-12-12zm0 21.896c-1.874 0-3.625-.487-5.15-1.353l-.37-.21-3.816 1.001 1.018-3.717-.23-.365c-.958-1.534-1.472-3.32-1.472-5.19 0-5.467 4.449-9.916 9.916-9.916s9.916 4.449 9.916 9.916-4.449 9.916-9.916 9.916zm5.45-7.443c-.299-.15-1.767-.872-2.04-.972-.272-.1-.471-.15-.67.15-.199.3-.771.972-.945 1.171-.174.2-.348.225-.647.075-.299-.15-1.26-.465-2.4-1.485-.886-.795-1.484-1.777-1.658-2.077-.174-.3-.018-.462.132-.612.135-.135.299-.35.449-.525.15-.175.2-.299.299-.499.1-.2.05-.375-.025-.525-.075-.15-.67-1.615-.918-2.21-.242-.579-.487-.5-.67-.51-.174-.01-.373-.01-.572-.01-.199 0-.522.075-.796.375-.274.3-1.045 1.02-1.045 2.485 0 1.465 1.07 2.88 1.219 3.08.15.2 2.095 3.195 5.076 4.482.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.086 1.767-.722 2.016-1.422.249-.7.249-1.295.174-1.422-.074-.127-.274-.2-.573-.35z"/></svg>
                </a>
            </div>
            
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-gray-500 hover:text-gray-900 transition-colors font-medium mt-6 sm:mt-0">
                &larr; Back to all stories
            </a>
        </div>
    </div>
</main>
@endsection
