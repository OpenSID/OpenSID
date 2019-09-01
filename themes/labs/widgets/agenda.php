<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php if ($agenda): ?>
<div class="block block-themed  block-mode-hidden">
    <div class="block-header bg-gd-sea block-header-default">
        <h3 class="block-title"> <i class="si si-calendar"></i> Agenda Desa</h3>
        <div class="block-options">
            <button type="button" class="btn-block-option" data-toggle="block-option" data-action="fullscreen_toggle">
                <i class="si si-size-fullscreen"></i>
            </button>
            <button type="button" class="btn-block-option" data-toggle="block-option" data-action="content_toggle">
                <i class="si si-arrow-up"></i>
            </button>
        </div>
    </div>
    <div class="block-content">
        <div class="block-content" data-toggle="slimscroll" data-height="300px" data-color="#10101a" data-opacity="1"
            data-always-visible="true">
            <ul class="list list-activity">
                <?php foreach ($agenda as $l): ?>
                <li>
                    <i class="si si-event text-danger"></i>
                    <div class="font-w600 pb-10"><a class="link-effect"
                            href="<?= site_url("first/artikel/$l[id_artikel]")?>"><?= $l['judul']?></a></div>
                    <div>
                        <i class="si si-clock text-success"></i> Waktu <?= tgl_indo($l['tgl_agenda'])?>
                    </div>
                    <div>
                        <i class="si si-pointer text-success"></i> Lokasi <?= $l['lokasi_kegiatan']?>
                    </div>
                    <div>
                        <i class="si si-directions text-success"></i> Koordinator<?= $l['koordinator_kegiatan']?>
                    </div>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</div>
<?php endif; ?>