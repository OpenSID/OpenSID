<?php defined('BASEPATH') || exit('No direct script access allowed'); ?>

<style type="text/css">
    <?php include FCPATH . '/assets/css/lampiran-surat.css'; ?>
</style>

<page orientation="portrait" format="210x330" style="font-size: 10pt">

    <!-- Judul Lampiran -->
    <table align="right">
        <tr>
            <td colspan="10">Lampiran V</td>
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
            <td><strong>Model N2</strong></td>
            <td colspan="30">&nbsp;</td>
        </tr>
    </table>

    <table>
        <tr>
            <td style="width:40px">Perihal</td>
            <td>:</td>
            <td>Permohonan Kehendak Nikah</td>
            <td style="width:200px">&nbsp;</td>
            <td style="width:200px;text-align:right">[NAma_kecamatan], [TgL_surat] </td>
        </tr>
    </table>

    <p>
    <div>Kepada Yth.</div>
    <div>Kepala KUA Kecamatan/PPN LN</div>
    <div>di [NAma_kecamatan]</div>
    </p>

    <p style="text-align: justify; text-indent: 30px;">Dengan hormat, kami mengajukan permohonan kehendak nikah untuk atas nama:</p>

    <table id="kop" class="disdukcapil">
        <col span="48" style="width: 2.0833%;">
        <tr>
            <td colspan="48">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">Calon Suami</td>
            <td>: </td>
            <td colspan="27"><strong>[NAma]</strong></td>
        </tr>
        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">Calon Istri</td>
            <td>: </td>
            <td colspan="27"><strong>[NAma_dcpw]</strong></td>
        </tr>
        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">Hari/tanggal/jam</td>
            <td>: </td>
            <td colspan="27">[Form_hari_nikaH] / [Form_tanggal_nikaH] / [Form_jam_nikaH] WIB</td>
        </tr>
        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">Tempat akad nikah</td>
            <td>: </td>
            <td colspan="27">[Form_tempat_nikaH]</td>
        </tr>
        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">Maskawin</td>
            <td>: </td>
            <td colspan="27">[Form_maskawin_nikaH]</td>
        </tr>

    </table>

    <p style="text-align: justify; text-indent: 30px;">Bersama ini kami sampaikan surat-surat yang diperlukan untuk diperiksa sebagai
        berikut :
    </p>

    <div>
        <ol>
            <li>Surat Pengantar Nikah dari Desa / Kelurahan</li>
            <li>Persetujuan Calon Mempelai</li>
            <li>Fotokopi KTP Elektronik /Suket</li>
            <li>Fotokopi Akta Kelahiran</li>
            <li>Fotokopi Kartu Keluarga (KK)</li>
            <li>Fotokopi Ijazah Terakhir</li>
            <li>Paspoto ukuran 2x3 dan 4x6 sebanyak 4 lembar berlatar belakang warna biru</li>
            <li>Fotokopi KTP Elektronik Ayah dan Ibu calon pengantin</li>
            <li>Surat izin orangtua (N5) bila umur kurang 21 Tahun *)</li>
            <li>Surat Keterangan Kematian Suami/Istri (N6) bila Duda/Janda Mati *)</li>
            <li>Akta Cerai bila Duda/Janda Hidup *)</li>
            <li>…………………………………………</li>
            <li>…………………………………………</li>
            <li>…………………………………………</li>
        </ol>
    </div>

    <p style="text-align: justify; text-indent: 30px;">Demikian permohonan ini kami sampaikan, kiranya dapat diperiksa, dihadiri dan
        dicatat sesuai dengan ketentuan peraturan perundang-undangan.
    </p>


    <!-- Penandatangan -->
    <br><br><br>
    <table class="disdukcapil" style="margin-top: -5px; border: 0px;">
        <col span="48" style="width: 2.0833%;">
        <tr>
            <td colspan="16">
                <div>Diterima tanggal.............. </div>
                <div>Yang menerima,</div>
                <div>Kepala KUA/PPN LN,</div>
                <br>
                <br>
                <br>
                <br>
                <div>.................................</div>
            </td>
            <td colspan="18">&nbsp;</td>
            <td colspan="14">
                <div>Wassalam,</div>
                <div>Pemohon</div>
                <div></div>
                <br>
                <br>
                <br>
                <br>
                <div><strong>[NAma]</strong></div>
            </td>
        </tr>
    </table>

    <br>
    <br>
    <div>
        *) Coret bila tidak disertakan
    </div>
</page>