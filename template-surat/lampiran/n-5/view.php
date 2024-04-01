<?php defined('BASEPATH') || exit('No direct script access allowed'); ?>

<style type="text/css">
    <?php include FCPATH . '/assets/css/lampiran-surat.css'; ?>
</style>

<page orientation="portrait" format="210x330" style="font-size: 10pt">

    <!-- Judul Lampiran -->
    <table align="right">
        <tr>
            <td colspan="10">Lampiran IX</td>
        </tr>
        <tr>
            <td colspan="10">Kepdirjen Bimas Islam Nomor 473 Tahun 2020</td>
        </tr>
        <tr>
            <td colspan="10">Tentang</td>
        </tr>
        <tr>
            <td colspan="10">Petunjuk Teknis Pelaksanaan Pencatatan Nikah</td>
        </tr>
        <tr>
            <?php for ($i = 0; $i < 48; $i++) : ?>
                <td>&nbsp;</td>
            <?php endfor; ?>
        </tr>
    </table>

    <!-- Model N4 -->
    <table align="right">
        <tr>
            <td><strong>Model N5</strong></td>
            <td colspan="30">&nbsp;</td>
        </tr>
    </table>

    <br>
    <p class="title">PERSETUJUAN CALON PENGANTIN</p>

    <p>Yang bertanda tangan di bawah ini :</p>

    <table id="kop" class="disdukcapil">
        <col span="48" style="width: 2.0833%;">
        <!-- data ayah -->
        <tr>
            <td colspan="1">A.</td>
            <td colspan="20">1. Nama lengkap dan alias</td>
            <td>: </td>
            <td colspan="27"><strong><?= strtoupper($dataIndividuN5['nama_ayah']); ?></strong></td>
        </tr>
        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">2. Bin</td>
            <td>: </td>
            <td colspan="27"><?= $dataIndividuN5['bin_ayah']; ?></td>
        </tr>

        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">3. Nomor Induk Kependudukan</td>
            <td>: </td>
            <td colspan="27"><?= $dataIndividuN5['nik_ayah']; ?></td>
        </tr>

        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">4. Tempat dan tanggal lahir</td>
            <td>: </td>
            <td colspan="27"><?= $dataIndividuN5['tempat_lahir_ayah'] . ', ' . tgl_indo2(!empty($dataIndividuN5['tanggal_lahir_ayah']) ? date('Y-m-d', strtotime($dataIndividuN5['tanggal_lahir_ayah'])) : ''); ?></td>
        </tr>

        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">5. Kewarganegaraan</td>
            <td>: </td>
            <td colspan="27"><?= $dataIndividuN5['warga_negara_ayah']; ?></td>
        </tr>

        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">6. Agama</td>
            <td>: </td>
            <td colspan="27"><?= $dataIndividuN5['agama_ayah']; ?></td>
        </tr>

        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">7. Pekerjaan</td>
            <td>: </td>
            <td colspan="27"><?= $dataIndividuN5['pekerjaan_ayah']; ?></td>
        </tr>

        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">8. Alamat</td>
            <td>: </td>
            <td colspan="27"><?= $dataIndividuN5['alamat_ayah']; ?></td>
        </tr>

        <!-- data ibu -->
        <tr>
            <td colspan="1">B.</td>
            <td colspan="20">1. Nama lengkap dan alias</td>
            <td>: </td>
            <td colspan="27"><strong><?= strtoupper($dataIndividuN5['nama_ibu']); ?></strong></td>
        </tr>

        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">2. Binti</td>
            <td>: </td>
            <td colspan="27"><?= $dataIndividuN5['binti_ibu']; ?></td>
        </tr>

        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">3. Nomor Induk Kependudukan</td>
            <td>: </td>
            <td colspan="27"><?= $dataIndividuN5['nik_ibu']; ?></td>
        </tr>

        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">4. Tempat dan tanggal lahir</td>
            <td>: </td>
            <td colspan="27"><?= $dataIndividuN5['tempat_lahir_ibu'] . ', ' . tgl_indo2(!empty($dataCalonPriaN5['tanggal_lahir_ibu']) ? date('Y-m-d', strtotime($dataCalonPriaN5['tanggal_lahir_ibu'])) : ''); ?></td>
        </tr>

        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">5. Kewarganegaraan</td>
            <td>: </td>
            <td colspan="27"><?= $dataIndividuN5['warga_negara_ibu']; ?></td>
        </tr>

        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">6. Agama</td>
            <td>: </td>
            <td colspan="27"><?= $dataIndividuN5['agama_ibu']; ?></td>
        </tr>

        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">7. Pekerjaan</td>
            <td>: </td>
            <td colspan="27"><?= $dataIndividuN5['pekerjaan_ibu']; ?></td>
        </tr>

        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">8. Alamat</td>
            <td>: </td>
            <td colspan="27"><?= $dataIndividuN5['alamat_ibu']; ?></td>
        </tr>

        <!-- data individu -->
        <tr>
            <td colspan="48">adalah ayah dan ibu kandung dari :</td>
        </tr>

        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">1. Nama lengkap dan alias</td>
            <td>: </td>
            <td colspan="27"><strong><?= strtoupper($dataIndividuN5['nama']); ?></strong></td>
        </tr>

        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">2. Bin</td>
            <td>: </td>
            <td colspan="27"><?= $dataIndividuN5['nama_ayah']; ?></td>
        </tr>

        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">3. Nomor Induk Kependudukan</td>
            <td>: </td>
            <td colspan="27"><?= $dataIndividuN5['nik']; ?></td>
        </tr>

        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">4. Tempat dan tanggal lahir</td>
            <td>: </td>
            <td colspan="27"><?= $dataIndividuN5['tempatlahir'] . ', ' . tgl_indo2(!empty($dataIndividuN5['tanggallahir']) ? date('Y-m-d', strtotime($dataIndividuN5['tanggallahir'])) : ''); ?></td>
        </tr>

        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">5. Kewarganegaraan</td>
            <td>: </td>
            <td colspan="27"><?= $dataIndividuN5['warganegara']; ?></td>
        </tr>

        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">6. Agama</td>
            <td>: </td>
            <td colspan="27"><?= $dataIndividuN5['agama']; ?></td>
        </tr>

        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">7. Pekerjaan</td>
            <td>: </td>
            <td colspan="27"><?= $dataIndividuN5['pekerjaan']; ?></td>
        </tr>

        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">8. Alamat</td>
            <td>: </td>
            <td colspan="27"><?= $dataIndividuN5['alamat_wilayah']; ?></td>
        </tr>

        <!-- data calon pasangan  -->

    </table>

    <p>Memberikan izin kepada anak kami untuk melakukan pernikahan dengan :</p>
    <table id="kop" class="disdukcapil">
        <col span="48" style="width: 2.0833%;">
        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">1. Nama lengkap dan alias</td>
            <td>: </td>
            <td colspan="27"><strong><?= strtoupper($dataCalonPasanganN5['nama']); ?></strong></td>
        </tr>

        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">2. Bin</td>
            <td>: </td>
            <td colspan="27"><?= $dataCalonPasanganN5['bin']; ?></td>
        </tr>

        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">3. Nomor Induk Kependudukan</td>
            <td>: </td>
            <td colspan="27"><?= $dataCalonPasanganN5['nik']; ?></td>
        </tr>

        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">4. Tempat dan tanggal lahir</td>
            <td>: </td>
            <td colspan="27"><?= $dataCalonPasanganN5['tempatlahir'] . ', ' . tgl_indo2(!empty($dataCalonPasanganN5['tanggallahir']) ? date('Y-m-d', strtotime($dataCalonPasanganN5['tanggallahir'])) : ''); ?></td>
        </tr>

        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">5. Kewarganegaraan</td>
            <td>: </td>
            <td colspan="27"><?= $dataCalonPasanganN5['warganegara']; ?></td>
        </tr>

        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">6. Agama</td>
            <td>: </td>
            <td colspan="27"><?= $dataCalonPasanganN5['agama']; ?></td>
        </tr>

        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">7. Pekerjaan</td>
            <td>: </td>
            <td colspan="27"><?= $dataCalonPasanganN5['pekerjaan']; ?></td>
        </tr>

        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">8. Alamat</td>
            <td>: </td>
            <td colspan="27"><?= $dataCalonPasanganN5['alamat_wilayah']; ?></td>
        </tr>

    </table>


    <p style="text-align: justify; text-indent: 30px;">Demikian surat izin ini dibuat dengan kesadaran tanpa ada paksaan dari siapapun dan untuk digunakan seperlunya.</p>


    <!-- Penandatangan -->
    <br><br><br>
    <table class="disdukcapil" style="margin-top: -5px; border: 0px;">
        <col span="48" style="width: 2.0833%;">
        <tr>
            <td colspan="26">&nbsp;</td>
            <td colspan="16" class="tengah"><?= $config['nama_desa']; ?>, <?= tgl_indo(date('Y m d', time())) ?></td>
        </tr>
        <tr>
            <td colspan="16">
                <div>Ayah/wali/pengampu</div>
                <br>
                <br>
                <br>
                <br>
                <div><strong><?= $dataIndividuN5['nama_ayah']; ?></strong></div>
            </td>
            <td colspan="16">&nbsp;</td>
            <td colspan="16">
                <div>Ibu/wali/pengampu</div>
                <br>
                <br>
                <br>
                <br>
                <div><strong><?= $dataIndividuN5['nama_ibu']; ?></strong></div>
            </td>
        </tr>
    </table>
    <?= $qrcode ?? '' ?>
</page>