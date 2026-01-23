<?php if (! defined('BASEPATH')) exit('No direct script access allowed');
?>
<style>
    table.disdukcapil {
        width: 100%;
    }

    table.disdukcapil td {
        padding: 1px 1px 1px 3px;
    }

    table.disdukcapil td.padat {
        padding: 0px;
        margin: 0px;
    }

    table.disdukcapil td.kotak {
        border: solid 1px #000000;
    }

    table.disdukcapil td.anggota {
        border-left: solid 1px #000000;
        border-right: solid 1px #000000;
        border-top: dashed 0px #000000;
        border-bottom: dashed 0px #000000;
    }

    table.disdukcapil td.judul {
        border-left: solid 1px #000000;
        border-right: solid 1px #000000;
        border-top: double 1px #000000;
        border-bottom: double 1px #000000;
    }

    table.disdukcapil td.bawah {
        border-left: solid 1px #000000;
        border-right: solid 1px #000000;
        border-top: dashed 1px #000000;
        border-bottom: double 1px #000000;
    }

    table.disdukcapil td.abu {
        background-color: lightgrey;
    }

    table.disdukcapil td.kode {
        background-color: lightgrey;
    }

    table.disdukcapil td.kode div {
        margin: 0px 15px 0px 15px;
        border: solid 1px black;
        background-color: white;
        text-align: center;
    }

    table.disdukcapil td.pakai-padding {
        padding-left: 20px;
        padding-right: 2px;
    }

    table.disdukcapil td.atas {
        text-align: top;
    }

    table.disdukcapil td.kanan {
        text-align: right;
    }

    table.disdukcapil td.tengah {
        text-align: center;
    }

    table.ttd {
        margin-top: 20px;
        width: 100%;
    }

    table.ttd td {
        text-align: center;
    }

    table.ttd td.left {
        text-align: left;
    }

    table.ttd td div {
        display: inline-block;
        width: auto;
        border-bottom: 1px solid black;
        padding-bottom: 3px;
    }
</style>

