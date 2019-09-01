<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div class="block block-themed block-mode-hidden">
    <div class="block-header bg-gd-sea">
        <h3 class="block-title"><i class="si si-feed"></i>  Media Sosial</h3>
        <div class="block-options mr-15">
        <button
                type="button"
                class="btn-block-option"
                data-toggle="block-option"
                data-action="fullscreen_toggle">
                <i class="si si-size-fullscreen"></i>
            </button>
            <button
                type="button"
                class="btn-block-option"
                data-toggle="block-option"
                data-action="content_toggle">
                <i class="si si-arrow-up"></i>
            </button>
        </div>
    </div>
    <div class="block-content tab-content pb-20">
        <!-- Tiles Slider 2 -->
        <div class="js-slider text-center" data-autoplay="true" data-dots="true" data-arrows="true" data-slides-to-show="3">
		<?php foreach ($sosmed As $data): ?>
            <div class="block text-center bg-white mb-0">
                <div class="py-20">
				
				<?php if (!empty($data["link"])): ?>
					<a href="<?= $data['link']?>" target="_blank">
						<img src="<?= base_url().'assets/front/'.$data['gambar'] ?>" alt="<?= $data['nama'] ?>" style="width:50px;height:50px;"/>
					</a>
				<?php endif; ?>

                </div>   
            </div>
			<?php endforeach; ?>
        </div>
        <!-- END Tiles Slider 2 -->
    </div>
</div>
