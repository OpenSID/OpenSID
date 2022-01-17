<?php

defined('BASEPATH') || exit('No direct script access allowed');

/*
 * File ini:
 *
 * View untuk pencarian spesifik modul penduduk
 *
 * donjo-app/views/sid/kependudukan/ajax_adv_search_form.php,
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
						<?php foreach ($list_pekerjaan as $data): ?>
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
						<?php foreach ($list_status_kawin as $data): ?>
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
						<?php foreach ($list_agama as $data): ?>
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
						<?php foreach ($list_pendidikan as $data): ?>
							<option value="<?= $data['id']?>" <?php selected($pendidikan_sedang_id, $data['id']); ?> ><?= $data['nama']?></option>
						<?php endforeach; ?>
					</select>
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-group">
					<label for="pendidikan_kk_id">Pendidikan Dalam KK</label>
					<select class="form-control input-sm" id="pendidikan_kk_id" name="pendidikan_kk_id">
						<option value=""> -- </option>
						<?php foreach ($list_pendidikan_kk as $data): ?>
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
						<?php foreach ($list_status_penduduk as $data): ?>
							<option value="<?= $data['id']?>" <?php selected($status_penduduk, $data['id']); ?>><?= $data['nama']?></option>
						<?php endforeach; ?>
					</select>
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-group">
					<label for="sex">Jenis Kelamin</label>
					<select class="form-control input-sm" id="sex" name="sex">
						<option value=""> -- </option>
						<?php foreach ($list_sex as $data): ?>
							<option value="<?= $data['id']?>" <?php selected($sex, $data['id']); ?>><?= $data['nama']?></option>
						<?php endforeach; ?>
					</select>
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-group">
					<label for="status_dasar">Status Dasar</label>
					<select class="form-control input-sm" id="status_dasar" name="status_dasar">
						<option value=""> -- </option>
						<?php foreach ($list_status_dasar as $data): ?>
							<option value="<?= $data['id']?>" <?php selected($status_dasar, $data['id']); ?>><?= $data['nama']?></option>
						<?php endforeach; ?>
					</select>
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-group">
					<label for="cacat">Cacat</label>
					<select class="form-control input-sm" id="cacat" name="cacat">
						<option value=""> -- </option>
						<?php foreach ($list_cacat as $data): ?>
							<option value="<?= $data['id']?>" <?php selected($cacat, $data['id']); ?>><?= $data['nama']?></option>
						<?php endforeach; ?>
					</select>
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-group">
					<label for="cara_kb_id">Cara KB</label>
					<select class="form-control input-sm" id="cara_kb_id" name="cara_kb_id">
						<option value=""> -- </option>
						<?php foreach ($list_cara_kb as $data): ?>
							<option value="<?= $data['id']?>" <?php selected($cara_kb_id, $data['id']); ?>><?= $data['nama']?></option>
						<?php endforeach; ?>
					</select>
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-group">
					<label for="status_ktp">Status KTP</label>
					<select class="form-control input-sm" id="status_ktp" name="status_ktp">
						<option value=""> -- </option>
						<?php foreach ($list_status_ktp as $data): ?>
							<option value="<?= $data['id']?>" <?php selected($status_ktp, $data['id']); ?>><?= $data['nama']?></option>
						<?php endforeach; ?>
					</select>
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-group">
					<label for="id_asuransi">Asuransi</label>
					<select class="form-control input-sm" id="id_asuransi" name="id_asuransi">
						<option value=""> -- </option>
						<?php foreach ($list_asuransi as $data): ?>
							<option value="<?= $data['id']?>" <?php selected($id_asuransi, $data['id']); ?>><?= $data['nama']?></option>
						<?php endforeach; ?>
					</select>
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-group">
					<label for="warganegara">Warga Negara</label>
					<select class="form-control input-sm" id="warganegara" name="warganegara">
						<option value=""> -- </option>
						<?php foreach ($list_warganegara as $data): ?>
							<option value="<?= $data['id']?>" <?php selected($warganegara, $data['id']); ?>><?= $data['nama']?></option>
						<?php endforeach; ?>
					</select>
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-group">
					<label for="golongan_darah">Golongan Darah</label>
					<select class="form-control input-sm" id="golongan_darah" name="golongan_darah">
						<option value=""> -- </option>
						<?php foreach ($list_golongan_darah as $data): ?>
							<option value="<?= $data['id']?>" <?php selected($golongan_darah, $data['id']); ?>><?= $data['nama']?></option>
						<?php endforeach; ?>
					</select>
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-group">
					<label for="menahun">Sakit Menahun</label>
					<select class="form-control input-sm" id="menahun" name="menahun">
						<option value=""> -- </option>
						<?php foreach ($list_sakit_menahun as $data): ?>
							<option value="<?= $data['id']?>" <?php selected($menahun, $data['id']); ?>><?= $data['nama']?></option>
						<?php endforeach; ?>
					</select>
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-group">
					<label for="tag_id_card">Tag ID Card</label>
					<select class="form-control input-sm" id="tag_id_card" name="tag_id_card">
						<option value=""> -- </option>
						<option value="2" <?php	selected($tag_id_card, '2') ?>>YA</option>
						<option value="1" <?php	selected($tag_id_card, '1') ?>>TIDAK</option>
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