<page orientation="landscape" format="A3" style="font-size: 8pt">
    <table align="right" style="padding: 5px 20px;">
        <tr>
            <td><strong style="font-size: 16pt;"><i>ASLI</i></strong></td>
        </tr>
    </table>
    <p style="text-align: center; margin-top: -10px; margin-bottom: 0px; padding-bottom: 0px;">
        <strong style="font-size: 12pt; text-decoration: underline;">FORMULIR DATA ISIAN KARTU KELUARGA</strong>
    </p>

    <table class="disdukcapil" style="margin-top: 0px;">
        <col span="48" style="width: 2.0833%;">

        <tr>
            <td colspan="48">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="13" class="left">NAMA KEPALA KELUARGA</td>
            <td>:</td>
            <td colspan="35"><?= $kepala_keluarga['nama']; ?></td>
        </tr>
        <tr>
            <td colspan="13" class="left">ALAMAT</td>
            <td>:</td>
            <td colspan="35"><?= $kepala_keluarga['alamat_wilayah']; ?></td>
        </tr>
        <tr>
            <td colspan="48">&nbsp;</td>
        </tr>
        <tr>
            <td class="kotak" colspan="48">
                <strong>Perhatian:</strong><br />
                1. Harap diisi dengan huruf cetak dan menggunakan tinta hitam<br />
                2. Setelah formulir ini diisi dan ditandatangani, harap diserahkan kembali ke Kantor Desa/Kelurahan<br />
                3. Isi pilihan kotak sesuai dengan daftar pilihan pada Kotak A. Baru, B. Pergantian
            </td>
        </tr>
        <tr>
            <td colspan="48">&nbsp;</td>
        </tr>
    </table>

    <!-- Tabel Pertama -->
    <?php $kolom = 10; ?>
    <table style="border-collapse: collapse;" class="disdukcapil">
        <col style="width: 2%;"/>
        <col style="width: 20%;"/>
        <col style="width: 8%;"/>
        <col style="width: 12%;"/>
        <col style="width: 12%;"/>
        <col style="width: 12%;"/>
        <col style="width: 10%;"/>
        <col style="width: 10%;"/>
        <col style="width: 8%;"/>
        <col style="width: 6%;"/>

        <tr>
            <td class="judul tengah" rowspan="2">No Urut</td>
            <td class="judul tengah" rowspan="2">Nama Lengkap<br />Sesuai Akta Kelahiran / Ijazah</td>
            <td class="judul tengah" rowspan="2">Jenis Kelamin<br />(L/P)</td>
            <td class="judul tengah" rowspan="2">Tanggal Lahir</td>
            <td class="judul tengah" rowspan="2">Tempat Lahir</td>
            <td class="judul tengah" rowspan="2">No. Akta Kelahiran</td>
            <td class="judul tengah" colspan="2">Kewarganegaraan</td>
            <td class="judul tengah" rowspan="2">Gol Darah</td>
            <td class="judul tengah" rowspan="2">Agama</td>
        </tr>
        <tr>
            <td class="judul tengah">Kode</td>
            <td class="judul tengah">Dokumen Imigrasi</td>
        </tr>
        <tr>
            <?php for ($i = 1; $i <= $kolom; $i++): ?>
                <td class="judul abu tengah"><?= $i; ?></td>
            <?php endfor; ?>
        </tr>
        <?php for ($i = 0; $i < MAX_ANGGOTA_F101; $i++): ?>
            <tr>
                <?php $class = ($i == 10 - 1) ? 'bawah' : 'anggota'; ?>
                <td class="tengah <?= $class; ?>"><?= $i + 1; ?></td>
                <?php if ($i < count($anggota)): ?>
                    <td class="<?= $class; ?>"><?= $anggota[$i]['nama']; ?></td>
                    <td class="tengah <?= $class; ?>"><?= $anggota[$i]['sex']; ?></td>
                    <td class="tengah <?= $class; ?>"><?= tgl_indo_out($anggota[$i]['tanggallahir']) ?: ''; ?></td>
                    <td class="tengah <?= $class; ?>"><?= $anggota[$i]['tempatlahir']; ?></td>
                    <td class="tengah <?= $class; ?>"><?= $anggota[$i]['akta_lahir'] ?: '-'; ?></td>
                    <td class="tengah <?= $class; ?>"><?= $anggota[$i]['warganegara_id'] ?: '-'; ?></td>
                    <td class="tengah <?= $class; ?>"><?= $anggota[$i]['golongan_darah_id'] ?: '-'; ?></td>
                    <td class="tengah <?= $class; ?>"><?= $anggota[$i]['agama_id'] ?: '-'; ?></td>
                    <td class="tengah <?= $class; ?>"><?= $anggota[$i]['agama_id'] ?: '-'; ?></td>
                <?php else: ?>
                    <?php for ($k = 0; $k < $kolom - 1; $k++): ?>
                        <td class="tengah <?= $class; ?>">&nbsp;</td>
                    <?php endfor; ?>
                <?php endif; ?>
            </tr>
        <?php endfor; ?>
    </table>

    <!-- Tabel Kedua -->
    <?php $kolom2 = 9; ?>
    <table style="border-collapse: collapse; margin-top: 5px;" class="disdukcapil">
        <col style="width: 2%;"/>
        <col style="width: 10%;"/>
        <col style="width: 10%;"/>
        <col style="width: 10%;"/>
        <col style="width: 10%;"/>
        <col style="width: 10%;"/>
        <col style="width: 26%;"/>
        <col style="width: 10%;"/>
        <col style="width: 12%;"/>

        <tr>
            <td class="judul tengah">No Urut</td>
            <td class="judul tengah">Status Perkawinan</td>
            <td class="judul tengah">No. Akta Perkawinan/<br />Perceraian</td>
            <td class="judul tengah">Pendidikan Terakhir</td>
            <td class="judul tengah">Pekerjaan</td>
            <td class="judul tengah">Hubungan Keluarga</td>
            <td class="judul tengah">Nama Bapak/Ibu</td>
            <td class="judul tengah">Kode Aksetor KB</td>
            <td class="judul tengah">Kode Kelainan Khusus</td>
        </tr>
        <tr>
            <?php for ($i = 1; $i <= $kolom2; $i++): ?>
                <td class="judul abu tengah"><?= $i; ?></td>
            <?php endfor; ?>
        </tr>
        <?php for ($i = 0; $i < MAX_ANGGOTA_F101; $i++): ?>
            <tr>
                <?php $class = ($i == 10 - 1) ? 'bawah' : 'anggota'; ?>
                <td class="tengah <?= $class; ?>"><?= $i + 1; ?></td>
                <?php if ($i < count($anggota)): ?>
                    <td class="tengah <?= $class; ?>">
                        <?php
                            $status_map = [
                                'Belum Kawin'                => 1,
                                'Kawin Tercatat'             => 2,
                                'Kawin Belum Tercatat'       => 3,
                                'Cerai Hidup'                => 4,
                                'Cerai Hidup Belum Tercatat' => 5,
                                'Cerai Mati'                 => 6,
                            ];
                            $status_key = ucwords(strtolower($anggota[$i]['status_perkawinan']));
                            $status = $status_map[$status_key] ?? '-';
                            echo $status;
                        ?>
                    </td>
                    <td class="tengah <?= $class; ?>"><?= $anggota[$i]['akta_perkawinan'] ?: '-'; ?></td>
                    <td class="tengah <?= $class; ?>"><?= $anggota[$i]['pendidikan_kk_id'] ?: '-'; ?></td>
                    <td class="tengah <?= $class; ?>"><?= $anggota[$i]['pekerjaan_id'] ?: '-'; ?></td>
                    <td class="tengah <?= $class; ?>"><?= $anggota[$i]['kk_level'] ?: '-'; ?></td>
                    <td class="tengah <?= $class; ?>"><?= $anggota[$i]['nama_ayah'] ?: '-'; ?></td>
                    <td class="tengah <?= $class; ?>"><?= $anggota[$i]['cacat_id'] ?: '-'; ?></td>
                    <td class="tengah <?= $class; ?>"><?= $anggota[$i]['cacat_id'] ?: '-'; ?></td>
                <?php else: ?>
                    <?php for ($k = 0; $k < $kolom2 - 1; $k++): ?>
                        <td class="tengah <?= $class; ?>">&nbsp;</td>
                    <?php endfor; ?>
                <?php endif; ?>
            </tr>
        <?php endfor; ?>
    </table>

    <table class="disdukcapil">
        <tr>
            <td colspan="48">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="48"><strong>Keterangan :</strong></td>
        </tr>
        <tr>
            <td colspan="48">1) Diisi oleh Petugas</td>
        </tr>
        <tr>
            <td colspan="48">2) Diisi oleh WNA dan WNI Keturunan</td>
        </tr>
        <tr>
            <td colspan="48">3) Diisi Nomor Susunan Hubungan Keluarga terhadap Kepala Keluarga</td>
        </tr>
        <tr>
            <td colspan="48">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="16">Tanggal Pemasukan Data:</td>
            <td colspan="6">Tgl.</td>
            <td class="kotak tengah">&nbsp;&nbsp;</td>
            <td class="kotak tengah">&nbsp;&nbsp;</td>
            <td colspan="1">Bln.</td>
            <td class="kotak tengah">&nbsp;&nbsp;</td>
            <td class="kotak tengah">&nbsp;&nbsp;</td>
            <td colspan="1">Thn.</td>
            <td class="kotak tengah">&nbsp;&nbsp;</td>
            <td class="kotak tengah">&nbsp;&nbsp;</td>
            <td colspan="20">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="48">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="13">Paraf Petugas</td>
            <td colspan="8" style="border: solid 1px #000000; text-align: center; height: 40px;">&nbsp;</td>
            <td colspan="27">&nbsp;</td>
        </tr>
    </table>

    <table class="ttd" style="margin-top: 15px; width: 100%; border-collapse: collapse; padding: 0px; font-size: 8.5pt;">
        <col style="width: 35%;" />
        <col style="width: 30%;" />
        <col style="width: 35%;" />

        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>Tabanan , <?= tgl_indo(date('Y m d')); ?></td>
        </tr>
        <tr>
            <td class="center">Mengetahui,</td>
            <td class="center">Mengetahui,</td>
            <td class="center">Pemohon,</td>
        </tr>
        <tr>
            <td class="center">Kepala Dusun</td>
            <td class="center">Kepala Desa / Lurah</td>
            <td class="center"></td>
        </tr>
        <tr style="font-size: 20mm; line-height: normal;">
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td class="center">(.........................................................)</td>
            <td class="center">(.........................................................)</td>
            <td class="center"><?= "( {$individu['nama']} )" ?></td>
        </tr>
    </table>

</page>
