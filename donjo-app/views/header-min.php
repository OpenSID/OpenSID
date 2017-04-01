<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>SID - Desa <?php echo $desa['nama_desa'] ?></title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="shortcut icon" href="<?php echo base_url()?>assets/files/logo/<?php echo $desa['logo']?>" />
		<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?php echo base_url()?>rss.xml" />
		<link href="<?php echo base_url()?>assets/css/screen.css" rel="stylesheet" type="text/css" />
		<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>assets/css/style2.css" />
		<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>assets/css/noJS.css" />
		<script src="<?php echo base_url()?>assets/js/jquery-1.5.2.min.js"></script>
		<script src="<?php echo base_url()?>assets/js/jquery-ui-1.8.16.custom.min.js"></script>
		<script src="<?php echo base_url()?>assets/js/jquery-layout.js"></script>
		<script src="<?php echo base_url()?>assets/js/jquery.formtips.1.2.2.packed.js"></script>
		<script src="<?php echo base_url()?>assets/js/jquery.tipsy.js"></script>
		<script src="<?php echo base_url()?>assets/js/jquery.elastic.js"></script>
		<script src="<?php echo base_url()?>assets/js/jquery.flexbox.min.js"></script>
		<script src="<?php echo base_url()?>assets/js/jquery.easing-1.3.pack.js"></script>
		<script src="<?php echo base_url()?>assets/js/donjoscript/donjoscript2.js"></script>
		<script src="<?php echo base_url()?>assets/js/donjoscript/donjo.ui.layout.js"></script>
		<script src="<?php echo base_url()?>assets/js/donjoscript/donjo.ui.mainmenu.js"></script>
		<script src="<?php echo base_url()?>assets/js/donjoscript/donjo.ui.dialog.js"></script>
		<script src="<?php echo base_url()?>assets/js/donjoscript/donjo.ui.attribut.js"></script>
		<script src="<?php echo base_url()?>assets/js/jquery.validate.min.js"></script>
		<script src="<?php echo base_url()?>assets/js/validasi.js"></script>
		<script>
			$('document').ready(function(){
				$_wrapperHeight = parseInt($('#wrapper').height());
				$('#maincontent').css({'height':$_wrapperHeight-35});
				$('#contentpane').css({'height':$_wrapperHeight});
			});
		</script>
	</head>
<body>
<div class="ui-layout-north">
</div>
<div class="ui-layout-center" id="wrapper">