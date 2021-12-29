<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>
		<?=$this->setting->login_title
            . ' ' . ucwords($this->setting->sebutan_desa)
            . (($header['nama_desa']) ? ' ' . $header['nama_desa'] : '')
            . get_dynamic_title_page_from_path();
        ?>
	</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="robots" content="noindex">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<?php if (is_file(LOKASI_LOGO_DESA . 'favicon.ico')): ?>
		<link rel="shortcut icon" href="<?= base_url()?><?= LOKASI_LOGO_DESA?>favicon.ico" />
	<?php else: ?>
		<link rel="shortcut icon" href="<?= base_url()?>favicon.ico" />
	<?php endif; ?>
	<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?= base_url()?>rss.xml" />
	<link rel="stylesheet" href="<?= base_url() ?>assets/css/login-style.css" media="screen" type="text/css"/>
	<link rel="stylesheet" href="<?= base_url() ?>assets/css/login-form-elements.css" media="screen" type="text/css"/>
	<link rel="stylesheet" href="<?= base_url() ?>assets/css/siteman_mandiri.css" media="screen" type="text/css"/>
	<link rel="stylesheet" href="<?= base_url() ?>assets/bootstrap/css/bootstrap.bar.css" media="screen" type="text/css"/>
	<?php if (is_file('desa/pengaturan/siteman/siteman_mandiri.css')): ?>
		<link type='text/css' href="<?= base_url()?>desa/pengaturan/siteman/siteman_mandiri.css" rel='Stylesheet' />
	<?php endif; ?>
	<link rel="stylesheet" type="text/css" href="<?= base_url('assets/css/mandiri_video.css')?>">

	<!-- Google Font -->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
	<script src="<?= base_url()?>assets/bootstrap/js/jquery.min.js"></script>

	<?php if ($cek_anjungan): ?>
		<!-- Keyboard Default (Ganti dengan keyboard-dark.min.css untuk tampilan lain)-->
		<link rel="stylesheet" href="<?= base_url('assets/css/keyboard.min.css')?>">
		<link rel="stylesheet" href="<?= base_url('assets/front/css/mandiri-keyboard.css')?>">
	<?php endif; ?>

	<?php $this->load->view('head_tags'); ?>
	<?php if ($latar_login_mandiri): ?>
		<style type="text/css">
			body.login {
				background: url('<?= base_url($latar_login_mandiri); ?>');
			}
		</style>
	<?php endif; ?>
</head>

<body class="login">
	<div class="top-content">
		<div class="inner-bg">
			<div class="container">
				<div class="row">
					<div class="col-sm-6 col-sm-offset-4 form-box">
						<div class="form-top">
							<a href="<?=site_url(); ?>"><img src="<?= gambar_desa($header['logo']); ?>" alt="Lambang Desa" class="img-responsive"/></a>
							<div class="login-footer-top">
								<h1>LAYANAN MANDIRI<br/>
								<?=ucwords($this->setting->sebutan_desa)?> <?=$header['nama_desa']?></h1>
								<h3>
									<br/><?=ucwords($this->setting->sebutan_kecamatan)?> <?=$header['nama_kecamatan']?>
									<br/><?=ucwords($this->setting->sebutan_kabupaten)?> <?=$header['nama_kabupaten']?>
									<br/><?=$header['alamat_kantor']?>
									<br />Kodepos <?=$header['kode_pos']?>
									<br/><br/>Silakan hubungi operator desa untuk mendapatkan kode PIN anda.
									<?php if (! $cek_anjungan): ?>
										<br/><br/><br/>IP Address: <?= $this->input->ip_address(); ?>
									<?php else: ?>
										<br/><br/><br/>IP Address :	<?= $cek_anjungan['ip_address']?>
										<br/>Mac Address :	<?= $cek_anjungan['mac_address']?>
										<br/>Anjungan Mandiri
										<?= jecho($cek_anjungan['keyboard'] == 1, true, ' | Virtual Keyboard : Aktif'); ?>
									<?php endif; ?>
								</h3>
							</div>
						</div>
						<div class="form-bottom">
							<form id="validasi" action="<?= $form_action; ?>" method="post" class="login-form" >
								<?php if ($notif = $this->session->flashdata('notif')): ?>
									<div class="alert alert-success" role="alert">
										<p><?= $notif['pesan']; ?></p>
									</div>
									<hr/>
								<?php else: ?>
								<div class="form-group form-login">
									<input type="text" autocomplete="off" value="5201142005716996" class="form-control required <?= jecho($cek_anjungan['keyboard'] == 1, true, 'kbvnumber'); ?>" name="nik" placeholder=" NIK">
								</div>
								<div class="form-group">
									<button type="submit" class="btn btn-block bg-green"><b>TELEGRAM</b></button>
								</div>
								<?php endif; ?>
								<div class="form-group">
									<div class="row">
										<div class="col-sm-6">
											<a href="<?= site_url('layanan-mandiri/masuk')?>" >
												<button type="button" class="btn btn-block bg-green"><b>MASUK</b></button>
											</a>
										</div>
										<div class="col-sm-6">
											<a href="<?= site_url('layanan-mandiri/masuk-ektp')?>" >
												<button type="button" class="btn btn-block bg-green"><b>MASUK EKTP</b></button>
											</a>
										</div>
								</div>
							</form>
							<div class="login-footer-bottom">
								<a href="https://github.com/OpenSID/OpenSID" class="content-color-secondary" rel="noopener noreferrer" target="_blank">OpenSID <?= AmbilVersi() ?></a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- jQuery 3 -->
	<script src="<?= base_url()?>assets/bootstrap/js/jquery.min.js"></script>
	<!-- Bootstrap 3.3.7 -->
	<script src="<?= base_url()?>assets/bootstrap/js/bootstrap.min.js"></script>
	<!-- SlimScroll -->
	<script src="<?= base_url()?>assets/bootstrap/js/jquery.slimscroll.min.js"></script>
	<!-- FastClick -->
	<script src="<?= base_url()?>assets/bootstrap/js/fastclick.js"></script>
	<!-- AdminLTE App -->
	<script src="<?= base_url()?>assets/js/adminlte.min.js"></script>
	<!-- Validasi -->
	<script src="<?= base_url()?>assets/js/jquery.validate.min.js"></script>
	<script src="<?= base_url()?>assets/js/validasi.js"></script>
	<script src="<?= base_url()?>assets/js/localization/messages_id.js"></script>

	<?php if ($cek_anjungan): ?>
		<!-- keyboard widget css & script -->
		<script src="<?= base_url('assets/js/jquery.keyboard.min.js')?>"></script>
		<script src="<?= base_url('assets/js/jquery.mousewheel.min.js')?>"></script>
		<script src="<?= base_url('assets/js/jquery.keyboard.extension-all.min.js')?>"></script>
		<script src="<?= base_url('assets/front/js/mandiri-keyboard.js')?>"></script>
	<?php endif; ?>
</body>
</html>
