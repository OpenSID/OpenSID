@php
    $layout = 'theme::layouts.full-content';
    $is_homepage = empty($judul_kategori);
    $title = $is_homepage ? 'Artikel Terkini' : ($judul_kategori['kategori'] ?? 'Kategori Artikel');
    $active_gradient = 'from-green-500 to-teal-500';
@endphp

@extends($layout)

@section('content')
<div class="space-y-12 content-wrapper">
    @if ($is_homepage)
    <div class="space-y-8">
        {{-- Sambutan & Aparatur --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 -mt-5 lg:-mt-10 relative z-20">
            @include('theme::partials.home.sambutan')
            @include('theme::partials.home.aparatur')
        </div>

        {{-- Teks Berjalan (Pindah ke sini) --}}
        @include('theme::commons.running_text')
    </div>

    @include('theme::partials.home.info_panel')
    @endif

    <div @if(!$is_homepage) class="bg-[var(--bg-color-card)] p-6 shadow-xl border border-[var(--border-color)] -mt-16 relative z-10" @endif>
        <div class="flex items-center mb-8">
            <h2 class="px-6 py-2 text-sm font-bold text-white uppercase tracking-wider shadow-lg rounded-none"
                style="clip-path: polygon(0 0, 100% 0, 92% 100%, 0% 100%);"
                :class="darkMode ? 'bg-gray-700' : 'bg-gradient-to-r {{ $active_gradient }}'">
                {{ $title }}
            </h2>
            <div class="flex-grow border-t border-gray-300 dark:border-gray-700"></div>
        </div>

        @if ($artikel->isNotEmpty())
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach ($artikel as $post)
            @include('theme::partials.artikel.list', ['post' => $post])
            @endforeach
        </div>

        <div class="mt-8">
            @include('theme::commons.paging', ['paginator' => $artikel])
        </div>
        @else
        @include('theme::partials.artikel.empty', ['title' => $title])
        @endif
    </div>

    @if ($is_homepage)
    @include('theme::partials.home.interactive_circle')
    @include('theme::partials.home.gallery_slider')
    @endif
</div>
@endsection