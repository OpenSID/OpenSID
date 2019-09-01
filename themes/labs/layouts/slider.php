<?php if (count($slide_galeri)>0 OR count($slide_artikel)>0): ?>
<div class="js-slider slick-nav-black slick-dotted-inner slick-dotted-white" data-dots="true" data-arrows="true"
    autoplay="true">
    <?php foreach ($slider_gambar['gambar'] as $gambar) : ?>
    <?php if(is_file($slider_gambar['lokasi'].'sedang_'.$gambar['gambar'])) : ?>
    <div class="ribbon ribbon-right ribbon-bottom ribbon-bookmark ribbon-primary">
        <a href="<?= site_url('first/artikel/'.$gambar['id']) ?>">
            <img width="758px" height="379px" class="img-thumb"
                src="<?= base_url().$slider_gambar['lokasi'].'sedang_'.$gambar['gambar']?>" alt="">
        </a>
        <div class="js-animation-object animated fadeInRight ribbon-box">
            <i class="fa fa-check"></i> <?= $gambar['judul']; ?>
        </div>
    </div>
    <?php endif; ?>
    <?php endforeach; ?>
</div>
<?php endif; ?>