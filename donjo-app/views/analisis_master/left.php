<div id="penduduk" class="box box-info">
	<div class="box-header with-border">
		<h3 class="box-title">Pengaturan Analisis</h3>
		<div class="box-tools">
			<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
		</div>
	</div>
	<div class="box-body no-padding">
		<ul class="nav nav-pills nav-stacked">
			<li <?= jecho($this->session->submenu, 'Data Kategori', 'class="active"'); ?>><a href="<?= site_url('analisis_kategori/clear') . '/' . $analisis_master['id']?>">Kategori / Variabel</a></li>
      <li <?= jecho($this->session->submenu, 'Data Indikator', 'class="active"'); ?>><a href="<?= site_url('analisis_indikator/clear') . '/' . $analisis_master['id']?>">Indikator & Pertanyaan</a></li>
      <li <?= jecho($this->session->submenu, 'Data Klasifikasi', 'class="active"'); ?>><a href="<?= site_url('analisis_klasifikasi/clear') . '/' . $analisis_master['id']?>">Klasifikasi Analisis</a></li>
      <li <?= jecho($this->session->submenu, 'Data Periode', 'class="active"'); ?>><a href="<?= site_url('analisis_periode/clear') . '/' . $analisis_master['id']?>">Periode Sensus / Survei</a></li>
		</ul>
	</div>
</div>
<div id="penduduk" class="box box-info">
	<div class="box-header with-border">
		<h3 class="box-title">Input Data Analisis</h3>
		<div class="box-tools">
			<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
		</div>
	</div>
	<div class="box-body no-padding">
		<ul class="nav nav-pills nav-stacked">
			<li <?= jecho($this->session->submenu, 'Input Data', 'class="active"'); ?>><a href="<?= site_url('analisis_respon/clear') . '/' . $analisis_master['id']?>">Input Data Sensus / Survei</a></li>
		</ul>
	</div>
</div>
<div id="penduduk" class="box box-info">
	<div class="box-header with-border">
		<h3 class="box-title">Laporan Analisis</h3>
		<div class="box-tools">
			<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
		</div>
	</div>
	<div class="box-body no-padding">
		<ul class="nav nav-pills nav-stacked">
			<li <?= jecho($this->session->submenu, 'Laporan Analisis', 'class="active"'); ?>><a href="<?= site_url('analisis_laporan/clear') . '/' . $analisis_master['id']?>">Laporan Hasil Klasifikasi</a></li>
			<li <?= jecho($this->session->submenu, 'Statistik Jawaban', 'class="active"'); ?>><a href="<?= site_url('analisis_statistik_jawaban/clear') . '/' . $analisis_master['id']?>">Laporan Per Indikator</a></li>
		</ul>
	</div>
</div>
