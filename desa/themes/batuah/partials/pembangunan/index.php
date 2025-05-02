<?php if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<div class="col-default">
	<div class="bodypage bgwhite bordergrey1">
		<div class="headstat-lebar flexcenter">
			<img src="<?= base_url("$this->theme_folder/$this->theme/images/pembangunan.png") ?>"/>
			<div>
			<h1 class="color-1">Pembangunan</h1>
			<h2><?= ucwords($this->setting->sebutan_desa); ?></h2>
			</div>
		</div>
		<div class="pagebox">
			<?php if ($pembangunan): ?>
				<?php foreach ($pembangunan as $data): ?>
					<div class="pemb-row bggrey1">
					<div class="rowsame forhover">
						<div class="pemb-image">
						<img src="<?= is_file(LOKASI_GALERI . $data->foto) == TRUE ? base_url() . LOKASI_GALERI . $data->foto : base_url("$this->theme_folder/$this->theme/assets/images/no-image.jpg") ?>">
						<div class="hoverimage flexcenter">
							<div class="hoverimage-left bgcolor-1 hover-width"></div>
							<div class="hoverimage-right bgcolor-1 hover-width"></div>
							<div>
								<a href="<?= base_url() . LOKASI_GALERI . $data->foto; ?>"  data-fancybox="images">
								<div class="hoverimage-icon flexcenter hover-height">
								<svg viewBox="0 0 24 24"><path d="M9.5,13.09L10.91,14.5L6.41,19H10V21H3V14H5V17.59L9.5,13.09M10.91,9.5L9.5,10.91L5,6.41V10H3V3H10V5H6.41L10.91,9.5M14.5,13.09L19,17.59V14H21V21H14V19H17.59L13.09,14.5L14.5,13.09M13.09,9.5L17.59,5H14V3H21V10H19V6.41L14.5,10.91L13.09,9.5Z"/></svg>
								</div>
								</a>
							</div>
						</div>
						</div>
						<div class="pemb-title flexleft">
							<div>
							<div class="pemb-padding">
							<h2><?= $data->judul ?></h2>
							<h3>Tahun : <span class="color-2"><?= $data->tahun_anggaran ?></span></h3>
							<h3 class="desktop-only">Anggaran : <span class="color-2">Rp. <?= number_format($data->anggaran,0) ?></span></h3>
							<a href="<?= site_url("pembangunan/$data->slug"); ?>" title="Detail">
								<div class="flexleft" style="margin:10px 0 0;">
									<div class="b-btn bgcolor-1 flexcenter"><p>Lihat Detail...</p></div>
								</div>
							</a>
							</div>
							</div>
						</div>
					</div>
					<a href="https://www.google.com/maps/dir//<?= $data->lat?>,<?= $data->lng?>" title="Lokasi" rel="noopener noreferrer" target="_blank">
					<div class="pemb-lokasi bgwhite bordergrey1 flexcenter">
						<svg viewBox="0 0 24 24">
							<path d="M12,11.5A2.5,2.5 0 0,1 9.5,9A2.5,2.5 0 0,1 12,6.5A2.5,2.5 0 0,1 14.5,9A2.5,2.5 0 0,1 12,11.5M12,2A7,7 0 0,0 5,9C5,14.25 12,22 12,22C12,22 19,14.25 19,9A7,7 0 0,0 12,2Z" />
						</svg>
					</div>
					</a>
					</div>
				<?php endforeach; ?>
				<?php $this->load->view("$folder_themes/commons/page"); ?>
			<?php else: ?>
				<div class="blankdata">
					<img style="width:100%;height:auto;display:block;border-radius:5px;" src="<?= base_url("$this->theme_folder/$this->theme/images/nature.jpg") ?>"/>
					<div class="blankdata-title">
					<h2>Mohon maaf,</h2>
						<h3>Data Pembangunan belum tersedia untuk saat ini...</h3>
					</div>
					<div class="blankdata-bottom">
						<img src="<?= base_url("$this->theme_folder/$this->theme/images/girl.png") ?>"/>
					</div>
				</div>
			<?php endif;?>
		</div>
	</div>
</div>	
