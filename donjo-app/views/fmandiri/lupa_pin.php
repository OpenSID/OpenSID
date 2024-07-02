<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<title><?= $this->setting->login_title . ' ' . ucwords($this->setting->sebutan_desa) . (($header['nama_desa']) ? ' ' . $header['nama_desa'] : '') . get_dynamic_title_page_from_path(); ?>
	</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="robots" content="noindex">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<link rel="shortcut icon" href="<?= favico_desa() ?>" />
	<link rel="stylesheet" href="<?= asset('css/login-style.css') ?>" media="screen">
	<link rel="stylesheet" href="<?= asset('css/login-form-elements.css') ?>" media="screen">
	<link rel="stylesheet" href="<?= asset('css/siteman_mandiri.css') ?>" media="screen">
	<link rel="stylesheet" href="<?= asset('bootstrap/css/bootstrap.bar.css') ?>" media="screen">
	<?php if (is_file('desa/pengaturan/siteman/siteman_mandiri.css')) : ?>
		<link rel='Stylesheet' href="<?= base_url('desa/pengaturan/siteman/siteman_mandiri.css') ?>">
	<?php endif; ?>
	<link rel="stylesheet" href="<?= asset('css/mandiri_video.css') ?>">

	<!-- Google Font -->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
	<script src="<?= asset('bootstrap/js/jquery.min.js') ?>"></script>

	<?php if ($cek_anjungan) : ?>
		<!-- Keyboard Default (Ganti dengan keyboard-dark.min.css untuk tampilan lain)-->
		<link rel="stylesheet" href="<?= asset('css/keyboard.min.css') ?>">
		<link rel="stylesheet" href="<?= asset('front/css/mandiri-keyboard.css') ?>">
	<?php endif; ?>

	<?php $this->load->view('head_tags'); ?>
	<style type="text/css">
        body.login {
            background-image: url('<?= default_file(LATAR_KEHADIRAN, DEFAULT_LATAR_KEHADIRAN) ?>');
        }
    </style>
</head>

<body class="login">
	<div class="top-content">
		<div class="inner-bg">
			<div class="container">
				<div class="row">
					<div class="col-sm-6 col-sm-offset-4 form-box">
						<div class="form-top">
							<a href="<?= site_url(); ?>"><img src="<?= gambar_desa($header['logo']); ?>" alt="Lambang Desa" class="img-responsive" /></a>
							<div class="login-footer-top">
								<h1>LAYANAN MANDIRI<br />
									<?= ucwords($this->setting->sebutan_desa) ?> <?= $header['nama_desa'] ?></h1>
								<h3>
									<br /><?= ucwords($this->setting->sebutan_kecamatan) ?> <?= $header['nama_kecamatan'] ?>
									<br /><?= ucwords($this->setting->sebutan_kabupaten) ?> <?= $header['nama_kabupaten'] ?>
									<br /><?= $header['alamat_kantor'] ?>
									<br />Kodepos <?= $header['kode_pos'] ?>
									<br /><br />Silakan hubungi operator desa untuk mendapatkan kode PIN anda.
									<?php if (! $cek_anjungan) : ?>
										<br /><br /><br />IP Address: <?= $this->input->ip_address(); ?>
									<?php else : ?>
										<br /><br /><br />IP Address : <?= $cek_anjungan['ip_address'] ?>
										<br />Mac Address : <?= $cek_anjungan['mac_address'] ?>
										<br />Anjungan Mandiri
										<?= jecho($cek_anjungan['keyboard'] == 1, true, ' | Virtual Keyboard : Aktif'); ?>
									<?php endif; ?>
								</h3>
							</div>
						</div>
						<div class="form-bottom">
							<form id="validasi" action="<?= $form_action; ?>" method="post" class="login-form">
								<?php if ($this->session->mandiri_wait == 1) : ?>
									<div class="error login-footer-top">
										<p id="countdown" style="color:red; text-transform:uppercase"></p>
									</div>
								<?php else : ?>
									<?php if ($lupa_pin = $this->session->flashdata('lupa_pin')) : ?>
										<div class="alert alert-success" role="alert">
											<p><?= $lupa_pin['pesan']; ?></p>
										</div>
									<?php else : ?>
										<div class="form-group form-login">
											<input type="text" autocomplete="off" value="" class="form-control required nik <?= jecho($cek_anjungan['keyboard'] == 1, true, 'kbvnumber'); ?>" name="nik" placeholder=" NIK" maxlength="16">
										</div>
										<div class="form-group">
											<button type="submit" class="btn btn-block bg-green" name="send" value="telegram"><b>TELEGRAM</b></button>
										</div>
										<div class="form-group">
											<button type="submit" class="btn btn-block bg-green" name="send" value="email"><b>Email</b></button>
										</div>
									<?php endif; ?>
								<?php endif; ?>
								<div class="form-group">
									<div class="row">
										<div class="col-sm-6">
											<a href="<?= site_url('layanan-mandiri/masuk') ?>">
												<button type="button" class="btn btn-block bg-green"><b>MASUK</b></button>
											</a>
										</div>
										<div class="col-sm-6">
											<a href="<?= site_url('layanan-mandiri/masuk-ektp') ?>">
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
	<script src="<?= asset('bootstrap/js/jquery.min.js') ?>"></script>
	<!-- Bootstrap 3.3.7 -->
	<script src="<?= asset('bootstrap/js/bootstrap.min.js') ?>"></script>
	<!-- SlimScroll -->
	<script src="<?= asset('bootstrap/js/jquery.slimscroll.min.js') ?>"></script>
	<!-- FastClick -->
	<script src="<?= asset('bootstrap/js/fastclick.js') ?>"></script>
	<!-- AdminLTE App -->
	<script src="<?= asset('js/adminlte.min.js') ?>"></script>
	<!-- Validasi -->
	<script src="<?= asset('js/jquery.validate.min.js') ?>"></script>
	<script src="<?= asset('js/validasi.js') ?>"></script>
	<script src="<?= asset('js/localization/messages_id.js') ?>"></script>

	<?php if ($cek_anjungan) : ?>
		<!-- keyboard widget css & script -->
		<script src="<?= asset('js/jquery.keyboard.min.js') ?>"></script>
		<script src="<?= asset('js/jquery.mousewheel.min.js') ?>"></script>
		<script src="<?= asset('js/jquery.keyboard.extension-all.min.js') ?>"></script>
		<script src="<?= asset('front/js/mandiri-keyboard.js') ?>"></script>
	<?php endif; ?>

	<script type="text/javascript">
		$('document').ready(function() {
			if ($('#countdown').length) {
				start_countdown();
			}

			window.setTimeout(function() {
				$("#notif").fadeTo(500, 0).slideUp(500, function() {
					$(this).remove();
				});
			}, 5000);
		});

		function start_countdown() {
			var times = eval(<?= json_encode($this->session->mandiri_timeout, JSON_THROW_ON_ERROR) ?>) - eval(<?= json_encode(time(), JSON_THROW_ON_ERROR) ?>);
			var menit = Math.floor(times / 60);
			var detik = times % 60;

			timer = setInterval(function() {
				detik--;
				if (detik <= 0 && menit >= 1) {
					detik = 60;
					menit--;
				}
				if (menit <= 0 && detik <= 0) {
					clearInterval(timer);
					location.reload();
				} else {
					document.getElementById("countdown").innerHTML = "<b>Gagal 3 kali silakan coba kembali dalam " + menit + " MENIT " + detik + " DETIK </b>";
				}
			}, 1000);
		}
	</script>
</body>

</html>