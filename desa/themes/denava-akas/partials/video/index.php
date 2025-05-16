<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php
$vid = [];
$i = 1;
while ($link_youtube = theme_config("link_youtube_$i")) {
	$link_youtube = extract_youtube_id($link_youtube);
		$vid[] = [];
	$i++;
}
?>
<?php if(theme_config('link_youtube', false)) : ?>
	<div class="module-gallery">
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
									<div class="bottom-gradient"></div>
									<img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/latar.png") ?>" alt=""/>
								</div>
							</div>
						</div>
						<div class="fotohome-right">
							<div class="pd-lr-5">
								<div class="margin-min5">
									<div class="carouselcustom js-flickity" data-flickity='{ "autoPlay": false, "wrapAround": true, "cellAlign": "left" }'>
									<?php shuffle($vid); ?>
										<?php 
										$youtubes = array(
											'https://www.youtube.com/embed/'.extract_youtube_id(theme_config("link_youtube_1")),
											'https://www.youtube.com/embed/'.extract_youtube_id(theme_config("link_youtube_2")),
											'https://www.youtube.com/embed/'.extract_youtube_id(theme_config("link_youtube_3"))
										);
										shuffle($youtubes);
										?>
										<?php foreach($vid as $index => $video) : ?>
										<?php 
											$random_youtubes = $youtubes;
											shuffle($random_youtubes);
										?>
										<?php foreach ($random_youtubes as $youtube) : ?>
										<div class="carouselcustom-item">
											<div class="mlr-5">
												<div class="image-fotohome">
													<iframe width="100%" height="100%" scrolling="no" frameborder="no" src="<?= $youtube ?>" loading="lazy"></iframe>
												</div>	
											</div>
										</div>				
										<?php if (!IS_PREMIUM || (IS_PREMIUM && IS_240302)) break; ?>
										<?php endforeach; ?>
										<?php break; ?>
										<?php endforeach; ?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php endif ?>
