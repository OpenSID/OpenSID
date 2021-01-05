<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title><?=$this->setting->admin_title . ' ' . ucwords($this->setting->sebutan_desa) . (($desa['nama_desa']) ? ' ' . $desa['nama_desa']: '') . 'Layanan Mandiri'; ?></title>
	<!-- Tell the browser to be responsive to screen width -->
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<?php if (is_file(LOKASI_LOGO_DESA . "favicon.ico")): ?>
		<link rel="shortcut icon" href="<?= base_url()?><?= LOKASI_LOGO_DESA?>favicon.ico" />
	<?php else: ?>
		<link rel="shortcut icon" href="<?= base_url()?>favicon.ico" />
	<?php endif; ?>
	<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?= base_url()?>rss.xml" />
	<!-- Bootstrap 3.3.7 -->
	<link rel="stylesheet" href="<?= base_url()?>assets/bootstrap/css/bootstrap.min.css">
	<!-- Font Awesome -->
	<link rel="stylesheet" href="<?= base_url()?>assets/bootstrap/css/font-awesome.min.css">
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
	<!-- Style Mandiri Modification CSS -->
	<link rel="stylesheet" href="<?= base_url()?>assets/css/mandiri-style.css">
	<!-- Jquery Confirm -->
	<link rel="stylesheet" href="<?= base_url()?>assets/front/css/jquery-confirm.min.css">
	<!-- Jquery UI -->
	<link rel="stylesheet" href="<?= base_url()?>assets/bootstrap/css/jquery-ui.min.css">
	<!-- Diperlukan untuk script jquery khusus halaman -->
	<script src="<?= base_url() ?>assets/bootstrap/js/jquery.min.js"></script>
	<!-- Diperlukan untuk global automatic base_url oleh external js file -->
	<script type="text/javascript">
		const BASE_URL = "<?= base_url(); ?>";
		const SITE_URL = "<?= site_url(); ?>";
	</script>
	<?php if ($cek_anjungan): ?>
		<!-- Keyboard Default (Ganti dengan keyboard-dark.min.css untuk tampilan lain)-->
		<link rel="stylesheet" href="<?= base_url("assets/css/keyboard.min.css")?>">
		<link rel="stylesheet" href="<?= base_url("assets/front/css/mandiri-keyboard.css")?>">
	<?php endif; ?>

	<?php $this->load->view('head_tags'); ?>
</head>

