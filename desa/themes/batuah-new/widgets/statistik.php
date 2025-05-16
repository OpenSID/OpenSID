<?php defined('BASEPATH') || exit('No direct script access allowed'); ?>

<style type="text/css">
	.population-item:nth-child(1){background:url(<?= base_url("$this->theme_folder/$this->theme/assets/images/svgfile/man.svg") ?>) center right;background-size:40px auto;background-repeat:no-repeat;}
	.population-item:nth-child(2){background:url(<?= base_url("$this->theme_folder/$this->theme/assets/images/svgfile/woman.svg") ?>) center left;background-size:40px auto;background-repeat:no-repeat;}
</style>

<div class="col-default" style="margin-top:10px !important;">
	<div class="statistik-row bggrad-grey1 bordergrey">
	<div class="stathead flexcenter">
	<h1 class="bgcolor-1 flexcenter">Statistik Desa</h1>
	</div>
	<div class="statistik-inner">
		<div class="circle-area">
		
		<?php foreach ($stat_widget as $data): ?>
		<?php if ($data['jumlah'] != "-" AND $data['nama']!= "JUMLAH"): ?>
			<div class="statcircle bgwhite flexcenter">
			<div>
			<img class="flip-vertical-right" src="<?= base_url("$this->theme_folder/$this->theme/assets/images/pngfile/populasi.png") ?>"/>
			<p>Populasi</p>
			<h2 class="color-2"><?= $data['jumlah']?></h2>
			</div>
			</div>
		<?php endif; ?>
		<?php endforeach; ?>	
		
		</div>
		<div class="population flexcenter">
			<?php foreach ($stat_widget as $data): ?>
			<?php if ($data['jumlah'] != "-" AND $data['nama']!= "JUMLAH"): ?>	
				<div class="population-item">
					<div>
					<h2><?= $data['jumlah']?></h2>
					<h3><?= $data['nama']?></h3>
					</div>
				</div>
			<?php endif; ?>
			<?php endforeach; ?>
		</div>
		<div class="statleft">
			<a href="<?= site_url('data-wilayah') ?>">
			<div class="statleft1 statbox bgwhite flexleft bordergrey1 hover-height2">
				<p>Data <font class="desktop-only">Berdasarkan</font> Wilayah</p>
				<div class="staticon bgbiru bordergrey1 flexcenter">
				<svg viewBox="0 0 24 24"><path d="M14,11.5A2.5,2.5 0 0,0 16.5,9A2.5,2.5 0 0,0 14,6.5A2.5,2.5 0 0,0 11.5,9A2.5,2.5 0 0,0 14,11.5M14,2C17.86,2 21,5.13 21,9C21,14.25 14,22 14,22C14,22 7,14.25 7,9A7,7 0 0,1 14,2M5,9C5,13.5 10.08,19.66 11,20.81L10,22C10,22 3,14.25 3,9C3,5.83 5.11,3.15 8,2.29C6.16,3.94 5,6.33 5,9Z"/></svg>
				</div>
				<div class="stat-arrow"></div>
			</div>
			</a>
			<a href="<?= site_url('first/statistik/1') ?>">
			<div class="statleft2 statbox bgwhite flexleft bordergrey1 hover-height2">
				<p>Data <font class="desktop-only">Berdasarkan</font> Pekerjaan</p>
				<div class="staticon bgorange bordergrey1 flexcenter">
				<svg viewBox="0 0 24 24"><path d="M20,6H16V4A2,2 0 0,0 14,2H10C8.89,2 8,2.89 8,4V6H4C2.89,6 2,6.89 2,8V19A2,2 0 0,0 4,21H20A2,2 0 0,0 22,19V8A2,2 0 0,0 20,6M10,4H14V6H10V4M12,9A2.5,2.5 0 0,1 14.5,11.5A2.5,2.5 0 0,1 12,14A2.5,2.5 0 0,1 9.5,11.5A2.5,2.5 0 0,1 12,9M17,19H7V17.75C7,16.37 9.24,15.25 12,15.25C14.76,15.25 17,16.37 17,17.75V19Z"/></svg>
				</div>
				<div class="stat-arrow"></div>
			</div>
			</a>
			<a href="<?= site_url('first/statistik/14') ?>">
			<div class="statleft3 statbox bgwhite flexleft bordergrey1 hover-height2">
				<p>Data <font class="desktop-only">Statistik</font> Pendidikan <font class="desktop-only">Ditempuh</font></p>
				<div class="staticon bgtoska bordergrey1 flexcenter">
				<svg viewBox="0 0 24 24"><path d="M12,8A3,3 0 0,0 15,5A3,3 0 0,0 12,2A3,3 0 0,0 9,5A3,3 0 0,0 12,8M12,11.54C9.64,9.35 6.5,8 3,8V19C6.5,19 9.64,20.35 12,22.54C14.36,20.35 17.5,19 21,19V8C17.5,8 14.36,9.35 12,11.54Z"/></svg>
				</div>
				<div class="stat-arrow"></div>
			</div>
			</a>
		</div>
		<div class="statright">
			<a href="<?= site_url('first/statistik/2') ?>">
			<div class="statright1 statbox bgwhite flexright bordergrey1 hover-height2">
				<div class="staticon bgpink bordergrey1 flexcenter">
				<svg viewBox="0 0 24 24"><path d="M12,21.35L10.55,20.03C5.4,15.36 2,12.27 2,8.5C2,5.41 4.42,3 7.5,3C9.24,3 10.91,3.81 12,5.08C13.09,3.81 14.76,3 16.5,3C19.58,3 22,5.41 22,8.5C22,12.27 18.6,15.36 13.45,20.03L12,21.35Z"/></svg>
				</div>
				<p>Data <font class="desktop-only">Statistik Status</font> Perkawinan</p>
				<div class="stat-arrow"></div>
			</div>
			</a>
			<a href="<?= site_url('first/statistik/3') ?>">
			<div class="statright2 statbox bgwhite flexright bordergrey1 hover-height2">
				<div class="staticon bgyellow bordergrey1 flexcenter">
				<svg viewBox="0 0 24 24"><path d="M7.9 21.47C6 19.81 5.35 17.17 6.18 14.84L8.31 8.91C8.53 8.3 9.36 8.22 9.69 8.78L10 9.33C10.24 9.72 10.29 10.2 10.14 10.63L9.16 13.37L9.59 13.75L15.55 7C15.9 6.6 16.5 6.56 16.91 6.91C17.3 7.26 17.34 7.87 17 8.26L12.55 13.29L13.13 13.8L18.58 7.62C18.93 7.22 19.54 7.18 19.93 7.53C20.33 7.88 20.37 8.5 20 8.89L14.56 15.07L15.14 15.58L19.83 10.26C20.18 9.86 20.79 9.82 21.18 10.17S21.62 11.13 21.27 11.5L16.58 16.84L17.15 17.35L20.32 13.76C20.67 13.36 21.28 13.32 21.68 13.67S22.11 14.63 21.76 15L16.56 20.92C14.32 23.47 10.44 23.71 7.9 21.47M11.59 9.22L14.43 6C14.67 5.73 14.97 5.5 15.3 5.37L15.68 4.59C15.92 4.12 15.72 3.54 15.24 3.31C14.77 3.08 14.19 3.28 13.96 3.75L11.45 8.89C11.5 9 11.56 9.11 11.59 9.22M11 8L11 8.05L13.78 2.38C14 1.9 13.81 1.33 13.33 1.1C12.86 .865 12.28 1.06 12.05 1.54L9.41 6.95C10.06 7.06 10.63 7.43 11 8M4.77 14.33L6.9 8.4C7.17 7.65 7.8 7.14 8.55 6.97L10.69 2.58C10.92 2.1 10.72 1.53 10.25 1.3C9.77 1.07 9.2 1.26 8.97 1.74L5 9.84L4.5 9.59L4.71 6.68C4.75 6.23 4.57 5.78 4.25 5.46L3.79 5C3.32 4.57 2.55 4.86 2.5 5.5L2 11.79C1.87 13.83 2.77 15.78 4.35 17C4.33 16.12 4.46 15.21 4.77 14.33Z"/></svg>
				</div>
				<p>Data <font class="desktop-only">Statistik Berdasarkan</font> Agama</p>
				<div class="stat-arrow"></div>
			</div>
			</a>
			<a href="<?= site_url('first/dpt') ?>">
			<div class="statright3 statbox bgwhite flexright bordergrey1">
				<div class="staticon bgungu bordergrey1 flexcenter">
				<svg viewBox="0 0 24 24"><path d="M5 6C3.9 6 3 6.9 3 8S3.9 10 5 10 7 9.11 7 8 6.11 6 5 6M12 4C10.9 4 10 4.89 10 6S10.9 8 12 8 14 7.11 14 6 13.11 4 12 4M19 2C17.9 2 17 2.9 17 4S17.9 6 19 6 21 5.11 21 4 20.11 2 19 2M3.5 11C2.67 11 2 11.67 2 12.5V17H3V22H7V17H8V12.5C8 11.67 7.33 11 6.5 11H3.5M10.5 9C9.67 9 9 9.67 9 10.5V15H10V20H14V15H15V10.5C15 9.67 14.33 9 13.5 9H10.5M17.5 7C16.67 7 16 7.67 16 8.5V13H17V18H21V13H22V8.5C22 7.67 21.33 7 20.5 7H17.5Z"/></svg>
				</div>
				<p><font class="desktop-only">Daftar Pemilih Tetap</font><font class="mobile-only">Data Pemilih</font></p>
				<div class="stat-arrow"></div>
			</div>
			</a>
		</div>
	</div>
	</div>
</div>