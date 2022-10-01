<?php

defined('BASEPATH') || exit('No direct script access allowed');

/*
 * File ini:
 *
 * View modul Layanan Mandiri > Pesan > Buat Pesan
 *
 * donjo-app/views/fmandiri/buat_pesan.php
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

<form id="validasi" action="<?= $form_action ?>" method="POST">
	<div class="box box-solid">
		<div class="box-header with-border bg-green">
			<h4 class="box-title">Surat</h4>
		</div>
		<div class="box-body box-line">
			<h4><b>PERMOHONAN SURAT</b></h4>
			<input type="hidden" id="id_permohonan" name="id_permohonan" value="<?= $permohonan['id'] ?>" />
		</div>
		<div class="box-body box-line">
			<?php if ($permohonan) : ?>
				<div class="alert alert-warning" role="alert">
					<span style="font-size: larger;">Lengkapi permohonan surat tanggal <?= $permohonan['updated_at']; ?></span>
				</div>
			<?php endif; ?>
			<div class="form-group">
				<label for="nama_surat" class="col-sm-3 control-label">Jenis Surat Yang Dimohon</label>
				<div class="col-sm-9">
					<select class="form-control select2 required" name="id_surat" id="id_surat">
						<option value=""> -- Pilih Jenis Surat -- </option>
						<?php foreach ($menu_surat_mandiri as $data) : ?>
							<option value="<?= $data['id'] ?>" <?= selected($data['id'], $permohonan['id_surat']) ?>><?= $data['nama'] ?></option>
						<?php endforeach; ?>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label for="keterangan_tambahan" class="col-sm-3 control-label">Keterangan Tambahan</label>
				<div class="col-sm-9">
					<textarea class="form-control <?= jecho($cek_anjungan['keyboard'] == 1, true, 'kbvtext'); ?>" name="keterangan" id="keterangan" placeholder="Ketik di sini untuk memberikan keterangan tambahan."><?= $permohonan['keterangan']; ?></textarea>
				</div>
			</div>
			<div class="form-group">
				<label for="no_hp_aktif" class="col-sm-3 control-label">No. HP aktif</label>
				<div class="col-sm-9">
					<input class="form-control bilangan_spasi required <?= jecho($cek_anjungan['keyboard'] == 1, true, 'kbvnumber'); ?>" type="text" name="no_hp_aktif" id="no_hp_aktif" placeholder="Ketik No. HP" maxlength="14" value="<?= $permohonan['no_hp_aktif'] ?? $this->is_login->telepon; ?>"/>
				</div>
			</div>
		</div>
	</div>

	<!-- Kelengkapan Dokumen Yang Dibutuhkan -->
	<div class="box box-default">
		<div class="ada_syarat" style="display: none">
			<div class="box-header with-border">
				<h4><b>SYARAT SURAT</b></h4>
			</div>
			<div class="box-body">
				<div class="table-responsive">
					<table class="table table-bordered table-hover table-data" id="syarat_surat" style="width: 100%;">
						<thead>
							<tr>
								<th>No</th>
								<th>Syarat</th>
								<th>Dokumen Melengkapi Syarat</th>
							</tr>
						</thead>
					</table>
				</div>
			</div>
		</div>
		<div class="box-footer">
			<button type="reset" class="btn btn-social btn-sm btn-danger"><i class="fa fa-times"></i> Batal</button>
			<button type="submit" class="btn btn-social btn-primary btn-sm pull-right" id="isi_form"><i class="fa fa-sign-in"></i>Isi Form</button>
		</div>
	</div>
</form>

<div class="modal fade in" id="dialog" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header btn-danger">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
				<h4 class="modal-title" id="myModalLabel"><i class="fa fa-exclamation-triangle"></i> &nbsp;Peringatan</h4>
			</div>
			<div class="modal-body">
				<p id="kata_peringatan"></p>
			</div>
			<div class="modal-footer">
				<button class="btn btn-social btn-danger btn-sm" data-dismiss="modal"><i class='fa fa-sign-out'></i> Tutup</button>
			</div>
		</div>
	</div>
</div>
<script type='text/javascript'>
	function cek_perhatian(elem) {
		if ($(elem).val() == '-1') {
			$(elem).next('.perhatian').show();
		} else {
			$(elem).next('.perhatian').hide();
		}
	}
</script>