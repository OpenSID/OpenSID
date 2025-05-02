<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php if (theme_config('rotate_logo')) : ?>
<style>
@keyframes spin {
    0% {
        transform: rotateY(0deg);
    }
    100% {
        transform: rotateY(360deg);
    }
}
.rotating-image {
    display: block;
    margin: auto;
    animation: spin 5s linear infinite;
}
</style>
<?php endif; ?>
<div class="mainheader bg-color1">
	<div class="mainheader-cover-image1" style="background-image:url(<?= $latar_website ? $latar_website : base_url($this->theme_folder.'/'.$this->theme.'/assets/images/header.jpg'); ?>);"></div>
	<div class="mainheader-cover-image2" style="background-image:url(<?= $latar_website ? $latar_website : base_url($this->theme_folder.'/'.$this->theme.'/assets/images/header.jpg'); ?>);"></div>
	<div class="mainheader-cover-color">
		<ul class="bg-bubbles">
			<li></li>
			<li></li>
			<li></li>
		</ul>
	</div>
	<div class="container-page">
		<div class="mainheader-inner">
			<div class="mainheader-inner2 flexcenter">
				<div class="logo">
					<a href="<?= site_url(); ?>">
						<div class="flexleft">
							<div class="logo-image">
								<?php if ($w_cos): ?>
									<?php $photo_found = false; ?>
									<?php foreach ($w_cos as $data): ?>
										<?php if (strtoupper(strip_tags($data['judul'])) == strtoupper('logo')): 
										$photo_found = true;
										break; ?>
										<?php endif; ?>
									<?php endforeach; ?>
									<?php if ($photo_found): ?>
										<?php foreach ($w_cos as $data): ?>
											<?php if (strtoupper(strip_tags($data['judul'])) == strtoupper('logo')): ?>
												<img src="<?= to_base64(LOKASI_GAMBAR_WIDGET . $data['foto']); ?>" alt="">
											<?php endif; ?>
										<?php endforeach; ?>
									<?php elseif (IS_PREMIUM && IS_240710 && theme_config('logo_header') == TRUE) : ?>
										<a href="<?= theme_config('url_logo_header'); ?>" class="mh-100"><img src="<?= base_url(theme_config('logo_header')); ?>" height="100px" alt="" <?= theme_config('rotate_logo') ? "class='rotating-image'" : ""; ?>/></a>
									<?php else: ?>
										<img src="<?= gambar_desa($desa['logo']);?>" alt="" <?= theme_config('rotate_logo') ? "class='rotating-image'" : ""; ?>/>
									<?php endif; ?>
								<?php endif; ?>
							</div>
							<div class="logo-title">
								<h1><?= ucwords($this->setting->sebutan_desa); ?> <?= ucwords(($desa['nama_desa']) ? ' ' . $desa['nama_desa'] : ''); ?></h1> 
								<h2><?= ucwords($this->setting->sebutan_kecamatan_singkat." ".$desa['nama_kecamatan'])?>, <?= ucwords($this->setting->sebutan_kabupaten_singkat." ".$desa['nama_kabupaten'])?><br/><?= ucwords("Prov. ".$desa['nama_propinsi'])?></h2>
								<?php if (!empty(setting('motto_desa'))) : ?>
									<h2>Motto <?= ucwords($this->setting->sebutan_desa); ?> : <span class="small-screen"><br></span><?= setting('motto_desa') ?></h2>
								<?php endif ?>
							</div>
						</div>
					</a>
				</div>
				<div class="header-right flexright">
					<div class="big-screen">
						<h2>
							<?php if (theme_config('tag_header')) : ?>
							<?= ucwords(setting('branding_desa')); ?>
							<?php else : ?>
							<?= ucwords($this->setting->sebutan_desa) . ' ' . ucwords($desa['nama_desa']); ?>
							<?php endif ?>
						</h2>
					</div>
					<?php $this->load->view($folder_themes . "/partials/event/countdown"); ?>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="bg-grey-medium">
	<div class="mainmenu">
		<div class="container-page">
			<div class="mainmenu-inner bg-mainmenu">
				<div class="nav-wrapper large-nav">
					<ul class="clearlist local-scroll" style="margin:0;padding:0;">
						<div class="to-home flexcenter"><a style="border:none;" href="<?= site_url(); ?>"><img style="float:left;height:20px;opacity:0.6;"src="<?= base_url("$this->theme_folder/$this->theme/assets/images/icon/home.svg") ?>" alt=""/></a></div>
						<div class="carouselcustom js-flickity" data-flickity='{ "autoPlay": false, "groupCells": true, "groupCells": 1, "contain": true, "cellAlign": "left"}'>
						<div class="carouselcustom-item"></div>
							<?php if (IS_PREMIUM || (!IS_PREMIUM && IS_240810)) : ?>
								<?php foreach(menu_tema() as $data): ?>
									<div class="carouselcustom-item ">
										<?php $this->load->view($folder_themes . "/partials/home/menu_header", ['data' => $data]); ?>
									</div>
								<?php endforeach; ?>
							<?php else : ?>
							<?php foreach($menu_atas as $data): ?>
								<div class="carouselcustom-item ">
									<?php if(count($data['submenu'] ?? []) > 0): ?>
										<li><a style="white-space: nowrap;" class="menu-down" href="<?= count($data['submenu'] ?? []) > 0 ? 'javascript:void(0);' : $data['link'] ?>"><?= $data['nama']; if(count($data['submenu'] ?? []) > 0) { echo "<span class='caret'></span>"; } ?></a>
											<ul class="nav-sub bg-navsub">
												<?php foreach($data['submenu'] as $submenu): ?>
													<li><a href="<?= $submenu['link']?>"><?= $submenu['nama']?></a></li>
												<?php endforeach; ?>
											</ul>
										</li>
									<?php else: ?>
										<li><a style="white-space: nowrap;" href="<?= $data['link']?>"><?= $data['nama']?></a></li>
									<?php endif; ?>
								</div>
							<?php endforeach; ?>
							<?php endif; ?>
						</div>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>
