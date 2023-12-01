<?php

defined('BASEPATH') || exit('No direct script access allowed');

/*
 * File ini:
 *
 * View untuk modul Statistik Kependudukan
 *
 * donjo-app/views/statistik/ajax_rentang_form.php,
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

<script src="<?= asset('js/jquery.validate.min.js')?>"></script>
<script src="<?= asset('js/validasi.js')?>"></script>
<script src="<?= asset('js/localization/messages_id.js')?>"></script>
<form action="<?= $form_action?>" method="post" id="validasi">
	<div class='modal-body'>
		<div class="form-group">
			<div class="row">
				<div class="col-sm-12">
					<label for="nama">Rentang Umur</label>
				</div>
				<div class="col-xs-6">
					<input class="form-control input-sm required bilangan" type="text" placeholder="Dari" id="dari" name="dari" value="<?= $rentang['dari']?>"></input>
				</div>
				<div class="col-xs-6">
					<input id="sampai" class="form-control input-sm required bilangan" type="text" placeholder="Sampai" name="sampai" value="<?= $rentang['sampai']?>"></input>
				</div>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<?= batal() ?>
		<button type="submit" class="btn btn-social btn-info btn-sm" id="ok"><i class='fa fa-check'></i> Simpan</button>
	</div>
</form>

<script>

	// dokument ready
	$(document).ready(function() {
		function validateMin() {
			var min = parseFloat($('#dari').val());
			var max = parseFloat($('#sampai').val());

			if (min >= max) {
				$('#dari').prop('max', max);
			} else {
				$('#dari').prop('max', '');
				$('#sampai').prop('min', '');
			}
		}

		function validateMax() {
			var min = parseFloat($('#dari').val());
			var max = parseFloat($('#sampai').val());

			if (max < min) {
				$('#sampai').prop('min', min);
			} else {
				$('#sampai').prop('min', '');
				$('#dari').prop('max', '');
			}
		}

		$('#dari').on('keyup', function() {
			validateMin();
		});

		$('#sampai').on('keyup', function() {
			validateMax();
		});

		$('#validasi').submit(function(e) {
			var min = parseFloat($('#dari').val());
			var max = parseFloat($('#sampai').val());

			if (min >= max) {
				e.preventDefault();
			}
		});
	});
</script>

