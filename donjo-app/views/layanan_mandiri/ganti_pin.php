<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * File ini:
 *
 * View modul Layanan Mandiri > Ganti PIN
 *
 * donjo-app/views/layanan_mandiri/ganti_pin.php
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

<div class="box box-solid">
	<div class="box-header with-border bg-navy">
		<h4 class="box-title">Ganti PIN</h4>
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-md-3"></div>
			<div class="col-md-6 col-sm-6 col-xs-12">
				<form action="<?= $form_action; ?>" method="POST" id="validasi">
					<div class="box-body">
						<?php $gagal = $data = $this->session->flashdata('notif'); ?>
						<?php if ($data['status'] == -1): ?>
							<div class="callout callout-danger">
								<?= $gagal['pesan']; ?>
							</div>
						<?php endif; ?>

						<div class="form-group">
							<label for="pin_lama">PIN Lama</label>
							<div class="input-group">
								<input type="password" class="form-control input-md bilangan pin required <?= jecho($cek_anjungan['keyboard'] == 1, TRUE, 'kbvnumber'); ?>" name="pin_lama" placeholder="Masukkan PIN Lama" minlength="6" maxlength="6">
								<span class="input-group-addon"><i class="fa fa-eye-slash" id="lama" onclick="show(this);" aria-hidden="true"></i></span>
							</div>
						</div>
						<div class="form-group">
							<label for="pin_baru1">PIN Baru</label>
							<div class="input-group">
								<input type="password" class="form-control input-md bilangan pin required <?= jecho($cek_anjungan['keyboard'] == 1, TRUE, 'kbvnumber'); ?>" name="pin_baru1" id="pin_baru1" placeholder="Masukkan PIN Baru" minlength="6" maxlength="6">
								<span class="input-group-addon"><i class="fa fa-eye-slash" id="baru1" onclick="show(this);" aria-hidden="true"></i></span>
							</div>
						</div>
						<div class="form-group">
							<label for="pin_baru2">Konfirmasi PIN Baru</label>
							<div class="input-group">
								<input type="password" class="form-control input-md bilangan pin required <?= jecho($cek_anjungan['keyboard'] == 1, TRUE, 'kbvnumber'); ?>" name="pin_baru2" id="pin_baru2" placeholder="Masukkan Konfirmasi PIN Baru" minlength="6" maxlength="6">
								<span class="input-group-addon"><i class="fa fa-eye-slash" id="baru2" onclick="show(this);" aria-hidden="true"></i></span>
							</div>
						</div>
					</div>
					<div class="box-footer">
						<button type="reset" class="btn bg-red">Batal</button>
						<button type="submit" class="btn bg-green pull-right">Simpan</button>
					</div>
				</form>
			</div>
			<div class="col-md-3"></div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function(){
		setTimeout(function() {
			$('#pin_baru2').rules('add', {
				equalTo: '#pin_baru1'
			});
		}, 500);

		window.setTimeout(function() {
			$(".callout").fadeTo(500, 0).slideUp(500, function(){
				$(this).remove();
			});
		}, 5000);
	});

	function show(elem) {
		if ($(elem).hasClass('fa-eye')) {
			$(".pin").attr('type', 'password');
			$(".fa-eye").addClass('fa-eye-slash');
			$(".fa-eye").removeClass('fa-eye');
		} else {
			$(".pin").attr('type', 'text');
			$(".fa-eye-slash").addClass('fa-eye');
			$(".fa-eye-slash").removeClass('fa-eye-slash');
		}
	}
</script>
