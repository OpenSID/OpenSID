<?php defined('BASEPATH') || exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">

<head>
	<title>Error</title>
	<link rel="stylesheet" type="text/css" href="<?= base_url('assets/bootstrap/css/bootstrap.min.css') ?>" />
	<link rel="stylesheet" type="text/css" href="<?= base_url('assets/css/font-awesome.min.css') ?>" />
	<link rel="stylesheet" type="text/css" href="<?= base_url('assets/css/AdminLTE.css') ?>" />
</head>

<body>
	<div class="container">
		<div class="error-page">
			<h2 class="headline text-danger"><?= $status_code ?></h2>

			<div class="error-content">
				<h3><i class="fa fa-warning text-danger"></i> <?= strip_tags($heading); ?></h3>
				<?php error_log(strip_tags($message)); ?>
				<p>
					<?= $message; ?>

					Versi <?= config_item('nama_aplikasi') . ' ' . AmbilVersi() ?>.

					<?php if ($status_code >= 500) : ?>
						<br><br>
						Harap laporkan masalah ini, agar kami dapat mencari solusinya dengan melampirkan file log terakhir atau saat masalah ini terjadi.
						Untuk memperoleh file log ikuti langkah berikut:
						<ol>
							<li>Masuk ke modul pengaturan</li>
							<li>Info sistem</li>
							<li>Logs</li>
							<li>Pilih log terakhir atau saat masalah ini terjadi</li>
							<li>Klik unduh</li>
						</ol>
						<br>
						Untuk sementara Anda dapat kembali ke halaman <a href="<?= APP_URL ?>">awal</a>.
					<?php endif ?>
				</p>
			</div>

		</div>
	</div>
</body>

</html>