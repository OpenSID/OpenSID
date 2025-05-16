<?php if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<div class="iconcontainer bgwhite-trans1 bordergrey1 margin-topbottom-6">
<div class="iconrow">
	<div class="icon-item bgwhite bordergrey1" data-toggle="modal" data-target="#identitas">
		<div class="icon-item-inner"><img src="<?= base_url("$this->theme_folder/$this->theme/images/iconmenu/identitas.svg") ?>"/><p>Identitas<br/><?= ucwords($this->setting->sebutan_desa); ?></p></div>
	</div>
	<div class="icon-item bgwhite bordergrey1" data-toggle="modal" data-target="#aparatur">
		<div class="icon-item-inner"><img src="<?= base_url("$this->theme_folder/$this->theme/images/iconmenu/aparatur.svg") ?>"/><p>Aparatur<br/><?= ucwords($this->setting->sebutan_desa); ?></p></div>
	</div>
	<div class="icon-item bgwhite bordergrey1">
		<a href="<?= site_url('data-wilayah') ?>">
		<div class="icon-item-inner"><img src="<?= base_url("$this->theme_folder/$this->theme/images/iconmenu/statistik.svg") ?>"/><p>Statistik<br/>Wilayah</p></div>
		</a>
	</div>
	<div class="icon-item bgwhite bordergrey1">
		<a href="<?= site_url('galeri') ?>">
		<div class="icon-item-inner"><img src="<?= base_url("$this->theme_folder/$this->theme/images/iconmenu/camera.svg") ?>"/><p>Galeri<br/>Foto</p></div>
		</a>
	</div>
	<div class="icon-item bgwhite bordergrey1">
		<a href="<?= site_url('pembangunan') ?>">
		<div class="icon-item-inner"><img src="<?= base_url("$this->theme_folder/$this->theme/images/iconmenu/pembangunan.svg") ?>"/><p>Bangun<br/><?= ucwords($this->setting->sebutan_desa); ?></p></div>
		</a>
	</div>
	<div class="icon-item bgwhite bordergrey1">
		<a href="<?= site_url('lapak') ?>">
		<div class="icon-item-inner"><img src="<?= base_url("$this->theme_folder/$this->theme/images/iconmenu/lapak.svg") ?>"/><p>Lapak<br/><?= ucwords($this->setting->sebutan_desa); ?></p></div>
		</a>
	</div>
	<div class="icon-item bgwhite bordergrey1">
		<a href="<?= site_url('peta') ?>">
		<div class="icon-item-inner"><img src="<?= base_url("$this->theme_folder/$this->theme/images/iconmenu/peta.svg") ?>"/><p>Peta<br/><?= ucwords($this->setting->sebutan_desa); ?></p></div>
		</a>
	</div>
	<div class="icon-item bgwhite bordergrey1">
	<a href="<?= site_url('arsip') ?>">
		<div class="icon-item-inner"><img src="<?= base_url("$this->theme_folder/$this->theme/images/iconmenu/arsip.svg") ?>"/><p>Arsip<br/>Artikel</p></div>
	</a>	
	</div>
	<div class="icon-item bgwhite bordergrey1" data-toggle="modal" data-target="#lapor">
		<div class="icon-item-inner"><img src="<?= base_url("$this->theme_folder/$this->theme/images/iconmenu/pengaduan.svg") ?>"/><p>Ruang<br/>Lapor</p></div>
	</div>
</div>
</div>


