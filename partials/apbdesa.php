<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-5 container px-3 lg:px-5">
  <?php foreach($data_widget as $subdata_name => $subdatas) : ?>
    <div class="shadow bg-white rounded-lg overflow-hidden">
      <h3 class="bg-primary-100 text-white px-5 py-3 text-h5"><?= ($subdatas['laporan'])?></h3>
      <div class="px-5 py-4 text-xs lg:text-sm space-y-3">
          <?php foreach($subdatas as $key => $subdata) : ?>
            <?php if($subdata['judul'] != NULL and $key != 'laporan' and $subdata['realisasi'] != 0 or $subdata['anggaran'] != 0): ?>
            <div class="space-y-1">
              <span class="text-sm font-bold"><?= ucwords(strtolower($subdata['judul'])) ?></span>
              <div class="text-sm flex justify-between">
                <span>Rp<?= number_format($subdata['realisasi']) ?></span>
                <span>Rp<?= number_format($subdata['anggaran']) ?></span>
              </div>
              <div class="w-full bg-gray-200 rounded-full overflow-hidden">
                <div class="bg-secondary-100 text-xs font-medium text-white text-center p-0.5 leading-none rounded-l-full" style="width: <?= $subdata['persen'] ?>%"><?= $subdata['persen'] ?>%</div>
              </div>
            </div>
          <?php endif ?>
        <?php endforeach ?>
      </div>
    </div>
    <?php $index++ ?>
  <?php endforeach ?>
</div>