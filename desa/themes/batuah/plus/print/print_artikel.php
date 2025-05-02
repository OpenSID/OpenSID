<?php if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<div class="print-header flexleft">
	<img src="<?= gambar_desa($desa['logo']);?>"/>
	<div>
	<h2><?= ucwords($this->setting->website_title); ?></h2>
	<h1><?= ucwords($this->setting->sebutan_desa); ?> <?= ucwords(($desa['nama_desa']) ? ' ' . $desa['nama_desa'] : ''); ?></h1>
	<h2><?=ucwords($this->setting->sebutan_kecamatan)?> <?=$desa['nama_kecamatan']?>, <?=ucwords($this->setting->sebutan_kabupaten)?> <?=$desa['nama_kabupaten']?> - <?=$desa['nama_propinsi']?></h2>
	</div>
</div>
<div class="print-body">
	<div class="print-headcontent"><?= $single_artikel["judul"]?></div>
	<div class="flexleft">
		<p><?= $single_artikel['owner'] ?> | <?= tgl_indo($single_artikel['tgl_upload']);?> | <?= hit($single_artikel['hit']) ?> dibuka</p>
	</div>
	<div class="print-content">
		<div class="print-content-image">
		<?php if ($single_artikel['gambar']!='' and is_file(LOKASI_FOTO_ARTIKEL."sedang_".$single_artikel['gambar'])): ?>
		<img src="<?= AmbilFotoArtikel($single_artikel['gambar'],'sedang')?>"/>
		<?php endif; ?>
		</div>
		<?= $single_artikel["isi"]?>
	</div>

</div>