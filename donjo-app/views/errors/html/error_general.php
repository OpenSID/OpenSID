<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php
	$CI =& get_instance();
	if( ! isset($CI))
	{
		$CI = new CI_Controller();
	}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>Error</title>
	<link rel="stylesheet" type="text/css" href="<?= $CI->config->base_url() ?>assets/bootstrap/css/bootstrap.min.css" />
	<link rel="stylesheet" type="text/css" href="<?= $CI->config->base_url() ?>assets/css/font-awesome.min.css" />
	<link rel="stylesheet" type="text/css" href="<?= $CI->config->base_url() ?>assets/css/AdminLTE.css" />
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

				Versi OpenSID <?= AmbilVersi()?>. PHP versi <?= preg_split('/-/', phpversion())[0]?>.

				<?php if ($status_code >= 500): ?>
					<br><br>
					Harap laporkan masalah ini, agar kami dapat mencarikan solusinya.
					Untuk sementara Anda dapat kembali ke halaman <a href="<?= APP_URL ?>">awal</a>.
				<?php endif ?>
			</p>
		</div>

	</div>
</div>
</body>
</html>
