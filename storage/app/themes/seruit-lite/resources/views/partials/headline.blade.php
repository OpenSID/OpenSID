@if ($headline)
    @php
        $image = $headline['gambar'] && is_file(LOKASI_FOTO_ARTIKEL . 'sedang_' . $headline['gambar']) 
            ? AmbilFotoArtikel($headline['gambar'], 'sedang') 
            : theme_asset('images/placeholder.png');
    @endphp
    <div class="bg-[var(--bg-color-card)] rounded-none shadow-lg overflow-hidden border border-[var(--border-color)] group">
        <div class="grid grid-cols-1 lg:grid-cols-2">
            <a href="{{ $headline->url_slug }}" class="block overflow-hidden">
                <img src="{{ $image }}" alt="{{ $headline['judul'] }}" class="w-full h-64 lg:h-full object-cover transition-transform duration-300 group-hover:scale-105">
            </a>
            <div class="p-6 flex flex-col justify-center">
                <span class="text-sm font-semibold text-blue-600 dark:text-blue-400 uppercase">Berita Utama</span>
                <h2 class="mt-2 text-2xl lg:text-3xl font-bold text-gray-800 dark:text-white">
                    <a href="{{ $headline->url_slug }}" class="hover:text-blue-600 dark:hover:text-blue-400">
                        {{ $headline['judul'] }}
                    </a>
                </h2>
                <p class="mt-4 text-sm text-gray-600 dark:text-gray-300 line-clamp-4">
                    {{ potong_teks(strip_tags($headline['isi']), 250) }}...
                </p>
                <div class="mt-4 text-xs text-gray-500 dark:text-gray-400">
                    <span><i class="fas fa-calendar-alt mr-1"></i> {{ tgl_indo($headline['tgl_upload']) }}</span>
                </div>
            </div>
        </div>
    </div>
@endif