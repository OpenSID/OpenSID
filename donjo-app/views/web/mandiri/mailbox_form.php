<?php defined('BASEPATH') OR exit('No direct script access allowed');
/*
 *  File ini:
 *
 * View untuk modul Layanan Mandiri Web > Mail Box
 *
 * donjo-app/views/web/mandiri/mailbox_form.php
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
 * @copyright	Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright	Hak Cipta 2016 - 2020 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license	http://www.gnu.org/licenses/gpl.html	GPL V3
 * @link 	https://github.com/OpenSID/OpenSID
 */
?>

<section class="content no-padding" id="maincontent">
	<div class="row">
		<div class="col-md-12">
			<div class="box no-border">
				<div class="box-header">
					<a href="<?= site_url("mandiri_web/mandiri/1/3")?>" class="btn btn-social btn-flat btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"  title="Tambah Artikel">
						<i class="fa fa-arrow-circle-left "></i>Kembali ke halaman Kotak Pesan
					</a>
				</div>
				<div class="box-body">
					<form action="<?= $form_action ?>" class="form form-horizontal" id="validasi" method="POST">
						<div class="form-group">
							<label class="control-label col-sm-2" for="email">NIK</label>
							<div class="col-sm-9">
								<input type="text" class="form-control input-sm required" id="email" name="email" value="<?= $individu['nik'] ?>" readonly>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-2" for="owner">Nama Pengirim</label>
							<div class="col-sm-9">
								<input type="text" class="form-control input-sm required" id="owner" name="owner" value="<?= $individu['nama'] ?>" readonly>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-2" for="subjek">Subjek</label>
							<div class="col-sm-9">
								<input class="form-control input-sm required" id="subjek" name="subjek" value="<?php $subjek and print($subjek) ?>">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" for="komentar">Pesan</label>
							<div class="col-sm-9">
								<textarea class="form-control input-sm required" name="komentar" id="komentar"></textarea>
							</div>
						</div>

					</div>
					<div class='box-footer'>
						<div class='col-xs-12'>
							<button type="submit" class='btn btn-social btn-flat btn-info btn-sm pull-right confirm'><i class='fa fa-check'></i> Kirim Pesan</a>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</section>
<script>
	$(document).ready(function() {
		const sHeight = parseInt($("#komentar").get(0).scrollHeight) + 30;
		$('#komentar').attr('style', `height:${sHeight}px;`);
	})
</script>
