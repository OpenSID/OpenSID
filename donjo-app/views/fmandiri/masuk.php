<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<title>
		<?= $this->setting->login_title . ' ' . ucwords($this->setting->sebutan_desa) . (($header['nama_desa']) ? ' ' . $header['nama_desa'] : '') . get_dynamic_title_page_from_path() ?>
	</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="robots" content="noindex">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<link rel="shortcut icon" href="<?= favico_desa() ?>" />
	<link rel="stylesheet" href="<?= asset('css/login-style.css') ?>" media="screen">
	<link rel="stylesheet" href="<?= asset('css/login-form-elements.css') ?>" media="screen">
	<link rel="stylesheet" href="<?= asset('css/daftar-form-elements.css') ?>" media="screen">
	<link rel="stylesheet" href="<?= asset('css/siteman_mandiri.css') ?>" media="screen">
	<link rel="stylesheet" href="<?= asset('bootstrap/css/bootstrap.bar.css') ?>" media="screen">
	<!-- bootstrap datetimepicker -->
	<link rel="stylesheet" href="<?= asset('bootstrap/css/bootstrap-datetimepicker.min.css') ?>">
	<?php if (is_file('desa/pengaturan/siteman/siteman_mandiri.css')) : ?>
		<link rel='Stylesheet' href="<?= base_url('desa/pengaturan/siteman/siteman_mandiri.css') ?>">
	<?php endif; ?>
	<link rel="stylesheet" href="<?= asset('css/mandiri_video.css') ?>">
	<!-- Font Awesome -->
	<link rel="stylesheet" href="<?= asset('bootstrap/css/font-awesome.min.css') ?>">
	<!-- Google Font -->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
	<script src="<?= asset('bootstrap/js/jquery.min.js') ?>"></script>

	<?php if ($cek_anjungan) : ?>
		<!-- Keyboard Default (Ganti dengan keyboard-dark.min.css untuk tampilan lain)-->
		<link rel="stylesheet" href="<?= asset('css/keyboard.min.css') ?>">
		<link rel="stylesheet" href="<?= asset('front/css/mandiri-keyboard.css') ?>">
	<?php endif; ?>

	<?php $this->load->view('head_tags') ?>
	<?php if ($latar_login_mandiri) : ?>
		<style type="text/css">
			body.login {
				background: url('<?= base_url($latar_login_mandiri) ?>');
			}
		</style>
	<?php endif; ?>

	<!-- Form Wizard - smartWizard -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/smartwizard@5/dist/css/smart_wizard_all.min.css">
</head>

<?php if ($this->setting->tampilan_anjungan == 1 && ! empty($this->setting->tampilan_anjungan_slider)) : ?>
	<div id="sliderv" class="video-internal" style="display: none;">
		<div id="myCarousel" class="carousel slide" data-ride="carousel">
			<div class="carousel-inner">
				<?php foreach ($daftar_album as $key => $data) : ?>
					<div class="item <?= jecho($key, 0, 'active') ?> ">
						<img src="<?= AmbilGaleri($data['gambar'], 'sedang') ?>" alt="Los Angeles" style="width:100%;">
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
<?php endif; ?>

<?php if ($this->setting->tampilan_anjungan == 2 && ! empty($this->setting->tampilan_anjungan_video)) : ?>
	<div class="video-internal" id="videov" style="display: none;">
		<video loop <?= jecho($this->setting->tampilan_anjungan_audio, 0, 'muted') ?> poster="<?= base_url($latar_login_mandiri) ?>" class="video-internal-bg" id="videona">
			<source src="<?= $this->setting->tampilan_anjungan_video; ?>" type="video/mp4">
		</video>
	</div>
<?php endif; ?>

