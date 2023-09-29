<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php 
  $s_links = [
    [
      'target' => 'statistikPenduduk',
      'label' => 'Statistik Penduduk',
      'icon' => 'fa-chart-pie',
      'submenu' => $daftar_statistik['penduduk']
    ],
    [
      'target' => 'statistikKeluarga',
      'label' => 'Statistik Keluarga',
      'icon' => 'fa-chart-bar',
      'submenu' => $daftar_statistik['keluarga']
    ],
    [
      'target' => 'statistikBantuan',
      'label' => 'Statistik Bantuan',
      'icon' => 'fa-chart-line',
      'submenu' => [
        [
          'slug' => 'first/statistik/bantuan_penduduk',
          'label' => 'Penerima Bantuan Penduduk'
        ],
        [
          'slug' => 'first/statistik/bantuan_keluarga',
          'label' => 'Penerima Bantuan Keluarga'
        ],
        [
          'slug' => 'first/statistik/501',
          'label' => 'BPNT'
        ],
        [
          'slug' => 'first/statistik/502',
          'label' => 'BLSM'
        ],
        [
          'slug' => 'first/statistik/503',
          'label' => 'PKH'
        ],
        [
          'slug' => 'first/statistik/504',
          'label' => 'Bedah Rumah'
        ],
        [
          'slug' => 'first/statistik/505',
          'label' => 'JAMKESMAS'
        ]
      ]
    ],
    [
      'target' => 'statistikLainnya',
      'label' => 'Statistik Lainnya',
      'icon' => 'fa-chart-area',
      'submenu' => [
        [
          'slug' => 'first/dpt',
          'label' => 'Calon Pemilih'
        ],
        [
          'slug' => 'data-wilayah',
          'label' => 'Wilayah Administratif'
        ]
      ]
    ]
  ]
?>

<div class="sticky top-5 w-full shadow">
  <div class="accordion" id="statistikNavigation">
    <?php foreach($s_links as $statistik) : ?>
      <?php $url_slug = str_replace([site_url(), 'data-statistik/'], '', current_url()) ?>
      <?php $is_active = array_search($url_slug, array_column($statistik['submenu'], 'slug')) !== false ? true : false ?>
      <div class="accordion-item bg-white border border-gray-200 overflow-hidden">
        <h4 class="accordion-header mb-0" id="heading-<?= $statistik['target'] ?>">
          <button
            class="accordion-button relative flex items-center w-full py-4 px-5 text-base text-left bg-white border-0 rounded-none transition focus:outline-none text-h5"
            type="button" data-bs-toggle="collapse" data-bs-target="#<?= $statistik['target']?>" aria-expanded="<?= $is_active ? 'true' : 'false' ?>"
            aria-controls="<?= $statistik['target']?>">
            <i class="fas <?= $statistik['icon'] ?> mr-2"></i> <?= $statistik['label'] ?>
          </button>
        </h4>
        <div id="<?= $statistik['target'] ?>" class="accordion-collapse collapse <?php $is_active && print('show') ?>" data-bs-parent="#statistikNavigation" aria-labelledby="heading-<?= $statistik['target']?>">
          <div class="accordion-body">
            <ul class="divide-y-2">
              <?php foreach($statistik['submenu'] as $submenu) : ?>
                <?php if (in_array($statistik['target'], ['statistikBantuan', 'statistikLainnya'])) : ?>
                  <?php $stat_slug = str_replace('first/', '', $submenu['slug']) ?>
                  <?php if($this->web_menu_model->menu_aktif($stat_slug)) : ?>
                    <li id="statistik_13"><a href="<?= site_url($submenu['slug']) ?>" class="px-5 py-2 block <?= site_url($submenu['slug']) === current_url() ? 'bg-primary-100 text-white' : 'hover:cursor-pointer hover:text-primary-100' ?>"><?= $submenu['label'] ?></a></li>
                  <?php endif ?>
                <?php else : ?>
                  <?php if($this->web_menu_model->menu_aktif('statistik/' . $submenu['key'])) : ?>
                    <li id="statistik_13"><a href="<?= site_url('data-statistik/' . $submenu['slug']) ?>" class="px-5 py-2 block <?= site_url('data-statistik/' . $submenu['slug']) === current_url() ? 'bg-primary-100 text-white' : 'hover:cursor-pointer hover:text-primary-100' ?>"><?= $submenu['label'] ?></a></li>
                  <?php endif ?>
                <?php endif ?>
              <?php endforeach ?>
            </ul>
          </div>
        </div>
      </div>
    <?php endforeach ?>
  </div>
</div>