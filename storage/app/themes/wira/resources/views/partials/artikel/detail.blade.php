@extends('theme::layouts.' . $layout)
@php
    $post = $single_artikel;
    $alt_slug = PREMIUM ? 'artikel' : 'first';
@endphp
@include('theme::commons.asset_highcharts')

@section('content')
    {{-- Breadcrumb --}}
    <nav role="navigation" aria-label="navigation" class="breadcrumb mb-6">
        <ol>
            <li><a href="{{ ci_route() }}">Beranda</a></li>
            <li>
                @if ($post['kategori'])
                    <a href="{{ ci_route("{$alt_slug}.kategori.{$post['kat_slug']}") }}">
                        {{ $post['kategori'] }}
                    </a>
                @else
                    Artikel
                @endif
            </li>
        </ol>
    </nav>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        {{-- Main Image --}}
        @if ($post['gambar'] && is_file(LOKASI_FOTO_ARTIKEL . 'sedang_' . $post['gambar']))
            <div class="w-full h-64 md:h-96 relative">
                <img src="{{ AmbilFotoArtikel($post['gambar'], 'sedang') }}" 
                     alt="{{ $post['judul'] }}" 
                     class="w-full h-full object-cover">
            </div>
        @endif

        <div class="p-6 md:p-8">
            {{-- Category Badge --}}
            @if ($post['kategori'])
                <a href="{{ ci_route("{$alt_slug}.kategori.{$post['kat_slug']}") }}" 
                   class="inline-block px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold mb-4 hover:bg-green-200 transition-colors no-underline">
                    {{ $post['kategori'] }}
                </a>
            @endif

            {{-- Title --}}
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6 leading-tight">
                {{ $post['judul'] }}
            </h1>

            {{-- Meta Info --}}
            <div class="flex flex-wrap items-center gap-6 text-sm text-gray-500 mb-8 pb-8 border-b border-gray-100">
                <div class="flex items-center gap-2">
                    <i class="fas fa-user-circle text-lg text-gray-400"></i>
                    <span class="font-medium text-gray-900">{{ $post['owner'] }}</span>
                    <i class="fas fa-check-circle text-green-500"></i>
                </div>
                <div class="flex items-center gap-2">
                    <i class="far fa-calendar-alt text-gray-400"></i>
                    <span>{{ $post['tgl_upload_local'] }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <i class="far fa-eye text-gray-400"></i>
                    <span>{{ hit($post['hit']) }} Views</span>
                </div>
            </div>

            {{-- Content Body --}}
            <div class="prose prose-green max-w-none text-gray-700 leading-relaxed">
                {!! $post['isi'] !!}
            </div>

            {{-- Additional Images Grid --}}
            @if ($post['gambar1'] || $post['gambar2'] || $post['gambar3'])
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-8">
                    @for ($i = 1; $i <= 3; $i++)
                        @if ($post['gambar' . $i] && is_file(LOKASI_FOTO_ARTIKEL . 'sedang_' . $post['gambar' . $i]))
                            <a href="{{ AmbilFotoArtikel($post['gambar' . $i], 'sedang') }}" data-fancybox="gallery" class="block group relative rounded-lg overflow-hidden h-64">
                                <img src="{{ AmbilFotoArtikel($post['gambar' . $i], 'sedang') }}" 
                                     alt="Lampiran {{ $i }}" 
                                     class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                            </a>
                        @endif
                    @endfor
                </div>
            @endif

            {{-- Attachment Card --}}
            @if ($post['dokumen'])
                <div class="mt-8 p-4 bg-green-50 border border-green-100 rounded-lg flex items-start sm:items-center gap-4 group hover:bg-green-100 transition-colors duration-300">
                    <div class="bg-white p-2 rounded-lg shadow-sm text-green-600">
                        <i class="fas fa-file-alt text-xl"></i>
                    </div>
                    <div class="flex-grow">
                        <h4 class="text-sm font-semibold text-gray-900">Dokumen Lampiran</h4>
                        <p class="text-xs text-gray-600 line-clamp-1">{{ $post['dokumen'] }}</p>
                    </div>
                    <a href="{{ ci_route('first.unduh_dokumen_artikel', $post['id']) }}" 
                       class="px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-all flex items-center gap-2 whitespace-nowrap no-underline">
                        <i class="fas fa-download"></i> Unduh
                    </a>
                </div>
            @endif
        </div>
    </div>

    {{-- Comments Section --}}
    <div class="mt-8">
        @include('theme::partials.artikel.comment')
    </div>
@endsection