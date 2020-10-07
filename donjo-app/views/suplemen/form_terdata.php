<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * File ini:
 *
 * View form terdata untuk modul suplemen
 *
 * donjo-app/views/suplemen/form_terdata.php,
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

<div class="content-wrapper">
	<section class="content-header">
		<h1>Formulir Penambahan Terdata</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?= site_url('suplemen')?>"> Data Suplemen</a></li>
			<li><a href="<?= site_url()?>suplemen/rincian/1/<?= $suplemen['id']?>"> Rincian Data Suplemen</a></li>
			<li class="active">Formulir Penambahan Terdata</li>
		</ol>
	</section>
	<section class="content">
		<div class="box box-info">
			<div class="box-header with-border">
				<a href="<?= site_url("suplemen"); ?>" class="btn btn-social btn-flat btn-primary btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Kembali Ke Daftar Suplemen"><i class="fa fa-arrow-circle-o-left"></i> Kembali Ke Daftar Suplemen</a>
				<a href="<?= site_url("suplemen/rincian/$suplemen[id]"); ?>" class="btn btn-social btn-flat btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-arrow-circle-left"></i> Kembali Ke Rincian Data Suplemen</a>
			</div>
			<?php $this->load->view('suplemen/rincian'); ?>
			<div class="box-body">
				<h5><b>Tambahkan Warga Terdata</b></h5>
				<hr>
				<form action="" id="main" name="main" method="POST" class="form-horizontal">
					<div class="form-group" >
						<label for="terdata" class="col-sm-3 control-label"><?= $list_sasaran['judul']; ?></label>
						<div class="col-sm-8">
							<select class="form-control select2 required" id="terdata" name="terdata" onchange="formAction('main')">
								<option selected="selected">-- Silakan Masukan <?= $list_sasaran['judul']; ?>  --</option>
								<?php foreach ($list_sasaran['data'] as $item): ?>
									<?php if (strlen($item["id"])>0): ?>
										<option value="<?= $item['id']?>" <?= selected($individu['id'], $item['id']); ?>>Nama : <?= $item['nama'] . ' - ' . $item['info']; ?></option>
									<?php endif; ?>
								<?php endforeach; ?>
							</select>
						</div>
					</div>
				</form>
				<div id="form-melengkapi-data-peserta">
					<form id="validasi" action="<?= "$form_action/$suplemen[id]"; ?>" method="POST" enctype="multipart/form-data" class="form-horizontal">
						<div class="form-group">
							<label class="col-sm-3 control-label"></label>
							<div class="col-sm-8">
								<input type="hidden" name="id_terdata" value="<?= $individu['id']?>" class="form-control input-sm required">
							</div>
						</div>
						<?php if ($individu): ?>
							<?php include("donjo-app/views/suplemen/konfirmasi_terdata.php"); ?>
						<?php endif; ?>
						<div class="form-group">
							<label class="col-sm-3 control-label" for="keterangan">Keterangan</label>
							<div class="col-sm-8">
								<textarea name="keterangan" id="keterangan" class="form-control input-sm" maxlength="100" placeholder="Keterangan" rows="5"></textarea>
							</div>
						</div>
					</form>
				</div>
			</div>
			<div class="box-footer">
				<button type="reset" class="btn btn-social btn-flat btn-danger btn-sm"><i class="fa fa-times"></i> Batal</button>
				<button type="submit" class="btn btn-social btn-flat btn-info btn-sm pull-right" onclick="$('#'+'validasi').submit();"><i class="fa fa-check"></i> Simpan</button>
			</div>
		</div>
	</section>
</div>

