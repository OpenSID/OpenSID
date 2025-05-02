<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php 
  $s_links = [
    [
      'target' => 'statistikPenduduk',
      'title' => 'Statistik Penduduk',
      'icon' => 'fa-user',
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
      'icon' => 'fa-users',
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
      'icon' => 'fa-heart',
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
      'icon' => 'fa-pie-chart',
      'submenu' => [
        [
          'slug' => 'first/dpt',
          'title' => 'Calon Pemilih'
        ],
        [
		  'slug' => 'data-wilayah',
          'title' => 'Wilayah Administratif'
        ]
      ]
    ]
  ]
?>



	  <?php foreach($s_links as $statistik) : ?>
      <?php $url_slug = str_replace(site_url(), '', current_url()) ?>
      <?php $is_active = array_search($url_slug, array_column($statistik['submenu'], 'slug')) !== false ? true : false ?>
    <div class="panel-group bgwhite" id="accordion" role="tablist" aria-multiselectable="true" style="margin:10px 0 0 !important;">
	
      <div class="panel panel-default bgwhite" style="margin-bottom:0 !important;">

        <div class="heading-stat bgwhite flexleft bordergrey1" id="heading-<?= $statistik['title'] ?>" role="tab">
          <a role="button" data-toggle="collapse" data-parent="#accordion" href="#<?= $statistik['target']?>" aria-expanded="<?= $is_active ? 'true' : 'false' ?>" aria-controls="<?= $statistik['target']?>"><i class="fa <?= $statistik['icon'] ?>"></i> <?= $statistik['title'] ?></a>
        </div>
        <div id="<?= $statistik['target'] ?>" class="panel-collapse collapse<?php $is_active && print('show') ?>" role="tabpanel" aria-labelledby="heading-<?= $statistik['target']?>">
          <div class="panel-box bordergrey1">
            <?php foreach($statistik['submenu'] as $submenu) : ?>
                <?php $stat_slug = str_replace('first/', '', $submenu['slug']) ?>
                <?php if($this->web_menu_model->menu_aktif($stat_slug)) : ?>
                  <h3 class="stat-sub" id="statistik_13"><a href="<?= site_url($submenu['slug']) ?>" class="<?= site_url($submenu['slug']) === current_url() ? 'bggrad-color2 stat-white' : 'hover:cursor-pointer hover:text-primary-100' ?>"><?= $submenu['title'] ?></a></h3>
                <?php endif ?>
              <?php endforeach ?>
          </div>
        </div>
      </div>

    </div>
<?php endforeach ?>
