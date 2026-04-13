@php
    $url = $post->url_slug;
    $abstract = potong_teks(strip_tags($post['isi']), 300);
    $image = $post['gambar'] && is_file(LOKASI_FOTO_ARTIKEL . 'sedang_' . $post['gambar']) 
        ? AmbilFotoArtikel($post['gambar'], 'sedang') 
        : gambar_desa($desa['logo']);
@endphp

<div class="border border-gray-200 rounded-lg overflow-hidden hover:shadow-lg transition-shadow h-100 flex flex-col">
    <img src="{{ $image }}" alt="{{ $post['judul'] }}" 
         class="w-full h-48 object-cover flex-shrink-0">
    <div class="p-4 flex flex-col flex-grow">
        <a href="{{ $url }}" >
            <h3 class="font-bold mb-2 leading-tight">
                {{ potong_teks($post['judul'], 80) }}{{ strlen($post['judul']) > 80 ? '...' : '' }}
            </h3>
        </a>
        <p class="text-sm text-gray-600 mb-4 flex-grow">
            {!! potong_teks(html_entity_decode($abstract), 100) !!}{{ strlen($abstract) > 100 ? '...' : '' }}
        </p>
        
        <div class="flex justify-between items-center mt-auto">
            <span class="text-xs text-gray-500">{{ tgl_indo($post['tgl_upload']) }}</span>
            <a href="{{ $url }}" 
                class="inline-block bg-green-600 text-white px-3 py-1 rounded text-xs hover:bg-green-700 transition-colors">
                Lihat Detail 
            </a>
        </div>
        <span class="text-xs text-gray-500"> {{ $post['owner'] }}</span>
    </div>
</div>