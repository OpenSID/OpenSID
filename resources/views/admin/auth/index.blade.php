<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>{{ setting('login_title') . ' ' . ucwords(setting('sebutan_desa')) . ($header['nama_desa'] ? ' ' . $header['nama_desa'] : '') . get_dynamic_title_page_from_path() }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex">
    <link rel="stylesheet" href="{{ asset('css/login-style.css') }}" media="screen">
    <link rel="stylesheet" href="{{ asset('css/login-form-elements.css') }}" media="screen">
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.bar.css') }}" media="screen">
    @if (is_file('desa/pengaturan/siteman/siteman.css'))
        <link rel='stylesheet' href="{{ base_url('desa/pengaturan/siteman/siteman.css') }}">
    @endif
    <link rel="shortcut icon" href="{{ favico_desa() }}" />
    <style type="text/css">
        body.login {
            background-image: url('{{ $latar_login }}');
        }
    </style>
    <script src="{{ asset('bootstrap/js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('js/validasi.js') }}"></script>
    <script src="{{ asset('js/localization/messages_id.js') }}"></script>
    @include('admin.layouts.components.token')
</head>

<body class="login">
    <div class="top-content">
        <div class="inner-bg">
            <div class="container">
                <div class="row">
                    <div class="col-sm-4 col-sm-offset-4 form-box">
                        <div class="form-top">
                            <a href="{{ site_url() }}">
                                <img src="{{ gambar_desa($header['logo']) }}" alt="{{ $header['nama_desa'] }}" class="img-responsive" style="width: 100px;" />
                                @if (setting('tte'))
                                    <img src="{{ $logo_bsre }}" alt="Bsre" class="img-responsive" style="width: 200px;" />
                                @endif
                            </a>
                            <div class="login-footer-top">
                                <h1>{{ ucwords(setting('sebutan_desa')) }} {{ $header['nama_desa'] }}</h1>
                                <h3>
                                    <br />{{ $header['alamat_kantor'] }}<br />Kodepos {{ $header['kode_pos'] }}
                                    <br />{{ ucwords(setting('sebutan_kecamatan')) }} {{ $header['nama_kecamatan'] }}<br />{{ ucwords(setting('sebutan_kabupaten')) }} {{ $header['nama_kabupaten'] }}
                                </h3>
                            </div>
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    @foreach ($errors->all() as $item)
                                        @if (str_contains($item, 'Terlalu banyak upaya masuk.'))
                                            <p id="countdown">{{ $item }}</p>
                                        @else
                                            <p>{{ $item }}</p>
                                        @endif
                                    @endforeach
                                </div>
                            @endif
                            @if ($notif = $ci->session->flashdata('notif'))
                                <div class="alert alert-danger">
                                    <p>{{ $notif }}</p>
                                </div>
                            @endif
                        </div>
                        <div class="form-bottom">

                            @yield('content')

                            <hr style="margin-top: 5px; margin-bottom: 5px;" />
                            <div class="login-footer-bottom"><a href="https://github.com/OpenSID/OpenSID" target="_blank">OpenSID</a> v{{ AmbilVersi() }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @stack('js')

</body>

</html>
