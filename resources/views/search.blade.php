@extends('layouts.public')

@section('title', 'Hasil Pencarian: "' . $query . '" - vnews.id')

@section('nav-back')
<span class="mr-2 text-xl">&larr;</span> 
@endsection

@section('content')
<main class="flex-grow max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 w-full">
    
    <div class="mb-12">
        <h1 class="text-3xl sm:text-4xl font-bold tracking-tight mb-4">Hasil Pencarian</h1>
        @if($query)
            <p class="text-lg text-gray-500">Menampilkan hasil untuk: <span class="font-bold text-gray-900">"{{ $query }}"</span></p>
        @else
            <p class="text-lg text-gray-500">Semua berita yang tersedia</p>
        @endif
    </div>

    @if($posts->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($posts as $post)
                <a href="{{ route('posts.show', $post->slug) }}" class="group block">
                    {{-- Image --}}
                    <div class="relative w-full h-56 overflow-hidden mb-4 bg-gray-100">
                        @if($post->image)
                            @php $searchImage = is_array($post->image) ? $post->image[0] : $post->image; @endphp
                            <img src="{{ Storage::url($searchImage) }}"
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
    @else
        <div class="flex flex-col items-center justify-center py-20 text-center">
            <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <h3 class="text-xl font-semibold text-gray-900">Tidak ada berita ditemukan</h3>
            <p class="text-gray-500 mt-2">Coba gunakan kata kunci pencarian yang berbeda.</p>
            <a href="{{ route('home') }}" class="mt-6 px-6 py-2 bg-black text-white rounded-full font-medium hover:bg-gray-800 transition-colors">Kembali ke Beranda</a>
        </div>
    @endif

</main>
@endsection
