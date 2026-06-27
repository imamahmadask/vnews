@extends('layouts.public')

@section('title', $category->name . ' - vnews.id')

@section('nav-back')
<span class="mr-2 text-xl">&larr;</span> 
@endsection

@section('content')
<main class="flex-grow max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 w-full">
    
    <!-- Category Navigation -->
    <div class="mb-10 flex space-x-2 overflow-x-auto pb-4 scrollbar-hide">
        <a href="{{ route('home') }}" class="whitespace-nowrap px-4 py-2 rounded-full text-sm font-semibold transition-colors bg-gray-100 text-gray-700 hover:bg-gray-200">All</a>
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
        <p class="text-lg text-gray-500 max-w-2xl">{{ $category->description ?? 'Explore all stories in this category.' }}</p>
    </div>

    @if($posts->count() > 0)
        <div class="columns-1 sm:columns-2 lg:columns-3 gap-6 space-y-6">
            @foreach($posts as $post)
                <div class="break-inside-avoid group relative rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 bg-gray-50 hover:bg-gray-100">
                    <a href="{{ route('posts.show', $post->slug) }}" class="block">
                        @if($post->image)
                            @php $catImage = is_array($post->image) ? $post->image[0] : $post->image; @endphp
                            <img src="{{ Storage::url($catImage) }}" alt="{{ $post->title }}" class="w-full h-auto object-cover rounded-t-2xl">
                        @else
                            <div class="w-full h-48 bg-gray-200 flex items-center justify-center rounded-t-2xl">
                                <span class="text-gray-400">No Image</span>
                            </div>
                        @endif
                        <div class="p-5">
                            <h2 class="text-xl font-bold text-gray-900 leading-tight mb-2 group-hover:text-rose-600 transition-colors">{{ $post->title }}</h2>
                            <p class="text-sm text-gray-500 flex items-center gap-2 mt-4">
                                <span class="w-6 h-6 rounded-full bg-rose-500 flex items-center justify-center text-xs text-white font-bold">{{ substr($post->user->name, 0, 1) }}</span>
                                {{ $post->user->name }} &middot; {{ $post->created_at->diffForHumans() }}
                            </p>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    @else
        <div class="flex flex-col items-center justify-center py-20 text-center">
            <h3 class="text-xl font-semibold text-gray-900">No stories in this category</h3>
            <p class="text-gray-500 mt-2">Check back later or explore other categories.</p>
        </div>
    @endif

</main>
@endsection
