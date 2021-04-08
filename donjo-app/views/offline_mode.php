<!DOCTYPE html>
<html>
<head>
	<title>Offline Mode - <?= ucwords($this->setting->sebutan_desa).' '.$main['nama_desa'] ?></title>
</head>
<body>
	<br/><br/><br/>
	<div align="center">
		<?php if ($main['logo']): ?>
			<img class="profile-user-img img-responsive img-circle" src="<?=gambar_desa($main['logo'])?>" alt="Logo">
		<?php else: ?>
			<img class="profile-user-img img-responsive img-circle" src="<?= base_url()?>assets/files/logo/home.png" alt="Logo">
		<?php endif ?>
		<p>
			Selamat datang di Halaman Situs Resmi <?= ucwords($this->setting->sebutan_desa).' '.$main['nama_desa'] ?><br/>
			Kami mohon maaf untuk sementara halaman tidak dapat di akses, dikarenakan sedang adanya perbaikan oleh tim terkait.
		</p>
		<p>
			Jika ada keperluan yang mendesak silakan langsung datang ke Kantor <?= ucwords($this->setting->sebutan_desa)?>.<br>
			Alamat : <?= $main['alamat_kantor'] ?><br>
			Email : <?= $main['email_desa'] ?><br>
			Telepon : <?= $main['telepon'] ?>
		</p>
		<p>
			<?= ucwords($pamong_kades['jabatan']).' '.$main['nama_desa'] ?>
			<br>
			<u><b><?= $main['nama_kepala_desa'] ?></b></u><br>
			NIP. <?= $main['nip_kepala_desa'] ?>
		</p>
	</div>
</body>
</html>