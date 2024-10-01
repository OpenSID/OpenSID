<!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8">
	<title><?= $this->setting->login_title . ' ' . ucwords($this->setting->sebutan_desa) . (($header['nama_desa']) ? ' ' . $header['nama_desa'] : '') . get_dynamic_title_page_from_path() ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="robots" content="noindex">
	<link rel="stylesheet" href="<?= asset('css/login-style.css') ?>" media="screen">
	<link rel="stylesheet" href="<?= asset('css/login-form-elements.css') ?>" media="screen">
	<link rel="stylesheet" href="<?= asset('bootstrap/css/bootstrap.bar.css') ?>" media="screen">
	<?php if (is_file('desa/pengaturan/siteman/siteman.css')) : ?>
		<link rel='Stylesheet' href="<?= base_url('desa/pengaturan/siteman/siteman.css') ?>">
	<?php endif ?>
	<link rel="shortcut icon" href="<?= favico_desa() ?>" />
	<style type="text/css">
		body.login {
			background-image: url('<?= $latar_login ?>');
		}
	</style>
	<script src="<?= asset('bootstrap/js/jquery.min.js') ?>"></script>
	<script src="<?= asset('js/jquery.validate.min.js') ?>"></script>
	<script src="<?= asset('js/validasi.js') ?>"></script>
	<script src="<?= asset('js/localization/messages_id.js') ?>"></script>
	<?php require __DIR__ . '/head_tags.php' ?>
</head>

<body class="login">
	<div class="top-content">
		<div class="inner-bg">
			<div class="container">
				<div class="row">
					<div class="col-sm-4 col-sm-offset-4 form-box">
						<div class="form-top">
							<a href="<?= site_url() ?>">
								<img src="<?= gambar_desa($header['logo']) ?>" alt="<?= $header['nama_desa'] ?>" class="img-responsive" />
								<?php if (setting('tte')) : ?>
									<img src="<?= $logo_bsre ?>" alt="Bsre" class="img-responsive" style="width: 185px;" />
								<?php endif ?>
							</a>
							<div class="login-footer-top">
								<h1><?= ucwords($this->setting->sebutan_desa) ?> <?= $header['nama_desa'] ?></h1>
								<h3>
									<br /><?= $header['alamat_kantor'] ?><br />Kodepos <?= $header['kode_pos'] ?>
									<br /><?= ucwords($this->setting->sebutan_kecamatan) ?> <?= $header['nama_kecamatan'] ?><br /><?= ucwords($this->setting->sebutan_kabupaten) ?> <?= $header['nama_kabupaten'] ?>
								</h3>
							</div>
							<?php if ($notif = $this->session->flashdata('notif')) : ?>
								<div class="alert alert-info">
									<p><?= $notif ?></p>
								</div>
							<?php endif ?>
						</div>
						<div class="form-bottom">
							<form id="validasi" class="login-form" action="<?= $form_action ?>" method="post">
								<?php if ($this->session->flashdata('time_block')) : ?>
									<div class="error login-footer-top">
										<p id="countdown" style="color:red; text-transform:uppercase"></p>
									</div>
								<?php else : ?>
									<div class="form-group">
										<input name="username" type="text" autocomplete="off" placeholder="Nama pengguna" <?php jecho($this->session->siteman_wait, 1, 'disabled') ?> class="form-username form-control required" maxlength="100">
									</div>
									<div class="form-group">
										<input id="password" name="password" type="password" autocomplete="off" placeholder="Kata sandi" <?php jecho($this->session->siteman_wait, 1, 'disabled') ?> class="form-username form-control required" maxlength="100">
									</div>
									<?php if (setting('google_recaptcha')) : ?>
										<div id="recaptcha"></div>
									<?php endif ?>
									<div class="form-group">
										<input type="checkbox" id="checkbox" class="form-checkbox"> Tampilkan kata sandi
										<a href="<?= site_url('siteman/lupa_sandi') ?>" class="btn" role="button" aria-pressed="true">Lupa Kata Sandi?</a>
									</div>
									<hr />
									<div class="form-group">
										<button type="submit" class="btn">Masuk</button>
									</div>
									<?php if ($attempts_error = $this->session->flashdata('attempts_error')) : ?>
										<div class="error">
											<p style="color:red; text-transform:uppercase"><?= $attempts_error ?> </p>
										</div>
									<?php endif ?>
								<?php endif ?>
							</form>
							<hr />
							<div class="login-footer-bottom"><a href="https://github.com/OpenSID/OpenSID" target="_blank">OpenSID</a> <?= AmbilVersi() ?></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<script>
		function start_countdown() {
			var times = eval(<?= $this->session->flashdata('time_block') + config_item('lockout_time') ?>) - eval(<?= json_encode(time(), JSON_THROW_ON_ERROR) ?>);
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
					document.getElementById("countdown").innerHTML = "<b>User telah diblokir karena gagal login 3 kali silakan coba kembali dalam " + menit + " MENIT " + detik + " DETIK </b>";
				}
			}, 1000)
		}

		$('document').ready(function() {
			var pass = $("#password");
			$('#checkbox').click(function() {
				if (pass.attr('type') === "password") {
					pass.attr('type', 'text');
				} else {
					pass.attr('type', 'password')
				}
			});

			if ($('#countdown').length) {
				start_countdown();
			}

		});
	</script>
	<script src="https://www.google.com/recaptcha/api.js?onload=recaptchaCallback&render=explicit&hl=id" async defer></script>
	<script type="text/javascript">
		function onErrorCallback(errorCode) {
			if (errorCode === undefined) {
				var formData = {
					'key': 'value'
				};

				$.ajax({
					url: '<?= site_url('siteman/matikan_captcha') ?>',
					type: 'POST',
					data: formData,
					success: function(response) {
						console.log('Berhasil mematikan captcha');
						window.location.href = '<?= site_url('siteman') ?>';
					},
					error: function(xhr, status, error) {
						console.error('Error Captcha');
					}
				});
			}
		}

		var recaptchaCallback = function() {
			try {
				grecaptcha.render("recaptcha", {
					sitekey: '<?= setting('google_recaptcha_site_key') ?>',
					callback: function() {
						console.log('reCAPTCHA callback');
					},
					'error-callback': onErrorCallback
				});
			} catch (error) {
				console.log('Error rendering reCAPTCHA:', error);
			}
		}
	</script>
</body>

</html>