<?php if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div class="slick_slider" style="margin-bottom:5px;">
    <?php foreach ($slider_gambar['gambar'] as $gambar) : ?>
        <?php if(is_file($slider_gambar['lokasi'].'sedang_'.$gambar['gambar'])) : ?>
        <div class="single_iteam">
            <style type="text/css">
                .slick_slider img {
                    width: 100%;
                }
                .slick_slider, .cycle-slideshow {
            		max-height: 300px;
            		border: 5px solid #e5e5e5;
                    display: block;
                    position: relative;
                    /*margin: 0px auto;*/
                    overflow: hidden;
                }
            </style>
            <img class="tlClogo" src="<?= base_url().$slider_gambar['lokasi'].'sedang_'.$gambar['gambar']?>" data-artikel="<?= $gambar['id']?>" onclick="tampil_artikel($(this).data('artikel'));">
        </div>
        <?php endif; ?>
    <?php endforeach; ?>
</div>
<script>
$('.tlClogo').bind('contextmenu', function(e) {
    return false;
});
</script>