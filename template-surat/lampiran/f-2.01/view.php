<?php defined('BASEPATH') || exit('No direct script access allowed'); ?>

<style type="text/css">
    <?php include FCPATH . '/assets/css/lampiran-surat.css'; ?>
</style>

<page orientation="portrait" format="210x330" style="font-size: 7pt">

    <!-- Awal Halaman 1 -->
    <table id="kode" align="right">
        <tr><td><strong>Kode . F-2.01</strong></td></tr>
    </table>
    <table id="kop" class="disdukcapil">
        <col span="48" style="width: 2.0833%;">
        <tr><td colspan="48">&nbsp;</td></tr>
        <tr>
            <td colspan="10">Pemerintah Desa/Kelurahan</td>
            <td>: </td>
            <td colspan="7"><?= $config['nama_desa']; ?></td>
            <td colspan="30">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="10">Kecamatan</td>
            <td>: </td>
            <td colspan="7"><?= $config['nama_kecamatan']; ?></td>
            <td colspan="30">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="10">Kabupaten/Kota</td>
            <td>:</td>
            <td colspan="7"><?= $config['nama_kabupaten']; ?></td>
            <td colspan="30">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="11">&nbsp;</td>
            <?php for ($i = 0; $i < 10; $i++): ?>
                <td style="border-bottom: 1px solid black;">&nbsp;</td>
            <?php endfor; ?>
            <td colspan="30">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="10">Kode Wilayah</td>
            <td style="border-right: 1px solid black;">:</td>
            <?= kotak($config['kode_desa'], 10); ?>
        </tr>
        <tr>
            <?php for ($i = 0; $i < 48; $i++): ?>
                <td>&nbsp;</td>
            <?php endfor; ?>
        </tr>
    </table>

    <p style="text-align: center; margin: 0px; padding: 0px;">
        <strong style="font-size: 9pt;">FORMULIR PELAPORAN PENCATATAN SIPIL DI DALAM WILAYAH NKRI</strong>
    </p>

    <p><b>Jenis Pelaporan Pencatatan Sipil</b></p>
    <table class="disdukcapil" style="margin-top: -5px; border: 0px;">
        <col span="48" style="width: 2.0833%;">
        <tr>
            <?= checklist($format_f201, 1); ?>
            <td colspan="24">Kelahiran</td>
            <?= checklist($format_f201, 9); ?>
            <td colspan="29">Pengakuan Anak</td>
        </tr>
        <tr>
            <?= checklist($format_f201, 2); ?>
            <td colspan="24">Lahir Mati</td>
            <?= checklist($format_f201, 10); ?>
            <td colspan="29">Pengesahan Anak</td>
        </tr>
        <tr>
            <?= checklist($format_f201, 3); ?>
            <td colspan="24">Perkawinan</td>
            <?= checklist($format_f201, 11); ?>
            <td colspan="29">Perubahan Nama</td>
        </tr>
        <tr>
            <?= checklist($format_f201, 4); ?>
            <td colspan="24">Pembatalan Perkawinan</td>
            <?= checklist($format_f201, 12); ?>
            <td colspan="29">Perubahan Status Kewarganegaraan</td>
        </tr>
        <tr>
            <?= checklist($format_f201, 5); ?>
            <td colspan="24">Perceraian</td>
            <?= checklist($format_f201, 13); ?>
            <td colspan="29">Pencatatan Peristiwa Penting Lainnya</td>
        </tr>
        <tr>
            <?= checklist($format_f201, 6); ?>
            <td colspan="24">Pembatalan Perceraian</td>
            <?= checklist($format_f201, 14); ?>
            <td colspan="29">Pembetulan Akta</td>
        </tr>
        <tr>
            <?= checklist($format_f201, 7); ?>
            <td colspan="24">Kematian</td>
            <?= checklist($format_f201, 15) ?>
            <td colspan="29">Pembatalan Akta</td>
        </tr>
        <tr>
            <?= checklist($format_f201, 8); ?>
            <td colspan="24">Pengangkatan Anak</td>
            <?= checklist($format_f201, 16); ?>
            <td colspan="29">Pelaporan Pencatatan Sipil dari Luar Wilayah NKRI</td>
        </tr>
    </table>

    <?php if ($tampil_data_pelapor): ?>
    <table class="disdukcapil" style="margin-top: 5px;">
        <col span="48" style="width: 2.0833%;">
        <tr>
            <td colspan="48"><strong>DATA PELAPOR</strong></td>
        </tr>
        <tr>
            <td colspan="21">Nama</td>
            <td class="kanan">:</td>
            <?= kotak($input['nama_pelapor']); ?>
        </tr>
        <tr>
            <td colspan="21">NIK</td>
            <td class="kanan">:</td>
            <?= kotak($input['nik_pelapor'], 16); ?>
        </tr>
        <tr>
            <td colspan="21">Nomor Dokumen Perjalanan*</td>
            <td class="kanan">:</td>
            <?= kotak($input['dokumen_perjalanan_pelapor'], 16); ?>
        </tr>
        <tr>
            <td colspan="21">Nomor Kartu Keluarga</td>
            <td class="kanan">:</td>
            <?= kotak($input['no_kk_pelapor'], 16); ?>
        </tr>
        <tr>
            <td colspan="21">Kewarganegaraan</td>
            <td class="kanan">:</td>
            <?= kotak($input['kewarganegaraan_pelapor']); ?>
        </tr>
    </table>
    <?php endif ?>

    <?php if ($tampil_data_subjek_akta_1): ?>
    <table class="disdukcapil" style="margin-top: 5px;">
        <col span="48" style="width: 2.0833%;">
        <tr>
            <td colspan="48"><strong>DATA SUBJEK AKTA KESATU</strong></td>
        </tr>
        <tr>
            <td colspan="21">Nama</td>
            <td class="kanan">:</td>
            <?= kotak($input['nama_akta1']); ?>
        </tr>
        <tr>
            <td colspan="21">NIK</td>
            <td class="kanan">:</td>
            <?= kotak($input['nik_akta1'], 16); ?>
        </tr>
        <tr>
            <td colspan="21">Nomor Dokumen Perjalanan*</td>
            <td class="kanan">:</td>
            <?= kotak($input['dokumen_perjalanan_akta1']); ?>
        </tr>
        <tr>
            <td colspan="21">Nomor Kartu Keluarga</td>
            <td class="kanan">:</td>
            <?= kotak($input['no_kk_akta1'], 16); ?>
        </tr>
        <tr>
            <td colspan="21">Kewarganegaraan</td>
            <td class="kanan">:</td>
            <?= kotak($input['kewarganegaraan_akta1']); ?>
        </tr>
    </table>
    <?php endif ?>

    <?php if ($tampil_data_subjek_akta_2): ?>
    <table class="disdukcapil" style="margin-top: 5px;">
        <col span="48" style="width: 2.0833%;">
        <tr>
            <td colspan="48"><strong>DATA SUBJEK AKTA KEDUA (JIKA ADA)</strong></td>
        </tr>
        <tr>
            <td colspan="21">Nama</td>
            <td class="kanan">:</td>
            <?= kotak($input['nama_akta2']); ?>
        </tr>
        <tr>
            <td colspan="21">NIK</td>
            <td class="kanan">:</td>
            <?= kotak($input['nik_akta2'], 16); ?>
        </tr>
        <tr>
            <td colspan="21">Nomor Dokumen Perjalanan*</td>
            <td class="kanan">:</td>
            <?= kotak($input['dokumen_perjalanan_akta2']); ?>
        </tr>
        <tr>
            <td colspan="21">Nomor Kartu Keluarga</td>
            <td class="kanan">:</td>
            <?= kotak($input['no_kk_akta2'], 16); ?>
        </tr>
        <tr>
            <td colspan="21">Kewarganegaraan</td>
            <td class="kanan">:</td>
            <?= kotak($input['kewarganegaraan_akta2']); ?>
        </tr>
    </table>
    <?php endif ?>

    <?php if ($tampil_data_saksi): ?>
    <table class="disdukcapil" style="margin-top: 5px;">
        <col span="48" style="width: 2.0833%;">
        <tr>
            <td colspan="48"><strong>DATA SAKSI I</strong></td>
        </tr>
        <tr>
            <td colspan="21">Nama</td>
            <td class="kanan">:</td>
            <?= kotak($input['nama_saksi1']); ?>
        </tr>
        <tr>
            <td colspan="21">NIK</td>
            <td class="kanan">:</td>
            <?= kotak($input['nik_saksi1'], 16); ?>
        </tr>
        <tr>
            <td colspan="21">Nomor Kartu Keluarga</td>
            <td class="kanan">:</td>
            <?= kotak($input['no_kk_saksi1'], 16); ?>
        </tr>
        <tr>
            <td colspan="21">Kewarganegaraan</td>
            <td class="kanan">:</td>
            <?= kotak($input['kewarganegaraan_saksi1']); ?>
        </tr>
        <tr>
            <td colspan="48"><strong><u>DATA SAKSI II</u></strong></td>
        </tr>
        <tr>
            <td colspan="21">Nama</td>
            <td class="kanan">:</td>
            <?= kotak($input['nama_saksi2']); ?>
        </tr>
        <tr>
            <td colspan="21">NIK</td>
            <td class="kanan">:</td>
            <?= kotak($input['nik_saksi2'], 16); ?>
        </tr>
        <tr>
            <td colspan="21">Nomor Kartu Keluarga</td>
            <td class="kanan">:</td>
            <?= kotak($input['no_kk_saksi2'], 16); ?>
        </tr>
        <tr>
            <td colspan="21">Kewarganegaraan</td>
            <td class="kanan">:</td>
            <?= kotak($input['kewarganegaraan_saksi2']); ?>
        </tr>
    </table>
    <?php endif ?>

    <?php if ($tampil_data_orang_tua): ?>
    <table class="disdukcapil" style="margin-top: 5px;">
        <col span="48" style="width: 2.0833%;">
        <tr>
            <td colspan="48"><strong>DATA ORANG TUA** <i>( hanya diisi untuk keperluan pencatatan kelahiran, lahir mati dan kematian)</i></strong></td>
        </tr>
        <tr>
            <td colspan="21">Nama Ayah</td>
            <td class="kanan">:</td>
            <?= kotak($input['nama_ayah']); ?>
        </tr>
        <tr>
            <td colspan="21">NIK Ayah</td>
            <td class="kanan">:</td>
            <?= kotak($input['nik_ayah'], 16); ?>
        </tr>
        <tr>
            <td colspan="21">Tempat Lahir Ayah</td>
            <td class="kanan">:</td>
            <?= kotak($input['tempat_lahir_ayah']); ?>
        </tr>
        <tr>
            <td colspan="21">Tanggal Lahir Ayah</td>
            <td class="kanan">:</td>
            <td>Tgl</td>
            <td class="kanan">:</td>
            <?= kotak(date('dd', strtotime($input['tanggal_lahir_ayah'])), 2); ?>
            <td>Bln</td>
            <td class="kanan">:</td>
            <?= kotak(date('mm', strtotime($input['tanggal_lahir_ayah'])), 2); ?>
            <td>Thn</td>
            <td class="kanan">:</td>
            <?= kotak(date('Y', strtotime($input['tanggal_lahir_ayah'])), 4); ?>
        </tr>
        <tr>
            <td colspan="21">Kewarganegaraan</td>
            <td class="kanan">:</td>
            <?= kotak($input['kewarganegaraan_ayah']); ?>
        </tr>
        <tr>
            <td colspan="21">Nama Ibu</td>
            <td class="kanan">:</td>
            <?= kotak($input['nama_ibu']); ?>
        </tr>
        <tr>
            <td colspan="21">NIK Ibu</td>
            <td class="kanan">:</td>
            <?= kotak($input['nik_ibu'], 16); ?>
        </tr>
        <tr>
            <td colspan="21">Tempat Lahir Ibu</td>
            <td class="kanan">:</td>
            <?= kotak($input['tempat_lahir_ibu']); ?>
        </tr>
        <tr>
            <td colspan="21">Tanggal Lahir Ibu</td>
            <td class="kanan">:</td>
            <td>Tgl</td>
            <td class="kanan">:</td>
            <?= kotak(date('dd', strtotime($input['tanggal_lahir_ibu'])), 2); ?>
            <td>Bln</td>
            <td class="kanan">:</td>
            <?= kotak(date('mm', strtotime($input['tanggal_lahir_ibu'])), 2); ?>
            <td>Thn</td>
            <td class="kanan">:</td>
            <?= kotak(date('Y', strtotime($input['tanggal_lahir_ibu'])), 4); ?>
        </tr>
        <tr>
            <td colspan="21">Kewarganegaraan</td>
            <td class="kanan">:</td>
            <?= kotak($input['kewarganegaraan_ibu']); ?>
        </tr>
    </table>
    <?php endif ?>

    <?php if ($tampil_data_anak): ?>
    <table class="disdukcapil" style="margin-top: 5px;">
        <col span="48" style="width: 2.0833%;">
        <tr>
            <td colspan="48"><strong>DATA ANAK</strong></td>
        </tr>
        <tr>
            <td>1.</td>
            <td colspan="20">Nama</td>
            <td class="kanan">:</td>
            <?= kotak($input['nama_bayi']); ?>
        </tr>
        <tr>
            <td>2.</td>
            <td colspan="20">Jenis kelamin</td>
            <td class="kanan">:</td>
            <?= checklist($input['sex'], 1); ?>
            <td colspan="6">1. Laki-laki </td>
            <?= checklist($input['sex'], 2); ?>
            <td colspan="7">2. Perempuan </td>
        </tr>
        <tr>
            <td>3.</td>
            <td colspan="20">Tempat dilahirkan</td>
            <td class="kanan">:</td>
            <?= checklist($input['tempat_dilahirkan'], 1); ?>
            <td colspan="4">1. RS/SB</td>
            <?= checklist($input['tempat_dilahirkan'], 2); ?>
            <td colspan="5">2. Puskesmas </td>
            <?= checklist($input['tempat_dilahirkan'], 3); ?>
            <td colspan="4">3. Polindes</td>
            <?= checklist($input['tempat_dilahirkan'], 4); ?>
            <td colspan="4">4. Rumah </td>
            <?= checklist($input['tempat_dilahirkan'], 5); ?>
            <td colspan="4">5. Lainnya &nbsp; </td>
        </tr>
        <tr>
            <td>4.</td>
            <td colspan="20">Tempat kelahiran </td>
            <td class="kanan">:</td>
            <?= kotak($input['tempatlahir']); ?>
        </tr>
        <tr>
            <td>5.</td>
            <td colspan="20">Hari dan Tanggal Lahir </td>
            <td class="kanan">:</td>
            <td>Hari</td>
            <td class="kanan">:</td>
            <?= kotak($input['hari'], 6); ?>
            <td colspan="4">&nbsp;</td>
            <td>Tgl</td>
            <td class="kanan">:</td>
            <?= kotak(date('dd', strtotime($input['tanggallahir'])), 2); ?>
            <td>Bln</td>
            <td class="kanan">:</td>
            <?= kotak(date('mm', strtotime($input['tanggallahir'])), 2); ?>
            <td>Thn</td>
            <td class="kanan">:</td>
            <?= kotak(date('Y', strtotime($input['tanggallahir'])), 4); ?>
        </tr>
        <tr>
            <td>6.</td>
            <td colspan="20">Pukul </td>
            <td class="kanan">:</td>
            <?= kotak($input['waktu_lahir'], 5); ?>
        </tr>
        <tr>
            <td>7.</td>
            <td colspan="20">Jenis kelahiran </td>
            <td class="kanan">:</td>
            <?= checklist($input['jenis_kelahiran'], 1); ?>
            <td colspan="4">1. Tunggal</td>
            <?= checklist($input['jenis_kelahiran'], 2); ?>
            <td colspan="4">2. Kembar 2 </td>
            <?= checklist($input['jenis_kelahiran'], 3); ?>
            <td colspan="5">3. Kembar 3</td>
            <?= checklist($input['jenis_kelahiran'], 4); ?>
            <td colspan="4">4. Kembar 4 </td>
            <?= checklist($input['jenis_kelahiran'], 5); ?>
            <td colspan="4">5. Lainnya &nbsp; </td>
        </tr>
        <tr>
            <td>8.</td>
            <td colspan="20">Kelahiran anak ke- </td>
            <td class="kanan">:</td>
            <?= kotak(date('Y', strtotime($input['kelahiran_anak_ke'])), 1); ?>
        </tr>
        <tr>
            <td>9.</td>
            <td colspan="20">Penolong kelahiran</td>
            <td class="kanan">:</td>
            <?= checklist($input['penolong_kelahiran'], 1); ?>
            <td colspan="4">1. Dokter</td>
            <?= checklist($input['penolong_kelahiran'], 2); ?>
            <td colspan="6">2. Bidan/Perawat</td>
            <?= checklist($input['penolong_kelahiran'], 3); ?>
            <td colspan="4">3. Dukun</td>
            <?= checklist($input['penolong_kelahiran'], 4); ?>
            <td colspan="4">4. Lainnya</td>
        </tr>
        <tr>
            <td>10.</td>
            <td colspan="20">Berat Bayi</td>
            <td class="kanan">:</td>
            <td colspan="2" class="kotak padat tengah">
                <?= $input['berat_lahir'] ?>
            </td>
            <td> gram</td>
        </tr>
        <tr>
            <td>11.</td>
            <td colspan="20">Panjang Bayi</td>
            <td class="kanan">:</td>
            <td colspan="2" class="kotak padat tengah">
                <?= $input['panjang_lahir'] ?>
            </td>
            <td> cm</td>
        </tr>
    </table>
    <?php endif ?>
    <!-- Akhir Halaman 1 -->


    <!-- Awal Halaman 2 -->
    <?php if ($tampil_data_lahir_mati): ?>
    <table class="disdukcapil" style="margin-top: 5px;">
        <col span="48" style="width: 2.0833%;">
        <tr>
            <td colspan="48"><strong>YANG LAHIR MATI</strong></td>
        </tr>
        <tr>
            <td>1.</td>
            <td colspan="20">Lamanya dalam kandungan</td>
            <td class="kanan">:</td>
            <?= kotak(date('Y', strtotime($input['lamanya_dalam_kandungan']))); ?>
        </tr>
        <tr>
            <td>2.</td>
            <td colspan="20">Jenis kelamin</td>
            <td class="kanan">:</td>
            <?= checklist($input['sex'], 1); ?>
            <td colspan="6">1. Laki-laki </td>
            <?= checklist($input['sex'], 2); ?>
            <td colspan="7">2. Perempuan </td>
            <td colspan="11">&nbsp;</td>
        </tr>
        <tr>
            <td>3.</td>
            <td colspan="20">Tanggal lahir mati</td>
            <td class="kanan">:</td>
            <td>Tgl</td>
            <td class="kanan">:</td>
            <?= kotak(date('dd', strtotime($input['tanggal_lahir_lahir_mati'])), 2); ?>
            <td>Bln</td>
            <td class="kanan">:</td>
            <?= kotak(date('mm', strtotime($input['tanggal_lahir_lahir_mati'])), 2); ?>
            <td>Thn</td>
            <td class="kanan">:</td>
            <?= kotak(date('Y', strtotime($input['tanggal_lahir_lahir_mati'])), 2); ?>
        </tr>
        <tr>
            <td>4.</td>
            <td colspan="20">Jenis kelahiran </td>
            <td class="kanan">:</td>
            <?= checklist($input['jenis_kelahiran_lahir_mati'], 1); ?>
            <td colspan="4">1. Tunggal</td>
            <?= checklist($input['jenis_kelahiran_lahir_mati'], 2); ?>
            <td colspan="4">2. Kembar 2 </td>
            <?= checklist($input['jenis_kelahiran_lahir_mati'], 3); ?>
            <td colspan="5">3. Kembar 3</td>
            <?= checklist($input['jenis_kelahiran_lahir_mati'], 4); ?>
            <td colspan="4">4. Kembar 4 </td>
            <?= checklist($input['jenis_kelahiran_lahir_mati'], 5); ?>
            <td colspan="4">5. Lainnya &nbsp; </td>
        </tr>
        <tr>
            <td>5.</td>
            <td colspan="20">Anak ke- </td>
            <td class="kanan">:</td>
            <?= kotak(date('Y', strtotime($input['anak_ke_yg_lahir_mati']))); ?>
        </tr>
        <tr>
            <td>6.</td>
            <td colspan="20">Tempat dilahirkan </td>
            <td class="kanan">:</td>
            <?= checklist($input['tempat_dilahirkan_lahir_mati'], 1); ?>
            <td colspan="4">1. RS/SB</td>
            <?= checklist($input['tempat_dilahirkan_lahir_mati'], 2); ?>
            <td colspan="5">2. Puskesmas </td>
            <?= checklist($input['tempat_dilahirkan_lahir_mati'], 3); ?>
            <td colspan="4">3. Polindes</td>
            <?= checklist($input['tempat_dilahirkan_lahir_mati'], 4); ?>
            <td colspan="4">4. Rumah </td>
            <?= checklist($input['tempat_dilahirkan_lahir_mati'], 5); ?>
            <td colspan="4">5. Lainnya &nbsp; </td>
        </tr>
        <tr>
            <td>7.</td>
            <td colspan="20">Penolong kelahiran </td>
            <td class="kanan">:</td>
            <?= checklist($input['penolong_kelahiran_lahir_mati'], 1); ?>
            <td colspan="4">1. Dokter</td>
            <?= checklist($input['penolong_kelahiran_lahir_mati'], 2); ?>
            <td colspan="6">2. Bidan/Perawat</td>
            <?= checklist($input['penolong_kelahiran_lahir_mati'], 3); ?>
            <td colspan="4">3. Dukun</td>
            <?= checklist($input['penolong_kelahiran_lahir_mati'], 4); ?>
            <td colspan="4">4. Lainnya</td>
        </tr>
        <tr>
            <td>8.</td>
            <td colspan="20">Sebab lahir mati</td>
            <td class="kanan">:</td>
            <?= kotak(date('Y', strtotime($input['sebab_lahir_mati']))); ?>
        </tr>
        <tr>
            <td>9.</td>
            <td colspan="20">Yang menentukan</td>
            <td class="kanan">:</td>
            <?= checklist($input['penentu_kelahiran_lahir_mati'], 1); ?>
            <td colspan="4">1. Dokter</td>
            <?= checklist($input['penentu_kelahiran_lahir_mati'], 2); ?>
            <td colspan="6">2. Bidan/Perawat</td>
            <?= checklist($input['penentu_kelahiran_lahir_mati'], 3); ?>
            <td colspan="4">3. Tenaga Kes</td>
            <?= checklist($input['penentu_kelahiran_lahir_mati'], 4); ?>
            <td colspan="4">4. Kepolisian</td>
            <?= checklist($input['penentu_kelahiran_lahir_mati'], 5); ?>
            <td colspan="4">5. Lainnya</td>
        </tr>
        <tr>
            <td>10.</td>
            <td colspan="20">Tempat kelahiran</td>
            <td class="kanan">:</td>
            <?= kotak($input['tempat_kelahiran']); ?>
        </tr>
    </table>
    <?php endif ?>

    <?php if ($tampil_data_perkawinan): ?>
    <table class="disdukcapil" style="margin-top: 5px;">
        <col span="48" style="width: 2.0833%;">
        <tr>
            <td colspan="48"><strong>PERKAWINAN ATAU PEMBATALAN PERKAWINAN</strong></td>
        </tr>
        <tr>
            <td>1.</td>
            <td colspan="20">NIK Ayah dari Suami</td>
            <td class="kanan">:</td>
            <?= kotak($input['nik_ayah_dari_suami'], 16); ?>
        </tr>
        <tr>
            <td>2.</td>
            <td colspan="20">Nama Ayah dari Suami</td>
            <td class="kanan">:</td>
            <?= kotak(date('Y', strtotime($input['nama_ayah_dari_suami_perkawinan']))); ?>
        </tr>
        <tr>
            <td>3.</td>
            <td colspan="20">NIK Ibu dari Suami</td>
            <td class="kanan">:</td>
            <?= kotak($input['nik_ibu_dari_suami'], 16); ?>
        </tr>
        <tr>
            <td>4.</td>
            <td colspan="20">Nama Ibu dari Suami</td>
            <td class="kanan">:</td>
            <?= kotak(date('Y', strtotime($input['nama_ibu_dari_suami_perkawinan']))); ?>
        </tr>
        <tr>
            <td>5.</td>
            <td colspan="20">NIK Ayah dari Istri</td>
            <td class="kanan">:</td>
            <?= kotak($input['nik_ayah_dari_istri'], 16); ?>
        </tr>
        <tr>
            <td>6.</td>
            <td colspan="20">Nama Ayah dari Istri</td>
            <td class="kanan">:</td>
            <?= kotak(date('Y', strtotime($input['nama_ayah_dari_istri_perkawinan']))); ?>
        </tr>
        <tr>
            <td>7.</td>
            <td colspan="20">NIK Ibu dari Istri</td>
            <td class="kanan">:</td>
            <?= kotak($input['nik_ibu_dari_istri'], 16); ?>
        </tr>
        <tr>
            <td>8.</td>
            <td colspan="20">Nama Ibu dari Istri</td>
            <td class="kanan">:</td>
            <?= kotak(date('Y', strtotime($input['nama_ibu_dari_istri_perkawinan']))); ?>
        </tr>
        <tr>
            <td>9.</td>
            <td colspan="20">Status Perkawinan Sebelum Kawin</td>
            <td class="kanan">:</td>
            <?= checklist($input['perkawinan_status_kawin'], 1); ?>
            <td colspan="4">1. Kawin </td>
            <?= checklist($input['perkawinan_status_kawin'], 2); ?>
            <td colspan="5">2. Belum Kawin </td>
            <?= checklist($input['perkawinan_status_kawin'], 3); ?>
            <td colspan="5">3. Cerai Hidup </td>
            <?= checklist($input['perkawinan_status_kawin'], 4); ?>
            <td colspan="5">4. Cerai Mati </td>
        </tr>
        <tr>
            <td>10.</td>
            <td colspan="20">Perkawinan yang ke-</td>
            <td class="kanan">:</td>
            <?= kotak(date('Y', strtotime($input['perkawinan_anak_ke_perkawinan'])), 1); ?>
        </tr>
        <tr>
            <td>11.</td>
            <td colspan="20">Istri yang ke-</td>
            <td class="kanan">:</td>
            <?= kotak(date('Y', strtotime($input['istri_yg_ke_perkawinan'])), 1); ?>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td colspan="47">(bagi yang poligami)</td>
        </tr>
        <tr>
            <td>12.</td>
            <td colspan="20">Tanggal Pemberkatan</td>
            <td class="kanan">:</td>
            <td>Tgl</td>
            <td class="kanan">:</td>
            <?= kotak(date('dd', strtotime($input['tanggal_pemberkatan_perkawinan'])), 2); ?>
            <td>Bln</td>
            <td class="kanan">:</td>
            <?= kotak(date('mm', strtotime($input['tanggal_pemberkatan_perkawinan'])), 2); ?>
            <td>Thn</td>
            <td class="kanan">:</td>
            <?= kotak(date('Y', strtotime($input['tanggal_pemberkatan_perkawinan'])), 2); ?>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td colspan="47">Perkawinan</td>
        </tr>
        <tr>
            <td>13.</td>
            <td colspan="20">Tanggal Melapor</td>
            <td class="kanan">:</td>
            <td>Tgl</td>
            <td class="kanan">:</td>
            <?= kotak(date('dd', strtotime($input['tanggal_melapor_perkawinan'])), 2); ?>
            <td>Bln</td>
            <td class="kanan">:</td>
            <?= kotak(date('mm', strtotime($input['tanggal_melapor_perkawinan'])), 2); ?>
            <td>Thn</td>
            <td class="kanan">:</td>
            <?= kotak(date('Y', strtotime($input['tanggal_melapor_perkawinan'])), 2); ?>
        </tr>
        <tr>
            <td>14.</td>
            <td colspan="20">Jam Pelaporan</td>
            <td class="kanan">:</td>
            <?= kotak($input['waktu_perkwinan'], 5); ?>
        </tr>
        <tr>
            <td>15.</td>
            <td colspan="20">Agama</td>
            <td class="kanan">:</td>
            <?= checklist($input['agama_perkawinan'], 1); ?>
            <td colspan="3">1. Islam</td>
            <?= checklist($input['agama_perkawinan'], 2); ?>
            <td colspan="3">2. Kristen</td>
            <?= checklist($input['agama_perkawinan'], 3); ?>
            <td colspan="3">3. Katolik</td>
            <?= checklist($input['agama_perkawinan'], 4); ?>
            <td colspan="3">4. Hindu</td>
            <?= checklist($input['agama_perkawinan'], 5); ?>
            <td colspan="3">5. Budha</td>
            <?= checklist($input['agama_perkawinan'], 6); ?>
            <td colspan="5">6. Konghuchu</td>
        </tr>
        <tr>
            <td>16.</td>
            <td colspan="20">Kepercayaan </td>
            <td class="kanan">:</td>
            <td class="kotak padat tengah"><?= $input['kepercayaan_perkawinan'] ?></td>
        </tr>
        <tr>
            <td>17.</td>
            <td colspan="20">Nama Organisasi</td>
            <td class="kanan">:</td>
            <?= kotak(date('Y', strtotime($input['nama_organisasi_perkawinan']))); ?>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td colspan="47">Kepercayaan</td>
        </tr>
        <tr>
            <td>18.</td>
            <td colspan="20">Nama Pengadilan </td>
            <td class="kanan">:</td>
            <?= kotak(date('Y', strtotime($input['nama_pengadilan_perkawinan']))); ?>
        </tr>
        <tr>
            <td>19.</td>
            <td colspan="20">Nomor Penetapan</td>
            <td class="kanan">:</td>
            <?= kotak(date('Y', strtotime($input['nomor_penetapan_perkawinan']))); ?>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td colspan="47">Pengadilan</td>
        </tr>
        <tr>
            <td>20.</td>
            <td colspan="20">Tanggal Penetapan</td>
            <td class="kanan">:</td>
            <td>Tgl</td>
            <td class="kanan">:</td>
            <?= kotak(date('dd', strtotime($input['tanggal_penetapan_pengadilan'])), 2); ?>
            <td>Bln</td>
            <td class="kanan">:</td>
            <?= kotak(date('mm', strtotime($input['tanggal_penetapan_pengadilan'])), 2); ?>
            <td>Thn</td>
            <td class="kanan">:</td>
            <?= kotak(date('Y', strtotime($input['tanggal_penetapan_pengadilan'])), 2); ?>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td colspan="47">Pengadilan</td>
        </tr>
        <tr>
            <td>21.</td>
            <td colspan="20">Nama Pemuka Agama/</td>
            <td class="kanan">:</td>
            <?= kotak(date('Y', strtotime($input['nama_pemuka_agama']))); ?>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td colspan="47">Kepercayaan</td>
        </tr>
        <tr>
            <td>22.</td>
            <td colspan="20">Nomor Surat Izin</td>
            <td class="kanan">:</td>
            <?= kotak(date('Y', strtotime($input['nomor_surat_izin'])), 16); ?>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td colspan="47">dari Perwakilan</td>
        </tr>
        <tr>
            <td>23.</td>
            <td colspan="20">Nomor Pasport</td>
            <td class="kanan">:</td>
            <?= kotak(date('Y', strtotime($input['nomor_pasport'])), 16); ?>
        </tr>
        <tr>
            <td>24.</td>
            <td colspan="20">Perjanjian Perkawinan</td>
            <td class="kanan">:</td>
            <?= kotak(date('Y', strtotime($input['perjanjian_perkawinan'])), 16); ?>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td colspan="47">dibuat oleh Notaris</td>
        </tr>
        <tr>
            <td>25.</td>
            <td colspan="20">Nomor Akta Notaris</td>
            <td class="kanan">:</td>
            <?= kotak(date('Y', strtotime($input['nomor_akta_notaris'])), 16); ?>
        </tr>
        <tr>
            <td>26.</td>
            <td colspan="20">Tanggal Akta Notaris</td>
            <td class="kanan">:</td>
            <?= kotak(date('Y', strtotime($input['tanggal_akta_notaris'])), 16); ?>
        </tr>
        <tr>
            <td>27.</td>
            <td colspan="20">Jumlah Anak (jika ada</td>
            <td class="kanan">:</td>
            <?= kotak(date('Y', strtotime($input['jumlah_anak'])), 16); ?>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td colspan="47">agar mengisi formulir</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td colspan="47">tambahan nama anak</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td colspan="47">dan akta kelahiran anak)</td>
        </tr>
        <tr>
            <td colspan="48">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="48"><strong>Bagi Pemohon Pembatalan Perkawinan Harap Mengisi Data di bawah ini:</strong></td>
        </tr>
        <tr>
            <td>1.</td>
            <td colspan="20">Tanggal Perkawinan</td>
            <td class="kanan">:</td>
            <td>Tgl</td>
            <td class="kanan">:</td>
            <?= kotak(date('dd', strtotime($input['tanggal_perkawinan'])), 2); ?>
            <td>Bln</td>
            <td class="kanan">:</td>
            <?= kotak(date('mm', strtotime($input['tanggal_perkawinan'])), 2); ?>
            <td>Thn</td>
            <td class="kanan">:</td>
            <?= kotak(date('Y', strtotime($input['tanggal_perkawinan'])), 2); ?>
        </tr>
        <tr>
            <td>2.</td>
            <td colspan="20">Nomor Akta Perkawinan</td>
            <td class="kanan">:</td>
            <?= kotak($input['nomor_akta_perkawinan'], 16); ?>
        </tr>
        <tr>
            <td>3.</td>
            <td colspan="20">Tanggal Akta Perkawinan</td>
            <td class="kanan">:</td>
            <td>Tgl</td>
            <td class="kanan">:</td>
            <?= kotak(date('dd', strtotime($input['tanggal_akta_perkawinan'])), 2); ?>
            <td>Bln</td>
            <td class="kanan">:</td>
            <?= kotak(date('mm', strtotime($input['tanggal_akta_perkawinan'])), 2); ?>
            <td>Thn</td>
            <td class="kanan">:</td>
            <?= kotak(date('Y', strtotime($input['tanggal_akta_perkawinan'])), 2); ?>
        </tr>
        <tr>
            <td>4.</td>
            <td colspan="20">Nama Pengadilan</td>
            <td class="kanan">:</td>
            <?= kotak($input['nama_pengadilan'], 16); ?>
        </tr>
        <tr>
            <td>5.</td>
            <td colspan="20">Nomor Putusan Pengadilan</td>
            <td class="kanan">:</td>
            <?= kotak($input['nomor_putusan_pengadilan'], 16); ?>
        </tr>
        <tr>
            <td>6.</td>
            <td colspan="20">Tanggal Putusan Pengadilan</td>
            <td class="kanan">:</td>
            <td>Tgl</td>
            <td class="kanan">:</td>
            <?= kotak(date('dd', strtotime($input['tanggal_putusan_pengadilan'])), 2); ?>
            <td>Bln</td>
            <td class="kanan">:</td>
            <?= kotak(date('mm', strtotime($input['tanggal_putusan_pengadilan'])), 2); ?>
            <td>Thn</td>
            <td class="kanan">:</td>
            <?= kotak(date('Y', strtotime($input['tanggal_putusan_pengadilan'])), 2); ?>
        </tr>
        <tr>
            <td>7.</td>
            <td colspan="20">Tanggal Pelaporan Perkawinan</td>
            <td class="kanan">:</td>
            <td>Tgl</td>
            <td class="kanan">:</td>
            <?= kotak(date('dd', strtotime($input['tanggal_pelaporan_perkawinan_luar_negeri'])), 2); ?>
            <td>Bln</td>
            <td class="kanan">:</td>
            <?= kotak(date('mm', strtotime($input['tanggal_pelaporan_perkawinan_luar_negeri'])), 2); ?>
            <td>Thn</td>
            <td class="kanan">:</td>
            <?= kotak(date('Y', strtotime($input['tanggal_pelaporan_perkawinan_luar_negeri'])), 2); ?>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td colspan="47">di Luar Negeri</td>
        </tr>
    </table>
    <?php endif ?>

    <?php if ($tampil_data_perceraian): ?>
    <table class="disdukcapil" style="margin-top: 5px;">
        <col span="48" style="width: 2.0833%;">
        <tr>
            <td colspan="48"><strong>PERCERAIAN ATAU PEMBATALAN PERCERAIAN</strong></td>
        </tr>
        <tr>
            <td colspan="48">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="48"><strong>Yang mengajukan perceraian/pembatalan perceraian***</strong></td>
        </tr>
        <tr>
            <td>1.</td>
            <td colspan="20">Nomor Akta Perkawinan</td>
            <td class="kanan">:</td>
            <?= kotak($input['nomor_akta_perkawinan'], 16); ?>
        </tr>
        <tr>
            <td>2.</td>
            <td colspan="20">Tanggal akta Perkawinan</td>
            <td class="kanan">:</td>
            <td>Tgl</td>
            <td class="kanan">:</td>
            <?= kotak(date('dd', strtotime($input['tanggal_perkawinan'])), 2); ?>
            <td>Bln</td>
            <td class="kanan">:</td>
            <?= kotak(date('mm', strtotime($input['tanggal_perkawinan'])), 2); ?>
            <td>Thn</td>
            <td class="kanan">:</td>
            <?= kotak(date('Y', strtotime($input['tanggal_perkawinan'])), 2); ?>
        </tr>
        <tr>
            <td>2.</td>
            <td colspan="20">Nomor Akta Perkawinan</td>
            <td class="kanan">:</td>
            <?= kotak($input['nomor_akta_perkawinan'], 16); ?>
        </tr>
        <tr>
            <td>3.</td>
            <td colspan="20">Tanggal Akta Perkawinan</td>
            <td class="kanan">:</td>
            <td>Tgl</td>
            <td class="kanan">:</td>
            <?= kotak(date('dd', strtotime($input['tanggal_akta_perkawinan'])), 2); ?>
            <td>Bln</td>
            <td class="kanan">:</td>
            <?= kotak(date('mm', strtotime($input['tanggal_akta_perkawinan'])), 2); ?>
            <td>Thn</td>
            <td class="kanan">:</td>
            <?= kotak(date('Y', strtotime($input['tanggal_akta_perkawinan'])), 2); ?>
        </tr>
        <tr>
            <td>4.</td>
            <td colspan="20">Nama Pengadilan</td>
            <td class="kanan">:</td>
            <?= kotak($input['nama_pengadilan'], 16); ?>
        </tr>
        <tr>
            <td>5.</td>
            <td colspan="20">Tanggal Putusan Pengadilan</td>
            <td class="kanan">:</td>
            <td>Tgl</td>
            <td class="kanan">:</td>
            <?= kotak(date('dd', strtotime($input['tanggal_putusan_pengadilan'])), 2); ?>
            <td>Bln</td>
            <td class="kanan">:</td>
            <?= kotak(date('mm', strtotime($input['tanggal_putusan_pengadilan'])), 2); ?>
            <td>Thn</td>
            <td class="kanan">:</td>
            <?= kotak(date('Y', strtotime($input['tanggal_putusan_pengadilan'])), 2); ?>
        </tr>
        <tr>
            <td>6.</td>
            <td colspan="20">Nomor Putusan Pengadilan</td>
            <td class="kanan">:</td>
            <?= kotak($input['nomor_putusan_pengadilan'], 23); ?>
        </tr>
        <tr>
            <td>7.</td>
            <td colspan="20">Nomor Surat Keterangan Panitera Pengadilan</td>
            <td class="kanan">:</td>
            <?= kotak($input['nomor_surat_keterangan_panitera_pengadilan'], 16); ?>
        </tr>
        <tr>
            <td>8.</td>
            <td colspan="20">Tanggal Surat Keterangan Panitera Pengadilan</td>
            <td class="kanan">:</td>
            <td>Tgl</td>
            <td class="kanan">:</td>
            <?= kotak(date('dd', strtotime($input['tanggal_pelaporan_perkawinan_luar_negeri'])), 2); ?>
            <td>Bln</td>
            <td class="kanan">:</td>
            <?= kotak(date('mm', strtotime($input['tanggal_pelaporan_perkawinan_luar_negeri'])), 2); ?>
            <td>Thn</td>
            <td class="kanan">:</td>
            <?= kotak(date('Y', strtotime($input['tanggal_pelaporan_perkawinan_luar_negeri'])), 2); ?>
        </tr>
        <tr>
            <td>9.</td>
            <td colspan="20">Tanggal Melapor</td>
            <td class="kanan">:</td>
            <td>Tgl</td>
            <td class="kanan">:</td>
            <?= kotak(date('dd', strtotime($input['tanggal_melapor'])), 2); ?>
            <td>Bln</td>
            <td class="kanan">:</td>
            <?= kotak(date('mm', strtotime($input['tanggal_melapor'])), 2); ?>
            <td>Thn</td>
            <td class="kanan">:</td>
            <?= kotak(date('Y', strtotime($input['tanggal_melapor'])), 2); ?>
        </tr>
        <tr>
            <td colspan="48">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="48"><strong>Bagi Pemohon Pembatalan Perceraian Harap Mengisi Data di bawah ini:</strong></td>
        </tr>
        <tr>
            <td>1.</td>
            <td colspan="20">Nomor Akta Perceraian</td>
            <td class="kanan">:</td>
            <?= kotak($input['nomor_akta_perceraian'], 16); ?>
        </tr>
        <tr>
            <td>2.</td>
            <td colspan="20">Tanggal Akta Perceraian</td>
            <td class="kanan">:</td>
            <td>Tgl</td>
            <td class="kanan">:</td>
            <?= kotak(date('dd', strtotime($input['tanggal_lahir_ayah'])), 2); ?>
            <td>Bln</td>
            <td class="kanan">:</td>
            <?= kotak(date('mm', strtotime($input['tanggal_lahir_ayah'])), 2); ?>
            <td>Thn</td>
            <td class="kanan">:</td>
            <?= kotak(date('Y', strtotime($input['tanggal_lahir_ayah'])), 2); ?>
        </tr>
        <tr>
            <td>3.</td>
            <td colspan="20">Tanggal Pelaporan</td>
            <td class="kanan">:</td>
            <td>Tgl</td>
            <td class="kanan">:</td>
            <?= kotak(date('dd', strtotime($input['tanggal_pelaporan_pembatalan_perceraian'])), 2); ?>
            <td>Bln</td>
            <td class="kanan">:</td>
            <?= kotak(date('mm', strtotime($input['tanggal_pelaporan_pembatalan_perceraian'])), 2); ?>
            <td>Thn</td>
            <td class="kanan">:</td>
            <?= kotak(date('Y', strtotime($input['tanggal_pelaporan_pembatalan_perceraian'])), 2); ?>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td colspan="47">Perceraian dari Luar Negeri</td>
        </tr>
    </table>
    <?php endif ?>

    <?php if ($tampil_data_kematian): ?>
    <table class="disdukcapil" style="margin-top: 5px;">
        <col span="48" style="width: 2.0833%;">
        <tr>
            <td colspan="48"><strong>KEMATIAN</strong></td>
        </tr>
        <tr>
            <td>1.</td>
            <td colspan="20">NIK</td>
            <td class="kanan">:</td>
            <?= kotak($input['nik_kematian'], 16); ?>
        </tr>
        <tr>
            <td>2.</td>
            <td colspan="20">Nama Lengkap</td>
            <td class="kanan">:</td>
            <?= kotak($input['nama_kematian']); ?>
        </tr>
        <tr>
            <td>3.</td>
            <td colspan="20">Tanggal kematian</td>
            <td class="kanan">:</td>
            <td>Tgl</td>
            <td class="kanan">:</td>
            <?= kotak(date('dd', strtotime($input['tanggal_kematian'])), 2); ?>
            <td>Bln</td>
            <td class="kanan">:</td>
            <?= kotak(date('mm', strtotime($input['tanggal_kematian'])), 2); ?>
            <td>Thn</td>
            <td class="kanan">:</td>
            <?= kotak(date('Y', strtotime($input['tanggal_kematian'])), 2); ?>
        </tr>
        <tr>
            <td>4.</td>
            <td colspan="20">Pukul </td>
            <td class="kanan">:</td>
            <?= kotak($input['jam_kematian'], 5); ?>
        </tr>
        <tr>
            <td>5.</td>
            <td colspan="20">Sebab kematian</td>
            <td class="kanan">:</td>
            <?= checklist($input['sebab_kematian'], 1); ?>
            <td colspan="7">1. Sakit biasa / tua</td>
            <?= checklist($input['sebab_kematian'], 2); ?>
            <td colspan="7">2. Wabah Penyakit</td>
            <?= checklist($input['sebab_kematian'], 3); ?>
            <td colspan="7">3. Kecelakaan</td>
        </tr>
        <tr>
            <td colspan="22">&nbsp;</td>
            <?= checklist($input['sebab_kematian'], 4); ?>
            <td colspan="7">4. Kriminalitas</td>
            <?= checklist($input['sebab_kematian'], 5); ?>
            <td colspan="7">5. Bunuh Diri</td>
            <?= checklist($input['sebab_kematian'], 6); ?>
            <td colspan="7">6. Lainnya</td>
        </tr>
        <tr>
            <td>6.</td>
            <td colspan="20">Tempat kematian</td>
            <td class="kanan">:</td>
            <?= kotak($input['tempat_kematian']); ?>
        </tr>
        <tr>
            <td>7.</td>
            <td colspan="20">Yang menerangkan</td>
            <td class="kanan">:</td>
            <?= checklist($input['penolong_kematian'], 1); ?>
            <td colspan="5">1. Dokter</td>
            <?= checklist($input['penolong_kematian'], 2); ?>
            <td colspan="7">2. Tenaga Kesehatan</td>
            <?= checklist($input['penolong_kematian'], 3); ?>
            <td colspan="6">3. Kepolisian</td>
            <?= checklist($input['penolong_kematian'], 4); ?>
            <td colspan="6">4. Lainnya</td>
        </tr>
    </table>
    <?php endif ?>
    <!-- Akhir Halaman 2 -->

    <!-- Awal Halaman 3 -->
    <?php if ($tampil_data_pengankatan_anak): ?>
    <table class="disdukcapil" style="margin-top: 5px;">
        <col span="48" style="width: 2.0833%;">
        <tr>
            <td colspan="48"><strong>PENGANGKATAN ANAK</strong></td>
        </tr>
        <tr>
            <td>1.</td>
            <td colspan="20">Nama anak angkat</td>
            <td class="kanan">:</td>
            <?= kotak($input['nama_anak_angkat']); ?>
        </tr>
        <tr>
            <td>2.</td>
            <td colspan="20">Nomor Akta Kelahiran</td>
            <td class="kanan">:</td>
            <?= kotak($input['nomor_akta_kelahiran'], 17); ?>
        </tr>
        <tr>
            <td>3.</td>
            <td colspan="20">Tanggal/Bulan/Tahun</td>
            <td class="kanan">:</td>
            <td>Tgl</td>
            <td class="kanan">:</td>
            <?= kotak(date('dd', strtotime($input['tanggal_akta_kelahiran'])), 2); ?>
            <td>Bln</td>
            <td class="kanan">:</td>
            <?= kotak(date('mm', strtotime($input['tanggal_akta_kelahiran'])), 2); ?>
            <td>Thn</td>
            <td class="kanan">:</td>
            <?= kotak(date('Y', strtotime($input['tanggal_akta_kelahiran'])), 2); ?>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td colspan="47">Penerbitan Akta Kelahiran</td>
        </tr>
        <tr>
            <td>4.</td>
            <td colspan="20">Dinas Kabupaten/Kota yang</td>
            <td class="kanan">:</td>
            <?= kotak($input['penerbit_akta_kelahiran']); ?>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td colspan="47">menerbitkan Akta Kelahiran</td>
        </tr>
        <tr>
            <td>5.</td>
            <td colspan="20">Nama Ibu Kandung</td>
            <td class="kanan">:</td>
            <?= kotak($input['nama_ibu_kandung']); ?>
        </tr>
        <tr>
            <td>6.</td>
            <td colspan="20">NIK Ibu Kandung</td>
            <td class="kanan">:</td>
            <?= kotak($input['nik_ibu_kandung'], 16); ?>
        </tr>
        <tr>
            <td>7.</td>
            <td colspan="20">Kewarganegaraan</td>
            <td class="kanan">:</td>
            <?= kotak($input['kewarganegaraan_ibu_kandung']); ?>
        </tr>
        <tr>
            <td>8.</td>
            <td colspan="20">Nama Ayah</td>
            <td class="kanan">:</td>
            <?= kotak($input['nama_ayah_kandung']); ?>
        </tr>
        <tr>
            <td>9.</td>
            <td colspan="20">NIK Ayah Kandung</td>
            <td class="kanan">:</td>
            <?= kotak($input['nik_ayah_kandung'], 16); ?>
        </tr>
        <tr>
            <td>10.</td>
            <td colspan="20">Kewarganegaraan</td>
            <td class="kanan">:</td>
            <?= kotak($input['kewarganegaraan_ayah_kandung']); ?>
        </tr>
        <tr>
            <td>11.</td>
            <td colspan="20">Nama Ibu Angkat</td>
            <td class="kanan">:</td>
            <?= kotak($input['nama_ibu_angkat']); ?>
        </tr>
        <tr>
            <td>12.</td>
            <td colspan="20">NIK Ibu Angkat</td>
            <td class="kanan">:</td>
            <?= kotak($input['nik_ibu_angkat'], 16); ?>
        </tr>
        <tr>
            <td>13.</td>
            <td colspan="20">Nomor Paspor</td>
            <td class="kanan">:</td>
            <?= kotak($input['nomor_paspor_ibu']); ?>
        </tr>
        <tr>
            <td>14.</td>
            <td colspan="20">Nama Ayah Angkat</td>
            <td class="kanan">:</td>
            <?= kotak($input['nama_ayah_angkat']); ?>
        </tr>
        <tr>
            <td>15.</td>
            <td colspan="20">NIK Ayah Angkat</td>
            <td class="kanan">:</td>
            <?= kotak($input['nik_ayah_angkat'], 16); ?>
        </tr>
        <tr>
            <td>16.</td>
            <td colspan="20">Nomor Paspor</td>
            <td class="kanan">:</td>
            <?= kotak($input['nomor_paspor_ayah']); ?>
        </tr>
        <tr>
            <td>17.</td>
            <td colspan="20">Nama Pengadilan</td>
            <td class="kanan">:</td>
            <?= kotak($input['nama+pengadilan'], 18); ?>
        </tr>
        <tr>
            <td>18.</td>
            <td colspan="20">Tanggal Penetapan Pengadilan</td>
            <td class="kanan">:</td>
            <td>Tgl</td>
            <td class="kanan">:</td>
            <?= kotak(date('dd', strtotime($input['tanggal_penetapan_pengadilan'])), 2); ?>
            <td>Bln</td>
            <td class="kanan">:</td>
            <?= kotak(date('mm', strtotime($input['tanggal_penetapan_pengadilan'])), 2); ?>
            <td>Thn</td>
            <td class="kanan">:</td>
            <?= kotak(date('Y', strtotime($input['tanggal_penetapan_pengadilan'])), 2); ?>
        </tr>
        <tr>
            <td>19.</td>
            <td colspan="20">Nomor Penetapan Pengadilan</td>
            <td class="kanan">:</td>
            <?= kotak($input['nomor_penetapan_pengadilan'], 19); ?>
        </tr>
        <tr>
            <td>20.</td>
            <td colspan="20">Nama lembaga Penetapan Pengadilan</td>
            <td class="kanan">:</td>
            <?= kotak($input['nama_lembaga_penetapan_pengadilan'], 16); ?>
        </tr>
        <tr>
            <td>21.</td>
            <td colspan="20">Tempat lembaga Penetapan Pengadilan</td>
            <td class="kanan">:</td>
            <?= kotak($input['tempat_lembaga_penetapan_pengadilan'], 16); ?>
        </tr>

    </table>
    <?php endif ?>
    <!-- Akhir Halaman 3 -->

    <!-- Awal Halaman 4 -->

    <!-- Penandatangan -->
    <br><br><br>
    <table class="disdukcapil" style="margin-top: -5px; border: 0px;">
        <col span="48" style="width: 2.0833%;">
        <tr>
            <td colspan="37">&nbsp;</td>
            <td colspan="10" class="tengah"><?= $config['nama_desa']; ?>, <?= tgl_indo(date('Y m d', time()))?></td>
            <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="2">&nbsp;</td>
            <td colspan="10" class="tengah">Mengetahui :</td>
            <td colspan="24">&nbsp;</td>
            <td colspan="10" class="tengah">Pelapor</td>
            <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="2">&nbsp;</td>
            <td colspan="10" class="tengah">Pejabat Dukcapil Yang Membidangi</td>
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
            <td colspan="2">&nbsp;</td>
            <td colspan="10" class="tengah"><strong>(<?= str_pad('.', 50, '.', STR_PAD_LEFT); ?>)</strong></td>
            <td colspan="24">&nbsp;</td>
            <td colspan="10" class="tengah"><strong>(<?= padded_string_center(strtoupper($input['nama_pelapor']), 30) ?>)</strong></td>
            <td colspan="2">&nbsp;</td>
        </tr>
    </table>
    <!-- Akhir Halaman 4 -->
</page>