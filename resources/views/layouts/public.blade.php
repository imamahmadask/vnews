<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'vnews.id - Berita Visual Indonesia')</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    @yield('meta_tags')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Outfit', sans-serif; }
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
    @yield('extra_css')
</head>
<body class="bg-white text-gray-900 antialiased min-h-screen flex flex-col selection:bg-black selection:text-white">
    <!-- Navbar -->
    <nav class="sticky top-0 z-50 bg-white/90 backdrop-blur-md border-b border-gray-100 transition-colors duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                <div class="flex-shrink-0 flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center group gap-2">
                        @yield('nav-back')
                        <img src="{{ asset('images/logo vnews.png') }}" alt="vnews.id" class="h-10 w-auto object-contain group-hover:opacity-80 transition-opacity">
                    </a>
                </div>
                <div class="flex items-center space-x-6">
                    <form action="{{ url('/search') }}" method="GET" class="hidden sm:block relative">
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari berita..." class="w-48 bg-gray-100 border-none rounded-full px-4 py-2 text-sm focus:ring-orange-500 focus:bg-white transition-all">
                        <button type="submit" class="absolute right-3 top-2.5 text-gray-400 hover:text-gray-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </button>
                    </form>
                    <a href="{{ route('about') }}" class="text-sm font-medium text-gray-600 hover:text-black transition-colors hidden sm:block">Tentang</a>
                    @auth
                        <a href="{{ url('/admin') }}" class="text-sm font-medium text-gray-600 hover:text-black transition-colors">Panel Admin</a>
                    @else
                        <a href="{{ url('/admin/login') }}" class="text-sm font-medium text-gray-600 hover:text-black transition-colors">Masuk</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    @yield('hero')
    @yield('content')

    @yield('extra_js')
    <footer class="bg-[#0B192C] mt-auto">
        <!-- Section Label (matches hero label style) -->
        <div class="border-b border-white/5 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto py-4">
                <div class="flex items-center gap-3">
                    <span class="hero-live-dot"></span>
                    <span class="text-[0.65rem] font-bold uppercase tracking-[0.2em] text-white/30">vnews.id</span>
                    <span class="flex-1 h-px bg-gradient-to-r from-white/10 to-transparent"></span>
                </div>
            </div>
        </div>

        <!-- Main Footer Content -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-12 md:gap-8 pb-14 border-b border-white/8">

                <!-- Col 1: Logo & Tagline (wider) -->
                <div class="md:col-span-5 flex flex-col gap-5">
                    <a href="{{ route('home') }}" class="inline-block group">
                        <img src="{{ asset('images/logo vnews.png') }}" alt="vnews.id"
                             class="h-11 w-auto object-contain brightness-0 invert group-hover:opacity-75 transition-opacity duration-300">
                    </a>
                    <p class="text-sm text-white/45 leading-relaxed max-w-xs">
                        Portal berita visual terdepan. Kami percaya satu foto mampu menceritakan kisah yang lebih dalam daripada ribuan kata.
                    </p>
                    <!-- Social icons -->
                    <div class="flex items-center gap-3 mt-1">
                        <a href="https://instagram.com" target="_blank" rel="noopener noreferrer"
                           class="w-9 h-9 rounded-full border border-white/10 bg-white/5 flex items-center justify-center text-white/50 hover:text-white hover:bg-orange-600 hover:border-orange-600 transition-all duration-300"
                           aria-label="Instagram">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect>
                                <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                                <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line>
                            </svg>
                        </a>
                        <a href="https://facebook.com" target="_blank" rel="noopener noreferrer"
                           class="w-9 h-9 rounded-full border border-white/10 bg-white/5 flex items-center justify-center text-white/50 hover:text-white hover:bg-orange-600 hover:border-orange-600 transition-all duration-300"
                           aria-label="Facebook">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"/>
                            </svg>
                        </a>
                        <a href="https://twitter.com" target="_blank" rel="noopener noreferrer"
                           class="w-9 h-9 rounded-full border border-white/10 bg-white/5 flex items-center justify-center text-white/50 hover:text-white hover:bg-orange-600 hover:border-orange-600 transition-all duration-300"
                           aria-label="Twitter / X">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Col 2: Navigasi -->
                <div class="md:col-span-3 md:col-start-7">
                    <h4 class="text-[0.65rem] font-bold uppercase tracking-[0.2em] text-white/30 mb-5">Navigasi</h4>
                    <ul class="space-y-3">
                        <li>
                            <a href="{{ route('home') }}"
                               class="text-sm text-white/55 hover:text-white hover:translate-x-1 transition-all duration-200 inline-flex items-center gap-2 group">
                                <span class="w-3 h-px bg-orange-600 opacity-0 group-hover:opacity-100 transition-opacity"></span>
                                Beranda
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('about') }}"
                               class="text-sm text-white/55 hover:text-white hover:translate-x-1 transition-all duration-200 inline-flex items-center gap-2 group">
                                <span class="w-3 h-px bg-orange-600 opacity-0 group-hover:opacity-100 transition-opacity"></span>
                                Tentang Kami
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('/admin') }}"
                               class="text-sm text-white/55 hover:text-white hover:translate-x-1 transition-all duration-200 inline-flex items-center gap-2 group">
                                <span class="w-3 h-px bg-orange-600 opacity-0 group-hover:opacity-100 transition-opacity"></span>
                                Panel Admin
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Col 3: Kategori -->
                <div class="md:col-span-3">
                    <h4 class="text-[0.65rem] font-bold uppercase tracking-[0.2em] text-white/30 mb-5">Kategori</h4>
                    <ul class="space-y-3">
                        @foreach(\App\Models\Category::take(5)->get() as $footerCat)
                        <li>
                            <a href="{{ route('category.show', $footerCat->slug) }}"
                               class="text-sm text-white/55 hover:text-white hover:translate-x-1 transition-all duration-200 inline-flex items-center gap-2 group">
                                <span class="w-3 h-px bg-orange-600 opacity-0 group-hover:opacity-100 transition-opacity"></span>
                                {{ $footerCat->name }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <!-- Bottom Bar -->
            <div class="pt-8 flex flex-col sm:flex-row items-center justify-between gap-4">
                <p class="text-[0.7rem] text-white/25 tracking-wide">
                    &copy; {{ date('Y') }} <span class="text-white/40">vnews.id</span> &mdash; Seluruh hak cipta visual dilindungi.
                </p>
                <div class="flex items-center gap-6 text-[0.7rem] text-white/25">
                    <a href="#" class="hover:text-white/60 transition-colors">Kebijakan Privasi</a>
                    <span class="w-px h-3 bg-white/15"></span>
                    <a href="#" class="hover:text-white/60 transition-colors">Syarat &amp; Ketentuan</a>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
