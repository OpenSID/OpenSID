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
			<li <?php if ($lap==13): ?>class="active"<?php endif; ?>><a href="<?=site_url('statistik/index/13')?>">Umur</a></li>
      <li <?php if ($lap==0): ?>class="active"<?php endif; ?>><a href="<?=site_url('statistik/index/0')?>">Pendidikan dalam KK</a></li>
      <li <?php if ($lap==14): ?>class="active"<?php endif; ?>><a href="<?=site_url('statistik/index/14')?>">Pendidikan sedang Ditempuh</a></li>
      <li <?php if ($lap==1): ?>class="active"<?php endif; ?>><a href="<?=site_url('statistik/index/1')?>">Pekerjaan</a></li>
      <li <?php if ($lap==2): ?>class="active"<?php endif; ?>><a href="<?=site_url('statistik/index/2')?>">Status Perkawinan</a></li>
      <li <?php if ($lap==3): ?>class="active"<?php endif; ?>><a href="<?=site_url('statistik/index/3')?>">Agama</a></li>
      <li <?php if ($lap==4): ?>class="active"<?php endif; ?>><a href="<?=site_url('statistik/index/4')?>">Jenis Kelamin</a></li>
      <li <?php if ($lap==5): ?>class="active"<?php endif; ?>><a href="<?=site_url('statistik/index/5')?>">Warga Negara</a></li>
      <li <?php if ($lap==6): ?>class="active"<?php endif; ?>><a href="<?=site_url('statistik/index/6')?>">Status Penduduk</a></li>
      <li <?php if ($lap==7): ?>class="active"<?php endif; ?>><a href="<?=site_url('statistik/index/7')?>"> Golongan Darah</a></li>
      <li <?php if ($lap==9): ?>class="active"<?php endif; ?>><a href="<?=site_url('statistik/index/9')?>">Penyandang Cacat</a></li>
      <li <?php if ($lap==10): ?>class="active"<?php endif; ?>><a href="<?=site_url('statistik/index/10')?>">Sakit Menahun</a></li>
      <li <?php if ($lap==16): ?>class="active"<?php endif; ?>><a href="<?=site_url('statistik/index/16')?>">Akseptor KB</a></li>
      <li <?php if ($lap==17): ?>class="active"<?php endif; ?>><a href="<?=site_url('statistik/index/17')?>">Akte Kelahiran</a></li>
      <li <?php if ($lap==18): ?>class="active"<?php endif; ?>><a href="<?=site_url('statistik/index/18')?>">Kepemilikan KTP</a></li>
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