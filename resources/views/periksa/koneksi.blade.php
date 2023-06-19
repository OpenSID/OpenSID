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
                width: 450px;
            }
        }
    </style>
</head>

<body class="hold-transition register-page">
    <div class="register-box">
        <div class="register-box-body">
            <h4 class="login-box-msg">
                <i class="fa fa-warning text-danger"></i><b> Koneksi Database Gagal</b>
            </h4>
            <hr>
            <p>Atasi dengan melakukan langkah sebagai berikut:</p>
            <ol style="text-align: justify">
                <li>Pastikan telah membuat database untuk <?= config_item('nama_aplikasi') ?>.</li>
                <li>Buka file <b>{{ LOKASI_CONFIG_DESA . 'database.php' }}</b> menggunakan editor.</li>
                <li>Perbaiki dengan memastikan setiap baris terisi nilai sesuai dengan database di (1).</li>
                <ul>
                    <li>$db['default']['hostname'] = '<span class="red">isi-hostname-database</span>';</li>
                    <li>$db['default']['username'] = '<span class="red">isi-username-database</span>';</li>
                    <li>$db['default']['password'] = '<span class="red">isi-password-database</span>';</li>
                    <li>$db['default']['database'] = '<span class="red">isi-nama-database'</span>;</li>
                </ul>
                <li>Simpan file <b>{{ LOKASI_CONFIG_DESA . 'database.php' }}.</b></li>
                <li>Kembali ke halaman <a href="{{ APP_URL }}">awal</a>.</li>
            </ol>
        </div>
    </div>
</body>

</html>
