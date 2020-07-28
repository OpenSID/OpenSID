<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * File ini:
 *
 * View untuk modul Rumah Tangga
 *
 * donjo-app/views/sid/kependudukan/ajax_add_rtm.php,
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
 * Pemberitahuan hak cipta di atas dan pemberitahuan izin ini harus disertakan dalam
 * setiap salinan atau bagian penting Aplikasi Ini. Barang siapa yang menghapus atau menghilangkan
 * pemberitahuan ini melanggar ketentuan lisensi Aplikasi Ini.
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

<script src="<?= base_url('assets/js/jquery.validate.min.js')?>"></script>
<script src="<?= base_url('assets/js/validasi.js')?>"></script>
<script src="<?= base_url('assets/js/localization/messages_id.js')?>"></script>
<script>
	$(function () {
		$('.select2').select2()
	})
</script>
<form action="<?= $form_action?>" method="post" id="validasi">
	<div class='modal-body'>
		<div class="form-group">
			<label for="nik">Kepala Rumah Tangga</label>
			<select class="form-control input-sm select2 required"  id="nik_kepala" name="nik_kepala" style="width:100%;">
				<option option value="">-- Silakan Cari NIK / Nama Penduduk--</option>
				<?php foreach ($penduduk as $data): ?>
					<option value="<?= $data['id']?>">NIK :<?= $data['nik']." - ".$data['nama']?></option>
				<?php endforeach; ?>
			</select>
		</div>
		<p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
			Silakan cari nama / NIK dari data penduduk yang sudah terinput.
			Penduduk yang dipilih otomatis berstatus sebagai Kepala Rumah Tangga baru tersebut.
		</p>
	</div>
	<div class="modal-footer">
		<button type="reset" class="btn btn-social btn-flat btn-danger btn-sm" data-dismiss="modal"><i class='fa fa-sign-out'></i> Tutup</button>
		<button type="submit" class="btn btn-social btn-flat btn-info btn-sm" id="ok"><i class='fa fa-check'></i> Simpan</button>
	</div>
</form>

