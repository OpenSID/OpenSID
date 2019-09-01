<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div class="block block-themed block-mode-hidden">
    <div class="block-header bg-gd-sea block-header-default">
        <h3 class="block-title"><i class="si si-book-open"></i>  Artikel</h3>
        <div class="block-options">
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
    <div class="block-content full-content">
        <!-- Block Tabs Animated Fade -->
        <div class="block-header block-header-default">
            <ul class="nav nav-pills push align-items-center" data-toggle="tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" href="#terkini">Terkini</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#random">Random</a>
                </li>
            </ul>
        </div>
        <div class="block-content" data-toggle="slimscroll" data-height="300px" data-color="#ef5350" data-opacity="1"
            data-always-visible="true">
            <div class="tab-pane fade show active" id="terkini" role="tabpanel">
                <?php foreach ($arsip as $l): ?>
                <div class="media my-20">
                    <?php if (is_file(LOKASI_FOTO_ARTIKEL."kecil_$l[gambar]")): ?>
                    <img class="img-avatar img-avatar48 d-flex mr-20"
                        src="<?= base_url("desa/upload/artikel/kecil_$l[gambar]")?>" alt="">
                    <?php else: ?>
                    <img class="img-avatar img-avatar48 d-flex mr-20"
                        src="<?= base_url("assets/images/404-image-not-found.jpg")?>" alt="">
                    <?php endif;?>
                    <div class="media-body">
                        <p class="mb-5"><a class="link-effect font-w600" href="<?= site_url("first/artikel/$l[id]")?>">
                                <p><?= $l['judul']?></p>
                                <div class="font-size-sm">
                                    <span class="text-muted mr-5"><?= tgl_indo2($l['tgl_upload']) ?></span>
                                </div>
                    </div>
                </div>
                <hr>
                <?php endforeach; ?>
            </div>

            <div class="tab-pane fade" id="random" role="tabpanel">
                <?php foreach ($arsip_rand as $l): ?>
                <div class="media my-20">
                    <?php if (is_file(LOKASI_FOTO_ARTIKEL."kecil_$l[gambar]")): ?>
                    <img class="img-avatar img-avatar48 d-flex mr-20"
                        src="<?= base_url("desa/upload/artikel/kecil_$l[gambar]")?>" alt="">
                    <?php else: ?>
                    <img class="img-avatar img-avatar48 d-flex mr-20"
                        src="<?= base_url("assets/images/404-image-not-found.jpg")?>" alt="">
                    <?php endif;?>
                    <div class="media-body">
                        <p class="mb-5"><a class="link-effect font-w600" href="<?= site_url("first/artikel/$l[id]")?>">
                                <p><?= $l['judul']?></p>
                                <div class="font-size-sm">
                                    <span class="text-muted mr-5"><?= tgl_indo2($l['tgl_upload']) ?></span>
                                </div>
                    </div>
                </div>
                <hr>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
</div>