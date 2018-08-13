<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>404 Page Not Found</title>
	<link rel="stylesheet" type="text/css" href="/assets/bootstrap/css/bootstrap.min.css" />
	<link rel="stylesheet" type="text/css" href="/assets/css/font-awesome.min.css" />
	<link rel="stylesheet" type="text/css" href="/assets/css/AdminLTE.css" />
</head>
<body>
<div class="container">
	<div class="error-page">
		<h2 class="headline text-yellow">404</h2>

		<div class="error-content">
			<h3><i class="fa fa-warning text-yellow"></i> <?= strip_tags($message); ?></h3>
			<p>
				Kami tidak dapat menemukan halaman yang Anda inginkan.
				Untuk sementara Anda dapat kembali ke halaman <a href="<?= APP_URL ?>">awal</a>.
			</p>
		</div>

	</div>
</div>
</body>
</html>