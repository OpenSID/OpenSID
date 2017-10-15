<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title><?php if(@$single_artikel){echo $single_artikel['judul']." - ";}?>Website Desa <?php echo unpenetration($desa['nama_desa']);?></title>
		<meta content="utf-8" http-equiv="encoding">
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<?php
		if(isset($single_artikel['gambar'])){
			$gambar = $single_artikel['gambar'];
		}elseif(isset($single_artikel['gambar1'])){
			$gambar = $single_artikel['gambar1'];
		}elseif(isset($single_artikel['gambar2'])){
			$gambar = $single_artikel['gambar2'];
		}elseif(isset($single_artikel['gambar3'])){
			$gambar = $single_artikel['gambar4'];
		}
		?>
		<meta property="og:image" content="<?php echo base_url()."assets/files/artikel/kecil_".$gambar; ?>"  >
		<meta property="og:image:width" content="300">
		<meta property="og:image:height" content="180">
		<meta property="og:url" content="<?php echo urlencode(current_url()); ?>">
		<meta property="og:title" content="<?php echo $single_artikel['judul']; ?>"> 
		<meta property="og:site_name" content="<?php echo unpenetration($desa['nama_desa']);?>"/>
		<link rel="shortcut icon" href="<?php echo base_url()?>assets/files/logo/<?php echo $desa['logo']?>" />
		<link type='text/css' href="<?php echo base_url()?>assets/front/css/first.css" rel='Stylesheet' />
		<link type='text/css' href="<?php echo base_url()?>assets/css/ui-buttons.css" rel='Stylesheet' />
		<link type='text/css' href="<?php echo base_url()?>assets/front/css/colorbox.css" rel='Stylesheet' />
		<script src="<?php echo base_url()?>assets/front/js/stscode.js"></script>
		<script src="<?php echo base_url()?>assets/front/js/jquery.js"></script>
		<script src="<?php echo base_url()?>assets/front/js/layout.js"></script>
		<script src="<?php echo base_url()?>assets/front/js/jquery.colorbox.js"></script>
		<script>
			$(document).ready(function(){
				$(".group2").colorbox({rel:'group2', transition:"fade"});
				$(".group3").colorbox({rel:'group3', transition:"fade"});
			});
		</script>
	</head>
	<body>
		<div id="maincontainer">
			<div id="topsection">
				<div class="innertube">
					<div id="header">
						<div id="headercontent">
							<div id="menu_vert">
								<div id="menuwrapper">
									<?php $this->load->view('partials/menu.tpl.php');?>
								</div>
							</div>
							<div id="menu_vert2">
								<?php if(count($slide)>0){
									$this->load->view('layouts/slide.php');
								} ?>
							</div>
						</div>
					</div>
					<div id="headleft">
						<div id="divlogo">
							<div id="divlogo-txt">
								<div class="intube">
									<div id="siteTitle">
										<h1>Desa <?php echo unpenetration($desa['nama_desa'])?></h1>
										<h2>Kecamatan <?php echo unpenetration($desa['nama_kecamatan'])?><br />
										Kabupaten <?php echo unpenetration($desa['nama_kabupaten'])?><br />
                                                                                Provinsi <?php echo unpenetration($desa['nama_propinsi'])?></h2>
										<h3><?php echo unpenetration($desa['alamat_kantor'])?></h3>
									</div>
								</div>
							</div>
						</div>
						<div id="divlogo-img">
							<div class="intube">
								<a href="<?php echo site_url(); ?>first/">
								<img src="<?php echo base_url()?>assets/files/logo/<?php echo $desa['logo']?>" alt="<?php echo $desa['nama_desa']?>"/>
								</a>
							</div>
						</div>
						<br class="clearboth"/>
					</div>
					
					<?php if(count($teks_berjalan)>0){
						$this->load->view('layouts/teks_berjalan.php');
					} ?>
						
					<div id="mainmenu">
						<?php $this->load->view('partials/menu.left.php');?>
					</div>
					
				</div>
			</div>