<div class="box-footer no-padding">
	<ul class="nav nav-stacked">
		<li <?= jecho($selected_nav, 'daftar', 'class="active"'); ?>><a href="<?= site_url($this->controller); ?>">Daftar Penerima</a></li>
		<li <?= jecho($selected_nav, 'laporan', 'class="active"'); ?>><a href="<?= site_url($this->controller . '/laporan_penduduk/1'); ?>">Laporan Penduduk</a></li>
		<li <?= jecho($selected_nav, 'rekap', 'class="active"'); ?>><a href="<?= site_url($this->controller . '/laporan_rekap'); ?>">Laporan Rekap</a></li>
	</ul>
</div>