<body class="login">
	<div class="top-content">
		<div class="inner-bg">
			<div class="container">
				<div class="row">
					<div class="col-sm-6 col-sm-offset-4 form-box">
						<div class="form-top">
							<a href="<?= site_url() ?>"><img src="<?= gambar_desa($header['logo']) ?>" alt="Lambang Desa" class="img-responsive" /></a>
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
										<br /><br /><br />IP Address: <?= $this->input->ip_address() ?>
									<?php else : ?>
										<br /><br /><br />IP Address : <?= $cek_anjungan['ip_address'] ?>
										<br />Mac Address : <?= $cek_anjungan['mac_address'] ?>
										<br />Anjungan Mandiri
										<?= jecho($cek_anjungan['keyboard'] == 1, true, ' | Virtual Keyboard : Aktif') ?>
									<?php endif; ?>
								</h3>
							</div>
						</div>
						<div class="form-bottom">
							<?php if ($this->session->mandiri_wait == 1) : ?>
								<div class="error login-footer-top">
									<p id="countdown" style="color:red; text-transform:uppercase"></p>
								</div>
							<?php else : ?>
								<?php $data = $this->session->flashdata('notif') ?>

								<?php if ($this->session->daftar_verifikasi) : ?>
									<!-- View Pendaftaran -->
									<div class="login-form">
										<?php $this->load->view(MANDIRI . '/pendaftaran-verifikasi') ?>
									</div>
								<?php else : ?>
									<?php if ($this->session->daftar) : ?>
										<!-- View Pendaftaran -->
										<form id="validasi" action="<?= $form_action; ?>" method="post" class="login-form" enctype="multipart/form-data">
											<?php $this->load->view(MANDIRI . '/pendaftaran') ?>
										</form>
									<?php else : ?>
										<form id="validasi" action="<?= $form_action; ?>" method="post" class="login-form">
											<?php if (! $this->session->login_ektp) : ?>

												<?php if ($this->session->mandiri_try < 4) : ?>
													<div class="callout callout-danger" id="notif">
														<p>NIK atau PIN salah.<br />Kesempatan mencoba <?= ($this->session->mandiri_try - 1) ?> kali lagi.</p>
													</div>
												<?php endif; ?>
												<?php if ($this->session->aktif == true) : ?>
													<div class="callout callout-danger" id="notif">
														<p>Mohon Maaf, Akun Layanan Mandiri dapat digunakan setelah mendapatkan persetujuan dan proses verifikasi dari operator.</p>
													</div>
												<?php endif; ?>

												<div class="form-group form-login">
													<input type="text" autocomplete="off" class="form-control required <?= jecho($cek_anjungan['keyboard'] == 1, true, 'kbvnumber') ?>" name="nik" placeholder=" NIK">
												</div>
												<div class="form-group form-login">
													<input type="password" autocomplete="off" class="form-control required <?= jecho($cek_anjungan['keyboard'] == 1, true, 'kbvnumber') ?>" name="pin" placeholder="PIN" id="pin">
												</div>
												<div class="form-group">
													<center><input type="checkbox" id="checkbox" style="display: initial;"> Tampilkan PIN</center>
												</div>
												<div class="form-group">
													<button type="submit" class="btn btn-block bg-green"><b>MASUK</b></button>
												</div>
												<div class="form-group">
													<a href="<?= site_url('layanan-mandiri/masuk-ektp') ?>">
														<button type="button" class="btn btn-block bg-green"><b>MASUK DENGAN E-KTP</b></button>
													</a>
												</div>
												<?php if ($this->setting->tampilkan_pendaftaran) : ?>
													<div class="form-group">
														<a href="<?= site_url('layanan-mandiri/daftar') ?>">
															<button type="button" class="btn btn-block bg-green"><b>DAFTAR</b></button>
														</a>
													</div>
												<?php endif; ?>
											<?php else : ?>

												<?php if ($this->session->mandiri_try < 4) : ?>
													<div class="callout callout-danger" id="notif">
														<p>PIN ATAU ID E-KTP salah.<br />Kesempatan mencoba <?= ($this->session->mandiri_try - 1) ?> kali lagi.</p>
													</div>
												<?php endif; ?>
												<div class="login-footer-top">
													<?php if ($cek_anjungan) : ?>
														Tempelkan e-KTP Pada Card Reader
													<?php endif; ?>
													<div class="thumbnail">
														<img src="<?= asset('images/camera-scan.gif') ?>" alt="scanner" class="center" style="width:30%">
													</div>
												</div>
												<div class="form-group form-login" style="<?= jecho($cek_anjungan == 0 || ENVIRONMENT == 'development', false, 'width: 0; overflow: hidden;') ?>">
													<input name="tag" id="tag" autocomplete="off" placeholder="Tempelkan e-KTP Pada Card Reader" class="form-control required number" type="password" onkeypress="if (event.keyCode == 13){$('#'+'validasi').attr('action', '<?= $form_action; ?>');$('#'+'validasi').submit();}">
												</div>
												<?php if (! $cek_anjungan) : ?>
													<div class="form-group form-login">
														<input type="password" class="form-control required number" name="pin" placeholder="Masukan PIN" id="pin" autocomplete="off">
													</div>
													<div class="form-group">
														<button type="submit" class="btn btn-block bg-green"><b>MASUK</b></button>
													</div>
												<?php endif; ?>
												<div class="form-group">
													<a href="<?= site_url('layanan-mandiri/masuk') ?>">
														<button type="button" class="btn btn-block bg-green"><b>MASUK DENGAN NIK</b></button>
													</a>
												</div>
												<?php if ($this->setting->tampilkan_pendaftaran) : ?>
													<div class="form-group">
														<a href="<?= site_url('layanan-mandiri/daftar') ?>">
															<button type="button" class="btn btn-block bg-green"><b>DAFTAR</b></button>
														</a>
													</div>
												<?php endif; ?>
											<?php endif; ?>
											<div class="form-group">
												<a href="<?= site_url('layanan-mandiri/lupa-pin') ?>">
													<button type="button" class="btn btn-block bg-green"><b>LUPA PIN</b></button>
												</a>
											</div>
										</form>
									<?php endif; ?>
								<?php endif; ?>
							<?php endif; ?>
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
	<!-- bootstrap Moment -->
	<script src="<?= asset('bootstrap/js/moment.min.js') ?>"></script>
	<script src="<?= asset('bootstrap/js/moment-timezone.js') ?>"></script>
	<script src="<?= asset('bootstrap/js/moment-timezone-with-data.js') ?>"></script>
	<!-- bootstrap Date time picker -->
	<script src="<?= asset('bootstrap/js/bootstrap-datetimepicker.min.js') ?>"></script>
	<script src="<?= asset('bootstrap/js/id.js') ?>"></script>
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

	<!-- Form Wizard - jquery.smartWizard -->
	<script src="https://cdn.jsdelivr.net/npm/smartwizard@5/dist/js/jquery.smartWizard.min.js" type="text/javascript"></script>

	<?php if ($cek_anjungan) : ?>
		<!-- keyboard widget css & script -->
		<script src="<?= asset('js/jquery.keyboard.min.js') ?>"></script>
		<script src="<?= asset('js/jquery.mousewheel.min.js') ?>"></script>
		<script src="<?= asset('js/jquery.keyboard.extension-all.min.js') ?>"></script>
		<script src="<?= asset('front/js/mandiri-keyboard.js') ?>"></script>
	<?php endif; ?>
	<script type="text/javascript">
		$('document').ready(function() {

			var ektp = '<?= $this->session->login_ektp ?>';
			var anjungan = '<?= $cek_anjungan ?>';
			var tampil_anjungan = '<?= $this->setting->tampilan_anjungan ?>';
			var tampil_anjungan_video = '<?= $this->setting->tampilan_anjungan_video ?>';
			var tampil_anjungan_slider = '<?= $this->setting->tampilan_anjungan_slider ?>';
			var IDLE_TIMEOUT = '<?= $this->setting->tampilan_anjungan_waktu ?>';

			$('#daftar_tgl_lahir').datetimepicker({
				format: 'DD-MM-YYYY',
				locale: 'id',
				maxDate: 'now',
			});
			var addOrRemoveRequiredAttribute = function() {
				var tgllahir = parseInt($('#daftar_tgl_lahir').val().substring(6, 10));
			};
			$("#daftar_tgl_lahir").on('change keyup paste click keydown', addOrRemoveRequiredAttribute);

			if (ektp) {
				if (anjungan) {
					$('#tag').focus();
				} else {
					$('#pin').focus();
				}
			}

			var pass = $("#pin");
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

			window.setTimeout(function() {
				$("#notif").fadeTo(500, 0).slideUp(500, function() {
					$(this).remove();
				});
			}, 5000);

			var videona = document.getElementById("videona");
			videona.pause();
			var _idleSecondsCounter = 0;
			document.onclick = function() {
				_idleSecondsCounter = 0;
			};
			document.onmousemove = function() {
				_idleSecondsCounter = 0;
			};
			document.onkeypress = function() {
				_idleSecondsCounter = 0;
			};
			window.setInterval(CheckIdleTime, 500);

			function CheckIdleTime() {
				_idleSecondsCounter++;
				var video = document.getElementById("videov");
				var slider = document.getElementById("sliderv");

				if (_idleSecondsCounter >= IDLE_TIMEOUT) {
					if (tampil_anjungan == 2 && tampil_anjungan_video) {
						videona.play();
						video.style.display = "block";
					} else if (tampil_anjungan == 1 && tampil_anjungan_slider) {
						slider.style.display = "block";
					}
				} else {
					if (tampil_anjungan == 2 && tampil_anjungan_video) {
						videona.pause();
						video.style.display = "none";
					} else if (tampil_anjungan == 1 && tampil_anjungan_slider) {
						slider.style.display = "none";
					}
				}
			}
		});

		function start_countdown() {
			var times = eval(<?= json_encode($this->session->mandiri_timeout) ?>) - eval(<?= json_encode(time()) ?>);
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

		function show(elem) {
			if ($(elem).hasClass('fa-eye')) {
				$(".pin").attr('type', 'password');
				$(".fa-eye").addClass('fa-eye-slash');
				$(".fa-eye").removeClass('fa-eye');
			} else {
				$(".pin").attr('type', 'text');
				$(".fa-eye-slash").addClass('fa-eye');
				$(".fa-eye-slash").removeClass('fa-eye-slash');
			}
		}

		<?php if ($this->session->flashdata('info_pendaftaran')) : ?>
			$(window).on('load', function() {
				$('#informasi').modal('show');
			});
		<?php endif; ?>
		<?php if ($this->session->flashdata('daftar_notif_telegram')) : ?>
			$(window).on('load', function() {
				$('#notif_telegram').modal('show');
			});
		<?php endif; ?>
	</script>
</body>

</html>