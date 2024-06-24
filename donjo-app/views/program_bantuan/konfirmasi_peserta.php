<?php
/**
 * File ini:
 *
 * Bagian dari form penambahan peserta Program Bantuan
 *
 * donjo-app/views/program_bantuan/konfirmasi_peserta.php
 */

/*
 * File ini bagian dari:
 *
 * OpenSID
 *
 * Sistem informasi desa sumber terbuka untuk memajukan desa
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * Hak Cipta 2016 - 2020 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 *
 * Dengan ini diberikan izin, secara gratis, kepada siapa pun yang mendapatkan salinan
 * dari perangkat lunak ini dan file dokumentasi terkait ("Aplikasi Ini"), untuk diperlakukan
 * tanpa batasan, termasuk hak untuk menggunakan, menyalin, mengubah dan/atau mendistribusikan,
 * asal tunduk pada syarat berikut:
 *
 * Pemberitahuan hak cipta di atas dan pemberitahuan izin ini harus disertakan dalam
 * setiap salinan atau bagian penting Aplikasi Ini. Barang siapa yang menghapus atau menghilangkan
 * pemberitahuan ini melanggar ketentuan lisensi Aplikasi Ini.
 *
 * PERANGKAT LUNAK INI DISEDIAKAN "SEBAGAIMANA ADANYA", TANPA JAMINAN APA PUN, BAIK TERSURAT MAUPUN
 * TERSIRAT. PENULIS ATAU PEMEGANG HAK CIPTA SAMA SEKALI TIDAK BERTANGGUNG JAWAB ATAS KLAIM, KERUSAKAN ATAU
 * KEWAJIBAN APAPUN ATAS PENGGUNAAN ATAU LAINNYA TERKAIT APLIKASI INI.
 *
 * @copyright	  Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright	  Hak Cipta 2016 - 2020 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license	http://www.gnu.org/licenses/gpl.html	GPL V3
 *
 * @see 	https://github.com/OpenSID/OpenSID
 */
?>

<div class="form-group">
	<label class="col-sm-4 col-lg-5 control-label"><?=$individu['judul_nik']?></label>
	<div class="col-sm-7">
		<input class="form-control input-sm" type="text" disabled value="<?= $individu['nik']; ?>">
	</div>
</div>
<div class="form-group">
	<label class="col-sm-4 col-lg-5 control-label">Nama <?=$individu['judul']?></label>
	<div class="col-sm-7">
		<input class="form-control input-sm" type="text" disabled value="<?= $individu['nama']; ?>">
	</div>
</div>
<?php if ($detail['sasaran'] == 2): ?>
	<div class="form-group">
		<label class="col-sm-4 col-lg-5 control-label">Nomer KK</label>
		<div class="col-sm-7">
			<input class="form-control input-sm" type="text" disabled value="<?= $individu['no_kk']; ?>">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-4 col-lg-5 control-label">Nama Kepala Keluarga</label>
		<div class="col-sm-7">
			<input class="form-control input-sm" type="text" disabled value="<?= $individu['nama_kk']; ?>">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-4 col-lg-5 control-label">Status KK</label>
		<div class="col-sm-7">
			<input class="form-control input-sm" type="text" disabled value="<?= $individu['hubungan']; ?>">
		</div>
	</div>
<?php endif; ?>
<div class="form-group">
	<label class="col-sm-4 col-lg-5 control-label">Alamat <?=$individu['judul']?></label>
	<div class="col-sm-7">
		<input class="form-control input-sm" type="text" disabled value="<?= $individu['alamat_wilayah']; ?>">
	</div>
</div>
<div class="form-group">
	<label class="col-sm-4 col-lg-5 control-label">Tempat Tanggal, Lahir <?=$individu['judul']?></label>
	<div class="col-sm-7">
		<input class="form-control input-sm" type="text" disabled value="<?= $individu['tempatlahir']?>, <?= tgl_indo($individu['tanggallahir'])?>">
	</div>
</div>
<div class="form-group">
	<label class="col-sm-4 col-lg-5 control-label">Jenis Kelamin <?=$individu['judul']?></label>
	<div class="col-sm-7">
		<input class="form-control input-sm" type="text" disabled value="<?= $individu['sex']?>">
	</div>
</div>
<div class="form-group">
	<label class="col-sm-4 col-lg-5 control-label">Umur <?=$individu['judul']?></label>
	<div class="col-sm-7">
		<input class="form-control input-sm" type="text" disabled value="<?= $individu['umur']?> TAHUN">
	</div>
</div>
<div class="form-group">
	<label class="col-sm-4 col-lg-5 control-label">Pendidikan <?=$individu['judul']?></label>
	<div class="col-sm-7">
		<input class="form-control input-sm" type="text" disabled value="<?= $individu['pendidikan']?>">
	</div>
</div>
<div class="form-group">
	<label class="col-sm-4 col-lg-5 control-label">Warga Negara / Agama <?=$individu['judul']?></label>
	<div class="col-sm-7">
		<input class="form-control input-sm" type="text" disabled value="<?= $individu['warganegara']?> / <?= $individu['agama']?>">
	</div>
</div>
<div class="form-group">
	<label class="col-sm-4 col-lg-5 control-label">Bantuan <?=$individu['judul']?> Yang Sedang Diterima</label>
	<div class="col-sm-7">
		<?php foreach ($individu['program']['programkerja'] as $item): ?>
			<?php if ($item[\STATUS] == '1'): ?>
				<?= anchor("program_bantuan/data_peserta/{$item['peserta_id']}", '<span class="label label-success">' . $item['nama'] . '</span>&nbsp;', 'target="_blank"'); ?>
			<?php endif; ?>
		<?php endforeach; ?>
	</div>
</div>
