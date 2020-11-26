<?php
/**
 * File ini:
 *
 * Modul Header OpenSID
 *
 * /donjo-app/views/header.php
 *
 */

/**
 *
 * File ini bagian dari:
 *
 * OpenSID
 *
 * Sistem informasi desa sumber terbuka untuk memajukan desa
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * Hak Cipta 2016 - 2020 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 *
 * Dengan ini diberikan izin, secara gratis, kepada siapa pun yang mendapatkan salinan
 * dari perangkat lunak ini dan file dokumentasi terkait ("Aplikasi Ini"), untuk diperlakukan
 * tanpa batasan, termasuk hak untuk menggunakan, menyalin, mengubah dan/atau mendistribusikan,
 * asal tunduk pada syarat berikut:

 * Pemberitahuan hak cipta di atas dan pemberitahuan izin ini harus disertakan dalam
 * setiap salinan atau bagian penting Aplikasi Ini. Barang siapa yang menghapus atau menghilangkan
 * pemberitahuan ini melanggar ketentuan lisensi Aplikasi Ini.

 * PERANGKAT LUNAK INI DISEDIAKAN "SEBAGAIMANA ADANYA", TANPA JAMINAN APA PUN, BAIK TERSURAT MAUPUN
 * TERSIRAT. PENULIS ATAU PEMEGANG HAK CIPTA SAMA SEKALI TIDAK BERTANGGUNG JAWAB ATAS KLAIM, KERUSAKAN ATAU
 * KEWAJIBAN APAPUN ATAS PENGGUNAAN ATAU LAINNYA TERKAIT APLIKASI INI.
 *
 * @package OpenSID
 * @author  Tim Pengembang OpenDesa
 * @copyright Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright Hak Cipta 2016 - 2020 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license http://www.gnu.org/licenses/gpl.html  GPL V3
 * @link  https://github.com/OpenSID/OpenSID
 */
