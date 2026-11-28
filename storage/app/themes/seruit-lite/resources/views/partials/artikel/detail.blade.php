@php
    $layout = 'theme::layouts.right-sidebar';
    $post = $single_artikel;
    $active_gradient = 'from-green-500 to-teal-500';
@endphp

@extends($layout)

@section('content')
<div class="bg-white dark:bg-gray-800 p-6 shadow-md border border-gray-100 dark:border-gray-700">
    <article class="prose dark:prose-invert max-w-none">
        
        @if ($post['tipe'] == 'agenda' && isset($detail_agenda))
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 not-prose mb-8 p-5 bg-gray-50 dark:bg-gray-900/50 border-l-4 border-teal-500 shadow-sm">
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 flex flex-shrink-0 items-center justify-center bg-teal-500 text-white rounded-none">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div>
                        <span class="block font-bold text-[10px] uppercase text-gray-400 tracking-widest">Waktu Pelaksanaan</span>
                        <span class="text-sm font-bold text-gray-700 dark:text-gray-200">{{ tgl_indo2($detail_agenda['tgl_agenda']) }}</span>
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 flex flex-shrink-0 items-center justify-center bg-teal-500 text-white rounded-none">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div>
                        <span class="block font-bold text-[10px] uppercase text-gray-400 tracking-widest">Lokasi Kegiatan</span>
                        <span class="text-sm font-bold text-gray-700 dark:text-gray-200">{{ $detail_agenda['lokasi_kegiatan'] }}</span>
                    </div>
                </div>
            </div>
        @endif

        <div class="content-artikel leading-relaxed text-gray-700 dark:text-gray-300">
            {!! $post['isi'] !!}
        </div>

        @php $gambar_tambahan = array_filter([$post['gambar1'], $post['gambar2'], $post['gambar3']]); @endphp
        @if (count($gambar_tambahan) > 0)
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mt-10 not-prose">
                @foreach ($gambar_tambahan as $gbr)
                    @if (is_file(LOKASI_FOTO_ARTIKEL . 'sedang_' . $gbr))
                        <a href="{{ AmbilFotoArtikel($gbr, 'sedang') }}" data-fancybox="gallery" class="block overflow-hidden group">
                            <img src="{{ AmbilFotoArtikel($gbr, 'sedang') }}" class="w-full h-40 object-cover border border-gray-100 dark:border-gray-700 group-hover:scale-105 transition-transform duration-500">
                        </a>
                    @endif
                @endforeach
            </div>
        @endif

        @if ($post['dokumen'] && is_file(LOKASI_DOKUMEN . $post['dokumen']))
            <div class="mt-10 p-5 bg-gray-50 dark:bg-gray-900/30 border border-gray-200 dark:border-gray-700 not-prose">
                <p class="font-bold text-gray-500 dark:text-gray-400 mb-3 text-[10px] uppercase tracking-widest">Dokumen Lampiran</p>
                <a href="{{ ci_route("first.unduh_dokumen_artikel.{$post['id']}") }}" class="inline-flex items-center gap-3 px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white text-sm font-bold transition-colors">
                    <i class="fas fa-file-download"></i> {{ $post['link_dokumen'] ?: $post['dokumen'] }}
                </a>
            </div>
        @endif
    </article>

    <div class="mt-12 border-t border-gray-100 dark:border-gray-700 pt-8">
        @include('theme::partials.comment', ['gradient_class' => $active_gradient])
    </div>
</div>
@endsection