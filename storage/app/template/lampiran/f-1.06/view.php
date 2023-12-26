<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<!-- Cara pengisian bisa dilihat di : https://sipenduduk.pekanbaru.go.id/Formulir-F1-01.pdf -->

<style type="text/css">
    <?php include(FCPATH . "/assets/css/dukcapil.css"); ?>
</style>

<style type="text/css">
    .tg {
        border-collapse: collapse;
        border-spacing: 0;
        width: 100% !important;
    }

    .tg td {
        border-color: black;
        border-style: solid;
        border-width: 1px;
        font-family: Arial, sans-serif;
        font-size: 14px;
        overflow: hidden;
        padding: 10px 5px;
        word-break: normal;
    }

    .tg th {
        border-color: black;
        border-style: solid;
        border-width: 1px;
        font-family: Arial, sans-serif;
        font-size: 14px;
        font-weight: normal;
        overflow: hidden;
        padding: 10px 5px;
        word-break: normal;
    }

    .tg .tg-cly1 {
        text-align: left;
        vertical-align: middle
    }

    .tg .tg-baqh {
        text-align: center;
        vertical-align: top
    }

    .tg .tg-nrix {
        text-align: center;
        vertical-align: middle
    }

    .tg .tg-0lax {
        text-align: left;
        vertical-align: top
    }
</style>

