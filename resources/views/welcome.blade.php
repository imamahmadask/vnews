@extends('layouts.public')

@section('content')
<main class="flex-grow max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 w-full">
    
    <!-- Category Navigation -->
    <div class="mb-10 flex space-x-2 overflow-x-auto pb-4 scrollbar-hide">
        <a href="{{ route('home') }}" class="whitespace-nowrap px-4 py-2 rounded-full text-sm font-semibold transition-colors bg-black text-white">All</a>
        @foreach($categories as $cat)
            <a href="{{ route('category.show', $cat->slug) }}" class="whitespace-nowrap px-4 py-2 rounded-full text-sm font-semibold transition-colors bg-gray-100 text-gray-700 hover:bg-gray-200">{{ $cat->name }}</a>
        @endforeach
    </div>

    @if($featuredPost)
        <!-- Featured Post -->
        <div class="mb-16 group relative block rounded-3xl overflow-hidden shadow-xl hover:shadow-2xl transition-all duration-500 bg-white">
            <a href="{{ route('posts.show', $featuredPost->slug) }}" class="block relative w-full h-[60vh] md:h-[70vh]">
                @if($featuredPost->image)
                    @php $featuredImage = is_array($featuredPost->image) ? $featuredPost->image[0] : $featuredPost->image; @endphp
                    <img src="{{ Storage::url($featuredImage) }}" alt="{{ $featuredPost->title }}" class="absolute inset-0 w-full h-full object-cover transform transition-transform duration-700 group-hover:scale-105">
                @else
                    <div class="absolute inset-0 bg-gray-200 flex items-center justify-center">
                        <span class="text-gray-400">No Image</span>
                    </div>
                @endif
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                <div class="absolute bottom-0 left-0 right-0 p-8 md:p-12">
                    @if($featuredPost->category)
                        <span class="inline-block px-3 py-1 mb-4 text-xs font-bold tracking-wider text-rose-600 bg-white rounded-full uppercase shadow-sm">
                            {{ $featuredPost->category->name }}
                        </span>
                    @endif
                    <h2 class="text-3xl md:text-5xl lg:text-6xl font-extrabold text-white leading-tight mb-4 drop-shadow-md">{{ $featuredPost->title }}</h2>
                    <div class="flex items-center gap-3 text-white">
                        <div class="w-10 h-10 rounded-full bg-rose-500 flex items-center justify-center text-sm font-bold shadow-sm">
                            {{ substr($featuredPost->user->name, 0, 1) }}
                        </div>
                        <div>
                            <p class="font-semibold">{{ $featuredPost->user->name }}</p>
                            <p class="text-sm opacity-80">{{ $featuredPost->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    @endif

    @if($topPosts && $topPosts->count() > 0)
        <!-- Top Posts -->
        <div class="mb-16">
            <h3 class="text-2xl font-bold mb-6 border-b border-gray-200 pb-2">Top Stories</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($topPosts as $post)
                    <a href="{{ route('posts.show', $post->slug) }}" class="group block">
                        <div class="relative w-full h-56 rounded-2xl overflow-hidden mb-4 bg-gray-100">
                            @if($post->image)
                                @php $topImage = is_array($post->image) ? $post->image[0] : $post->image; @endphp
                                <img src="{{ Storage::url($topImage) }}" alt="{{ $post->title }}" class="w-full h-full object-cover transform transition-transform duration-500 group-hover:scale-105">
                            @endif
                        </div>
                        @if($post->category)
                            <span class="text-xs font-bold tracking-wider text-rose-600 uppercase mb-2 block">
                                {{ $post->category->name }}
                            </span>
                        @endif
                        <h4 class="text-xl font-bold text-gray-900 group-hover:text-rose-600 transition-colors leading-tight mb-2">{{ $post->title }}</h4>
                        <p class="text-sm text-gray-500">{{ $post->user->name }} &middot; {{ $post->created_at->format('M d, Y') }}</p>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    @if($otherPosts && $otherPosts->count() > 0)
        <!-- Other Posts Masonry -->
        <div>
            <h3 class="text-2xl font-bold mb-6 border-b border-gray-200 pb-2">More News</h3>
            <div class="columns-1 sm:columns-2 lg:columns-4 gap-6 space-y-6">
                @foreach($otherPosts as $post)
                    <div class="break-inside-avoid group relative rounded-2xl overflow-hidden bg-gray-50 hover:bg-gray-100 transition-colors duration-300">
                        <a href="{{ route('posts.show', $post->slug) }}" class="block">
                            @if($post->image)
                                @php $otherImage = is_array($post->image) ? $post->image[0] : $post->image; @endphp
                                <img src="{{ Storage::url($otherImage) }}" alt="{{ $post->title }}" class="w-full h-auto object-cover rounded-t-2xl">
                            @endif
                            <div class="p-5">
                                @if($post->category)
                                    <span class="text-xs font-bold tracking-wider text-rose-600 uppercase mb-2 block">
                                        {{ $post->category->name }}
                                    </span>
                                @endif
                                <h4 class="text-lg font-bold text-gray-900 group-hover:text-rose-600 transition-colors leading-tight mb-2">{{ $post->title }}</h4>
                                <p class="text-xs text-gray-500">{{ $post->created_at->diffForHumans() }}</p>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
    
    @if(!$featuredPost && (!$topPosts || $topPosts->count() == 0))
        <div class="flex flex-col items-center justify-center py-20 text-center">
            <h3 class="text-xl font-semibold text-gray-900">No visual stories yet</h3>
            <p class="text-gray-500 mt-2">Check back later for breathtaking photo journalism.</p>
        </div>
    @endif

</main>
@endsection
