@php
$url = $post->url_slug;
$abstract = potong_teks(strip_tags($post['isi']), 300);
$image = ($post['gambar'] && is_file(LOKASI_FOTO_ARTIKEL . 'sedang_' . $post['gambar'])) ?
AmbilFotoArtikel($post['gambar'], 'sedang') :
gambar_desa($desa['logo']);
@endphp

<div class="box-def hoverstyle">
  <div class="box-def-inner artikel-list">
    <a href="{{ $url }}">
      <h2>{{ $post["judul"] }}</h2>
    </a>
    <div class="row-custom mlr-min5">
      <div class="artikel-image">
        <div class="image-absolute">
          @if (is_file(LOKASI_FOTO_ARTIKEL."kecil_".$post['gambar']))
          <img src="{{ AmbilFotoArtikel($post['gambar'],'kecil') }}">
          @else
          <img src="{{ theme_asset("images/pengganti.jpg") }}" />
          <div class="small-image"><img src="{{ gambar_desa($desa['logo']) }}" /></div>
          @endif
        </div>
      </div>
      <div class="artikel-title">
        <div class="artikel-meta">
          <div class="meta-item l-flex"><i class="fa fa-calendar"></i>
            <p>{{ tgl_indo($post['tgl_upload']);}}</p>
          </div>
        </div>
        <div class="artikel-meta" style="margin-bottom:5px;">
          <div class="meta-item l-flex"><i class="fa fa-user"></i>
            <p>{{ $post['owner'] }}</p>
          </div>
          <div class="meta-item l-flex"><i class="fa fa-eye"></i>
            <p>{{ hit($post['hit']) }} dibaca</p>
          </div>
          <div class="meta-item l-flex"><i class="fa fa-comment"></i>
            <p>
              {{ $post['jumlah_komentar'] }}
            </p>
          </div>
        </div>
        <p>{!! potong_teks(html_entity_decode($abstract), 100) !!}{{ strlen($abstract) > 100 ?
          '...' : '' }}...</p>
        <a href="{{ $url }}">
          <div class="l-flex">
            <div class="artikel-link l-flex"><i class="fa fa-fast-forward c-flex"></i>Selengkapnya...</div>
          </div>
        </a>
      </div>
    </div>
  </div>
</div>