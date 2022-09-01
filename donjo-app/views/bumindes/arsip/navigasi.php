<div class="row">
	<div class="col-lg-4 col-xs-4">
		<div class="small-box bg-yellow">
			<div class="inner">
				<h3><?= $dokumen_desa['total'] ?></h3>
				<p><?= $dokumen_desa['title'] ?></p>
			</div>
			<div class="icon">
				<i class="ion ion-document"></i>
			</div>
			<a href="<?= site_url("{$this->controller}/clear/{$dokumen_desa['uri']}") ?>" class="small-box-footer">Lihat Detail <i class="fa fa-arrow-circle-right"></i></a>
		</div>
	</div>
	<div class="col-lg-$ col-xs-4">
		<div class="small-box bg-aqua">
			<div class="inner">
				<h3><?= $surat_masuk['total'] ?></h3>
				<p><?= $surat_masuk['title'] ?></p>
			</div>
			<div class="icon">
				<i class="ion ion-email"></i>
			</div>
			<a href="<?= site_url("{$this->controller}/clear/{$surat_masuk['uri']}") ?>" class="small-box-footer">Lihat Detail <i class="fa fa-arrow-circle-right"></i></a>
		</div>
	</div>
	<div class="col-lg-4 col-xs-4">
		<div class="small-box bg-blue">
			<div class="inner">
				<h3><?= $surat_keluar['total'] ?></h3>
				<p><?= $surat_keluar['title'] ?></p>
			</div>
			<div class="icon">
				<i class="ion ion-email"></i>
			</div>
			<a href="<?= site_url("{$this->controller}/clear/{$surat_keluar['uri']}") ?>" class="small-box-footer">Lihat Detail <i class="fa fa-arrow-circle-right"></i></a>
		</div>
	</div>
	<div class="col-lg-$ col-xs-4">
		<div class="small-box bg-purple">
			<div class="inner">
				<h3><?= $kependudukan['total'] ?></h3>
				<p><?= $kependudukan['title'] ?></p>
			</div>
			<div class="icon">
				<i class="ion ion-person"></i>
			</div>
			<a href="<?= site_url("{$this->controller}/clear/{$kependudukan['uri']}") ?>" class="small-box-footer">Lihat Detail <i class="fa fa-arrow-circle-right"></i></a>
		</div>
	</div>
	<div class="col-lg-4 col-xs-4">
		<div class="small-box bg-green">
			<div class="inner">
				<h3><?= $layanan_surat['total'] ?></h3>
				<p><?= $layanan_surat['title'] ?></p>
			</div>
			<div class="icon">
				<i class="ion ion-document-text"></i>
			</div>
			<a href="<?= site_url("{$this->controller}/clear/{$layanan_surat['uri']}") ?>" class="small-box-footer">Lihat Detail <i class="fa fa-arrow-circle-right"></i></a>
		</div>
	</div>
</div>