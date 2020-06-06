<?php if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>
			<?=
				$this->setting->website_title
				. ' ' . ucwords($this->setting->sebutan_desa)
				. (($desa['nama_desa']) ? ' ' . $desa['nama_desa'] : '')
				. get_dynamic_title_page_from_path();
			?>
		</title>
		<meta content="utf-8" http-equiv="encoding">
		<meta name="keywords" content="OpenSID,opensid,sid,SID,SID CRI,SID-CRI,sid cri,sid-cri,Sistem Informasi Desa,sistem informasi desa, desa <?= $desa['nama_desa'];?>">
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta property="og:site_name" content="<?= $desa['nama_desa'];?>"/>
		<meta property="og:type" content="article"/>

		<?php if (isset($single_artikel)): ?>
			<meta property="og:title" content="<?= $single_artikel["judul"];?>"/>
			<meta property="og:url" content="<?= base_url('index.php/artikel/'.buat_slug($single_artikel))?>"/>
			<meta property="og:image" content="<?= base_url(LOKASI_FOTO_ARTIKEL.'sedang_'.$single_artikel['gambar']);?>"/>
			<meta property="og:description" content="<?= potong_teks($single_artikel['isi'], 300)?> ..."/>
			<meta name="description" content="<?= potong_teks($single_artikel['isi'], 300)?> ..."/>
		<?php else: ?>
			<meta name="description" content="Website <?= ucwords($this->setting->sebutan_desa).' '.$desa['nama_desa'];?>"/>
		<?php endif; ?>

		<?php if (is_file(LOKASI_LOGO_DESA . "favicon.ico")): ?>
			<link rel="shortcut icon" href="<?= base_url()?><?= LOKASI_LOGO_DESA?>favicon.ico" />
		<?php else: ?>
			<link rel="shortcut icon" href="<?= base_url()?>favicon.ico" />
		<?php endif; ?>
		<link type='text/css' href="<?= base_url()?>assets/front/css/first.css" rel='Stylesheet' />

		<!-- Styles untuk tema dan penyesuaiannya di folder desa -->
		<link type='text/css' href="<?= base_url().$this->theme_folder.'/'.$this->theme.'/css/first.css'?>" rel='Stylesheet' />
		<?php if (is_file("desa/css/".$this->theme."/desa-web.css")): ?>
			<link type='text/css' href="<?= base_url()?>desa/css/<?= $this->theme ?>/desa-web.css" rel='Stylesheet' />
		<?php endif; ?>

		<link type='text/css' href="<?= base_url()?>assets/css/font-awesome.min.css" rel='Stylesheet' />
		<link type='text/css' href="<?= base_url()?>assets/css/ui-buttons.css" rel='Stylesheet' />
		<?php if ($single_artikel OR $gallery): ?>
			<link type='text/css' href="<?= base_url()?>assets/front/css/colorbox.css" rel='Stylesheet' />
		<?php endif ?>
		<link rel="stylesheet" href="<?= base_url()?>assets/css/leaflet.css" />

		<script src="<?= base_url()?>assets/front/js/jquery.js"></script>
		<script src="<?= base_url()?>assets/js/leaflet.js"></script>
		<script src="<?= base_url()?>assets/front/js/layout.js"></script>
		<script src="<?= base_url()?>assets/front/js/bootstrap.min.js"></script>
		<script src="<?= base_url()?>assets/js/leaflet-providers.js"></script>

		<!-- Datatables -->
		<link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/bootstrap/css/dataTables.bootstrap.min.css">
		<script src="<?= base_url() ?>assets/bootstrap/js/jquery.dataTables.min.js"></script>
		<script src="<?= base_url() ?>assets/bootstrap/js/dataTables.bootstrap.min.js"></script>
		<!-- Charts -->
		<script src="<?= base_url()?>assets/js/highcharts/highcharts.js"></script>
		<script src="<?= base_url()?>assets/js/highcharts/highcharts-3d.js"></script>
		<script src="<?= base_url()?>assets/js/highcharts/exporting.js"></script>
		<script src="<?= base_url()?>assets/js/highcharts/highcharts-more.js"></script>
		<!-- Untuk carousel, slider, teks_berjalan dan widget aparatur_desa -->
		<script src="<?php echo base_url()?>assets/front/js/jquery.cycle2.min.js"></script>
		<script src="<?php echo base_url()?>assets/front/js/jquery.cycle2.carousel.js"></script>
		<!-- Diperlukan untuk javascript yg mengakses resources -->
		<script type="text/javascript">
			var BASE_URL = "<?= base_url(); ?>";
		</script>

		<?php $this->load->view('head_tags_front') ?>
	</head>
	<body>
		<div id="maincontainer">
			<div id="topsection">
				<div class="innertube">
					<div id="header-default">
						<div id="headercontent-default">
							<div id="menu_vert">
								<div id="menuwrapper">
									<?php $this->load->view($folder_themes.'/partials/menu.tpl.php');?>
								</div>
							</div>
							<div id="menu_vert2">
								<?php $this->load->view($folder_themes.'/layouts/carousel.php'); ?>
							</div>
						</div>
						<div id="headleft-default">
							<div id="divlogo">
								<div id="divlogo-txt">
									<div class="intube">
										<div id="siteTitle">
											<h1>
												<span id="header_sebutan_desa">
													<?= ucwords($this->setting->sebutan_desa." ")?>
												</span>
												<?= ucwords($desa['nama_desa'])?>
											</h1>
											<h2>
												<?= ucwords($this->setting->sebutan_kecamatan." ".$desa['nama_kecamatan'])?><br />
												<?= ucwords($this->setting->sebutan_kabupaten." ".$desa['nama_kabupaten'])?>
											</h2>
											<h3><?= $desa['alamat_kantor']?></h3>
										</div>
									</div>
								</div>
							</div>
							<div id="divlogo-img">
								<div class="intube">
									<a href="<?= site_url(); ?>first/">
									<img src="<?= gambar_desa($desa['logo']);?>" alt="<?= $desa['nama_desa']?>"/>
									</a>
								</div>
							</div>
							<br class="clearboth"/>
						</div>
					</div>

					<?php if (!empty($teks_berjalan)): ?>
						<?php $this->load->view($folder_themes.'/layouts/teks_berjalan.php');?>
					<?php endif; ?>

					<div id="mainmenu">
						<?php $this->load->view($folder_themes.'/partials/menu.left.php');?>
					</div>

				</div>
			</div>
