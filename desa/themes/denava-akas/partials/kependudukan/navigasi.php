<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php
$daftar_statistik = daftar_statistik();
$slug_aktif       = str_replace('_', '-', $slug_aktif);
$s_links = [
    [
        'target' => 'statistikPenduduk',
        'label' => 'Statistik Penduduk',
        'icon' => 'fa-user iconpenduduk',
        'submenu' => $daftar_statistik['penduduk']
    ],
    [
        'target' => 'statistikKeluarga',
        'label' => 'Statistik Keluarga',
        'icon' => 'fa-user iconkeluarga',
        'submenu' => $daftar_statistik['keluarga']
    ],
    [
        'target' => 'statistikBantuan',
        'label' => 'Statistik Bantuan',
        'icon' => 'fa-user iconbantuan',
        'submenu' => $daftar_statistik['bantuan']
    ],
    [
        'target' => 'statistikLainnya',
        'label' => 'Statistik Lainnya',
        'icon' => 'fa-user iconlainnya',
        'submenu' => $daftar_statistik['lainnya']
    ]
];
?>

<div class="big-screen">
<?php foreach($s_links as $statistik) : ?>
  <?php $is_active = in_array($slug_aktif, array_column($statistik['submenu'], 'slug')) ?>
    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
      <div class="panel panel-default" style="margin-bottom:10px;">
       <div class="heading-stat bg-grey-medium flexleft border-grey-soft" id="heading-<?= $statistik['target'] ?>" role="tab">
         <a role="button" data-toggle="collapse" data-parent="#accordion" href="#<?= $statistik['target']?>" aria-expanded="<?= $is_active ? 'true' : 'false' ?>" aria-controls="<?= $statistik['target']?>"><div class="flexleft"><i class="fa <?= $statistik['icon'] ?>"></i> <?= $statistik['label'] ?></div></a>
       </div>
       <div id="<?= $statistik['target'] ?>" class="panel-collapse collapse<?php $is_active && print('show') ?>" role="tabpanel" aria-labelledby="heading-<?= $statistik['target']?>">
        <div class="panel-box border-grey-soft">
          <?php foreach($statistik['submenu'] as $submenu) : ?>
            <?php
            $stat_slug = in_array($statistik['target'], ['statistikBantuan', 'statistikLainnya']) ? str_replace('first/', '', $submenu['url']) : 'statistik/' . $submenu['key'];
            if ($this->web_menu_model->menu_aktif($stat_slug)) :
            ?>
            <p class="stat-sub" id="statistik_13"><a href="<?= site_url($submenu['url']) ?>" class="<?= $submenu['slug'] == $slug_aktif ? 'stat-active color-1 popin2' : 'hover:cursor-pointer hover:text-primary-100' ?>"><?= $submenu['label'] ?></a></p>
            <?php endif ?>
         <?php endforeach ?>
       </div>
     </div>
   </div>
 </div>
<?php endforeach ?>
</div>
<div class="small-screen">
  <?php foreach($s_links as $statistik) : ?>
    <?php $is_active = in_array($slug_aktif, array_column($statistik['submenu'], 'slug')) ?>
    <div class="">
      <div class="panel panel-default" style="margin-bottom:5px !important;">
       <div class="heading-stat bg-grey-medium flexleft border-grey-soft" id="heading-<?= $statistik['target'] ?>" role="tab">
         <a role="button" data-toggle="collapse" data-parent="#accordion" href="#<?= $statistik['target']?>" aria-expanded="<?= $is_active ? 'true' : 'false' ?>" aria-controls="<?= $statistik['target']?>"><div class="flexleft"><i class="fa <?= $statistik['icon'] ?>"></i> <?= $statistik['label'] ?></div></a>
       </div>
       <div id="<?= $statistik['target'] ?>">
        <div class="panel-box border-grey-soft">
        <?php foreach($statistik['submenu'] as $submenu) : ?>
            <?php
            $stat_slug = in_array($statistik['target'], ['statistikBantuan', 'statistikLainnya']) ? str_replace('first/', '', $submenu['url']) : 'statistik/' . $submenu['key'];
            if ($this->web_menu_model->menu_aktif($stat_slug)) :
            ?>
            <p class="stat-sub" id="statistik_13"><a href="<?= site_url($submenu['url']) ?>" class="<?= $submenu['slug'] == $slug_aktif ? 'stat-active color-1 popin2' : 'hover:cursor-pointer hover:text-primary-100' ?>"><?= $submenu['label'] ?></a></p>
            <?php endif ?>
         <?php endforeach ?>
       </div>
     </div>
   </div>
 </div>
<?php endforeach ?>
</div>
