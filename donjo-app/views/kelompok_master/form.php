<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * File ini:
 *
 * View untuk modul Kelompok > Kelompok Master
 *
 * donjo-app/views/kelompok_master/form.php
 *
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
		<h1>Pengelolaan Kategori Kelompok</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid'); ?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?= site_url('kelompok'); ?>"> Daftar Kelompok</a></li>
			<li><a href="<?= site_url('kelompok_master'); ?>"> Daftar Ketegori Kelompok</a></li>
			<li class="active">Pengelolaan Kategori Kelompok</li>
		</ol>
	</section>
	<section class="content">
		<div class="box box-info">
			<div class="box-header with-border">
				<a href="<?= site_url('kelompok_master'); ?>" class="btn btn-social btn-flat btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-arrow-circle-left"></i> Kembali Ke Kategori Kelompok</a>
			</div>
			<form id="validasi" action="<?= $form_action; ?>" method="POST" enctype="multipart/form-data" class="form-horizontal">
				<div class="box-body">
					<div class="form-group">
						<label class="col-sm-3 control-label" for="nama">Klasifikasi/Kategori Kelompok</label>
						<div class="col-sm-8">
							<input id="kelompok" class="form-control input-sm required" type="text" placeholder="Kategori Kelompok" name="kelompok" value="<?= $kelompok_master['kelompok']; ?>"></input>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label" for="Deskripsi">Deskripsi Kelompok</label>
						<div class="col-sm-8">
						 	<textarea name="deskripsi" class="form-control input-sm" placeholder="Deskripsi Kelompok" rows="3"><?= $kelompok_master['deskripsi']; ?></textarea>
						 </div>
					</div>
				</div>
				<div class="box-footer">
					<button type="reset" class="btn btn-social btn-flat btn-danger btn-sm"><i class="fa fa-times"></i> Batal</button>
					<button type="submit" class="btn btn-social btn-flat btn-info btn-sm pull-right"><i class="fa fa-check"></i> Simpan</button>
				</div>
			</form>
		</div>
	</section>
</div>
