
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Sistem Informasi Desa</title>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<link rel="shortcut icon" href="<?php echo base_url()?>favicon.ico" />
<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?php echo base_url()?>rss.xml" />
<link href="<?php echo base_url()?>assets/css/screen.css" rel="stylesheet" type="text/css" />

<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>assets/css/style-gis.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>assets/css/noJS.css" /></noscript>


<script type="text/javascript" src="<?php echo base_url()?>assets/js/jquery-1.5.2.min.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>assets/js/jquery-ui-1.8.16.custom.min.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>assets/js/jquery-layout.js"></script>
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
<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?key=<?php echo $this->config->item('sid')['googlekey']?>"></script>
<script type="text/javascript" src="<?php echo base_url()?>assets/js/jscolor/jscolor.js"></script>
</head>
<body>
<div class="ui-layout-north" id="header">
<div id="sid-logo"><a href="<?php echo site_url()?>first" target="_blank"><img src="<?php echo LogoDesa($desa['logo']);?>" alt=""/></a></div>
<div id="sid-judul">SID Sistem Informasi Desa</div>
<div id="sid-info">Kab. <?php echo unpenetration($desa['nama_kabupaten'])?>, Kec. <?php echo unpenetration($desa['nama_kecamatan'])?>, Desa <?php echo unpenetration($desa['nama_desa'])?></div>
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
	<li><a href="<?php echo site_url()?>user_setting" target="ajax-modalz" rel="window-lok" header="Pengaturan Pengguna" title="Pengaturan Pengguna"><i class="icon-gear icon-large"></i>Setting User</a></li>
<?php  if($_SESSION['grup']==1 OR $_SESSION['grup']==2){?>
	<li><a href="<?php echo site_url()?>hom_desa"><i class="icon-home icon-large"></i>SID Home</a></li>
	<li><a href="<?php echo site_url()?>sid_core"><i class="icon-group icon-large"></i>Penduduk</a></li>
	<li><a href="<?php echo site_url()?>statistik"><i class="icon-bar-chart icon-large"></i>Statistik</a></li>
	<li><a href="<?php echo site_url()?>surat"><i class="icon-print icon-large"></i>Cetak Surat</a></li>
	<li><a href="<?php echo site_url()?>analisis"><i class="icon-dashboard icon-large"></i>Analisis</a></li>
<?php  }?>
<?php  if($_SESSION['grup']==1 OR $_SESSION['grup']==2){?>
	<?php  if($_SESSION['grup']==1){?>
		<li><a href="<?php echo site_url()?>man_user/clear"><i class="icon-user icon-large"></i>Pengguna</a></li>
		<li><a href="<?php echo site_url()?>database"><i class="icon-hdd icon-large"></i>Database</a></li>
	<?php  }?>
	<li><a href="<?php echo site_url()?>sms"><i class="icon-envelope-alt icon-large"></i>SMS</a></li>
	<li><a href="<?php echo site_url()?>web"><i class="icon-cloud icon-large"></i>Admin Web</a></li>
<?php  }?>
<li><a href="<?php echo site_url()?>siteman"><i class="icon-off icon-large"></i>Log Out</a></li>
</ul>

</div>
</div>
<div id="sidebar" >
</div>
<div class="ui-layout-center" id="wrapper">


<!-- NOTIFICATION
<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?key=<?php echo $this->config->item('sid')['googlekey']?>"></script>--><?php  if($_SESSION['success']==1): ?>
<script type="text/javascript">
$('document').ready(function(){
notification('success','Data Berhasil Disimpan')();
});
</script><?php  elseif($_SESSION['success']==-1): ?>
<script type="text/javascript">
$('document').ready(function(){
notification('error','Data Gagal Disimpan')();
});
</script><?php  elseif($_SESSION['success']==-2): ?>
<script type="text/javascript">
$('document').ready(function(){
notification('error','Simpan data gagal, nama id sudah ada!')();
});
</script><?php  elseif($_SESSION['success']==-3): ?>
<script type="text/javascript">
$('document').ready(function(){
notification('error','Simpan data gagal, nama id sudah ada!')();
});
</script><?php  endif; ?><?php  $_SESSION['success']=0; ?>
<!-- ************ -->
<!-- ************ -->

<div class="module-panel">
	<div class="contentm" style="overflow: hidden;">
		<?php if($_SESSION['grup']==1 OR $_SESSION['grup']==2){?>
		<a class="cpanel" href="<?php echo site_url()?>hom_desa/about">
			<img src="<?php echo base_url()?>assets/images/cpanel/go-home-5.png" alt=""/>
			<span>SID Home</span>
		</a>
		<a class="cpanel" href="<?php echo site_url()?>sid_core/clear">
			<img src="<?php echo base_url()?>assets/images/cpanel/preferences-contact-list.png" alt=""/>
			<span>Penduduk</span>
		</a>
		<a class="cpanel" href="<?php echo site_url()?>statistik">
			<img src="<?php echo base_url()?>assets/images/cpanel/statistik.png" alt=""/>
			<span>Statistik</span>
		</a>
		<a class="cpanel" href="<?php echo site_url()?>surat">
			<img src="<?php echo base_url()?>assets/images/cpanel/applications-office-5.png" alt=""/>
			<span>Cetak Surat</span>
		</a>
		<a class="cpanel" href="<?php echo site_url()?>analisis_master/clear">
			<img src="<?php echo base_url()?>assets/images/cpanel/analysis.png" alt=""/>
		<span>Analisis</span>
		</a>
		<a class="cpanel" href="<?php echo site_url()?>program_bantuan" title="Program Bantuan">
			<img src="<?php echo base_url()?>assets/images/cpanel/program.png" alt=""/>
		<span>Bantuan</span>
		</a>
		<a class="cpanel" href="<?php echo site_url()?>data_persil" title="Data Persil">
			<img src="<?php echo base_url()?>assets/images/cpanel/persil.png" alt=""/>
		<span>Persil</span>
		</a>

		<a class="cpanel" href="<?php echo site_url()?>plan">
			<img src="<?php echo base_url()?>assets/images/cpanel/plan.png" alt=""/>
			<span>Plan</span>
		</a>
		<a class="cpanel" href="<?php echo site_url()?>gis">
			<img src="<?php echo base_url()?>assets/images/cpanel/gis.png" alt=""/>
			<span>Peta</span>
		</a>
		<a class="cpanel" href="<?php echo site_url()?>sms">
			<img src="<?php echo base_url()?>assets/images/cpanel/mail-send-receive.png" alt=""/>
			<span>SMS</span>
		</a>
		<?php if($_SESSION['grup']==1){?>
		<a class="cpanel" href="<?php echo site_url()?>man_user/clear">
			<img src="<?php echo base_url()?>assets/images/cpanel/system-users.png" alt=""/>
			<span>Pengguna</span>
		</a>
		<a class="cpanel" href="<?php echo site_url()?>database">
			<img src="<?php echo base_url()?>assets/images/cpanel/database.png" alt=""/>
			<span>Database</span>
		</a>
		<?php }?>
		<?php }?>
		<a class="cpanel" href="<?php echo site_url()?>web">
			<img src="<?php echo base_url()?>assets/images/cpanel/message-news.png" alt=""/>
			<span>Admin Web</span>
		</a>
		<?php /*
		*/?>
	</div>
</div>
