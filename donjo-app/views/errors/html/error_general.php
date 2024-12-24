<?php defined('BASEPATH') || exit('No direct script access allowed'); ?>

<?php if (! function_exists('base_url')) : ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Error <?= $status_code ?></title>

        <style type="text/css">
            ::selection {
                background-color: #E13300;
                color: white;
            }

            ::-moz-selection {
                background-color: #E13300;
                color: white;
            }

            body {
                background-color: #fff;
                margin: 40px;
                font: 13px/20px normal Helvetica, Arial, sans-serif;
                color: #4F5155;
            }

            a {
                color: #003399;
                background-color: transparent;
                font-weight: normal;
            }

            h1 {
                color: #444;
                background-color: transparent;
                border-bottom: 1px solid #D0D0D0;
                font-size: 19px;
                font-weight: normal;
                margin: 0 0 14px 0;
                padding: 14px 15px 10px 15px;
            }

            code {
                font-family: Consolas, Monaco, Courier New, Courier, monospace;
                font-size: 12px;
                background-color: #f9f9f9;
                border: 1px solid #D0D0D0;
                color: #002166;
                display: block;
                margin: 14px 0 14px 0;
                padding: 12px 10px 12px 10px;
            }

            #container {
                margin: 10px;
                border: 1px solid #D0D0D0;
                box-shadow: 0 0 8px #D0D0D0;
            }

            p {
                margin: 12px 15px 12px 15px;
            }
        </style>
    </head>

    <body>
        <div id="container">
            <h1><?= $heading; ?></h1>
            <?= $message; ?>
        </div>
    </body>

    </html>
<?php else : ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Error</title>
        <link rel="stylesheet" type="text/css" href="<?= asset('bootstrap/css/bootstrap.min.css') ?>" />
        <link rel="stylesheet" type="text/css" href="<?= asset('css/font-awesome.min.css') ?>" />
        <link rel="stylesheet" type="text/css" href="<?= asset('css/AdminLTE.css') ?>" />
    </head>

    <body>
        <div class="container">
            <div class="error-page">
                <h2 class="headline text-danger"><?= $status_code ?></h2>

                <div class="error-content">
                    <h3><i class="fa fa-warning text-danger"></i> <?= strip_tags($heading); ?></h3>
                    <?php error_log(strip_tags($message)); ?>
                    <p>
                        <?= $message; ?>

                        Versi <?= config_item('nama_aplikasi') . ' ' . AmbilVersi() ?>.

                        <?php if ($status_code >= 500) : ?>
                            <br>
                            <br>
                            Harap laporkan masalah ini, agar kami dapat mencari solusinya dengan melampirkan file log terakhir atau saat masalah ini terjadi.
                            Untuk memperoleh file log ikuti langkah berikut:
                            <ol>
                                <li>Masuk ke modul pengaturan</li>
                                <li>Info sistem</li>
                                <li>Logs</li>
                                <li>Pilih log terakhir atau saat masalah ini terjadi</li>
                                <li>Klik unduh</li>
                            </ol>
                            <br>
                            Untuk sementara Anda dapat kembali ke halaman <a href="<?= APP_URL ?>">awal</a>.
                        <?php endif ?>
                    </p>
                </div>

            </div>
        </div>
    </body>

    </html>
<?php endif ?>