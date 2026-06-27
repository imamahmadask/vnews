@extends('layouts.public')

@section('title', 'Search Results for "' . $query . '" - vnews.id')

@section('nav-back')
<span class="mr-2 text-xl">&larr;</span> 
@endsection

@section('content')
<main class="flex-grow max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 w-full">
    
    <div class="mb-12">
        <h1 class="text-3xl sm:text-4xl font-bold tracking-tight mb-4">Search Results</h1>
        @if($query)
            <p class="text-lg text-gray-500">Showing results for: <span class="font-bold text-gray-900">"{{ $query }}"</span></p>
        @else
            <p class="text-lg text-gray-500">All available news</p>
        @endif
    </div>

    @if($posts->count() > 0)
        <div class="columns-1 sm:columns-2 lg:columns-3 gap-6 space-y-6">
            @foreach($posts as $post)
                <div class="break-inside-avoid group relative rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 bg-gray-50 hover:bg-gray-100">
                    <a href="{{ route('posts.show', $post->slug) }}" class="block">
                        @if($post->image)
                            @php $searchImage = is_array($post->image) ? $post->image[0] : $post->image; @endphp
                            <img src="{{ Storage::url($searchImage) }}" alt="{{ $post->title }}" class="w-full h-auto object-cover rounded-t-2xl">
                        @else
                            <div class="w-full h-48 bg-gray-200 flex items-center justify-center rounded-t-2xl">
                                <span class="text-gray-400">No Image</span>
                            </div>
                        @endif
                        <div class="p-5">
                            @if($post->category)
                                <span class="text-xs font-bold tracking-wider text-rose-600 uppercase mb-2 block">
                                    {{ $post->category->name }}
                                </span>
                            @endif
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
            <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <h3 class="text-xl font-semibold text-gray-900">No stories found</h3>
            <p class="text-gray-500 mt-2">Try adjusting your search keywords.</p>
            <a href="{{ route('home') }}" class="mt-6 px-6 py-2 bg-black text-white rounded-full font-medium hover:bg-gray-800 transition-colors">Go back home</a>
        </div>
    @endif

</main>
@endsection
