<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div class="block block-themed block-mode-hidden">
    <div class="block-header bg-gd-sea">
        <h3 class="block-title"><i class="si si-users"></i>  Aparatur <?= ucwords($this->setting->sebutan_desa)?></h3>
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
    <div class="block-content tab-content">
        <!-- Tiles Slider 2 -->
        <div class="js-slider slick-nav-hover" data-dots="true" data-arrows="false" data-autoplay="true">
            <?php foreach($aparatur_desa as $data) : ?>
            <div class="block text-center bg-white mb-0">
                <div class="py-20">
                    <?php if(AmbilFoto($data['foto'],"besar") AND is_file(LOKASI_USER_PICT.$data['foto'])) : ?>
                    <img class="img-fluid" width="329px" src="<?= AmbilFoto($data['foto'],"besar") ?>">
                    <?php endif; ?>
                </div>
                <div class="font-size-h5 font-w600"><?= $data['nama'];?></div>
                <div class="text-muted"><?= $data['jabatan'];?></div>
            </div>
            <?php endforeach; ?>
        </div>
        <!-- END Tiles Slider 2 -->
    </div>
</div>
