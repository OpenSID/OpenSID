<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php if($artikel) : ?>
<?php if ($w_cos): ?>
	<?php foreach ($w_cos as $data): ?>
	<?php $widget = trim($data['isi']) ?>
	<?php if ($data["jenis_widget"] == 1): ?>
	<?php if ($data["isi"] == "galeri.php"): ?>
	<div class="module-gallery">
	<?php if ($w_gal): ?>
		<div class="relative-row ptb-10">
			<div class="container-page">
				<div class="fotohome border-grey-soft bg-white-transparent">
					<div class="margin-min5">
						<div class="fotohome-left">
							<div class="pd-lr-5">
								<div class="fotohome-label" style="background-image:url(<?= gambar_desa($desa['kantor_desa'], TRUE)?>);">
									<div class="fotohome-label2">
										<img class="yall_lazy" src="<?= base_url("$this->theme_folder/$this->theme/assets/images/icon/spinner.svg") ?>" data-src="<?= gambar_desa($desa['kantor_desa'], TRUE)?>" alt=""/>
									</div>
									<img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/galeri.svg") ?>" alt=""/>
								</div>
							</div>
						</div>
						<div class="fotohome-right">
							<div class="pd-lr-5">
								<div class="margin-min5">
									<div class="carouselcustom js-flickity" data-flickity='{ "autoPlay": false, "wrapAround": true, "cellAlign": "left" }'>
										<?php foreach ($w_gal As $data): ?>
											<div class="carouselcustom-item">
												<div class="mlr-5">
													<a href="<?= site_url("first/sub_gallery/$data[id]"); ?>">
														<div class="image-fotohome">
															<?php if (is_file(LOKASI_GALERI . "sedang_" . $data['gambar'])): ?>
																<img class="yall_lazy" src="<?= base_url("$this->theme_folder/$this->theme/assets/images/icon/spinner.svg") ?>" data-src="<?= AmbilGaleri($data['gambar'],'sedang')?>" alt="Album : <?= "$data[nama]" ?>">
															<?php else: ?>
																<img class="yall_lazy" src="<?= base_url("$this->theme_folder/$this->theme/assets/images/icon/spinner.svg") ?>" data-src="<?= $latar_website ? $latar_website : base_url('assets/front/css/images/latar_website.jpg'); ?>" alt=""/>
																<div class="logo-no-image"><img src="<?= gambar_desa($desa['logo']);?>" alt=""/></div>
															<?php endif; ?>	
															<div class="bottom-gradient"></div>
															<div class="foto-title" style="text-align:center;">
																<h2><?= "$data[nama]" ?></h2>
															</div>
														</div>	
													</a>
												</div>
											</div>
										<?php endforeach; ?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	<?php endif; ?>	
	</div>
	<?php endif; ?>
	<?php endif; ?>
	<?php endforeach; ?>
	<?php endif; ?>
<?php endif; ?>	
<?php $this->load->view($folder_themes . "/partials/video/index"); ?>
