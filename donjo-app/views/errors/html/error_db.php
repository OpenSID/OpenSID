<?php defined('BASEPATH') || exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">

<head>
	<title>Database Error</title>
	<link rel="stylesheet" type="text/css" href="<?= asset('bootstrap/css/bootstrap.min.css') ?>" />
	<link rel="stylesheet" type="text/css" href="<?= asset('css/font-awesome.min.css') ?>" />
	<link rel="stylesheet" type="text/css" href="<?= asset('css/AdminLTE.css') ?>" />
</head>

<body>
	<div class="container">
		<div class="error-page">
			<h2 class="headline text-danger"> 500</h2>

			<div class="error-content">
				<h3><i class="fa fa-warning text-danger"></i> <?= strip_tags($heading); ?></h3>
				<p>
					Versi <?= config_item('nama_aplikasi') . ' ' . AmbilVersi() ?>.<br>

					<br>
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
				</p>
			</div>
			<?php if (ENVIRONMENT == 'development') : ?>
				<pre><?= $message ?></pre>
				<pre><?= strip_tags((new Exception())->getTraceAsString()) ?></pre>
			<?php endif ?>
		</div>
	</div>
</body>

</html>