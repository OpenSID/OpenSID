<div class="box box-primary box-solid">
    <div class="box-header">
        <h3 class="box-title">
            <a href="{{ site_url('first/gallery') }}"><i class="fas fa-camera mr-1"></i>{{ $judul_widget }}</a>
        </h3>
    </div>
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
