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
            <?php for ($i = 0; $i < 10; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($config['kode_desa'][$i])): ?>
                        <?= $config['kode_desa'][$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
            <td colspan="24">&nbsp;</td>
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
            <td class="kotak padat tengah"><?= jecho($format_f201, 1, 'v') ?></td>
            <td colspan="24">Kelahiran</td>
            <td class="kotak padat tengah"><?= jecho($format_f201, 9, 'v') ?></td>
            <td colspan="29">Pengakuan Anak</td>
        </tr>
        <tr>
            <td class="kotak padat tengah"><?= jecho($format_f201, 2, 'v') ?></td>
            <td colspan="24">Lahir Mati</td>
            <td class="kotak padat tengah"><?= jecho($format_f201, 10, 'v') ?></td>
            <td colspan="29">Pengesahan Anak</td>
        </tr>
        <tr>
            <td class="kotak padat tengah"><?= jecho($format_f201, 3, 'v') ?></td>
            <td colspan="24">Perkawinan</td>
            <td class="kotak padat tengah"><?= jecho($format_f201, 11, 'v') ?></td>
            <td colspan="29">Perubahan Nama</td>
        </tr>
        <tr>
            <td class="kotak padat tengah"><?= jecho($format_f201, 4, 'v') ?></td>
            <td colspan="24">Pembatalan Perkawinan</td>
            <td class="kotak padat tengah"><?= jecho($format_f201, 12, 'v') ?></td>
            <td colspan="29">Perubahan Status Kewarganegaraan</td>
        </tr>
        <tr>
            <td class="kotak padat tengah"><?= jecho($format_f201, 5, 'v') ?></td>
            <td colspan="24">Perceraian</td>
            <td class="kotak padat tengah"><?= jecho($format_f201, 13, 'v') ?></td>
            <td colspan="29">Pencatatan Peristiwa Penting Lainnya</td>
        </tr>
        <tr>
            <td class="kotak padat tengah"><?= jecho($format_f201, 6, 'v') ?></td>
            <td colspan="24">Pembatalan Perceraian</td>
            <td class="kotak padat tengah"><?= jecho($format_f201, 14, 'v') ?></td>
            <td colspan="29">Pembetulan Akta</td>
        </tr>
        <tr>
            <td class="kotak padat tengah"><?= jecho($format_f201, 7, 'v') ?></td>
            <td colspan="24">Kematian</td>
            <td class="kotak padat tengah"><?= jecho($format_f201, 15, 'v') ?></td>
            <td colspan="29">Pembatalan Akta</td>
        </tr>
        <tr>
            <td class="kotak padat tengah"><?= jecho($format_f201, 8, 'v') ?></td>
            <td colspan="24">Pengangkatan Anak</td>
            <td class="kotak padat tengah"><?= jecho($format_f201, 16, 'v') ?></td>
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
            <?php for ($i = 0; $i < 26; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($input['nama_pelapor'][$i])): ?>
                        <?= $input['nama_pelapor'][$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td colspan="21">NIK</td>
            <td class="kanan">:</td>
            <?php for ($i = 0; $i < 16; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($input['nik_pelapor'][$i])): ?>
                        <?= $input['nik_pelapor'][$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td colspan="21">Nomor Dokumen Perjalanan*</td>
            <td class="kanan">:</td>
            <?php for ($i = 0; $i < 16; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($input['dokumen_perjalanan_pelapor'][$i])): ?>
                        <?= $input['dokumen_perjalanan_pelapor'][$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td colspan="21">Nomor Kartu Keluarga</td>
            <td class="kanan">:</td>
            <?php for ($i = 0; $i < 16; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($input['no_kk_pelapor'][$i])): ?>
                        <?= $input['no_kk_pelapor'][$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td colspan="21">Kewarganegaraan</td>
            <td class="kanan">:</td>
            <?php for ($i = 0; $i < 26; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($input['kewarganegaraan_pelapor'][$i])): ?>
                        <?= $input['kewarganegaraan_pelapor'][$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
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
            <?php for ($i = 0; $i < 26; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($input['kata_akta1'][$i])): ?>
                        <?= $input['kata_akta1'][$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td colspan="21">NIK</td>
            <td class="kanan">:</td>
            <?php for ($i = 0; $i < 16; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($input['nik_akta1'][$i])): ?>
                        <?= $input['nik_akta1'][$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td colspan="21">Nomor Dokumen Perjalanan*</td>
            <td class="kanan">:</td>
            <?php for ($i = 0; $i < 16; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($input['dokumen_perjalanan_akta1'][$i])): ?>
                        <?= $input['dokumen_perjalanan_akta1'][$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td colspan="21">Nomor Kartu Keluarga</td>
            <td class="kanan">:</td>
            <?php for ($i = 0; $i < 16; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($input['no_kk_akta1'][$i])): ?>
                        <?= $input['no_kk_akta1'][$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td colspan="21">Kewarganegaraan</td>
            <td class="kanan">:</td>
            <?php for ($i = 0; $i < 26; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($input['kewarganegaraan_akta1'][$i])): ?>
                        <?= $input['kewarganegaraan_akta1'][$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
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
            <?php for ($i = 0; $i < 26; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($input['kata_akta2'][$i])): ?>
                        <?= $input['kata_akta2'][$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td colspan="21">NIK</td>
            <td class="kanan">:</td>
            <?php for ($i = 0; $i < 16; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($input['nik_akta2'][$i])): ?>
                        <?= $input['nik_akta2'][$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td colspan="21">Nomor Dokumen Perjalanan*</td>
            <td class="kanan">:</td>
            <?php for ($i = 0; $i < 16; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($input['dokumen_perjalanan_akta2'][$i])): ?>
                        <?= $input['dokumen_perjalanan_akta2'][$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td colspan="21">Nomor Kartu Keluarga</td>
            <td class="kanan">:</td>
            <?php for ($i = 0; $i < 16; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($input['no_kk_akta2'][$i])): ?>
                        <?= $input['no_kk_akta2'][$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td colspan="21">Kewarganegaraan</td>
            <td class="kanan">:</td>
            <?php for ($i = 0; $i < 26; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($input['kewarganegaraan_akta2'][$i])): ?>
                        <?= $input['kewarganegaraan_akta2'][$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
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
            <?php for ($i = 0; $i < 26; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($input['nama_saksi1'][$i])): ?>
                        <?= $input['nama_saksi1'][$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td colspan="21">NIK</td>
            <td class="kanan">:</td>
            <?php for ($i = 0; $i < 16; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($input['nik_saksi1'][$i])): ?>
                        <?= $input['nik_saksi1'][$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td colspan="21">Nomor Kartu Keluarga</td>
            <td class="kanan">:</td>
            <?php for ($i = 0; $i < 16; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($input['no_kk_saksi1'][$i])): ?>
                        <?= $input['no_kk_saksi1'][$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td colspan="21">Kewarganegaraan</td>
            <td class="kanan">:</td>
            <?php for ($i = 0; $i < 26; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($input['kewarganegaraan_saksi1'][$i])): ?>
                        <?= $input['kewarganegaraan_saksi1'][$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td colspan="48"><strong><u>DATA SAKSI II</u></strong></td>
        </tr>
        <tr>
            <td colspan="21">Nama</td>
            <td class="kanan">:</td>
            <?php for ($i = 0; $i < 26; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($input['nama_saksi2'][$i])): ?>
                        <?= $input['nama_saksi2'][$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td colspan="21">NIK</td>
            <td class="kanan">:</td>
            <?php for ($i = 0; $i < 16; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($input['nik_saksi2'][$i])): ?>
                        <?= $input['nik_saksi2'][$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td colspan="21">Nomor Kartu Keluarga</td>
            <td class="kanan">:</td>
            <?php for ($i = 0; $i < 16; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($input['no_kk_saksi2'][$i])): ?>
                        <?= $input['no_kk_saksi2'][$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td colspan="21">Kewarganegaraan</td>
            <td class="kanan">:</td>
            <?php for ($i = 0; $i < 26; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($input['kewarganegaraan_saksi2'][$i])): ?>
                        <?= $input['kewarganegaraan_saksi2'][$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
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
            <?php for ($i = 0; $i < 26; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($input['nama_ayah'][$i])): ?>
                        <?= $input['nama_ayah'][$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td colspan="21">NIK Ayah</td>
            <td class="kanan">:</td>
            <?php for ($i = 0; $i < 16; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($input['nik_ayah'][$i])): ?>
                        <?= $input['nik_ayah'][$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td colspan="21">Tempat Lahir Ayah</td>
            <td class="kanan">:</td>
            <?php for ($i = 0; $i < 26; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($input['tempat_lahir_ayah'][$i])): ?>
                        <?= $input['tempat_lahir_ayah'][$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td colspan="21">Tanggal Lahir Ayah</td>
            <td class="kanan">:</td>
            <td>Tgl</td>
            <td class="kanan">:</td>
            <?php $tgl_ayah = date('dd', strtotime($input['tanggal_perkawinan'])); ?>
            <?php for ($i = 0; $i < 2; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($tgl_ayah[$i])): ?>
                        <?= $tgl_ayah[$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
            <td>Bln</td>
            <td class="kanan">:</td>
            <?php $bln_ayah = date('mm', strtotime($input['tanggal_lahir_ayah'])); ?>
            <?php for ($i = 0; $i < 2; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($bln_ayah[$i])): ?>
                        <?= $bln_ayah[$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
            <td>Thn</td>
            <td class="kanan">:</td>
            <?php $thn_ayah = date('Y', strtotime($input['tanggal_lahir_ayah'])); ?>
            <?php for ($i = 0; $i < 4; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($thn_ayah[$i])): ?>
                        <?= $thn_ayah[$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td colspan="21">Kewarganegaraan</td>
            <td class="kanan">:</td>
            <?php for ($i = 0; $i < 26; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($input['kewarganegaraan_ayah'][$i])): ?>
                        <?= $input['kewarganegaraan_ayah'][$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td colspan="21">Nama Ibu</td>
            <td class="kanan">:</td>
            <?php for ($i = 0; $i < 26; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($input['nama_ibu'][$i])): ?>
                        <?= $input['nama_ibu'][$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td colspan="21">NIK Ibu</td>
            <td class="kanan">:</td>
            <?php for ($i = 0; $i < 16; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($input['nik_ibu'][$i])): ?>
                        <?= $input['nik_ibu'][$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td colspan="21">Tempat Lahir Ibu</td>
            <td class="kanan">:</td>
            <?php for ($i = 0; $i < 26; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($input['tempat_lahir_ibu'][$i])): ?>
                        <?= $input['tempat_lahir_ibu'][$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td colspan="21">Tanggal Lahir Ibu</td>
            <td class="kanan">:</td>
            <td>Tgl</td>
            <td class="kanan">:</td>
            <?php $tgl_ibu = date('dd', strtotime($input['tanggal_lahir_ibu'])); ?>
            <?php for ($i = 0; $i < 2; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($tgl_ibu[$i])): ?>
                        <?= $tgl_ibu[$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
            <td>Bln</td>
            <td class="kanan">:</td>
            <?php $bln_ibu = date('mm', strtotime($input['tanggal_lahir_ibu'])); ?>
            <?php for ($i = 0; $i < 2; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($bln_ibu[$i])): ?>
                        <?= $bln_ibu[$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
            <td>Thn</td>
            <td class="kanan">:</td>
            <?php $thn_ibu = date('Y', strtotime($input['tanggal_lahir_ibu'])); ?>
            <?php for ($i = 0; $i < 4; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($thn_ibu[$i])): ?>
                        <?= $thn_ibu[$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td colspan="21">Kewarganegaraan</td>
            <td class="kanan">:</td>
            <?php for ($i = 0; $i < 26; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($input['kewarganegaraan_ibu'][$i])): ?>
                        <?= $input['kewarganegaraan_ibu'][$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
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
            <?php for ($i = 0; $i < 26; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($input['nama_bayi'][$i])): ?>
                        <?= $input['nama_bayi'][$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td>2.</td>
            <td colspan="20">Jenis kelamin</td>
            <td class="kanan">:</td>
            <td class="kotak padat tengah"><?= jecho($input['sex'], 1, 'v') ?></td>
            <td colspan="6">1. Laki-laki </td>
            <td class="kotak padat tengah"><?= jecho($input['sex'], 2, 'v') ?></td>
            <td colspan="7">2. Perempuan </td>
        </tr>
        <tr>
            <td>3.</td>
            <td colspan="20">Tempat dilahirkan</td>
            <td class="kanan">:</td>
            <td class="kotak padat tengah"><?= jecho($input['tempat_dilahirkan'], 1, 'v') ?></td>
            <td colspan="4">1. RS/SB</td>
            <td class="kotak padat tengah"><?= jecho($input['tempat_dilahirkan'], 2, 'v') ?></td>
            <td colspan="5">2. Puskesmas </td>
            <td class="kotak padat tengah"><?= jecho($input['tempat_dilahirkan'], 3, 'v') ?></td>
            <td colspan="4">3. Polindes</td>
            <td class="kotak padat tengah"><?= jecho($input['tempat_dilahirkan'], 4, 'v') ?></td>
            <td colspan="4">4. Rumah </td>
            <td class="kotak padat tengah"><?= jecho($input['tempat_dilahirkan'], 5, 'v') ?></td>
            <td colspan="4">5. Lainnya &nbsp; </td>
        </tr>
        <tr>
            <td>4.</td>
            <td colspan="20">Tempat kelahiran </td>
            <td class="kanan">:</td>
            <?php for ($i = 0; $i < 26; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($input['tempatlahir'][$i])): ?>
                        <?= $input['tempatlahir'][$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td>5.</td>
            <td colspan="20">Hari dan Tanggal Lahir </td>
            <td class="kanan">:</td>
            <td>Hari</td>
            <td class="kanan">:</td>
            <?php for ($j = 0; $j < 6; $j++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($input['hari'][$j])): ?>
                        <?= strtoupper($input['hari'][$j]); ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
            <td colspan="4">&nbsp;</td>
            <td>Tgl</td>
            <td class="kanan">:</td>
            <?php $tgl = date('dd', strtotime($input['tanggallahir'])); ?>
            <?php for ($j = 0; $j < 2; $j++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($tgl[$j])): ?>
                        <?= $tgl[$j]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
            <td>Bln</td>
            <td class="kanan">:</td>
            <?php $bln = date('mm', strtotime($input['tanggallahir'])); ?>
            <?php for ($j = 0; $j < 2; $j++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($bln[$j])): ?>
                        <?= $bln[$j]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
            <td>Thn</td>
            <td class="kanan">:</td>
            <?php $thn = date('Y', strtotime($input['tanggallahir'])); ?>
            <?php for ($j = 0; $j < 4; $j++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($thn[$j])): ?>
                        <?= $thn[$j]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td>6.</td>
            <td colspan="20">Pukul </td>
            <td class="kanan">:</td>
            <?php for ($i = 0; $i < 5; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($input['waktu_lahir'][$i])): ?>
                        <?= $input['waktu_lahir'][$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td>7.</td>
            <td colspan="20">Jenis kelahiran </td>
            <td class="kanan">:</td>
            <td class="kotak padat tengah"><?= jecho($input['jenis_kelahiran'], 1, 'v') ?></td>
            <td colspan="4">1. Tunggal</td>
            <td class="kotak padat tengah"><?= jecho($input['jenis_kelahiran'], 2, 'v') ?></td>
            <td colspan="4">2. Kembar 2 </td>
            <td class="kotak padat tengah"><?= jecho($input['jenis_kelahiran'], 3, 'v') ?></td>
            <td colspan="5">3. Kembar 3</td>
            <td class="kotak padat tengah"><?= jecho($input['jenis_kelahiran'], 4, 'v') ?></td>
            <td colspan="4">4. Kembar 4 </td>
            <td class="kotak padat tengah"><?= jecho($input['jenis_kelahiran'], 5, 'v') ?></td>
            <td colspan="4">5. Lainnya &nbsp; </td>
        </tr>
        <tr>
            <td>8.</td>
            <td colspan="20">Kelahiran anak ke- </td>
            <td class="kanan">:</td>
            <?php for ($i = 0; $i < 1; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($input['kelahiran_anak_ke'][$i])): ?>
                        <?= $input['kelahiran_anak_ke'][$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td>9.</td>
            <td colspan="20">Penolong kelahiran</td>
            <td class="kanan">:</td>
            <td class="kotak padat tengah"><?= jecho($input['penolong_kelahiran'], 1, 'v') ?></td>
            <td colspan="4">1. Dokter</td>
            <td class="kotak padat tengah"><?= jecho($input['penolong_kelahiran'], 2, 'v') ?></td>
            <td colspan="6">2. Bidan/Perawat</td>
            <td class="kotak padat tengah"><?= jecho($input['penolong_kelahiran'], 3, 'v') ?></td>
            <td colspan="4">3. Dukun</td>
            <td class="kotak padat tengah"><?= jecho($input['penolong_kelahiran'], 4, 'v') ?></td>
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
            <?php for ($i = 0; $i < 26; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($input['lama_lahir_mati'][$i])): ?>
                        <?= $input['lama_lahir_mati'][$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td>2.</td>
            <td colspan="20">Jenis kelamin</td>
            <td class="kanan">:</td>
            <td class="kotak padat tengah"><?= jecho($input['sex'], 1, 'v') ?></td>
            <td colspan="6">1. Laki-laki </td>
            <td class="kotak padat tengah"><?= jecho($input['sex'], 2, 'v') ?></td>
            <td colspan="7">2. Perempuan </td>
            <td colspan="11">&nbsp;</td>
        </tr>
        <tr>
            <td>3.</td>
            <td colspan="20">Tanggal lahir mati</td>
            <td class="kanan">:</td>
            <td>Tgl</td>
            <td class="kanan">:</td>
            <?php $tgl_lahir_mati = date('dd', strtotime($input['tanggal_lahir_lahir_mati'])); ?>
            <?php for ($i = 0; $i < 2; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($tgl_lahir_mati[$i])): ?>
                        <?= $tgl_lahir_mati[$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
            <td>Bln</td>
            <td class="kanan">:</td>
            <?php $bln_lahir_mati = date('mm', strtotime($input['tanggal_lahir_lahir_mati'])); ?>
            <?php for ($i = 0; $i < 2; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($bln_lahir_mati[$i])): ?>
                        <?= $bln_lahir_mati[$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
            <td>Thn</td>
            <td class="kanan">:</td>
            <?php $thn_lahir_mati = date('Y', strtotime($input['tanggal_lahir_lahir_mati'])); ?>
            <?php for ($i = 0; $i < 4; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($thn_lahir_mati[$i])): ?>
                        <?= $thn_lahir_mati[$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td>4.</td>
            <td colspan="20">Jenis kelahiran </td>
            <td class="kanan">:</td>
            <td class="kotak padat tengah"><?= jecho($input['jenis_kelahiran_lahir_mati'], 1, 'v') ?></td>
            <td colspan="4">1. Tunggal</td>
            <td class="kotak padat tengah"><?= jecho($input['jenis_kelahiran_lahir_mati'], 2, 'v') ?></td>
            <td colspan="4">2. Kembar 2 </td>
            <td class="kotak padat tengah"><?= jecho($input['jenis_kelahiran_lahir_mati'], 3, 'v') ?></td>
            <td colspan="5">3. Kembar 3</td>
            <td class="kotak padat tengah"><?= jecho($input['jenis_kelahiran_lahir_mati'], 4, 'v') ?></td>
            <td colspan="4">4. Kembar 4 </td>
            <td class="kotak padat tengah"><?= jecho($input['jenis_kelahiran_lahir_mati'], 5, 'v') ?></td>
            <td colspan="4">5. Lainnya &nbsp; </td>
        </tr>
        <tr>
            <td>5.</td>
            <td colspan="20">Anak ke- </td>
            <td class="kanan">:</td>
            <?php for ($i = 0; $i < 1; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($input['anak_ke_lahir_mati'][$i])): ?>
                        <?= $input['anak_ke_lahir_mati'][$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td>6.</td>
            <td colspan="20">Tempat dilahirkan </td>
            <td class="kanan">:</td>
            <td class="kotak padat tengah"><?= jecho($input['tempat_dilahirkan_lahir_mati'], 1, 'v') ?></td>
            <td colspan="4">1. RS/SB</td>
            <td class="kotak padat tengah"><?= jecho($input['tempat_dilahirkan_lahir_mati'], 2, 'v') ?></td>
            <td colspan="5">2. Puskesmas </td>
            <td class="kotak padat tengah"><?= jecho($input['tempat_dilahirkan_lahir_mati'], 3, 'v') ?></td>
            <td colspan="4">3. Polindes</td>
            <td class="kotak padat tengah"><?= jecho($input['tempat_dilahirkan_lahir_mati'], 4, 'v') ?></td>
            <td colspan="4">4. Rumah </td>
            <td class="kotak padat tengah"><?= jecho($input['tempat_dilahirkan_lahir_mati'], 5, 'v') ?></td>
            <td colspan="4">5. Lainnya &nbsp; </td>
        </tr>
        <tr>
            <td>7.</td>
            <td colspan="20">Penolong kelahiran </td>
            <td class="kanan">:</td>
            <td class="kotak padat tengah"><?= jecho($input['penolong_kelahiran_lahir_mati'], 1, 'v') ?></td>
            <td colspan="4">1. Dokter</td>
            <td class="kotak padat tengah"><?= jecho($input['penolong_kelahiran_lahir_mati'], 2, 'v') ?></td>
            <td colspan="6">2. Bidan/Perawat</td>
            <td class="kotak padat tengah"><?= jecho($input['penolong_kelahiran_lahir_mati'], 3, 'v') ?></td>
            <td colspan="4">3. Dukun</td>
            <td class="kotak padat tengah"><?= jecho($input['penolong_kelahiran_lahir_mati'], 4, 'v') ?></td>
            <td colspan="4">4. Lainnya</td>
        </tr>
        <tr>
            <td>8.</td>
            <td colspan="20">Sebab lahir mati</td>
            <td class="kanan">:</td>
            <?php for ($i = 0; $i < 26; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($input['anak_ke_lahir_mati'][$i])): ?>
                        <?= $input['anak_ke_lahir_mati'][$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td>9.</td>
            <td colspan="20">Yang menentukan</td>
            <td class="kanan">:</td>
            <td class="kotak padat tengah"><?= jecho($input['penentu_kelahiran_lahir_mati'], 1, 'v') ?></td>
            <td colspan="4">1. Dokter</td>
            <td class="kotak padat tengah"><?= jecho($input['penentu_kelahiran_lahir_mati'], 2, 'v') ?></td>
            <td colspan="6">2. Bidan/Perawat</td>
            <td class="kotak padat tengah"><?= jecho($input['penentu_kelahiran_lahir_mati'], 3, 'v') ?></td>
            <td colspan="4">3. Tenaga Kes</td>
            <td class="kotak padat tengah"><?= jecho($input['penentu_kelahiran_lahir_mati'], 4, 'v') ?></td>
            <td colspan="4">4. Kepolisian</td>
            <td class="kotak padat tengah"><?= jecho($input['penentu_kelahiran_lahir_mati'], 5, 'v') ?></td>
            <td colspan="4">5. Lainnya</td>
        </tr>
        <tr>
            <td>10.</td>
            <td colspan="20">Tempat kelahiran</td>
            <td class="kanan">:</td>
            <?php for ($i = 0; $i < 26; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($input['tempat_kelahiran'][$i])): ?>
                        <?= $input['tempat_kelahiran'][$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
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
            <?php for ($i = 0; $i < 16; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($input['lama_lahir_mati'][$i])): ?>
                        <?= $input['lama_lahir_mati'][$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td>2.</td>
            <td colspan="20">Nama Ayah dari Suami</td>
            <td class="kanan">:</td>
            <?php for ($i = 0; $i < 26; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($input['lama_lahir_mati'][$i])): ?>
                        <?= $input['lama_lahir_mati'][$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td>3.</td>
            <td colspan="20">NIK Ibu dari Suami</td>
            <td class="kanan">:</td>
            <?php for ($i = 0; $i < 16; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($input['lama_lahir_mati'][$i])): ?>
                        <?= $input['lama_lahir_mati'][$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td>4.</td>
            <td colspan="20">Nama Ibu dari Suami</td>
            <td class="kanan">:</td>
            <?php for ($i = 0; $i < 26; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($input['lama_lahir_mati'][$i])): ?>
                        <?= $input['lama_lahir_mati'][$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td>5.</td>
            <td colspan="20">NIK Ayah dari Istri</td>
            <td class="kanan">:</td>
            <?php for ($i = 0; $i < 16; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($input['lama_lahir_mati'][$i])): ?>
                        <?= $input['lama_lahir_mati'][$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td>6.</td>
            <td colspan="20">Nama Ayah dari Istri</td>
            <td class="kanan">:</td>
            <?php for ($i = 0; $i < 26; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($input['lama_lahir_mati'][$i])): ?>
                        <?= $input['lama_lahir_mati'][$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td>7.</td>
            <td colspan="20">NIK Ibu dari Istri</td>
            <td class="kanan">:</td>
            <?php for ($i = 0; $i < 16; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($input['lama_lahir_mati'][$i])): ?>
                        <?= $input['lama_lahir_mati'][$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td>8.</td>
            <td colspan="20">Nama Ibu dari Istri</td>
            <td class="kanan">:</td>
            <?php for ($i = 0; $i < 26; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($input['lama_lahir_mati'][$i])): ?>
                        <?= $input['lama_lahir_mati'][$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td>9.</td>
            <td colspan="20">Status Perkawinan Sebelum Kawin</td>
            <td class="kanan">:</td>
            <td class="kotak padat tengah"><?= jecho($input['perkawinan_status_kawin'], 1, 'v') ?></td>
            <td colspan="4">1. Kawin </td>
            <td class="kotak padat tengah"><?= jecho($input['perkawinan_status_kawin'], 2, 'v') ?></td>
            <td colspan="5">2. Belum Kawin </td>
            <td class="kotak padat tengah"><?= jecho($input['perkawinan_status_kawin'], 3, 'v') ?></td>
            <td colspan="5">3. Cerai Hidup </td>
            <td class="kotak padat tengah"><?= jecho($input['perkawinan_status_kawin'], 4, 'v') ?></td>
            <td colspan="5">4. Cerai Mati </td>
        </tr>
        <tr>
            <td>10.</td>
            <td colspan="20">Perkawinan yang ke-</td>
            <td class="kanan">:</td>
            <?php for ($i = 0; $i < 1; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($input['perkawinan_anak_ke'][$i])): ?>
                        <?= $input['perkawinan_anak_ke'][$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td>11.</td>
            <td colspan="20">Istri yang ke-</td>
            <td class="kanan">:</td>
            <?php for ($i = 0; $i < 1; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($input['perkawinan_anak_ke'][$i])): ?>
                        <?= $input['perkawinan_anak_ke'][$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
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
            <?php $tgl_perkawinan = date('dd', strtotime($input['tanggal_pemberkatan_perkawinan'])); ?>
            <?php for ($i = 0; $i < 2; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($tgl_perkawinan[$i])): ?>
                        <?= $tgl_perkawinan[$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
            <td>Bln</td>
            <td class="kanan">:</td>
            <?php $bln_perkawinan = date('mm', strtotime($input['tanggal_pemberkatan_perkawinan'])); ?>
            <?php for ($i = 0; $i < 2; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($bln_perkawinan[$i])): ?>
                        <?= $bln_perkawinan[$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
            <td>Thn</td>
            <td class="kanan">:</td>
            <?php $thn_perkawinan = date('Y', strtotime($input['tanggal_pemberkatan_perkawinan'])); ?>
            <?php for ($i = 0; $i < 4; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($thn_perkawinan[$i])): ?>
                        <?= $thn_perkawinan[$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
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
            <?php $tgl_lapor_perkawinan = date('dd', strtotime($input['tanggal_lapor_perkawinan'])); ?>
            <?php for ($i = 0; $i < 2; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($tgl_lapor_perkawinan[$i])): ?>
                        <?= $tgl_lapor_perkawinan[$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
            <td>Bln</td>
            <td class="kanan">:</td>
            <?php $bln_lapor_perkawinan = date('mm', strtotime($input['tanggal_lapor_perkawinan'])); ?>
            <?php for ($i = 0; $i < 2; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($bln_lapor_perkawinan[$i])): ?>
                        <?= $bln_lapor_perkawinan[$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
            <td>Thn</td>
            <td class="kanan">:</td>
            <?php $bln_lapor_perkawinan_perkawinan = date('Y', strtotime($input['tanggal_lahir_perkawinan'])); ?>
            <?php for ($i = 0; $i < 4; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($bln_lapor_perkawinan_perkawinan[$i])): ?>
                        <?= $bln_lapor_perkawinan_perkawinan[$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td>14.</td>
            <td colspan="20">Jam Pelaporan</td>
            <td class="kanan">:</td>
            <?php for ($i = 0; $i < 5; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($input['waktu_perkwinan'][$i])): ?>
                        <?= $input['waktu_perkwinan'][$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td>15.</td>
            <td colspan="20">Agama</td>
            <td class="kanan">:</td>
            <td class="kotak padat tengah"><?= jecho($input['agama_perkawinan'], 1, 'v') ?></td>
            <td colspan="3">1. Islam</td>
            <td class="kotak padat tengah"><?= jecho($input['agama_perkawinan'], 2, 'v') ?></td>
            <td colspan="3">2. Kristen</td>
            <td class="kotak padat tengah"><?= jecho($input['agama_perkawinan'], 3, 'v') ?></td>
            <td colspan="3">3. Katolik</td>
            <td class="kotak padat tengah"><?= jecho($input['agama_perkawinan'], 4, 'v') ?></td>
            <td colspan="3">4. Hindu</td>
            <td class="kotak padat tengah"><?= jecho($input['agama_perkawinan'], 5, 'v') ?></td>
            <td colspan="3">5. Budha</td>
            <td class="kotak padat tengah"><?= jecho($input['agama_perkawinan'], 6, 'v') ?></td>
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
            <?php for ($i = 0; $i < 26; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($input['anak_ke_lahir_mati'][$i])): ?>
                        <?= $input['anak_ke_lahir_mati'][$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td colspan="47">Kepercayaan</td>
        </tr>
        <tr>
            <td>18.</td>
            <td colspan="20">Nama Pengadilan </td>
            <td class="kanan">:</td>
            <?php for ($i = 0; $i < 22; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($input['anak_ke_lahir_mati'][$i])): ?>
                        <?= $input['anak_ke_lahir_mati'][$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td>19.</td>
            <td colspan="20">Nomor Penetapan</td>
            <td class="kanan">:</td>
            <?php for ($i = 0; $i < 22; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($input['anak_ke_lahir_mati'][$i])): ?>
                        <?= $input['anak_ke_lahir_mati'][$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
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
            <?php $tgl_pengadilan = date('dd', strtotime($input['tanggal_penetapan_pengadilan'])); ?>
            <?php for ($i = 0; $i < 2; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($tgl_pengadilan[$i])): ?>
                        <?= $tgl_pengadilan[$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
            <td>Bln</td>
            <td class="kanan">:</td>
            <?php $bln_pengadilan = date('mm', strtotime($input['tanggal_penetapan_pengadilan'])); ?>
            <?php for ($i = 0; $i < 2; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($bln_pengadilan[$i])): ?>
                        <?= $bln_pengadilan[$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
            <td>Thn</td>
            <td class="kanan">:</td>
            <?php $thn_pengadilan = date('Y', strtotime($input['tanggal_penetapan_pengadilan'])); ?>
            <?php for ($i = 0; $i < 4; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($thn_pengadilan[$i])): ?>
                        <?= $thn_pengadilan[$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td colspan="47">Pengadilan</td>
        </tr>
        <tr>
            <td>21.</td>
            <td colspan="20">Nama Pemuka Agama/</td>
            <td class="kanan">:</td>
            <?php for ($i = 0; $i < 26; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($input['anak_ke_lahir_mati'][$i])): ?>
                        <?= $input['anak_ke_lahir_mati'][$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td colspan="47">Kepercayaan</td>
        </tr>
        <tr>
            <td>22.</td>
            <td colspan="20">Nomor Surat Izin</td>
            <td class="kanan">:</td>
            <?php for ($i = 0; $i < 16; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($input['anak_ke_lahir_mati'][$i])): ?>
                        <?= $input['anak_ke_lahir_mati'][$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td colspan="47">dari Perwakilan</td>
        </tr>
        <tr>
            <td>23.</td>
            <td colspan="20">Nomor Pasport</td>
            <td class="kanan">:</td>
            <?php for ($i = 0; $i < 16; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($input['anak_ke_lahir_mati'][$i])): ?>
                        <?= $input['anak_ke_lahir_mati'][$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td>24.</td>
            <td colspan="20">Perjanjian Perkawinan</td>
            <td class="kanan">:</td>
            <?php for ($i = 0; $i < 16; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($input['anak_ke_lahir_mati'][$i])): ?>
                        <?= $input['anak_ke_lahir_mati'][$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td colspan="47">dibuat oleh Notaris</td>
        </tr>
        <tr>
            <td>25.</td>
            <td colspan="20">Nomor Akta Notaris</td>
            <td class="kanan">:</td>
            <?php for ($i = 0; $i < 16; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($input['anak_ke_lahir_mati'][$i])): ?>
                        <?= $input['anak_ke_lahir_mati'][$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td>26.</td>
            <td colspan="20">Tanggal Akta Notaris</td>
            <td class="kanan">:</td>
            <?php for ($i = 0; $i < 16; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($input['anak_ke_lahir_mati'][$i])): ?>
                        <?= $input['anak_ke_lahir_mati'][$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td>27.</td>
            <td colspan="20">Jumlah Anak (jika ada</td>
            <td class="kanan">:</td>
            <?php for ($i = 0; $i < 16; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($input['anak_ke_lahir_mati'][$i])): ?>
                        <?= $input['anak_ke_lahir_mati'][$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
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
            <?php $tgl_perkawinan = date('dd', strtotime($input['tanggal_perkawinan'])); ?>
            <?php for ($i = 0; $i < 2; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($tgl_perkawinan[$i])): ?>
                        <?= $tgl_perkawinan[$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
            <td>Bln</td>
            <td class="kanan">:</td>
            <?php $bln_perkawinan = date('mm', strtotime($input['tanggal_perkawinan'])); ?>
            <?php for ($i = 0; $i < 2; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($bln_perkawinan[$i])): ?>
                        <?= $bln_perkawinan[$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
            <td>Thn</td>
            <td class="kanan">:</td>
            <?php $thn_perkawinan = date('Y', strtotime($input['tanggal_perkawinan'])); ?>
            <?php for ($i = 0; $i < 4; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($thn_perkawinan[$i])): ?>
                        <?= $thn_perkawinan[$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td>2.</td>
            <td colspan="20">Nomor Akta Perkawinan</td>
            <td class="kanan">:</td>
            <?php for ($i = 0; $i < 16; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($input['anak_ke_lahir_mati'][$i])): ?>
                        <?= $input['anak_ke_lahir_mati'][$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td>3.</td>
            <td colspan="20">Tanggal Akta Perkawinan</td>
            <td class="kanan">:</td>
            <td>Tgl</td>
            <td class="kanan">:</td>
            <?php $tgl_akta_perkawinan = date('dd', strtotime($input['tanggal_akta_perkawinan'])); ?>
            <?php for ($i = 0; $i < 2; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($tgl_akta_perkawinan[$i])): ?>
                        <?= $tgl_akta_perkawinan[$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
            <td>Bln</td>
            <td class="kanan">:</td>
            <?php $bln_akta_perkawinan = date('mm', strtotime($input['tanggal_akta_perkawinan'])); ?>
            <?php for ($i = 0; $i < 2; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($bln_akta_perkawinan[$i])): ?>
                        <?= $bln_akta_perkawinan[$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
            <td>Thn</td>
            <td class="kanan">:</td>
            <?php $thn_akta_perkawinan = date('Y', strtotime($input['tanggal_akta_perkawinan'])); ?>
            <?php for ($i = 0; $i < 4; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($thn_akta_perkawinan[$i])): ?>
                        <?= $thn_akta_perkawinan[$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td>4.</td>
            <td colspan="20">Nama Pengadilan</td>
            <td class="kanan">:</td>
            <?php for ($i = 0; $i < 16; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($input['nama_pengadilan'][$i])): ?>
                        <?= $input['nama_pengadilan'][$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td>5.</td>
            <td colspan="20">Nomor Putusan Pengadilan</td>
            <td class="kanan">:</td>
            <?php for ($i = 0; $i < 16; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($input['nomor_putusan_pengadilan'][$i])): ?>
                        <?= $input['nomor_putusan_pengadilan'][$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td>6.</td>
            <td colspan="20">Tanggal Putusan Pengadilan</td>
            <td class="kanan">:</td>
            <td>Tgl</td>
            <td class="kanan">:</td>
            <?php $tgl_putusan_pengadilan = date('dd', strtotime($input['tanggal_putusan_pengadilan'])); ?>
            <?php for ($i = 0; $i < 2; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($tgl_putusan_pengadilan[$i])): ?>
                        <?= $tgl_putusan_pengadilan[$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
            <td>Bln</td>
            <td class="kanan">:</td>
            <?php $bln_putusan_pengadilan = date('mm', strtotime($input['tanggal_putusan_pengadilan'])); ?>
            <?php for ($i = 0; $i < 2; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($bln_putusan_pengadilan[$i])): ?>
                        <?= $bln_putusan_pengadilan[$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
            <td>Thn</td>
            <td class="kanan">:</td>
            <?php $thn_putusan_pengadilan = date('Y', strtotime($input['tanggal_putusan_pengadilan'])); ?>
            <?php for ($i = 0; $i < 4; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($thn_putusan_pengadilan[$i])): ?>
                        <?= $thn_putusan_pengadilan[$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td>7.</td>
            <td colspan="20">Tanggal Pelaporan Perkawinan</td>
            <td class="kanan">:</td>
            <td>Tgl</td>
            <td class="kanan">:</td>
            <?php $tgl_pelaporan_perkawinan_luar_negeri = date('dd', strtotime($input['tanggal_pelaporan_perkawinan_luar_negeri'])); ?>
            <?php for ($i = 0; $i < 2; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($tgl_pelaporan_perkawinan_luar_negeri[$i])): ?>
                        <?= $tgl_pelaporan_perkawinan_luar_negeri[$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
            <td>Bln</td>
            <td class="kanan">:</td>
            <?php $bln_pelaporan_perkawinan_luar_negeri = date('mm', strtotime($input['tanggal_pelaporan_perkawinan_luar_negeri'])); ?>
            <?php for ($i = 0; $i < 2; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($bln_pelaporan_perkawinan_luar_negeri[$i])): ?>
                        <?= $bln_pelaporan_perkawinan_luar_negeri[$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
            <td>Thn</td>
            <td class="kanan">:</td>
            <?php $thn_pelaporan_perkawinan_luar_negeri = date('Y', strtotime($input['tanggal_pelaporan_perkawinan_luar_negeri'])); ?>
            <?php for ($i = 0; $i < 4; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($thn_pelaporan_perkawinan_luar_negeri[$i])): ?>
                        <?= $thn_pelaporan_perkawinan_luar_negeri[$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
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
            <?php for ($i = 0; $i < 16; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($input['anak_ke_lahir_mati'][$i])): ?>
                        <?= $input['anak_ke_lahir_mati'][$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td>2.</td>
            <td colspan="20">Tanggal akta Perkawinan</td>
            <td class="kanan">:</td>
            <td>Tgl</td>
            <td class="kanan">:</td>
            <?php $tgl_perkawinan = date('dd', strtotime($input['tanggal_perkawinan'])); ?>
            <?php for ($i = 0; $i < 2; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($tgl_perkawinan[$i])): ?>
                        <?= $tgl_perkawinan[$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
            <td>Bln</td>
            <td class="kanan">:</td>
            <?php $bln_perkawinan = date('mm', strtotime($input['tanggal_perkawinan'])); ?>
            <?php for ($i = 0; $i < 2; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($bln_perkawinan[$i])): ?>
                        <?= $bln_perkawinan[$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
            <td>Thn</td>
            <td class="kanan">:</td>
            <?php $thn_perkawinan = date('Y', strtotime($input['tanggal_perkawinan'])); ?>
            <?php for ($i = 0; $i < 4; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($thn_perkawinan[$i])): ?>
                        <?= $thn_perkawinan[$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td>2.</td>
            <td colspan="20">Nomor Akta Perkawinan</td>
            <td class="kanan">:</td>
            <?php for ($i = 0; $i < 16; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($input['anak_ke_lahir_mati'][$i])): ?>
                        <?= $input['anak_ke_lahir_mati'][$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td>3.</td>
            <td colspan="20">Tanggal Akta Perkawinan</td>
            <td class="kanan">:</td>
            <td>Tgl</td>
            <td class="kanan">:</td>
            <?php $tgl_akta_perkawinan = date('dd', strtotime($input['tanggal_akta_perkawinan'])); ?>
            <?php for ($i = 0; $i < 2; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($tgl_akta_perkawinan[$i])): ?>
                        <?= $tgl_akta_perkawinan[$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
            <td>Bln</td>
            <td class="kanan">:</td>
            <?php $bln_akta_perkawinan = date('mm', strtotime($input['tanggal_akta_perkawinan'])); ?>
            <?php for ($i = 0; $i < 2; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($bln_akta_perkawinan[$i])): ?>
                        <?= $bln_akta_perkawinan[$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
            <td>Thn</td>
            <td class="kanan">:</td>
            <?php $thn_akta_perkawinan = date('Y', strtotime($input['tanggal_akta_perkawinan'])); ?>
            <?php for ($i = 0; $i < 4; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($thn_akta_perkawinan[$i])): ?>
                        <?= $thn_akta_perkawinan[$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td>4.</td>
            <td colspan="20">Nama Pengadilan</td>
            <td class="kanan">:</td>
            <?php for ($i = 0; $i < 16; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($input['nama_pengadilan'][$i])): ?>
                        <?= $input['nama_pengadilan'][$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td>5.</td>
            <td colspan="20">Tanggal Putusan Pengadilan</td>
            <td class="kanan">:</td>
            <td>Tgl</td>
            <td class="kanan">:</td>
            <?php $tgl_putusan_pengadilan = date('dd', strtotime($input['tanggal_putusan_pengadilan'])); ?>
            <?php for ($i = 0; $i < 2; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($tgl_putusan_pengadilan[$i])): ?>
                        <?= $tgl_putusan_pengadilan[$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
            <td>Bln</td>
            <td class="kanan">:</td>
            <?php $bln_putusan_pengadilan = date('mm', strtotime($input['tanggal_putusan_pengadilan'])); ?>
            <?php for ($i = 0; $i < 2; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($bln_putusan_pengadilan[$i])): ?>
                        <?= $bln_putusan_pengadilan[$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
            <td>Thn</td>
            <td class="kanan">:</td>
            <?php $thn_putusan_pengadilan = date('Y', strtotime($input['tanggal_putusan_pengadilan'])); ?>
            <?php for ($i = 0; $i < 4; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($thn_putusan_pengadilan[$i])): ?>
                        <?= $thn_putusan_pengadilan[$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td>6.</td>
            <td colspan="20">Nomor Putusan Pengadilan</td>
            <td class="kanan">:</td>
            <?php for ($i = 0; $i < 23; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($input['nomor_putusan_pengadilan'][$i])): ?>
                        <?= $input['nomor_putusan_pengadilan'][$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td>7.</td>
            <td colspan="20">Nomor Surat Keterangan Panitera Pengadilan</td>
            <td class="kanan">:</td>
            <?php for ($i = 0; $i < 16; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($input['nomor_putusan_pengadilan'][$i])): ?>
                        <?= $input['nomor_putusan_pengadilan'][$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td>8.</td>
            <td colspan="20">Tanggal Surat Keterangan Panitera Pengadilan</td>
            <td class="kanan">:</td>
            <td>Tgl</td>
            <td class="kanan">:</td>
            <?php $tgl_pelaporan_perkawinan_luar_negeri = date('dd', strtotime($input['tanggal_pelaporan_perkawinan_luar_negeri'])); ?>
            <?php for ($i = 0; $i < 2; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($tgl_pelaporan_perkawinan_luar_negeri[$i])): ?>
                        <?= $tgl_pelaporan_perkawinan_luar_negeri[$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
            <td>Bln</td>
            <td class="kanan">:</td>
            <?php $bln_pelaporan_perkawinan_luar_negeri = date('mm', strtotime($input['tanggal_pelaporan_perkawinan_luar_negeri'])); ?>
            <?php for ($i = 0; $i < 2; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($bln_pelaporan_perkawinan_luar_negeri[$i])): ?>
                        <?= $bln_pelaporan_perkawinan_luar_negeri[$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
            <td>Thn</td>
            <td class="kanan">:</td>
            <?php $thn_pelaporan_perkawinan_luar_negeri = date('Y', strtotime($input['tanggal_pelaporan_perkawinan_luar_negeri'])); ?>
            <?php for ($i = 0; $i < 4; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($thn_pelaporan_perkawinan_luar_negeri[$i])): ?>
                        <?= $thn_pelaporan_perkawinan_luar_negeri[$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td>9.</td>
            <td colspan="20">Tanggal Melapor</td>
            <td class="kanan">:</td>
            <td>Tgl</td>
            <td class="kanan">:</td>
            <?php $tgl_pelaporan_perkawinan_luar_negeri = date('dd', strtotime($input['tanggal_melapor'])); ?>
            <?php for ($i = 0; $i < 2; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($tgl_melapor[$i])): ?>
                        <?= $tgl_melapor[$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
            <td>Bln</td>
            <td class="kanan">:</td>
            <?php $bln_melapor = date('mm', strtotime($input['tanggal_melapor'])); ?>
            <?php for ($i = 0; $i < 2; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($bln_melapor[$i])): ?>
                        <?= $bln_melapor[$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
            <td>Thn</td>
            <td class="kanan">:</td>
            <?php $thn_melapor = date('Y', strtotime($input['tanggal_melapor'])); ?>
            <?php for ($i = 0; $i < 4; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($thn_melapor[$i])): ?>
                        <?= $thn_melapor[$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
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
            <?php for ($i = 0; $i < 16; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($input['anak_ke_lahir_mati'][$i])): ?>
                        <?= $input['anak_ke_lahir_mati'][$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td>2.</td>
            <td colspan="20">Tanggal Akta Perceraian</td>
            <td class="kanan">:</td>
            <td>Tgl</td>
            <td class="kanan">:</td>
            <?php $tgl_ayah = date('dd', strtotime($input['tanggal_lahir_ayah'])); ?>
            <?php for ($i = 0; $i < 2; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($tgl_ayah[$i])): ?>
                        <?= $tgl_ayah[$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
            <td>Bln</td>
            <td class="kanan">:</td>
            <?php $bln_ayah = date('mm', strtotime($input['tanggal_lahir_ayah'])); ?>
            <?php for ($i = 0; $i < 2; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($bln_ayah[$i])): ?>
                        <?= $bln_ayah[$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
            <td>Thn</td>
            <td class="kanan">:</td>
            <?php $thn_ayah = date('Y', strtotime($input['tanggal_lahir_ayah'])); ?>
            <?php for ($i = 0; $i < 4; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($thn_ayah[$i])): ?>
                        <?= $thn_ayah[$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
            <td colspan="12">&nbsp;</td>
        </tr>
        <tr>
            <td>3.</td>
            <td colspan="20">Tanggal Pelaporan</td>
            <td class="kanan">:</td>
            <td>Tgl</td>
            <td class="kanan">:</td>
            <?php $tgl_ayah = date('dd', strtotime($input['tanggal_lahir_ayah'])); ?>
            <?php for ($i = 0; $i < 2; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($tgl_ayah[$i])): ?>
                        <?= $tgl_ayah[$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
            <td>Bln</td>
            <td class="kanan">:</td>
            <?php $bln_ayah = date('mm', strtotime($input['tanggal_lahir_ayah'])); ?>
            <?php for ($i = 0; $i < 2; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($bln_ayah[$i])): ?>
                        <?= $bln_ayah[$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
            <td>Thn</td>
            <td class="kanan">:</td>
            <?php $thn_ayah = date('Y', strtotime($input['tanggal_lahir_ayah'])); ?>
            <?php for ($i = 0; $i < 4; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($thn_ayah[$i])): ?>
                        <?= $thn_ayah[$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
            <td colspan="12">&nbsp;</td>
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
            <?php for ($i = 0; $i < 16; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($input['nik'][$i])): ?>
                        <?= $input['nik'][$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td>2.</td>
            <td colspan="20">Nama Lengkap</td>
            <td class="kanan">:</td>
            <?php for ($i = 0; $i < 26; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($input['nama_lengkap'][$i])): ?>
                        <?= $input['nama_lengkap'][$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td>3.</td>
            <td colspan="20">Tanggal kematian</td>
            <td class="kanan">:</td>
            <td>Tgl</td>
            <td class="kanan">:</td>
            <?php $tgl_kematian = date('dd', strtotime($input['tanggal_kematian'])); ?>
            <?php for ($i = 0; $i < 2; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($tgl_kematian[$i])): ?>
                        <?= $tgl_kematian[$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
            <td>Bln</td>
            <td class="kanan">:</td>
            <?php $bln_kematian = date('mm', strtotime($input['tanggal_kematian'])); ?>
            <?php for ($i = 0; $i < 2; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($bln_kematian[$i])): ?>
                        <?= $bln_kematian[$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
            <td>Thn</td>
            <td class="kanan">:</td>
            <?php $thn_kematian = date('Y', strtotime($input['tanggal_kematian'])); ?>
            <?php for ($i = 0; $i < 4; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($thn_kematian[$i])): ?>
                        <?= $thn_kematian[$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td>4.</td>
            <td colspan="20">Pukul </td>
            <td class="kanan">:</td>
            <?php for ($i = 0; $i < 5; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($input['waktu_kematian'][$i])): ?>
                        <?= $input['waktu_kematian'][$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td>5.</td>
            <td colspan="20">Sebab kematian</td>
            <td class="kanan">:</td>
            <td class="kotak padat tengah"><?= jecho($input['sebab_kematian'], 1, 'v') ?></td>
            <td colspan="7">1. Sakit biasa / tua</td>
            <td class="kotak padat tengah"><?= jecho($input['sebab_kematian'], 2, 'v') ?></td>
            <td colspan="7">2. Wabah Penyakit</td>
            <td class="kotak padat tengah"><?= jecho($input['sebab_kematian'], 3, 'v') ?></td>
            <td colspan="7">3. Kecelakaan</td>
        </tr>
        <tr>
            <td colspan="22">&nbsp;</td>
            <td class="kotak padat tengah"><?= jecho($input['sebab_kematian'], 4, 'v') ?></td>
            <td colspan="7">4. Kriminalitas</td>
            <td class="kotak padat tengah"><?= jecho($input['sebab_kematian'], 5, 'v') ?></td>
            <td colspan="7">5. Bunuh Diri</td>
            <td class="kotak padat tengah"><?= jecho($input['sebab_kematian'], 6, 'v') ?></td>
            <td colspan="7">6. Lainnya</td>
        </tr>
        <tr>
            <td>6.</td>
            <td colspan="20">Tempat kematian</td>
            <td class="kanan">:</td>
            <?php for ($i = 0; $i < 16; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($input['tempat_kematian'][$i])): ?>
                        <?= $input['tempat_kematian'][$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td>7.</td>
            <td colspan="20">Yang menerangkan</td>
            <td class="kanan">:</td>
            <td class="kotak padat tengah"><?= jecho($input['menerangkan_kematian'], 1, 'v') ?></td>
            <td colspan="5">1. Dokter</td>
            <td class="kotak padat tengah"><?= jecho($input['menerangkan_kematian'], 2, 'v') ?></td>
            <td colspan="7">2. Tenaga Kesehatan</td>
            <td class="kotak padat tengah"><?= jecho($input['menerangkan_kematian'], 3, 'v') ?></td>
            <td colspan="6">3. Kepolisian</td>
            <td class="kotak padat tengah"><?= jecho($input['menerangkan_kematian'], 4, 'v') ?></td>
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
            <?php for ($i = 0; $i < 26; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($input['nama_anak_angkat'][$i])): ?>
                        <?= $input['nama_anak_angkat'][$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td>2.</td>
            <td colspan="20">Nomor Akta Kelahiran</td>
            <td class="kanan">:</td>
            <?php for ($i = 0; $i < 17; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($input['nama_lengkap'][$i])): ?>
                        <?= $input['nama_lengkap'][$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td>3.</td>
            <td colspan="20">Tanggal/Bulan/Tahun</td>
            <td class="kanan">:</td>
            <td>Tgl</td>
            <td class="kanan">:</td>
            <?php $tgl_kematian = date('dd', strtotime($input['tanggal_kematian'])); ?>
            <?php for ($i = 0; $i < 2; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($tgl_kematian[$i])): ?>
                        <?= $tgl_kematian[$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
            <td>Bln</td>
            <td class="kanan">:</td>
            <?php $bln_kematian = date('mm', strtotime($input['tanggal_kematian'])); ?>
            <?php for ($i = 0; $i < 2; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($bln_kematian[$i])): ?>
                        <?= $bln_kematian[$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
            <td>Thn</td>
            <td class="kanan">:</td>
            <?php $thn_kematian = date('Y', strtotime($input['tanggal_kematian'])); ?>
            <?php for ($i = 0; $i < 4; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($thn_kematian[$i])): ?>
                        <?= $thn_kematian[$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td colspan="47">Penerbitan Akta Kelahiran</td>
        </tr>
        <tr>
            <td>4.</td>
            <td colspan="20">Dinas Kabupaten/Kota yang</td>
            <td class="kanan">:</td>
            <?php for ($i = 0; $i < 26; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($input['anak_ke_lahir_mati'][$i])): ?>
                        <?= $input['anak_ke_lahir_mati'][$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td colspan="47">menerbitkan Akta Kelahiran</td>
        </tr>
        <tr>
            <td>5.</td>
            <td colspan="20">Nama Ibu Kandung</td>
            <td class="kanan">:</td>
            <?php for ($i = 0; $i < 26; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($input['anak_ke_lahir_mati'][$i])): ?>
                        <?= $input['anak_ke_lahir_mati'][$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td>6.</td>
            <td colspan="20">NIK Ibu Kandung</td>
            <td class="kanan">:</td>
            <?php for ($i = 0; $i < 16; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($input['anak_ke_lahir_mati'][$i])): ?>
                        <?= $input['anak_ke_lahir_mati'][$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td>7.</td>
            <td colspan="20">Kewarganegaraan</td>
            <td class="kanan">:</td>
            <?php for ($i = 0; $i < 14; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($input['anak_ke_lahir_mati'][$i])): ?>
                        <?= $input['anak_ke_lahir_mati'][$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td>8.</td>
            <td colspan="20">Nama Ayah</td>
            <td class="kanan">:</td>
            <?php for ($i = 0; $i < 26; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($input['anak_ke_lahir_mati'][$i])): ?>
                        <?= $input['anak_ke_lahir_mati'][$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td>9.</td>
            <td colspan="20">NIK Ayah Kandung</td>
            <td class="kanan">:</td>
            <?php for ($i = 0; $i < 16; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($input['anak_ke_lahir_mati'][$i])): ?>
                        <?= $input['anak_ke_lahir_mati'][$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td>10.</td>
            <td colspan="20">Kewarganegaraan</td>
            <td class="kanan">:</td>
            <?php for ($i = 0; $i < 14; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($input['anak_ke_lahir_mati'][$i])): ?>
                        <?= $input['anak_ke_lahir_mati'][$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td>11.</td>
            <td colspan="20">Nama Ibu Angkat</td>
            <td class="kanan">:</td>
            <?php for ($i = 0; $i < 26; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($input['anak_ke_lahir_mati'][$i])): ?>
                        <?= $input['anak_ke_lahir_mati'][$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td>12.</td>
            <td colspan="20">NIK Ibu Angkat</td>
            <td class="kanan">:</td>
            <?php for ($i = 0; $i < 16; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($input['anak_ke_lahir_mati'][$i])): ?>
                        <?= $input['anak_ke_lahir_mati'][$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td>13.</td>
            <td colspan="20">Nomor Paspor</td>
            <td class="kanan">:</td>
            <?php for ($i = 0; $i < 11; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($input['anak_ke_lahir_mati'][$i])): ?>
                        <?= $input['anak_ke_lahir_mati'][$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td>14.</td>
            <td colspan="20">Nama Ayah Angkat</td>
            <td class="kanan">:</td>
            <?php for ($i = 0; $i < 26; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($input['anak_ke_lahir_mati'][$i])): ?>
                        <?= $input['anak_ke_lahir_mati'][$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td>15.</td>
            <td colspan="20">NIK Ayah Angkat</td>
            <td class="kanan">:</td>
            <?php for ($i = 0; $i < 16; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($input['anak_ke_lahir_mati'][$i])): ?>
                        <?= $input['anak_ke_lahir_mati'][$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td>16.</td>
            <td colspan="20">Nomor Paspor</td>
            <td class="kanan">:</td>
            <?php for ($i = 0; $i < 11; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($input['anak_ke_lahir_mati'][$i])): ?>
                        <?= $input['anak_ke_lahir_mati'][$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td>17.</td>
            <td colspan="20">Nama Pengadilan</td>
            <td class="kanan">:</td>
            <?php for ($i = 0; $i < 18; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($input['anak_ke_lahir_mati'][$i])): ?>
                        <?= $input['anak_ke_lahir_mati'][$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td>18.</td>
            <td colspan="20">Tanggal Penetapan Pengadilan</td>
            <td class="kanan">:</td>
            <td>Tgl</td>
            <td class="kanan">:</td>
            <?php $tgl_penetapan_pengadilan = date('dd', strtotime($input['tanggal_penetapan_pengadilan'])); ?>
            <?php for ($i = 0; $i < 2; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($tgl_penetapan_pengadilan[$i])): ?>
                        <?= $tgl_penetapan_pengadilan[$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
            <td>Bln</td>
            <td class="kanan">:</td>
            <?php $bln_penetapan_pengadilan = date('mm', strtotime($input['tanggal_penetapan_pengadilan'])); ?>
            <?php for ($i = 0; $i < 2; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($bln_penetapan_pengadilan[$i])): ?>
                        <?= $bln_penetapan_pengadilan[$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
            <td>Thn</td>
            <td class="kanan">:</td>
            <?php $thn_penetapan_pengadilan = date('Y', strtotime($input['tanggal_penetapan_pengadilan'])); ?>
            <?php for ($i = 0; $i < 4; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($thn_penetapan_pengadilan[$i])): ?>
                        <?= $thn_penetapan_pengadilan[$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
            <td colspan="12">&nbsp;</td>
        </tr>
        <tr>
            <td>19.</td>
            <td colspan="20">Nomor Penetapan Pengadilan</td>
            <td class="kanan">:</td>
            <?php for ($i = 0; $i < 19; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($input['anak_ke_lahir_mati'][$i])): ?>
                        <?= $input['anak_ke_lahir_mati'][$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td>20.</td>
            <td colspan="20">Nama lembaga Penetapan Pengadilan</td>
            <td class="kanan">:</td>
            <?php for ($i = 0; $i < 16; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($input['anak_ke_lahir_mati'][$i])): ?>
                        <?= $input['anak_ke_lahir_mati'][$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
        </tr>
        <tr>
            <td>21.</td>
            <td colspan="20">Tempat lembaga Penetapan Pengadilan</td>
            <td class="kanan">:</td>
            <?php for ($i = 0; $i < 16; $i++): ?>
                <td class="kotak padat tengah">
                    <?php if (isset($input['anak_ke_lahir_mati'][$i])): ?>
                        <?= $input['anak_ke_lahir_mati'][$i]; ?>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
            <?php endfor; ?>
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
            <td colspan="10" class="tengah">Kepala Desa/Lurah</td>
            <td colspan="24">&nbsp;</td>
            <td colspan="10" class="tengah">&nbsp;</td>
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