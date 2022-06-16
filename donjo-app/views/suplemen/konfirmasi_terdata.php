<?php

defined('BASEPATH') || exit('No direct script access allowed');

/*
 * File ini:
 *
 * View konfirmasi terdata untuk modul Suplemen
 *
 * donjo-app/views/suplemen/konfirmasi_terdata.php,
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

<?php if ($suplemen['sasaran'] == 1): ?>
	<div class="form-group">
		<label for="keperluan"  class="col-sm-3 control-label">Tempat  Tanggal Lahir / Umur</label>
		<div class="col-sm-4">
			<input class="form-control input-sm" type="text" value="<?= $individu['tempatlahir']?>" disabled="">
		</div>
		<div class="col-sm-2">
			<input class="form-control input-sm" type="text" value="<?= tgl_indo($individu['tanggallahir'])?>" disabled="">
		</div>
		<div class="col-sm-2">
			<input class="form-control input-sm" type="text" value="<?= $individu['umur']?> Tahun" disabled="">
		</div>
	</div>
	<div class="form-group">
		<label for="keperluan"  class="col-sm-3 control-label">Alamat</label>
		<div class="col-sm-8">
			<input class="form-control input-sm" type="text" value="<?= $individu['alamat_wilayah']; ?>" disabled="">
		</div>
	</div>
	<div class="form-group">
		<label for="keperluan"  class="col-sm-3 control-label">Pendidikan</label>
		<div class="col-sm-8">
			<input class="form-control input-sm" type="text" value="<?= $individu['pendidikan']?>" disabled="">
		</div>
	</div>
	<div class="form-group">
		<label for="keperluan"  class="col-sm-3 control-label">Warga Negara /Agama</label>
		<div class="col-sm-4">
			<input class="form-control input-sm" type="text" value="<?= $individu['warganegara']?>" disabled="">
		</div>
		<div class="col-sm-4">
			<input class="form-control input-sm" type="text" value="<?= $individu['agama']?>" disabled="">
		</div>
	</div>
<?php elseif ($suplemen['sasaran'] == 2): ?>
	<div class="form-group">
		<label for="keperluan"  class="col-sm-3 control-label">Tempat Tanggal Lahir (Umur) KK</label>
		<div class="col-sm-4">
			<input class="form-control input-sm" type="text" value="<?= $individu['tempatlahir']?>" disabled="">
		</div>
		<div class="col-sm-2">
			<input class="form-control input-sm" type="text" value=" <?= tgl_indo($individu['tanggallahir'])?>" disabled="">
		</div>
		<div class="col-sm-2">
			<input class="form-control input-sm" type="text" value="<?= $individu['umur']?> Tahun" disabled="">
		</div>
	</div>
	<div class="form-group">
		<label for="keperluan"  class="col-sm-3 control-label">Alamat Keluarga</label>
		<div class="col-sm-8">
			<input class="form-control input-sm" type="text" value="<?= $individu['alamat_wilayah']; ?>" disabled="">
		</div>
	</div>
	<div class="form-group">
		<label for="keperluan"  class="col-sm-3 control-label">Pendidikan KK</label>
		<div class="col-sm-8">
			<input class="form-control input-sm" type="text" value="<?= $individu['pendidikan']?>" disabled="">
		</div>
	</div>
	<div class="form-group">
		<label for="keperluan"  class="col-sm-3 control-label">Warga Negara /Agama KK</label>
		<div class="col-sm-4">
			<input class="form-control input-sm" type="text" value="<?= $individu['warganegara']?>" disabled="">
		</div>
		<div class="col-sm-4">
			<input class="form-control input-sm" type="text" value="<?= $individu['agama']?>" disabled="">
		</div>
	</div>
<?php endif; ?>
