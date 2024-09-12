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
            <td colspan="36"><?= $config['nama_desa']; ?></td>
            <td colspan="1">&nbsp;</td>
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
            <td colspan="13">Nama</td>
            <td class="kanan">:</td>
            <?= kotak($input['nama_pelapor'], 34); ?>
        </tr>
        <tr>
            <td colspan="13">NIK</td>
            <td class="kanan">:</td>
            <?= kotak($input['nik_pelapor'], 16); ?>
        </tr>
        <tr>
            <td colspan="13">Nomor Dokumen Perjalanan*</td>
            <td class="kanan">:</td>
            <?= kotak($input['dokumen_perjalanan_pelapor'], 16); ?>
        </tr>
        <tr>
            <td colspan="13">Nomor Kartu Keluarga</td>
            <td class="kanan">:</td>
            <?= kotak($input['no_kk_pelapor'], 16); ?>
        </tr>
        <tr>
            <td colspan="13">Kewarganegaraan</td>
            <td class="kanan">:</td>
            <?= kotak($input['kewarganegaraan_pelapor'], 34); ?>
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
            <td colspan="13">Nama</td>
            <td class="kanan">:</td>
            <?= kotak($input['nama_akta1'], 34); ?>
        </tr>
        <tr>
            <td colspan="13">NIK</td>
            <td class="kanan">:</td>
            <?= kotak($input['nik_akta1'], 16); ?>
        </tr>
        <tr>
            <td colspan="13">Nomor Dokumen Perjalanan*</td>
            <td class="kanan">:</td>
            <?= kotak($input['dokumen_perjalanan_akta1'], 34); ?>
        </tr>
        <tr>
            <td colspan="13">Nomor Kartu Keluarga</td>
            <td class="kanan">:</td>
            <?= kotak($input['no_kk_akta1'], 16); ?>
        </tr>
        <tr>
            <td colspan="13">Kewarganegaraan</td>
            <td class="kanan">:</td>
            <?= kotak($input['kewarganegaraan_akta1'], 34); ?>
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
            <td colspan="13">Nama</td>
            <td class="kanan">:</td>
            <?= kotak($input['nama_akta2'], 34); ?>
        </tr>
        <tr>
            <td colspan="13">NIK</td>
            <td class="kanan">:</td>
            <?= kotak($input['nik_akta2'], 16); ?>
        </tr>
        <tr>
            <td colspan="13">Nomor Dokumen Perjalanan*</td>
            <td class="kanan">:</td>
            <?= kotak($input['dokumen_perjalanan_akta2']); ?>
        </tr>
        <tr>
            <td colspan="13">Nomor Kartu Keluarga</td>
            <td class="kanan">:</td>
            <?= kotak($input['no_kk_akta2'], 16); ?>
        </tr>
        <tr>
            <td colspan="13">Kewarganegaraan</td>
            <td class="kanan">:</td>
            <?= kotak($input['kewarganegaraan_akta2'], 34); ?>
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
            <td colspan="13">Nama</td>
            <td class="kanan">:</td>
            <?= kotak($input['nama_saksi1'], 34); ?>
        </tr>
        <tr>
            <td colspan="13">NIK</td>
            <td class="kanan">:</td>
            <?= kotak($input['nik_saksi1'], 16); ?>
        </tr>
        <tr>
            <td colspan="13">Nomor Kartu Keluarga</td>
            <td class="kanan">:</td>
            <?= kotak($input['no_kk_saksi1'], 16); ?>
        </tr>
        <tr>
            <td colspan="13">Kewarganegaraan</td>
            <td class="kanan">:</td>
            <?= kotak($input['kewarganegaraan_saksi1'], 34); ?>
        </tr>
        <tr>
            <td colspan="48"><strong><u>DATA SAKSI II</u></strong></td>
        </tr>
        <tr>
            <td colspan="13">Nama</td>
            <td class="kanan">:</td>
            <?= kotak($input['nama_saksi2'], 34); ?>
        </tr>
        <tr>
            <td colspan="13">NIK</td>
            <td class="kanan">:</td>
            <?= kotak($input['nik_saksi2'], 16); ?>
        </tr>
        <tr>
            <td colspan="13">Nomor Kartu Keluarga</td>
            <td class="kanan">:</td>
            <?= kotak($input['no_kk_saksi2'], 16); ?>
        </tr>
        <tr>
            <td colspan="13">Kewarganegaraan</td>
            <td class="kanan">:</td>
            <?= kotak($input['kewarganegaraan_saksi2'], 34); ?>
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
            <td colspan="13">Nama Ayah</td>
            <td class="kanan">:</td>
            <?= kotak($input['nama_ayah'], 34); ?>
        </tr>
        <tr>
            <td colspan="13">NIK Ayah</td>
            <td class="kanan">:</td>
            <?= kotak($input['nik_ayah'], 16); ?>
        </tr>
        <tr>
            <td colspan="13">Tempat Lahir Ayah</td>
            <td class="kanan">:</td>
            <?= kotak($input['tempat_lahir_ayah'], 34); ?>
        </tr>
        <tr>
            <td colspan="13">Tanggal Lahir Ayah</td>
            <td class="kanan">:</td>
            <td>Tgl</td>
            <td class="kanan">:</td>
            <?= kotak(! empty($input['tanggal_lahir_ayah']) ? date('dd', strtotime($input['tanggal_lahir_ayah'])) : '', 2); ?>
            <td>Bln</td>
            <td class="kanan">:</td>
            <?= kotak(! empty($input['tanggal_lahir_ayah']) ? date('mm', strtotime($input['tanggal_lahir_ayah'])) : '', 2); ?>
            <td>Thn</td>
            <td class="kanan">:</td>
            <?= kotak(! empty($input['tanggal_lahir_ayah']) ? date('Y', strtotime($input['tanggal_lahir_ayah'])) : '', 4); ?>
        </tr>
        <tr>
            <td colspan="13">Kewarganegaraan</td>
            <td class="kanan">:</td>
            <?= kotak($input['kewarganegaraan_ayah'], 34); ?>
        </tr>
        <tr>
            <td colspan="13">Nama Ibu</td>
            <td class="kanan">:</td>
            <?= kotak($input['nama_ibu'], 34); ?>
        </tr>
        <tr>
            <td colspan="13">NIK Ibu</td>
            <td class="kanan">:</td>
            <?= kotak($input['nik_ibu'], 16); ?>
        </tr>
        <tr>
            <td colspan="13">Tempat Lahir Ibu</td>
            <td class="kanan">:</td>
            <?= kotak($input['tempat_lahir_ibu'], 34); ?>
        </tr>
        <tr>
            <td colspan="13">Tanggal Lahir Ibu</td>
            <td class="kanan">:</td>
            <td>Tgl</td>
            <td class="kanan">:</td>
            <?= kotak(! empty($input['tanggal_lahir_ibu']) ? date('dd', strtotime($input['tanggal_lahir_ibu'])) : '', 2); ?>
            <td>Bln</td>
            <td class="kanan">:</td>
            <?= kotak(! empty($input['tanggal_lahir_ibu']) ? date('mm', strtotime($input['tanggal_lahir_ibu'])) : '', 2); ?>
            <td>Thn</td>
            <td class="kanan">:</td>
            <?= kotak(! empty($input['tanggal_lahir_ibu']) ? date('Y', strtotime($input['tanggal_lahir_ibu'])) : '', 4); ?>
        </tr>
        <tr>
            <td colspan="13">Kewarganegaraan</td>
            <td class="kanan">:</td>
            <?= kotak($input['kewarganegaraan_ibu'], 34); ?>
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
            <td colspan="12">Nama</td>
            <td class="kanan">:</td>
            <?= kotak($individu['nama'], 34); ?>
        </tr>
        <tr>
            <td>2.</td>
            <td colspan="12">Jenis kelamin</td>
            <td class="kanan">:</td>
            <?= checklist($individu['sex'], 'Laki-laki'); ?>
            <td colspan="6">1. <?= $individu['sex'] ?> </td>
            <?= checklist($individu['sex'], 'Perempuan'); ?>
            <td colspan="7">2. <?= $individu['sex'] ?> </td>
        </tr>
        <tr>
            <td>3.</td>
            <td colspan="12">Tempat dilahirkan</td>
            <td class="kanan">:</td>
            <?= checklist($individu['tempat_dilahirkan'], 1); ?>
            <td colspan="4">1. RS/SB</td>
            <?= checklist($individu['tempat_dilahirkan'], 2); ?>
            <td colspan="5">2. Puskesmas </td>
            <?= checklist($individu['tempat_dilahirkan'], 3); ?>
            <td colspan="4">3. Polindes</td>
            <?= checklist($individu['tempat_dilahirkan'], 4); ?>
            <td colspan="4">4. Rumah </td>
            <?= checklist($individu['tempat_dilahirkan'], 5); ?>
            <td colspan="4">5. Lainnya &nbsp; </td>
        </tr>
        <tr>
            <td>4.</td>
            <td colspan="12">Tempat kelahiran </td>
            <td class="kanan">:</td>
            <?= kotak($individu['tempatlahir'], 34); ?>
        </tr>
        <tr>
            <td>5.</td>
            <td colspan="12">Hari dan Tanggal Lahir </td>
            <td class="kanan">:</td>
            <td>Hari</td>
            <td class="kanan">:</td>
            <?= kotak(hari($individu['tanggallahir']), 6); ?>
            <td colspan="4">&nbsp;</td>
            <td>Tgl</td>
            <td class="kanan">:</td>
            <?= kotak(date('dd', strtotime($individu['tanggallahir'])), 2); ?>
            <td>Bln</td>
            <td class="kanan">:</td>
            <?= kotak(date('mm', strtotime($individu['tanggallahir'])), 2); ?>
            <td>Thn</td>
            <td class="kanan">:</td>
            <?= kotak(date('Y', strtotime($individu['tanggallahir'])), 4); ?>
        </tr>
        <tr>
            <td>6.</td>
            <td colspan="12">Pukul </td>
            <td class="kanan">:</td>
            <?= kotak($individu['waktu_lahir'], 5); ?>
        </tr>
        <tr>
            <td>7.</td>
            <td colspan="12">Jenis kelahiran </td>
            <td class="kanan">:</td>
            <?= checklist($individu['jenis_kelahiran'], 1); ?>
            <td colspan="4">1. Tunggal</td>
            <?= checklist($individu['jenis_kelahiran'], 2); ?>
            <td colspan="4">2. Kembar 2 </td>
            <?= checklist($individu['jenis_kelahiran'], 3); ?>
            <td colspan="5">3. Kembar 3</td>
            <?= checklist($individu['jenis_kelahiran'], 4); ?>
            <td colspan="4">4. Kembar 4 </td>
            <?= checklist($individu['jenis_kelahiran'], 5); ?>
            <td colspan="4">5. Lainnya &nbsp; </td>
        </tr>
        <tr>
            <td>8.</td>
            <td colspan="12">Kelahiran anak ke- </td>
            <td class="kanan">:</td>
            <?= kotak($individu['kelahiran_anak_ke'], 1); ?>
        </tr>
        <tr>
            <td>9.</td>
            <td colspan="12">Penolong kelahiran</td>
            <td class="kanan">:</td>
            <?= checklist($individu['penolong_kelahiran'], 1); ?>
            <td colspan="4">1. Dokter</td>
            <?= checklist($individu['penolong_kelahiran'], 2); ?>
            <td colspan="6">2. Bidan/Perawat</td>
            <?= checklist($individu['penolong_kelahiran'], 3); ?>
            <td colspan="4">3. Dukun</td>
            <?= checklist($individu['penolong_kelahiran'], 4); ?>
            <td colspan="4">4. Lainnya</td>
        </tr>
        <tr>
            <td>10.</td>
            <td colspan="12">Berat Bayi</td>
            <td class="kanan">:</td>
            <td colspan="2" class="kotak padat tengah">
                <?= $individu['berat_lahir'] ?>
            </td>
            <td> gram</td>
        </tr>
        <tr>
            <td>11.</td>
            <td colspan="12">Panjang Bayi</td>
            <td class="kanan">:</td>
            <td colspan="2" class="kotak padat tengah">
                <?= $individu['panjang_lahir'] ?>
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
            <td colspan="12">Lamanya dalam kandungan</td>
            <td class="kanan">:</td>
            <?= kotak($input['lamanya_dalam_kandungan'], 34); ?>
        </tr>
        <tr>
            <td>2.</td>
            <td colspan="12">Jenis kelamin</td>
            <td class="kanan">:</td>
            <?= checklist($input['sex'], 1); ?>
            <td colspan="6">1. Laki-laki </td>
            <?= checklist($input['sex'], 2); ?>
            <td colspan="7">2. Perempuan </td>
            <td colspan="11">&nbsp;</td>
        </tr>
        <tr>
            <td>3.</td>
            <td colspan="12">Tanggal lahir mati</td>
            <td class="kanan">:</td>
            <td>Tgl</td>
            <td class="kanan">:</td>
            <?= kotak(! empty($input['tanggal_lahir_lahir_mati']) ? date('dd', strtotime($input['tanggal_lahir_lahir_mati'])) : '', 2); ?>
            <td>Bln</td>
            <td class="kanan">:</td>
            <?= kotak(! empty($input['tanggal_lahir_lahir_mati']) ? date('mm', strtotime($input['tanggal_lahir_lahir_mati'])) : '', 2); ?>
            <td>Thn</td>
            <td class="kanan">:</td>
            <?= kotak(! empty($input['tanggal_lahir_lahir_mati']) ? date('Y', strtotime($input['tanggal_lahir_lahir_mati'])) : '', 4); ?>
        </tr>
        <tr>
            <td>4.</td>
            <td colspan="12">Jenis kelahiran </td>
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
            <td colspan="12">Anak ke- </td>
            <td class="kanan">:</td>
            <?= kotak($input['anak_ke_yg_lahir_mati'], 34); ?>
        </tr>
        <tr>
            <td>6.</td>
            <td colspan="12">Tempat dilahirkan </td>
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
            <td colspan="12">Penolong kelahiran </td>
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
            <td colspan="12">Sebab lahir mati</td>
            <td class="kanan">:</td>
            <?= kotak($input['sebab_lahir_mati'], 34); ?>
        </tr>
        <tr>
            <td>9.</td>
            <td colspan="12">Yang menentukan</td>
            <td class="kanan">:</td>
            <?= checklist($input['penentu_kelahiran_lahir_mati'], 1); ?>
            <td colspan="4">1. Dokter</td>
            <?= checklist($input['penentu_kelahiran_lahir_mati'], 2); ?>
            <td colspan="6">2. Bidan/Perawat</td>
            <?= checklist($input['penentu_kelahiran_lahir_mati'], 3); ?>
            <td colspan="8">3. Tenaga Kesehatan</td>
            <?= checklist($input['penentu_kelahiran_lahir_mati'], 4); ?>
            <td colspan="5">4. Kepolisian</td>
            <?= checklist($input['penentu_kelahiran_lahir_mati'], 5); ?>
            <td colspan="5">5. Lainnya</td>
        </tr>
        <tr>
            <td>10.</td>
            <td colspan="12">Tempat kelahiran</td>
            <td class="kanan">:</td>
            <?= kotak($input['tempat_kelahiran'], 34); ?>
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
            <td colspan="12">NIK Ayah dari Suami</td>
            <td class="kanan">:</td>
            <?= kotak($input['nik_ayah_dari_suami'], 16); ?>
        </tr>
        <tr>
            <td>2.</td>
            <td colspan="12">Nama Ayah dari Suami</td>
            <td class="kanan">:</td>
            <?= kotak($input['nama_ayah_dari_suami_perkawinan'], 34); ?>
        </tr>
        <tr>
            <td>3.</td>
            <td colspan="12">NIK Ibu dari Suami</td>
            <td class="kanan">:</td>
            <?= kotak($input['nik_ibu_dari_suami'], 16); ?>
        </tr>
        <tr>
            <td>4.</td>
            <td colspan="12">Nama Ibu dari Suami</td>
            <td class="kanan">:</td>
            <?= kotak($input['nama_ibu_dari_suami_perkawinan'], 34); ?>
        </tr>
        <tr>
            <td>5.</td>
            <td colspan="12">NIK Ayah dari Istri</td>
            <td class="kanan">:</td>
            <?= kotak($input['nik_ayah_dari_istri'], 16); ?>
        </tr>
        <tr>
            <td>6.</td>
            <td colspan="12">Nama Ayah dari Istri</td>
            <td class="kanan">:</td>
            <?= kotak($input['nama_ayah_dari_istri_perkawinan'], 34); ?>
        </tr>
        <tr>
            <td>7.</td>
            <td colspan="12">NIK Ibu dari Istri</td>
            <td class="kanan">:</td>
            <?= kotak($input['nik_ibu_dari_istri'], 16); ?>
        </tr>
        <tr>
            <td>8.</td>
            <td colspan="12">Nama Ibu dari Istri</td>
            <td class="kanan">:</td>
            <?= kotak($input['nama_ibu_dari_istri_perkawinan'], 34); ?>
        </tr>
        <tr>
            <td>9.</td>
            <td colspan="12">Status Perkawinan Sebelum Kawin</td>
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
            <td colspan="12">Perkawinan yang ke-</td>
            <td class="kanan">:</td>
            <?= kotak($input['perkawinan_anak_ke_perkawinan'], 1); ?>
        </tr>
        <tr>
            <td>11.</td>
            <td colspan="12">Istri yang ke-</td>
            <td class="kanan">:</td>
            <?= kotak($input['istri_yg_ke_perkawinan'], 1); ?>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td colspan="47">(bagi yang poligami)</td>
        </tr>
        <tr>
            <td>12.</td>
            <td colspan="12">Tanggal Pemberkatan</td>
            <td class="kanan">:</td>
            <td>Tgl</td>
            <td class="kanan">:</td>
            <?= kotak(! empty($input['tanggal_pemberkatan_perkawinan']) ? date('dd', strtotime($input['tanggal_pemberkatan_perkawinan'])) : '', 2); ?>
            <td>Bln</td>
            <td class="kanan">:</td>
            <?= kotak(! empty($input['tanggal_pemberkatan_perkawinan']) ? date('mm', strtotime($input['tanggal_pemberkatan_perkawinan'])) : '', 2); ?>
            <td>Thn</td>
            <td class="kanan">:</td>
            <?= kotak(! empty($input['tanggal_pemberkatan_perkawinan']) ? date('Y', strtotime($input['tanggal_pemberkatan_perkawinan'])) : '', 4); ?>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td colspan="47">Perkawinan</td>
        </tr>
        <tr>
            <td>13.</td>
            <td colspan="12">Tanggal Melapor</td>
            <td class="kanan">:</td>
            <td>Tgl</td>
            <td class="kanan">:</td>
            <?= kotak(! empty($input['tanggal_melapor_perkawinan']) ? date('dd', strtotime($input['tanggal_melapor_perkawinan'])) : '', 2); ?>
            <td>Bln</td>
            <td class="kanan">:</td>
            <?= kotak(! empty($input['tanggal_melapor_perkawinan']) ? date('mm', strtotime($input['tanggal_melapor_perkawinan'])) : '', 2); ?>
            <td>Thn</td>
            <td class="kanan">:</td>
            <?= kotak(! empty($input['tanggal_melapor_perkawinan']) ? date('Y', strtotime($input['tanggal_melapor_perkawinan'])) : '', 4); ?>
        </tr>
        <tr>
            <td>14.</td>
            <td colspan="12">Jam Pelaporan</td>
            <td class="kanan">:</td>
            <?= kotak($input['waktu_perkwinan'], 5); ?>
        </tr>
        <tr>
            <td>15.</td>
            <td colspan="12">Agama</td>
            <td class="kanan">:</td>
            <?= checklist($input['agama_perkawinan'], 1); ?>
            <td colspan="4">1. Islam</td>
            <?= checklist($input['agama_perkawinan'], 2); ?>
            <td colspan="4">2. Kristen</td>
            <?= checklist($input['agama_perkawinan'], 3); ?>
            <td colspan="4">3. Katolik</td>
            <?= checklist($input['agama_perkawinan'], 4); ?>
            <td colspan="4">4. Hindu</td>
            <?= checklist($input['agama_perkawinan'], 5); ?>
            <td colspan="4">5. Budha</td>
            <?= checklist($input['agama_perkawinan'], 6); ?>
            <td colspan="5">6. Konghuchu</td>
        </tr>
        <tr>
            <td>16.</td>
            <td colspan="12">Kepercayaan </td>
            <td class="kanan">:</td>
            <td class="kotak padat tengah"><?= $input['kepercayaan_perkawinan'] ?></td>
        </tr>
        <tr>
            <td>17.</td>
            <td colspan="12">Nama Organisasi</td>
            <td class="kanan">:</td>
            <?= kotak($input['nama_organisasi_perkawinan'], 34); ?>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td colspan="47">Kepercayaan</td>
        </tr>
        <tr>
            <td>18.</td>
            <td colspan="12">Nama Pengadilan </td>
            <td class="kanan">:</td>
            <?= kotak($input['nama_pengadilan_perkawinan'], 34); ?>
        </tr>
        <tr>
            <td>19.</td>
            <td colspan="12">Nomor Penetapan</td>
            <td class="kanan">:</td>
            <?= kotak($input['nomor_penetapan_perkawinan'], 34); ?>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td colspan="47">Pengadilan</td>
        </tr>
        <tr>
            <td>20.</td>
            <td colspan="12">Tanggal Penetapan</td>
            <td class="kanan">:</td>
            <td>Tgl</td>
            <td class="kanan">:</td>
            <?= kotak(! empty($input['tanggal_penetapan_pengadilan']) ? date('dd', strtotime($input['tanggal_penetapan_pengadilan'])) : '', 2); ?>
            <td>Bln</td>
            <td class="kanan">:</td>
            <?= kotak(! empty($input['tanggal_penetapan_pengadilan']) ? date('mm', strtotime($input['tanggal_penetapan_pengadilan'])) : '', 2); ?>
            <td>Thn</td>
            <td class="kanan">:</td>
            <?= kotak(! empty($input['tanggal_penetapan_pengadilan']) ? date('Y', strtotime($input['tanggal_penetapan_pengadilan'])) : '', 4); ?>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td colspan="47">Pengadilan</td>
        </tr>
        <tr>
            <td>21.</td>
            <td colspan="12">Nama Pemuka Agama/</td>
            <td class="kanan">:</td>
            <?= kotak($input['nama_pemuka_agama'], 34); ?>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td colspan="47">Kepercayaan</td>
        </tr>
        <tr>
            <td>22.</td>
            <td colspan="12">Nomor Surat Izin</td>
            <td class="kanan">:</td>
            <?= kotak($input['nomor_surat_izin'], 16); ?>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td colspan="47">dari Perwakilan</td>
        </tr>
        <tr>
            <td>23.</td>
            <td colspan="12">Nomor Pasport</td>
            <td class="kanan">:</td>
            <?= kotak($input['nomor_pasport'], 16); ?>
        </tr>
        <tr>
            <td>24.</td>
            <td colspan="12">Perjanjian Perkawinan</td>
            <td class="kanan">:</td>
            <?= kotak($input['perjanjian_perkawinan'], 16); ?>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td colspan="47">dibuat oleh Notaris</td>
        </tr>
        <tr>
            <td>25.</td>
            <td colspan="12">Nomor Akta Notaris</td>
            <td class="kanan">:</td>
            <?= kotak($input['nomor_akta_notaris'], 16); ?>
        </tr>
        <tr>
            <td>26.</td>
            <td colspan="12">Tanggal Akta Notaris</td>
            <td class="kanan">:</td>
            <?= kotak(! empty($input['tanggal_akta_notaris']) ? date('ddmmY', strtotime($input['tanggal_akta_notaris'])) : '', 16); ?>
        </tr>
        <tr>
            <td>27.</td>
            <td colspan="12">Jumlah Anak (jika ada</td>
            <td class="kanan">:</td>
            <?= kotak($input['jumlah_anak'], 16); ?>
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
            <td colspan="12">Tanggal Perkawinan</td>
            <td class="kanan">:</td>
            <td>Tgl</td>
            <td class="kanan">:</td>
            <?= kotak(! empty($input['tanggal_perkawinan']) ? date('dd', strtotime($input['tanggal_perkawinan'])) : '', 2); ?>
            <td>Bln</td>
            <td class="kanan">:</td>
            <?= kotak(! empty($input['tanggal_perkawinan']) ? date('mm', strtotime($input['tanggal_perkawinan'])) : '', 2); ?>
            <td>Thn</td>
            <td class="kanan">:</td>
            <?= kotak(! empty($input['tanggal_perkawinan']) ? date('Y', strtotime($input['tanggal_perkawinan'])) : '', 4); ?>
        </tr>
        <tr>
            <td>2.</td>
            <td colspan="12">Nomor Akta Perkawinan</td>
            <td class="kanan">:</td>
            <?= kotak($input['nomor_akta_perkawinan'], 16); ?>
        </tr>
        <tr>
            <td>3.</td>
            <td colspan="12">Tanggal Akta Perkawinan</td>
            <td class="kanan">:</td>
            <td>Tgl</td>
            <td class="kanan">:</td>
            <?= kotak(! empty($input['tanggal_akta_perkawinan']) ? date('dd', strtotime($input['tanggal_akta_perkawinan'])) : '', 2); ?>
            <td>Bln</td>
            <td class="kanan">:</td>
            <?= kotak(! empty($input['tanggal_akta_perkawinan']) ? date('mm', strtotime($input['tanggal_akta_perkawinan'])) : '', 2); ?>
            <td>Thn</td>
            <td class="kanan">:</td>
            <?= kotak(! empty($input['tanggal_akta_perkawinan']) ? date('Y', strtotime($input['tanggal_akta_perkawinan'])) : '', 4); ?>
        </tr>
        <tr>
            <td>4.</td>
            <td colspan="12">Nama Pengadilan</td>
            <td class="kanan">:</td>
            <?= kotak($input['nama_pengadilan'], 16); ?>
        </tr>
        <tr>
            <td>5.</td>
            <td colspan="12">Nomor Putusan Pengadilan</td>
            <td class="kanan">:</td>
            <?= kotak($input['nomor_putusan_pengadilan'], 16); ?>
        </tr>
        <tr>
            <td>6.</td>
            <td colspan="12">Tanggal Putusan Pengadilan</td>
            <td class="kanan">:</td>
            <td>Tgl</td>
            <td class="kanan">:</td>
            <?= kotak(! empty($input['tanggal_putusan_pengadilan']) ? date('dd', strtotime($input['tanggal_putusan_pengadilan'])) : '', 2); ?>
            <td>Bln</td>
            <td class="kanan">:</td>
            <?= kotak(! empty($input['tanggal_putusan_pengadilan']) ? date('mm', strtotime($input['tanggal_putusan_pengadilan'])) : '', 2); ?>
            <td>Thn</td>
            <td class="kanan">:</td>
            <?= kotak(! empty($input['tanggal_putusan_pengadilan']) ? date('Y', strtotime($input['tanggal_putusan_pengadilan'])) : '', 4); ?>
        </tr>
        <tr>
            <td>7.</td>
            <td colspan="12">Tanggal Pelaporan Perkawinan</td>
            <td class="kanan">:</td>
            <td>Tgl</td>
            <td class="kanan">:</td>
            <?= kotak(! empty($input['tanggal_pelaporan_perkawinan_luar_negeri']) ? date('dd', strtotime($input['tanggal_pelaporan_perkawinan_luar_negeri'])) : '', 2); ?>
            <td>Bln</td>
            <td class="kanan">:</td>
            <?= kotak(! empty($input['tanggal_pelaporan_perkawinan_luar_negeri']) ? date('mm', strtotime($input['tanggal_pelaporan_perkawinan_luar_negeri'])) : '', 2); ?>
            <td>Thn</td>
            <td class="kanan">:</td>
            <?= kotak(! empty($input['tanggal_pelaporan_perkawinan_luar_negeri']) ? date('Y', strtotime($input['tanggal_pelaporan_perkawinan_luar_negeri'])) : '', 4); ?>
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
            <td colspan="12">Nomor Akta Perkawinan</td>
            <td class="kanan">:</td>
            <?= kotak($input['nomor_akta_perkawinan'], 16); ?>
        </tr>
        <tr>
            <td>2.</td>
            <td colspan="12">Tanggal akta Perkawinan</td>
            <td class="kanan">:</td>
            <td>Tgl</td>
            <td class="kanan">:</td>
            <?= kotak(! empty($input['tanggal_perkawinan']) ? date('dd', strtotime($input['tanggal_perkawinan'])) : '', 2); ?>
            <td>Bln</td>
            <td class="kanan">:</td>
            <?= kotak(! empty($input['tanggal_perkawinan']) ? date('mm', strtotime($input['tanggal_perkawinan'])) : '', 2); ?>
            <td>Thn</td>
            <td class="kanan">:</td>
            <?= kotak(! empty($input['tanggal_perkawinan']) ? date('Y', strtotime($input['tanggal_perkawinan'])) : '', 4); ?>
        </tr>
        <tr>
            <td>3.</td>
            <td colspan="12">Tanggal Akta Perkawinan</td>
            <td class="kanan">:</td>
            <td>Tgl</td>
            <td class="kanan">:</td>
            <?= kotak(! empty($input['tanggal_akta_perkawinan']) ? date('dd', strtotime($input['tanggal_akta_perkawinan'])) : '', 2); ?>
            <td>Bln</td>
            <td class="kanan">:</td>
            <?= kotak(! empty($input['tanggal_akta_perkawinan']) ? date('mm', strtotime($input['tanggal_akta_perkawinan'])) : '', 2); ?>
            <td>Thn</td>
            <td class="kanan">:</td>
            <?= kotak(! empty($input['tanggal_akta_perkawinan']) ? date('Y', strtotime($input['tanggal_akta_perkawinan'])) : '', 4); ?>
        </tr>
        <tr>
            <td>4.</td>
            <td colspan="12">Nama Pengadilan</td>
            <td class="kanan">:</td>
            <?= kotak($input['nama_pengadilan'], 16); ?>
        </tr>
        <tr>
            <td>5.</td>
            <td colspan="12">Tanggal Putusan Pengadilan</td>
            <td class="kanan">:</td>
            <td>Tgl</td>
            <td class="kanan">:</td>
            <?= kotak(! empty($input['tanggal_putusan_pengadilan']) ? date('dd', strtotime($input['tanggal_putusan_pengadilan'])) : '', 2); ?>
            <td>Bln</td>
            <td class="kanan">:</td>
            <?= kotak(! empty($input['tanggal_putusan_pengadilan']) ? date('mm', strtotime($input['tanggal_putusan_pengadilan'])) : '', 2); ?>
            <td>Thn</td>
            <td class="kanan">:</td>
            <?= kotak(! empty($input['tanggal_putusan_pengadilan']) ? date('Y', strtotime($input['tanggal_putusan_pengadilan'])) : '', 4); ?>
        </tr>
        <tr>
            <td>6.</td>
            <td colspan="12">Nomor Putusan Pengadilan</td>
            <td class="kanan">:</td>
            <?= kotak($input['nomor_putusan_pengadilan'], 23); ?>
        </tr>
        <tr>
            <td>7.</td>
            <td colspan="12">Nomor Surat Keterangan</td>
            <td class="kanan">:</td>
            <?= kotak($input['nomor_surat_keterangan_panitera_pengadilan'], 16); ?>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td colspan="47">Panitera Pengadilan</td>
        </tr>
        <tr>
            <td>8.</td>
            <td colspan="12">Tanggal Surat Keterangan</td>
            <td class="kanan">:</td>
            <td>Tgl</td>
            <td class="kanan">:</td>
            <?= kotak(! empty($input['tanggal_pelaporan_perkawinan_luar_negeri']) ? date('dd', strtotime($input['tanggal_pelaporan_perkawinan_luar_negeri'])) : '', 2); ?>
            <td>Bln</td>
            <td class="kanan">:</td>
            <?= kotak(! empty($input['tanggal_pelaporan_perkawinan_luar_negeri']) ? date('mm', strtotime($input['tanggal_pelaporan_perkawinan_luar_negeri'])) : '', 2); ?>
            <td>Thn</td>
            <td class="kanan">:</td>
            <?= kotak(! empty($input['tanggal_pelaporan_perkawinan_luar_negeri']) ? date('Y', strtotime($input['tanggal_pelaporan_perkawinan_luar_negeri'])) : '', 4); ?>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td colspan="47">Panitera Pengadilan</td>
        </tr>
        <tr>
            <td>9.</td>
            <td colspan="12">Tanggal Melapor</td>
            <td class="kanan">:</td>
            <td>Tgl</td>
            <td class="kanan">:</td>
            <?= kotak(! empty($input['tanggal_melapor']) ? date('dd', strtotime($input['tanggal_melapor'])) : '', 2); ?>
            <td>Bln</td>
            <td class="kanan">:</td>
            <?= kotak(! empty($input['tanggal_melapor']) ? date('mm', strtotime($input['tanggal_melapor'])) : '', 2); ?>
            <td>Thn</td>
            <td class="kanan">:</td>
            <?= kotak(! empty($input['tanggal_melapor']) ? date('Y', strtotime($input['tanggal_melapor'])) : '', 4); ?>
        </tr>
        <tr>
            <td colspan="48">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="48"><strong>Bagi Pemohon Pembatalan Perceraian Harap Mengisi Data di bawah ini:</strong></td>
        </tr>
        <tr>
            <td>1.</td>
            <td colspan="12">Nomor Akta Perceraian</td>
            <td class="kanan">:</td>
            <?= kotak($input['nomor_akta_perceraian'], 16); ?>
        </tr>
        <tr>
            <td>2.</td>
            <td colspan="12">Tanggal Akta Perceraian</td>
            <td class="kanan">:</td>
            <td>Tgl</td>
            <td class="kanan">:</td>
            <?= kotak(! empty($input['tanggal_akta_perceraian']) ? date('dd', strtotime($input['tanggal_akta_perceraian'])) : '', 2); ?>
            <td>Bln</td>
            <td class="kanan">:</td>
            <?= kotak(! empty($input['tanggal_akta_perceraian']) ? date('mm', strtotime($input['tanggal_akta_perceraian'])) : '', 2); ?>
            <td>Thn</td>
            <td class="kanan">:</td>
            <?= kotak(! empty($input['tanggal_akta_perceraian']) ? date('Y', strtotime($input['tanggal_akta_perceraian'])) : '', 4); ?>
        </tr>
        <tr>
            <td>3.</td>
            <td colspan="12">Tanggal Pelaporan</td>
            <td class="kanan">:</td>
            <td>Tgl</td>
            <td class="kanan">:</td>
            <?= kotak(! empty($input['tanggal_pelaporan_pembatalan_perceraian']) ? date('dd', strtotime($input['tanggal_pelaporan_pembatalan_perceraian'])) : '', 2); ?>
            <td>Bln</td>
            <td class="kanan">:</td>
            <?= kotak(! empty($input['tanggal_pelaporan_pembatalan_perceraian']) ? date('mm', strtotime($input['tanggal_pelaporan_pembatalan_perceraian'])) : '', 2); ?>
            <td>Thn</td>
            <td class="kanan">:</td>
            <?= kotak(! empty($input['tanggal_pelaporan_pembatalan_perceraian']) ? date('Y', strtotime($input['tanggal_pelaporan_pembatalan_perceraian'])) : '', 4); ?>
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
            <td colspan="12">NIK</td>
            <td class="kanan">:</td>
            <?= kotak($surat->url_surat == 'surat-keterangan-kelahiran' ? '' : $input['nik_kematian'], 16); ?>
        </tr>
        <tr>
            <td>2.</td>
            <td colspan="12">Nama Lengkap</td>
            <td class="kanan">:</td>
            <?= kotak($surat->url_surat == 'surat-keterangan-kelahiran' ? '' : $input['nama_kematian'], 34); ?>
        </tr>
        <tr>
            <td>3.</td>
            <td colspan="12">Tanggal kematian</td>
            <td class="kanan">:</td>
            <td>Tgl</td>
            <td class="kanan">:</td>
            <?= kotak(! empty($input['tanggal_kematian']) ? date('dd', strtotime($input['tanggal_kematian'])) : '', 2); ?>
            <td>Bln</td>
            <td class="kanan">:</td>
            <?= kotak(! empty($input['tanggal_kematian']) ? date('mm', strtotime($input['tanggal_kematian'])) : '', 2); ?>
            <td>Thn</td>
            <td class="kanan">:</td>
            <?= kotak(! empty($input['tanggal_kematian']) ? date('Y', strtotime($input['tanggal_kematian'])) : '', 4); ?>
        </tr>
        <tr>
            <td>4.</td>
            <td colspan="12">Pukul </td>
            <td class="kanan">:</td>
            <?= kotak($input['jam_kematian'], 5); ?>
        </tr>
        <tr>
            <td>5.</td>
            <td colspan="12">Sebab kematian</td>
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
            <td colspan="12">Tempat kematian</td>
            <td class="kanan">:</td>
            <?= kotak($input['tempat_kematian'], 34); ?>
        </tr>
        <tr>
            <td>7.</td>
            <td colspan="12">Yang menerangkan</td>
            <td class="kanan">:</td>
            <?= checklist($input['penolong_kematian'], 1); ?>
            <td colspan="4">1. Dokter</td>
            <?= checklist($input['penolong_kematian'], 2); ?>
            <td colspan="8">2. Tenaga Kesehatan</td>
            <?= checklist($input['penolong_kematian'], 3); ?>
            <td colspan="5">3. Kepolisian</td>
            <?= checklist($input['penolong_kematian'], 4); ?>
            <td colspan="5">4. Lainnya</td>
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
            <td colspan="12">Nama anak angkat</td>
            <td class="kanan">:</td>
            <?= kotak($input['nama_anak_angkat'], 34); ?>
        </tr>
        <tr>
            <td>2.</td>
            <td colspan="12">Nomor Akta Kelahiran</td>
            <td class="kanan">:</td>
            <?= kotak($input['nomor_akta_kelahiran'], 17); ?>
        </tr>
        <tr>
            <td>3.</td>
            <td colspan="12">Tanggal/Bulan/Tahun</td>
            <td class="kanan">:</td>
            <td>Tgl</td>
            <td class="kanan">:</td>
            <?= kotak(! empty($input['tanggal_akta_kelahiran']) ? date('dd', strtotime($input['tanggal_akta_kelahiran'])) : '', 2); ?>
            <td>Bln</td>
            <td class="kanan">:</td>
            <?= kotak(! empty($input['tanggal_akta_kelahiran']) ? date('mm', strtotime($input['tanggal_akta_kelahiran'])) : '', 2); ?>
            <td>Thn</td>
            <td class="kanan">:</td>
            <?= kotak(! empty($input['tanggal_akta_kelahiran']) ? date('Y', strtotime($input['tanggal_akta_kelahiran'])) : '', 4); ?>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td colspan="47">Penerbitan Akta Kelahiran</td>
        </tr>
        <tr>
            <td>4.</td>
            <td colspan="12">Dinas Kabupaten/Kota yang</td>
            <td class="kanan">:</td>
            <?= kotak($input['penerbit_akta_kelahiran'], 34); ?>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td colspan="47">menerbitkan Akta Kelahiran</td>
        </tr>
        <tr>
            <td>5.</td>
            <td colspan="12">Nama Ibu Kandung</td>
            <td class="kanan">:</td>
            <?= kotak($input['nama_ibu_kandung'], 34); ?>
        </tr>
        <tr>
            <td>6.</td>
            <td colspan="12">NIK Ibu Kandung</td>
            <td class="kanan">:</td>
            <?= kotak($input['nik_ibu_kandung'], 16); ?>
        </tr>
        <tr>
            <td>7.</td>
            <td colspan="12">Kewarganegaraan</td>
            <td class="kanan">:</td>
            <?= kotak($input['kewarganegaraan_ibu_kandung'], 34); ?>
        </tr>
        <tr>
            <td>8.</td>
            <td colspan="12">Nama Ayah</td>
            <td class="kanan">:</td>
            <?= kotak($input['nama_ayah_kandung'], 34); ?>
        </tr>
        <tr>
            <td>9.</td>
            <td colspan="12">NIK Ayah Kandung</td>
            <td class="kanan">:</td>
            <?= kotak($input['nik_ayah_kandung'], 16); ?>
        </tr>
        <tr>
            <td>10.</td>
            <td colspan="12">Kewarganegaraan</td>
            <td class="kanan">:</td>
            <?= kotak($input['kewarganegaraan_ayah_kandung'], 34); ?>
        </tr>
        <tr>
            <td>11.</td>
            <td colspan="12">Nama Ibu Angkat</td>
            <td class="kanan">:</td>
            <?= kotak($input['nama_ibu_angkat'], 34); ?>
        </tr>
        <tr>
            <td>12.</td>
            <td colspan="12">NIK Ibu Angkat</td>
            <td class="kanan">:</td>
            <?= kotak($input['nik_ibu_angkat'], 16); ?>
        </tr>
        <tr>
            <td>13.</td>
            <td colspan="12">Nomor Paspor</td>
            <td class="kanan">:</td>
            <?= kotak($input['nomor_paspor_ibu'], 34); ?>
        </tr>
        <tr>
            <td>14.</td>
            <td colspan="12">Nama Ayah Angkat</td>
            <td class="kanan">:</td>
            <?= kotak($input['nama_ayah_angkat'], 34); ?>
        </tr>
        <tr>
            <td>15.</td>
            <td colspan="12">NIK Ayah Angkat</td>
            <td class="kanan">:</td>
            <?= kotak($input['nik_ayah_angkat'], 16); ?>
        </tr>
        <tr>
            <td>16.</td>
            <td colspan="12">Nomor Paspor</td>
            <td class="kanan">:</td>
            <?= kotak($input['nomor_paspor_ayah'], 34); ?>
        </tr>
        <tr>
            <td>17.</td>
            <td colspan="12">Nama Pengadilan</td>
            <td class="kanan">:</td>
            <?= kotak($input['nama+pengadilan'], 18); ?>
        </tr>
        <tr>
            <td>18.</td>
            <td colspan="12">Tanggal Penetapan Pengadilan</td>
            <td class="kanan">:</td>
            <td>Tgl</td>
            <td class="kanan">:</td>
            <?= kotak(! empty($input['tanggal_penetapan_pengadilan']) ? date('dd', strtotime($input['tanggal_penetapan_pengadilan'])) : '', 2); ?>
            <td>Bln</td>
            <td class="kanan">:</td>
            <?= kotak(! empty($input['tanggal_penetapan_pengadilan']) ? date('mm', strtotime($input['tanggal_penetapan_pengadilan'])) : '', 2); ?>
            <td>Thn</td>
            <td class="kanan">:</td>
            <?= kotak(! empty($input['tanggal_penetapan_pengadilan']) ? date('Y', strtotime($input['tanggal_penetapan_pengadilan'])) : '', 4); ?>
        </tr>
        <tr>
            <td>19.</td>
            <td colspan="12">Nomor Penetapan Pengadilan</td>
            <td class="kanan">:</td>
            <?= kotak($input['nomor_penetapan_pengadilan'], 19); ?>
        </tr>
        <tr>
            <td>20.</td>
            <td colspan="12">Nama lembaga Penetapan Pengadilan</td>
            <td class="kanan">:</td>
            <?= kotak($input['nama_lembaga_penetapan_pengadilan'], 16); ?>
        </tr>
        <tr>
            <td>21.</td>
            <td colspan="12">Tempat lembaga Penetapan Pengadilan</td>
            <td class="kanan">:</td>
            <?= kotak($input['tempat_lembaga_penetapan_pengadilan'], 16); ?>
        </tr>

    </table>
    <?php endif ?>

    <?php if ($tampil_data_pengakuan_anak): ?>
    <table class="disdukcapil" style="margin-top: 5px;">
        <col span="48" style="width: 2.0833%;">
        <tr>
            <td colspan="48"><strong>PENGAKUAN ANAK</strong></td>
        </tr>
        <tr>
            <td>1.</td>
            <td colspan="12">Nomor Akta Kelahiran</td>
            <td class="kanan">:</td>
            <?= kotak($input['nomor_akta_kelahiran'], 17); ?>
        </tr>
        <tr>
            <td>2.</td>
            <td colspan="12">Tanggal/Bulan/Tahun</td>
            <td class="kanan">:</td>
            <td>Tgl</td>
            <td class="kanan">:</td>
            <?= kotak(! empty($input['tanggal_akta_kelahiran']) ? date('dd', strtotime($input['tanggal_akta_kelahiran'])) : '', 2); ?>
            <td>Bln</td>
            <td class="kanan">:</td>
            <?= kotak(! empty($input['tanggal_akta_kelahiran']) ? date('mm', strtotime($input['tanggal_akta_kelahiran'])) : '', 2); ?>
            <td>Thn</td>
            <td class="kanan">:</td>
            <?= kotak(! empty($input['tanggal_akta_kelahiran']) ? date('Y', strtotime($input['tanggal_akta_kelahiran'])) : '', 4); ?>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td colspan="47">Penerbitan Akta Kelahiran</td>
        </tr>
        <tr>
            <td>3.</td>
            <td colspan="12">Dinas Kabupaten/Kota yang</td>
            <td class="kanan">:</td>
            <?= kotak($input['penerbit_akta_kelahiran']); ?>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td colspan="47">menerbitkan Akta Kelahiran</td>
        </tr>
        <tr>
            <td>4.</td>
            <td colspan="12">Tanggal/Bulan/Tahun</td>
            <td class="kanan">:</td>
            <td>Tgl</td>
            <td class="kanan">:</td>
            <?= kotak(! empty($input['tanggal_kelahiran']) ? date('dd', strtotime($input['tanggal_kelahiran'])) : '', 2); ?>
            <td>Bln</td>
            <td class="kanan">:</td>
            <?= kotak(! empty($input['tanggal_kelahiran']) ? date('mm', strtotime($input['tanggal_kelahiran'])) : '', 2); ?>
            <td>Thn</td>
            <td class="kanan">:</td>
            <?= kotak(! empty($input['tanggal_kelahiran']) ? date('Y', strtotime($input['tanggal_kelahiran'])) : '', 4); ?>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td colspan="47">Kelahiran Anak</td>
        </tr>
        <tr>
            <td>5.</td>
            <td colspan="12">Tanggal/Bulan/Tahun</td>
            <td class="kanan">:</td>
            <td>Tgl</td>
            <td class="kanan">:</td>
            <?= kotak(! empty($input['tanggal_perkawinan_agama']) ? date('dd', strtotime($input['tanggal_perkawinan_agama'])) : '', 2); ?>
            <td>Bln</td>
            <td class="kanan">:</td>
            <?= kotak(! empty($input['tanggal_perkawinan_agama']) ? date('mm', strtotime($input['tanggal_perkawinan_agama'])) : '', 2); ?>
            <td>Thn</td>
            <td class="kanan">:</td>
            <?= kotak(! empty($input['tanggal_perkawinan_agama']) ? date('Y', strtotime($input['tanggal_perkawinan_agama'])) : '', 4); ?>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td colspan="47">Perkawinan Agama</td>
        </tr>
        <tr>
            <td>6.</td>
            <td colspan="12">Nama Ibu Kandung</td>
            <td class="kanan">:</td>
            <?= kotak($input['nama_ibu_kandung'], 34); ?>
        </tr>
        <tr>
            <td>7.</td>
            <td colspan="12">NIK Ibu Kandung</td>
            <td class="kanan">:</td>
            <?= kotak($input['nik_ibu_kandung'], 16); ?>
        </tr>
        <tr>
            <td>8.</td>
            <td colspan="12">Kewarganegaraan Ibu Kandung</td>
            <td class="kanan">:</td>
            <?= kotak($input['kewarganegaraan_ibu_kandung'], 34); ?>
        </tr>
        <tr>
            <td>9.</td>
            <td colspan="12">Nama Ayah</td>
            <td class="kanan">:</td>
            <?= kotak($input['nama_ayah_kandung'], 34); ?>
        </tr>
        <tr>
            <td>10.</td>
            <td colspan="12">NIK Ayah Kandung</td>
            <td class="kanan">:</td>
            <?= kotak($input['nik_ayah_kandung'], 16); ?>
        </tr>
        <tr>
            <td>11.</td>
            <td colspan="12">Kewarganegaraan Ayah Kandung</td>
            <td class="kanan">:</td>
            <?= kotak($input['kewarganegaraan_ayah_kandung'], 34); ?>
        </tr>
        <tr>
            <td>12.</td>
            <td colspan="12">Tanggal Penetapan Pengadilan</td>
            <td class="kanan">:</td>
            <td>Tgl</td>
            <td class="kanan">:</td>
            <?= kotak(! empty($input['tanggal_penetapan_pengadilan']) ? date('dd', strtotime($input['tanggal_penetapan_pengadilan'])) : '', 2); ?>
            <td>Bln</td>
            <td class="kanan">:</td>
            <?= kotak(! empty($input['tanggal_penetapan_pengadilan']) ? date('mm', strtotime($input['tanggal_penetapan_pengadilan'])) : '', 2); ?>
            <td>Thn</td>
            <td class="kanan">:</td>
            <?= kotak(! empty($input['tanggal_penetapan_pengadilan']) ? date('Y', strtotime($input['tanggal_penetapan_pengadilan'])) : '', 4); ?>
        </tr>
        <tr>
            <td>13.</td>
            <td colspan="12">Nomor Penetapan Pengadilan</td>
            <td class="kanan">:</td>
            <?= kotak($input['nomor_penetapan_pengadilan'], 19); ?>
        </tr>
        <tr>
            <td>14.</td>
            <td colspan="12">Nama lembaga Pengadilan</td>
            <td class="kanan">:</td>
            <?= kotak($input['nama_lembaga_pengadilan'], 34); ?>
        </tr>
    </table>
    <?php endif ?>

    <?php if ($tampil_data_pengesahan_anak): ?>
    <table class="disdukcapil" style="margin-top: 5px;">
        <col span="48" style="width: 2.0833%;">
        <tr>
            <td colspan="48"><strong>PENGESAHAN ANAK</strong></td>
        </tr>
        <tr>
            <td>1.</td>
            <td colspan="12">Nomor Akta Kelahiran</td>
            <td class="kanan">:</td>
            <?= kotak($input['nomor_akta_kelahiran'], 17); ?>
        </tr>
        <tr>
            <td>2.</td>
            <td colspan="12">Tanggal/Bulan/Tahun</td>
            <td class="kanan">:</td>
            <td>Tgl</td>
            <td class="kanan">:</td>
            <?= kotak(! empty($input['tanggal_akta_kelahiran']) ? date('dd', strtotime($input['tanggal_akta_kelahiran'])) : '', 2); ?>
            <td>Bln</td>
            <td class="kanan">:</td>
            <?= kotak(! empty($input['tanggal_akta_kelahiran']) ? date('mm', strtotime($input['tanggal_akta_kelahiran'])) : '', 2); ?>
            <td>Thn</td>
            <td class="kanan">:</td>
            <?= kotak(! empty($input['tanggal_akta_kelahiran']) ? date('Y', strtotime($input['tanggal_akta_kelahiran'])) : '', 4); ?>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td colspan="47">Penerbitan Akta Kelahiran</td>
        </tr>
        <tr>
            <td>3.</td>
            <td colspan="12">Dinas Kabupaten/Kota yang</td>
            <td class="kanan">:</td>
            <?= kotak($input['penerbit_akta_kelahiran'], 34); ?>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td colspan="47">menerbitkan Akta Kelahiran</td>
        </tr>
        <tr>
            <td>4.</td>
            <td colspan="12">Tanggal/Bulan/Tahun</td>
            <td class="kanan">:</td>
            <td>Tgl</td>
            <td class="kanan">:</td>
            <?= kotak(! empty($input['tanggal_kelahiran']) ? date('dd', strtotime($input['tanggal_kelahiran'])) : '', 2); ?>
            <td>Bln</td>
            <td class="kanan">:</td>
            <?= kotak(! empty($input['tanggal_kelahiran']) ? date('mm', strtotime($input['tanggal_kelahiran'])) : '', 2); ?>
            <td>Thn</td>
            <td class="kanan">:</td>
            <?= kotak(! empty($input['tanggal_kelahiran']) ? date('Y', strtotime($input['tanggal_kelahiran'])) : '', 4); ?>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td colspan="47">Kelahiran Anak</td>
        </tr>
        <tr>
            <td>5.</td>
            <td colspan="12">Tanggal/Bulan/Tahun</td>
            <td class="kanan">:</td>
            <td>Tgl</td>
            <td class="kanan">:</td>
            <?= kotak(! empty($input['tanggal_perkawinan_agama']) ? date('dd', strtotime($input['tanggal_perkawinan_agama'])) : '', 2); ?>
            <td>Bln</td>
            <td class="kanan">:</td>
            <?= kotak(! empty($input['tanggal_perkawinan_agama']) ? date('mm', strtotime($input['tanggal_perkawinan_agama'])) : '', 2); ?>
            <td>Thn</td>
            <td class="kanan">:</td>
            <?= kotak(! empty($input['tanggal_perkawinan_agama']) ? date('Y', strtotime($input['tanggal_perkawinan_agama'])) : '', 4); ?>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td colspan="47">Perkawinan Agama</td>
        </tr>
        <tr>
            <td>6.</td>
            <td colspan="12">Nomor Tanggal/Bulan/Tahun</td>
            <td class="kanan">:</td>
            <?= kotak($input['nomor_akta_perkawinan_buku_nikah'], 34); ?>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td colspan="12">Akta Perkawinan/Buku Nikah</td>
            <td class="kanan">:</td>
            <td>Tgl</td>
            <td class="kanan">:</td>
            <?= kotak(! empty($input['tanggal_akta_perkawinan_buku_nikah']) ? date('dd', strtotime($input['tanggal_akta_perkawinan_buku_nikah'])) : '', 2); ?>
            <td>Bln</td>
            <td class="kanan">:</td>
            <?= kotak(! empty($input['tanggal_akta_perkawinan_buku_nikah']) ? date('mm', strtotime($input['tanggal_akta_perkawinan_buku_nikah'])) : '', 2); ?>
            <td>Thn</td>
            <td class="kanan">:</td>
            <?= kotak(! empty($input['tanggal_akta_perkawinan_buku_nikah']) ? date('Y', strtotime($input['tanggal_akta_perkawinan_buku_nikah'])) : '', 4); ?>
        </tr>
        <tr>
            <td>7.</td>
            <td colspan="12">Nama Ibu Kandung</td>
            <td class="kanan">:</td>
            <?= kotak($input['nama_ibu_kandung'], 34); ?>
        </tr>
        <tr>
            <td>8.</td>
            <td colspan="12">NIK Ibu Kandung</td>
            <td class="kanan">:</td>
            <?= kotak($input['nik_ibu_kandung'], 16); ?>
        </tr>
        <tr>
            <td>9.</td>
            <td colspan="12">Kewarganegaraan Ibu Kandung</td>
            <td class="kanan">:</td>
            <?= kotak($input['kewarganegaraan_ibu_kandung'], 34); ?>
        </tr>
        <tr>
            <td>10.</td>
            <td colspan="12">Nama Ayah</td>
            <td class="kanan">:</td>
            <?= kotak($input['nama_ayah_kandung'], 34); ?>
        </tr>
        <tr>
            <td>11.</td>
            <td colspan="12">NIK Ayah Kandung</td>
            <td class="kanan">:</td>
            <?= kotak($input['nik_ayah_kandung'], 16); ?>
        </tr>
        <tr>
            <td>12.</td>
            <td colspan="12">Kewarganegaraan Ayah Kandung</td>
            <td class="kanan">:</td>
            <?= kotak($input['kewarganegaraan_ayah_kandung'], 34); ?>
        </tr>
        <tr>
            <td>13.</td>
            <td colspan="12">Tanggal Penetapan Pengadilan</td>
            <td class="kanan">:</td>
            <td>Tgl</td>
            <td class="kanan">:</td>
            <?= kotak(! empty($input['tanggal_penetapan_pengadilan']) ? date('dd', strtotime($input['tanggal_penetapan_pengadilan'])) : '', 2); ?>
            <td>Bln</td>
            <td class="kanan">:</td>
            <?= kotak(! empty($input['tanggal_penetapan_pengadilan']) ? date('mm', strtotime($input['tanggal_penetapan_pengadilan'])) : '', 2); ?>
            <td>Thn</td>
            <td class="kanan">:</td>
            <?= kotak(! empty($input['tanggal_penetapan_pengadilan']) ? date('Y', strtotime($input['tanggal_penetapan_pengadilan'])) : '', 4); ?>
        </tr>
        <tr>
            <td>14.</td>
            <td colspan="12">Nomor Penetapan Pengadilan</td>
            <td class="kanan">:</td>
            <?= kotak($input['nomor_penetapan_pengadilan'], 19); ?>
        </tr>
        <tr>
            <td>15.</td>
            <td colspan="12">Nama lembaga Pengadilan</td>
            <td class="kanan">:</td>
            <?= kotak($input['nama_lembaga_pengadilan'], 34); ?>
        </tr>
    </table>
    <?php endif ?>

    <?php if ($tampil_data_perubahan_nama): ?>
    <table class="disdukcapil" style="margin-top: 5px;">
        <col span="48" style="width: 2.0833%;">
        <tr>
            <td colspan="48"><strong>PERUBAHAN NAMA</strong></td>
        </tr>
        <tr>
            <td>1.</td>
            <td colspan="12">Nama Baru</td>
            <td class="kanan">:</td>
            <?= kotak($input['nama_baru'], 34); ?>
        </tr>
        <tr>
            <td>2.</td>
            <td colspan="12">Nama Lama</td>
            <td class="kanan">:</td>
            <?= kotak($input['nama_lama'], 34); ?>
        </tr>
        <tr>
            <td>3.</td>
            <td colspan="12">Nomor Akta Kelahiran</td>
            <td class="kanan">:</td>
            <?= kotak($input['nomor_akta_kelahiran'], 17); ?>
        </tr>
        <tr>
            <td>4.</td>
            <td colspan="12">Nama Ayah/Ibu/Wali</td>
            <td class="kanan">:</td>
            <?= kotak($input['nama_ayah_ibu_wali'], 34); ?>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td colspan="47">(bagi yang di bawah umur)</td>
        </tr>
        <tr>
            <td>5.</td>
            <td colspan="12">NIK Nama Ayah/Ibu/Wali</td>
            <td class="kanan">:</td>
            <?= kotak($input['nik_ayah_ibu_wali'], 16); ?>
        </tr>
        <tr>
            <td>6.</td>
            <td colspan="12">Kewarganegaraan</td>
            <td class="kanan">:</td>
            <?= kotak($input['kewarganegaraan'], 34); ?>
        </tr>
        <tr>
            <td>7.</td>
            <td colspan="12">Nomor Penetapan Pengadilan</td>
            <td class="kanan">:</td>
            <?= kotak($input['nomor_penetapan_pengadilan'], 19); ?>
        </tr>
        <tr>
            <td>8.</td>
            <td colspan="12">Tanggal Penetapan Pengadilan</td>
            <td class="kanan">:</td>
            <td>Tgl</td>
            <td class="kanan">:</td>
            <?= kotak(! empty($input['tanggal_penetapan_pengadilan']) ? date('dd', strtotime($input['tanggal_penetapan_pengadilan'])) : '', 2); ?>
            <td>Bln</td>
            <td class="kanan">:</td>
            <?= kotak(! empty($input['tanggal_penetapan_pengadilan']) ? date('mm', strtotime($input['tanggal_penetapan_pengadilan'])) : '', 2); ?>
            <td>Thn</td>
            <td class="kanan">:</td>
            <?= kotak(! empty($input['tanggal_penetapan_pengadilan']) ? date('Y', strtotime($input['tanggal_penetapan_pengadilan'])) : '', 4); ?>
        </tr>
        <tr>
            <td>9.</td>
            <td colspan="12">Nama lembaga Pengadilan</td>
            <td class="kanan">:</td>
            <?= kotak($input['nama_lembaga_pengadilan'], 34); ?>
        </tr>
    </table>
    <?php endif ?>
    <!-- Akhir Halaman 3 -->

    <!-- Awal Halaman 4 -->
    <?php if ($tampil_data_perubahan_status_kewarganeraan): ?>
    <table class="disdukcapil" style="margin-top: 5px;">
        <col span="48" style="width: 2.0833%;">
        <tr>
            <td colspan="48"><strong>PERUBAHAN STATUS KEWARGANERAAN</strong></td>
        </tr>
        <tr>
            <td>1.</td>
            <td colspan="12">Kewarganegaraan Baru</td>
            <td class="kanan">:</td>
            <?= kotak($input['kewarganegaraan_baru'], 17); ?>
        </tr>
        <tr>
            <td>2.</td>
            <td colspan="12">Nomor Akta Kelahiran</td>
            <td class="kanan">:</td>
            <?= kotak($input['nomor_akta_kelahiran'], 17); ?>
        </tr>
        <tr>
            <td>3.</td>
            <td colspan="12">Nomor Akta Perkawinan</td>
            <td class="kanan">:</td>
            <?= kotak($input['nomor_akta_perkawinan'], 17); ?>
        </tr>
        <tr>
            <td>4.</td>
            <td colspan="12">Nama Suami atau Istri</td>
            <td class="kanan">:</td>
            <?= kotak($input['nama_suami_atau_istri'], 34); ?>
        </tr>
        <tr>
            <td>5.</td>
            <td colspan="12">NIK Suami atau Istri</td>
            <td class="kanan">:</td>
            <?= kotak($input['nik_suami_atau_istri'], 16); ?>
        </tr>
        <tr>
            <td>6.</td>
            <td colspan="12">Nomor Paspor</td>
            <td class="kanan">:</td>
            <?= kotak($input['nomor_paspor'], 17); ?>
        </tr>
        <tr>
            <td>7.</td>
            <td colspan="12">Nomor Affidavit</td>
            <td class="kanan">:</td>
            <?= kotak($input['nomor_affidavit'], 17); ?>
        </tr>
        <tr>
            <td>8.</td>
            <td colspan="12">Nomor Keputusan Presiden</td>
            <td class="kanan">:</td>
            <?= kotak($input['nomor_keputusan_presiden'], 17); ?>
        </tr>
        <tr>
            <td>9.</td>
            <td colspan="12">Tanggal/Bulan/Tahun</td>
            <td class="kanan">:</td>
            <td>Tgl</td>
            <td class="kanan">:</td>
            <?= kotak(! empty($input['tanggal_keputusan_presiden']) ? date('dd', strtotime($input['tanggal_keputusan_presiden'])) : '', 2); ?>
            <td>Bln</td>
            <td class="kanan">:</td>
            <?= kotak(! empty($input['tanggal_keputusan_presiden']) ? date('mm', strtotime($input['tanggal_keputusan_presiden'])) : '', 2); ?>
            <td>Thn</td>
            <td class="kanan">:</td>
            <?= kotak(! empty($input['tanggal_keputusan_presiden']) ? date('Y', strtotime($input['tanggal_keputusan_presiden'])) : '', 4); ?>
        </tr>
        <tr>
            <td>10.</td>
            <td colspan="12">Nomor Berita Acara Sumpah/Janji Setia</td>
            <td class="kanan">:</td>
            <?= kotak($input['nomor_berita_acara'], 17); ?>
        </tr>
        <tr>
            <td>11.</td>
            <td colspan="12">Nama Jabatan yang menerbitkan</td>
            <td class="kanan">:</td>
            <?= kotak($input['nama_jabatan'], 34); ?>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td colspan="47">BAS/Janji Setia</td>
        </tr>
        <tr>
            <td>12.</td>
            <td colspan="12">Tanggal/Bulan/Tahun</td>
            <td class="kanan">:</td>
            <td>Tgl</td>
            <td class="kanan">:</td>
            <?= kotak(! empty($input['tanggal_jabatan']) ? date('dd', strtotime($input['tanggal_jabatan'])) : '', 2); ?>
            <td>Bln</td>
            <td class="kanan">:</td>
            <?= kotak(! empty($input['tanggal_jabatan']) ? date('mm', strtotime($input['tanggal_jabatan'])) : '', 2); ?>
            <td>Thn</td>
            <td class="kanan">:</td>
            <?= kotak(! empty($input['tanggal_jabatan']) ? date('Y', strtotime($input['tanggal_jabatan'])) : '', 4); ?>
        </tr>
        <tr>
            <td>13.</td>
            <td colspan="12">Nomor Keputusan Menteri</td>
            <td class="kanan">:</td>
            <?= kotak($input['nomor_keputusan_menteri'], 17); ?>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td colspan="47">(Bidang Kewarganeraan)</td>
        </tr>
        <tr>
            <td>14.</td>
            <td colspan="12">Tanggal/Bulan/Tahun</td>
            <td class="kanan">:</td>
            <td>Tgl</td>
            <td class="kanan">:</td>
            <?= kotak(! empty($input['tanggal_keputusan_menteri']) ? date('dd', strtotime($input['tanggal_keputusan_menteri'])) : '', 2); ?>
            <td>Bln</td>
            <td class="kanan">:</td>
            <?= kotak(! empty($input['tanggal_keputusan_menteri']) ? date('mm', strtotime($input['tanggal_keputusan_menteri'])) : '', 2); ?>
            <td>Thn</td>
            <td class="kanan">:</td>
            <?= kotak(! empty($input['tanggal_keputusan_menteri']) ? date('Y', strtotime($input['tanggal_keputusan_menteri'])) : '', 4); ?>
        </tr>
    </table>
    <?php endif ?>

    <?php if ($tampil_data_perubahan_peristiwa_lain): ?>
    <table class="disdukcapil" style="margin-top: 5px;">
        <col span="48" style="width: 2.0833%;">
        <tr>
            <td colspan="48"><strong>PERUBAHAN PERISTIWA PENTING LAINNYA</strong></td>
        </tr>
        <tr>
            <td>1.</td>
            <td colspan="12">Nomor Akta Kelahiran</td>
            <td class="kanan">:</td>
            <?= kotak($input['nomor_akta_kelahiran'], 17); ?>
        </tr>
        <tr>
            <td>2.</td>
            <td colspan="12">Jenis kelamin Lama</td>
            <td class="kanan">:</td>
            <?= checklist($input['sex_lama'], 1); ?>
            <td colspan="6">1. Laki-laki </td>
            <?= checklist($input['sex_lama'], 2); ?>
            <td colspan="7">2. Perempuan </td>
            <td colspan="11">&nbsp;</td>
        </tr>
        <tr>
            <td>3.</td>
            <td colspan="12">Jenis kelamin Baru</td>
            <td class="kanan">:</td>
            <?= checklist($input['sex_baru'], 1); ?>
            <td colspan="6">1. Laki-laki </td>
            <?= checklist($input['sex_baru'], 2); ?>
            <td colspan="7">2. Perempuan </td>
            <td colspan="11">&nbsp;</td>
        </tr>
        <tr>
            <td>4.</td>
            <td colspan="12">Nomor Penetapan Pengadilan</td>
            <td class="kanan">:</td>
            <?= kotak($input['nomor_penetapan_pengadilan'], 19); ?>
        </tr>
        <tr>
            <td>5.</td>
            <td colspan="12">Tanggal Penetapan Pengadilan</td>
            <td class="kanan">:</td>
            <td>Tgl</td>
            <td class="kanan">:</td>
            <?= kotak(! empty($input['tanggal_penetapan_pengadilan']) ? date('dd', strtotime($input['tanggal_penetapan_pengadilan'])) : '', 2); ?>
            <td>Bln</td>
            <td class="kanan">:</td>
            <?= kotak(! empty($input['tanggal_penetapan_pengadilan']) ? date('mm', strtotime($input['tanggal_penetapan_pengadilan'])) : '', 2); ?>
            <td>Thn</td>
            <td class="kanan">:</td>
            <?= kotak(! empty($input['tanggal_penetapan_pengadilan']) ? date('Y', strtotime($input['tanggal_penetapan_pengadilan'])) : '', 4); ?>
        </tr>
        <tr>
            <td>6.</td>
            <td colspan="12">Nama lembaga Pengadilan</td>
            <td class="kanan">:</td>
            <?= kotak($input['nama_lembaga_pengadilan'], 34); ?>
        </tr>
    </table>
    <?php endif ?>

    <?php if ($tampil_data_perubahan_akta): ?>
    <table class="disdukcapil" style="margin-top: 5px;">
        <col span="48" style="width: 2.0833%;">
        <tr>
            <td colspan="48"><strong>PEMBETULAN AKTA</strong></td>
        </tr>
        <tr>
            <td>1.</td>
            <td colspan="12">Nomor Akta yang akan</td>
            <td class="kanan">:</td>
            <?= kotak($input['nomor_akta'], 17); ?>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td colspan="47">dibetulkan/ditarik</td>
        </tr>
        <tr>
            <td>2.</td>
            <td colspan="12">Nama Ayah/Ibu/Wali</td>
            <td class="kanan">:</td>
            <?= kotak($input['nama_ayah_ibu_wali'], 34); ?>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td colspan="47">(bagi yang di bawah umur)</td>
        </tr>
        <tr>
            <td>3.</td>
            <td colspan="12">NIK Nama Ayah/Ibu/Wali</td>
            <td class="kanan">:</td>
            <?= kotak($input['nik_ayah_ibu_wali'], 16); ?>
        </tr>
        <tr>
            <td colspan="48">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="48"><strong>PEMBATALAN AKTA</strong></td>
        </tr>
        <tr>
            <td>1.</td>
            <td colspan="12">Akta yang dibatalkan</td>
            <td class="kanan">:</td>
            <?= kotak($input['akta_dibatalkan'], 17); ?>
        </tr>
        <tr>
            <td>2.</td>
            <td colspan="12">Nomor Akta yang dibatalkan</td>
            <td class="kanan">:</td>
            <?= kotak($input['nomor_akta_dibatalkan'], 17); ?>
        </tr>
        <tr>
            <td>3.</td>
            <td colspan="12">Nomor Putusan Pengadilan</td>
            <td class="kanan">:</td>
            <?= kotak($input['nomor_putusan_pengadilan'], 17); ?>
        </tr>
        <tr>
            <td>4.</td>
            <td colspan="12">Tanggal Putusan Pengadilan</td>
            <td class="kanan">:</td>
            <td>Tgl</td>
            <td class="kanan">:</td>
            <?= kotak(! empty($input['tanggal_putusan_pengadilan']) ? date('dd', strtotime($input['tanggal_putusan_pengadilan'])) : '', 2); ?>
            <td>Bln</td>
            <td class="kanan">:</td>
            <?= kotak(! empty($input['tanggal_putusan_pengadilan']) ? date('mm', strtotime($input['tanggal_putusan_pengadilan'])) : '', 2); ?>
            <td>Thn</td>
            <td class="kanan">:</td>
            <?= kotak(! empty($input['tanggal_putusan_pengadilan']) ? date('Y', strtotime($input['tanggal_putusan_pengadilan'])) : '', 4); ?>
        </tr>
        <tr>
            <td>5.</td>
            <td colspan="12">Nama lembaga Pengadilan</td>
            <td class="kanan">:</td>
            <?= kotak($input['nama_lembaga_pengadilan'], 34); ?>
        </tr>
    </table>
    <?php endif ?>

    <?php if ($tampil_data_pelaporan_luar_nkri): ?>
    <table class="disdukcapil" style="margin-top: 5px;">
        <col span="48" style="width: 2.0833%;">
        <tr>
            <td colspan="48"><strong>PELAPORAN PENCATATAN SIPIL DARI LUAR WILAYAH NKRI</strong></td>
        </tr>
        <tr>
            <td>E.</td>
            <td colspan="12">Jenis Peristiwa Penting</td>
            <td class="kanan">:</td>
            <?= checklist($input['kelahiran'], 1); ?>
            <td colspan="5">1. Kelahiran</td>
            <?= checklist($input['perkawinan'], 2); ?>
            <td colspan="5">2. Perkawinan</td>
            <?= checklist($input['perceraian'], 3); ?>
            <td colspan="5">3. Perceraian</td>
            <?= checklist($input['kematian'], 4); ?>
            <td colspan="4">4. Kematian</td>
            <td class="kanan"></td>
            <?= checklist($input['pengangkatan_anak'], 5); ?>
            <td colspan="8">5. Pengangkatan Anak</td>
        </tr>
        <tr>
            <td colspan="14">&nbsp;</td>
            <?= checklist($input['pelepasan_kewarganeraan'], 6); ?>
            <td colspan="12">6. Pelepasan Kewarganeraan RI</td>
        </tr>
        <tr>
            <td>F.</td>
            <td colspan="12">Surat Nomor Keterangan Pelaporan</td>
            <td class="kanan">:</td>
            <?= kotak($input['surat_nomor_keterangan_pelaporan'], 17); ?>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td colspan="47">Pencatatan Sipil dan Perwakilan RI</td>
        </tr>
        <tr>
            <td>G.</td>
            <td colspan="12">Tanggal Surat Keterangan Pelaporan</td>
            <td class="kanan">:</td>
            <?= kotak($input['tanggal_surat_keterangan_pelaporan'], 6); ?>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td colspan="47">Pencatatan Sipil dan Perwakilan RI</td>
        </tr>
        <tr>
            <td>H.</td>
            <td colspan="12">Kantor Perwakilan yg melakukan</td>
            <td class="kanan">:</td>
            <?= kotak($input['kantor_perwakilan_pencatatan_sipil'], 17); ?>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td colspan="47">Pencatatan</td>
        </tr>
        <tr>
            <td>I.</td>
            <td colspan="12">Nomor Bukti Pencatatan Sipil dari</td>
            <td class="kanan">:</td>
            <?= kotak($input['bukti_pencatatan_sipil'], 17); ?>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td colspan="47">Pencatatan</td>
        </tr>
        <tr>
            <td>J.</td>
            <td colspan="12">Tanggal Penerbitan dari Negara Setempat</td>
            <td class="kanan">:</td>
            <?= kotak($input['tanggal_penerbitan'], 6); ?>
        </tr>
    </table>
    <?php endif ?>

    <!-- Penandatangan -->
    <br><br><br>
    <table style="border-collapse: collapse; width: 100%; height: 144px;" border="0">
        <tbody>
            <tr style="height: 18px;">
                <td style="width: 30%; text-align: center; height: 18px;">Mengetahui :</td>
                <td style="width: 40%; height: 18px;"> </td>
                <td style="width: 30%; text-align: center; height: 18px;">[NAma_desa], [TgL_surat]</td>
            </tr>
            <tr style="height: 18px;">
                <td style="width: 30%; text-align: center; height: 18px;">Pejabat Dukcapil Yang Membidangi</td>
                <td style="width: 40%; height: 18px;"> </td>
                <td style="width: 30%; text-align: center; height: 18px;">Pelapor</td>
            </tr>
            <tr style="height: 72px;">
                <td style="width: 30%; text-align: center; height: 72px;"> </td>
                <td style="width: 40%; height: 72px;"><br><br><br><br></td>
                <td style="width: 30%; height: 72px;"> </td>
            </tr>
            <tr style="height: 18px;">
                <td style="width: 30%; text-align: center; height: 18px;"><strong>(<?= str_pad('.', 50, '.', STR_PAD_LEFT); ?>)</strong></td>
                <td style="width: 40%; height: 18px;"> </td>
                <td style="width: 30%; text-align: center; height: 18px;"><strong>(<?= padded_string_center(strtoupper($input['nama_pelapor']), 30) ?>)</strong></td>
            </tr>
            <tr style="height: 18px;">
                <td style="width: 30%; height: 18px;"> </td>
                <td style="width: 40%; height: 18px;"> </td>
                <td style="width: 30%; text-align: center; height: 18px;"> </td>
            </tr>
        </tbody>
    </table>
    <!-- Akhir Halaman 4 -->
</page>