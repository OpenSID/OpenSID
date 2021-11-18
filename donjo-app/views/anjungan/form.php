<?php

defined('BASEPATH') || exit('No direct script access allowed');

/*
 * File ini:
 *
 * View form untuk modul Anjungan
 *
 * donjo-app/views/anjungan/form.php
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

<div class="content-wrapper">
	<section class="content-header">
		<h1>Form Anjungan Layanan Mandiri</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?= site_url('anjungan')?>"> Daftar Anjungan</a></li>
			<li class="active">Form Anjungan</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<div class="box box-info">
			<div class="box-header with-border">
				<a href="<?= site_url('anjungan')?>" class="btn btn-social btn-flat btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-arrow-circle-left"></i> Kembali Ke Daftar Anjungan</a>
			</div>
			<form id="validasi" action="<?= $form_action; ?>" method="POST" class="form-horizontal">
				<div class="box-body">
					<div class="form-group">
						<label class="col-sm-3 control-label" for="ip_address">IP Address</label>
						<div class="col-sm-7">
							<input class="form-control input-sm ip_address required" type="text" placeholder="IP address statis untuk anjungan" name="ip_address" value="<?= $anjungan['ip_address']?>">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label" for="ip_address">IP Address Printer</label>
						<div class="col-sm-7">
							<input class="form-control input-sm ip_address" type="text" placeholder="IP address statis untuk printer anjungan" name="printer_ip" value="<?= $anjungan['printer_ip']?>">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label" for="ip_address">Port Address Printer</label>
						<div class="col-sm-7">
							<input class="form-control input-sm" type="text" placeholder="Port address statis untuk printer anjungan" name="printer_port" value="<?= $anjungan['printer_port']?>">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label" for="ip_address">Mac Address</label>
						<div class="col-sm-7">
							<input class="form-control input-sm mac_address" type="text" placeholder="00:1B:44:11:3A:B7" name="mac_address" value="<?= $anjungan['mac_address']?>">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label" for="keterangan">Keterangan</label>
						<div class="col-sm-7">
							 <textarea name="keterangan" class="form-control input-sm" maxlength="300" placeholder="Keterangan" rows="3" style="resize:none;"><?= $anjungan['keterangan']?></textarea>
						 </div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label" for="keyboard">Keyboard Virtual</label>
						<div class="btn-group col-sm-7" data-toggle="buttons">
							<label id="sx1" class="btn btn-info btn-flat btn-sm col-xs-6 col-sm-5 col-lg-3 form-check-label <?= jecho($anjungan['keyboard'], '1', 'active') ?>">
								<input type="radio" name="keyboard" class="form-check-input" type="radio" value="1" <?= jecho($anjungan['keyboard'], '1', 'checked') ?>> Aktif
							</label>
							<label id="sx2" class="btn btn-info btn-flat btn-sm col-xs-6 col-sm-5 col-lg-3 form-check-label <?= jecho($anjungan['keyboard'] != '1', true, 'active') ?>">
								<input type="radio" name="keyboard" class="form-check-input" type="radio" value="0" <?= jecho($anjungan['keyboard'] != '1', true, 'checked') ?>> Tidak Aktif
							</label>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label" for="status">Status</label>
						<div class="btn-group col-sm-7" data-toggle="buttons">
							<label id="sx3" class="btn btn-info btn-flat btn-sm col-xs-6 col-sm-5 col-lg-3 form-check-label <?= jecho($anjungan['status'], '1', 'active') ?>">
								<input type="radio" name="status" class="form-check-input" type="radio" value="1" <?= jecho($anjungan['status'], '1', 'checked') ?>> Aktif
							</label>
							<label id="sx4" class="btn btn-info btn-flat btn-sm col-xs-6 col-sm-5 col-lg-3 form-check-label <?= jecho($anjungan['status'] != '1', true, 'active') ?>">
								<input type="radio" name="status" class="form-check-input" type="radio" value="0" <?= jecho($anjungan['status'] != '1', true, 'checked') ?>> Tidak Aktif
							</label>
						</div>
					</div>
				</div>
				<div class="box-footer">
					<button type="reset" class="btn btn-social btn-flat btn-danger btn-sm" onclick="reset_form($(this).val());"><i class="fa fa-times"></i> Batal</button>
					<button type="submit" class="btn btn-social btn-flat btn-info btn-sm pull-right"><i class="fa fa-check"></i> Simpan</button>
				</div>
			</form>
		</div>
	</section>
</div>
<script type="text/javascript">
	function reset_form()
	{
		<?php if ($anjungan['keyboard'] == '1'): ?>
			$("#sx1").addClass('active');
			$("#sx2").removeClass("active");
		<?php else: ?>
			$("#sx2").addClass('active');
			$("#sx1").removeClass("active");
		<?php endif ?>
		<?php if ($anjungan['status'] == '1'): ?>
			$("#sx3").addClass('active');
			$("#sx4").removeClass("active");
		<?php else: ?>
			$("#sx4").addClass('active');
			$("#sx3").removeClass("active");
		<?php endif ?>
	};
</script>
