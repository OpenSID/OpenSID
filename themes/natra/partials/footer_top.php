<?php if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php if (!is_null($transparansi)) $this->load->view($folder_themes. '/partials/apbdesa-tema.php', $transparansi);?>
    <div class="col-md-12" align="center">
        <h2>Statistik Desa</h2><hr>
        <div class="col-md-6">
        	<a href="<?= site_url(); ?>first/wilayah"><img alt="Statistik Wilayah" width="30%" src="<?= base_url("$this->theme_folder/$this->theme/images/statistik_wil.png") ?>" /></a> 
            <a href="<?= site_url(); ?>first/statistik/0"><img alt="Statistik Pendidikan" width="30%" src="<?= base_url("$this->theme_folder/$this->theme/images/statistik_pend.png") ?>" /></a>
        	<a href="<?= site_url(); ?>first/statistik/1"><img alt="Statistik Pekerjaan" width="30%" src="<?= base_url("$this->theme_folder/$this->theme/images/statistik_pekerjaan.png") ?>" /></a> 
        <hr></div>
        <div class="col-md-6">
            <a href="<?= site_url(); ?>first/statistik/3"><img alt="Statistik Agama" width="30%" src="<?= base_url("$this->theme_folder/$this->theme/images/statistik_agama.png") ?>" /></a>
        	<a href="<?= site_url(); ?>first/statistik/4"><img alt="Statistik Jenis Kelamin" width="30%" src="<?= base_url("$this->theme_folder/$this->theme/images/statistik_kelamin.png") ?>" /></a>
        	<a href="<?= site_url(); ?>first/statistik/13"><img alt="Statistik Umur" width="30%" src="<?= base_url("$this->theme_folder/$this->theme/images/statistik_umur.png") ?>" /></a>
        <hr></div>
    </div>
<div class="footer_top">
    <div class="container">
      <div class="row">
        <div class="col-lg-4 col-md-4 col-sm-4">
          <div class="single_footer_top wow fadeInRight">
            <h2><?= ucwords($this->setting->sebutan_desa." ")?> <?= ucwords($desa['nama_desa'])?></h2>
			<p><?= $desa['alamat_kantor']?><br><?= ucwords($this->setting->sebutan_kecamatan." ".$desa['nama_kecamatan'])?> <?= ucwords($this->setting->sebutan_kabupaten." ".$desa['nama_kabupaten'])?> Provinsi <?= $desa['nama_propinsi']?> Kode Pos <?= $desa['kode_pos']?></p>
			<p><?php if (!empty($desa['email_desa'])): ?>Email: <?= $desa['email_desa']?><?php endif; ?><br /><?php if (!empty($desa['telepon'])): ?>Telp: <?= $desa['telepon']?><?php endif; ?></p>
          </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4">
          <div class="single_footer_top wow fadeInDown">
            <h2>Kategori</h2>
            <ul class="labels_nav">
              <?php foreach ($menu_kiri as $data): ?>
                <li><li><a href="<?= site_url("artikel/kategori/$data[slug]"); ?>"><?= $data['kategori']; ?></a></li>
              <?php endforeach; ?>
            </ul>
          </div>
        </div>
	    <div class="col-lg-4 col-md-4 col-sm-4">
	        <div class="single_footer_top wow fadeInRight">
	            <?php foreach ($sosmed As $data): ?>
	            <?php if (!empty($data["link"])): ?>
	            <a href="<?= $data['link']?>" rel="noopener noreferrer" target="_blank">
	                <span style="color:#fff"><i class="fa fa-<?= strtolower($data['nama']) ?>-square fa-3x"></i></span>
	                <?php if (strtolower($data["nama"]) == 'whatsapp' OR strtolower($data["nama"]) == 'instagram' OR strtolower($data["nama"]) == 'telegram'): ?>
	                <span style="color:#fff"><i class="fa fa-<?= strtolower($data['nama']) ?> fa-3x"></i></span>
	                <?php endif; ?>
	            </a>
	            <?php endif; ?>
	            <?php endforeach; ?>
	        </div>
        </div>
      </div>
    </div>
</div>
