<div class="box box-primary box-solid">

    <div class="bg-green-600 flex items-center justify-center py-3 px-6 mb-1">
        <h3 class="text-md font-semibold text-white text-center">
           <a href="{{ site_url('first/gallery') }}"><i class="fas fa-camera mr-1"></i>{{ strtoupper($judul_widget) }}</a>
        </h3>
    </div>
    <div class="h-1 bg-green-500 mb-2"></div>

    <div class="box-body grid grid-cols-3 gap-2 flex-wrap">
        @foreach ($w_gal as $data)
            @if (is_file(LOKASI_GALERI . 'sedang_' . $data['gambar']))
                <a href='{{ site_url("first/sub_gallery/{$data['id']}") }}' title="{{ "Album : {$data['nama']}" }}">
                    <img src="{{ AmbilGaleri($data['gambar'], 'kecil') }}" alt="{{ "Album : {$data['nama']}" }}" class="w-full">
                </a>
            @endif
        @endforeach
    </div>
</div>
