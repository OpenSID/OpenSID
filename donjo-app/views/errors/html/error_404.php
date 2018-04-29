<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php
	$ci = new CI_Controller();
	$ci =& get_instance();
	$ci->load->helper('url');
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>404 Page Not Found</title>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css'); ?>" />
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/font-awesome.min.css'); ?>" />
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/errors.css'); ?>" />
</head>
<body>
<div class="container">
	<div class="error-page">
		<h2 class="headline text-yellow">404</h2>

		<div class="error-content">
			<h3><i class="fa fa-warning text-yellow"></i> <?php echo strip_tags($message); ?></h3>
			<p>
				Kami tidak dapat menemukan halaman yang Anda inginkan.
				Untuk sementara Anda dapat kembali ke halaman <a href="<?php echo base_url(); ?>">awal</a>.
			</p>
		</div>

	</div>
</div>
</body>
</html>