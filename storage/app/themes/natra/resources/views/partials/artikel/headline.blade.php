@php $abstrak_headline = potong_teks($headline['isi'], 550) @endphp
<div class="single_category wow fadeInDown">
    <h2> <span class="bold_line"><span></span></span> <span class="solid_line"></span> <span class="title_text">Berita Utama</span> </h2>
</div>
<div id="headline" class="single_category wow fadeInDown">
    <div class="archive_style_1">
        <div class="business_category_left wow fadeInDown">
            <ul class="fashion_catgnav">
                <li>
                    <div class="catgimg2_container2">
                        <h5 class="catg_titile">
                            <a href="{{ $headline->url_slug }}"> {{ $headline['judul'] }}</a>
                        </h5>
                        <a href="{{ $headline->url_slug }}">
                            @if ($headline['gambar'] != '')
                                @if (is_file(LOKASI_FOTO_ARTIKEL . 'sedang_' . $headline['gambar']))
                                    <img data-src="{{ AmbilFotoArtikel($headline['gambar'], 'sedang') }}" src="{{ asset('images/img-loader.gif') }}" width="300" class="yall_lazy img-fluid img-thumbnail hidden-sm hidden-xs" style="float:left; margin:0 8px 4px 0;" />
                                    <img data-src="{{ AmbilFotoArtikel($headline['gambar'], 'sedang') }}" src="{{ asset('images/img-loader.gif') }}" width="100%" class="yall_lazy img-fluid img-thumbnail hidden-lg hidden-md" style="float:left; margin:0 8px 4px 0;" />
                                @else
                                    <img src="{{ theme_asset('images/noimage.png') }}" width="300px" class="img-fluid img-thumbnail hidden-sm hidden-xs" style="float:left; margin:0 8px 4px 0;" />
                                    <img src="{{ theme_asset('images/noimage.png') }}" width="100%" class="img-fluid img-thumbnail hidden-lg hidden-md" style="float:left; margin:0 8px 4px 0;" />
                                @endif
                            @endif
                        </a>
                        <div style="text-align: justify;" class="hidden-sm hidden-xs">
                            {!! $abstrak_headline !!} ...
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>
