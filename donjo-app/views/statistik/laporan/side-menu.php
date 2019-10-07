<input id="kategori" name="kategori" type="hidden" value="<?= $kategori ?>" />
<div id="penduduk" class="box box-info <?php if ($kategori !='penduduk'): ?>collapsed-box<?php endif ?>">
	<div class="box-header with-border">
		<h3 class="box-title">Statistik Penduduk</h3>
		<div class="box-tools">
			<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
		</div>
	</div>
	<div class="box-body no-padding">
		<ul class="nav nav-pills nav-stacked">
			<li class="<?php ($lap==13) and print('active') ?>"><a href="<?=site_url('statistik/index/13')?>">Umur</a></li>
      <li class="<?php ($lap==0) and print('active') ?>"><a href="<?=site_url('statistik/index/0')?>">Pendidikan dalam KK</a></li>
      <li class="<?php ($lap==14) and print('active') ?>"><a href="<?=site_url('statistik/index/14')?>">Pendidikan sedang Ditempuh</a></li>
      <li class="<?php ($lap==1) and print('active') ?>"><a href="<?=site_url('statistik/index/1')?>">Pekerjaan</a></li>
      <li class="<?php ($lap==2) and print('active') ?>"><a href="<?=site_url('statistik/index/2')?>">Status Perkawinan</a></li>
      <li class="<?php ($lap==3) and print('active') ?>"><a href="<?=site_url('statistik/index/3')?>">Agama</a></li>
      <li class="<?php ($lap==4) and print('active') ?>"><a href="<?=site_url('statistik/index/4')?>">Jenis Kelamin</a></li>
      <li class="<?php ($lap==5) and print('active') ?>"><a href="<?=site_url('statistik/index/5')?>">Warga Negara</a></li>
      <li class="<?php ($lap==6) and print('active') ?>"><a href="<?=site_url('statistik/index/6')?>">Status Penduduk</a></li>
      <li class="<?php ($lap==7) and print('active') ?>"><a href="<?=site_url('statistik/index/7')?>"> Golongan Darah</a></li>
      <li class="<?php ($lap==9) and print('active') ?>"><a href="<?=site_url('statistik/index/9')?>">Penyandang Cacat</a></li>
      <li class="<?php ($lap==10) and print('active') ?>"><a href="<?=site_url('statistik/index/10')?>">Sakit Menahun</a></li>
      <li class="<?php ($lap==16) and print('active') ?>"><a href="<?=site_url('statistik/index/16')?>">Akseptor KB</a></li>
      <li class="<?php ($lap==17) and print('active') ?>"><a href="<?=site_url('statistik/index/17')?>">Akte Kelahiran</a></li>
			<li class="<?php ($lap==18) and print('active') ?>"><a href="<?=site_url('statistik/index/18')?>">Kepemilikan KTP</a></li>
			<li class="<?php ($lap==19) and print('active') ?>"><a href="<?=site_url('statistik/index/19')?>">Jenis Asuransi</a></li>
		</ul>
	</div>
</div>
<div id="keluarga" class="box box-info <?php if ($kategori !='keluarga'): ?>collapsed-box<?php endif ?>">
	<div class="box-header with-border">
		<h3 class="box-title">Statistik Keluarga</h3>
		<div class="box-tools">
			<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
		</div>
	</div>
	<div class="box-body no-padding">
		<ul class="nav nav-pills nav-stacked">
			<li <?php if ($lap=='kelas_sosial'): ?>class="active"<?php endif; ?>><a href="<?=site_url('statistik/index/kelas_sosial')?>">Klasifikasi Sosial</a></li>
		</ul>
	</div>
</div>
<div id="bantuan" class="box box-info <?php if ($kategori !='bantuan'): ?>collapsed-box<?php endif ?>">
	<div class="box-header with-border">
		<h3 class="box-title">Statistik Program Bantuan</h3>
		<div class="box-tools">
			<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
		</div>
	</div>
	<div class="box-body no-padding">
		<ul class="nav nav-pills nav-stacked">
      <?php foreach ($list_bantuan as $bantuan): ?>
        <li <?php if ($lap==$bantuan['lap']): ?>class="active"<?php endif; ?>>
          <a href="<?= site_url()?>statistik/index/<?= $bantuan['lap']?>"><?= $bantuan['nama']." (".$bantuan['lap'].")"?></a>
        </li>
      <?php endforeach; ?>
		</ul>
	</div>
</div>