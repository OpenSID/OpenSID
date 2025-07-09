<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>
        {{ setting('login_title') . ' ' . ucwords(setting('sebutan_desa')) . ($desa['nama_desa'] ? ' ' . $desa['nama_desa'] : '') . get_dynamic_title_page_from_path() }}
    </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="shortcut icon" href="{{ favico_desa() }}" />
    <link rel="stylesheet" href="{{ asset('css/login-style.css') }}" media="screen">
    <link rel="stylesheet" href="{{ asset('css/login-form-elements.css') }}" media="screen">
    <link rel="stylesheet" href="{{ asset('css/daftar-form-elements.css') }}" media="screen">
    <link rel="stylesheet" href="{{ asset('css/siteman_mandiri.css') }}" media="screen">
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.bar.css') }}" media="screen">
    <!-- bootstrap datetimepicker -->
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap-datetimepicker.min.css') }}">
    @if (is_file('desa/pengaturan/siteman/siteman_mandiri.css'))
        <link rel="stylesheet" href="{{ base_url('desa/pengaturan/siteman/siteman_mandiri.css') }}">
    @endif
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('bootstrap/css/font-awesome.min.css') }}">
    <!-- Google Font -->
    @if (cek_koneksi_internet())
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    @endif
    <script src="{{ asset('bootstrap/js/jquery.min.js') }}"></script>

    @if ($cek_anjungan)
        <!-- Keyboard Default (Ganti dengan keyboard-dark.min.css untuk tampilan lain)-->
        <link rel="stylesheet" href="{{ asset('css/keyboard.min.css') }}">
        <link rel="stylesheet" href="{{ asset('front/css/mandiri-keyboard.css') }}">
    @endif

    @include('admin.layouts.components.token')

    <style type="text/css">
        body.login {
            background-image: url('{{ default_file(LATAR_LOGIN . setting('latar_login_mandiri'), DEFAULT_LATAR_KEHADIRAN) }}');
        }
    </style>
</head>

<body class="login">
    <div class="top-content">
        <div class="inner-bg">
            <div class="container">
                <div class="row">
                    <div class="col-sm-6 col-sm-offset-4 form-box">
                        <div class="form-top">
                            <a href="{{ base_url('/') }}"><img src="{{ gambar_desa($desa['logo']) }}" alt="Lambang Desa" class="img-responsive" /></a>
                            <div class="login-footer-top">
                                <h1>LAYANAN MANDIRI<br />
                                    {{ ucwords(setting('sebutan_desa')) }} {{ $desa['nama_desa'] }}</h1>
                                <h3>
                                    <br />{{ ucwords(setting('sebutan_kecamatan')) }} {{ $desa['nama_kecamatan'] }}
                                    <br />{{ ucwords(setting('sebutan_kabupaten')) }} {{ $desa['nama_kabupaten'] }}
                                    <br />{{ $desa['alamat_kantor'] }}
                                    <br />Kodepos {{ $desa['kode_pos'] }}
                                    <br /><br />Silakan hubungi operator desa untuk mendapatkan kode PIN anda.
                                    <br /><br /><br />IP Address: {{ request()->ip() }}
                                    <br />ID Pengunjung : <span id="pengunjung"></span>&nbsp;<span><a href="#" class="copy" title="Copy" style="color: white"><i class="fa fa-copy"></i></a></span>
                                    @if ($cek_anjungan)
                                        @if ($cek_anjungan['mac_address'])
                                            <br />Mac Address : {{ $cek_anjungan['mac_address'] }}
                                        @endif
                                        <br />Anjungan Mandiri
                                        {!! jecho($cek_anjungan['keyboard'] == 1, true, ' | Virtual Keyboard : Aktif') !!}
                                    @endif
                                </h3>
                            </div>
                        </div>
                        <div class="form-bottom">

                            @php
                                preg_match('/(\d+)/', $errors->first('email'), $matches);

                                $second = $matches[0] ?? 0;
                            @endphp

                            @if ($errors->any())
                                <div @if (!str_contains($errors->first('email'), 'Terlalu banyak upaya masuk.')) id="notif" @endif class="alert alert-danger">
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
                                <div id="notif" class="alert alert-danger">
                                    <p>{{ $notif }}</p>
                                </div>
                            @endif

                            @yield('content')

                            <div class="login-footer-bottom">
                                <a href="https://github.com/OpenSID/OpenSID" class="content-color-secondary" rel="noopener noreferrer" target="_blank">OpenSID v<?= AmbilVersi() ?></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('admin.layouts.components.konfirmasi_cookie', ['cookie_name' => 'pengunjung'])
    @include('admin.layouts.components.aktifkan_cookie')

    <!-- jQuery 3 -->
    <script src="{{ asset('bootstrap/js/jquery.min.js') }}"></script>
    <!-- Bootstrap 3.3.7 -->
    <script src="{{ asset('bootstrap/js/bootstrap.min.js') }}"></script>
    <!-- bootstrap Moment -->
    <script src="{{ asset('bootstrap/js/moment.min.js') }}"></script>
    <script src="{{ asset('bootstrap/js/moment-timezone.js') }}"></script>
    <script src="{{ asset('bootstrap/js/moment-timezone-with-data.js') }}"></script>
    <!-- bootstrap Date time picker -->
    <script src="{{ asset('bootstrap/js/bootstrap-datetimepicker.min.js') }}"></script>
    <script src="{{ asset('bootstrap/js/id.js') }}"></script>
    <!-- SlimScroll -->
    <script src="{{ asset('bootstrap/js/jquery.slimscroll.min.js') }}"></script>
    <!-- FastClick -->
    <script src="{{ asset('bootstrap/js/fastclick.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('js/adminlte.min.js') }}"></script>
    <!-- Validasi -->
    <script src="{{ asset('js/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('js/validasi.js') }}"></script>
    <script src="{{ asset('js/localization/messages_id.js') }}"></script>

    @if ($cek_anjungan)
        <!-- keyboard widget css & script -->
        <script src="{{ asset('js/jquery.keyboard.min.js') }}"></script>
        <script src="{{ asset('js/jquery.mousewheel.min.js') }}"></script>
        <script src="{{ asset('js/jquery.keyboard.extension-all.min.js') }}"></script>
        <script src="{{ asset('front/js/mandiri-keyboard.js') }}"></script>
    @endif
    <script src="{{ asset('js/id_browser.js') }}"></script>
    <script>
        function start_countdown() {
            let totalSeconds = {{ $second }};
            const timer = setInterval(function() {
                const minutes = Math.floor(totalSeconds / 60);
                const seconds = totalSeconds % 60;

                if (totalSeconds <= 0) {
                    clearInterval(timer);
                    location.reload();
                } else {
                    document.getElementById("countdown").innerHTML = `Terlalu banyak upaya masuk. Silahkan coba lagi dalam ${minutes} menit ${seconds} detik.`;
                    totalSeconds--;
                }
            }, 1000);
        }

        $(document).ready(function() {
            if ($('#pin').length) {
                $('#pin').focus();
            } else if ($('#tag').length) {
                $('#tag').focus();
            }

            if ($('#countdown').length) {
                start_countdown();
            }

            window.setTimeout(function() {
                $("#notif").fadeTo(500, 0).slideUp(500, function() {
                    $(this).remove();
                });
            }, 5000);
        });
    </script>

    @stack('script')
</body>

</html>
