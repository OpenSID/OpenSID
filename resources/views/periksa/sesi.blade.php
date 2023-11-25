<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>
        {{ $setting->admin_title . ' ' . ucwords($setting->sebutan_desa . ' ' . ($desa['nama_desa'] ?? '')) . get_dynamic_title_page_from_path() }}
    </title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="shortcut icon" href="{{ favico_desa() }}" />
    <link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="{{ base_url('rss.xml') }}" />
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.min.css') }}" />
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('bootstrap/css/font-awesome.min.css') }}" />
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{ asset('bootstrap/css/ionicons.min.css') }}" />
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('bootstrap/css/select2.min.css') }}" />
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('css/AdminLTE.min.css') }}" />
    <!-- AdminLTE Skins. -->
    <link rel="stylesheet" href="{{ asset('css/skins/_all-skins.min.css') }}" />
    @stack('css')
</head>

<body class="hold-transition login-page">
    <div class="login-box">
        <div class="login-box-body">
            <center>
                <img src="<?= base_url('assets/files/logo/opensid_logo.png') ?>" width="30%" alt="Instalasi OpenSID"
                    class="img-responsive" />
                <hr>
                <h4><b>Sesi Telah Berakhir</b></h4>
            </center>
            <hr>
            <p align="center">Sesi anda telah berakhir karena terlalu lama tidak beraktifitas. Anda mengakses langsung
                kesebuah halaman yang memerlukan masuk.<br>Anda masuk berulang-ulang melalui tab di browser.</p>
            <hr>
            <div class="form-group">
                <a href="<?= site_url('siteman') ?>" class="btn btn-primary btn-block btn-flat">Masuk Kembali</a>
            </div>
        </div>
    </div>

    <!-- jQuery 3 -->
    <script src="{{ asset('bootstrap/js/jquery.min.js') }}"></script>
    <!-- Bootstrap 3.3.7 -->
    <script src="{{ asset('bootstrap/js/bootstrap.min.js') }}"></script>
    <!-- Slimscroll -->
    <script src="{{ asset('bootstrap/js/jquery.slimscroll.min.js') }}"></script>
    <!-- FastClick -->
    <script src="{{ asset('bootstrap/js/fastclick.js') }}"></script>
    <!-- AdminLTE -->
    <script src="{{ asset('js/adminlte.min.js') }}"></script>
</body>

</html>