<body class="hold-transition skin-blue fixed layout-top-nav">
	<div class="wrapper">
		<header class="main-header">
			<nav class="navbar navbar-static-top">
				<div class="container">
					<div class="navbar-header">
						<a class="navbar-brand" href="<?= site_url(); ?>">
							<img src="<?= gambar_desa($desa['logo']); ?>" class="logo-brand" alt="<?= $desa['nama_desa']?>"/>
						</a>
						<div class="navbar-brand">
							<?= ucwords($this->setting->sebutan_desa . ' ' .$desa['nama_desa']); ?>
						</div>
						<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false">
							<i class="fa fa-bars"></i>
						</button>
					</div>

					<div class="collapse navbar-collapse pull-left" id="navbar-collapse">
						<ul class="nav navbar-nav">
							<li><a href="<?= site_url('layanan-mandiri/profil'); ?>">Profil</a></li>
							<li><a href="<?= site_url('layanan-mandiri/arsip-surat'); ?>">Surat</a></li>
							<li><a href="<?= site_url('layanan-mandiri/pesan-masuk'); ?>">Pesan</a></li>
							<li><a href="<?= site_url('layanan-mandiri/bantuan'); ?>">Bantuan</a></li>
						</ul>
					</div>
					<div class="navbar-custom-menu">
						<ul class="nav navbar-nav">
							<li>
								<a href="#" data-toggle="control-sidebar" title="Panduan Layanan"><i class="fa fa-question-circle fa-lg"></i></a>
							</li>

							<li>
								<a href="<?= site_url('layanan-mandiri/pesan-masuk'); ?>" title="Pesan Masuk">
								<i class="fa fa-envelope-o"></i>
									<span class="label label-danger" id="b_pesan" style="display: none;"></span>
								</a>
							</li>

							<li class="dropdown user user-menu">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown">
									<img class="user-image" src="<?= AmbilFoto($this->session->foto)?>" alt="Foto">
									<span class="hidden-xs"><?= $this->session->nama; ?></span>
								</a>
								<ul class="dropdown-menu">
									<li class="user-header">
										<img class="img-circle" src="<?= AmbilFoto($this->session->foto); ?>" alt="Foto Penduduk">
										<p><?= $this->session->nama; ?>
										<small><b>NIK : <?= $this->session->nik; ?> | No.KK : <?= $this->session->no_kk; ?></b></small>
									</li>
									<li class="user-footer">
										<div class="pull-left">
											<a href="<?= site_url('layanan-mandiri/profil'); ?>" class="btn btn-default">Profil</a>
										</div>
										<div class="pull-right">
											<a href="<?= site_url('layanan-mandiri/keluar'); ?>" class="btn btn-default">Keluar</a>
										</div>
									</li>
								</ul>
							</li>
						</ul>
					</div>
				</div>
			</nav>
		</header>

		<div class="content-wrapper" style="background: #ecf0f5;">
			<div class="container">
				<section class="content-header fixed">
					<div class="row hidden-xs">
						<a href="<?= site_url('layanan-mandiri/profil'); ?>">
							<div class="col-md-3 col-sm-6 col-xs-12">
								<div class="info-box bg-aqua">
									<span class="info-box-icon"><i class="fa fa-user-o"></i></span>
									<div class="info-box-content">
										<span class="info-box-text">Profil</span>
									</div>
								</div>
							</div>
						</a>
						<a href="<?= site_url('layanan-mandiri/arsip-surat'); ?>">
							<div class="col-md-3 col-sm-6 col-xs-12">
								<div class="info-box bg-green">
									<span class="info-box-icon"><i class="fa fa-file-word-o"></i></span>
									<div class="info-box-content">
										<span class="info-box-text">Surat</span>
									</div>
								</div>
							</div>
						</a>
						<a href="<?= site_url('layanan-mandiri/pesan-masuk'); ?>">
							<div class="col-md-3 col-sm-6 col-xs-12">
								<div class="info-box bg-yellow">
									<span class="info-box-icon"><i class="fa fa-envelope-o"></i></span>
									<div class="info-box-content">
										<span class="info-box-text">Pesan</span>
									</div>
								</div>
							</div>
						</a>
						<a href="<?= site_url('layanan-mandiri/bantuan'); ?>">
							<div class="col-md-3 col-sm-6 col-xs-12">
								<div class="info-box bg-red">
									<span class="info-box-icon"><i class="fa fa-handshake-o"></i></span>
									<div class="info-box-content">
										<span class="info-box-text">Bantuan</span>
									</div>
								</div>
							</div>
						</a>
					</div>
				</section>

				<section class="content">
					<div class="row">
						<div class="col-md-3">
							<div class="box box-solid">
								<div class="box-body box-line">
									<img class="img-circle" src="<?= AmbilFoto($this->session->foto)?>" alt="Foto" width="100%">
								</div>
								<div class="box-body">
									<a href="<?= ($this->session->lg == 1) ? '#' : site_url('layanan-mandiri/cetak-biodata'); ?>" class="btn btn-block btn-social bg-green" target="_blank" rel="noopener noreferrer">
										<i class="fa fa-print"></i> Cetak Biodata
									</a>
									<a href="<?= ($this->session->lg == 1) ? '#' : site_url('layanan-mandiri/cetak-kk'); ?>" class="btn btn-block btn-social bg-aqua" target="_blank" rel="noopener noreferrer">
										<i class="fa fa-print"></i> Cetak KK
									</a>
									<a href="<?= site_url('layanan-mandiri/ganti-pin'); ?>" class="btn btn-block btn-social bg-navy">
										<i class="fa fa-key"></i> Ganti PIN
									</a>
									<a href="<?= site_url('layanan-mandiri/keluar'); ?>" class="btn btn-block btn-social bg-red">
										<i class="fa fa-sign-out"></i> Keluar
									</a>
								</div>
							</div>
						</div>
						<div class="col-md-9">
							<?php
								$this->load->view("layanan_mandiri/$konten");

								// TODO: Gunakan $this->session->flashdata untuk notif sekali panggil
								if ($this->session->lg == 1 && $this->uri->segment(2) != "ganti-pin"):

									$data = [
										'pesan' => "Selamat datang pengguna layanan mandiri <b> " . ucwords($this->setting->sebutan_desa . " " . $desa[nama_desa]) . " </b>, <br>Untuk keamanan akun anda, silahkan ganti <b>PIN</b> anda terlebih dahulu sebelum melanjutkan menggunakan layanan mandiri.",
										'aksi' => site_url('layanan-mandiri/ganti-pin')
									];

									$this->load->view('layanan_mandiri/notif', $data);
								endif;

								$data = $this->session->flashdata('notif');

								if ($data['status'] == 1):
									$this->load->view('layanan_mandiri/notif', $data);
								endif;
							?>
						</div>
					</div>
				</section>
			</div>
		</div>

		<footer class="main-footer">
			<div class="container">
				<div class="pull-right hidden-xs">
					<b>Versi</b> <?= AmbilVersi()?>
				</div>
				<strong>Aplikasi <a href="https://github.com/OpenSID/OpenSID" target="_blank"> OpenSID</a>, dikembangkan oleh <a href="https://www.facebook.com/groups/OpenSID/" target="_blank">Komunitas OpenSID</a>.</strong>
			</div>
		</footer>
	</div>
	<!-- jQuery 3 -->
	<script src="<?= base_url()?>assets/bootstrap/js/jquery.min.js"></script>
	<!-- Jquery UI -->
	<script src="<?= base_url()?>assets/bootstrap/js/jquery-ui.min.js"></script>
	<script src="<?= base_url()?>assets/bootstrap/js/jquery.ui.autocomplete.scroll.min.js"></script>

	<script src="<?= base_url()?>assets/bootstrap/js/moment.min.js"></script>
	<!-- Bootstrap 3.3.7 -->
	<script src="<?= base_url()?>assets/bootstrap/js/bootstrap.min.js"></script>
	<!-- Select2 -->
	<script src="<?= base_url()?>assets/bootstrap/js/select2.full.min.js"></script>
	<!-- DataTables -->
	<script src="<?= base_url()?>assets/bootstrap/js/jquery.dataTables.min.js"></script>
	<script src="<?= base_url()?>assets/bootstrap/js/dataTables.bootstrap.min.js"></script>
	<!-- bootstrap color picker -->
	<script src="<?= base_url()?>assets/bootstrap/js/bootstrap-colorpicker.min.js"></script>
	<!-- bootstrap Date time picker -->
	<script src="<?= base_url()?>assets/bootstrap/js/bootstrap-datetimepicker.min.js"></script>
	<script src="<?= base_url()?>assets/bootstrap/js/id.js"></script>
	<!-- bootstrap Date picker -->
	<script src="<?= base_url()?>assets/bootstrap/js/bootstrap-datepicker.min.js"></script>
	<script src="<?= base_url()?>assets/bootstrap/js/bootstrap-datepicker.id.min.js"></script>
	<!-- Bootstrap WYSIHTML5 -->
	<script src="<?= base_url()?>assets/bootstrap/js/bootstrap3-wysihtml5.all.min.js"></script>
	<!-- Slimscroll -->
	<script src="<?= base_url()?>assets/bootstrap/js/jquery.slimscroll.min.js"></script>
	<!-- FastClick -->
	<script src="<?= base_url()?>assets/bootstrap/js/fastclick.js"></script>
	<!-- AdminLTE App -->
	<script src="<?= base_url()?>assets/js/adminlte.min.js"></script>
	<script src="<?= base_url()?>assets/front/js/jquery.overlay.min.js"></script>
	<script src="<?= base_url()?>assets/front/js/jquery-confirm.min.js"></script>

	<!-- Validasi js -->
	<?php $this->load->view('global/validasi_form'); ?>
	<!-- Numeral js -->
	<script src="<?= base_url()?>assets/js/numeral.min.js"></script>
	<!-- Khusus modul layanan mandiri -->
	<script src="<?= base_url() ?>assets/front/js/mandiri.js"></script>

	<?php if ($cek_anjungan): ?>
		<!-- keyboard widget script -->
		<script src="<?= base_url("assets/js/jquery.keyboard.min.js")?>"></script>
		<script src="<?= base_url("assets/js/jquery.mousewheel.min.js")?>"></script>
		<script src="<?= base_url("assets/js/jquery.keyboard.extension-all.min.js")?>"></script>
		<script src="<?= base_url("assets/front/js/mandiri-keyboard.js")?>"></script>
	<?php endif; ?>
	<script type="text/javascript">
		$(window).on('load', function() {
			$('#notif').modal('show');
		});

		$('document').ready(function() {
			setTimeout(function() {
				refresh_badge($("#b_pesan"), "<?= site_url('notif_web/inbox'); ?>");
				refresh_badge($("#b_surat"), "<?= site_url('notif_web/surat_perlu_perhatian'); ?>");
			}, 500);
		});
	</script>
</body>
</html>
