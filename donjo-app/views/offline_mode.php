<!DOCTYPE html>
<html>
<head>
	<title>Offline Mode - Desa <?= $main['nama_desa'] ?></title>
</head>
<body>
	<br/><br/><br/>
	<div align="center">
		<?php if ($main['logo']): ?>
			<img class="profile-user-img img-responsive img-circle" src="<?=LogoDesa($main['logo'])?>" alt="Logo">
		<?php else: ?>
			<img class="profile-user-img img-responsive img-circle" src="<?= base_url()?>assets/files/logo/home.png" alt="Logo">
		<?php endif ?>
		<p>
			Selamat datang di Halaman Situs Resmi Desa <?= $main['nama_desa'] ?><br/>
			Kami mohon maaf untuk sementara halaman tidak dapat di akses, dikarenakan sedang adanya perbaikan oleh tim terkait.
		</p>
		<p>
			Jika ada keperluan yang mendesak silahkan langsung data ke Kantor Desa.<br>
			Alamat : <?= $main['alamat_kantor'] ?><br>
			Email : <?= $main['email_desa'] ?><br>
			Telepon : <?= $main['Telepon'] ?>
		</p>
		<p>
			Kades <?= $main['nama_desa'] ?>
			<br>
			<u><b><?= $main['nama_kepala_desa'] ?></b></u><br>
			NIP. <?= $main['nip_kepala_desa'] ?>
		</p>
	</div>
</body>
</html>