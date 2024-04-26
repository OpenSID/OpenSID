<?php if (! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<style type="text/css">
    table.disdukcapil
    {
        width: 100%;
        border: solid 2px #000000;
        /*border-collapse: collapse;*/
    }
    table.disdukcapil td
    {
        padding: 3px 3px 3px 3px;
    }
    table.disdukcapil td.padat
    {
        padding: 0px;
        margin: 0px;
    }
    table.disdukcapil td.anggota
    {
        border-left: solid 1px #000000;
        border-right: solid 1px #000000;
        border-top: dashed 1px #000000;
        border-bottom: dashed 1px #000000;
    }
    table.disdukcapil td.judul
    {
        border-left: solid 1px #000000;
        border-right: solid 1px #000000;
        border-top: double 1px #000000;
        border-bottom: double 1px #000000;
    }
    table.disdukcapil td.bawah {border-bottom: solid 1px #000000;}
    table.disdukcapil td.atas {border-top: solid 1px #000000;}
    table.disdukcapil td.tengah_blank
    {
        border-left: solid 1px #000000;
        border-right: solid 1px #000000;
    }
    table.disdukcapil td.pinggir_kiri {border-left: solid 1px #000000;}
    table.disdukcapil td.pinggir_kanan {border-right: solid 1px #000000;}
    table.disdukcapil td.kotak {border: solid 1px #000000;}
    table.disdukcapil td.abu {background-color: lightgrey;}
    table.disdukcapil td.kode {background-color: lightgrey;}
    table.disdukcapil td.kode div
    {
        margin: 0px 15px 0px 15px;
        border: solid 1px black;
        background-color: white;
        text-align: center;
    }
    table.disdukcapil td.pakai-padding
    {
        padding-left: 20px;
        padding-right: 2px;
    }
    table.disdukcapil td.kanan { text-align: right; }
    table.disdukcapil td.tengah { text-align: center; }
</style>

<page orientation="portrait" format="210x330" style="font-size: 8pt">
    <table align="right" style="padding: 5px 20px; border: solid 1px black;">
        <tr><td><strong style="font-size: 14pt;">F-1.21</strong></td></tr>
    </table>
    <p style="text-align: center; margin-top: 40px;">
            <strong style="font-size: 10pt;text-decoration:underline">FORMULIR PERMOHONAN KARTU TANDA PENDUDUK (KTP)</strong><br>
            <label style="font-size: 10pt;">Nomor : [FOrmat_nomor_surat]</label>
    </p>
    <table class="disdukcapil" style="margin-top: 0px; padding:10px;">
        <col span="48" style="width: 2.0833%;">
        <tr>
            <td class="kotak" colspan="48" style="padding:5px;" style="border: solid 2px #000000">
                <strong>Perhatian:</strong><br>
                1. Harap diisi dengan huruf cetak dan menggunakan tinta hitam<br>
                2. Untuk kolom pilihan, harap memberi tanda (X) pada kotak pilihan<br>
                2. Setelah formulir ini diisi dan ditandatangani, harap diserahkan kembali kekantor Desa/Kelurahan
            </td>
        </tr>
        <tr><td colspan=48>&nbsp;</td></tr>
        <tr>
            <td>&nbsp;</td>
            <td colspan="16" class="left"><strong>PEMERINTAH PROVINSI</strong></td>
            <td class="tengah">:</td>
            <td colspan="5" class="kotak"><?= $config['kode_propinsi']; ?></td>
            <td>&nbsp;</td>
            <td colspan="24" class="kotak"><?= $config['nama_propinsi']; ?></td>
        </tr>

        <tr>
            <td>&nbsp;</td>
            <td colspan="16" class="left"><strong>PEMERINTAH KABUPATEN/KOTA</strong></td>
            <td class="tengah">:</td>
            <td colspan="5" class="kotak"><?= substr($config['kode_kabupaten'], 0, 4); ?></td>
            <td>&nbsp;</td>
            <td colspan="24" class="kotak"><?= $config['nama_kabupaten']; ?></td>
        </tr>

        <tr>
            <td>&nbsp;</td>
            <td colspan="16" class=" left"><strong>KECAMATAN</strong></td>
            <td class="tengah">:</td>
            <td colspan="5" class="kotak"><?= substr($config['kode_kecamatan'], 0, 6); ?></td>
            <td>&nbsp;</td>
            <td colspan="24" class="kotak"><?= $config['nama_kecamatan']; ?></td>
        </tr>

        <tr>
            <td>&nbsp;</td>
            <td colspan="16" class="left"><strong>KELURAHAN/DESA</strong></td>
            <td class="tengah">:</td>
            <td colspan="5" class="kotak"><?= substr($config['kode_desa'], 0, 10); ?></td>
            <td>&nbsp;</td>
            <td colspan="24" class="kotak"><?= $config['nama_desa']; ?></td>
        </tr>

        <tr>
            <td>&nbsp;</td>
            <td colspan="16" class="left"><strong>PERMOHONAN KTP</strong></td>
            <td class="tengah">:</td>
            <td colspan="5" class="kotak tengah abu">C</td>
            <td>&nbsp;</td>
            <td colspan="7" class="kotak tengah">A. Baru</td>
            <td colspan="9" class="kotak tengah">B. Perpanjangan</td>
            <td colspan="8" class="kotak tengah">C. Pergantian</td>
        </tr>
        <tr><td colspan=48>&nbsp;</td></tr>

        <tr>
            <td colspan="8" class="kotak">1. Nama</td>
            <td class="tengah" class="tengah">:</td>
            <td colspan="39" class="kotak"><?= $individu['nama']; ?></td>
        </tr>
        <tr>
            <td colspan=8 class="kotak">2. No. KK </td>
            <td class="tengah">:</td>
            <td colspan="20" class="kotak"><?= $individu['no_kk']; ?></td>
        </tr>
        <tr>
            <td colspan="8" class="kotak">3. NIK</td>
            <td class="tengah">:</td>
            <td colspan="20" class="kotak"><?= $individu['nik']; ?></td>
        </tr>
        <tr>
            <td colspan="8" class="kotak">4. Alamat</td>
            <td class="tengah">:</td>
            <td colspan="20" class="kotak"><?= $individu['alamat']?></td>
        </tr>
        <tr>
            <td colspan="9" >&nbsp;</td>
            <td colspan="7" class="left">a. Kelurahan/Desa</td>
            <td colspan="13" class="kotak"><?= $config['nama_desa']; ?></td>
            <td colspan="5" class="left">b. Kec.</td>
            <td colspan="14" class="kotak"><?= $config['nama_kecamatan']; ?></td>
        </tr>
        <tr>
            <td colspan="9" >&nbsp;</td>
            <td colspan="7" class="left">c. Kabupaten/Kota</td>
            <td colspan="13" class="kotak"><?= $config['nama_kabupaten']; ?></td>
            <td colspan="5" class="left">d. Prov.</td>
            <td colspan="14" class="kotak"><?= $config['nama_propinsi']; ?></td>
        </tr>
        <tr>
            <td colspan="13">&nbsp;</td>
            <td colspan="3" class="tengah">RT.</td>
            <td colspan="5" class="kotak"><?= $individu['rt']?></td>
            <td colspan="3" class="tengah">RW.</td>
            <td colspan="5" class="kotak"><?= $individu['rw']?></td>
            <td colspan="5" class="left">Pos. Kod.</td>
            <td colspan="5" class="kotak"><?= $individu['kode_pos']?></td>
            <td colspan="7">&nbsp;</td>
            <td colspan="2">&nbsp;</td>
            <td colspan="5">&nbsp;</td>
        </tr>
        <tr><td>&nbsp;</td></tr>
        <tr>
            <td>&nbsp;</td>
            <td colspan="8" class="kotak tengah">Foto 2X3</td>
            <td>&nbsp;</td>
            <td colspan="8" class="kotak tengah">Cap Jempol</td>
            <td>&nbsp;</td>
            <td colspan="13" class="kotak tengah">Specimen Tanda Tangan</td>
            <td>&nbsp;</td>
            <td colspan="14" class="tengah"><?= $config['nama_desa'] ?>, <?= tgl_indo(date('Y-m-d')); ?></td>
        </tr>
        <tr>
            <td >&nbsp;</td>
            <td colspan="8" rowspan="6" class="kotak tengah">&nbsp;</td>
            <td >&nbsp;</td>
            <td colspan="8" rowspan="6" class="kotak tengah">&nbsp;</td>
            <td >&nbsp;</td>
            <td colspan="13" rowspan="5" class="kotak tengah">&nbsp;</td>
            <td >&nbsp;</td>
            <td colspan="14" class="tengah">Pemohon</td>
        </tr>
        <tr>
            <td >&nbsp;</td>
            <td >&nbsp;</td>
            <td >&nbsp;</td>
            <td >&nbsp;</td>
            <td colspan="14"></td>
        </tr>
        <tr>
            <td >&nbsp;</td>
            <td >&nbsp;</td>
            <td >&nbsp;</td>
            <td >&nbsp;</td>
            <td colspan="14"></td>
        </tr>
        <tr>
            <td >&nbsp;</td>
            <td >&nbsp;</td>
            <td >&nbsp;</td>
            <td >&nbsp;</td>
            <td colspan="14"></td>
        </tr>
        <tr>
            <td >&nbsp;</td>
            <td >&nbsp;</td>
            <td >&nbsp;</td>
            <td >&nbsp;</td>
            <td colspan="14" class="tengah"><strong>( <?= padded_string_center(strtoupper($individu['nama']), 5)?> )</strong></td>
        </tr>
        <tr>
            <td >&nbsp;</td>
            <td >&nbsp;</td>
            <td >&nbsp;</td>
            <td colspan="13" class="tengah">Ket. Cap Jempol / Tanda Tangan</td>
            <td >&nbsp;</td>
            <td colspan="14"></td>
        </tr>
        <tr><td colspan="48">&nbsp;</td></tr>
        <tr><td colspan="47" class="tengah">Mengetahui</td></tr>
        <tr>
            <td colspan="20" class="tengah">Camat <?= $config['nama_kecamatan']; ?></td>
            <td colspan="8" class="tengah">&nbsp;</td>
            <td colspan="20" class="tengah"><?= $penandatangan['atas_nama']?></td>
        </tr>
        <tr><td colspan="48">&nbsp;</td></tr>
        <tr><td colspan="48">&nbsp;</td></tr>
        <tr><td colspan="48">&nbsp;</td></tr>
        <tr>
            <td colspan="20" class="tengah">( <?= str_pad('.', 60, '.', STR_PAD_LEFT); ?> )</td>
            <td colspan="8" class="tengah">&nbsp;</td>
            <td colspan="20" class="tengah"><strong>( <?= padded_string_center(strtoupper($penandatangan['nama']), 5) ?> )</strong></td>
        </tr>
        <tr>
            <td colspan="20" class="tengah"><?= 'NIP&nbsp;&nbsp;:&nbsp;' . str_pad('', 40 * 6, '&nbsp;', STR_PAD_LEFT)?></td>
            <td colspan="8" class="tengah">&nbsp;</td>
            <td colspan="20" class="tengah"><?= 'NIP&nbsp;&nbsp;:&nbsp;' . $penandatangan['nip']?></td>
        </tr>
        <tr><td colspan="48">&nbsp;</td></tr>
        <tr><td colspan="48">&nbsp;</td></tr>
        <tr><td colspan="48">&nbsp;</td></tr>
    </table>
</page>
