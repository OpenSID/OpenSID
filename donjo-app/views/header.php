<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>
			<?=$this->setting->admin_title
				. ' ' . ucwords($this->setting->sebutan_desa)
				. (($desa['nama_desa']) ? ' ' . $desa['nama_desa']:  '')
				. get_dynamic_title_page_from_path();
			?>
		</title>
		<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
		<?php if (is_file(LOKASI_LOGO_DESA . "favicon.ico")): ?>
			<link rel="shortcut icon" href="<?= base_url()?><?= LOKASI_LOGO_DESA?>favicon.ico" />
		<?php else: ?>
			<link rel="shortcut icon" href="<?= base_url()?>favicon.ico" />
		<?php endif; ?>
		<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?= base_url()?>rss.xml" />

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
		<!-- Style Admin Modification Css -->
		<link rel="stylesheet" href="<?= base_url()?>assets/css/admin-style.css">
		<!-- OpenStreetMap Css -->
		<link rel="stylesheet" href="<?= base_url()?>assets/css/leaflet.css" />
		<link rel="stylesheet" href="<?= base_url()?>assets/css/leaflet-geoman.css" />
		<link rel="stylesheet" href="<?= base_url()?>assets/css/L.Control.Locate.min.css" />
		<link rel="stylesheet" href="<?= base_url()?>assets/css/MarkerCluster.css" />
		<link rel="stylesheet" href="<?= base_url()?>assets/css/MarkerCluster.Default.css" />
		<link rel="stylesheet" href="<?= base_url()?>assets/css/leaflet-measure-path.css" />

		<!-- Untuk ubahan style desa -->
		<?php if (is_file("desa/css/siteman.css")): ?>
			<link type='text/css' href="<?= base_url()?>desa/css/siteman.css" rel='Stylesheet' />
		<?php endif; ?>
		<!-- Diperlukan untuk script jquery khusus halaman -->
		<script src="<?= base_url() ?>assets/bootstrap/js/jquery.min.js"></script>
		<!-- OpenStreetMap Js-->
		<script src="<?= base_url()?>assets/js/leaflet.js"></script>
		<script src="<?= base_url()?>assets/js/turf.min.js"></script>
		<script src="<?= base_url()?>assets/js/leaflet-geoman.min.js"></script>
		<script src="<?= base_url()?>assets/js/leaflet.filelayer.js"></script>
		<script src="<?= base_url()?>assets/js/togeojson.js"></script>
		<script src="<?= base_url()?>assets/js/togpx.js"></script>
		<script src="<?= base_url()?>assets/js/leaflet-providers.js"></script>
		<script src="<?= base_url()?>assets/js/L.Control.Locate.min.js"></script>
		<script src="<?= base_url()?>assets/js/leaflet.markercluster.js"></script>
		<script src="<?= base_url()?>assets/js/peta.js"></script>
		<script src="<?= base_url()?>assets/js/leaflet-measure-path.js"></script>
		<script src="<?= base_url()?>assets/js/apbdes_manual.js"></script>

		<!-- Diperlukan untuk global automatic base_url oleh external js file -->
		<script type="text/javascript">
			var BASE_URL = "<?= base_url(); ?>";
		</script>

		<!-- Highcharts JS -->
		<script src="<?= base_url()?>assets/js/highcharts/highcharts.js"></script>
		<script src="<?= base_url()?>assets/js/highcharts/highcharts-3d.js"></script>
		<script src="<?= base_url()?>assets/js/highcharts/exporting.js"></script>
		<script src="<?= base_url()?>assets/js/highcharts/highcharts-more.js"></script>
		<?php require __DIR__ .'/head_tags.php' ?>
	</head>
	<body class="<?= $this->setting->warna_tema_admin; ?> sidebar-mini fixed <?php if ($minsidebar==1): ?>sidebar-collapse<?php endif ?>">
		<div class="wrapper">
			<header class="main-header">
				<a href="<?=site_url()?>first"  target="_blank" class="logo">
					<span class="logo-mini"><b>SID</b></span>
					<span class="logo-lg"><b>OpenSID</b></span>
				</a>
				<nav class="navbar navbar-static-top">
					<a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
						<span class="sr-only">Toggle navigation</span>
					</a>
					<div class="navbar-custom-menu">
						<ul class="nav navbar-nav">
							<?php if ($this->CI->cek_hak_akses('b', 'permohonan_surat_admin')): ?>
								<li>
									<a href="<?=site_url()?>permohonan_surat_admin/clear">
										<i class="fa fa-print fa-lg" title="Permohonan surat baru"></i><span class="badge" id="b_permohonan_surat"></span>
									</a>
								</li>
							<?php endif; ?>
							<?php if ($this->CI->cek_hak_akses('b', 'komentar')): ?>
								<li>
									<a href="<?=site_url()?>komentar">
										<i class="fa fa-commenting fa-lg" title="Komentar baru"></i><span class="badge" id="b_komentar"></span>
									</a>
								</li>
							<?php endif; ?>
							<?php if ($this->CI->cek_hak_akses('b', 'mailbox')): ?>
								<li>
									<a href="<?=site_url()?>mailbox">
										<i class="fa fa-envelope fa-lg" title="Pesan masuk baru"></i><span class="badge" id="b_inbox"></span>
									</a>
								</li>
							<?php endif; ?>
							<li class="dropdown user user-menu">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown">
								<?php if ($foto): ?>
									<img src="<?= AmbilFoto($foto)?>" class="user-image" alt="User Image"/>
								<?php else :?>
									<img src="<?= base_url()?>assets/files/user_pict/kuser.png" class="user-image" alt="User Image"/>
								<?php endif; ?>
									<span class="hidden-xs"><?=$nama?> </span>
								</a>
								<ul class="dropdown-menu">
									<li class="user-header">
										<?php if ($foto): ?>
											<img src="<?=AmbilFoto($foto)?>" class="img-circle" alt="User Image"/>
										<?php else :?>
											<img src="<?= base_url()?>assets/files/user_pict/kuser.png" class="img-circle" alt="User Image"/>
										<?php endif; ?>
										<p>Anda Login Sebagai</p>
										<p><strong><?=$nama?></strong></p>
									</li>
									<li class="user-footer">
										<div class="pull-left">
											<a href="<?=site_url()?>user_setting/" data-remote="false" data-toggle="modal" data-tittle="Pengaturan Pengguna" data-target="#modalBox">
												<button data-toggle="modal"  class="btn bg-maroon btn-flat btn-sm" >Profil</button>
											</a>
										</div>
										<div class="pull-right">
											<a href="<?=site_url()?>siteman" class="btn bg-maroon btn-flat btn-sm">Keluar</a>
										</div>
									</li>
								</ul>
							</li>
						</ul>
					</div>
				</nav>
			</header>
			<input id="success-code" type="hidden" value="<?= $_SESSION['success']?>">
			<!-- Untuk menampilkan modal bootstrap umum  -->
			<div class="modal fade" id="modalBox" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class='modal-dialog'>
					<div class='modal-content'>
						<div class='modal-header'>
							<button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
							<h4 class='modal-title' id='myModalLabel'> Pengaturan Pengguna</h4>
						</div>
						<div class="fetched-data"></div>
					</div>
				</div>
			</div>
			<!-- Untuk menampilkan modal / pemberitahuan perubahan password default  -->
			<div class="modal fade" id="massageBox" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class='modal-dialog'>
					<div class='modal-content'>
						<div class='modal-header btn-info'>
							<button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
							<h4 class='modal-title' id='myModalLabel'><i class='fa fa-exclamation-triangle text-red'></i> &nbsp;<?= $_SESSION['admin_warning'][0]; ?></h4>
						</div>
						<div class='modal-body'>
							<?= $_SESSION['admin_warning'][1]; ?>
						</div>
						<div class='modal-footer'>
							<button type="button" class="btn btn-social btn-flat btn-warning btn-sm" data-dismiss="modal"><i class='fa fa-arrow-circle-o-left'></i> Lain Kali</button>
							<a href="<?= site_url()?>user_setting/" data-remote="false" data-tittle="Pengaturan Pengguna" data-toggle="modal" data-target="#modalBox" id="ok">
								<button type="button" class="btn btn-social btn-flat btn-success btn-sm"><i class='fa fa-edit'></i> Ubah</button>
							</a>
						</div>
					</div>
				</div>
			</div>
