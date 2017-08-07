<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php
	echo $this->setting->admin_title
		. ' ' . ucwords($this->setting->sebutan_desa)
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
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>assets/css/noJS.css" /></noscript>


		<script type="text/javascript" src="<?php echo base_url()?>assets/js/jquery-1.5.2.min.js"></script>
		<script type="text/javascript" src="<?php echo base_url()?>assets/js/jquery-ui-1.8.16.custom.min.js"></script>
		<script type="text/javascript" src="<?php echo base_url()?>assets/js/jquery-ui-timepicker-addon.min.js"></script>
		<script type="text/javascript" src="<?php echo base_url()?>assets/js/jquery-layout-1.3.0.js"></script>
		<script type="text/javascript" src="<?php echo base_url()?>assets/js/jquery.formtips.1.2.2.packed.js"></script>
		<script type="text/javascript" src="<?php echo base_url()?>assets/js/jquery.tipsy.js"></script>
		<script type="text/javascript" src="<?php echo base_url()?>assets/js/jquery.elastic.js"></script>
		<script type="text/javascript" src="<?php echo base_url()?>assets/js/jquery.flexbox.min.js"></script>
		<script type="text/javascript" src="<?php echo base_url()?>assets/js/jquery.easing-1.3.pack.js"></script>
		<script type="text/javascript" src="<?php echo base_url()?>assets/js/donjoscript/donjoscript2.js"></script>
		<script type="text/javascript" src="<?php echo base_url()?>assets/js/donjoscript/donjo.ui.layout.js"></script>
		<script type="text/javascript" src="<?php echo base_url()?>assets/js/donjoscript/donjo.ui.mainmenu.js"></script>
		<script type="text/javascript" src="<?php echo base_url()?>assets/js/donjoscript/donjo.ui.dialog.js"></script>
		<script type="text/javascript" src="<?php echo base_url()?>assets/js/donjoscript/donjo.ui.attribut.js"></script>
		<script type="text/javascript" src="<?php echo base_url()?>assets/js/jquery.validate.min.js"></script>
		<script type="text/javascript" src="<?php echo base_url()?>assets/js/validasi.js"></script><script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?v=4&key=<?php echo $this->setting->google_key; ?>"></script>
<!---->
<!--[if lte IE 6]>
<style type="text/css">
img, div,span,a,button { behavior: url(assets/js/iepngfix.htc) }
</style>
<link href="=base_url()?>assets/css/ie6.css" rel="stylesheet" type="text/css" />
<![endif]-->

<style type="text/css">
.iconic {
	-moz-box-shadow:inset 0px 1px 0px 0px #ffffff;
	-webkit-box-shadow:inset 0px 1px 0px 0px #ffffff;
	box-shadow:inset 0px 1px 0px 0px #ffffff;
	background-color:transparent;
	-webkit-border-top-left-radius:6px;
	-moz-border-radius-topleft:6px;
	border-top-left-radius:6px;
	-webkit-border-top-right-radius:6px;
	-moz-border-radius-topright:6px;
	border-top-right-radius:6px;
	-webkit-border-bottom-right-radius:6px;
	-moz-border-radius-bottomright:6px;
	border-bottom-right-radius:6px;
	-webkit-border-bottom-left-radius:6px;
	-moz-border-radius-bottomleft:6px;
	border-bottom-left-radius:6px;
	text-indent:0;
	border:1px solid #cccccc;
	display:inline-block;
	color:#ffffff;
	line-height:25px;
	width:30px;
	text-align:center;
	text-shadow:1px 1px 0px #ffffff;
}.iconic:active {
	position:relative;
	top:1px;
}</style>
</head>
<body>
<div class="ui-layout-north" id="header">
	<div id="sid-logo"><a href="<?php echo site_url()?>first" target="_blank"><img src="<?php echo LogoDesa($desa['logo']);?>" alt=""/></a></div>
	<div id="sid-judul">SID Sistem Informasi Desa</div>
	<div id="sid-info"><?php echo ucwords($this->setting->sebutan_desa." ".$desa['nama_desa'].", ".$this->setting->sebutan_kecamatan." ".unpenetration($desa['nama_kecamatan']).", ".$this->setting->sebutan_kabupaten." ".unpenetration($desa['nama_kabupaten']))?></div>
	<div id="userbox" class="wrapper-dropdown-3" tabindex="1">
  <div class="avatar">
		<?php if($foto){?>
			<img src="<?php echo AmbilFoto($foto)?>" alt=""/>
		<?php }else{?>
			<img src="<?php echo base_url()?>assets/files/user_pict/kuser.png" alt=""/>
		<?php }?>
	</div>
	<div class="info">
		<div><strong>Anda Login sebagai</strong></div>
		<div><?php echo $nama?></div>
	</div>

<ul class="dropdown" tabindex="1">
<?php  if($_SESSION['grup']==1 OR $_SESSION['grup']==2){?>
	<li><a href="<?php echo site_url()?>modul/clear"><i class="icon-gear"></i>Pengaturan</a></li>
	<li><a href="<?php echo site_url()?>hom_desa"><i class="icon-home"></i>SID Home</a></li>
	<li><a href="<?php echo site_url()?>sid_core"><i class="icon-group"></i>Penduduk</a></li>
	<li><a href="<?php echo site_url()?>statistik"><i class="icon-bar-chart"></i>Statistik</a></li>
	<li><a href="<?php echo site_url()?>surat"><i class="icon-print"></i>Cetak Surat</a></li>
	<li><a href="<?php echo site_url()?>analisis"><i class="icon-dashboard"></i>Analisis</a></li>
<?php  }?>
<?php  if($_SESSION['grup']==1 OR $_SESSION['grup']==2){?>
	<?php  if($_SESSION['grup']==1){?>
		<li><a href="<?php echo site_url()?>man_user/clear"><i class="icon-user"></i>Pengguna</a></li>
		<li><a href="<?php echo site_url()?>database"><i class="icon-hdd"></i>Database</a></li>
	<?php  }?>
	<li><a href="<?php echo site_url()?>sms"><i class="icon-envelope-alt"></i>SMS</a></li>
	<li><a href="<?php echo site_url()?>web"><i class="icon-cloud"></i>Admin Web</a></li>
<?php  }?>
<li><a href="<?php echo site_url()?>siteman"><i class="icon-off"></i>Log Out</a></li>
</ul>

    </div>
</div>
