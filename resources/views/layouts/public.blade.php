<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'vnews.id - Visual News')</title>
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
                    <a href="{{ route('home') }}" class="flex flex-col group">
                        <div class="text-3xl font-extrabold tracking-tighter text-black flex items-center group-hover:opacity-80 transition-opacity">
                            @yield('nav-back')vnews<span class="text-rose-600">.id</span>
                        </div>
                        <span class="text-[0.65rem] font-bold text-gray-400 uppercase tracking-widest mt-[0.1rem] ml-[0.1rem]">Visual News</span>
                    </a>
                </div>
                <div class="flex items-center space-x-6">
                    <form action="{{ url('/search') }}" method="GET" class="hidden sm:block relative">
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Search news..." class="w-48 bg-gray-100 border-none rounded-full px-4 py-2 text-sm focus:ring-rose-500 focus:bg-white transition-all">
                        <button type="submit" class="absolute right-3 top-2.5 text-gray-400 hover:text-gray-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </button>
                    </form>
                    <a href="{{ route('about') }}" class="text-sm font-medium text-gray-600 hover:text-black transition-colors hidden sm:block">About</a>
                    @auth
                        <a href="{{ url('/admin') }}" class="text-sm font-medium text-gray-600 hover:text-black transition-colors">Admin Panel</a>
                    @else
                        <a href="{{ url('/admin/login') }}" class="text-sm font-medium text-gray-600 hover:text-black transition-colors">Sign In</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    @yield('content')

    <footer class="border-t border-gray-100 py-12 mt-auto bg-white">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p class="text-sm text-gray-400">
                &copy; {{ date('Y') }} vnews.id. All visual rights reserved.
            </p>
        </div>
    </footer>
</body>
</html>
