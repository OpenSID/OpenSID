<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Periksa Database |
        {{ setting('admin_title') . ' ' . ucwords(setting('sebutan_desa') . ' ' . $header['nama_desa']) }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="robots" content="noindex">
    <link rel="shortcut icon" href="{{ favico_desa() }}" />
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.min.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{ asset('bootstrap/css/ionicons.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('css/AdminLTE.min.css') }}">
    <!-- AdminLTE Skins. Choose a skin from the css/skins folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="{{ asset('css/skins/_all-skins.min.css') }}">
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('bootstrap/css/select2.min.css') }}">
</head>

<body class="hold-transition skin-blue layout-top-nav">
    <div class="wrapper">

        <header class="main-header">
            <nav class="navbar navbar-static-top">
                <div class="container">
                    <div class="navbar-header">
                        <a href="{{ ci_route('/') }}" class="navbar-brand"><b>Open</b>SID</a>
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                            data-target="#navbar-collapse">
                            <i class="fa fa-bars"></i>
                        </button>
                    </div>

                    <div class="collapse navbar-collapse pull-left" id="navbar-collapse">
                    </div>
                    <div class="navbar-custom-menu">
                        <ul class="nav navbar-nav">
                        </ul>
                    </div>
                </div>
            </nav>
        </header>

        <div class="content-wrapper">
            <div class="container">

                <section class="content-header">
                    <h1>
                        Pemeriksaan Database
                    </h1>
                </section>

                <section class="content">
                    @if (ci_auth()->id != super_admin())
                    <div class="callout callout-warning">
                        <h4>Info!</h4>
                        <p>Periksa database hanya diperbolehkan untuk Super Admin.</p>
                    </div>
                    <div class="text-center">
                        <a href="{{ ci_route('siteman.logout') }}" class="btn btn-sm btn-social btn-danger"
                            role="button" title="Kembali ke Dasbor"><i class="fa fa fa-sign-out"></i>Kembali</a>
                    </div>
                    @else
                    @if ($session->db_error)
                    <div class="callout callout-warning">
                        <h4>{{ $session->heading }}</h4>
                        <p>{!! $session->message !!}</p>
                        @if (ENVIRONMENT === 'development')
                        <pre>{{ $session->message_query }}</pre>
                        <pre>{{ $session->message_exception }}</pre>
                        @endif
                    </div>
                    <div class="callout callout-info">
                        <h4>Info!</h4>
                        <p>Sepertinya database Anda tidak lengkap, yang mungkin disebabkan proses migrasi yang tidak
                            sempurna.</p>
                        <p>Pada halaman ini, didaftarkan masalah database yang terdeksi.</p>
                    </div>
                    @endif

                    <div class="box box-default">
                        <div class="box-header with-border">
                            <h3 class="box-title">Daftar Masalah</h3>
                        </div>
                        <div class="panel-body">

                            @if (empty($masalah))
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <p>Masalah ini belum diketahui sebabnya. Harap laporkan kepada OpenDesa untuk
                                        dibantu lebih lanjut. Harap periksa berkas logs dan laporkan juga isinya.
                                    </p>
                                    <p>Sementara bisa masuk kembali.</p>
                                    <a href="{{ ci_route('siteman') }}" class="btn btn-sm btn-info" role="button"
                                        title="Masuk ke admin">Masuk Lagi</a>
                                </div>
                            </div>
                            @else
                            @if (in_array('kode_kelompok', $masalah))
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <strong>Terdeteksi kode kelompok terlalu panjang</strong>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover">
                                            <tr>
                                                <th>Kode kelompok terlalu panjang</th>
                                            </tr>
                                            @foreach ($kode_panjang as $kode)
                                            <tr>
                                                <td>{{ $kode['kode'] }}</td>
                                            </tr>
                                            @endforeach
                                        </table>
                                    </div>
                                    <p>Klik tombol Perbaiki untuk memperpendek kode kelompok supaya dapat dibuat
                                        unik dengan menambahkan ID di akhir masing-masing kode. Untuk melihat
                                        kode yang diubah harap periksa berkas logs. <br><a href="#"
                                            data-href="{{ ci_route('periksa.perbaikiSebagian', 'kode_kelompok') }}"
                                            class="btn btn-sm btn-social btn-danger" role="button"
                                            title="Perbaiki masalah data" data-toggle="modal"
                                            data-target="#confirm-backup"
                                            data-body="Apakah sudah melakukan backup database/folder desa?"><i
                                                class="fa fa fa-wrench"></i>Perbaiki Data</a></p>
                                </div>
                            </div>
                            @endif

                            @if (in_array('ref_inventaris_kosong', $masalah))
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <strong>Terdeteksi referensi pertanahan dan inventaris kosong</strong>
                                    <p>Klik tombol Perbaiki untuk mengembalikan isi tabel referensi tersebut.
                                        <br><a href="#"
                                            data-href="{{ ci_route('periksa.perbaikiSebagian', 'ref_inventaris_kosong') }}"
                                            class="btn btn-sm btn-social btn-danger" role="button"
                                            title="Perbaiki masalah data" data-toggle="modal"
                                            data-target="#confirm-backup"
                                            data-body="Apakah sudah melakukan backup database/folder desa?"><i
                                                class="fa fa fa-wrench"></i>Perbaiki Data</a>
                                    </p>
                                </div>
                            </div>
                            @endif

                            @if (in_array('id_cluster_null', $masalah))
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <strong>Terdeteksi lokasi keluarga kosong</strong>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover">
                                            <tr>
                                                <th>No KK</th>
                                                <th>Nama Kepala Keluarga</th>
                                            </tr>
                                            @foreach ($id_cluster_null as $kel)
                                            <tr>
                                                <td>{{ $kel['no_kk'] }}</td>
                                                <td>{{ $kel['nama'] }}</td>
                                            </tr>
                                            @endforeach
                                        </table>
                                    </div>
                                    <p>Klik tombol Perbaiki untuk mengubah lokasi keluarga kosong menjadi
                                        <strong>{{ $wilayah_pertama['wil'] }}</strong>. Untuk melihat keluarga
                                        yang diubah harap periksa berkas logs. <br><a href="#"
                                            data-href="{{ ci_route('periksa.perbaikiSebagian', 'id_cluster_null') }}"
                                            class="btn btn-sm btn-social btn-danger" role="button"
                                            title="Perbaiki masalah data" data-toggle="modal"
                                            data-target="#confirm-backup"
                                            data-body="Apakah sudah melakukan backup database/folder desa?"><i
                                                class="fa fa fa-wrench"></i>Perbaiki Data</a>
                                    </p>
                                </div>
                            </div>
                            @endif

                            @if (in_array('nik_ganda', $masalah))
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <strong>Terdeteksi NIK ganda</strong>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover">
                                            <tr>
                                                <th>NIK</th>
                                                <th>Ganda</th>
                                            </tr>
                                            @foreach ($nik_ganda as $nik)
                                            <tr>
                                                <td>{{ $nik['nik'] }}</td>
                                                <td>{{ $nik['jml'] }}</td>
                                            </tr>
                                            @endforeach
                                        </table>
                                    </div>
                                    <p>Klik tombol Perbaiki untuk memperbaiki NIK ganda dengan (1) mengubah
                                        semua NIK yang bukan numerik menjadi NIK sementara, dan (2) mengubah NIK
                                        ganda selain yang pertama menjadi NIK sementara. Untuk melihat NIK yang
                                        diubah harap periksa berkas logs. <br><a href="#"
                                            data-href="{{ ci_route('periksa.perbaikiSebagian', 'nik_ganda') }}"
                                            class="btn btn-sm btn-social btn-danger" role="button"
                                            title="Perbaiki masalah data" data-toggle="modal"
                                            data-target="#confirm-backup"
                                            data-body="Apakah sudah melakukan backup database/folder desa?"><i
                                                class="fa fa fa-wrench"></i>Perbaiki Data</a></p>
                                </div>
                            </div>
                            @endif

                            @if (in_array('kk_panjang', $masalah))
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <strong>Terdeteksi nomor KK melebihi 16 karakter</strong>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover">
                                            <tr>
                                                <th>No KK</th>
                                                <th>Panjang</th>
                                            </tr>
                                            @foreach ($kk_panjang as $kk)
                                            <tr>
                                                <td>{{ $kk['no_kk'] }}</td>
                                                <td>{{ $kk['panjang'] }}</td>
                                            </tr>
                                            @endforeach
                                        </table>
                                    </div>
                                    <p>Klik tombol Perbaiki untuk memperbaiki dengan mengubah semua nomor KK
                                        panjang menjadi KK sementara. Untuk melihat nomor KK yang diubah harap
                                        periksa berkas logs. <br><a href="#"
                                            data-href="{{ ci_route('periksa.perbaikiSebagian', 'kk_panjang') }}"
                                            class="btn btn-sm btn-social btn-danger" role="button"
                                            title="Perbaiki masalah data" data-toggle="modal"
                                            data-target="#confirm-backup"
                                            data-body="Apakah sudah melakukan backup database/folder desa?"><i
                                                class="fa fa fa-wrench"></i>Perbaiki Data</a></p>
                                </div>
                            </div>
                            @endif

                            @if (in_array('no_kk_ganda', $masalah))
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <strong>Terdeteksi no_kk ganda</strong>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover">
                                            <tr>
                                                <th>No KK</th>
                                                <th>Ganda</th>
                                            </tr>
                                            @foreach ($no_kk_ganda as $no_kk)
                                            <tr>
                                                <td>{{ $no_kk['no_kk'] }}</td>
                                                <td>{{ $no_kk['jml'] }}</td>
                                            </tr>
                                            @endforeach
                                        </table>
                                    </div>
                                    <p>Klik tombol Perbaiki untuk memperbaiki no_kk ganda dengan (1) menambah id
                                        ke masing-masing no_kk. Untuk melihat no_kk yang diubah harap periksa
                                        berkas logs. <br><a href="#"
                                            data-href="{{ ci_route('periksa.perbaikiSebagian', 'no_kk_ganda') }}"
                                            class="btn btn-sm btn-social btn-danger" role="button"
                                            title="Perbaiki masalah data" data-toggle="modal"
                                            data-target="#confirm-backup"
                                            data-body="Apakah sudah melakukan backup database/folder desa?"><i
                                                class="fa fa fa-wrench"></i>Perbaiki Data</a>
                                    </p>
                                </div>
                            </div>
                            @endif

                            @if (in_array('username_user_ganda', $masalah))
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <strong>Terdeteksi username user ganda</strong>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover">
                                            <tr>
                                                <th>Username</th>
                                                <th>Ganda</th>
                                            </tr>
                                            @foreach ($username_user_ganda as $username)
                                            <tr>
                                                <td>{{ $username['username'] }}</td>
                                                <td>{{ $username['jml'] }}</td>
                                            </tr>
                                            @endforeach
                                        </table>
                                    </div>
                                    <p>Klik tombol Perbaiki untuk memperbaiki username ganda dengan (1) mengubah
                                        username kosong menjadi null, dan (2) menambah id ke masing-masing
                                        username. Untuk melihat username yang diubah harap periksa berkas logs.
                                        <br><a href="#"
                                            data-href="{{ ci_route('periksa.perbaikiSebagian', 'username_user_ganda') }}"
                                            class="btn btn-sm btn-social btn-danger" role="button"
                                            title="Perbaiki masalah data" data-toggle="modal"
                                            data-target="#confirm-backup"
                                            data-body="Apakah sudah melakukan backup database/folder desa?"><i
                                                class="fa fa fa-wrench"></i>Perbaiki Data</a>
                                    </p>
                                </div>
                            </div>
                            @endif

                            @if (in_array('email_user_ganda', $masalah))
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <strong>Terdeteksi email user ganda</strong>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover">
                                            <tr>
                                                <th>Email</th>
                                                <th>Ganda</th>
                                            </tr>
                                            @foreach ($email_user_ganda as $email)
                                            <tr>
                                                <td>{{ $email['email'] }}</td>
                                                <td>{{ $email['jml'] }}</td>
                                            </tr>
                                            @endforeach
                                        </table>
                                    </div>
                                    <p>Klik tombol Perbaiki untuk memperbaiki email ganda dengan (1) mengubah
                                        email kosong menjadi null, dan (2) menambah id ke masing-masing email.
                                        Untuk melihat email yang diubah harap periksa berkas logs. <br><a href="#"
                                            data-href="{{ ci_route('periksa.perbaikiSebagian', 'email_user_ganda') }}"
                                            class="btn btn-sm btn-social btn-danger" role="button"
                                            title="Perbaiki masalah data" data-toggle="modal"
                                            data-target="#confirm-backup"
                                            data-body="Apakah sudah melakukan backup database/folder desa?"><i
                                                class="fa fa fa-wrench"></i>Perbaiki Data</a></p>
                                </div>
                            </div>
                            @endif

                            @if (in_array('email_ganda', $masalah))
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <strong>Terdeteksi email penduduk ganda</strong>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover">
                                            <tr>
                                                <th>Email</th>
                                                <th>Ganda</th>
                                            </tr>
                                            @foreach ($email_ganda as $email)
                                            <tr>
                                                <td>{{ $email['email'] }}</td>
                                                <td>{{ $email['jml'] }}</td>
                                            </tr>
                                            @endforeach
                                        </table>
                                    </div>
                                    <p>Klik tombol Perbaiki untuk memperbaiki email ganda dengan (1) mengubah
                                        email kosong menjadi null, dan (2) menambah id ke masing-masing email.
                                        Untuk melihat email yang diubah harap periksa berkas logs. <br><a href="#"
                                            data-href="{{ ci_route('periksa.perbaikiSebagian', 'email_ganda') }}"
                                            class="btn btn-sm btn-social btn-danger" role="button"
                                            title="Perbaiki masalah data" data-toggle="modal"
                                            data-target="#confirm-backup"
                                            data-body="Apakah sudah melakukan backup database/folder desa?"><i
                                                class="fa fa fa-wrench"></i>Perbaiki Data</a></p>
                                </div>
                            </div>
                            @endif

                            @if (in_array('tag_id_ganda', $masalah))
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <strong>Terdeteksi Tag ID ganda</strong>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover">
                                            <tr>
                                                <th>Tag ID</th>
                                                <th>Ganda</th>
                                            </tr>
                                            @foreach ($tag_id_ganda as $tag_id)
                                            <tr>
                                                <td>{{ $tag_id['tag_id_card'] }}</td>
                                                <td>{{ $tag_id['jml'] }}</td>
                                            </tr>
                                            @endforeach
                                        </table>
                                    </div>
                                    <p>Klik tombol Perbaiki untuk mengosongkan Tag ID ganda, supaya hanya Tag ID
                                        yang unik saja yang tertinggal. Untuk melihat Tag ID yang diubah harap
                                        periksa berkas logs. <br><a href="#"
                                            data-href="{{ ci_route('periksa.perbaikiSebagian', 'tag_id_ganda') }}"
                                            class="btn btn-sm btn-social btn-danger" role="button"
                                            title="Perbaiki masalah data" data-toggle="modal"
                                            data-target="#confirm-backup"
                                            data-body="Apakah sudah melakukan backup database/folder desa?"><i
                                                class="fa fa fa-wrench"></i>Perbaiki Data</a></p>
                                </div>
                            </div>
                            @endif

                            @if (in_array('kartu_alamat', $masalah))
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <strong>Terdeteksi ada kartu_tempat_lahir atau kartu_alamat berisi null,
                                        seharusnya ''</strong>
                                    <p>Klik tombol Perbaiki untuk mengubah nilai null menjadi '' <br><a href="#"
                                            data-href="{{ ci_route('periksa.perbaikiSebagian', 'kartu_alamat') }}"
                                            class="btn btn-sm btn-social btn-danger" role="button"
                                            title="Perbaiki masalah data" data-toggle="modal"
                                            data-target="#confirm-backup"
                                            data-body="Apakah sudah melakukan backup database/folder desa?"><i
                                                class="fa fa fa-wrench"></i>Perbaiki Data</a></p>
                                </div>
                            </div>
                            @endif

                            @if (in_array('autoincrement', $masalah))
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <strong>Terdeteksi ada tabel yang kehilangan autoincrement</strong>
                                    <p>Klik tombol Perbaiki untuk mengembalikan autoincrement pada semua tabel
                                        yang memerlukan <br><a href="#"
                                            data-href="{{ ci_route('periksa.perbaikiSebagian', 'autoincrement') }}"
                                            class="btn btn-sm btn-social btn-danger" role="button"
                                            title="Perbaiki masalah data" data-toggle="modal"
                                            data-target="#confirm-backup"
                                            data-body="Apakah sudah melakukan backup database/folder desa?"><i
                                                class="fa fa fa-wrench"></i>Perbaiki Data</a></p>
                                </div>
                            </div>
                            @endif

                            @if (in_array('collation', $masalah))
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <strong>Terdeteksi collation table bukan
                                        <code>{{ $collation }}</code></strong>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover">
                                            <tr>
                                                <th>Tabel</th>
                                                <th>Collation</th>
                                            </tr>
                                            @foreach ($collation_table as $value)
                                            <tr>
                                                <td>{{ $value->TABLE_NAME }}</td>
                                                <td>{{ $value->TABLE_COLLATION }}</td>
                                            </tr>
                                            @endforeach
                                        </table>
                                    </div>
                                    <p>Klik tombol Perbaiki untuk memperbaiki semua collation table yang tidak
                                        sesuai menjadi collation <code>{{ $collation }}</code>.<br><a href="#"
                                            data-href="{{ ci_route('periksa.perbaikiSebagian', 'collation') }}"
                                            class="btn btn-sm btn-social btn-danger" role="button"
                                            title="Perbaiki masalah data" data-toggle="modal"
                                            data-target="#confirm-backup"
                                            data-body="Apakah sudah melakukan backup database/folder desa?"><i
                                                class="fa fa fa-wrench"></i>Perbaiki Data</a></p>
                                </div>
                            </div>
                            @endif

                            @if (in_array('zero_date_default_value', $masalah))

                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <strong>Terdeteksi zero date Default Value<code>(0000-00-00 00:00:00)</code>
                                        pada tabel berikut : </strong>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover">
                                            <tr>
                                                <th>Tabel</th>
                                                <th>Kolom</th>
                                            </tr>
                                            @foreach ($zero_date_default_value as $key => $value)
                                            <tr>
                                                <td>{{ $value['table_name'] }}</td>
                                                <td>{{ $value['column_name'] }}</td>
                                            </tr>
                                            @endforeach
                                        </table>
                                    </div>
                                    <p>Klik tombol Perbaiki untuk memperbaiki semua data default table yang
                                        tidak sesuai <code>(0000-00-00 00:00:00)</code>.</code>Untuk melihat
                                        data tanggal yang diubah harap periksa berkas logs. <br><a href="#"
                                            data-href="{{ ci_route('periksa.perbaikiSebagian', 'zero_date_default_value') }}"
                                            class="btn btn-sm btn-social btn-danger" role="button"
                                            title="Perbaiki masalah data" data-toggle="modal"
                                            data-target="#confirm-backup"
                                            data-body="Apakah sudah melakukan backup database/folder desa?"><i
                                                class="fa fa fa-wrench"></i>Perbaiki Data</a></p>
                                </div>
                            </div>
                            @endif

                            @if (in_array('tabel_invalid_date', $masalah))
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <strong>Terdeteksi tanggal tidak sesuai <code>(0000-00-00 00:00:00)</code>
                                        pada tabel berikut : </strong>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover">
                                            <tr>
                                                <th>Tabel</th>
                                            </tr>
                                            @foreach ($tabel_invalid_date as $key => $value)
                                            <tr>
                                                <td>{{ $key }}</td>
                                            </tr>
                                            @endforeach
                                        </table>
                                    </div>
                                    <p>Klik tombol Perbaiki untuk memperbaiki semua data tanggal table yang
                                        tidak sesuai <code>(0000-00-00 00:00:00)</code>.</code>Untuk melihat
                                        data tanggal yang diubah harap periksa berkas logs. <br><a href="#"
                                            data-href="{{ ci_route('periksa.perbaikiSebagian', 'tabel_invalid_date') }}"
                                            class="btn btn-sm btn-social btn-danger" role="button"
                                            title="Perbaiki masalah data" data-toggle="modal"
                                            data-target="#confirm-backup"
                                            data-body="Apakah sudah melakukan backup database/folder desa?"><i
                                                class="fa fa fa-wrench"></i>Perbaiki Data</a></p>
                                </div>
                            </div>
                            @endif

                            @if (in_array('data_jabatan_tidak_ada', $masalah))
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <strong>Terdeteksi ada data jabatan yang tidak tersedia.</strong>
                                    <p>Klik tombol Perbaiki untuk mengembalikan data jabatan yang diperlukan
                                        tersebut. <br><a href="#"
                                            data-href="{{ ci_route('periksa.perbaikiSebagian', 'data_jabatan_tidak_ada') }}"
                                            class="btn btn-sm btn-social btn-danger" role="button"
                                            title="Perbaiki masalah data" data-toggle="modal"
                                            data-target="#confirm-backup"
                                            data-body="Apakah sudah melakukan backup database/folder desa?"><i
                                                class="fa fa fa-wrench"></i>Perbaiki Data</a></p>
                                </div>
                            </div>
                            @endif

                            @if (in_array('penduduk_tanpa_keluarga', $masalah))
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <strong>Terdeteksi penduduk belum tercatat di data keluarga</strong>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover">
                                            <tr>
                                                <th>NIK</th>
                                                <th>Nama</th>
                                            </tr>
                                            @foreach ($penduduk_tanpa_keluarga as $penduduk)
                                            <tr>
                                                <td>{{ $penduduk['nik'] }}</td>
                                                <td>{{ $penduduk['nama'] }}</td>
                                            </tr>
                                            @endforeach
                                        </table>
                                    </div>
                                    <p>Klik tombol Perbaiki Data untuk memperbaiki penduduk yang belum tercatat
                                        keluarganya. Untuk melihat no_kk_sementara yang diubah harap periksa
                                        berkas logs. <br><a href="#"
                                            data-href="{{ ci_route('periksa.perbaikiSebagian', 'penduduk_tanpa_keluarga') }}"
                                            class="btn btn-sm btn-social btn-danger" role="button"
                                            title="Perbaiki masalah data" data-toggle="modal"
                                            data-target="#confirm-backup"
                                            data-body="Apakah sudah melakukan backup database/folder desa?"><i
                                                class="fa fa fa-wrench"></i>Perbaiki Data</a></p>
                                </div>
                            </div>
                            @endif

                            @if (in_array('log_penduduk_tidak_sinkron', $masalah))
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <strong>Terdeteksi log penduduk dan status dasar penduduk tidak sesuai</strong>

                                    {{-- Hapus pembatas col-md-10 --}}
                                    <div id="info-log-penduduk-tidak-sinkron"></div>

                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover">
                                            <thead>
                                                <tr>
                                                    <th>NIK</th>
                                                    <th>Nama</th>
                                                    <th>Kode Peristiwa Log Terakhir</th>
                                                    <th>Status Dasar Saat Ini</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($log_penduduk_tidak_sinkron as $penduduk)
                                                <tr data-log-tidak-sinkron="{{ $penduduk['nik'] }}">
                                                    <td>{{ $penduduk['nik'] }}</td>
                                                    <td>{{ $penduduk['nama'] }}</td>
                                                    <td>{{ \App\Models\LogPenduduk::kodePeristiwaAll($penduduk['kode_peristiwa']) }}</td>
                                                    <td>{{ \App\Enums\StatusDasarEnum::all()[$penduduk['status_dasar']] ?? '-' }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <p>Klik tombol Perbaiki Data untuk memperbaiki log penduduk dan status dasar penduduk tidak sesuai dengan menghapus log penduduk yang terlanjur masuk. <br><a href="#"
                                            data-href="{{ ci_route('periksa.perbaikiSebagian', 'log_penduduk_tidak_sinkron') }}"
                                            class="btn btn-sm btn-social btn-danger" role="button"
                                            title="Perbaiki masalah data" data-toggle="modal"
                                            data-target="#confirm-backup"
                                            data-body="Apakah sudah melakukan backup database/folder desa?"><i
                                                class="fa fa fa-wrench"></i>Perbaiki Data</a>
                                    </p>
                                </div>
                            </div>
                            @endif

                            @if (in_array('log_penduduk_null', $masalah))
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <strong>Terdeteksi log penduduk memiliki kode peristiwa null</strong>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover">
                                            <tr>
                                                <th>NIK</th>
                                                <th>Nama</th>
                                                <th>Kode Peristiwa</th>
                                            </tr>
                                            @foreach ($log_penduduk_null as $penduduk)
                                            <tr>
                                                <td>{{ $penduduk['nik'] }}</td>
                                                <td>{{ $penduduk['nama'] }}</td>
                                                <td>{{ $penduduk['kode_peristiwa'] }}</td>
                                            </tr>
                                            @endforeach
                                        </table>
                                    </div>
                                    <p>Klik tombol Perbaiki Data memperbaiki data, kode peristiwa akan diset default
                                        menjadi 5 (baru pindah masuk). <br><a href="#"
                                            data-href="{{ ci_route('periksa.perbaikiSebagian', 'log_penduduk_null') }}"
                                            class="btn btn-sm btn-social btn-danger" role="button"
                                            title="Perbaiki masalah data" data-toggle="modal"
                                            data-target="#confirm-backup"
                                            data-body="Apakah sudah melakukan backup database/folder desa?"><i
                                                class="fa fa fa-wrench"></i>Perbaiki Data</a></p>
                                </div>
                            </div>
                            @endif

                            @if (in_array('log_penduduk_asing', $masalah))
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <strong>Terdeteksi log penduduk memiliki kode peristiwa yang tidak
                                        terdaftar</strong>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover">
                                            <tr>
                                                <th>NIK</th>
                                                <th>Nama</th>
                                                <th>Kode Peristiwa</th>
                                            </tr>
                                            @foreach ($log_penduduk_asing as $penduduk)
                                            <tr>
                                                <td>{{ $penduduk['nik'] }}</td>
                                                <td>{{ $penduduk['nama'] }}</td>
                                                <td>{{ $penduduk['kode_peristiwa'] }}</td>
                                            </tr>
                                            @endforeach
                                        </table>
                                    </div>
                                    <p>Klik tombol Perbaiki Data memperbaiki data, log akan dihapus. <br><a href="#"
                                            data-href="{{ ci_route('periksa.perbaikiSebagian', 'log_penduduk_asing') }}"
                                            class="btn btn-sm btn-social btn-danger" role="button"
                                            title="Perbaiki masalah data" data-toggle="modal"
                                            data-target="#confirm-backup"
                                            data-body="Apakah sudah melakukan backup database/folder desa?"><i
                                                class="fa fa fa-wrench"></i>Perbaiki Data</a></p>
                                </div>
                            </div>
                            @endif

                            @if (in_array('log_keluarga_bermasalah', $masalah))
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <strong>Terdeteksi keluarga tidak memiliki log keluarga baru</strong>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover">
                                            <tr>
                                                <th>No KK</th>
                                                <th>Alamat</th>
                                            </tr>
                                            @foreach ($log_keluarga_bermasalah as $penduduk)
                                            <tr>
                                                <td>{{ $penduduk['no_kk'] }}</td>
                                                <td>{{ $penduduk['alamat'] }}</td>
                                            </tr>
                                            @endforeach
                                        </table>
                                    </div>
                                    <p>Klik tombol Perbaiki Data memperbaiki data, log keluarga dengan id peristiwa
                                        1 (keluarga baru) akan dibuat otomatis <br><a href="#"
                                            data-href="{{ ci_route('periksa.perbaikiSebagian', 'log_keluarga_bermasalah') }}"
                                            class="btn btn-sm btn-social btn-danger" role="button"
                                            title="Perbaiki masalah data" data-toggle="modal"
                                            data-target="#confirm-backup"
                                            data-body="Apakah sudah melakukan backup database/folder desa?"><i
                                                class="fa fa fa-wrench"></i>Perbaiki Data</a></p>
                                </div>
                            </div>
                            @endif

                            @if (in_array('log_keluarga_ganda', $masalah))
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <strong>Terdeteksi keluarga tidak memiliki log keluarga ganda</strong>
                                    <div class="col-md-10 col-offset-1" id="info-log-keluarga-ganda">
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover">
                                            <tr>
                                                <th>No KK</th>
                                                <th>Alamat</th>
                                                <th>Aksi</th>
                                            </tr>
                                            @foreach ($log_keluarga_ganda as $keluarga)
                                            <tr data-log-keluarga-ganda="{{ $keluarga['id'] }}">
                                                <td>{{ $keluarga['no_kk'] }}</td>
                                                <td>{{ $keluarga['alamat'] }}</td>
                                                <td><button type="button" class="btn btn-sm btn-danger"
                                                        data-title="Data Catatan Peristiwa Keluarga {{ $keluarga['no_kk'] }} / {{ $keluarga['alamat'] }}"
                                                        data-url='periksaLogKeluarga' data-ref='{!! json_encode(['
                                                        keluarga'=> $keluarga]) !!}'
                                                        data-toggle="modal"
                                                        data-target="#modal-kosong"
                                                        data-close-btn-center=0
                                                        ><i class="fa fa-eye"></i> Lihat log</button></td>
                                            </tr>
                                            @endforeach
                                        </table>
                                    </div>
                                </div>
                            </div>
                            @endif

                            @if (in_array('no_anggota_ganda', $masalah))
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <strong>Terdeteksi No Anggota ganda</strong>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover">
                                            <tr>
                                                <th>Ganda</th>
                                                <th>Config ID</th>
                                                <th>ID Kelompok</th>
                                                <th>No Anggota</th>
                                            </tr>
                                            @foreach ($no_anggota_ganda as $no_anggota)
                                            <tr>
                                                <td>{{ $no_anggota->jml }}</td>
                                                <td>{{ $no_anggota->config_id }}</td>
                                                <td>{{ $no_anggota->id_kelompok }}</td>
                                                <td>{{ $no_anggota->no_anggota }}</td>
                                            </tr>
                                            @endforeach
                                        </table>
                                    </div>
                                    <p>Klik tombol Perbaiki untuk memperbaiki no_anggota ganda dengan (1) menambah
                                        id
                                        ke masing-masing no_anggota. Untuk melihat no_anggota yang diubah harap
                                        periksa
                                        berkas logs. <br><a href="#"
                                            data-href="{{ ci_route('periksa.perbaikiSebagian', 'no_anggota_ganda') }}"
                                            class="btn btn-sm btn-social btn-danger" role="button"
                                            title="Perbaiki masalah data" data-toggle="modal"
                                            data-target="#confirm-backup"
                                            data-body="Apakah sudah melakukan backup database/folder desa?"><i
                                                class="fa fa fa-wrench"></i>Perbaiki Data</a>
                                    </p>
                                </div>
                            </div>
                            @endif

                            @if (in_array('klasifikasi_surat_ganda', $masalah))
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <strong>Terdeteksi kode klasifikasi surat ganda</strong>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover">
                                            <tr>
                                                <th>Id</th>
                                                <th>Kode</th>
                                                <th>Nama</th>
                                                <th>Uraian</th>
                                                <th>Aksi</th>
                                            </tr>
                                            @foreach ($klasifikasi_surat_ganda as $klasifikasi)
                                            <tr>
                                                <td>{{ $klasifikasi['id'] }}</td>
                                                <td>{{ $klasifikasi['kode'] }}</td>
                                                <td>{{ $klasifikasi['nama'] }}</td>
                                                <td>{{ $klasifikasi['uraian'] }}</td>
                                                <td><button
                                                        onclick="$.get('periksaKlasifikasiSurat/hapus', {id: {{ $klasifikasi['id'] }}},function(){$(event.target).replaceWith(`<button class='btn btn-sm btn-success'><i class='fa fa-check'></i> Sudah dihapus</button>`)},'json')"
                                                        type="button" class="btn btn-sm btn-danger"><i
                                                            class="fa fa-trash"></i> Hapus </button></td>
                                            </tr>
                                            @endforeach
                                        </table>
                                    </div>
                                </div>
                            </div>
                            @endif
                            
                            @if (in_array('kepala_rtm_ganda', $masalah))
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <strong>Terdeteksi kepala RTM ganda / tidak valid</strong><br><br>
                                    <p>
                                        Sistem mendeteksi data Kepala Rumah Tangga (RTM) yang ganda atau tidak valid.
                                        Data berikut <strong>perlu dihapus</strong> agar tidak mengganggu keakuratan data.
                                    </p>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover">
                                            <tr>
                                                <th>Nomor Rumah Tangga</th>
                                                <th>Kepala Rumah Tangga</th>
                                                <th>Tanggal Terdaftar</th>
                                                <th>Aksi</th>
                                            </tr>
                                            @foreach ($kepala_rtm_ganda as $rtm)
                                            <tr>
                                                <td>{{ $rtm['no_kk'] }}</td>
                                                <td>{{ $rtm['nama_penduduk'] }}</td>
                                                <td>{{ $rtm['tgl_daftar'] }}</td>
                                                <td><button
                                                        onclick="$.get('periksaKepalaRtm/hapus', {id: {{ $rtm['id'] }}},function(){$(event.target).replaceWith(`<button class='btn btn-sm btn-success'><i class='fa fa-check'></i> Sudah dihapus</button>`)},'json')"
                                                        type="button" class="btn btn-sm btn-danger"><i
                                                            class="fa fa-trash"></i> Hapus </button></td>
                                            </tr>
                                            @endforeach
                                        </table>
                                    </div>
                                </div>
                            </div>
                            @endif

                            @if (in_array('tgllahir_null_kosong', $masalah))
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <strong>Terdeteksi tanggal lahir kosong atau null</strong>
                                    <form id="form-tanggallahir" action="{{ ci_route('periksa.tanggallahir') }}"
                                        method="post">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-hover">
                                                <tr>
                                                    <th>Id</th>
                                                    <th>NIK</th>
                                                    <th>Nama</th>
                                                    <th>Tanggal Lahir</th>
                                                </tr>
                                                @foreach ($tgllahir_null_kosong as $tgllahir)
                                                <tr>
                                                    <td>{{ $tgllahir['id'] }}</td>
                                                    <td>{{ $tgllahir['nik'] }}</td>
                                                    <td>{{ $tgllahir['nama'] }}</td>
                                                    <td>
                                                        <input type="hidden" name="id[]" value="{{ $tgllahir['id'] }}">
                                                        <input type="date" class="form-control" name="tanggallahir[]"
                                                            value="{{ $tgllahir['tanggallahir'] }}" required>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </table>
                                        </div>

                                        <button type="submit" class="btn btn-sm btn-social btn-danger" role="button"
                                            title="Perbaiki masalah data">
                                            <i class="fa fa fa-wrench"></i>Perbaiki Data
                                        </button>
                                    </form>
                                </div>
                            </div>
                            @endif

                            @if (in_array('menu_tanpa_parent', $masalah))
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <strong>Terdeteksi submenu tidak memiliki menu utama</strong>
                                    <form id="form-menu-tanpa-parent" action="{{ ci_route('periksa.menu_tanpa_parent') }}"
                                        method="post">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-hover">
                                                <tr>
                                                    <th>Id</th>
                                                    <th>Nama Submenu</th>
                                                </tr>
                                                @foreach ($menu_tanpa_parent as $submenu)
                                                <tr>
                                                    <td>{{ $submenu['id'] }}</td>
                                                    <td>{{ $submenu['nama'] }}</td>
                                                    <td>
                                                        <input type="hidden" name="id[]" value="{{ $submenu['id'] }}">
                                                        <select class="form-control input-sm" name="parrent[]">
                                                            <option value="">Menu Utama</option>
                                                            @foreach(App\Models\Menu::where('parrent', 0)->get() as $menu)
                                                            <option value="{{ $menu->id }}">{{ $menu->nama }}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </table>
                                        </div>

                                        <button type="submit" class="btn btn-sm btn-social btn-danger" role="button"
                                            title="Perbaiki masalah data">
                                            <i class="fa fa fa-wrench"></i>Simpan Perubahan Data
                                        </button>
                                    </form>
                                </div>
                            </div>
                            @endif

                            @php
                            $fields = [
                            'nama', 'nik', 'sex', 'kk_level', 'tempatlahir', 'tanggallahir',
                            'agama_id', 'pendidikan_kk_id', 'pekerjaan_id', 'golongan_darah_id',
                            'status_kawin', 'warganegara_id', 'nama_ayah', 'nama_ibu',
                            'dokumen_pasport', 'dokumen_kitas'
                            ];
                            @endphp

                            @if (in_array('data_null', $masalah))
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <strong>Terdeteksi data Null</strong><br><br>
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <tr>
                                                <th>Id</th>
                                                <th>NIK</th>
                                                <th>Nama</th>
                                                <th>Field Null</th>
                                            </tr>
                                            @foreach ($data_null as $data)
                                            @php
                                            $null_fields = [];
                                            foreach ($fields as $f) {
                                            if (empty($data[$f])) {
                                            $null_fields[] = $f;
                                            }
                                            }
                                            @endphp
                                            @if(count($null_fields))
                                            <tr>
                                                <td>{{ $data['id'] }}</td>
                                                <td>{{ $data['nik'] }}</td>
                                                <td>{{ $data['nama'] }}</td>
                                                <td>{{ implode(', ', $null_fields) }}</td>

                                            </tr>
                                            <tr>
                                                <td colspan="4">
                                                    <form class="form-datanull" action="{{ ci_route('periksa.datanull') }}"
                                                        method="post" class="p-3 mb-3 border rounded bg-light">
                                                        <input type="hidden" name="id" value="{{ $data['id'] }}"><br>
                                                        <div class="row">
                                                            @foreach($null_fields as $index => $nf)
                                                            <div class="col-md-3 mb-3">
                                                                @if($nf === 'nama')
                                                                <label for="nama">Nama Lengkap <code> (Tanpa Gelar) </code>
                                                                </label>
                                                                <input id="nama" name="nama"
                                                                    class="form-control input-sm nama" maxlength="100"
                                                                    type="text" placeholder="Nama Lengkap" value=""></input>
                                                                @elseif(str_contains($nf, 'nik'))
                                                                <label for="nik">Nomor NIK</label>
                                                                <input id="nik" name="nik" class="form-control input-sm nik"
                                                                    type="text" placeholder="Nomor NIK" value=""></input>
                                                                @elseif(str_contains($nf, 'sex'))
                                                                <label for="sex">Jenis Kelamin </label>
                                                                <select class="form-control input-sm" name="sex">
                                                                    <option value="">Jenis Kelamin</option>
                                                                    @foreach(\App\Enums\JenisKelaminEnum::all() as $key =>
                                                                    $label)
                                                                    <option value="{{ $key }}">{{ $label }}</option>
                                                                    @endforeach
                                                                </select>
                                                                @elseif(str_contains($nf, 'kk_level'))
                                                                <label for="kk_level">Hubungan Dalam Keluarga</label>
                                                                <select id="kk_level" class="form-control input-sm select2"
                                                                    name="kk_level">
                                                                    <option value="">Pilih Hubungan Keluarga</option>
                                                                    @foreach(\App\Enums\SHDKEnum::all() as $key => $label)
                                                                    <option value="{{ $key }}">{{ $label }}</option>
                                                                    @endforeach
                                                                </select>
                                                                @elseif(str_contains($nf, 'tempatlahir'))
                                                                <label for="tempatlahir">Tempat Lahir</label>
                                                                <input id="tempatlahir" name="tempatlahir"
                                                                    class="form-control input-sm" maxlength="100"
                                                                    type="text" placeholder="Tempat Lahir" value=""></input>
                                                                @elseif(str_contains($nf, 'tanggallahir'))
                                                                <label for="tanggallahir">Tanggal Lahir</label>
                                                                <div class="input-group input-group-sm date">
                                                                    <div class="input-group-addon">
                                                                        <i class="fa fa-calendar"></i>
                                                                    </div>
                                                                    <input class="form-control input-sm pull-right required"
                                                                        id="tgl_lahir" name="tanggallahir" type="text"
                                                                        value="">
                                                                </div>
                                                                @elseif(str_contains($nf, 'agama_id'))
                                                                <label class="form-label">Agama</label>
                                                                <select class="form-control input-sm" name="agama_id">
                                                                    <option value="">Pilih Agama</option>
                                                                    @foreach (\App\Enums\AgamaEnum::all() as $key => $value)
                                                                    <option value="{{ $key }}">
                                                                        {{ $value }}
                                                                    </option>
                                                                    @endforeach
                                                                </select>
                                                                @elseif(str_contains($nf, 'pendidikan_kk_id'))
                                                                <label for="pendidikan_kk_id">Pendidikan Dalam KK </label>
                                                                <select class="form-control input-sm"
                                                                    name="pendidikan_kk_id">
                                                                    <option value="">Pilih Pendidikan (Dalam KK) </option>
                                                                    @foreach (\App\Enums\PendidikanKKEnum::all() as $key =>
                                                                    $value)
                                                                    <option value="{{ $key }}">
                                                                        {{ $value }}
                                                                    </option>
                                                                    @endforeach
                                                                </select>
                                                                @elseif(str_contains($nf, 'pekerjaan_id'))
                                                                <label for="pekerjaan_id">Pekerjaaan</label>
                                                                <select class="form-control input-sm" name="pekerjaan_id">
                                                                    <option value="">Pilih Pekerjaan</option>
                                                                    @foreach(\App\Enums\PekerjaanEnum::all() as $key =>
                                                                    $label)
                                                                    <option value="{{ $key }}">{{ $label }}</option>
                                                                    @endforeach
                                                                </select>
                                                                @elseif(str_contains($nf, 'golongan_darah_id'))
                                                                <label for="golongan_darah_id">Golongan Darah</label>
                                                                <select class="form-control input-sm"
                                                                    name="golongan_darah_id">
                                                                    <option value="">Pilih Golongan Darah</option>
                                                                    @foreach (\App\Enums\GolonganDarahEnum::all() as $key =>
                                                                    $value)
                                                                    <option value="{{ $key }}">
                                                                        {{ $value }}
                                                                    </option>
                                                                    @endforeach
                                                                </select>
                                                                @elseif(str_contains($nf, 'status_kawin'))
                                                                <label for="status_kawin">Status Perkawinan</label>
                                                                <select class="form-control input-sm" name="status_kawin"
                                                                    id="status_perkawinan">
                                                                    <option value="">Pilih Status Perkawinan</option>
                                                                    @foreach(\App\Enums\StatusKawinEnum::all() as $key =>
                                                                    $label)
                                                                    <option value="{{ $key }}">{{ $label }}</option>
                                                                    @endforeach
                                                                </select>
                                                                @elseif(str_contains($nf, 'warganegara_id'))
                                                                <label for="warganegara_id">Status Warga Negara</label>
                                                                <select class="form-control input-sm required"
                                                                    id="warganegara_id" name="warganegara_id">
                                                                    <option value="">Pilih Warga Negara</option>
                                                                    @foreach (\App\Enums\WargaNegaraEnum::all() as $key =>
                                                                    $value)
                                                                    <option value="{{ $key }}">
                                                                        {{ $value }}
                                                                    </option>
                                                                    @endforeach
                                                                </select>
                                                                @elseif(str_contains($nf, 'nama_ayah'))
                                                                <label for="nama_ayah">Nama Ayah </label>
                                                                <input id="nama_ayah" name="nama_ayah"
                                                                    class="form-control input-sm nama" maxlength="100"
                                                                    type="text" placeholder="Nama Ayah" value=""></input>
                                                                @elseif(str_contains($nf, 'nama_ibu'))
                                                                <label for="nama_ibu">Nama Ibu </label>
                                                                <input id="nama_ibu" name="nama_ibu"
                                                                    class="form-control input-sm nama" maxlength="100"
                                                                    type="text" placeholder="Nama Ibu" value=""></input>
                                                                @elseif(str_contains($nf, 'dokumen_pasport'))
                                                                <label for="dokumen_pasport">Nomor Paspor </label>
                                                                <input id="dokumen_pasport" name="dokumen_pasport"
                                                                    class="form-control input-sm nomor_sk" maxlength="45"
                                                                    type="text" placeholder="Nomor Paspor"
                                                                    value="-"></input>
                                                                @elseif(str_contains($nf, 'dokumen_kitas'))
                                                                <label for="dokumen_kitas">Nomor KITAS/KITAP </label>
                                                                <input id="dokumen_kitas" name="dokumen_kitas"
                                                                    class="form-control input-sm number" maxlength="45"
                                                                    type="text" placeholder="Nomor KITAS/KITAP"
                                                                    value="-"></input>
                                                                @endif
                                                            </div>
                                                            @if(($index + 1) % 4 == 0)
                                                            <div class="w-100"></div> <!-- ganti baris setiap 4 field -->
                                                            @endif
                                                            @endforeach
                                                        </div>
                                                        <br><button type="submit"
                                                            class="btn btn-sm btn-danger">Simpan</button>
                                                    </form><br>
                                                </td>
                                            </tr>
                                            @endif
                                            @endforeach
                                        </table>
                                    </div>
                                </div>
                            </div>
                            @endif

                            @if (in_array('data_cluster', $masalah))
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <strong>Terdeteksi duplikasi data cluster</strong><br><br>
                                        @foreach ($data_cluster as $data)
                                        <table class="table table-bordered">
                                            <tr>
                                                <td colspan="4">
                                                        <form id="form-datacluster" action="{{ ci_route('periksa.datacluster') }}"
                                                            method="post" class="p-3 mb-3 border rounded bg-light">
                                                            <label for="dusun">Pilih nama dusun yang sesuai:</label>
                                                            <div class="row">
                                                                <div class="form-group col-sm-3">
                                                                    <label for="dusun">Dusun</label>
                                                                    <select id="dusun" name="dusun" class="form-control input-sm select2 required" required>
                                                                        <option value="">Pilih Dusun</option>
                                                                        @foreach ($data as $keyDusun => $dusun)
                                                                        <option value="{{ $dusun }}">{{ $dusun }}
                                                                        </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div class="form-group col-sm-3"><br>
                                                                    <button type="submit" class="btn btn-sm btn-danger">Simpan</button>
                                                                </div>
                                                            </div>
                                                        </form><br>
                                                </td>
                                            </tr>
                                        </table>
                                        @endforeach
                                </div>
                            </div>
                            @endif


                            @if (in_array('suplemen_terdata_kosong', $masalah))
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <form id="form-suplemen-terdata" action="{{ ci_route('periksa.suplemen_terdata') }}"
                                        method="post">
                                        <div class="table-responsive">
                                            @foreach ($suplemen_terdata_kosong as $terdataSuplemen)
                                            Terdeteksi Suplemen <strong>{{ $terdataSuplemen[0]['suplemen']['nama'] ?? ''
                                                }}</strong> Sasaran <strong>{{ $terdataSuplemen[0]['sasaran'] ==
                                                App\Models\SuplemenTerdata::PENDUDUK ? 'Penduduk' : 'Keluarga'
                                                }}</strong>
                                            Terdata Kosong
                                            <table class="table table-bordered">
                                                <tr>
                                                    <th>No</th>
                                                    <th>Id Terdata</th>
                                                    <th>Keterangan</th>
                                                    <th>Sasaran Terdata</th>
                                                </tr>
                                                @foreach ($terdataSuplemen as $terdata)
                                                <tr>
                                                    <td>{{ $loop->index + 1 }}</td>
                                                    <td>{{ $terdata['id_terdata'] }}</td>
                                                    <td>{{ $terdata['keterangan'] }}</td>
                                                    <td>
                                                        <select class="form-control input-sm select2 select-terdata"
                                                            onchange=""
                                                            name="suplemen_terdata[{{ $terdata['sasaran'] }}][{{ $terdata['id'] }}]"
                                                            style="width:100%;" data-suplemen="{{ $terdata['id'] }}"
                                                            data-sasaran="{{ $terdata['sasaran'] }}">
                                                            <option value="">-- Cari {{ $terdata['suplemen']['nama'] }}
                                                                --
                                                            </option>
                                                        </select>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </table>
                                            @endforeach
                                        </div>
                                        <button type="submit" class="btn btn-sm btn-social btn-danger" role="button"
                                            title="Perbaiki masalah data">
                                            <i class="fa fa fa-wrench"></i>Perbaiki Data
                                        </button>
                                    </form>
                                </div>
                            </div>
                            @endif

                            @if (in_array('kepala_keluarga_ganda', $masalah))
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <strong>Terdeteksi keluarga memiliki kepala keluarga ganda atau tidak
                                        valid<br></strong>
                                    <hr>

                                    <div id="info-kepala-keluarga-ganda"></div>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover">
                                            <tr>
                                                <th>Nama</th>
                                                <th>NIK</th>
                                                <th>No KK</th>
                                                <th>Aksi</th>
                                            </tr>
                                            @foreach ($kepala_keluarga_ganda as $penduduk)
                                            <tr data-kepala-keluarga-ganda="{{ $penduduk['id'] }}">
                                                <td>{{ $penduduk['nama'] }}</td>
                                                <td>{{ $penduduk['nik'] }}</td>
                                                <td>{{ $penduduk['keluarga']['no_kk'] }}</td>
                                                <td><button type="button" class="btn btn-sm btn-danger"
                                                        data-title="Ubah SHDK Keluarga {{ $penduduk['nik'] }} / {{ $penduduk['nama'] }}"
                                                        data-url='periksaKepalaKeluargaGanda'
                                                        data-ref='{!! json_encode([' id'=> $penduduk['id']]) !!}'
                                                        data-toggle="modal"
                                                        data-target="#modal-kosong"
                                                        data-close-btn-center=0
                                                        ><i class="fa fa-pencil"></i> Ubah SHDK</button></td>
                                            </tr>
                                            @endforeach
                                        </table>
                                    </div>
                                </div>
                            </div>
                            @endif

                            @if (in_array('keluarga_kepala_ganda', $masalah))
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <strong>Terdeteksi kepala keluarga berada pada lebih dari satu keluarga<br></strong>
                                    <hr>
                                                                         <div class="table-responsive">
                                                                            <table class="table table-bordered table-hover">
                                                                                <tr>
                                                                                    <th>Nama</th>
                                                                                    <th>NIK</th>
                                                                                    <th>No KK</th>
                                                                                    <th>Status Kepala Keluarga</th>
                                                                                    <th>Aksi Tambahan</th>
                                                                                </tr>
                                                                                @foreach ($keluarga_kepala_ganda as $item)
                                                                                <tr>
                                                                                    <td>{{ $item['kepala_keluarga']['nama'] }}</td>
                                                                                    <td>{{ $item['kepala_keluarga']['nik'] }}</td>
                                                                                    <td>{{ $item['no_kk'] }}</td>
                                                                                    <td>
                                                                                        @if ($item['kepala_keluarga']['status_dasar'] != 1)
                                                                                            <span class="label label-danger">Tidak Aktif (Meninggal/Pindah/Hilang)</span>
                                                                                        @else
                                                                                            <span class="label label-success">Aktif</span>
                                                                                        @endif
                                                                                    </td>
                                                                                    <td>
                                                                                        @if ($item['kepala_keluarga']['status_dasar'] != 1)
                                                                                            <a href="#"
                                                                                            data-href="{{ ci_route('periksa.lepas_kaitan_kk_lama', $item['id']) }}"
                                                                                            class="btn btn-sm btn-social btn-warning"
                                                                                            title="Lepas Kaitan KK Lama" data-toggle="modal"
                                                                                            data-target="#confirm-backup"
                                                                                            data-body="Tindakan ini akan mengosongkan NIK Kepala Keluarga pada KK No: {{ $item['no_kk'] }}. Ini digunakan untuk kasus di mana kepala keluarga ini sudah tidak aktif (misal: meninggal) dan anggota keluarganya telah membuat KK baru, tetapi kaitan dengan KK lama ini masih terdeteksi. Yakin ingin melanjutkan?">
                                                                                            <i class="fa fa-unlink"></i> Lepas Kaitan
                                                                                        </a>
                                                                                        @endif
                                                                                    </td>
                                                                                </tr>
                                                                                @endforeach
                                                                            </table>
                                                                        </div>
                                                                        <p>Tombol <strong>Perbaiki Data</strong> di bawah ini adalah untuk memperbaiki masalah umum no_anggota ganda. Gunakan tombol <strong>Lepas Kaitan</strong> pada baris spesifik di atas jika masalahnya sesuai dengan skenario kepala keluarga yang sudah tidak aktif. <br><a href="#"
                                                                                data-href="{{ ci_route('periksa.perbaikiSebagian', 'keluarga_kepala_ganda') }}"
                                                                                class="btn btn-sm btn-social btn-danger" role="button"
                                                                                title="Perbaiki masalah data" data-toggle="modal"
                                                                                data-target="#confirm-backup"
                                                                                data-body="Apakah sudah melakukan backup database/folder desa?"><i
                                                                                    class="fa fa fa-wrench"></i>Perbaiki Data</a>
                                                                        </p>                                </div>
                            </div>
                            @endif

                            @if (in_array('nik_kepala_bukan_kepala_keluarga', $masalah))
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <strong>Terdeteksi nik kepala pada keluarga bukan berstatus kepala
                                        keluarga<br></strong>
                                    <hr>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover">
                                            <tr>
                                                <th>Nama</th>
                                                <th>NIK</th>
                                                <th>No KK</th>
                                            </tr>
                                            @foreach ($nik_kepala_bukan_kepala_keluarga as $penduduk)
                                            <tr>
                                                <td>{{ $penduduk['nama'] }}</td>
                                                <td>{{ $penduduk['nik'] }}</td>
                                                <td>{{ $penduduk['keluarga']['no_kk'] }}</td>
                                            </tr>
                                            @endforeach
                                        </table>
                                    </div>
                                    <p>Klik tombol Perbaiki untuk memperbaiki no_anggota ganda dengan (1) menambah id
                                        ke masing-masing no_anggota. Untuk melihat no_anggota yang diubah harap periksa
                                        berkas logs. <br><a href="#"
                                            data-href="{{ ci_route('periksa.perbaikiSebagian', 'nik_kepala_bukan_kepala_keluarga') }}"
                                            class="btn btn-sm btn-social btn-danger" role="button"
                                            title="Perbaiki masalah data" data-toggle="modal"
                                            data-target="#confirm-backup"
                                            data-body="Apakah sudah melakukan backup database/folder desa?"><i
                                                class="fa fa fa-wrench"></i>Perbaiki Data</a>
                                    </p>
                                </div>
                            </div>
                            @endif

                            {{-- Error view tidak ada (loop) --}}
                            @php
                            $viewErrors = [
                            'view_dokumen_hidup_tidak_ada' => 'dokumen_hidup',
                            'view_keluarga_aktif_tidak_ada' => 'keluarga_aktif',
                            'view_master_inventaris_tidak_ada' => 'master_inventaris',
                            'view_penduduk_hidup_tidak_ada' => 'penduduk_hidup',
                            'view_rekap_mutasi_inventaris_tidak_ada' => 'rekap_mutasi_inventaris',
                            ];

                            $viewErrorsToShow = array_intersect_key($viewErrors, array_flip($masalah));
                            @endphp

                            @if ($viewErrorsToShow)
                            <div class="panel panel-danger">
                                <div class="panel-heading"><strong>Error:</strong> View tidak ada ({{
                                    count($viewErrorsToShow) }} item)</div>
                                <div class="panel-body">
                                    <p>View berikut diperlukan untuk fitur tertentu:</p>

                                    <ul class="list-unstyled">
                                        @foreach ($viewErrorsToShow as $viewName)
                                        <li><i class="fa fa-database text-danger"></i> <code>{{ $viewName }}</code></li>
                                        @endforeach
                                    </ul>
                                    <hr>
                                    <form id="form-perbaiki" action="{{ route('periksa.perbaiki.pilihan') }}"
                                        method="post">
                                        @foreach(array_keys($viewErrorsToShow) as $key)
                                        <input type="hidden" name="pilihan[]" value="{{ $key }}">
                                        @endforeach

                                        <button type="button" id="btn-perbaiki" class="btn btn-sm btn-danger"
                                            data-body="Apakah sudah melakukan backup database/folder desa?">
                                            <i class="fa fa-wrench"></i> Perbaiki View
                                        </button>
                                    </form>
                                </div>
                            </div>
                            @endif

                            @includeWhen(in_array('keluarga_tanpa_nik_kepala', $masalah),
                            'periksa.keluarga_tanpa_nik_kepala')
                            @includeWhen(in_array('modul_asing', $masalah), 'periksa.modul_asing')

                            @php
                            $excludePerbaikiSemua = ['klasifikasi_surat_ganda', 'log_keluarga_ganda',
                            'log_penduduk_tidak_sinkron', 'kepala_keluarga_ganda', 'tgllahir_null_kosong',
                            'suplemen_terdata_kosong'];
                            $pengurangMasalah = 0;
                            foreach ($excludePerbaikiSemua as $mandiri) {
                            if (in_array($mandiri, $masalah)) {
                            $pengurangMasalah++;
                            }
                            }
                            $totalMasalah = count($masalah) - $pengurangMasalah;
                            @endphp
                            @if ($totalMasalah)
                            <p>Setelah diperbaiki, migrasi akan otomatis diulangi mulai dari versi
                                {{ $migrasi_utk_diulang }}.</p>
                            <br><a href="#" data-href="{{ ci_route('periksa.perbaiki') }}"
                                class="btn btn-sm btn-social btn-danger" role="button" title="Perbaiki masalah data"
                                data-toggle="modal" data-target="#confirm-backup"
                                data-body="Apakah sudah melakukan backup database/folder desa?"><i
                                    class="fa fa fa-wrench"></i>Perbaiki Semua</a>
                            @endif
                            @endif
                        </div>
                    </div>
                    @endif
                </section>

            </div>

            @include('admin.layouts.components.konfirmasi', ['periksa_data' => true])
            @include('admin.layouts.components.modal_kosong')
        </div>

        <footer class="main-footer">
            <div class="container">
                <div class="pull-right hidden-xs">
                    <b>Versi
                        <?= config_item('nama_aplikasi') ?>
                    </b> v{{ AmbilVersi() }}
                </div>
                <strong>Hak cipta &copy; 2016-{{ date('Y') }} <a href="https://opendesa.id">OpenDesa</a>.</strong>
                Seluruh hak cipta dilindungi.
            </div>
        </footer>
    </div>

    <div class="modal fade" id="confirm-pilihan" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-yellow">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Konfirmasi</h4>
                </div>
                <div class="modal-body" id="confirm-body">
                    <!-- Isi dari data-body -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-sm btn-primary" id="confirm-submit">Ya, Perbaiki</button>

                </div>
            </div>
        </div>
    </div>

    <!-- jQuery 3 -->
    <script src="{{ asset('bootstrap/js/jquery.min.js') }}"></script>
    <!-- Bootstrap 3.3.7 -->
    <script src="{{ asset('bootstrap/js/bootstrap.min.js') }}"></script>
    <!-- SlimScroll -->
    <script src="{{ asset('bootstrap/js/jquery.slimscroll.min.js') }}"></script>
    <!-- FastClick -->
    <script src="{{ asset('bootstrap/js/fastclick.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('js/adminlte.min.js') }}"></script>
    <!-- Select2 -->
    <script src="{{ asset('bootstrap/js/select2.full.min.js') }}"></script>
    {{-- @if (!setting('inspect_element'))
    <script src="{{ asset('js/disabled.min.js') }}"></script>
    @endif --}}
    <script type="text/javascript">
        $('#btn-perbaiki').on('click', function() {
            const confirmText = $(this).data('body') || 'Yakin ingin melanjutkan?';
            $('#confirm-body').text(confirmText);
            $('#confirm-pilihan').modal('show');
        });

        // Submit AJAX setelah konfirmasi
        $('#confirm-submit').on('click', function() {
            let csrfTokenName = '{{ $token_name }}';
            let csrfTokenValue = '{{ $token_value }}';

            const $form = $('#form-perbaiki');
            const url = $form.attr('action');
            const data = $form.serializeArray();

            data.push({
                name: csrfTokenName,
                value: csrfTokenValue
            });

            // Disable tombol agar tidak double submit
            $(this).prop('disabled', true).text('Memproses...');

            $.post(url, data)
                .done(function(response) {
                    alert(response.message || 'Berhasil');
                    location.reload();
                })
                .fail(function(xhr) {
                    const res = xhr.responseJSON || { message: 'Terjadi kesalahan' };
                    alert(res.message);
                })
                .always(function() {
                    $('#confirm-submit').prop('disabled', false).text('Ya, Perbaiki');
                    $('#confirm-pilihan').modal('hide');
                });
        });

        $('#confirm-backup').on('show.bs.modal', function(e) {
            $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
            $(this).find('.modal-body').html($(e.relatedTarget).data('body'));
        });
        $('#modal-kosong').on('show.bs.modal', function(e) {
            let _btn = e.relatedTarget
            let _data = $(_btn).data('ref')
            let _url = $(_btn).data('url')
            let _title = $(_btn).data('title')
            let _btnCloseCenter = $(_btn).data('close-btn-center') ? {
                'text-align': 'center'
            } : {}
            let _modal = $(this)
            $.get(_url, _data, function(data) {
                _modal.find('.modal-body').html(data);
            }, 'html')

            _modal.find('.modal-title').html(_title)
            _modal.find('.modal-footer').css(_btnCloseCenter).html(
                `<button type="button" class="btn btn-social btn-flat btn-danger btn-sm" data-dismiss="modal"><i
                        class="fa fa-sign-out"></i> Tutup</button>
                `
            )
        });

        // kirim dara pada form-tanggallahir menggunakan ajax post
        $('#form-tanggallahir').submit(function(e) {
            e.preventDefault();

            // Ambil csrf token dari Laravel (pastikan blade directives diproses di server)
            let csrfTokenName = '{{ $token_name }}';
            let csrfTokenValue = '{{ $token_value }}';

            // Tambahkan CSRF token ke dalam data form
            let formData = $(this).serializeArray();
            formData.push({
                name: csrfTokenName,
                value: csrfTokenValue
            });

            $.ajax({
                type: 'POST',
                url: $(this).attr('action'),
                data: formData,
                success: function(data) {
                    if (data.status) {
                        alert('Data berhasil diperbarui');
                        location.reload();
                    } else {
                        alert('Data gagal diperbarui');
                    }
                },
                error: function(xhr, status, error) {
                    alert('Data gagal diperbarui');
                }
            });
        });

        $('#form-menu-tanpa-parent').submit(function(e) {
            e.preventDefault();

            // Ambil csrf token dari Laravel (pastikan blade directives diproses di server)
            let csrfTokenName = '{{ $token_name }}';
            let csrfTokenValue = '{{ $token_value }}';

            // Tambahkan CSRF token ke dalam data form
            let formData = $(this).serializeArray();
            formData.push({
                name: csrfTokenName,
                value: csrfTokenValue
            });

            $.ajax({
                type: 'POST',
                url: $(this).attr('action'),
                data: formData,
                success: function(data) {
                    if (data.status) {
                        alert('Data berhasil diperbarui');
                        location.reload();
                    } else {
                        alert('Data gagal diperbarui');
                    }
                },
                error: function(xhr, status, error) {
                    alert('Data gagal diperbarui');
                }
            });
        });

        $('.form-datanull').on('submit', function(e) {
            e.preventDefault();

            let csrfTokenName = '{{ $token_name }}';
            let csrfTokenValue = '{{ $token_value }}';

            let formData = $(this).serializeArray();
            formData.push({
                name: csrfTokenName,
                value: csrfTokenValue
            });

            $.ajax({
                type: 'POST',
                url: $(this).attr('action'),
                data: formData,
                success: function(data) {
                    if (data.status) {
                        alert('Data berhasil diperbarui');
                        location.reload();
                    } else {
                        alert('Data gagal diperbarui');
                    }
                },
                error: function() {
                    alert('Data gagal diperbarui');
                }
            });
        });


        $('#form-datacluster').submit(function(e) {
            e.preventDefault();

            // Ambil csrf token dari Laravel (pastikan blade directives diproses di server)
            let csrfTokenName = '{{ $token_name }}';
            let csrfTokenValue = '{{ $token_value }}';

            // Tambahkan CSRF token ke dalam data form
            let formData = $(this).serializeArray();
            formData.push({
                name: csrfTokenName,
                value: csrfTokenValue
            });

            $.ajax({
                type: 'POST',
                url: $(this).attr('action'),
                data: formData,
                success: function(data) {
                    if (data.status) {
                        alert('Data berhasil diperbarui');
                        location.reload();
                    } else {
                        alert('Data gagal diperbarui');
                    }
                },
                error: function(xhr, status, error) {
                    alert('Data gagal diperbarui');
                }
            });
        });

        $('#form-suplemen-terdata').submit(function(e) {
            e.preventDefault();

            // Ambil csrf token dari Laravel (pastikan blade directives diproses di server)
            let csrfTokenName = '{{ $token_name }}';
            let csrfTokenValue = '{{ $token_value }}';

            // Tambahkan CSRF token ke dalam data form
            let formData = $(this).serializeArray();
            formData.push({
                name: csrfTokenName,
                value: csrfTokenValue
            });

            $.ajax({
                type: 'POST',
                url: $(this).attr('action'),
                data: formData,
                success: function(data) {
                    if (data.status) {
                        alert('Data berhasil diperbarui');
                        location.reload();
                    } else {
                        alert('Data gagal diperbarui');
                    }
                },
                error: function(xhr, status, error) {
                    alert('Data gagal diperbarui');
                }
            });
        });

        $('.select-terdata').select2({
            ajax: {
                url: "{{ ci_route('internal_api.apipenduduksuplemen') }}",
                dataType: 'json',
                data: function(params) {
                    return {
                        q: params.term || '',
                        page: params.page || 1,
                        suplemen: $(this).data('suplemen'),
                        sasaran: $(this).data('sasaran'),
                    };
                },
                cache: true
            },
            placeholder: function() {
                $(this).data('placeholder');
            },
            minimumInputLength: 0,
            allowClear: true,
            escapeMarkup: function(markup) {
                return markup;
            },
        })
    </script>
</body>

</html>