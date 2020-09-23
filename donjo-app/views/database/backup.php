<?php defined('BASEPATH') OR exit('No direct script access allowed');
/*
 *  File ini:
 *
 * View untuk modul Database
 *
 * donjo-app/views/database/backup.php
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

<div class="tab-content">
	<div class="tab-pane <?= jecho($act_tab, 'database/backup', 'active'); ?>">
		<div class="box-header with-border">
			<h3 class="box-title"><strong>Backup Database SID</strong></h3>
		</div>
		<div class="box-body">
			<div class="row">
				<div class="col-sm-8">
					<form class="form-horizontal">
						<table class="table table-bordered">
							<tbody>
								<tr>
									<td class="col-sm-10"><b>Backup Seluruh Database SID (.sql)</b></td>
									<td class="col-sm-2">
										<a href="<?= site_url("database/exec_backup")?>" class="btn btn-social btn-flat btn-block btn-info btn-sm"><i class="fa fa-download"></i> Unduh Database</a>
									</td>
								</tr>
								<tr>
									<td class="col-sm-10"><b>Backup Seluruh Folder Desa SID (.zip)</b> </td>
									<td class="col-sm-2">
										<a href="<?= site_url("database/desa_backup"); ?>" class="btn btn-social btn-flat btn-block btn-info btn-sm"><i class="fa fa-download"></i> Unduh Folder Desa</a>
									</td>
								</tr>
							</tbody>
						</table>
					</form>
					<p>Proses Unduh akan mengunduh keseluruhan database SID anda.</p>
					<ul>
						<li> Usahakan untuk melakukan backup secara rutin dan terjadwal. </li>
						<li> Backup yang dihasilkan sebaiknya disimpan di komputer terpisah dari server SID. </li>
					</ul>
				</div>
			</div>
		</div>
	</div>
	<div class="box-header with-border">
		<h3 class="box-title"><strong>Restore Database SID</strong></h3>
	</div>
	<div class="box-body">
		<p>Backup yang dibuat dapat dipergunakan untuk mengembalikan database SID anda apabila ada masalah. Klik tombol Restore di bawah untuk menggantikan keseluruhan database SID dengan data hasil backup terdahulu.</p>
		<form action="<?= $form_action?>" method="post" enctype="multipart/form-data" class="form-horizontal">
			<?php if (strlen(@$_SESSION["SIAK"])>1): ?>
				<?=$_SESSION["SIAK"]?>
			<?php endif ?>
			<?php $_SESSION["SIAK"] = ""; ?>
			<p>Batas maksimal pengunggahan berkas <strong><?= max_upload() ?> MB.</strong></p>
			<p>Proses ini akan membutuhkan waktu beberapa menit, menyesuaikan dengan spesifikasi
			komputer server SID dan sambungan internet yang tersedia.</p>
			<p></p>
			<table class="table table-bordered table-hover" >
				<tbody>
					<tr>
						<td style="padding-top:20px;padding-bottom:10px;">
							<div class="form-group">
								<label for="file"  class="col-md-2 col-lg-3 control-label">Pilih File .Sql:</label>
								<div class="col-sm-12 col-md-5 col-lg-5">
									<div class="input-group input-group-sm">
										<input type="text" class="form-control" id="file_path" name="userfile">
										<input type="file" class="hidden" id="file" name="userfile" data-submit="restore" accept="application/sql">
										<span class="input-group-btn">
											<button type="button" class="btn btn-info btn-flat" id="file_browser"><i class="fa fa-search"></i> Browse</button>
										</span>
									</div>
								</div>
								<div class="col-sm-12 col-md-3 col-lg-2">
									<button type="submit" id="restore" class="btn btn-block btn-success btn-sm" disabled="disabled"><i class="fa fa-spin fa-refresh"></i>  Restore</button>
								</div>
							</div>
						</td>
					</tr>
				</tbody>
			</table>
		</form>
	</div>
</div>
