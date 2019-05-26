<?php
include "apbdesa.php";
?>
<div class="col-md-12" align="center">
<div align="center"><h2>Statistik Desa</h2></div><hr>
<div class="col-md-6">
	<a href="<?php echo site_url(); ?>first/wilayah"><img alt="Statistik Wilayah" width="30%" src="<?= base_url("$this->theme_folder/$this->theme/images/statistik_wil.png") ?>" /></a> 
    <a href="<?php echo site_url(); ?>first/statistik/0"><img alt="Statistik Pendidikan" width="30%" src="<?= base_url("$this->theme_folder/$this->theme/images/statistik_pend.png") ?>" /></a>
	<a href="<?php echo site_url(); ?>first/statistik/1"><img alt="Statistik Pekerjaan" width="30%" src="<?= base_url("$this->theme_folder/$this->theme/images/statistik_pekerjaan.png") ?>" /></a> 
<hr></div>
<div class="col-md-6">
    <a href="<?php echo site_url(); ?>first/statistik/3"><img alt="Statistik Agama" width="30%" src="<?= base_url("$this->theme_folder/$this->theme/images/statistik_agama.png") ?>" /></a>
	<a href="<?php echo site_url(); ?>first/statistik/4"><img alt="Statistik Jenis Kelamin" width="30%" src="<?= base_url("$this->theme_folder/$this->theme/images/statistik_kelamin.png") ?>" /></a>
	<a href="<?php echo site_url(); ?>first/statistik/13"><img alt="Statistik Umur" width="30%" src="<?= base_url("$this->theme_folder/$this->theme/images/statistik_umur.png") ?>" /></a>
<hr></div>
</div>

  <div class="footer_top">
    <div class="container">
      <div class="row">
        <div class="col-lg-4 col-md-4 col-sm-4">
          <div class="single_footer_top wow fadeInRight">
            <h2><?php echo ucwords($this->setting->sebutan_desa." ")?> <?php echo ucwords($desa['nama_desa'])?></h2>
			<p><?php echo $desa['alamat_kantor']?><br><?php echo ucwords($this->setting->sebutan_kecamatan." ".$desa['nama_kecamatan'])?> <?php echo ucwords($this->setting->sebutan_kabupaten." ".$desa['nama_kabupaten'])?> Provinsi <?php echo $desa['nama_propinsi']?> Kode Pos <?php echo $desa['kode_pos']?></p>
			<p><?php if (!empty($desa['email_desa'])): ?>Email: <?php echo $desa['email_desa']?><?php endif; ?><br /><?php if (!empty($desa['telepon'])): ?>Telp: <?php echo $desa['telepon']?><?php endif; ?></p>
          </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4">
          <div class="single_footer_top wow fadeInDown">
            <h2>Kategori</h2>
            <ul class="labels_nav">
              <?php foreach ($menu_kiri as $data): ?>
                <li><a href="<?php echo site_url()."first/kategori/".$data['id']?>"><?= $data['nama']?></a></li>
              <?php endforeach; ?>
            </ul>
          </div>
        </div>
	    <div class="col-lg-4 col-md-4 col-sm-4">
	        <div class="single_footer_top wow fadeInRight">
	            <?php foreach ($sosmed As $data): ?>
	            <?php if (!empty($data["link"])): ?>
	            <a href="<?= $data['link']?>" target="_blank">
	                <span style="color:#fff"><i class="fa fa-<?= strtolower($data['nama']) ?>-square fa-3x"></i></span>
	                <?php if (strtolower($data["nama"]) == 'whatsapp' OR strtolower($data["nama"]) == 'instagram'): ?>
	                <span style="color:#fff"><i class="fa fa-<?= strtolower($data['nama']) ?> fa-3x"></i></span>
	                <?php endif; ?>
	            </a>
	            <?php endif; ?>
	            <?php endforeach; ?>
	        </div>
        </div>
    </div>
  </div>
