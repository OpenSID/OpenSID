<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title><?= $this->setting->login_title . ' ' . ucwords($this->setting->sebutan_desa) . (($header['nama_desa']) ? ' ' . $header['nama_desa'] : '') . get_dynamic_title_page_from_path() ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex">
    <link rel="stylesheet" href="<?= asset('css/login-style.css') ?>" media="screen">
    <link rel="stylesheet" href="<?= asset('css/login-form-elements.css') ?>" media="screen">
    <link rel="stylesheet" href="<?= asset('bootstrap/css/bootstrap.bar.css') ?>" media="screen">
    <?php if (is_file('desa/pengaturan/siteman/siteman.css')) : ?>
        <link rel='Stylesheet' href="<?= base_url('desa/pengaturan/siteman/siteman.css') ?>">
    <?php endif ?>
    <link rel="shortcut icon" href="<?= favico_desa() ?>" />

    <style type="text/css">
        body.login {
            background-image: url('<?= $latar_login ?>');
        }
    </style>
    <script src="<?= asset('bootstrap/js/jquery.min.js') ?>"></script>
    <script src="<?= asset('js/jquery.validate.min.js') ?>"></script>
    <script src="<?= asset('js/validasi.js') ?>"></script>
    <script src="<?= asset('js/localization/messages_id.js') ?>"></script>
    <?php require __DIR__ . '/head_tags.php' ?>
</head>

<body class="login">
    <div class="top-content">
        <div class="inner-bg">
            <div class="container">
                <div class="row">
                    <div class="col-sm-4 col-sm-offset-4 form-box">
                        <div class="form-top">
                            <a href="<?= site_url() ?>"><img src="<?= gambar_desa($header['logo']) ?>" alt="<?= $header['nama_desa'] ?>" class="img-responsive" /></a>
                            <div class="login-footer-top">
                                <h1><?= ucwords($this->setting->sebutan_desa) ?> <?= $header['nama_desa'] ?></h1>
                                <h3>
                                    <br /><?= $header['alamat_kantor'] ?><br />Kodepos <?= $header['kode_pos'] ?>
                                    <br /><?= ucwords($this->setting->sebutan_kecamatan) ?> <?= $header['nama_kecamatan'] ?><br /><?= ucwords($this->setting->sebutan_kabupaten) ?> <?= $header['nama_kabupaten'] ?>
                                </h3>
                            </div>
                            <?php if ($notif = $this->session->flashdata('notif')) : ?>
                                <div class="alert alert-warning">
                                    <p><?= $notif ?></p>
                                </div>
                            <?php endif ?>
                        </div>
                        <div class="form-bottom">
                            <form id="validasi" class="login-form" action="<?= site_url('siteman/verifikasi_sandi') ?>" method="post">
                                <div class="form-group">
                                    <input name="email" type="text" placeholder="Email Pengguna" value="<?= $email ?>" class="form-control required" readonly>
                                    <input type="hidden" name="email" value="<?= $email ?>">
                                    <input type="hidden" name="token" value="<?= $token ?>">
                                </div>
                                <div class="form-group">
                                    <input name="password" type="password" placeholder="Password" autocomplete="off" class="form-control required pwdLengthNist">
                                </div>
                                <div class="form-group">
                                    <input name="konfirmasi_password" type="password" placeholder="Konfirmasi Password" autocomplete="off" class="form-control required pwdLengthNist">
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn">Reset Sandi</button>
                                </div>
                            </form>
                            <hr />
                            <div class="login-footer-bottom"><a href="https://github.com/OpenSID/OpenSID" target="_blank">OpenSID</a> <?= AmbilVersi() ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>