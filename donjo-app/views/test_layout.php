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

		<script type="text/javascript" src="<?php echo base_url()?>assets/js/jquery-1.12.4.min.js"></script>
		<script type="text/javascript" src="<?php echo base_url()?>assets/js/jquery-layout.js"></script>
		<script type="text/javascript" src="<?php echo base_url()?>assets/js/donjoscript/donjo.ui.layout.js"></script>
</head>
<body>
<div class="ui-layout-north" id="header">
	<div id="sid-logo"><a href="<?php echo site_url()?>first" target="_blank"><img src="<?php echo LogoDesa($desa['logo']);?>" alt=""/></a></div>
	<div id="sid-judul">SID Sistem Informasi Desa</div>
	<div id="sid-info"><?php echo unpenetration($desa['nama_desa'])?>, Kec. <?php echo unpenetration($desa['nama_kecamatan'])?>,  <?php echo unpenetration($desa['nama_kabupaten'])?></div>
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
				<li><a href="<?php echo site_url()?>modul/clear"><i class="icon-gear icon-large"></i>Pengaturan</a></li>
				<li><a href="<?php echo site_url()?>hom_desa"><i class="icon-home icon-large"></i>SID Home</a></li>
				<li><a href="<?php echo site_url()?>penduduk"><i class="icon-group icon-large"></i>Penduduk</a></li>
				<li><a href="<?php echo site_url()?>statistik"><i class="icon-bar-chart icon-large"></i>Statistik</a></li>
				<li><a href="<?php echo site_url()?>surat"><i class="icon-print icon-large"></i>Cetak Surat</a></li>
				<li><a href="<?php echo site_url()?>analisis"><i class="icon-dashboard icon-large"></i>Analisis</a></li>
				<li><a href="<?php echo site_url()?>program_bantuan"><i class="icon-folder-open icon-large"></i>Program</a></li>
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
	<div class="ui-layout-center">Inner Center
		<p><a href="http://layout.jquery-dev.com/demos.html">Go to the Demos page</a></p>
		<p>* Pane-resizing is disabled because ui.draggable.js is not linked</p>
		<p>* Pane-animation is disabled because ui.effects.js is not linked</p>
	</div>
	<div class="ui-layout-north">Inner North</div>
	<div class="ui-layout-south">Inner South</div>
	<div class="ui-layout-east">Inner East</div>
	<div class="ui-layout-west">Inner West</div>

	<!-- NOTIFICATION -->
<!-- 	<?php  if(@$_SESSION['success']==1): ?>
		<script type="text/javascript">
			$('document').ready(function(){
			notification('success','Data Berhasil Disimpan')();
			});
		</script>
	<?php  elseif(@$_SESSION['success']==-1): ?>
		<script type="text/javascript">
			$('document').ready(function(){
			notification('error','Data Gagal Disimpan <?php echo $_SESSION["error_msg"]?>')();
			});
		</script>
	<?php  elseif(@$_SESSION['success']==-2): ?>
		<script type="text/javascript">
			$('document').ready(function(){
			notification('error','Simpan data gagal, nama id sudah ada!')();
			});
		</script>
	<?php  elseif(@$_SESSION['success']==-3): ?>
		<script type="text/javascript">
			$('document').ready(function(){
			notification('error','Simpan data gagal, nama id sudah ada!')();
			});
		</script><?php  endif; ?>
	<?php  $_SESSION['success']=0; ?>
 -->	<!-- ************ -->

<!-- 	<div class="module-panel">
		<div class="contentm" style="overflow: hidden;">
			<?php foreach ($modul AS $mod){?>
				<a class="cpanel <?php if($modul_ini==$mod['id']){?>selected<?php }?>" href="<?php echo site_url()?><?php echo $mod['url']?>">
					<img src="<?php echo base_url()?>assets/images/cpanel/<?php echo $mod['ikon']?>" alt=""/>
					<span><?php echo $mod['modul']?></span>
				</a>
			<?php } ?>
	  </div>
	</div>
 -->
</div>
</body>
</html>