<page orientation="portrait" format="F4" style="font-size: 10pt">
    <table align="right" style="padding: 5px 20px; border: solid 1px black;">
        <tr>
            <td><strong style="font-size: 14pt;">F-1.06</strong></td>
        </tr>
    </table>
    <p style="text-align: center; margin-top: 40px;">
        <strong style="font-size: 10pt;">SURAT PERNYATAAN PERUBAHAN ELEMEN DATA KEPENDUDUKAN</strong>
    </p>
    <table class="disdukcapil" style="margin-top: 0px;">
        <tr>
            <td colspan="48">Yang bertanda tangan dibawah ini :</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td colspan="18">Nama Lengkap</td>
            <td class="kanan"> : </td>
            <td colspan="27"><?= $individu['nama'] ?></td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td colspan="18">NIK</td>
            <td class="kanan"> : </td>
            <td colspan="27"><?= $individu['nik'] ?></td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td colspan="18">Nomor KK</td>
            <td class="kanan"> : </td>
            <td colspan="27"><?= $individu['no_kk'] ?></td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td colspan="18">Alamat rumah</td>
            <td class="kanan"> : </td>
            <td colspan="27"><?= $individu['alamat_wilayah'] ?></td>
        </tr>
    </table>

    <p>dengan rincian KK sebagai berikut:</p>

    <table style="border-collapse: collapse;" class="tg">
        <thead>
            <col style="width: 5%;">
            <col style="width: 25%;">
            <col style="width: 25%;">
            <col style="width: 10%;">
            <col style="width: 35%;">
            <tr>
                <th class="tg-0lax">No </th>
                <th class="tg-0lax">Nama</th>
                <th class="tg-0lax">NIK</th>
                <th class="tg-0lax">SHDK</th>
                <th class="tg-0lax">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="tg-0lax"></td>
                <td class="tg-0lax"></td>
                <td class="tg-0lax"></td>
                <td class="tg-0lax"></td>
                <td class="tg-0lax"></td>
            </tr>
            <tr>
                <td class="tg-0lax"></td>
                <td class="tg-0lax"></td>
                <td class="tg-0lax"></td>
                <td class="tg-0lax"></td>
                <td class="tg-0lax"></td>
            </tr>
            <tr>
                <td class="tg-0lax"></td>
                <td class="tg-0lax"></td>
                <td class="tg-0lax"></td>
                <td class="tg-0lax"></td>
                <td class="tg-0lax"></td>
            </tr>
            <tr>
                <td class="tg-0lax"></td>
                <td class="tg-0lax"></td>
                <td class="tg-0lax"></td>
                <td class="tg-0lax"></td>
                <td class="tg-0lax"></td>
            </tr>
        </tbody>
    </table>

    <p>
        Menyatakan bahwa elemen data kependudukan saya dan anggota keluarga saya telah berubah, dengan rincian:
    </p>

    <p>A. Pendidikan dan Pekerjaan:</p>

    <table class="tg">
        <thead>
            <col style="width: 5%;">
            <col style="width: 9%;">
            <col style="width: 9%;">
            <col style="width: 19%;">
            <col style="width: 9%;">
            <col style="width: 9%;">
            <col style="width: 19%;">
            <col style="width: 21%;">
            <tr>
                <th class="tg-nrix" rowspan="3">No</th>
                <th class="tg-baqh" colspan="6">Elemen Data</th>
                <th class="tg-cly1" rowspan="3">Keterangan</th>
            </tr>
            <tr>
                <th class="tg-baqh" colspan="3">Pendidikan Terakhir</th>
                <th class="tg-baqh" colspan="3">Pekerjaan</th>
            </tr>
            <tr>
                <th class="tg-0lax">Semula</th>
                <th class="tg-0lax">Menjadi</th>
                <th class="tg-0lax">Dasar Perubahan</th>
                <th class="tg-0lax">Semula</th>
                <th class="tg-0lax">Menjadi</th>
                <th class="tg-0lax">Dasar Perubahan</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="tg-0lax">1</td>
                <td class="tg-0lax"></td>
                <td class="tg-0lax"></td>
                <td class="tg-0lax"></td>
                <td class="tg-0lax"></td>
                <td class="tg-0lax"><?= $input['form_pekerjaan'] ?></td>
                <td class="tg-0lax"></td>
                <td class="tg-0lax"></td>
            </tr>
        </tbody>
    </table>

    <p>
        B. Agama dan Perubahan Lainnya:
    </p>

    <table class="tg">
        <thead>
            <col style="width: 5%;">
            <col style="width: 9%;">
            <col style="width: 9%;">
            <col style="width: 19%;">
            <col style="width: 9%;">
            <col style="width: 9%;">
            <col style="width: 19%;">
            <col style="width: 21%;">
            <tr>
                <th class="tg-nrix" rowspan="3">No</th>
                <th class="tg-baqh" colspan="6">Elemen Data</th>
                <th class="tg-cly1" rowspan="3">Keterangan</th>
            </tr>
            <tr>
                <th class="tg-baqh" colspan="3">Agama</th>
                <th class="tg-baqh" colspan="3">Lainnya, yaitu: </th>
            </tr>
            <tr>
                <th class="tg-0lax">Semula</th>
                <th class="tg-0lax">Menjadi</th>
                <th class="tg-0lax">Dasar Perubahan</th>
                <th class="tg-0lax">Semula</th>
                <th class="tg-0lax">Menjadi</th>
                <th class="tg-0lax">Dasar Perubahan</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="tg-0lax">1</td>
                <td class="tg-0lax"></td>
                <td class="tg-0lax"><?= $input['form_agama'] ?></td>
                <td class="tg-0lax"></td>
                <td class="tg-0lax"></td>
                <td class="tg-0lax"></td>
                <td class="tg-0lax"></td>
                <td class="tg-0lax"></td>
            </tr>
        </tbody>
    </table>

    <p style="text-indent: 30px;">
        Terlampir disampaikan fotokopi berkas-berkas yang terkait dangan perubahan elemen data tersebut.
        Demikian Surat Pernyataan ini saya buat dengan sebenarnya, apabila dalam keterangan yang saya berikan
        terdapat hal-hal yang tidak berdasarkan keadilan yang sebenarnya, saya bersedia dikenakan sanksi sesuai
        ketentuan peraturan perundang-undangan yang berlaku.
    </p>

    <table class="ttd" style="margin-top: 15px">
        <col style="width:2%">
        <col style="width:20%">
        <col style="width:48%">
        <col style="width:20%">
        <col style="width:10%">

        <tr>
            <td colspan="4" style="text-align: right">
                <?= $config['nama_desa'] ?>, <?= tgl_indo(date('Y-m-d')) ?>
            </td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td colspan="4" style="text-align: right">
                Yang membuat pernyataan,
            </td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td colspan="7" style="height: 30px;">&nbsp;</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td class="center"><?= $individu['nama'] ?></td>
            <td>&nbsp;</td>
        </tr>
    </table>
</page>