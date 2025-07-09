@extends('theme::layouts.right-sidebar')
@php
    $title = !empty($judul_kategori) ? $judul_kategori : 'Artikel Terkini';
    $slug = 'terkini';
    if (is_array($title)) {
        $slug = $title['slug'];
        $title = $title['kategori'];
    }
@endphp
@section('content')
    <!-- Tampilkan slider hanya di halaman awal. Tidak tampil pada daftar artikel di halaman kategori atau halaman selanjutnya serta halaman hasil pencarian -->
    @if (empty($cari) && count($slider_gambar ?? []) > 0 && request()->segment(2) != 'kategori' && (request()->segment(2) !== 'index' && request()->segment(1) !== 'index'))
        @include('theme::partials.slider')
    @endif

    <!-- Judul Kategori / Artikel Terkini -->
    <div class="flex justify-between items-center w-full">
        <h3 class="text-h4 text-primary-200">{{ $title }}</h3>
        <a href="{{ site_url('arsip') }}" class="text-sm hover:text-primary-100">Indeks <i class="fas fa-chevron-right ml-1"></i></a>
    </div>

    @if (empty($cari) && count($slider_gambar ?? []) > 0 && request()->segment(2) != 'kategori' && (request()->segment(2) !== 'index' && request()->segment(1) !== 'index'))
        @include('theme::partials.headline')
    @endif

    @if ($artikel->count() > 0)
        @foreach ($artikel as $post)
            @include('theme::partials.artikel.list', ['post' => $post])
        @endforeach
        <div class="pagination space-y-1 flex-wrap w-full">
            @include('theme::commons.paging', ['paging_page' => $paging_page])
        </div>
    @else
        @include('theme::partials.artikel.empty', ['title' => $title])
    @endif
@endsection
