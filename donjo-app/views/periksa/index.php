<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Periksa Database</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="<?= asset('bootstrap/css/bootstrap.min.css') ?>">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?= asset('css/font-awesome.min.css') ?>">
    <!-- Ionicons -->
    <link rel="stylesheet" href="<?= asset('bootstrap/css/ionicons.min.css') ?>">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?= asset('css/AdminLTE.min.css') ?>">
    <!-- AdminLTE Skins. Choose a skin from the css/skins folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="<?= asset('css/skins/_all-skins.min.css') ?>">
    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<!-- ADD THE CLASS layout-top-nav TO REMOVE THE SIDEBAR. -->

<body class="hold-transition skin-blue layout-top-nav">
    <div class="wrapper">

        <header class="main-header">
            <nav class="navbar navbar-static-top">
                <div class="container">
                    <div class="navbar-header">
                        <a href="../../index2.html" class="navbar-brand"><b>Open</b>SID</a>
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
                            <i class="fa fa-bars"></i>
                        </button>
                    </div>

                    <!-- Collect the nav links, forms, and other content for toggling -->
                    <div class="collapse navbar-collapse pull-left" id="navbar-collapse">
                    </div>
                    <!-- /.navbar-collapse -->
                    <!-- Navbar Right Menu -->
                    <div class="navbar-custom-menu">
                        <ul class="nav navbar-nav">
                        </ul>
                    </div>
                    <!-- /.navbar-custom-menu -->
                </div>
                <!-- /.container-fluid -->
            </nav>
        </header>
        <!-- Full Width Column -->
        <div class="content-wrapper">
            <div class="container">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>
                        Pemeriksaan Database
                    </h1>
                </section>

                <!-- Main content -->
                <section class="content">
                    <?php if ($this->session->db_error || $masalah) : ?>
                        <div class="callout callout-warning">
                            <h4><?= $this->session->heading ?: 'Ditemukan masalah pada database'?></h4>
                            <p><?= $this->session->message ?></p>
                            <?php if (ENVIRONMENT == 'development') : ?>
                                <pre><?= $this->session->message_query ?></pre>
                                <pre><?= $this->session->message_exception ?></pre>
                            <?php endif ?>
                        </div>
                        <div class="callout callout-info">
                            <h4>Info!</h4>
                            <p>Sepertinya database anda tidak lengkap, yang mungkin disebabkan proses migrasi yang tidak sempurna.</p>
                            <p>Pada halaman ini, didaftarkan masalah database yang terdeksi.</p>
                        </div>
                    <?php endif ?>
                    <div class="box box-default">
                        <div class="box-header with-border">
                            <h3 class="box-title">Daftar Masalah</h3>
                        </div>
                        <div class="panel-body">
                            <?php if (empty($masalah)) : ?>
                                <div class="panel panel-default">
                                    <div class="panel-body">
                                        <p>Masalah ini belum diketahui sebabnya. Harap laporkan kepada OpenDesa untuk dibantu lebih lanjut. Harap periksa berkas logs dan laporkan juga isinya.</p>
                                        <p>Sementara bisa masuk kembali.</p>
                                        <a href="<?= site_url('siteman') ?>" class="btn btn-info" role="button" title="Masuk ke admin">Masuk Lagi</a>
                                    </div>
                                </div>
                            <?php else : ?>
                                <?php if (in_array('kode_kelompok', $masalah)) : ?>
                                    <div class="panel panel-default">
                                        <div class="panel-body">
                                            <strong>Terdeteksi kode kelompok terlalu panjang</strong>
                                            <table class="table">
                                                <tr>
                                                    <th>Kode kelompok terlalu panjang</th>
                                                </tr>
                                                <?php foreach ($kode_panjang as $kode) : ?>
                                                    <tr>
                                                        <td><?= $kode['kode']; ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </table>
                                            <p>Klik tombol Perbaiki untuk memperpendek kode kelompok supaya dapat dibuat unik dengan menambahkan ID di akhir masing-masing kode. Untuk melihat kode yang diubah harap periksa berkas logs.</p>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <?php if (in_array('ref_inventaris_kosong', $masalah)) : ?>
                                    <div class="panel panel-default">
                                        <div class="panel-body">
                                            <strong>Terdeteksi referensi pertanahan dan inventaris kosong</strong>
                                            <p>Klik tombol Perbaiki untuk mengembalikan isi tabel referensi tersebut.</p>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <?php if (in_array('id_cluster_null', $masalah)) : ?>
                                    <div class="panel panel-default">
                                        <div class="panel-body">
                                            <strong>Terdeteksi lokasi keluarga kosong</strong>
                                            <table class="table">
                                                <tr>
                                                    <th>No KK</th>
                                                    <th>Nama Kepala Keluarga</th>
                                                </tr>
                                                <?php foreach ($id_cluster_null as $kel) : ?>
                                                    <tr>
                                                        <td><?= $kel['no_kk']; ?></td>
                                                        <td><?= $kel['nama']; ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </table>
                                            <p>Klik tombol Perbaiki untuk mengubah lokasi keluarga kosong menjadi <strong><?= $wilayah_pertama['wil'] ?></strong>. Untuk melihat keluarga yang diubah harap periksa berkas logs.</p>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <?php if (in_array('nik_ganda', $masalah)) : ?>
                                    <div class="panel panel-default">
                                        <div class="panel-body">
                                            <strong>Terdeteksi NIK ganda</strong>
                                            <table class="table">
                                                <tr>
                                                    <th>NIK</th>
                                                    <th>Ganda</th>
                                                </tr>
                                                <?php foreach ($nik_ganda as $nik) : ?>
                                                    <tr>
                                                        <td><?= $nik['nik']; ?></td>
                                                        <td><?= $nik['jml']; ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </table>
                                            <p>Klik tombol Perbaiki untuk memperbaiki NIK ganda dengan (1) mengubah semua NIK yang bukan numerik menjadi NIK sementara, dan (2) mengubah NIK ganda selain yang pertama menjadi NIK sementara. Untuk melihat NIK yang diubah harap periksa berkas logs.</p>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <?php if (in_array('kk_panjang', $masalah)) : ?>
                                    <div class="panel panel-default">
                                        <div class="panel-body">
                                            <strong>Terdeteksi nomor KK melebihi 16 karakter</strong>
                                            <table class="table">
                                                <tr>
                                                    <th>No KK</th>
                                                    <th>Panjang</th>
                                                </tr>
                                                <?php foreach ($kk_panjang as $kk) : ?>
                                                    <tr>
                                                        <td><?= $kk['no_kk']; ?></td>
                                                        <td><?= $kk['panjang']; ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </table>
                                            <p>Klik tombol Perbaiki untuk memperbaiki dengan mengubah semua nomor KK panjang menjadi KK sementara. Untuk melihat nomor KK yang diubah harap periksa berkas logs.</p>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <?php if (in_array('no_kk_ganda', $masalah)) : ?>
                                    <div class="panel panel-default">
                                        <div class="panel-body">
                                            <strong>Terdeteksi no_kk ganda</strong>
                                            <table class="table">
                                                <tr>
                                                    <th>No KK</th>
                                                    <th>Ganda</th>
                                                </tr>
                                                <?php foreach ($no_kk_ganda as $no_kk) : ?>
                                                    <tr>
                                                        <td><?= $no_kk['no_kk']; ?></td>
                                                        <td><?= $no_kk['jml']; ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </table>
                                            <p>Klik tombol Perbaiki untuk memperbaiki no_kk ganda dengan (1) menambah id ke masing-masing no_kk. Untuk melihat no_kk yang diubah harap periksa berkas logs.</p>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <?php if (in_array('username_user_ganda', $masalah)) : ?>
                                    <div class="panel panel-default">
                                        <div class="panel-body">
                                            <strong>Terdeteksi username user ganda</strong>
                                            <table class="table">
                                                <tr>
                                                    <th>Username</th>
                                                    <th>Ganda</th>
                                                </tr>
                                                <?php foreach ($username_user_ganda as $username) : ?>
                                                    <tr>
                                                        <td><?= $username['username']; ?></td>
                                                        <td><?= $username['jml']; ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </table>
                                            <p>Klik tombol Perbaiki untuk memperbaiki username ganda dengan (1) mengubah username kosong menjadi null, dan (2) menambah id ke masing-masing username. Untuk melihat username yang diubah harap periksa berkas logs.</p>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <?php if (in_array('email_user_ganda', $masalah)) : ?>
                                    <div class="panel panel-default">
                                        <div class="panel-body">
                                            <strong>Terdeteksi email user ganda</strong>
                                            <table class="table">
                                                <tr>
                                                    <th>Email</th>
                                                    <th>Ganda</th>
                                                </tr>
                                                <?php foreach ($email_user_ganda as $email) : ?>
                                                    <tr>
                                                        <td><?= $email['email']; ?></td>
                                                        <td><?= $email['jml']; ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </table>
                                            <p>Klik tombol Perbaiki untuk memperbaiki email ganda dengan (1) mengubah email kosong menjadi null, dan (2) menambah id ke masing-masing email. Untuk melihat email yang diubah harap periksa berkas logs.</p>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <?php if (in_array('email_ganda', $masalah)) : ?>
                                    <div class="panel panel-default">
                                        <div class="panel-body">
                                            <strong>Terdeteksi email penduduk ganda</strong>
                                            <table class="table">
                                                <tr>
                                                    <th>Email</th>
                                                    <th>Ganda</th>
                                                </tr>
                                                <?php foreach ($email_ganda as $email) : ?>
                                                    <tr>
                                                        <td><?= $email['email']; ?></td>
                                                        <td><?= $email['jml']; ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </table>
                                            <p>Klik tombol Perbaiki untuk memperbaiki email ganda dengan (1) mengubah email kosong menjadi null, dan (2) menambah id ke masing-masing email. Untuk melihat email yang diubah harap periksa berkas logs.</p>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <?php if (in_array('tag_id_ganda', $masalah)) : ?>
                                    <div class="panel panel-default">
                                        <div class="panel-body">
                                            <strong>Terdeteksi Tag ID ganda</strong>
                                            <table class="table">
                                                <tr>
                                                    <th>Tag ID</th>
                                                    <th>Ganda</th>
                                                </tr>
                                                <?php foreach ($tag_id_ganda as $tag_id) : ?>
                                                    <tr>
                                                        <td><?= $tag_id['tag_id_card']; ?></td>
                                                        <td><?= $tag_id['jml']; ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </table>
                                            <p>Klik tombol Perbaiki untuk mengosongkan Tag ID ganda, supaya hanya Tag ID yang unik saja yang tertinggal. Untuk melihat Tag ID yang diubah harap periksa berkas logs.</p>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <?php if (in_array('kartu_alamat', $masalah)) : ?>
                                    <div class="panel panel-default">
                                        <div class="panel-body">
                                            <strong>Terdeteksi ada kartu_tempat_lahir atau kartu_alamat berisi null, seharusnya ''</strong>
                                            <p>Klik tombol Perbaiki untuk mengubah nilai null menjadi ''</p>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <?php if (in_array('autoincrement', $masalah)) : ?>
                                    <div class="panel panel-default">
                                        <div class="panel-body">
                                            <strong>Terdeteksi ada tabel yang kehilangan autoincrement</strong>
                                            <p>Klik tombol Perbaiki untuk mengembalikan autoincrement pada semua tabel yang memerlukan</p>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <?php if (in_array('collation', $masalah)) : ?>
                                    <div class="panel panel-default">
                                        <div class="panel-body">
                                            <strong>Terdeteksi collation table bukan <code>utf8_general_ci</code></strong>
                                            <table class="table">
                                                <tr>
                                                    <th>Tabel</th>
                                                    <th>Collation</th>
                                                </tr>
                                                <?php foreach ($collation_table as $value) : ?>
                                                    <tr>
                                                        <td><?= $value['TABLE_NAME']; ?></td>
                                                        <td><?= $value['TABLE_COLLATION']; ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </table>
                                            <p>Klik tombol Perbaiki untuk memperbaiki semua collation table yang tidak sesuai menjadi collation <code>utf8_general_ci</code></p>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <p>Setelah diperbaiki, migrasi akan otomatis diulangi mulai dari versi <?= $migrasi_utk_diulang ?>.</p>
                                <a href="#" data-href="<?= site_url('periksa/perbaiki') ?>" class="btn btn-social btn-flat btn-danger" role="button" title="Perbaiki masalah data" data-toggle="modal" data-target="#confirm-status" data-body="Apakah yakin akan memperbaiki masalah data?"><i class="fa fa fa-wrench"></i>Perbaiki</a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <!-- /.box -->
                </section>
                <!-- /.content -->
            </div>
            <!-- /.container -->
            <?php $this->load->view('global/konfirmasi', ['periksa_data' => true]); ?>
        </div>
        <!-- /.content-wrapper -->
        <footer class="main-footer">
            <div class="container">
                <div class="pull-right hidden-xs">
                    <b>Versi OpenSID</b> v<?= AmbilVersi() ?>
                </div>
                <strong>Hak cipta &copy; 2016-<?= date('Y') ?> <a href="https://opendesa.id">OpenDesa</a>.</strong> Seluruh hak cipta dilindungi.
            </div>
            <!-- /.container -->
        </footer>
    </div>
    <!-- ./wrapper -->

    <!-- jQuery 3 -->
    <script src="<?= asset('bootstrap/js/jquery.min.js') ?>"></script>
    <!-- Bootstrap 3.3.7 -->
    <script src="<?= asset('bootstrap/js/bootstrap.min.js') ?>"></script>
    <!-- SlimScroll -->
    <script src="<?= asset('bootstrap/js/jquery.slimscroll.min.js') ?>"></script>
    <!-- FastClick -->
    <script src="<?= asset('bootstrap/js/fastclick.js') ?>"></script>
    <!-- AdminLTE App -->
    <script src="<?= asset('js/adminlte.min.js') ?>"></script>
    <script type="text/javascript">
        $('#confirm-status').on('show.bs.modal', function(e) {
            $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
            $(this).find('.modal-body').html($(e.relatedTarget).data('body'));
        });
    </script>
</body>

</html>