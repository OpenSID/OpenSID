<?php

defined('BASEPATH') || exit('No direct script access allowed');

/*
 * File ini:
 *
 * View modul Layanan Mandiri > Surat > Permohonan Surat
 *
 * donjo-app/views/fmandiri/prmohonan_surat.php
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

<div class="box box-solid" id="wrapper-mandiri">
	<div class="box-header with-border bg-green">
		<h4 class="box-title">Surat</h4>
	</div>
	<div class="box-body box-line">
		<div class="box-body permohonan-surat">

			<form id="validasi" action="<?= $form_action?>" method="POST" target="_blank" class="form-surat form-horizontal">
				<input type="hidden" id="url_surat" name="url_surat" value="<?= $url ?>">
				<input type="hidden" id="url_remote" name="url_remote" value="<?= site_url('surat/nomor_surat_duplikat')?>">
				<div class="form-group cari_nik">
					<label for="nik"  class="col-sm-3 control-label">NIK / Nama <?= $pemohon?></label>
					<div class="col-sm-6 col-lg-4">
					<select class="form-control input-sm readonly-permohonan readonly-periksa" id="nik" name="nik" style ="width:100%;">
						<?php if ($individu): ?>
							<option value="<?= $individu['id']?>" selected><?= $individu['nik'] . ' - ' . $individu['tag_id_card'] . ' - ' . $individu['nama']?></option>
						<?php endif; ?>
					</select>
					</div>
				</div>
				<?php if ($individu): ?>
					<?php include 'donjo-app/views/surat/form/konfirmasi_pemohon.php'; ?>
				<?php	endif; ?>
				<div class="row jar_form">
					<label for="nomor" class="col-sm-3"></label>
					<div class="col-sm-8">
						<input class="required" type="hidden" name="nik" value="<?= $individu['id']?>">
					</div>
				</div>
				<?php include 'donjo-app/views/surat/form/nomor_surat.php'; ?>
				<?php foreach ($kode_isian as $item): ?>
                <div class="form-group">
                    <label for="<?= $item->nama ?>" class="col-sm-3 control-label"><?= $item->nama ?></label>
                    <div class="col-sm-8">
                        <textarea name="<?= underscore($item->nama, true, true) ?>" class="form-control input-sm required"
                            placeholder="<?= $item->deskripsi ?>"></textarea>
                    </div>
                </div>
           	 	<?php endforeach ?>
				<?php include 'donjo-app/views/surat/form/tgl_berlaku.php'; ?>
				<?php include 'donjo-app/views/surat/form/_pamong.php'; ?>
				<?php include 'donjo-app/views/surat/form/tampil_foto.php'; ?>
			</form>

			<textarea id="isian_form" hidden="hidden"><?= $isian_form; ?></textarea>
		</div>
	</div>
	<?php include 'donjo-app/views/surat/form/tombol_cetak.php'; ?>
</div>

<script type="text/javascript">
	$(document).ready(function() {
		// Di form surat ubah isian admin menjadi disabled
		$("#wrapper-mandiri .readonly-permohonan").attr('disabled', true);
		$("#wrapper-mandiri form#validasi").removeAttr('target');
		$("#wrapper-mandiri .tdk-permohonan textarea").removeClass('required');
		$("#wrapper-mandiri .tdk-permohonan select").removeClass('required');
		$("#wrapper-mandiri .tdk-permohonan input").removeClass('required');
	});

	$(document).ready(function() {
		// Di form surat ubah isian admin menjadi disabled
		$("#periksa-permohonan .readonly-periksa").attr('disabled', true);

		if ($('#isian_form').val()) {
			setTimeout(function() {isi_form();}, 100);
		}
	});

	function isi_form() {
		var isian_form = JSON.parse($('#isian_form').val(), function(key, value) {

			if (key) {
				var elem = $('*[name=' + key + ']');
				elem.val(value);
				elem.change();
				// Kalau isian hidden, akan ada isian lain untuk menampilkan datanya
				if (elem.is(":hidden")) {
					var show = $('#' + key + '_show');
					show.val(value);
					show.change();
				}
			}
		});
	}
</script>
