<?php defined('BASEPATH') || exit('No direct script access allowed'); ?>

<style type="text/css">
    <?php include FCPATH . '/assets/css/lampiran-surat.css'; ?>
</style>

<page orientation="portrait" format="210x330" style="font-size: 7pt">

    <!-- Awal Halaman 1 -->
    <table id="kode" align="right">
        <tr><td><strong>Kode F-2.29</strong></td></tr>
    </table>
    <table id="kop" class="disdukcapil">
        <col span="48" style="width: 2.0833%;">
        <tr><td colspan="48">&nbsp;</td></tr>
        <tr>
            <td colspan="10">Pemerintah Desa/Kelurahan</td>
            <td>: </td>
            <td colspan="22"><?= $config['nama_desa']; ?></td>
            <td colspan="2">Ket</td>
            <td colspan="4">Lembar 1</td>
            <td colspan="10">Untuk Yang Bersangkutan</td>
        </tr>
        <tr>
            <td colspan="10">Kecamatan</td>
            <td>: </td>
            <td colspan="24"><?= $config['nama_kecamatan']; ?></td>
            <td colspan="4">Lembar 2</td>
            <td colspan="10">Untuk UPTD/Instansi Pelaksana</td>
        </tr>
        <tr>
            <td colspan="10">Kabupaten/Kota</td>
            <td>:</td>
            <td colspan="24"><?= $config['nama_kabupaten']; ?></td>
            <td colspan="4">Lembar 3</td>
            <td colspan="10">Untuk Desa/kelurahan</td>
        </tr>
        <tr>
            <td colspan="11">&nbsp;</td>
            <?php for ($i = 0; $i < 10; $i++): ?>
                <td style="border-bottom: 1px solid black;">&nbsp;</td>
            <?php endfor; ?>
            <td colspan="14">&nbsp;</td>
            <td colspan="4">Lembar 4</td>
            <td colspan="10">Untuk Kecamatan</td>
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
    <p style="text-align: center; margin: 0px; padding: 0 0 5px 0">
        No. [FOrmat_nomor_surat]
    </p>

    <table id="kop" class="disdukcapil">
        <col span="48" style="width: 2.0833%;">
        <tr>
            <td colspan="11">&nbsp;</td>
            <?php for ($i = 0; $i < 37; $i++): ?>
                <td style="border-bottom: 1px solid black;">&nbsp;</td>
            <?php endfor; ?>
            <td colspan="30">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="10">Nama Kepala Keluarga</td>
            <td style="border-right: 1px solid black;">:</td>
            <?= kotak($input['nama_ayah'], 37); ?>
        </tr>
        <tr>
            <td colspan="11">&nbsp;</td>
            <?php for ($i = 0; $i < 16; $i++): ?>
                <td style="border-bottom: 1px solid black;">&nbsp;</td>
            <?php endfor; ?>
            <td colspan="30">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="10">Nomor Kepala Keluarga</td>
            <td style="border-right: 1px solid black;">:</td>
            <?= kotak($individu['no_kk'], 16); ?>
        </tr>
        <tr>
            <?php for ($i = 0; $i < 48; $i++): ?>
                <td>&nbsp;</td>
            <?php endfor; ?>
        </tr>
    </table>
    
    <table class="disdukcapil">
        <col span="48" style="width: 2.0833%;">
        <tr>
            <td colspan="48"><strong>JENAZAH</strong></td>
        </tr>
        <tr>
            <td class="pl-5">1.</td>
            <td colspan="12">NIK</td>
            <td class="kanan">:</td>
            <?= kotak($individu['nik'], 16); ?>
        </tr>
        <tr>
            <td class="pl-5">2.</td>
            <td colspan="12">Nama Lengkap</td>
            <td class="kanan">:</td>
            <td colspan="34" class="kotak"><?= $individu['nama']; ?></td>
        </tr>
        <tr>
            <td class="pl-5">3.</td>
            <td colspan="12">Jenis Kelamin</td>
            <td class="kanan">:</td>
			<?php for ($i = 0; $i < 1; $i++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($individu['sex_id'])): ?>
						<?= $individu['sex_id']; ?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td colspan="5">1. Laki-laki</td>
			<td colspan="5">2. Perempuan</td>
		</tr>
        <tr>
            <td class="pl-5">4.</td>
            <td colspan="12">Tanggal Lahir/Umur</td>
            <td class="kanan">:</td>
            <td>Tgl</td>
            <td class="kanan"></td>
            <?= kotak(! empty($individu['tanggallahir']) ? date('dd', strtotime($individu['tanggallahir'])) : '', 2); ?>
            <td>Bln</td>
            <td class="kanan"></td>
            <?= kotak(! empty($individu['tanggallahir']) ? date('mm', strtotime($individu['tanggallahir'])) : '', 2); ?>
            <td>Thn</td>
            <td class="kanan"></td>
            <?= kotak(! empty($individu['tanggallahir']) ? date('Y', strtotime($individu['tanggallahir'])) : '', 4); ?>
            <td>Umur</td>
            <td class="kanan"></td>
            <?= kotak($individu['umur'], 3); ?>
        </tr>
        <tr>
            <td class="pl-5">5.</td>
            <td colspan="12">Tempat Lahir</td>
            <td class="kanan">:</td>
            <td colspan="12" class="kotak"><?= $individu['tempatlahir']; ?></td>
            <td colspan="4">Kode Prov</td>
            <td class="kanan"></td>
            <?= kotak(! empty($config['kode_propinsi']) ? date('dd', strtotime($config['kode_propinsi'])) : '', 2); ?>
            <td colspan="4">Kode Kab</td>
            <td class="kanan"></td>
            <?= kotak(! empty($config['kode_kabupaten']) ? date('mm', strtotime($config['kode_kabupaten'])) : '', 2); ?>
        </tr>
        <tr>
            <td class="pl-5">6.</td>
            <td colspan="12">Agama</td>
            <td class="kanan">:</td>
			<?php for ($i = 0; $i < 1; $i++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($individu['agama_id'])): ?>
						<?= $individu['agama_id']; ?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td colspan="5">1. Islam</td>
			<td colspan="5">2. Kristen</td>
			<td colspan="5">3. Katolik</td>
			<td colspan="5">4. Hindu</td>
			<td colspan="5">5. Budha</td>
			<td colspan="5">6. Lainnya</td>
		</tr>
        <tr>
            <td class="pl-5">7.</td>
            <td colspan="12">Pekerjaan</td>
            <td class="kanan">:</td>
            <td colspan="10" class="kotak"><?= $individu['pekerjaan']; ?></td>
        </tr>
        <tr>
            <td class="pl-5">8.</td>
            <td colspan="12">Alamat</td>
            <td class="kanan">:</td>
            <td colspan="34" class="kotak"><?= $individu['alamat']; ?></td>
        </tr>
        <tr>
            <td colspan="14">&nbsp;</td>
            <td colspan="6">a. Desa/Kelurahan</td>
            <td class="kanan"></td>
            <td colspan="10" class="kotak"><?= $config['nama_desa']; ?></td>
            <td colspan="6">c. Kab/Kota</td>
            <td class="kanan"></td>
            <td colspan="10" class="kotak"><?= $config['nama_kabupaten']; ?></td>
        </tr>
        <tr>
            <td colspan="14">&nbsp;</td>
            <td colspan="6">b. Kecamatan</td>
            <td class="kanan"></td>
            <td colspan="10" class="kotak"><?= $config['nama_kecamatan']; ?></td>
            <td colspan="6">d. Provinsi</td>
            <td class="kanan"></td>
            <td colspan="10" class="kotak"><?= $config['nama_propinsi']; ?></td>
        </tr>
        <tr>
            <td class="pl-5">9.</td>
            <td colspan="12">Anak Ke</td>
            <td class="kanan">:</td>
			<?php for ($i = 0; $i < 1; $i++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($individu['kelahiran_anak_ke'])): ?>
						<?= $individu['kelahiran_anak_ke']; ?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td colspan="1">1</td>
			<td colspan="1">2</td>
			<td colspan="1">3</td>
			<td colspan="1">4</td>
		</tr>
        <tr>
            <td class="pl-5">10.</td>
            <td colspan="12">Tanggal Kematian</td>
            <td class="kanan">:</td>
            <td>Tgl</td>
            <td class="kanan"></td>
            <?= kotak(! empty($input['tanggal_kematian']) ? date('dd', strtotime($input['tanggal_kematian'])) : '', 2); ?>
            <td>Bln</td>
            <td class="kanan"></td>
            <?= kotak(! empty($input['tanggal_kematian']) ? date('mm', strtotime($input['tanggal_kematian'])) : '', 2); ?>
            <td>Thn</td>
            <td class="kanan"></td>
            <?= kotak(! empty($input['tanggal_kematian']) ? date('Y', strtotime($input['tanggal_kematian'])) : '', 4); ?>
        </tr>
        <tr>
            <td class="pl-5">11.</td>
            <td colspan="12">Pukul </td>
            <td class="kanan">:</td>
            <?= kotak($input['jam_kematian'], 5); ?>
        </tr>
        <tr>
            <td class="pl-5">12.</td>
            <td colspan="12">Sebab kematian</td>
            <td class="kanan">:</td>
			<?php for ($i = 0; $i < 1; $i++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($input['sebab_kematian'])): ?>
						<?= $input['sebab_kematian']; ?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
			<td colspan="7">1. Sakit biasa / tua</td>
			<td colspan="7">2. Wabah Penyakit</td>
			<td colspan="7">3. Kecelakaan</td>
		</tr>
        <tr>
            <td colspan="15">&nbsp;</td>
            <td colspan="7">4. Kriminalitas</td>
            <td colspan="7">5. Bunuh Diri</td>
            <td colspan="7">6. Lainnya</td>
        </tr>
        <tr>
            <td class="pl-5">13.</td>
            <td colspan="12">Tempat kematian</td>
            <td class="kanan">:</td>
            <td colspan="34" class="kotak"><?= $input['tempat_kematian']; ?></td>
        </tr>
        <tr>
            <td class="pl-5">14.</td>
            <td colspan="12">Yang menerangkan</td>
            <td class="kanan">:</td>
            <?php for ($i = 0; $i < 1; $i++): ?>
				<td class="kotak padat tengah">
					<?php if (isset($input['penolong_kematian'])): ?>
						<?= $input['penolong_kematian']; ?>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
				</td>
			<?php endfor; ?>
            <td colspan="4">1. Dokter</td>
            <td colspan="8">2. Bidan/Perawat</td>
            <td colspan="5">3. Dukun</td>
            <td colspan="5">4. Lainnya</td>
        </tr>
    </table>

    <table class="disdukcapil" style="border-top: 0">
        <col span="48" style="width: 2.0833%;">
        <tr>
            <td colspan="48"><strong>AYAH</strong></td>
        </tr>
        <tr>
            <td class="pl-5">1.</td>
            <td colspan="12">NIK</td>
            <td class="kanan">:</td>
            <?= kotak($input['nik_ayah'], 16); ?>
        </tr>
        <tr>
            <td class="pl-5">2.</td>
            <td colspan="12">Nama Lengkap</td>
            <td class="kanan">:</td>
            <td colspan="34" class="kotak"><?= $input['nama_ayah']; ?></td>
        </tr>
        <tr>
            <td class="pl-5">3.</td>
            <td colspan="12">Tanggal Lahir/Umur</td>
            <td class="kanan">:</td>
            <td>Tgl</td>
            <td class="kanan"></td>
            <?= kotak(! empty($input['tanggal_lahir_ayah']) ? date('dd', strtotime($input['tanggal_lahir_ayah'])) : '', 2); ?>
            <td>Bln</td>
            <td class="kanan"></td>
            <?= kotak(! empty($input['tanggal_lahir_ayah']) ? date('mm', strtotime($input['tanggal_lahir_ayah'])) : '', 2); ?>
            <td>Thn</td>
            <td class="kanan"></td>
            <?= kotak(! empty($input['tanggal_lahir_ayah']) ? date('Y', strtotime($input['tanggal_lahir_ayah'])) : '', 4); ?>
            <td>Umur</td>
            <td class="kanan"></td>
            <?= kotak($input['umur_ayah'], 3); ?>
        </tr>
        <tr>
            <td class="pl-5">4.</td>
            <td colspan="12">Pekerjaan</td>
            <td class="kanan">:</td>
            <td colspan="10" class="kotak"><?= $input['pekerjaanayah']; ?></td>
        </tr>
        <tr>
            <td class="pl-5">5.</td>
            <td colspan="12">Alamat</td>
            <td class="kanan">:</td>
            <td colspan="34" class="kotak"><?= $input['alamat_ayah']; ?></td>
        </tr>
        <tr>
            <td colspan="14">&nbsp;</td>
            <td colspan="6">a. Desa/Kelurahan</td>
            <td class="kanan"></td>
            <td colspan="10" class="kotak"><?= $config['nama_desa']; ?></td>
            <td colspan="6">c. Kab/Kota</td>
            <td class="kanan"></td>
            <td colspan="10" class="kotak"><?= $config['nama_kabupaten']; ?></td>
        </tr>
        <tr>
            <td colspan="14">&nbsp;</td>
            <td colspan="6">b. Kecamatan</td>
            <td class="kanan"></td>
            <td colspan="10" class="kotak"><?= $config['nama_kecamatan']; ?></td>
            <td colspan="6">d. Provinsi</td>
            <td class="kanan"></td>
            <td colspan="10" class="kotak"><?= $config['nama_propinsi']; ?></td>
        </tr>
    </table>

    <table class="disdukcapil" style="border-top: 0">
        <col span="48" style="width: 2.0833%;">
        <tr>
            <td colspan="48"><strong>IBU</strong></td>
        </tr>
        <tr>
            <td class="pl-5">1.</td>
            <td colspan="12">NIK</td>
            <td class="kanan">:</td>
            <?= kotak($input['nik_ibu'], 16); ?>
        </tr>
        <tr>
            <td class="pl-5">2.</td>
            <td colspan="12">Nama Lengkap</td>
            <td class="kanan">:</td>
            <td colspan="34" class="kotak"><?= $input['nama_ibu']; ?></td>
        </tr>
        <tr>
            <td class="pl-5">3.</td>
            <td colspan="12">Tanggal Lahir/Umur</td>
            <td class="kanan">:</td>
            <td>Tgl</td>
            <td class="kanan"></td>
            <?= kotak(! empty($input['tanggal_lahir_ibu']) ? date('dd', strtotime($input['tanggal_lahir_ibu'])) : '', 2); ?>
            <td>Bln</td>
            <td class="kanan"></td>
            <?= kotak(! empty($input['tanggal_lahir_ibu']) ? date('mm', strtotime($input['tanggal_lahir_ibu'])) : '', 2); ?>
            <td>Thn</td>
            <td class="kanan"></td>
            <?= kotak(! empty($input['tanggal_lahir_ibu']) ? date('Y', strtotime($input['tanggal_lahir_ibu'])) : '', 4); ?>
            <td>Umur</td>
            <td class="kanan"></td>
            <?= kotak($input['umur_ibu'], 3); ?>
        </tr>
        <tr>
            <td class="pl-5">4.</td>
            <td colspan="12">Pekerjaan</td>
            <td class="kanan">:</td>
            <td colspan="10" class="kotak"><?= $input['pekerjaanibu']; ?></td>
        </tr>
        <tr>
            <td>5.</td>
            <td colspan="12">Alamat</td>
            <td class="kanan">:</td>
            <td colspan="34" class="kotak"><?= $input['alamat_ibu']; ?></td>
        </tr>
        <tr>
            <td colspan="14">&nbsp;</td>
            <td colspan="6">a. Desa/Kelurahan</td>
            <td class="kanan"></td>
            <td colspan="10" class="kotak"><?= $config['nama_desa']; ?></td>
            <td colspan="6">c. Kab/Kota</td>
            <td class="kanan"></td>
            <td colspan="10" class="kotak"><?= $config['nama_kabupaten']; ?></td>
        </tr>
        <tr>
            <td colspan="14">&nbsp;</td>
            <td colspan="6">b. Kecamatan</td>
            <td class="kanan"></td>
            <td colspan="10" class="kotak"><?= $config['nama_kecamatan']; ?></td>
            <td colspan="6">d. Provinsi</td>
            <td class="kanan"></td>
            <td colspan="10" class="kotak"><?= $config['nama_propinsi']; ?></td>
        </tr>
    </table>

    <table class="disdukcapil"  style="border-top: 0">
        <col span="48" style="width: 2.0833%;">
        <tr>
            <td colspan="48"><strong>PELAPOR</strong></td>
        </tr>
        <tr>
            <td class="pl-5">1.</td>
            <td colspan="12">NIK</td>
            <td class="kanan">:</td>
            <?= kotak($input['nik_pelapor'], 16); ?>
        </tr>
        <tr>
            <td class="pl-5">2.</td>
            <td colspan="12">Nama Lengkap</td>
            <td class="kanan">:</td>
            <td colspan="34" class="kotak"><?= $input['nama_pelapor']; ?></td>
        </tr>
        <tr>
            <td class="pl-5">3.</td>
            <td colspan="12">Tanggal Lahir/Umur</td>
            <td class="kanan">:</td>
            <td>Tgl</td>
            <td class="kanan"></td>
            <?= kotak(! empty($input['tanggal_lahir_pelapor']) ? date('dd', strtotime($input['tanggal_lahir_pelapor'])) : '', 2); ?>
            <td>Bln</td>
            <td class="kanan"></td>
            <?= kotak(! empty($input['tanggal_lahir_pelapor']) ? date('mm', strtotime($input['tanggal_lahir_pelapor'])) : '', 2); ?>
            <td>Thn</td>
            <td class="kanan"></td>
            <?= kotak(! empty($input['tanggal_lahir_pelapor']) ? date('Y', strtotime($input['tanggal_lahir_pelapor'])) : '', 4); ?>
            <td>Umur</td>
            <td class="kanan"></td>
            <?= kotak($input['umur_pelapor'], 3); ?>
        </tr>
        <tr>
            <td class="pl-5">4.</td>
            <td colspan="12">Pekerjaan</td>
            <td class="kanan">:</td>
            <td colspan="10" class="kotak"><?= $input['pekerjaanpelapor']; ?></td>
        </tr>
        <tr>
            <td class="pl-5">5.</td>
            <td colspan="12">Alamat</td>
            <td class="kanan">:</td>
            <td colspan="34" class="kotak"><?= $input['alamat_pelapor']; ?></td>
        </tr>
        <tr>
            <td colspan="14">&nbsp;</td>
            <td colspan="6">a. Desa/Kelurahan</td>
            <td class="kanan"></td>
            <td colspan="10" class="kotak"><?= $config['nama_desa']; ?></td>
            <td colspan="6">c. Kab/Kota</td>
            <td class="kanan"></td>
            <td colspan="10" class="kotak"><?= $config['nama_kabupaten']; ?></td>
        </tr>
        <tr>
            <td colspan="14">&nbsp;</td>
            <td colspan="6">b. Kecamatan</td>
            <td class="kanan"></td>
            <td colspan="10" class="kotak"><?= $config['nama_kecamatan']; ?></td>
            <td colspan="6">d. Provinsi</td>
            <td class="kanan"></td>
            <td colspan="10" class="kotak"><?= $config['nama_propinsi']; ?></td>
        </tr>
    </table>

    <table class="disdukcapil"  style="border-top: 0">
        <col span="48" style="width: 2.0833%;">
        <tr>
            <td colspan="48"><strong>SAKSI I</strong></td>
        </tr>
        <tr>
            <td class="pl-5">1.</td>
            <td colspan="12">NIK</td>
            <td class="kanan">:</td>
            <?= kotak($input['nik_saksi1'], 16); ?>
        </tr>
        <tr>
            <td class="pl-5">2.</td>
            <td colspan="12">Nama Lengkap</td>
            <td class="kanan">:</td>
            <td colspan="34" class="kotak"><?= $input['nama_saksi1']; ?></td>
        </tr>
        <tr>
            <td class="pl-5">3.</td>
            <td colspan="12">Tanggal Lahir/Umur</td>
            <td class="kanan">:</td>
            <td>Tgl</td>
            <td class="kanan"></td>
            <?= kotak(! empty($input['tanggal_lahir_saksi1']) ? date('dd', strtotime($input['tanggal_lahir_saksi1'])) : '', 2); ?>
            <td>Bln</td>
            <td class="kanan"></td>
            <?= kotak(! empty($input['tanggal_lahir_saksi1']) ? date('mm', strtotime($input['tanggal_lahir_saksi1'])) : '', 2); ?>
            <td>Thn</td>
            <td class="kanan"></td>
            <?= kotak(! empty($input['tanggal_lahir_saksi1']) ? date('Y', strtotime($input['tanggal_lahir_saksi1'])) : '', 4); ?>
            <td>Umur</td>
            <td class="kanan"></td>
            <?= kotak($input['umur_saksi1'], 3); ?>
        </tr>
        <tr>
            <td class="pl-5">4.</td>
            <td colspan="12">Pekerjaan</td>
            <td class="kanan">:</td>
            <td colspan="10" class="kotak"><?= $input['pekerjaansaksi1']; ?></td>
        </tr>
        <tr>
            <td class="pl-5">5.</td>
            <td colspan="12">Alamat</td>
            <td class="kanan">:</td>
            <td colspan="34" class="kotak"><?= $input['alamat_saksi1']; ?></td>
        </tr>
        <tr>
            <td colspan="14">&nbsp;</td>
            <td colspan="6">a. Desa/Kelurahan</td>
            <td class="kanan"></td>
            <td colspan="10" class="kotak"><?= $config['nama_desa']; ?></td>
            <td colspan="6">c. Kab/Kota</td>
            <td class="kanan"></td>
            <td colspan="10" class="kotak"><?= $config['nama_kabupaten']; ?></td>
        </tr>
        <tr>
            <td colspan="14">&nbsp;</td>
            <td colspan="6">b. Kecamatan</td>
            <td class="kanan"></td>
            <td colspan="10" class="kotak"><?= $config['nama_kecamatan']; ?></td>
            <td colspan="6">d. Provinsi</td>
            <td class="kanan"></td>
            <td colspan="10" class="kotak"><?= $config['nama_propinsi']; ?></td>
        </tr>
    </table>

    <table class="disdukcapil" style="border-top: 0">
        <col span="48" style="width: 2.0833%;">
        <tr>
            <td colspan="48"><strong>SAKSI II</strong></td>
        </tr>
        <tr>
            <td class="pl-5">1.</td>
            <td colspan="12">NIK</td>
            <td class="kanan">:</td>
            <?= kotak($input['nik_saksi2'], 16); ?>
        </tr>
        <tr>
            <td class="pl-5">2.</td>
            <td colspan="12">Nama Lengkap</td>
            <td class="kanan">:</td>
            <td colspan="34" class="kotak"><?= $input['nama_saksi2']; ?></td>
        </tr>
        <tr>
            <td class="pl-5">3.</td>
            <td colspan="12">Tanggal Lahir/Umur</td>
            <td class="kanan">:</td>
            <td>Tgl</td>
            <td class="kanan"></td>
            <?= kotak(! empty($input['tanggal_lahir_saksi2']) ? date('dd', strtotime($input['tanggal_lahir_saksi2'])) : '', 2); ?>
            <td>Bln</td>
            <td class="kanan"></td>
            <?= kotak(! empty($input['tanggal_lahir_saksi2']) ? date('mm', strtotime($input['tanggal_lahir_saksi2'])) : '', 2); ?>
            <td>Thn</td>
            <td class="kanan"></td>
            <?= kotak(! empty($input['tanggal_lahir_saksi2']) ? date('Y', strtotime($input['tanggal_lahir_saksi2'])) : '', 4); ?>
            <td>Umur</td>
            <td class="kanan"></td>
            <?= kotak($input['umur_saksi2'], 3); ?>
        </tr>
        <tr>
            <td class="pl-5">4.</td>
            <td colspan="12">Pekerjaan</td>
            <td class="kanan">:</td>
            <td colspan="10" class="kotak"><?= $input['pekerjaansaksi2']; ?></td>
        </tr>
        <tr>
            <td class="pl-5">5.</td>
            <td colspan="12">Alamat</td>
            <td class="kanan">:</td>
            <td colspan="34" class="kotak"><?= $input['alamat_saksi2']; ?></td>
        </tr>
        <tr>
            <td colspan="14">&nbsp;</td>
            <td colspan="6">a. Desa/Kelurahan</td>
            <td class="kanan"></td>
            <td colspan="10" class="kotak"><?= $config['nama_desa']; ?></td>
            <td colspan="6">c. Kab/Kota</td>
            <td class="kanan"></td>
            <td colspan="10" class="kotak"><?= $config['nama_kabupaten']; ?></td>
        </tr>
        <tr>
            <td colspan="14">&nbsp;</td>
            <td colspan="6">b. Kecamatan</td>
            <td class="kanan"></td>
            <td colspan="10" class="kotak"><?= $config['nama_kecamatan']; ?></td>
            <td colspan="6">d. Provinsi</td>
            <td class="kanan"></td>
            <td colspan="10" class="kotak"><?= $config['nama_propinsi']; ?></td>
        </tr>
    </table>

    <!-- Penandatangan -->
    <br>
    <table style="border-collapse: collapse; width: 100%; height: 48px;" border="0">
        <tbody>
            <tr style="height: 12px;">
                <td  class="pl-5" style="width: 40%;text-align: left; height: 12px; border-top: 1px solid black; border-left: 1px solid black; border-right: 1px solid black;"><b>Lampiran Persyaratan:</b></td>
                <td style="width: 30%; height: 12px;"> </td>
                <td style="width: 30%; text-align: center; height: 12px;">[NAma_desa], [TgL_surat]</td>
            </tr>
            <tr style="height: 12px;">
                <td  class="pl-5" style="width: 40%;text-align: left; height: 12px; border-left: 1px solid black; border-right: 1px solid black;">1. Surat keterangan kematian dari <?= $penandatangan['atas_nama'] ?></td>
                <td style="width: 30%; height: 12px;"> </td>
                <td style="width: 30%; text-align: center; height: 12px;"><?= $penandatangan['atas_nama'] ?></td>
            </tr>
            <tr style="height: 12px;">
                <td  class="pl-5" style="width: 40%;text-align: left; height: 12px; border-left: 1px solid black; border-right: 1px solid black;">2. Surat keterangan kematian dari dokter/paramedis</td>
                <td style="width: 30%; height: 12px;"></td>
                <td style="width: 30%; height: 12px;"> </td>
            </tr>
            <tr style="height: 12px;">
                <td  class="pl-5" style="width: 40%;text-align: left; height: 12px; border-left: 1px solid black; border-right: 1px solid black;">3. Fotocopy KK</td>
                <td style="width: 30%; height: 12px;"></td>
                <td style="width: 30%; height: 12px;"> </td>
            </tr>
            <tr style="height: 12px;">
                <td  class="pl-5" style="width: 40%;text-align: left; height: 12px; border-left: 1px solid black; border-right: 1px solid black;">4. Fotocopy KTP Pelapor</td>
                <td style="width: 30%; height: 12px;"></td>
                <td style="width: 30%; height: 12px;"> </td>
            </tr>
            <tr style="height: 12px;">
                <td  class="pl-5" style="width: 40%;text-align: left; height: 12px; border-left: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black;">5. Fotocopy KTP 2 Orang Saksi</td>
                <td style="width: 30%; height: 12px;"> </td>
                <td style="width: 30%; text-align: center; height: 12px;"><strong>(<?= padded_string_center(strtoupper($penandatangan['nama']), 30) ?>)</strong></td>
            </tr>
        </tbody>
    </table>
    <!-- Akhir Halaman 4 -->
</page>