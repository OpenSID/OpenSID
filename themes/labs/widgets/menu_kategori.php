<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div class="block block-themed block-mode-hidden">
    <div class="block-header bg-gd-sea">
        <h3 class="block-title"><i class="si si-pin"></i> Menu Kategori </h3>
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
        <div id="faq1" role="tablist" aria-multiselectable="true">
            <?php foreach($menu_kiri as $data){?>
            <div class="block block-bordered block-rounded mb-5">
                <div class="block-header" role="tab" id="<?= $data['nama'];?>">
                    <a class="font-w600 text-body-color-dark collapsed"
                        <?= $data['nama']; if(count($data['submenu'])>0) { echo "data-toggle='collapse'"; } ?>
                        data-parent="#faq1" href="<?= site_url()."first/kategori/".$data['id']?>" aria-expanded="false"
                        aria-controls="faq1_q<?= $data['id'];?>"><?= $data['nama']; if(count($data['submenu'])>0) { echo "<span class='caret'></span>"; } ?>
                    </a>
                </div>
                <?php if(count($data['submenu'])>0): ?>
                <div id="faq1_q<?= $data['id'];?>" class="collapse" role="tabpanel"
                    aria-labelledby="faq1_h<?= $data['id'];?>" style="">
                    <div class="block-content border-t">
                        <?php foreach($data['submenu'] as $submenu): ?>
                        <li><a href="<?= site_url()."first/kategori/".$submenu['id']?>"><?= $submenu['nama']?></a></li>
                        <?php endforeach; ?>
                    </div>

                </div>
                <?php endif; ?>
            </div>
            <?php }?>
        </div>
    </div>
</div>

