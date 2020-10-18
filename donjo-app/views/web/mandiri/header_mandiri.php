<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>
			<?=$this->setting->admin_title . ' ' . ucwords($this->setting->sebutan_desa) . ' '. $desa['nama_desa'] ?: '' . 'Layanan Mandiri';
			?>
		</title>
		<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
		<link rel="shortcut icon" href="<?= base_url()?><?= is_file(LOKASI_LOGO_DESA . 'favicon.ico') ? LOKASI_LOGO_DESA . 'favicon.ico' : 'favicon.ico' ?>"/>
		<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?= base_url()?>rss.xml" />

		<link type='text/css' href="<?= base_url()?>assets/front/css/first.css" rel='Stylesheet' />
		<!-- <link rel="stylesheet" type="text/css" href="<?= base_url()?>assets/bootstrap/css/bootstrap.bar.css"> -->

		<!-- Bootstrap 3.3.7 -->
		<link rel="stylesheet" href="<?= base_url()?>assets/bootstrap/css/bootstrap.min.css">
		<!-- Jquery UI -->
		<link rel="stylesheet" href="<?= base_url()?>assets/bootstrap/css/jquery-ui.min.css">
		<!-- Font Awesome -->
		<link rel="stylesheet" href="<?= base_url()?>assets/bootstrap/css/font-awesome.min.css">
		<!-- Ionicons -->
		<link rel="stylesheet" href="<?= base_url()?>assets/bootstrap/css/ionicons.min.css">
		<!-- DataTables -->
		<link rel="stylesheet" href="<?= base_url()?>assets/bootstrap/css/dataTables.bootstrap.min.css">
		<!-- bootstrap wysihtml5 - text editor -->
		<link rel="stylesheet" href="<?= base_url()?>assets/bootstrap/css/bootstrap3-wysihtml5.min.css">
		<!-- Select2 -->
		<link rel="stylesheet" href="<?= base_url()?>assets/bootstrap/css/select2.min.css">
		<!-- Bootstrap Color Picker -->
		<link rel="stylesheet" href="<?= base_url()?>assets/bootstrap/css/bootstrap-colorpicker.min.css">
		<!-- Bootstrap Date time Picker -->
		<link rel="stylesheet" href="<?= base_url()?>assets/bootstrap/css/bootstrap-datetimepicker.min.css">
		<!-- bootstrap datepicker -->
		<link rel="stylesheet" href="<?= base_url()?>assets/bootstrap/css/bootstrap-datepicker.min.css">
		<!-- Theme style -->
		<link rel="stylesheet" href="<?= base_url()?>assets/css/AdminLTE.min.css">
		<!-- AdminLTE Skins. -->
		<link rel="stylesheet" href="<?= base_url()?>assets/css/skins/_all-skins.min.css">
		<!-- Jquery Confirm -->
		<link rel="stylesheet" href="<?= base_url()?>assets/front/css/jquery-confirm.min.css">
		<!-- Style Admin Modification Css -->
		<link rel="stylesheet" href="<?= base_url()?>assets/css/admin-style.css">
		<!-- Jquery UI -->
		<link rel="stylesheet" href="<?= base_url()?>assets/bootstrap/css/jquery-ui.min.css">
		<?php if ($cek_anjungan): ?>
			<!-- Keyboard Default (Ganti dengan keyboard-dark.min.css untuk tampilan lain)-->
			<link rel="stylesheet" href="<?= base_url("assets/css/keyboard.min.css")?>">
			<link rel="stylesheet" href="<?= base_url("assets/front/css/mandiri-keyboard.css")?>">
		<?php endif; ?>

		<!-- Diperlukan untuk global automatic base_url oleh external js file -->
		<script type="text/javascript">
			const BASE_URL = "<?= base_url(); ?>";
			const SITE_URL = "<?= site_url(); ?>";
		</script>
		<!-- Diperlukan untuk script jquery khusus halaman -->
		<script src="<?= base_url() ?>assets/bootstrap/js/jquery.min.js"></script>

		<style type="text/css">
			#footer {
				padding: 3px 0;
				background: rgba(32,124,229,1);
				background: -moz-linear-gradient(top, rgba(32,124,229,1) 0%, rgba(75,182,232,1) 100%);
				background: -webkit-gradient(left top, left bottom, color-stop(0%, rgba(32,124,229,1)), color-stop(100%, rgba(75,182,232,1)));
				background: -webkit-linear-gradient(top, rgba(32,124,229,1) 0%, rgba(75,182,232,1) 100%);
				background: -o-linear-gradient(top, rgba(32,124,229,1) 0%, rgba(75,182,232,1) 100%);
				background: -ms-linear-gradient(top, rgba(32,124,229,1) 0%, rgba(75,182,232,1) 100%);
				background: linear-gradient(to bottom, rgba(32,124,229,1) 0%, rgba(75,182,232,1) 100%);
				filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#207ce5', endColorstr='#4bb6e8', GradientType=0 );
			}
			#footer-left {
				text-align: left;
					padding: 10px;
					width: 53%;
			}
			#footer-right {
					padding: 10px;
					width: 47%;
					float: right;
					margin-top: -50px;
			}
			#footer a {
					color: #fff;
			}

			#global-nav-right {
					float: right; list-style: none;    margin-left: 0;
			}
			#global-nav-right a {
					color: #444;
					display: block;
					font-family: "Roboto","Arial";
					font-size: 11px;
					font-weight: normal;
					height: 33px;
					line-height: 33px;
					text-decoration: none;
					text-shadow: 0 0 0 #444;
					text-transform: uppercase;
			}
			#global-nav-right > li {
					border-right: 0px solid #ccc;
					float: left;
					height: 33px;
					padding: 0px 5px;
					position: relative;
					margin-left: 0;
					position: relative;
			}
			#global-nav-right li:hover {
					background: rgba(0, 0, 0, 0) linear-gradient(to bottom, #6db3f2 0%, #54a3ee 50%, #3690f0 51%, #1e69de 100%) repeat scroll 0 0;
			}
			.main-header i.fa {margin-right: 10px;}
		</style>
		<?php $this->load->view('head_tags_front') ?>
	</head>

	<body class="skin-blue layout-top-nav">
		<div class="wrapper">
			<header class="main-header">
				<nav class="navbar navbar-default">
					<div class="container-fluid">
						<div class="navbar-header">
							<a class="navbar-brand" href="<?= site_url(); ?>">
								<img src="<?= gambar_desa($desa['logo']);?>" alt="<?= $desa['nama_desa']?>" width="30px" style="margin:-7px"/>
							</a>
							<p class="navbar-brand">
								<?= ucwords($this->setting->sebutan_desa).' '.$desa['nama_desa'] ?>
							</p>
						</div>
					</div>
				</nav>
			</header>
