<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>
			<?= $this->setting->admin_title . ' ' . ucwords($this->setting->sebutan_desa) . (($desa['nama_desa']) ? ' ' . $desa['nama_desa'] : '') . get_dynamic_title_page_from_path() ?>
		</title>
		<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
		<link rel="shortcut icon" href="<?= favico_desa() ?>"/>
		<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?= base_url('rss.xml') ?>" />

		<!-- Bootstrap 3.3.7 -->
		<link rel="stylesheet" href="<?= asset('bootstrap/css/bootstrap.min.css') ?>">
		<!-- Jquery UI -->
		<link rel="stylesheet" href="<?= asset('bootstrap/css/jquery-ui.min.css') ?>">
		<!-- Font Awesome -->
		<link rel="stylesheet" href="<?= asset('bootstrap/css/font-awesome.min.css') ?>">
		<!-- Ionicons -->
		<link rel="stylesheet" href="<?= asset('bootstrap/css/ionicons.min.css') ?>">
		<!-- DataTables -->
		<link rel="stylesheet" href="<?= asset('bootstrap/css/dataTables.bootstrap.min.css') ?>">
		<!-- bootstrap wysihtml5 - text editor -->
		<link rel="stylesheet" href="<?= asset('bootstrap/css/bootstrap3-wysihtml5.min.css') ?>">
		<!-- Select2 -->
		<link rel="stylesheet" href="<?= asset('bootstrap/css/select2.min.css') ?>">
		<!-- Bootstrap Color Picker -->
		<link rel="stylesheet" href="<?= asset('bootstrap/css/bootstrap-colorpicker.min.css') ?>">
		<!-- bootstrap datepicker -->
		<link rel="stylesheet" href="<?= asset('bootstrap/css/bootstrap-datepicker.min.css') ?>">
		<!-- boostrap datetimepicker -->
		<link rel="stylesheet" href="<?= asset('bootstrap/css/bootstrap-datetimepicker.min.css') ?>">
		<!-- Theme style -->
		<link rel="stylesheet" href="<?= asset('css/AdminLTE.min.css') ?>">
		<!-- AdminLTE Skins. -->
		<link rel="stylesheet" href="<?= asset('css/skins/_all-skins.min.css') ?>">
		<!-- Style Admin Modification Css -->
		<!-- Token Field -->
		<?php if ($this->controller == 'bumindes_kader'): ?>
			<link rel="stylesheet" href="<?= asset('bootstrap/css/bootstrap-tokenfield.min.css') ?>">
		<?php endif ?>
		<link rel="stylesheet" href="<?= asset('css/admin-style.css') ?>">
		<!-- OpenStreetMap Css -->
		<link rel="stylesheet" href="<?= asset('css/leaflet.css') ?>">
		<link rel="stylesheet" href="<?= asset('css/leaflet-geoman.css') ?>">
		<link rel="stylesheet" href="<?= asset('css/L.Control.Locate.min.css') ?>">
		<link rel="stylesheet" href="<?= asset('css/MarkerCluster.css') ?>">
		<link rel="stylesheet" href="<?= asset('css/MarkerCluster.Default.css') ?>">
		<link rel="stylesheet" href="<?= asset('css/leaflet-measure-path.css') ?>">
		<link rel="stylesheet" href="<?= asset('css/mapbox-gl.css') ?>">
		<link rel="stylesheet" href="<?= asset('css/L.Control.Shapefile.css') ?>">
		<link rel="stylesheet" href="<?= asset('css/leaflet.groupedlayercontrol.min.css') ?>">
		<link rel="stylesheet" href="<?= asset('css/peta.css') ?>">
		<link rel="stylesheet" href="<?= asset('css/toastr.min.css') ?>">

		<style>
			@media (max-width: 576px) {
				.komunikasi-opendk {
					display: none !important;
				}
			}
		</style>

		<!-- Untuk ubahan style desa -->
		<?php if (is_file('desa/css/siteman.css')): ?>
			<link rel='Stylesheet' href="<?= base_url('desa/css/siteman.css') ?>">
		<?php endif ?>
		<!-- Diperlukan untuk script jquery khusus halaman -->
		<script src="<?= asset('bootstrap/js/jquery.min.js') ?>"></script>

		<!-- OpenStreetMap Js-->
		<script src="<?= asset('js/leaflet.js') ?>"></script>
		<script src="<?= asset('js/turf.min.js') ?>"></script>
		<script src="<?= asset('js/leaflet-geoman.min.js') ?>"></script>
		<script src="<?= asset('js/leaflet.filelayer.js') ?>"></script>
		<script src="<?= asset('js/togeojson.js') ?>"></script>
		<script src="<?= asset('js/togpx.js') ?>"></script>
		<script src="<?= asset('js/leaflet-providers.js') ?>"></script>
		<script src="<?= asset('js/L.Control.Locate.min.js') ?>"></script>
		<script src="<?= asset('js/leaflet.markercluster.js') ?>"></script>
		<script src="<?= asset('js/peta.js')?>"></script>
		<script src="<?= asset('js/leaflet-measure-path.js') ?>"></script>
		<script src="<?= asset('js/apbdes_manual.js') ?>"></script>
		<script src="<?= asset('js/mapbox-gl.js') ?>"></script>
		<script src="<?= asset('js/leaflet-mapbox-gl.js') ?>"></script>
		<script src="<?= asset('js/shp.js') ?>"></script>
		<script src="<?= asset('js/leaflet.shpfile.js') ?>"></script>
		<script src="<?= asset('js/leaflet.groupedlayercontrol.min.js') ?>"></script>
		<script src="<?= asset('js/leaflet.browser.print.js') ?>"></script>
		<script src="<?= asset('js/leaflet.browser.print.utils.js') ?>"></script>
		<script src="<?= asset('js/leaflet.browser.print.sizes.js') ?>"></script>
		<script src="<?= asset('js/dom-to-image.min.js') ?>"></script>
		<script src="<?= asset('js/toastr.min.js') ?>"></script>

		<!-- Diperlukan untuk global automatic base_url oleh external js file -->
		<script type="text/javascript">
			var BASE_URL = "<?= base_url() ?>";
			var SITE_URL = "<?= site_url() ?>";
		</script>

		<!-- Highcharts JS -->
		<script src="<?= asset('js/highcharts/highcharts.js') ?>"></script>
		<script src="<?= asset('js/highcharts/highcharts-3d.js') ?>"></script>
		<script src="<?= asset('js/highcharts/exporting.js') ?>"></script>
		<script src="<?= asset('js/highcharts/highcharts-more.js') ?>"></script>
		<script src="<?= asset('js/highcharts/sankey.js') ?>"></script>
		<script src="<?= asset('js/highcharts/organization.js') ?>"></script>
		<script src="<?= asset('js/highcharts/accessibility.js') ?>"></script>

		<?php require __DIR__ . '/head_tags.php' ?>
	</head>
	<body id="sidebar_collapse" class="<?= $this->setting->warna_tema_admin ?> sidebar-mini fixed">
		<div class="wrapper">
			<header class="main-header">
				<a href="<?= site_url() ?>" target="_blank" class="logo">
					<span class="logo-mini"><b>SID</b></span>
					<span class="logo-lg"><b>OpenSID</b></span>
				</a>
				<nav class="navbar navbar-static-top">
					<a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
						<span class="sr-only">Toggle navigation</span>
					</a>
					<div class="navbar-custom-menu">
						<ul class="nav navbar-nav">
							<?php if ($notif_langganan): ?>
								<li>
									<a href="<?= site_url('pelanggan') ?>">
										<span><i class="fa <?= $notif_langganan['ikon'] ?> fa-lg" title="Status Langganan <?= $notif_langganan['masa'] ?> hari" style="color: <?= $notif_langganan['warna'] ?>;"></i>&nbsp;</span>
										<?php if ($notif_langganan['status'] > 2) : ?>
											<span class="badge" id="b_langganan">!</span>
										<?php endif ?>
									</a>
								</li>
							<?php endif ?>
							<?php if (in_array('343', array_column($modul, 'id')) && can('b', 'opendk_pesan')) : ?>
								<li class="komunikasi-opendk">
									<a href="<?=  route('opendk_pesan.clear') ?>">
										<span><i class="fa fa-university fa-lg" title="Komunikasi OpenDk"></i>&nbsp;</span>
										<?php if ($notif_pesan_opendk) : ?>
											<span class="badge" id="b_opendkpesan"><?=  $notif_pesan_opendk ?></span>
										<?php endif ?>
									</a>
								</li>
							<?php endif ?>
							<?php if (can('b', 'permohonan_surat_admin')): ?>
								<li>
									<a href="<?= site_url('permohonan_surat_admin/clear') ?>">
										<span><i class="fa fa-print fa-lg" title="Permohonan Surat"></i>&nbsp;</span>
										<?php if ($notif_permohonan_surat) : ?>
											<span class="badge" id="b_permohonan_surat"><?= $notif_permohonan_surat ?></span>
										<?php endif ?>
									</a>
								</li>
							<?php endif ?>
							<?php if (can('b', 'komentar')): ?>
								<li>
									<a href="<?= site_url('komentar') ?>">
										<span><i class="fa fa-commenting-o fa-lg" title="Komentar"></i>&nbsp;</span>
										<?php if ($notif_komentar) : ?>
											<span class="badge" id="b_komentar"><?= $notif_komentar ?></span>
										<?php endif ?>
									</a>
								</li>
							<?php endif ?>
							<?php if (can('b', 'mailbox')): ?>
								<li>
									<a href="<?= site_url('mailbox') ?>">
										<span><i class="fa fa-envelope-o fa-lg" title="Pesan Masuk"></i>&nbsp;</span>
										<?php if ($notif_inbox) : ?>
											<span class="badge" id="b_inbox"><?= $notif_inbox ?></span>
										<?php endif ?>
									</a>
								</li>
							<?php endif ?>
							<li class="dropdown user user-menu">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown">
									<img src="<?= AmbilFoto($foto) ?>" class="user-image" alt="User Image"/>
									<span class="hidden-xs"><?=$nama?></span>
								</a>
								<ul class="dropdown-menu">
									<li class="user-header">
										<img src="<?= AmbilFoto($foto) ?>" class="img-circle" alt="User Image"/>
										<p>
											<small>Anda Masuk Sebagai</small>
											<?= $nama ?>
										</p>
									</li>
									<li class="user-footer">
										<div class="pull-left">
											<a href="<?= site_url('user_setting') ?>" data-remote="false" data-toggle="modal" data-title="Pengaturan Pengguna" data-target="#modalBox" class="btn bg-maroon btn-flat btn-sm">Profil</a>
										</div>
										<div class="pull-right">
											<a href="<?= site_url('siteman/logout') ?>" class="btn bg-maroon btn-flat btn-sm">Keluar</a>
										</div>
									</li>
								</ul>
							</li>
							<li>
								<a href="#" data-toggle="control-sidebar" title="Informasi">
									<span><i class="fa fa-question-circle fa-lg""></i>&nbsp;</span>
								</a>
							</li>
							<?php if ($this->header['kategori'] && can('u', $this->controller)): ?>
							<li>
								<a href="#" data-remote="false" data-toggle="modal" data-title="Pengaturan <?= ucwords($this->controller) ?>" data-target="#pengaturan">
									<span><i class="fa fa-gear"></i>&nbsp;</span>
								</a>
							</li>
							<?php endif ?>
						</ul>
					</div>
				</nav>
			</header>

			<!-- Untuk menampilkan modal bootstrap umum -->
			<div class="modal fade" id="modalBox" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
							<h4 class="modal-title" id="myModalLabel"></h4>
						</div>
						<div class="fetched-data"></div>
					</div>
				</div>
			</div>

			<!-- Untuk menampilkan pengaturan -->
			<?php if ($this->header['kategori'] && can('u', $this->controller)): ?>
				<div class="modal fade" id="pengaturan" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
								<h4 class="modal-title" id="myModalLabel"> Pengaturan <?= ucwords(str_replace('_', ' ', $this->header['kategori'])) ?></h4>
							</div>
							<?php $this->load->view('global/modal_setting', ['kategori' => [$this->header['kategori']]]) ?>
						</div>
					</div>
				</div>
			<?php endif ?>

			<?php
                if ($notif_pengumuman):
                    $this->load->view('notif/pengumuman', $notif_pengumuman);
                endif
			?>