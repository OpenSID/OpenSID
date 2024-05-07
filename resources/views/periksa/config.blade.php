<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Koneksi Database Gagal</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="robots" content="noindex">
    <link rel="shortcut icon" href="{{ favico_desa() }}" />
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/AdminLTE.css') }}" />
    <style type="text/css">
        body {
            overflow: hidden;
        }

        .red {
            color: red;
        }

        .login-box-msg {
            padding: 10px 0;
        }

        .register-box-body {
            padding: 20px;
            border-radius: 5px;
        }

        @media (min-device-width : 1024px) {
            .register-box {
                width: 650px;
            }
        }
    </style>
</head>

<body class="hold-transition register-page">
    <div class="register-box">
        <div class="register-box-body">
            <h4 class="login-box-msg">
                <i class="fa fa-warning text-danger"></i><b> AppKey Tidak Sesuai</b>
            </h4>
            <hr>
            <ol>
                <li>AppKey pada file <b>{{ DESAPATH . 'app_key' }}</b> adalah <b>{{ $appKey }}</b></li>
                <li>AppKey pada tabel <b>config</b> adalah <b>{{ $appKeyDb }}</b></li>
                <li>Perbarui AppKey (1) sesuai dengan yang ada di tabel config (2).</li>
                <!-- <li>Atau buat desa baru dengan menambah AppKey baru tanpa menghapus AppKey lama.</li> -->
            </ol>
            <hr>
            <div class="text-center">
                <a class="btn btn-primary" href="{{ APP_URL }}">Kembali ke Awal</a>
                <a class="btn btn-danger" href="{{ site_url('koneksi_database/updateKey') }}">Perbarui AppKey</a>
            </div>
        </div>
    </div>
</body>

</html>
