<?php  defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php $bg_header = $latar_website ? base_url($latar_website) : base_url($this->theme_folder.'/'.$this->theme .'/assets/images/header-bg.jpg') ?>

<div class="container md:px-4 lg:px-5">
  <header style="background-image: url(<?= $bg_header ?>);" class="bg-center bg-cover bg-no-repeat relative text-white">
    <div class="absolute bg-gray-800 bg-opacity-60 top-0 left-0 right-0 h-full">
    </div>
    
    <?php $this->load->view($folder_themes .'/commons/category_menu') ?>

    <section class="relative z-10 text-center space-y-2 mt-3 px-3 lg:px-5">
      <a href="<?= site_url() ?>">
        <figure>
          <img src="<?= gambar_desa($desa['logo']) ?>" alt="Logo <?= ucfirst($this->setting->sebutan_desa).' '.ucwords($desa['nama_desa']) ?>" class="h-16 mx-auto pb-2">
        </figure>
        <span class="text-h2 block"><?= NAMA_DESA ?></span>
        <p><?= ucfirst($this->setting->sebutan_kecamatan_singkat) ?>
          <?= ucwords($desa['nama_kecamatan']) ?>,
          <?= ucfirst($this->setting->sebutan_kabupaten_singkat) ?>
          <?= ucwords($desa['nama_kabupaten']) ?>,
          Provinsi 
          <?= ucwords($desa['nama_propinsi']) ?>
        </p>
      </a>
      <?php if($w_gal) : ?>
        <marquee onmouseover="this.stop();" onmouseout="this.start();" scrollamount="4" class="block w-10/12 lg:w-1/4 mx-auto">
          <div class="grid grid-flow-col gap-3 shadow-lg pt-2">
            <?php foreach($w_gal as $album) : ?>
              <?php if(is_file(LOKASI_GALERI . 'kecil_' . $album['gambar'])) : ?>
                <?php $link = site_url('first/sub_gallery/'.$album['id']) ?>
                <a href="<?= $link ?>" class="block w-32 h-20" title="<?= $album['nama'] ?>">
                  <img src="<?= AmbilGaleri($album['gambar'], 'kecil') ?>" alt="<?= $album['nama'] ?>" class="w-32 h-20 object-cover">
                </a>
              <?php endif ?>
            <?php endforeach ?>
          </div>
        </marquee>
      <?php endif ?>
    </section>
    <?php if($teks_berjalan) : ?>
      <div class="block px-3 bg-white text-white bg-opacity-20 py-1.5 text-xs mt-6 mb-0 z-20 relative">
        <marquee onmouseover="this.stop();" onmouseout="this.start();" class="block divide-x-4 relative">
          <?php foreach($teks_berjalan as $marquee) : ?>
            <span class="px-3">
              <?= $marquee['teks'] ?>
              <?php if(trim($marquee['tautan']) && $marquee['judul_tautan']) : ?>
              <a href="<?= $marquee['tautan'] ?>" class="hover:text-link"><?= $marquee['judul_tautan']?></a>
              <?php endif ?>
            </span>
          <?php endforeach ?>
        </marquee>
      </div>
    <?php endif ?>
  </header>
  <?php $this->load->view($folder_themes .'/commons/main_menu') ?>
  <?php $this->load->view($folder_themes .'/commons/mobile_menu') ?>
</div>
