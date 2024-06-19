<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Beranda</title>

    <link rel="shortcut icon" href="{{ favico_desa() }}" />
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.min.css') }}" />

    <link rel="stylesheet" href="{{ asset('js/sweetalert2/sweetalert2.min.css') }}">
    <link href="{{ asset('buku_tamu/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('buku_tamu/css/screen.css') }}" rel="stylesheet">
    
    <script src="{{ asset('bootstrap/js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('bootstrap/js/bootstrap.min.js') }}"></script>
    
    <link rel="stylesheet" href="{{ asset('css/AdminLTE.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/skins/_all-skins.min.css') }}">
    <link rel="stylesheet" href="{{ asset('bootstrap/css/font-awesome.min.css') }}">

    <style>
        .profil-area {
            padding: 11px;
        }
    
        .box-body table {
            font-size: medium;
        }
    
        .box {
            margin-bottom: 0px;
        }
    </style>
    @stack('css')
</head>

<body>

    <div class="full-container" id="element">

        <!-- Mulai Latar -->
        <div class="bg-image">
            <img src="{{ asset('buku_tamu/images/background.jpg') }}">
            <div class="bgload"></div>
            <div class="bgload bgload2"></div>
            <div class="bgload bgload3"></div>
        </div>
        <!-- Batas Latar -->

        <!-- Mulai Header -->
        <div class="headpage">
            <div class="relhid margin-master difle-l">
                <div class="logo difle-l">
                    <img src="{{ gambar_desa($desa->logo) }}" alt="{{ $desa->nama_desa }}">
                    <div>
                        <h1>{{ strtoupper('Pemerintah ' . setting('sebutan_desa') . ' ' . $desa->nama_desa) }}</h1>
                        <p> {{ strtoupper(setting('sebutan_kecamatan') . ' ' . $desa->nama_kecamatan) }} , {{ strtoupper(setting('sebutan_kabupaten') . ' ' . $desa->nama_kabupaten) }} </p>
                    </div>
                </div>
                <div class="headright difle-r">
                    <div>
                        <div class="datetime"><span id="tanggal"></span><span id="thistime"></span></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Batas Header -->

        <div class="relhid margin-master">
            <div class="grider mainmargin">

                <!-- Mulai Kolom Kiri -->
                <div class="area-title">
                    <div class="profil-area">
                        <div class="box box-solid">
                            <div class="box-body box-line text-center" style="margin-bottom: 10px;">
                                <img class="img-circle my-2" src="{{ AmbilFoto($penduduk->foto, '', $penduduk->id_sex) }}" alt="Foto" width="50%" style="margin-top: 10px;">
                            </div>
                            <div class="box-body" style="height: 218px;">
                                <table class="table">
                                    <tr>
                                        <td width="80px;">Nama</td>
                                        <td>:</td>
                                        <td>{{ $penduduk->nama }}</td>
                                    </tr>
                                    <tr>
                                        <td>No. KK</td>
                                        <td>:</td>
                                        <td>{{ $penduduk->keluarga->no_kk }}</td>
                                    </tr>
                                    <tr>
                                        <td>NIK</td>
                                        <td>:</td>
                                        <td>{{ $penduduk->nik }}</td>
                                    </tr>
                                    <tr>
                                        <td>Alamat</td>
                                        <td>:</td>
                                        <td>{{ $penduduk->alamat_wilayah }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Batas Kolom Kiri -->

                @yield('content')

            </div>
        </div>
    </div>

</body>
<script src="{{ asset('js/adminlte.min.js') }}"></script>
<script src="{{ asset('buku_tamu/js/plugins.bundle.js') }}"></script>

@stack('scripts')

</html>
