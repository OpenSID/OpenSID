<?php

defined('BASEPATH') || exit('No direct script access allowed');

/*
 * File ini:
 *
 * View untuk modul Buku Pembangunan Desa > Buku Rencana Pembangunan
 *
 * donjo-app/views/bumindes/pembangunan/ajax_dialog.php,
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

<?php if ($list_tahun): ?>
	<div class="form-group">
		<label for="tahun">Tahun Anggaran</label>
		<select class="form-control input-sm" name="tahun">
			<option value="semua">Semua Tahun</option>
			<?php foreach ($list_tahun as $list): ?>
				<option value="<?= $list->tahun_anggaran; ?>" <?= selected($tahun, $list->tahun_anggaran); ?>><?= $list->tahun_anggaran; ?></option>
			<?php endforeach; ?>
		</select>
	</div>
<?php endif; ?>

<div class="form-group">
	<label for="tgl_cetak">Tanggal Cetak</label>
	<div class="input-group input-group-sm date">
		<div class="input-group-addon">
			<i class="fa fa-calendar"></i>
		</div>
		<input class="form-control input-sm pull-right required" id="tgl_1" name="tgl_cetak" type="text" value="<?= date('d-m-Y'); ?>">
	</div>
</div>