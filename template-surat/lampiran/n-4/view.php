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
            <td colspan="27"><strong>[NamA]</strong></td>
        </tr>
        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">2. Bin</td>
            <td>: </td>
            <td colspan="27">[Nama_dapP]</td>
        </tr>
        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">3. Nomor Induk Kependudukan</td>
            <td>: </td>
            <td colspan="27">[NiK]</td>
        </tr>
        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">4. Tempat dan Tanggal Lahir</td>
            <td>: </td>
            <td colspan="27">[TtL]</td>
        </tr>
        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">5. Kewarganegaraan</td>
            <td>: </td>
            <td colspan="27">[Warga_negarA]</td>
        </tr>
        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">6. Agama</td>
            <td>: </td>
            <td colspan="27">[AgamA]</td>
        </tr>
        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">7. Pekerjaan</td>
            <td>: </td>
            <td colspan="27">[PekerjaaN]</td>
        </tr>
        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">8. Alamat</td>
            <td>: </td>
            <td colspan="27">[AlamaT]</td>
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
            <td colspan="27"><strong>[Nama_dcpW]</strong></td>
        </tr>
        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">2. Bin</td>
            <td>: </td>
            <td colspan="27">[Nama_dcpW]</td>
        </tr>
        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">3. Nomor Induk Kependudukan</td>
            <td>: </td>
            <td colspan="27">[Nik_dcpW]</td>
        </tr>
        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">4. Tempat dan Tanggal Lahir</td>
            <td>: </td>
            <td colspan="27">[Tempatlahir_dcpW], [Tanggallahir_dcpW]</td>
        </tr>
        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">5. Kewarganegaraan</td>
            <td>: </td>
            <td colspan="27">[Warga_negara_dcpW]</td>
        </tr>
        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">6. Agama</td>
            <td>: </td>
            <td colspan="27">[Agama_dcpW]</td>
        </tr>
        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">7. Pekerjaan</td>
            <td>: </td>
            <td colspan="27">[Pekerjaan_dcpW]</td>
        </tr>
        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">8. Alamat</td>
            <td>: </td>
            <td colspan="27">[Alamat_dcpW]</td>
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
            <td colspan="20" class="tengah">[Nama_desA], <?= tgl_indo(date('Y m d', time())) ?></td>
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
            <td colspan="18" class="tengah"><strong>([NamA])</strong></td>
            <td colspan="12">&nbsp;</td>
            <td colspan="18" class="tengah"><strong>([Nama_dcpW])</strong></td>
            <td colspan="1">&nbsp;</td>
        </tr>
    </table>
    <!-- Akhir Halaman 4 -->
</page>