@extends('theme::layouts.full-content')

@section('content')
<div class="bg-[var(--bg-color-card)] p-6 shadow-xl border border-[var(--border-color)] -mt-16 relative z-10">
    <div class="text-center mb-12">
        <nav aria-label="Breadcrumb" class="text-sm text-gray-500 dark:text-gray-400">
            <ol class="flex items-center justify-center space-x-2">
                <li><a href="{{ site_url() }}" class="hover:underline hover:text-blue-600">Beranda</a></li>
                <li><span class="mx-2">/</span></li>
                <li aria-current="page" class="font-medium text-gray-700 dark:text-gray-300">{{ $judul_kategori['kategori'] ?? 'Kategori' }}</li>
            </ol>
        </nav>
        
        <div class="flex items-center mt-6">
            <div class="flex-grow border-t border-gray-300 dark:border-gray-700"></div>
            <h1 class="flex-shrink px-4 text-2xl lg:text-3xl font-bold uppercase">
                {{ $judul_kategori['kategori'] ?? 'Arsip Artikel' }}
            </h1>
            <div class="flex-grow border-t border-gray-300 dark:border-gray-700"></div>
        </div>
    </div>

    @if ($artikel->isNotEmpty())
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach ($artikel as $post)
                @include('theme::partials.artikel.list', ['post' => $post])
            @endforeach
        </div>
        <div class="mt-8">
            @include('theme::commons.paging')
        </div>
    @else
        @include('theme::partials.artikel.empty', ['title' => $judul_kategori['kategori'] ?? 'Kategori Ini'])
    @endif
</div>
@endsection