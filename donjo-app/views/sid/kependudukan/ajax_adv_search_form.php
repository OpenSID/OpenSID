<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * File ini:
 *
 * View untuk pencarian spesifik modul penduduk
 *
 * donjo-app/views/sid/kependudukan/ajax_adv_search_form.php,
 *
 */

/**
 *
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
 * @package	OpenSID
 * @author	Tim Pengembang OpenDesa
 * @copyright	Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright	Hak Cipta 2016 - 2020 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license	http://www.gnu.org/licenses/gpl.html	GPL V3
 * @link 	https://github.com/OpenSID/OpenSID
 */
?>

<?php $this->load->view('global/validasi_form'); ?>
<form method="post" action="<?= $form_action?>" id="validasi">
	<div class="modal-body">
		<div class="row">
			<div class="col-sm-12">
				<label for="nama">Umur</label>
			</div>
			<div class="col-sm-6">
				<div class="form-group">
					<input class="form-control  input-sm bilangan" maxlength="3" type="text" placeholder="Dari" id="umur_min" name="umur_min"  value="<?= $umur_min?>"></input>
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-group">
					<input id="umur_max" class="form-control input-sm bilangan" maxlength="3" type="text" placeholder="Sampai" name="umur_max" value="<?= $umur_max?>"></input>
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-group">
					<label for="pekerjaan">Pekerjaan</label>
					<select class="form-control input-sm" id="pekerjaan_id" name="pekerjaan_id">
						<option value=""> -- </option>
						<?php foreach ($list_pekerjaan AS $data): ?>
							<option value="<?= $data['id']?>" <?php selected($pekerjaan_id, $data['id']); ?>><?= $data['nama']?></option>
						<?php endforeach; ?>
					</select>
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-group">
					<label for="status_dasar">Status Perkawinan</label>
					<select class="form-control input-sm" id="status" name="status">
						<option value=""> -- </option>
						<?php foreach ($list_status_kawin AS $data): ?>
							<option value="<?= $data['id']?>" <?php selected($status, $data['id']); ?>><?= $data['nama']?></option>
						<?php endforeach; ?>
					</select>
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-group">
					<label for="agama">Agama</label>
					<select class="form-control input-sm" id="agama" name="agama">
						<option value=""> -- </option>
						<?php foreach ($list_agama AS $data): ?>
							<option value="<?= $data['id']?>" <?php selected($agama, $data['id']); ?> ><?= $data['nama']?></option>
						<?php endforeach; ?>
					</select>
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-group">
					<label for="pendidikan_sedang_id">Pendidikan Sedang Ditempuh</label>
					<select class="form-control input-sm" id="pendidikan_sedang_id"  name="pendidikan_sedang_id">
						<option value=""> -- </option>
						<?php foreach ($list_pendidikan AS $data): ?>
							<option value="<?= $data['id']?>" <?php selected($pendidikan_sedang_id, $data['id']); ?> ><?= $data['nama']?></option>
						<?php endforeach;?>
					</select>
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-group">
					<label for="pendidikan_kk_id">Pendidikan Dalam KK</label>
					<select class="form-control input-sm" id="pendidikan_kk_id" name="pendidikan_kk_id">
						<option value=""> -- </option>
						<?php foreach ($list_pendidikan_kk AS $data): ?>
							<option value="<?= $data['id']?>" <?php selected($pendidikan_kk_id, $data['id']); ?>><?= $data['nama']?></option>
						<?php endforeach; ?>
					</select>
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-group">
					<label for="status_penduduk">Status Penduduk</label>
					<select class="form-control input-sm" id="status_penduduk" name="status_penduduk">
						<option value=""> -- </option>
						<?php foreach ($list_status_penduduk AS $data): ?>
							<option value="<?= $data['id']?>" <?php selected($status_penduduk, $data['id']); ?>><?= $data['nama']?></option>
						<?php endforeach; ?>
					</select>
				</div>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<button type="reset" class="btn btn-social btn-flat btn-danger btn-sm pull-left"><i class="fa fa-times"></i> Batal</button>
		<button type="submit" class="btn btn-social btn-flat btn-info btn-sm"><i class='fa fa-check'></i> Simpan</button>
	</div>
</form>
