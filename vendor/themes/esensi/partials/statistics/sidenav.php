<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php 
  $s_links = [
    [
      'target' => 'statistikPenduduk',
      'title' => 'Statistik Penduduk',
      'icon' => 'fa-chart-pie',
      'submenu' => [
        [
          'slug' => 'first/statistik/13',
          'title' => 'Umur (Rentang)'
        ],
        [
          'slug' => 'first/statistik/15',
          'title' => 'Umur (Kategori)'
        ],
        [
          'slug' => 'first/statistik/0',
          'title' => 'Pendidikan Dalam KK'
        ],
        [
          'slug' => 'first/statistik/14',
          'title' => 'Pendidikan Sedang Ditempuh'
        ],
        [
          'slug' => 'first/statistik/1',
          'title' => 'Pekerjaan'
        ],
        [
          'slug' => 'first/statistik/2',
          'title' => 'Status Perkawinan'
        ],
        [
          'slug' => 'first/statistik/3',
          'title' => 'Agama'
        ],
        [
          'slug' => 'first/statistik/4',
          'title' => 'Jenis Kelamin'
        ],
        [
          'slug' => 'first/statistik/hubungan_kk',
          'title' => 'Hubungan Dalam KK'
        ],
        [
          'slug' => 'first/statistik/5',
          'title' => 'Warga Negara'
        ],
        [
          'slug' => 'first/statistik/6',
          'title' => 'Status Penduduk'
        ],
        [
          'slug' => 'first/statistik/7',
          'title' => 'Golongan Darah'
        ],
        [
          'slug' => 'first/statistik/9',
          'title' => 'Penyandang Cacat'
        ],
        [
          'slug' => 'first/statistik/10',
          'title' => 'Penyakit Menahun'
        ],
        [
          'slug' => 'first/statistik/16',
          'title' => 'Akseptor KB'
        ],
        [
          'slug' => 'first/statistik/17',
          'title' => 'Akta Kelahiran'
        ],
        [
          'slug' => 'first/statistik/18',
          'title' => 'Kepemilikan KTP'
        ],
        [
          'slug' => 'first/statistik/19',
          'title' => 'Asuransi Kesehatan'
        ],
        [
          'slug' => 'first/statistik/covid',
          'title' => 'Status Covid'
        ],
        [
          'slug' => 'first/statistik/suku',
          'title' => 'Suku / Etnis'
        ],
        [
          'slug' => 'first/statistik/bpjs-tenagakerja',
          'title' => 'BPJS Ketenagakerjaan'
        ]
      ]
    ],
    [
      'target' => 'statistikKeluarga',
      'title' => 'Statistik Keluarga',
      'icon' => 'fa-chart-bar',
      'submenu' => [
        [
          'slug' => 'first/statistik/kelas_sosial',
          'title' => 'Kelas Sosial'
        ]
      ]
    ],
    [
      'target' => 'statistikBantuan',
      'title' => 'Statistik Bantuan',
      'icon' => 'fa-chart-line',
      'submenu' => [
        [
          'slug' => 'first/statistik/bantuan_penduduk',
          'title' => 'Penerima Bantuan Penduduk'
        ],
        [
          'slug' => 'first/statistik/bantuan_keluarga',
          'title' => 'Penerima Bantuan Keluarga'
        ],
        [
          'slug' => 'first/statistik/501',
          'title' => 'BPNT'
        ],
        [
          'slug' => 'first/statistik/502',
          'title' => 'BLSM'
        ],
        [
          'slug' => 'first/statistik/503',
          'title' => 'PKH'
        ],
        [
          'slug' => 'first/statistik/504',
          'title' => 'Bedah Rumah'
        ],
        [
          'slug' => 'first/statistik/505',
          'title' => 'JAMKESMAS'
        ]
      ]
    ],
    [
      'target' => 'statistikLainnya',
      'title' => 'Statistik Lainnya',
      'icon' => 'fa-chart-area',
      'submenu' => [
        [
          'slug' => 'first/dpt',
          'title' => 'Calon Pemilih'
        ],
        [
          'slug' => IS_PREMIUM ? 'data-wilayah' : 'first/wilayah',
          'title' => 'Wilayah Administratif'
        ]
      ]
    ]
  ]
?>

<div class="sticky top-5 w-full shadow">
  <div class="accordion" id="statistikNavigation">
    <?php foreach($s_links as $statistik) : ?>
      <?php $url_slug = str_replace(site_url(), '', current_url()) ?>
      <?php $is_active = array_search($url_slug, array_column($statistik['submenu'], 'slug')) !== false ? true : false ?>
      <div class="accordion-item bg-white border border-gray-200 overflow-hidden">
        <h4 class="accordion-header mb-0" id="heading-<?= $statistik['title'] ?>">
          <button
            class="accordion-button relative flex items-center w-full py-4 px-5 text-base text-left bg-white border-0 rounded-none transition focus:outline-none text-h5"
            type="button" data-bs-toggle="collapse" data-bs-target="#<?= $statistik['target']?>" aria-expanded="<?= $is_active ? 'true' : 'false' ?>"
            aria-controls="<?= $statistik['target']?>">
            <i class="fas <?= $statistik['icon'] ?> mr-2"></i> <?= $statistik['title'] ?>
          </button>
        </h4>
        <div id="<?= $statistik['target'] ?>" class="accordion-collapse collapse <?php $is_active && print('show') ?>" data-bs-parent="#statistikNavigation" aria-labelledby="heading-<?= $statistik['target']?>">
          <div class="accordion-body">
            <ul class="divide-y-2">
              <?php foreach($statistik['submenu'] as $submenu) : ?>
                <?php $stat_slug = str_replace('first/', '', $submenu['slug']) ?>
                <?php if($this->web_menu_model->menu_aktif($stat_slug)) : ?>
                  <li id="statistik_13"><a href="<?= site_url($submenu['slug']) ?>" class="px-5 py-2 block <?= site_url($submenu['slug']) === current_url() ? 'bg-primary-100 text-white' : 'hover:cursor-pointer hover:text-primary-100' ?>"><?= $submenu['title'] ?></a></li>
                <?php endif ?>
              <?php endforeach ?>
            </ul>
          </div>
        </div>
      </div>
    <?php endforeach ?>
  </div>
</div>