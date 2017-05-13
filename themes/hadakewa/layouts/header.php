<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title><?php
			echo config_item('website_title')
				. ' ' . ucwords(config_item('sebutan_desa'))
				. (($desa['nama_desa']) ? ' ' . unpenetration($desa['nama_desa']) : '')
				. get_dynamic_title_page_from_path();
		?></title>
		<meta content="utf-8" http-equiv="encoding">
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta property="og:site_name" content="<?php echo unpenetration($desa['nama_desa']);?>"/>
    <meta property="og:type" content="article"/>
		<?php if(isset($single_artikel)): ?>
	    <meta property="og:title" content="<?php echo unpenetration($single_artikel["judul"]);?>"/>
	    <meta property="og:url" content="<?php echo base_url()?>index.php/first/artikel/<?php echo unpenetration($single_artikel['id']);?>"/>
	    <meta property="og:image" content="<?php echo base_url()?><?php echo LOKASI_FOTO_ARTIKEL?>sedang_<?php echo $single_artikel['gambar'];?>"/>
		<?php endif; ?>
		<?php if(is_file(LOKASI_LOGO_DESA . "favicon.ico")): ?>
			<link rel="shortcut icon" href="<?php echo base_url()?><?php echo LOKASI_LOGO_DESA?>favicon.ico" />
		<?php else: ?>
			<link rel="shortcut icon" href="<?php echo base_url()?>favicon.ico" />
		<?php endif; ?>
		<?php if(is_file("desa/css/".$this->theme."/front/first.css")){?>
		  <link type='text/css' href="<?php echo base_url()?>desa/css/<?php echo $this->theme ?>/front/first.css" rel='Stylesheet' />
		<?php } else { ?>
		  <link type='text/css' href="<?php echo base_url()?>assets/front/css/first.css" rel='Stylesheet' />
		<?php } ?>

		<?php if(is_file("desa/css/".$this->theme."/desa-web.css")): ?>
			<link type='text/css' href="<?php echo base_url()?>desa/css/<?php echo $this->theme ?>/desa-web.css" rel='Stylesheet' />
		<?php endif; ?>
		<link type='text/css' href="<?php echo base_url()?>assets/css/font-awesome.min.css" rel='Stylesheet' />
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
									<?php $this->load->view($folder_themes.'/partials/menu.tpl.php');?>
								</div>
							</div>
							<div id="menu_vert2">
								<?php if(count($slide)>0){
									$this->load->view($folder_themes.'/layouts/slide.php');
								} ?>
							</div>
						</div>
					</div>
					<div id="headleft">
						<div id="divlogo">
							<div id="divlogo-txt">
								<div class="intube">

								</div>
							</div>
						</div>
						<div id="divlogo-img">
							<div class="intube">
							</div>
						</div>
						<br class="clearboth"/>
					</div>

					<?php if(count($teks_berjalan)>0){
						$this->load->view($folder_themes.'/layouts/teks_berjalan.php');
					} ?>

					<div id="mainmenu">
						<?php $this->load->view($folder_themes.'/partials/menu.left.php');?>
					</div>

				</div>
			</div>
