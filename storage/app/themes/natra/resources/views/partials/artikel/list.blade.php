@php $abstrak = potong_teks($post['isi'], 550) @endphp
<div class="business_category_left wow fadeInDown">
    <ul class="fashion_catgnav">
        <li>
            <div class="catgimg2_container2">
                <h5 class="catg_titile">
                    <a href="{{ $post->url_slug }}" title="Baca Selengkapnya">{{ $post['judul'] }}</a>
                </h5>
                <div class="post_commentbox">
                    <span class="meta_date">{{ tgl_indo($post['tgl_upload']) }}&nbsp;
                        <i class="fa fa-user"></i>{{ $post['owner'] }}&nbsp;
                        <i class="fa fa-eye"></i>{{ hit($post['hit']) }}&nbsp;
                        <i class="fa fa-comments"></i>
                        {{ $post->jumlah_komentar }}
                        &nbsp;
                    </span>
                </div>
                <a href="{{ $post->url_slug }}" title="Baca Selengkapnya" style="font-weight:bold">
                    @if (is_file(LOKASI_FOTO_ARTIKEL . 'kecil_' . $post['gambar']))
                        <img data-src="{{ AmbilFotoArtikel($post['gambar'], 'sedang') }}" src="{{ asset('images/img-loader.gif') }}" width="300" class="yall_lazy img-fluid img-thumbnail hidden-sm hidden-xs" style="float:left; margin:0 8px 4px 0;" alt="{{ $post['judul'] }}" />
                        <img data-src="{{ AmbilFotoArtikel($post['gambar'], 'sedang') }}" src="{{ asset('images/img-loader.gif') }}" width="100%" class="yall_lazy img-fluid img-thumbnail hidden-lg hidden-md" style="float:left; margin:0 8px 4px 0;" alt="{{ $post['judul'] }}" />
                    @else
                        <img src="{{ theme_asset('images/noimage.png') }}" width="300px" class="img-fluid img-thumbnail hidden-sm hidden-xs" style="float:left; margin:0 8px 4px 0;" alt="{{ $post['judul'] }}" />
                        <img src="{{ theme_asset('images/noimage.png') }}" width="100%" class="img-fluid img-thumbnail hidden-lg hidden-md" style="float:left; margin:0 8px 4px 0;" alt="{{ $post['judul'] }}" />
                    @endif
                </a>
                <div style="text-align: justify;" class="hidden-sm hidden-xs">
                    {!! $abstrak !!} ...
                </div>
            </div>
        </li>
    </ul>
</div>
