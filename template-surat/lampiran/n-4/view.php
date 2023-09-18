<?php defined('BASEPATH') || exit('No direct script access allowed'); ?>

<style type="text/css">
    <?php include FCPATH . '/assets/css/lampiran-surat.css'; ?>
</style>

<page orientation="portrait" format="210x330" style="font-size: 10pt">

    <!-- Judul Lampiran -->
    <table align="right">
        <tr>
            <td colspan="10">Lampiran VIII</td>
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
            <td><strong>Model N4</strong></td>
            <td colspan="30">&nbsp;</td>
        </tr>
    </table>

    <p class="title">PERSETUJUAN CALON PENGANTIN</p>

    <p>Yang bertanda tangan dibawah ini : </p>
    <p class="number">A. Calon Suami :</p>
    <table id="kop" class="disdukcapil">
        <col span="48" style="width: 2.0833%;">
        <tr>
            <td colspan="48">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">1. Nama lengkap dan alias</td>
            <td>: </td>
            <td colspan="27"><strong><?= strtoupper($dataCalonN4['nama_pria']); ?></strong></td>
        </tr>
        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">2. Bin</td>
            <td>: </td>
            <td colspan="27"><?= $dataCalonN4['bin_pria']; ?></td>
        </tr>
        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">3. Nomor Induk Kependudukan</td>
            <td>: </td>
            <td colspan="27"><?= $dataCalonN4['nik_pria']; ?></td>
        </tr>
        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">4. Tempat dan Tanggal Lahir</td>
            <td>: </td>
            <td colspan="27"><?= $dataCalonN4['tempatlahir_pria'] . ', ' . tgl_indo2( !empty($dataCalonN4['tanggallahir_pria']) ? date('Y-m-d', strtotime($dataCalonN4['tanggallahir_pria'])) : ''); ?></td>
        </tr>
        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">5. Kewarganegaraan</td>
            <td>: </td>
            <td colspan="27"><?= $dataCalonN4['warganegara_pria']; ?></td>
        </tr>
        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">6. Agama</td>
            <td>: </td>
            <td colspan="27"><?= $dataCalonN4['agama_pria']; ?></td>
        </tr>
        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">7. Pekerjaan</td>
            <td>: </td>
            <td colspan="27"><?= $dataCalonN4['pekerjaan_pria']; ?></td>
        </tr>
        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">8. Alamat</td>
            <td>: </td>
            <td colspan="27"><?= $dataCalonN4['alamat_pria']; ?></td>
        </tr>
    </table>

    <p class="number">B. Calon Istri :</p>
    <table id="kop" class="disdukcapil">
        <col span="48" style="width: 2.0833%;">
        <tr>
            <td colspan="48">&nbsp;</td>
        </tr>
        <tr class="spasi">
            <td colspan="1">&nbsp;</td>
            <td colspan="20">1. Nama lengkap dan alias</td>
            <td>: </td>
            <td colspan="27"><strong><?= strtoupper($dataCalonN4['nama_wanita']); ?></strong></td>
        </tr>
        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">2. Bin</td>
            <td>: </td>
            <td colspan="27"><?= $dataCalonN4['binti_wanita']; ?></td>
        </tr>
        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">3. Nomor Induk Kependudukan</td>
            <td>: </td>
            <td colspan="27"><?= $dataCalonN4['nik_wanita']; ?></td>
        </tr>
        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">4. Tempat dan Tanggal Lahir</td>
            <td>: </td>
            <td colspan="27"><?= $dataCalonN4['tempatlahir_wanita'] . ', ' . tgl_indo2(!empty($dataCalonN4['tanggallahir_wanita']) ? date('Y-m-d', strtotime($dataCalonN4['tanggallahir_wanita'])) : ''); ?></td>
        </tr>
        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">5. Kewarganegaraan</td>
            <td>: </td>
            <td colspan="27"><?= $dataCalonN4['warganegara_wanita']; ?></td>
        </tr>
        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">6. Agama</td>
            <td>: </td>
            <td colspan="27"><?= $dataCalonN4['agama_wanita']; ?></td>
        </tr>
        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">7. Pekerjaan</td>
            <td>: </td>
            <td colspan="27"><?= $dataCalonN4['pekerjaan_wanita']; ?></td>
        </tr>
        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">8. Alamat</td>
            <td>: </td>
            <td colspan="27"><?= $dataCalonN4['alamat_wanita']; ?></td>
        </tr>
    </table>

    <p>Menyatakan dengan sesungguhnya bahwa atas dasar sukarela, dengan kesadaran sendiri,
        tanpa ada paksaan dari siapapun juga, setuju untuk melangsungkan pernikahan.
    </p>
    <p>Demikian surat persetujuan ini dibuat untuk digunakan seperlunya.</p>

    <!-- Penandatangan -->
    <br><br><br>
    <table class="disdukcapil" style="margin-top: -5px; border: 0px;">
        <col span="48" style="width: 2.0833%;">
        <tr>
            <td colspan="30">&nbsp;</td>
            <td colspan="20" class="tengah"><?= $config['nama_desa']; ?>, <?= tgl_indo(date('Y m d', time())) ?></td>
            <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="18" class="tengah">Calon Suami</td>
            <td colspan="12">&nbsp;</td>
            <td colspan="18" class="tengah">Calon Istri</td>
            <td colspan="1">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="2">&nbsp;</td>
            <td colspan="10" class="tengah">&nbsp;</td>
            <td colspan="24">&nbsp;</td>
            <td colspan="10" class="tengah">&nbsp;</td>
            <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="2">&nbsp;</td>
            <td colspan="10" class="tengah">&nbsp;</td>
            <td colspan="24">&nbsp;</td>
            <td colspan="10" class="tengah">&nbsp;</td>
            <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="2">&nbsp;</td>
            <td colspan="10" class="tengah">&nbsp;</td>
            <td colspan="24">&nbsp;</td>
            <td colspan="10" class="tengah">&nbsp;</td>
            <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="18" class="tengah"><strong>(<?= padded_string_center(strtoupper($dataCalonN4['nama_pria']), 30) ?>)</strong></td>
            <td colspan="12">&nbsp;</td>
            <td colspan="18" class="tengah"><strong>(<?= padded_string_center(strtoupper($dataCalonN4['nama_wanita']), 30) ?>)</strong></td>
            <td colspan="1">&nbsp;</td>
        </tr>
    </table>
    <!-- Akhir Halaman 4 -->
</page>