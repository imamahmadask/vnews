@extends('layouts.public')

@section('title', $category->name . ' - vnews.id')

@section('nav-back')
<span class="mr-2 text-xl">&larr;</span> 
@endsection

@section('content')
<main class="flex-grow max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 w-full">
    
    <!-- Category Navigation -->
    <div class="mb-10 flex space-x-2 overflow-x-auto pb-4 scrollbar-hide">
        <a href="{{ route('home') }}" class="whitespace-nowrap px-4 py-2 rounded-full text-sm font-semibold transition-colors bg-gray-100 text-gray-700 hover:bg-gray-200">Semua</a>
        @foreach($categories as $cat)
            @if($cat->id === $category->id)
                <a href="{{ route('category.show', $cat->slug) }}" class="whitespace-nowrap px-4 py-2 rounded-full text-sm font-semibold transition-colors bg-black text-white">{{ $cat->name }}</a>
            @else
                <a href="{{ route('category.show', $cat->slug) }}" class="whitespace-nowrap px-4 py-2 rounded-full text-sm font-semibold transition-colors bg-gray-100 text-gray-700 hover:bg-gray-200">{{ $cat->name }}</a>
            @endif
        @endforeach
    </div>

    <div class="mb-12">
        <h1 class="text-4xl sm:text-5xl font-extrabold tracking-tight mb-4">{{ $category->name }}</h1>
        <p class="text-lg text-gray-500 max-w-2xl">{{ $category->description ?? 'Jelajahi semua berita dalam kategori ini.' }}</p>
    </div>

    @if($posts->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($posts as $post)
                <a href="{{ route('posts.show', $post->slug) }}" class="group block">
                    {{-- Image --}}
                    <div class="relative w-full aspect-[6/4] overflow-hidden mb-4 bg-gray-100">
                        @if($post->image)
                            @php $catImage = is_array($post->image) ? $post->image[0] : $post->image; @endphp
                            <img src="{{ Storage::url($catImage) }}"
                                 alt="{{ $post->title }}"
                                 class="w-full h-full object-cover transform transition-transform duration-500 group-hover:scale-105">
                        @else
                            <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                          d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        @endif
                        {{-- Views badge --}}
                        <div class="absolute top-2 right-2 bg-black/60 backdrop-blur-sm text-white text-[0.6rem] font-bold px-2 py-0.5 rounded-full flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            {{ number_format($post->views_count) }}
                        </div>
                    </div>
                    {{-- Content --}}
                    @if($post->category)
                        <span class="text-xs font-bold tracking-wider text-orange-600 uppercase mb-2 block">
                            {{ $post->category->name }}
                        </span>
                    @endif
                    <h2 class="text-xl font-bold text-gray-900 group-hover:text-orange-600 transition-colors leading-tight mb-2"
                        style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
                        {{ $post->title }}
                    </h2>
                    <div class="flex items-center gap-2">
                        <span class="w-6 h-6 rounded-full bg-orange-500 flex items-center justify-center text-xs text-white font-bold flex-shrink-0">
                            {{ strtoupper(substr($post->user->name, 0, 1)) }}
                        </span>
                        <p class="text-sm text-gray-500">
                            {{ $post->user->name }} &middot; {{ $post->published_at ? $post->published_at->diffForHumans() : $post->created_at->diffForHumans() }}
                        </p>
                    </div>
                </a>
            @endforeach
        </div>

        {{-- Pagination --}}
        @if($posts->hasPages())
            <div class="mt-12">
                {{ $posts->links() }}
            </div>
        @endif
    @else
        <div class="flex flex-col items-center justify-center py-20 text-center">
            <h3 class="text-xl font-semibold text-gray-900">Belum ada berita di kategori ini</h3>
            <p class="text-gray-500 mt-2">Kunjungi lagi nanti atau jelajahi kategori lainnya.</p>
        </div>
    @endif

</main>
@endsection
