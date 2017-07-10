<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>assets/bootstrap/css/bootstrap.bar.css">
<script type="text/javascript" src="<?php echo base_url()?>assets/bootstrap/js/jquery.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>assets/bootstrap/js/bootstrap.js"></script>
<link type='text/css' href="<?php echo base_url()?>assets/front/css/default.css" rel='Stylesheet' />
<link type='text/css' href="<?php echo base_url().'themes/'.$this->theme.'/css/default.css'?>" rel='Stylesheet' />
<?php if(is_file("desa/css/".$this->theme."/desa-default.css")):?>
  <link type='text/css' href="<?php echo base_url()?>desa/css/<?php echo $this->theme ?>/desa-default.css" rel='Stylesheet' />
<?php endif; ?>

<nav class="navbar navbar-default">
	<div class="container-fluid">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="<?php echo site_url(); ?>first/"><span id="header_sebutan_desa"><?php echo ucwords($this->setting->sebutan_desa)." "?></span><?php echo ucwords($desa['nama_desa'])?></a>
		</div>
		<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
			<ul class="nav navbar-nav">				
				<li><a href="<?php echo site_url()."first"?>"><i class="fa fa-home fa-lg"></i> Beranda</a></li>
				<li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="<?php echo site_url()."first"?>/artikel/98"><i class="fa fa-address-card"></i> Profil <span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li><a href="<?php echo site_url()."first"?>/artikel/98">Profil Wilayah Desa</a></li>
						<li><a href="<?php echo site_url()."first"?>/artikel/99">Sejarah Desa</a></li>
						<li><a href="<?php echo site_url()."first"?>/artikel/120">SID Hadakewa</a></li> 
					</ul>
				</li>
				<li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="<?php echo site_url()."first"?>/artikel/85"><i class="fa fa-university"></i> Pemerintahan <span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li><a href="<?php echo site_url()."first"?>/artikel/93">Visi Misi</a></li>
						<li><a href="<?php echo site_url()."first"?>/artikel/2">Pemerintah Desa</a></li>
					</ul>
				</li>
				<li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="<?php echo site_url()."first"?>/artikel/38"><i class="fa fa-sitemap"></i> Lembaga <span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li><a href="<?php echo site_url()."first"?>/artikel/37">B P D</a></li>
						<li><a href="<?php echo site_url()."first"?>/artikel/62">L P M D</a></li>
						<li><a href="<?php echo site_url()."first"?>/artikel/63">P K K</a></li> 
						<li><a href="<?php echo site_url()."first"?>/artikel/64">Karang Taruna</a></li>
					</ul>
				</li>
				<li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-bar-chart"></i> Data Desa <span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li><a href="<?php echo site_url()."first"?>/statistik/0">Data Pendidikan Dalam KK</a></li>
						<li><a href="<?php echo site_url()."first"?>/statistik/14">Data Pendidikan Ditempuh</a></li>
						<li><a href="<?php echo site_url()."first"?>/statistik/1">Data Pekerjaan Penduduk</a></li> 
						<li><a href="<?php echo site_url()."first"?>/statistik/3">Data Agama</a></li>
						<li><a href="<?php echo site_url()."first"?>/statistik/4">Data Jenis Kelamin</a></li>
						<li><a href="<?php echo site_url()."first"?>/statistik/13">Data Kelompok Umur</a></li>
						<li><a href="<?php echo site_url()."first"?>/statistik/17">Data Akta Kelahiran</a></li>
					</ul>
				</li> 				
				<li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-briefcase"></i> Layanan <span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li><a href="<?php echo site_url()."first"?>/artikel/137">Akta Perkawinan</a></li>
						<li><a href="<?php echo site_url()."first"?>/artikel/138">Akta Perceraian</a></li>
						<li><a href="<?php echo site_url()."first"?>/artikel/139">Akta Kematian</a></li> 
						<li><a href="<?php echo site_url()."first"?>/artikel/141">Akta Kelahiran</a></li>
						<li><a href="<?php echo site_url()."first"?>/artikel/140">Kartu Keluarga</a></li>
						<li><a href="<?php echo site_url()."first"?>/artikel/142">Pembuatan E-KTP</a></li>
						<li><a href="<?php echo site_url()."first"?>/artikel/143">Surat Keterangan</a></li>  
					</ul>
				</li> 
			</ul>
			<ul class="nav navbar-nav navbar-right">
				<li><a href="<?php echo site_url('siteman') ?>"><i class="fa fa-lock fa-lg"></i> Login Admin</a></li>
			</ul>
		</div><!-- /.navbar-collapse -->
	</div>
</nav>


