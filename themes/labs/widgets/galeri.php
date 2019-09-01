<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div class="block block-themed block-mode-hidden ">
    <div class="block-header bg-gd-sea">
        <h3 class="block-title"><i class="si si-picture"></i>  Galeri Desa</h3>
        </h3>
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
    <div class="block-content">
    <div class="js-slider slick-nav-black slick-nav-hover" data-dots="true" data-arrows="true" data-slides-to-show="2" data-center-mode="true" data-autoplay="true" data-autoplay-speed="3000">
            <?php foreach ($w_gal As $data): ?>
            <?php if (is_file(LOKASI_GALERI . "sedang_" . $data['gambar'])): ?>
            <div>
                <a href="<?= site_url("first/sub_gallery/$data[id]"); ?>">
                    <img class="img-fluid" src="<?= AmbilGaleri($data['gambar'],'kecil')?>" alt="">
                </a>
            </div>
            <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
</div>
