@extends('layouts.public')

@section('title', $page->title . ' - vnews.id')

@section('content')
    <main class="flex-grow w-full pb-20">
        <div class="max-w-3xl mx-auto px-4 pt-20 pb-10">
            <h1 class="text-4xl md:text-5xl font-extrabold leading-tight text-gray-900 mb-8">
                {{ $page->title }}
            </h1>

            <div class="prose prose-lg md:prose-xl max-w-none text-gray-700">
                @if($page->lead_text)
                    <p class="lead text-2xl font-medium text-gray-900 mb-8">
                        {{ $page->lead_text }}
                    </p>
                @endif

                {!! $page->content !!}

                @if($page->contact_email || $page->contact_address)
                    <div class="mt-12 p-8 bg-gray-50 rounded-3xl">
                        <h4 class="font-bold text-gray-900 mb-2">Kontak Redaksi</h4>
                        @if($page->contact_email)
                            <p class="mb-1"><strong>Email:</strong> {{ $page->contact_email }}</p>
                        @endif
                        @if($page->contact_address)
                            <p><strong>Alamat:</strong> {{ $page->contact_address }}</p>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </main>
@endsection
