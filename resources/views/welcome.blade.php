@extends('layouts.public')

@section('hero')
{{-- ============================================
     HERO SECTION — 2-Up Carousel (max 6 posts, 3 slides)
     ============================================ --}}
@if($heroPosts && $heroPosts->count() > 0)
    @php
        $heroChunks = $heroPosts->chunk(2);
        $totalSlides = $heroChunks->count();
    @endphp

    {{-- Section label --}}
    <div class="hero-section-label">
        <div class="hero-section-label__text">
            <span class="hero-live-dot"></span>
            <span>Berita Terkini</span>
            <span class="hero-section-label__line"></span>
        </div>
    </div>

    {{-- Carousel --}}
    <div class="hero-carousel" id="heroCarousel" data-total-slides="{{ $totalSlides }}">
        {{-- Track --}}
        <div class="hero-carousel__track" id="heroTrack">
            @foreach($heroChunks as $slideIndex => $chunk)
                <div class="hero-carousel__slide">
                    @foreach($chunk as $post)
                        @php
                            $postImage = is_array($post->image) ? ($post->image[0] ?? null) : $post->image;
                            $excerpt = $post->content ? Str::limit(strip_tags($post->content), 120, '...') : '';
                        @endphp

                        <div>
                            <a href="{{ route('posts.show', $post->slug) }}" class="hero-card">
                                {{-- Photo --}}
                                @if($postImage)
                                    <img src="{{ Storage::url($postImage) }}"
                                         alt="{{ $post->title }}"
                                         class="hero-card__image"
                                         loading="{{ $slideIndex === 0 ? 'eager' : 'lazy' }}"
                                         decoding="async">
                                @else
                                    <div class="hero-card__noimage">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                  d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                @endif

                                {{-- Gradient Overlay --}}
                                <div class="hero-card__overlay"></div>

                                {{-- Content --}}
                                <div class="hero-card__content">
                                    @if($post->category)
                                        <span class="hero-card__category">{{ $post->category->name }}</span>
                                    @endif

                                    <h2 class="hero-card__title">{{ $post->title }}</h2>

                                    @if($excerpt)
                                        <p class="hero-card__excerpt">{{ $excerpt }}</p>
                                    @endif

                                    <div class="hero-card__meta">
                                        <span>{{ $post->user->name }}</span>
                                        <span class="hero-card__meta-dot"></span>
                                        <span>{{ $post->published_at ? $post->published_at->diffForHumans() : $post->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>

        {{-- Navigation Arrows --}}
        @if($totalSlides > 1)
            <button class="hero-carousel__nav hero-carousel__nav--prev" id="heroPrev" aria-label="Previous slide">
                <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/>
                </svg>
            </button>
            <button class="hero-carousel__nav hero-carousel__nav--next" id="heroNext" aria-label="Next slide">
                <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/>
                </svg>
            </button>

            {{-- Dot indicators --}}
            <div class="hero-carousel__dots" id="heroDots">
                @for($i = 0; $i < $totalSlides; $i++)
                    <button class="hero-carousel__dot {{ $i === 0 ? 'is-active' : '' }}"
                            data-slide="{{ $i }}"
                            aria-label="Go to slide {{ $i + 1 }}"></button>
                @endfor
            </div>
        @endif
    </div>
@endif
@endsection

@section('content')
<main class="flex-grow max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 w-full">
    
    {{-- Category Navigation --}}
    <div class="mb-10 flex space-x-2 overflow-x-auto pb-4 scrollbar-hide">
        <a href="{{ route('home') }}" class="whitespace-nowrap px-4 py-2 rounded-full text-sm font-semibold transition-colors bg-black text-white">All</a>
        @foreach($categories as $cat)
            <a href="{{ route('category.show', $cat->slug) }}" class="whitespace-nowrap px-4 py-2 rounded-full text-sm font-semibold transition-colors bg-gray-100 text-gray-700 hover:bg-gray-200">{{ $cat->name }}</a>
        @endforeach
    </div>

    @if($topPosts && $topPosts->count() > 0)
        {{-- Top Posts --}}
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
        {{-- Other Posts Masonry --}}
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
    
    @if((!$heroPosts || $heroPosts->count() == 0) && (!$topPosts || $topPosts->count() == 0))
        <div class="flex flex-col items-center justify-center py-20 text-center">
            <h3 class="text-xl font-semibold text-gray-900">No visual stories yet</h3>
            <p class="text-gray-500 mt-2">Check back later for breathtaking photo journalism.</p>
        </div>
    @endif

</main>
@endsection

@section('extra_js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const carousel = document.getElementById('heroCarousel');
    if (!carousel) return;

    const track = document.getElementById('heroTrack');
    const prevBtn = document.getElementById('heroPrev');
    const nextBtn = document.getElementById('heroNext');
    const dotsContainer = document.getElementById('heroDots');
    const totalSlides = parseInt(carousel.dataset.totalSlides);

    if (totalSlides < 2) return;

    // --- Infinite loop: clone first & last slides ---
    const slides = Array.from(track.children);
    const firstClone = slides[0].cloneNode(true);
    const lastClone = slides[slides.length - 1].cloneNode(true);
    firstClone.setAttribute('aria-hidden', 'true');
    lastClone.setAttribute('aria-hidden', 'true');
    track.appendChild(firstClone);           // append clone of first at end
    track.insertBefore(lastClone, slides[0]); // prepend clone of last at start

    // With clones: [cloneLast] [slide0] [slide1] [slide2] [cloneFirst]
    // Index 0 = cloneLast, 1..N = real slides, N+1 = cloneFirst
    let index = 1; // start at first real slide
    let isTransitioning = false;
    let startX = 0;
    let currentX = 0;
    let isDragging = false;

    function setPosition(i, animate) {
        if (animate) {
            track.style.transition = 'transform 0.55s cubic-bezier(0.25, 0.46, 0.45, 0.94)';
        } else {
            track.style.transition = 'none';
        }
        track.style.transform = `translateX(-${i * 100}%)`;
    }

    function updateDots() {
        if (!dotsContainer) return;
        // Map index to real slide: index 1..totalSlides => dot 0..totalSlides-1
        let realIndex = index - 1;
        if (realIndex < 0) realIndex = totalSlides - 1;
        if (realIndex >= totalSlides) realIndex = 0;
        dotsContainer.querySelectorAll('.hero-carousel__dot').forEach((dot, i) => {
            dot.classList.toggle('is-active', i === realIndex);
        });
    }

    function goTo(i) {
        if (isTransitioning) return;
        isTransitioning = true;
        index = i;
        setPosition(index, true);
        updateDots();
    }

    // After transition ends, snap to real slide if on a clone
    track.addEventListener('transitionend', () => {
        isTransitioning = false;
        if (index === 0) {
            // On cloneLast -> jump to real last
            index = totalSlides;
            setPosition(index, false);
        } else if (index === totalSlides + 1) {
            // On cloneFirst -> jump to real first
            index = 1;
            setPosition(index, false);
        }
    });

    // Initialize position (no animation)
    setPosition(index, false);
    updateDots();

    // Arrow clicks
    prevBtn.addEventListener('click', () => goTo(index - 1));
    nextBtn.addEventListener('click', () => goTo(index + 1));

    // Dot clicks
    if (dotsContainer) {
        dotsContainer.addEventListener('click', (e) => {
            const dot = e.target.closest('.hero-carousel__dot');
            if (dot) goTo(parseInt(dot.dataset.slide) + 1); // +1 because of prepended clone
        });
    }

    // Touch swipe
    carousel.addEventListener('touchstart', (e) => {
        if (isTransitioning) return;
        startX = e.touches[0].clientX;
        isDragging = true;
        track.style.transition = 'none';
    }, { passive: true });

    carousel.addEventListener('touchmove', (e) => {
        if (!isDragging) return;
        currentX = e.touches[0].clientX;
        const diff = currentX - startX;
        const offset = -(index * 100) + (diff / carousel.offsetWidth * 100);
        track.style.transform = `translateX(${offset}%)`;
    }, { passive: true });

    carousel.addEventListener('touchend', () => {
        if (!isDragging) return;
        isDragging = false;
        const diff = currentX - startX;
        const threshold = carousel.offsetWidth * 0.15;

        if (diff < -threshold) {
            goTo(index + 1);
        } else if (diff > threshold) {
            goTo(index - 1);
        } else {
            goTo(index); // snap back
        }
        startX = 0;
        currentX = 0;
    });

    // Keyboard nav
    carousel.setAttribute('tabindex', '0');
    carousel.addEventListener('keydown', (e) => {
        if (e.key === 'ArrowLeft') goTo(index - 1);
        if (e.key === 'ArrowRight') goTo(index + 1);
    });
});
</script>
@endsection
