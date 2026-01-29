<?php if (! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<!-- Cara pengisian bisa dilihat di : https://sipenduduk.pekanbaru.go.id/Formulir-F1-01.pdf -->

<style type="text/css">
    <?php include FCPATH . '/assets/css/dukcapil.css'; ?>
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

    .disdukcapil td {
        line-height: 1.5;
    }
</style>

<page orientation="portrait" format="210x330" style="font-size: 10pt">
    <table align="right" style="padding: 5px 20px; border: solid 1px black;">
        <tr>
            <td><strong style="font-size: 14pt;">F-1.06</strong></td>
        </tr>
    </table>
    <p style="text-align: center; margin-top: 40px;">
        <strong style="font-size: 10pt;">SURAT PERNYATAAN PERUBAHAN ELEMEN DATA KEPENDUDUKAN</strong>
    </p>
    <table class="disdukcapil" style="margin-top: 0px; width: 100%; border-collapse: collapse;">
        <tr>
            <td colspan="48">Yang bertanda tangan di bawah ini :</td>
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

    <p>Dengan rincian KK sebagai berikut:</p>

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
            <?php if (!empty($pengikut_semua_anggota)) : ?>
                <?php $no = 1; foreach ($pengikut_semua_anggota as $anggota) : ?>
                <tr>
                    <td class="tg-0lax" style="text-align: center;"><?= $no++ ?></td>
                    <td class="tg-0lax" style="font-size: 8pt;"><?= $anggota->nama ?></td>
                    <td class="tg-0lax" style="font-size: 8pt;"><?= $anggota->nik ?></td>
                    <td class="tg-0lax" style="font-size: 8pt;"><?= $anggota->penduduk_hubungan ?></td>
                    <td class="tg-0lax" style="font-size: 8pt;"><?= $input['ket_' . $anggota->id] ?? '' ?></td>
                </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <?php tidak_ada_data(5); ?>
            <?php endif; ?>
        </tbody>
    </table>

    <p>
        Menyatakan bahwa elemen data kependudukan saya dan anggota keluarga saya telah berubah, dengan rincian:
    </p>

    <p>A. Pendidikan dan Pekerjaan:</p>

    <table class="tg">
        <thead>
            <col style="width: 5%;">
            <col style="width: 12%;">
            <col style="width: 12%;">
            <col style="width: 15%;">
            <col style="width: 12%;">
            <col style="width: 12%;">
            <col style="width: 15%;">
            <col style="width: 17%;">
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
            <?php if (!empty($pengikut_semua_anggota)) : ?>
                <?php $no = 1; foreach ($pengikut_semua_anggota as $anggota) : ?>
                    <?php $perubahan = $pengikut_ubahan_pendidikan_pekerjaan[$anggota->nik] ?? null; ?>
                    <tr>
                        <td class="tg-0lax" style="text-align: center; font-size: 8pt;"><?= $no++ ?></td>
                        <td class="tg-0lax" style="font-size: 8pt;"><?= $perubahan['pendidikan_semula'] ?? '-' ?></td>
                        <td class="tg-0lax" style="font-size: 8pt;"><?= $perubahan['pendidikan_menjadi'] ?? '' ?></td>
                        <td class="tg-0lax" style="font-size: 8pt;"><?= $perubahan['pendidikan_dasar_perubahan'] ?? '' ?></td>
                        <td class="tg-0lax" style="font-size: 8pt;"><?= $perubahan['pekerjaan_semula'] ?? '' ?></td>
                        <td class="tg-0lax" style="font-size: 8pt;"><?= $perubahan['pekerjaan_menjadi'] ?? '' ?></td>
                        <td class="tg-0lax" style="font-size: 8pt;"><?= $perubahan['pekerjaan_dasar_perubahan'] ?? '' ?></td>
                        <td class="tg-0lax" style="font-size: 8pt;"><?= $perubahan['keterangan'] ?? '' ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <?php tidak_ada_data(8); ?>
            <?php endif; ?>
        </tbody>
    </table>

    <p>
        B. Agama dan Perubahan Lainnya:
    </p>

    <?php
        $lainnya_pilihan = $input['lainnya'] ?? [];
        $lainnya_text = 'Lainnya, yaitu: ';
        if (!empty($lainnya_pilihan)) {
            $enum_values = \App\Enums\PerubahanDataPiEnum::valuesToUpper();
            $selected_values = array_map(static function($key) use ($enum_values) {
                return $enum_values[$key] ?? '';
            }, $lainnya_pilihan);
            $lainnya_text .= implode(', ', array_filter($selected_values));
        }
    ?>

    <table class="tg">
        <thead>
            <col style="width: 5%;">
            <col style="width: 12%;">
            <col style="width: 12%;">
            <col style="width: 15%;">
            <col style="width: 12%;">
            <col style="width: 12%;">
            <col style="width: 15%;">
            <col style="width: 17%;">
            <tr>
                <th class="tg-nrix" rowspan="3">No</th>
                <th class="tg-baqh" colspan="6">Elemen Data</th>
                <th class="tg-cly1" rowspan="3">Keterangan</th>
            </tr>
            <tr>
                <th class="tg-baqh" colspan="3">Agama</th>
                <th class="tg-baqh" colspan="3"><?= $lainnya_text ?></th>
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
            <?php if (!empty($pengikut_semua_anggota)) : ?>
                <?php $no = 1; foreach ($pengikut_semua_anggota as $anggota) : ?>
                    <?php $perubahan = $pengikut_ubahan_agama_lainnya[$anggota->nik] ?? null; ?>
                    <tr>
                        <td class="tg-0lax" style="text-align: center; font-size: 8pt;"><?= $no++ ?></td>
                        <td class="tg-0lax" style="font-size: 8pt;"><?= (!empty($perubahan['agama_menjadi']) && !empty($perubahan['agama_dasar_perubahan'])) ? ($perubahan['agama_semula'] ?? '-') : '' ?></td>
                        <td class="tg-0lax" style="font-size: 8pt;"><?= $perubahan['agama_menjadi'] ?? '' ?></td>
                        <td class="tg-0lax" style="font-size: 8pt;"><?= $perubahan['agama_dasar_perubahan'] ?? '' ?></td>
                        <td class="tg-0lax" style="font-size: 8pt;"><?= $perubahan['lainnya_semula'] ?? '' ?></td>
                        <td class="tg-0lax" style="font-size: 8pt;"><?= $perubahan['lainnya_menjadi'] ?? '' ?></td>
                        <td class="tg-0lax" style="font-size: 8pt;"><?= $perubahan['lainnya_dasar_perubahan'] ?? '' ?></td>
                        <td class="tg-0lax" style="font-size: 8pt;"><?= $perubahan['keterangan'] ?? '' ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <div style="clear: both; display: block; height: 10px;"></div>

    <p style="text-indent: 30px; text-align: justify; line-height: 1.2;">
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
            <td colspan="2" style="text-align: right;"><?= $individu['nama'] ?></td>
            <td>&nbsp;</td>
        </tr>
    </table>
</page>