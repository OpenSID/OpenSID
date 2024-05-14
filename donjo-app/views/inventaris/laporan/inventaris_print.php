<html>
<head>
    <title>KIB C</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" href="<?= asset('css/report.css') ?>">
    <link rel="shortcut icon" href="<?= favico_desa() ?>"/>
    <!-- TODO: Pindahkan ke external css -->
    <style>
        .textx {
            mso-number-format: "\@";
        }

        td,th {
            font-size: 9pt;
        }

        table#ttd td {
            text-align: center;
            white-space: nowrap;
        }

        .underline {
            text-decoration: underline;
        }

        /* Style berikut untuk unduh excel.
                Cetak mengabaikan dan menggunakan style dari report.css
            */
        table#inventaris {
            border: solid 2px black;
        }

        td.border {
            border: dotted 0.5px gray;
        }

        th.border {
            border: solid 0.5pt gray;
        }

        .pull-left {
            position: relative;
            width: 50%;
            float: left;
        }

        .pull-right {
            position: relative;
            width: 50%;
            float: right;
            text-align: right;
            /* padding-right:20px; */
        }
    </style>
</head>

<body>
    <div id="container">
        <!-- Print Body -->
        <div id="body">
            <div class="" align="center">
                <h3> BUKU INVENTARIS DAN KEKAYAAN DESA
                    <br><?= ($tahun == 1 ? 'Semua Tahun' : 'Tahun ' . $tahun); ?>
                </h3>
                <br>
            </div>
            <div style="padding-bottom: 35px;">
                <div class="pull-left">
                    <?= strtoupper($this->setting->sebutan_desa . ' = ' . $header['nama_desa']) ?><br>
                    <?= strtoupper($this->setting->sebutan_kecamatan . ' = ' . $header['nama_kecamatan']) ?><br>
                    <?= strtoupper($this->setting->sebutan_kabupaten . ' = ' . $header['nama_kabupaten']) ?><br>
                </div>
                <div class="pull-right">
                    KODE LOKASI : _ _ . _ _ . _ _ . _ _ . _ _ . _ _ . _ _ _
                </div>

            </div>
            <br>
            <table id="example" class="list border thick">
                <thead style="background-color:#f9f9f9;">
                    <tr>
                        <th class="text-center" rowspan="3">No</th>
                        <th class="text-center" rowspan="3">Jenis Barang</th>
                        <th class="text-center" colspan="5">Asal barang</th>
                        <th class="text-center" width="40%" rowspan="3">Keterangan</th>

                    </tr>
                    <tr>
                        <th class="text-center" style="text-align:center;" rowspan="2">Dibeli Sendiri</th>
                        <th class="text-center" style="text-align:center;" colspan="3">Bantuan</th>
                        <th class="text-center" style="text-align:center;" rowspan="2">Sumbangan</th>
                    </tr>
                    <tr>
                        <th class="text-center" style="text-align:center;">Pemerintah</th>
                        <th class="text-center" style="text-align:center;">Provinsi</th>
                        <th class="text-center" style="text-align:center;">Kabupaten</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>Asset Tetap Lainnya</td>
                        <td>
                            <?= $cetak_inventaris_asset_pribadi->total ?>
                        </td>
                        <td>
                            <?= $cetak_inventaris_asset_pemerintah->total ?>
                        </td>
                        <td>
                            <?= $cetak_inventaris_asset_provinsi->total ?>
                        </td>
                        <td>
                            <?= $cetak_inventaris_asset_kabupaten->total ?>
                        </td>
                        <td>
                            <?= $cetak_inventaris_asset_sumbangan->total ?>
                        </td>
                        <td>Informasi mengenai aset tetap seperti barang habis pakai contohnya buku-buku.</td>
                    </tr>

                    <tr>
                        <td>2</td>
                        <td>Gedung dan Bangunan</td>
                        <td>
                            <?= $cetak_inventaris_gedung_pribadi->total ?>
                        </td>
                        <td>
                            <?= $cetak_inventaris_gedung_pemerintah->total ?>
                        </td>
                        <td>
                            <?= $cetak_inventaris_gedung_provinsi->total ?>
                        </td>
                        <td>
                            <?= $cetak_inventaris_gedung_kabupaten->total ?>
                        </td>
                        <td>
                            <?= $cetak_inventaris_gedung_sumbangan->total ?>
                        </td>
                        <td>Informasi mengenai gedung dan bangunan yang dimiliki.</td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td>Jalan Irigasi dan Jaringan</td>
                        <td>
                            <?= $cetak_inventaris_jalan_pribadi->total ?>
                        </td>
                        <td>
                            <?= $cetak_inventaris_jalan_pemerintah->total ?>
                        </td>
                        <td>
                            <?= $cetak_inventaris_jalan_provinsi->total ?>
                        </td>
                        <td>
                            <?= $cetak_inventaris_jalan_kabupaten->total ?>
                        </td>
                        <td>
                            <?= $cetak_inventaris_jalan_sumbangan->total ?>
                        </td>
                        <td>Informasi mengenai jaringan, seperti listrik atau Internet.</td>
                    </tr>
                    <tr>
                        <td>4</td>
                        <td>Konstruksi Dalam Pengerjaan</td>
                        <td>
                            <?= $cetak_inventaris_kontruksi_pribadi->total ?>
                        </td>
                        <td>
                            <?= $cetak_inventaris_kontruksi_pemerintah->total ?>
                        </td>
                        <td>
                            <?= $cetak_inventaris_kontruksi_provinsi->total ?>
                        </td>
                        <td>
                            <?= $cetak_inventaris_kontruksi_kabupaten->total ?>
                        </td>
                        <td>
                            <?= $cetak_inventaris_kontruksi_sumbangan->total ?>
                        </td>
                        <td>Informasi mengenai bangunan yang masih dalam pengerjaan.</td>
                    </tr>

                    <tr>
                        <td>5</td>
                        <td>Peralatan dan Mesin</td>
                        <td>
                            <?= $cetak_inventaris_peralatan_pribadi->total ?>
                        </td>
                        <td>
                            <?= $cetak_inventaris_peralatan_pemerintah->total ?>
                        </td>
                        <td>
                            <?= $cetak_inventaris_peralatan_provinsi->total ?>
                        </td>
                        <td>
                            <?= $cetak_inventaris_peralatan_kabupaten->total ?>
                        </td>
                        <td>
                            <?= $cetak_inventaris_peralatan_sumbangan->total ?>
                        </td>
                        <td>Informasi mengenai peralatan dan mesin</td>
                    </tr>

                    <tr>
                        <td>6</td>
                        <td>Tanah Kas Desa</td>
                        <td>
                            <?= $cetak_inventaris_tanah_pribadi->total ?>
                        </td>
                        <td>
                            <?= $cetak_inventaris_tanah_pemerintah->total ?>
                        </td>
                        <td>
                            <?= $cetak_inventaris_tanah_provinsi->total ?>
                        </td>
                        <td>
                            <?= $cetak_inventaris_tanah_kabupaten->total ?>
                        </td>
                        <td>
                            <?= $cetak_inventaris_tanah_sumbangan->total ?>
                        </td>
                        <td>
                            Informasi mengenai segala yang menyangkut dengan tanah
                            (dalam hal ini tanah yang digunakan dalam instansi tersebut).
                        </td>
                    </tr>

                </tbody>
            </table>


            <table id="ttd">
                <tr>
                    <td colspan="14">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="14">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="2" width="10%">&nbsp;</td>
                    <td colspan="3" width="30%"></td>
                    <td colspan="5" width="55%"><span class="underline"><?= strtoupper($this->setting->sebutan_desa . ' ' . $header['nama_desa'] . ', ' . tgl_indo(date('Y m d'))) ?></span></td>
                    <td colspan="5" width="5%">&nbsp;</td>
                </tr>

                <tr>
                    <td colspan="14">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="14">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="2" width="10%">&nbsp;</td>
                    <td colspan="3" width="30%">MENGETAHUI</td>
                    <td colspan="5" width="55%"></td>
                    <td colspan="5" width="5%">&nbsp;</td>
                </tr>

                <tr>
                    <td colspan="2" width="10%">&nbsp;</td>
                    <td colspan="3" width="30%">KEPALA SKPD</td>
                    <td colspan="5" width="55%"><?= strtoupper($pamong['jabatan']) ?></td>
                    <td colspan="5" width="5%">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="2" width="10%">&nbsp;</td>
                    <td colspan="3" width="30%"></td>
                    <td colspan="5" width="55%"></td>
                    <td colspan="5" width="5%">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="14">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="14">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="14">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="2" width="10%">&nbsp;</td>
                    <td colspan="3" width="30%">(......................................................................)</td>
                    <td colspan="5" width="55%">( <?= strtoupper($pamong['nama']) ?>) </td>
                    <td colspan="5" width="5%">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="2" width="10%">&nbsp;</td>
                    <td colspan="3" width="30%">NIP ............................................................</td>
                    <td colspan="5" width="55%"> <?= strtoupper($pamong['pamong_nip']) ?> </td>
                    <td colspan="5" width="5%">&nbsp;</td>
                </tr>
            </table>
        </div>
    </div> <!-- Container -->
</body>

</html>