?>
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

		<link rel="stylesheet" href="<?= base_url()?>assets/plugins/jquery-ui/jquery-ui.min.css">
		<!-- Font Awesome -->
	  <link rel="stylesheet" href="<?= base_url()?>assets/plugins/fontawesome-free/css/all.min.css">
		<link rel="stylesheet" href="<?= base_url()?>assets/bootstrap/css/font-awesome.min.css">
		<!-- Ionicons -->
	  <link rel="stylesheet" href="<?= base_url()?>assets/bootstrap/css/ionicons.min.css">
		<!-- DataTables -->
		<link rel="stylesheet" href="<?= base_url()?>assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
		<link rel="stylesheet" href="<?= base_url()?>assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
		<!-- bootstrap wysihtml5 - text editor -->
		<link rel="stylesheet" href="<?= base_url()?>assets/bootstrap/css/bootstrap3-wysihtml5.min.css">
		<!-- Select2 -->
		<link rel="stylesheet" href="<?= base_url()?>assets/plugins/select2/css/select2.min.css">
		<link rel="stylesheet" href="<?= base_url()?>assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
		<!-- Bootstrap Color Picker -->
		<link rel="stylesheet" href="<?= base_url()?>assets/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css">
		<!-- bootstrap datepicker -->
		<link rel="stylesheet" href="<?= base_url()?>assets/bootstrap/css/bootstrap-datepicker.min.css">
		<!-- Theme style -->
		<link rel="stylesheet" href="<?= base_url()?>assets/adminlte-3.0.5/css/adminlte.min.css">
		<!-- AdminLTE Skins. -->
		<link rel="stylesheet" href="<?= base_url()?>assets/css/skins/_all-skins.min.css">
		<!-- Style Admin Modification Css -->
		<link rel="stylesheet" href="<?= base_url()?>assets/css/admin-style.css">

	  <!-- overlayScrollbars -->
	  <link rel="stylesheet" href="<?= base_url()?>assets/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
	  <!-- Daterange picker -->
	  <link rel="stylesheet" href="<?= base_url()?>assets/plugins/daterangepicker/daterangepicker.css">
		<!-- Google Font: Source Sans Pro -->
	  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">

		<!-- OpenStreetMap Css -->
		<link rel="stylesheet" href="<?= base_url()?>assets/css/leaflet.css" />
		<link rel="stylesheet" href="<?= base_url()?>assets/css/leaflet-geoman.css" />
		<link rel="stylesheet" href="<?= base_url()?>assets/css/L.Control.Locate.min.css" />
		<link rel="stylesheet" href="<?= base_url()?>assets/css/MarkerCluster.css" />
		<link rel="stylesheet" href="<?= base_url()?>assets/css/MarkerCluster.Default.css" />
		<link rel="stylesheet" href="<?= base_url()?>assets/css/leaflet-measure-path.css" />
		<link rel="stylesheet" href="<?= base_url()?>assets/css/mapbox-gl.css" />
		<link rel="stylesheet" href="<?= base_url()?>assets/css/L.Control.Shapefile.css" />
		<link rel="stylesheet" href="<?= base_url()?>assets/css/leaflet.groupedlayercontrol.min.css" />
		<link rel="stylesheet" href="<?= base_url()?>assets/css/peta.css">

		<!-- Untuk ubahan style desa -->
		<?php if (is_file("desa/css/siteman.css")): ?>
			<link type='text/css' href="<?= base_url()?>desa/css/siteman.css" rel='Stylesheet' />
		<?php endif; ?>
		<!-- Diperlukan untuk script jquery khusus halaman -->
		<script src="<?= base_url() ?>assets/plugins/jquery/jquery-3.5.1.min.js"></script>

		<!-- Diperlukan untuk global automatic base_url oleh external js file -->
		<script type="text/javascript">
			var BASE_URL = "<?= base_url(); ?>";
			var SITE_URL = "<?= site_url(); ?>";
		</script>

		<?php require __DIR__ .'/head_tags.php' ?>
	</head>

	<body class="<?= $this->setting->warna_tema_admin; ?> hold-transition sidebar-mini layout-fixed <?php if ($minsidebar==1): ?>sidebar-collapse<?php endif ?>">
    <div class="wrapper">
			<nav class="main-header navbar navbar-expand navbar-dark-primary navbar-dark sticky-top">
				<ul class="navbar-nav">
					<li class="nav-item">
						<a class="nav-link" data-widget="pushmenu" href="#"><i class="fa fa-bars"></i></a>
					</li>
				</ul>
				<ul class="navbar-nav ml-auto">
					<li class="nav-item">
						<?php if (ENVIRONMENT == 'development'): ?>
							<a class="nav-link" href="#">
								<i class="fa fa-cogs" title="Development"></i>
								<span class="badge badge-danger navbar-badge">development</span>
							</a>
						<?php endif; ?>
					</li>
					<li class="nav-item">
						<?php if ($this->CI->cek_hak_akses('b', 'permohonan_surat_admin')): ?>
							<a class="nav-link" href="<?= site_url('permohonan_surat_admin/clear'); ?>">
								<span><i class="fa fa-print" title="Permohonan Surat"></i>&nbsp;</span>
								<span class="badge badge-warning navbar-badge" id="b_permohonan_surat" style="display: none;"></span>
							</a>
						<?php endif; ?>
					</li>
					<li class="nav-item">
						<?php if ($this->CI->cek_hak_akses('b', 'komentar')): ?>
							<a class="nav-link" href="<?= site_url('komentar'); ?>">
								<span><i class="fas fa-comments" title="Komentar"></i>&nbsp;</span>
								<span class="badge badge-info navbar-badge" id="b_komentar" style="display: none;"></span>
							</a>
						<?php endif; ?>
					</li>
					<li class="nav-item">
						<?php if ($this->CI->cek_hak_akses('b', 'mailbox')): ?>
							<a class="nav-link" href="<?= site_url('mailbox'); ?>">
								<span ><i class="fa fa-envelope" title="Pesan Masuk"></i>&nbsp;</span>
								<span class="badge badge-warning navbar-badge" id="b_inbox" style="display: none;"></span>
							</a>
						<?php endif; ?>
					</li>
					<li class="nav-item dropdown user user-menu">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">
							<img src="<?= AmbilFoto($foto); ?>" class="user-image" alt="User Image"/>
							<span class="badge badge-info hidden-xs"><?=$nama?></span>
						</a>
						<ul class="dropdown-menu">
							<li class="user-header">
								<img src="<?= AmbilFoto($foto); ?>" class="rounded-circle" alt="User Image"/>
								<p>
									Anda Login Sebagai
									<strong><?=$nama?></strong>
								</p>
							</li>
							<li class="user-footer">
								<div class="pull-left">
									<a href="<?= site_url('user_setting'); ?>" data-remote="false" data-toggle="modal" data-tittle="Pengaturan Pengguna" data-target="#modalBox" class="btn bg-maroon btn-flat btn-xs">Profil</a>
								</div>
								<div class="pull-right">
									<a href="<?= site_url('siteman/logout'); ?>" class="btn bg-maroon btn-flat btn-xs">Keluar</a>
								</div>
							</li>
						</ul>
					</li>
					<li class="nav-item">
						<a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
							<i class="fas fa-th-large"></i>
						</a>
					</li>
				</ul>
			</nav>

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
			<!-- Untuk menampilkan dialog pengumuman  -->
			<?= $this->pengumuman; ?>
