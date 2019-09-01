<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div class="block block-themed block-mode-hidden">
    <div class="block-header bg-gd-sea">
        <h3 class="block-title"><i class="si si-notebook"></i> Sinergi Program</h3>
        <div class="block-options mr-15">
            <button type="button" class="btn-block-option" data-toggle="block-option" data-action="fullscreen_toggle">
                <i class="si si-size-fullscreen"></i>
            </button>
            <button type="button" class="btn-block-option" data-toggle="block-option" data-action="content_toggle">
                <i class="si si-arrow-up"></i>
            </button>
        </div>
    </div>
    
    <div class="block-content">
        <div class="js-slider text-center"  data-autoplay="true"  data-dots="false" data-arrows="true" data-slides-to-show="1">
        <?php foreach($sinergi_program as $key => $program) {
          $baris[$program['baris']][$program['kolom']] = $program;
        }
      ?>
      <?php foreach($baris as $baris_program) : ?>
        <?php $width = 100/count($baris_program)-count($baris_program)?>
            <?php foreach($baris_program as $key => $program) : ?>
            <div class="py-20">
                <a href="<?= $program['tautan']?>" target="_blank">
                    <img class="img-avatar" src="<?= base_url()?>desa/upload/widget/<?= $program['gambar']?>"></a>
                <div class="mt-10 font-w600"><?= $program['judul']?></div>
                <div class="font-size-sm text-muted"><?= $program['tautan']?></div>
            </div>
            <?php endforeach; ?>
            <?php endforeach; ?>
        </div>
    </div>
   
</div>