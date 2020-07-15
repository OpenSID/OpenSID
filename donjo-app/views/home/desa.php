<?php
/**
 * File ini:
 *
 * View untuk halaman dashboard Admin
 *
 * donjo-app/views/home/desa.php
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

<style type="text/css">
	.text-white {
		color: white;
	}
	.pengaturan {
		float: left;
		padding-left: 10px;
	}
	.modal-body
	{
		overflow-y: auto;
		height: 400px;
		margin-left: 5px;
		margin-right: 5px;
	}
</style>
<div class="content-wrapper">
	<section class='content-header'>
		<h1>Tentang OpenSID</h1>
		<ol class='breadcrumb'>
			<li><a href='<?=site_url()?>'><i class='fa fa-home'></i> Home</a></li>
			<li class='active'>Tentang OpenSID</li>
		</ol>
	</section>
	<section class='content' id="maincontent">
		<div class='row'>
			<?php if (isset($update_available) && $update_available): ?>
				<div class='col-md-12'>
					<div class="callout callout-success update">
						<h4><i class="fa fa-bullhorn"></i>&nbsp;&nbsp;Update Tersedia!</h4>
						<p align="justify">
							OpenSID <code><?=$latest_version ?></code> telah tersedia. Periksa catatan rilis untuk melihat daftar perubahan di versi ini. Sangat dianjurkan untuk update ke versi terkini, karena setiap rilis berisi perbaikan termasuk peningkatan keamanan data sejak versi yang anda gunakan saat ini <code><?=$current_version ?></code>. Petunjuk melakukan update dapat dilihat di <a href="https://github.com/OpenSID/OpenSID/wiki/Panduan-Update-OpenSID" target="_blank">sini</a>.
						</p>
						<button class="btn btn-social btn-flat btn-info btn-sm" data-toggle="modal" data-target="#modal-catatan-rilis">
							<i class="fa fa-book"></i> Catatan Rilis
						</button>
						<a href="https://github.com/OpenSID/OpenSID/archive/<?=$latest_version ?>.zip" class="btn btn-social btn-flat bg-navy btn-sm" style="text-decoration: none">
							<i class="fa fa-download none"></i> Unduh
						</a>
					</div>
					<div id="modal-catatan-rilis" class="modal fade" role="dialog">
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
									<h4 class="modal-title"><i class="fa fa-book"></i>&nbsp;&nbsp;Catatan Rilis OpenSID <small class="label label-success"><?=$latest_version ?></small></h4>
								</div>
								<div class="modal-body">
									<?=nl2br($release_body) ?>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-social btn-flat btn-danger btn-sm pull-left" data-dismiss="modal"><i class='fa fa-sign-out'></i> Tutup</button>
									<a href="https://github.com/OpenSID/OpenSID/archive/<?=$latest_version ?>.zip" class="btn btn-social btn-flat bg-navy btn-sm pull-right">
										<i class="fa fa-download"></i> Unduh
									</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			<?php endif; ?>
			<div class='col-md-6'>
				<div class='box box-info'>
					<div class='box-body'>
						<div class="row">
							<div class="col-lg-6 col-xs-6">
								<div class="small-box bg-purple">
									<div class="inner">
										<?php foreach ($dusun as $data): ?>
											<h3><?=$data['jumlah']?></h3>
										<?php endforeach; ?>
										<p>Wilayah Dusun</p>
									</div>
									<div class="icon">
										<i class="ion ion-location"></i>
									</div>
									<a href="<?=site_url('sid_core')?>" class="small-box-footer">Lihat Detail <i class="fa fa-arrow-circle-right"></i></a>
								</div>
							</div>
							<div class="col-lg-6 col-xs-6">
								<div class="small-box bg-aqua">
									<div class="inner">
										<?php foreach ($penduduk as $data): ?>
											<h3><?=$data['jumlah']?></h3>
										<?php endforeach; ?>
										<p>Penduduk</p>
									</div>
									<div class="icon">
										<i class="ion ion-person"></i>
									</div>
									<a href="<?=site_url('penduduk/clear')?>" class="small-box-footer">Lihat Detail <i class="fa fa-arrow-circle-right"></i></a>
								</div>
							</div>
							<div class="col-lg-6 col-xs-6">
								<div class="small-box bg-green">
									<div class="inner">
										<?php foreach ($keluarga as $data): ?>
											<h3><?=$data['jumlah']?></h3>
										<?php endforeach; ?>
										<p>Keluarga</p>
									</div>
									<div class="icon">
										<i class="ion ion-ios-people"></i>
									</div>
									<a href="<?=site_url('keluarga/clear')?>" class="small-box-footer">Lihat Detail <i class="fa fa-arrow-circle-right"></i></a>
								</div>
							</div>
							<div class="col-lg-6 col-xs-6">
								<div class="small-box bg-blue">
									<div class="inner">
										<h3><?=$jumlah_surat?></h3>
										<p>Surat Tercetak</p>
									</div>
									<div class="icon">
										<i class="ion-ios-paper"></i>
									</div>
									<a href="<?=site_url('keluar/clear')?>" class="small-box-footer">Lihat Detail <i class="fa fa-arrow-circle-right"></i></a>
								</div>
							</div>
							<div class="col-lg-4 col-xs-4">
								<div class="small-box bg-red">
									<div class="inner">
										<?php foreach ($kelompok as $data): ?>
											<h3><?=$data['jumlah']?></h3>
										<?php endforeach; ?>
										<p>Kelompok</p>
									</div>
									<div class="icon">
										<i class="ion ion-android-people"></i>
									</div>
									<a href="<?=site_url('kelompok/clear')?>" class="small-box-footer">Lihat Detail <i class="fa fa-arrow-circle-right"></i></a>
								</div>
							</div>
							<div class="col-lg-4 col-xs-4">
								<div class="small-box bg-gray">
									<div class="inner">
										<?php foreach ($rtm as $data): ?>
											<h3><?=$data['jumlah']?></h3>
										<?php endforeach; ?>
										<p>Rumah Tangga</p>
									</div>
									<div class="icon">
										<i class="ion ion-ios-home"></i>
									</div>
									<a href="<?=site_url('rtm/clear')?>" class="small-box-footer">Lihat Detail <i class="fa fa-arrow-circle-right"></i></a>
								</div>
							</div>
							<div class="col-lg-4 col-xs-4">
								<div class="small-box bg-yellow">
									<div class="inner">
										<h3><?=$bantuan['jumlah']?></h3>
										<p><?=$bantuan['nama']?></p>
									</div>
									<div class="icon">
										<i class="ion ion-ios-pie"></i>
									</div>
									<div class="small-box-footer">
										<?php if ($this->CI->cek_hak_akses('u')): ?>
											<a href="<?= site_url("{$this->controller}/dialog_pengaturan")?>" class="inner text-white pengaturan" title="Pengaturan Program Bantuan" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Pengaturan Program Bantuan"><i class="fa fa-gear"></i></a>
										<?php endif; ?>
										<a href="<?=site_url().$bantuan['link_detail']?>" class="inner text-white">Lihat Detail <i class="fa fa-arrow-circle-right"></i></a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class='col-md-6'>
				<div class='box box-info'>
					<?php $this->load->view('home/about.php');?>
				</div>
			</div>
		</div>
	</section>
</div>



