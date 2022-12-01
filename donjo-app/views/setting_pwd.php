<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title><?= $this->setting->login_title . ' ' . ucwords($this->setting->sebutan_desa) . (($header['nama_desa']) ? ' ' . $header['nama_desa'] : '') . get_dynamic_title_page_from_path(); ?></title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="robots" content="noindex">
		<link rel="stylesheet" href="<?= asset('css/login-style.css') ?>" media="screen">
		<link rel="stylesheet" href="<?= asset('css/login-form-elements.css') ?>" media="screen">
		<link rel="stylesheet" href="<?= asset('bootstrap/css/bootstrap.bar.css') ?>" media="screen">
		<?php if (is_file('desa/pengaturan/siteman/siteman.css')): ?>
			<link rel='Stylesheet' href="<?= base_url('desa/pengaturan/siteman/siteman.css') ?>">
		<?php endif; ?>
		<link rel="shortcut icon" href="<?= favico_desa() ?>"/>
		<style type="text/css">
			span.input-group-btn {
				position: absolute;
				display: inline-block;
				cursor: pointer;
				z-index: 2;
			}

			<?php if ($latar_login): ?>
				body.login {
					background-image: url('<?= base_url($latar_login) ?>');
				}
			<?php endif; ?>
		</style>
		<script src="<?= asset('bootstrap/js/jquery.min.js') ?>"></script>
		<script src="<?= asset('js/jquery.validate.min.js') ?>"></script>
		<script src="<?= asset('js/validasi.js') ?>"></script>
		<script src="<?= asset('js/localization/messages_id.js') ?>"></script>
		<?php require __DIR__ . '/head_tags.php'; ?>
	</head>
	<body class="login">
		<div class="top-content">
			<div class="inner-bg">
				<div class="container">
					<div class="row">
						<div class="col-sm-4 col-sm-offset-4 form-box">
							<div class="form-top">
								<a href="<?=site_url(); ?>"><img src="<?=gambar_desa($header['logo']); ?>" alt="<?=$header['nama_desa']?>" class="img-responsive" /></a>
								<div class="login-footer-top"><h1><?=ucwords($this->setting->sebutan_desa)?> <?=$header['nama_desa']?></h1>
									<h3>
										<br /><?=$header['alamat_kantor']?><br />Kodepos <?=$header['kode_pos']?>
										<br /><?=ucwords($this->setting->sebutan_kecamatan)?> <?=$header['nama_kecamatan']?><br /><?=ucwords($this->setting->sebutan_kabupaten)?> <?=$header['nama_kabupaten']?>
									</h3>
								</div>
								<div class="alert alert-danger">
									<?php if ($this->session->success == -1): ?>
										<?= $this->session->error_msg ?>
									<?php else: ?>
										Kata sandi anda tidak memenuhi syarat keamanan dan harus diganti
									<?php endif; ?>
								</div>
							</div>
							<div class="form-bottom">
								<form action="<?=site_url("user_setting/update_password/{$main['id']}")?>" method="POST" id="validasi" enctype="multipart/form-data">
									<div class="form-group">
										<div class="input-group">
											<input class="form-control input-sm required" type="password" name="pass_lama" autocomplete="off" placeholder="Kata Sandi Lama" ></input>
											<span class="input-group-btn">
												<button class="btn btn-default reveal" type="button"><i class="glyphicon glyphicon-eye-open"></i></button>
											</span>
										</div>
									</div>
									<div class="form-group">
										<div class="input-group">
											<input class="form-control input-sm required pwdLengthNist" type="password" id="pass_baru" name="pass_baru" autocomplete="off" placeholder="Kata Sandi Baru"></input>
											<span class="input-group-btn">
												<button class="btn btn-default reveal" type="button"><i class="glyphicon glyphicon-eye-open"></i></button>
											</span>
										</div>
									</div>
									<div class="form-group">
										<div class="input-group">
											<input class="form-control input-sm required pwdLengthNist" type="password" id="pass_baru1" name="pass_baru1" autocomplete="off" placeholder="Kata Sandi Baru (Ulangi)"></input>
											<span class="input-group-btn">
												<button class="btn btn-default reveal" type="button"><i class="glyphicon glyphicon-eye-open"></i></button>
											</span>
										</div>
									</div>
									<hr />
									<button type="submit" id="btnSubmit" class="btn btn-social btn-flat btn-info btn-sm"><i class='fa fa-check'></i>Simpan</button>
								</form>
								<hr/>
								<div class="login-footer-bottom"><a href="https://github.com/OpenSID/OpenSID" target="_blank">OpenSID</a> <?= AmbilVersi(); ?></div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>
<script src="<?= asset('bootstrap/js/jquery.min.js') ?>"></script>
<script src="<?= asset('js/jquery.validate.min.js') ?>"></script>
<script src="<?= asset('js/validasi.js') ?>"></script>
<script src="<?= asset('js/localization/messages_id.js') ?>"></script>
<script>
	$('document').ready(function() {
		$(".reveal").on('click',function() {
			var $pwd = $(".input-sm");
			if ($pwd.attr('type') === 'password') {
				$pwd.attr('type', 'text');
			} else {
				$pwd.attr('type', 'password');
			}
		});

		setTimeout(function() {
			$('#pass_baru1').rules('add', {
				equalTo: '#pass_baru'
			})
		}, 500);
	});
</script>
