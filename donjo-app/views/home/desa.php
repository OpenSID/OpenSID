<!-- Perubahan script coding untuk bisa menampilkan SID Home dalam bentuk tampilan bootstrap (AdminLTE)  -->
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
							OpenSID versi <code><?=$latest_version ?></code> telah tersedia, versi yang anda gunakan saat ini <code><?=$current_version ?></code> mungkin mengandung celah keamanan yang pelu ditutup. Silahakan baca catatan rilis dan segera update sekarang juga.
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
									<a href="<?=site_url('sid_core')?>" class="small-box-footer">Lihat Detail  <i class="fa fa-arrow-circle-right"></i></a>
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
									<a href="<?=site_url('penduduk/clear')?>" class="small-box-footer">Lihat Detail  <i class="fa fa-arrow-circle-right"></i></a>
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
									<a href="<?=site_url('keluarga/clear')?>" class="small-box-footer">Lihat Detail  <i class="fa fa-arrow-circle-right"></i></a>
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
									<a href="<?=site_url('keluar/clear')?>" class="small-box-footer">Lihat Detail  <i class="fa fa-arrow-circle-right"></i></a>
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
									<a href="<?=site_url('kelompok/clear')?>" class="small-box-footer">Lihat Detail  <i class="fa fa-arrow-circle-right"></i></a>
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
									<a href="<?=site_url('rtm/clear')?>" class="small-box-footer">Lihat Detail  <i class="fa fa-arrow-circle-right"></i></a>
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
										<a href="<?=site_url().$bantuan['link_detail']?>" class="inner text-white">Lihat Detail  <i class="fa fa-arrow-circle-right"></i></a>
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



