<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Sistem Informasi Desa</title>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<link rel="shortcut icon" href="<?=base_url()?>favicon.ico" />
<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?=base_url()?>rss.xml" />
<link href="<?=base_url()?>assets/css/screen.css" rel="stylesheet" type="text/css" />

<link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/css/style2.css" />
<link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/css/noJS.css" /></noscript> 

<script type="text/javascript" src="<?=base_url()?>assets/js/jquery-1.5.2.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>assets/js/jquery-ui-1.8.16.custom.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>assets/js/jquery-layout.js"></script>
<script type="text/javascript" src="<?=base_url()?>assets/js/jquery.formtips.1.2.2.packed.js"></script>
<script type="text/javascript" src="<?=base_url()?>assets/js/jquery.tipsy.js"></script>
<script type="text/javascript" src="<?=base_url()?>assets/js/jquery.elastic.js"></script>
<script type="text/javascript" src="<?=base_url()?>assets/js/autoNumeric.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>assets/js/jquery.flexbox.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>assets/js/jquery.easing-1.3.pack.js"></script>
<script type="text/javascript" src="<?=base_url()?>assets/js/jquery.fancybox-1.3.4.pack.js"></script>
<script type="text/javascript" src="<?=base_url()?>assets/js/donjoscript/donjoscript2.js"></script>
<script type="text/javascript" src="<?=base_url()?>assets/js/donjoscript/donjo.ui.layout.js"></script>
<script type="text/javascript" src="<?=base_url()?>assets/js/donjoscript/donjo.ui.mainmenu.js"></script>
<script type="text/javascript" src="<?=base_url()?>assets/js/donjoscript/donjo.ui.dialog.js"></script>
<script type="text/javascript" src="<?=base_url()?>assets/js/donjoscript/donjo.ui.attribut.js"></script>
<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=false"></script>
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
    <div id="sid-logo"><img src="<?=base_url()?>assets/images/logo/<?=$desa['logo']?>" alt=""/></div>
    <div id="sid-judul">SID Sistem Informasi Desa</div>
    <div id="sid-info">Kab. <?=$desa['nama_kabupaten']?>, Kec. <?=$desa['nama_kecamatan']?>, Desa <?=$desa['nama_desa']?></div>
<div id="userbox" class="wrapper-dropdown-3" tabindex="1">
        <div class="avatar">
		<?if($foto){?>
			<img src="<?=base_url()?>assets/images/photo/kecil_<?=$foto?>" alt=""/>
		<?}else{?>
			<img src="<?=base_url()?>assets/images/photo/kuser.png" alt=""/>
		<?}?>
		</div>
        <div class="info">
            <div><strong>Anda Login sebagai</strong></div>
            <div><?=$nama?></div>
        </div>

<ul class="dropdown" tabindex="1">
<? if($_SESSION['grup']==1 OR $_SESSION['grup']==2){?>
	<li><a href="<?=site_url()?>hom_desa"><i class="icon-home icon-large"></i>SID Home</a></li>
	<li><a href="<?=site_url()?>sid_core"><i class="icon-group icon-large"></i>Penduduk</a></li>
	<li><a href="<?=site_url()?>statistik"><i class="icon-bar-chart icon-large"></i>Statistik</a></li>
	<li><a href="<?=site_url()?>surat"><i class="icon-print icon-large"></i>Cetak Surat</a></li>
	<li><a href="<?=site_url()?>analisis"><i class="icon-dashboard icon-large"></i>Analisis</a></li>
<? }?>
<? if($_SESSION['grup']==1 OR $_SESSION['grup']==2){?>	
	<? if($_SESSION['grup']==1){?>
		<li><a href="<?=site_url()?>man_user/clear"><i class="icon-user icon-large"></i>Pengguna</a></li>
		<li><a href="<?=site_url()?>database"><i class="icon-hdd icon-large"></i>Database</a></li>
	<? }?>
	<li><a href="<?=site_url()?>sms"><i class="icon-envelope-alt icon-large"></i>SMS</a></li>
	<li><a href="<?=site_url()?>web"><i class="icon-cloud icon-large"></i>Admin Web</a></li>
<? }?>
<li><a href="<?=site_url()?>siteman"><i class="icon-off icon-large"></i>Log Out</a></li>
</ul>
		
    </div>
</div>
