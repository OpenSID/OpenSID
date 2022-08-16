<?php defined('BASEPATH') || exit('No direct script access allowed');

/*
 *  File ini:
 *
 * View untuk modul Layanan Mandiri
 *
 * donjo-app/views/ajax_pin.php
 *
 */
/*
 *  File ini bagian dari:
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
 * @copyright	  Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright	  Hak Cipta 2016 - 2020 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license	http://www.gnu.org/licenses/gpl.html	GPL V3
 * @link 	https://github.com/OpenSID/OpenSID
 */
?>

<?php $this->load->view('global/validasi_form'); ?>
<form action="<?= $form_action; ?>" method="post" id="validasi">
	<div class="modal-body">
		<?php if (! $id_pend) : ?>
			<div class="form-group">
				<label for="id_pend">NIK / Nama Penduduk <?= $id_pend; ?></label>
				<select class="form-control input-sm select2 required" id="id_pend" name="id_pend">
					<option option value="">-- Silakan Cari NIK - Nama Penduduk --</option>
					<?php foreach ($penduduk as $data) : ?>
						<option value="<?= $data['id']; ?>" <?= selected($id_pend, $data['id']); ?>><?= $data['nik'] . ' - ' . $data['nama']; ?></option>
					<?php endforeach; ?>
				</select>
			</div>
		<?php endif; ?>
		<div class="form-group">
			<label class="control-label" for="pin">PIN</label>
			<input id="pin" name="pin" class="form-control input-sm digits" minlength="6" maxlength="6" type="text" placeholder="PIN Warga" <?= ! $id_pend ? '' : 'disabled' ?> style="margin-bottom: 15px;"></input>
			<?php if (! $id_pend) : ?>
				<p class="help-block"><code>1. Jika PIN tidak di isi maka sistem akan menghasilkan PIN secara acak.</code></p>
				<p class="help-block"><code>2. 6 (enam) digit Angka.</code></p>
			<?php endif; ?>
			<?php if ($id_pend) : ?>
				<p class="help-block"><code>1. Sistem akan menghasilkan PIN secara acak dengan cara menekan tombol 'Reset PIN'.</code></p>
				<p class="help-block"><code>2. PIN berisi 6 (enam) digit Angka.</code></p>
				<p class="help-block"><code>3. PIN akan dikirimkan ke akun Telegram atau Email yang sudah diverifikasi.</code></p>
				<p class="help-block"><code>4. Cara Verifikasi Telegram atau Email di menu Verifikasi pada Layanan Mandiri.</code></p>
			<?php endif; ?>
		</div>

		<div class="form-group">
			<?php if ($tgl_verifikasi_telegram || $tgl_verifikasi_email) : ?>
				<label style="margin-top: 10px; margin-bottom: 0px;">Kirim PIN Baru Melalui : </label>
			<?php endif; ?>

			<?php if ($tgl_verifikasi_email) : ?>
				<div class="radio">
					<label style="font-size: 13.7px;">
						<input type="radio" value="kirim_email" id="kirim_email" name="pilihan_kirim" checked> Email
					</label>
				</div>
			<?php endif; ?>

			<?php if ($tgl_verifikasi_telegram) : ?>
				<div class="radio">
					<label style="font-size: 13.7px;">
						<input type="radio" value="kirim_telegram" id="kirim_telegram" name="pilihan_kirim" checked> Telegram
					</label>
				</div>
			<?php endif; ?>
		</div>

	</div>
	<div class="modal-footer">
		<button type="reset" class="btn btn-social btn-flat btn-danger btn-sm pull-left"><i class='fa fa-times'></i> Batal</button>
		<button type="submit" class="btn btn-social btn-flat btn-info btn-sm" id="ok"><i class='fa fa-check'></i> <?= ! $id_pend ? 'Simpan' : 'Reset PIN' ?></button>
	</div>
</form>