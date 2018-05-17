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
<?php if(is_file("desa/css/siteman.css")): ?>
	<link type='text/css' href="<?php echo base_url()?>desa/css/siteman.css" rel='Stylesheet' />
<?php endif; ?>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/leaflet.css" />

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
		<script type="text/javascript" src="<?php echo base_url()?>assets/js/validasi.js"></script>
        <script src="<?php echo base_url()?>assets/js/leaflet.js"></script>
        <script src="<?php echo base_url()?>assets/js/turf.min.js"></script>
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
	<li><a href="<?php echo site_url()?>user_setting" target="ajax-modalz" rel="window-lok" header="Pengaturan Pengguna" title="Pengaturan Pengguna"><i class="fa fa-user fa-lg"></i>Setting User</a></li>
	<?php  if($_SESSION['grup']==1 OR $_SESSION['grup']==2){?>
		<li><a href="<?php echo site_url()?>modul/clear"><i class="fa fa-gear fa-lg"></i>Pengaturan</a></li>
	<?php  }?>
	<?php foreach ($modul AS $mod){?>
		<?php
			if ($this->setting->offline_mode >= 2 &&
				in_array($mod['url'], array('web', 'gis'))) {
				continue;
			}
		?>
		<li><a href="<?php echo site_url().$mod['url']?>"><i class="<?php echo $mod['ikon_kecil']?>"></i><?php echo $mod['modul']?></a></li>
	<?php } ?>
	<li><a href="<?php echo site_url()?>siteman"><i class="fa fa-power-off fa-lg"></i>Log Out</a></li>
</ul>

    </div>
</div>
