@php defined('BASEPATH') || exit('No direct script access allowed'); @endphp

@if (!is_null($transparansi))
    @include('theme::partials.apbdesa-tema', $transparansi)
@endif

@if (theme_config('statistik_desa'))
    <div class="col-md-12" align="center">
        <h2>Statistik {{ ucwords(setting('sebutan_desa')) }}</h2>
        <hr>
        <div class="col-md-6">
            <a href="{{ site_url('data-wilayah') }}"><img alt="Statistik Wilayah" width="30%" src="{{ theme_asset('images/statistik_wil.png') }}" /></a>
            <a href="{{ site_url('data-statistik/pendidikan-dalam-kk') }}"><img alt="Statistik Pendidikan Dalam Kartu Keluarga" width="30%" src="{{ theme_asset('images/statistik_pend.png') }}" /></a>
            <a href="{{ site_url('data-statistik/pekerjaan') }}"><img alt="Statistik Pekerjaan" width="30%" src="{{ theme_asset('images/statistik_pekerjaan.png') }}" /></a>
            <hr>
        </div>
        <div class="col-md-6">
            <a href="{{ site_url('data-statistik/agama') }}"><img alt="Statistik Agama" width="30%" src="{{ theme_asset('images/statistik_agama.png') }}" /></a>
            <a href="{{ site_url('data-statistik/jenis-kelamin') }}"><img alt="Statistik Jenis Kelamin" width="30%" src="{{ theme_asset('images/statistik_kelamin.png') }}" /></a>
            <a href="{{ site_url('data-statistik/rentang-umur') }}"><img alt="Statistik Umur" width="30%" src="{{ theme_asset('images/statistik_umur.png') }}" /></a>
            <hr>
        </div>
    </div>
@endif

<div class="footer_top">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-4">
                <div class="single_footer_top wow fadeInRight">
                    <h2>{{ ucwords(setting('sebutan_desa')) }} {{ ucwords($desa['nama_desa']) }}</h2>
                    <p>{{ $desa['alamat_kantor'] }}<br>{{ ucwords(setting('sebutan_kecamatan') . ' ' . $desa['nama_kecamatan']) }} {{ ucwords(setting('sebutan_kabupaten') . ' ' . $desa['nama_kabupaten']) }} Provinsi {{ $desa['nama_propinsi'] }} Kode Pos {{ $desa['kode_pos'] }}</p>
                    <p>
                        @if (!empty($desa['email_desa']))
                            Email: {{ $desa['email_desa'] }}
                        @endif
                        <br />
                        @if (!empty($desa['telepon']))
                            Telp: {{ $desa['telepon'] }}
                        @endif
                    </p>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4">
                <div class="single_footer_top wow fadeInDown">
                    <h2>Kategori</h2>
                    <ul class="labels_nav">
                        @foreach ($menu_kiri as $data)
                            <li><a href="{{ site_url('artikel/kategori/' . $data['slug']) }}">{{ $data['kategori'] }}</a></li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4">
                <div class="single_footer_top wow fadeInRight">
                    @if (setting('tte'))
                        <img src="{{ asset('assets/images/bsre.png?v', false) }}" alt="Bsre" class="img-responsive" style="width: 185px;" />
                    @endif
                    @foreach ($sosmed as $data)
                        @if (!empty($data['link']))
                            <a href="{{ $data['link'] }}" rel="noopener noreferrer" target="_blank">
                                <img src="{{ $data['icon'] }}" alt="{{ $data['nama'] }}" style="width:50px;height:50px;" />
                            </a>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
