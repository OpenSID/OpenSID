<?php defined('BASEPATH') || exit('No direct script access allowed');

/*
 *  File ini:
 *
 * View untuk ubah telepon warga di modul Layanan Mandiri
 *
 * donjo-app/views/mandiri/ajax_hp.php
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
<form id="validasi" action="<?= $form_action; ?>" method="post">
	<div class="modal-body">
		<table class="table table-hover">
			<tr>
				<th width="20%">NIK</td>
				<td width="1%"> : </td>
				<td><?= $penduduk['nik']; ?></td>
			</tr>
			<tr>
				<th>Nama Warga</td>
				<td> : </td>
				<td><?= $penduduk['nama']; ?></td>
			</tr>
		</table>
		<div class="box box-danger">
			<div class="box-body">
				<div class="form-group">
					<label for="exampleInputEmail1">Scan KTP</label>
					<img class="img-responsive" src="<?= base_url("desa/upload/pendaftaran/{$penduduk['scan_ktp']}") ?>" alt="Scan KTP"/>
				</div>
				<div class="form-group">
					<label for="exampleInputPassword1">Scan KK</label>
					<img class="img-responsive" src="<?= base_url("desa/upload/pendaftaran/{$penduduk['scan_kk']}") ?>" alt="Scan KK"/>
				</div>
				<div class="form-group">
					<label for="exampleInputPassword1">Foto Selfie</label>
					<img class="img-responsive" src="<?= base_url("desa/upload/pendaftaran/{$penduduk['foto_selfie']}") ?>" alt="Foto Selfie"/>
				</div>
			</div>
		</div>
		<div class="form-group">
			<?php if ($tgl_verifikasi_telegram || $tgl_verifikasi_email) : ?>
				<label style="margin-top: 10px; margin-bottom: 0px;">Kirim Pemberitahuan Verifikasi Melalui : </label>
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

		<?php if ($tgl_verifikasi_telegram || $tgl_verifikasi_email) : ?>
			<button type="submit" class="btn btn-social btn-flat btn-info btn-sm" id="ok"><i class='fa fa-check'></i> Verifikasi</button>
		<?php endif; ?>
	</div>
</form>