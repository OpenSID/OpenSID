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
            <td colspan="27"><strong>[Nama_dapW]</strong></td>
        </tr>
        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">2. Bin</td>
            <td>: </td>
            <td colspan="27">[Form_bin_ayah_wanitA]</td>
        </tr>

        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">3. Nomor Induk Kependudukan</td>
            <td>: </td>
            <td colspan="27">[Nik_dapW]</td>
        </tr>

        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">4. Tempat dan tanggal lahir</td>
            <td>: </td>
            <td colspan="27">[Tempatlahir_dapW] , [Tanggallahir_dapW]</td>
        </tr>

        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">5. Kewarganegaraan</td>
            <td>: </td>
            <td colspan="27">[Warga_negara_dapW]</td>
        </tr>

        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">6. Agama</td>
            <td>: </td>
            <td colspan="27">[Agama_dapW]</td>
        </tr>

        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">7. Pekerjaan</td>
            <td>: </td>
            <td colspan="27">[Pekerjaan_dapW]</td>
        </tr>

        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">8. Alamat</td>
            <td>: </td>
            <td colspan="27">[Alamat_dapW]</td>
        </tr>

        <!-- data ibu -->
        <tr>
            <td colspan="1">B.</td>
            <td colspan="20">1. Nama lengkap dan alias</td>
            <td>: </td>
            <td colspan="27"><strong>[Nama_dibpW]</strong></td>
        </tr>

        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">2. Binti</td>
            <td>: </td>
            <td colspan="27">[Form_bin_ibu_wanitA]</td>
        </tr>

        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">3. Nomor Induk Kependudukan</td>
            <td>: </td>
            <td colspan="27">[Nik_dibpW]</td>
        </tr>

        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">4. Tempat dan tanggal lahir</td>
            <td>: </td>
            <td colspan="27">[Tempatlahir_dibpW], [Tanggallahir_dibpW]</td>
        </tr>

        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">5. Kewarganegaraan</td>
            <td>: </td>
            <td colspan="27">[Warga_negara_dibpW]</td>
        </tr>

        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">6. Agama</td>
            <td>: </td>
            <td colspan="27">[Agama_dibpW]</td>
        </tr>

        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">7. Pekerjaan</td>
            <td>: </td>
            <td colspan="27">[Pekerjaan_dibpW]</td>
        </tr>

        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">8. Alamat</td>
            <td>: </td>
            <td colspan="27">[Alamat_dibpW]</td>
        </tr>

        <!-- data individu -->
        <tr>
            <td colspan="48">adalah ayah dan ibu kandung dari :</td>
        </tr>

        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">1. Nama lengkap dan alias</td>
            <td>: </td>
            <td colspan="27"><strong>[Nama_dcpW]</strong></td>
        </tr>

        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">2. Bin</td>
            <td>: </td>
            <td colspan="27">[Nama_dapW]</td>
        </tr>

        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">3. Nomor Induk Kependudukan</td>
            <td>: </td>
            <td colspan="27">[Nik_dcpW]</td>
        </tr>

        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">4. Tempat dan tanggal lahir</td>
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

        <!-- data calon pasangan  -->

    </table>

    <p>Memberikan izin kepada anak kami untuk melakukan pernikahan dengan :</p>
    <table id="kop" class="disdukcapil">
        <col span="48" style="width: 2.0833%;">
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
            <td colspan="20">4. Tempat dan tanggal lahir</td>
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


    <p style="text-align: justify; text-indent: 30px;">Demikian surat izin ini dibuat dengan kesadaran tanpa ada paksaan dari siapapun dan untuk digunakan seperlunya.</p>


    <!-- Penandatangan -->
    <br><br><br>
    <table class="disdukcapil" style="margin-top: -5px; border: 0px;">
        <col span="48" style="width: 2.0833%;">
        <tr>
            <td colspan="26">&nbsp;</td>
            <td colspan="16" class="tengah">[Nama_desA], <?= tgl_indo(date('Y m d', time())) ?></td>
        </tr>
        <tr>
            <td colspan="16">
                <div>Ayah/wali/pengampu</div>
                <br>
                <br>
                <br>
                <br>
                <div><strong>[Nama_dapW]</strong></div>
            </td>
            <td colspan="16">&nbsp;</td>
            <td colspan="16">
                <div>Ibu/wali/pengampu</div>
                <br>
                <br>
                <br>
                <br>
                <div><strong>[Nama_dibpw]</strong></div>
            </td>
        </tr>
    </table>
    <?= $qrcode ?? '' ?>
</page>