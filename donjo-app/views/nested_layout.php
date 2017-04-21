<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title><?php
			echo config_item('admin_title')
				. ' ' . ucwords(config_item('sebutan_desa'))
				. (($desa['nama_desa']) ? ' ' . unpenetration($desa['nama_desa']) : '')
				. get_dynamic_title_page_from_path();
		?></title>

		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

		<?php if(is_file(LOKASI_LOGO_DESA . "favicon.ico")): ?>
			<link rel="shortcut icon" href="<?php echo base_url()?><?php echo LOKASI_LOGO_DESA?>favicon.ico" />
		<?php else: ?>
			<link rel="shortcut icon" href="<?php echo base_url()?>favicon.ico" />
		<?php endif; ?>
		<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?php echo base_url()?>rss.xml" />
		<link href="<?php echo base_url()?>assets/css/screen.css" rel="stylesheet" type="text/css" />

		<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>assets/css/style2.css" />
		<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>assets/css/siteman_styles.css" />
		<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>assets/css/noJS.css" />


	<link type="text/css" rel="stylesheet" href="<?php echo base_url()?>assets/css/layout-default-latest.css">

	<style type="text/css">
	.container	{ padding: 0; overflow: hidden; }
	.blue		{ background: #EEF; }
	.green		{ background: #EFE; }
	</style>

		<script type="text/javascript" src="<?php echo base_url()?>assets/js/jquery-1.12.4.min.js"></script>
		<script type="text/javascript" src="<?php echo base_url()?>assets/js/jquery-layout.js"></script>
		<script type="text/javascript" src="<?php echo base_url()?>assets/js/jquery-ui.min.js"></script>

<!-- 	<script type="text/javascript" src="/lib/js/jquery-latest.js"></script>
	<script type="text/javascript" src="/lib/js/jquery-ui-latest.js"></script>
	<script type="text/javascript" src="/lib/js/jquery.layout-latest.js"></script>
 -->	<script type="text/javascript">

	$(document).ready(function () {
		$('body').layout({
			center__childOptions: {
				center__childOptions: {
				}
			}
		});
		$('#content').layout({
			applyDefaultStyles: true
		});
	});

	</script>

</head>
<body>

<div class="ui-layout-north" id="header">
		<?php include("donjo-app/views/layout_header.php"); ?>
</div>
<div class="ui-layout-center">Outer Center

	<div class="ui-layout-north">
		<?php include("donjo-app/views/modul_admin.php"); ?>
	</div>

	<!-- <?php include("donjo-app/views/sid/kependudukan/test-content-layout.php"); ?> -->
	<div class="ui-layout-center">Inner Center
 		<div id="pageC">
<!-- 			<script  TYPE='text/javascript'>
			  $(function() {
			    var keyword = <?php echo $keyword?> ;
			    $( "#cari" ).autocomplete({
			    source: keyword
			    });
			  });
			</script>
 -->			<div class="ui-layout-center" id="content">

				<div class="ui-layout-north">Inner Center North</div>
				<div class="ui-layout-center">Inner Center Center</div>
				<div class="ui-layout-south">Inner Center South</div>

			</div>

		</div>

	</div>

</div>
<div class="ui-layout-south" id="footer">
  <span style="color:#002301;">Aplikasi <a href="https://github.com/eddieridwan/OpenSID" target="_blank" style="color:#009e04; text-decoration: none; font-weight: bold;">OpenSID <?php echo AmbilVersi()?></a>, berbasis SID yang dikembangkan oleh</span>
  <a href="http://www.combine.or.id" target="_blank" style="color:#009e04; text-decoration: none; font-weight: bold;">Combine.or.id </a>
</div>

</body>
</html>