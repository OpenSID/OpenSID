@php
    $url = $post['url_slug'] ?? site_url('artikel/'.buat_slug($post));
    $abstract = potong_teks(strip_tags($post['isi']), 120);
    $image = ($post['gambar'] && is_file(LOKASI_FOTO_ARTIKEL . 'sedang_' . $post['gambar']))
        ? AmbilFotoArtikel($post['gambar'], 'sedang') 
        : theme_asset('images/placeholder.png');

    $time = strtotime($post['tgl_upload']);
    $day = date('d', $time);
    $month_num = date('m', $time);
    $bulan_indo = [
        '01' => 'JAN', '02' => 'PEB', '03' => 'MAR', '04' => 'APR', '05' => 'MEI', '06' => 'JUN',
        '07' => 'JUL', '08' => 'AGU', '09' => 'SEP', '10' => 'OKT', '11' => 'NOP', '12' => 'DES'
    ];
    $month = $bulan_indo[$month_num];
@endphp

<article class="bg-white dark:bg-gray-800 shadow-md border border-gray-100 dark:border-gray-700 overflow-hidden h-full flex flex-col group">
    <div class="relative overflow-hidden aspect-video">
        <div class="absolute top-0 left-0 z-10 bg-teal-500 text-white px-3 py-2 text-center shadow-md">
            <span class="block text-xl font-extrabold leading-none">{{ $day }}</span>
            <span class="block text-[10px] font-bold uppercase tracking-widest mt-0.5">{{ $month }}</span>
        </div>
        
        <a href="{{ $url }}" class="block h-full">
            <img class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" 
                 src="{{ $image }}" 
                 alt="{{ $post['judul'] }}" 
                 loading="lazy">
        </a>
    </div>

    <div class="p-5 flex flex-col flex-grow">
        <h3 class="font-bold text-lg text-gray-800 dark:text-white leading-snug mb-3">
            <a href="{{ $url }}" class="hover:text-teal-600 transition-colors line-clamp-2">
                {{ $post['judul'] }}
            </a>
        </h3>

        <div class="flex flex-wrap items-center gap-4 text-[11px] text-gray-500 dark:text-gray-400 mb-4 border-b border-gray-50 dark:border-gray-700 pb-3">
            <span class="flex items-center">
                <i class="fas fa-calendar-alt mr-1.5 text-teal-500"></i> {{ tgl_indo($post['tgl_upload']) }}
            </span>
            <span class="flex items-center">
                <i class="fas fa-user mr-1.5 text-teal-500"></i> {{ $post['owner'] }}
            </span>
        </div>

        <p class="text-sm text-gray-600 dark:text-gray-300 line-clamp-3 leading-relaxed">
            {{ $abstract }}...
        </p>
    </div>
</article>