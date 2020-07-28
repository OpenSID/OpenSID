<input id="kategori" name="kategori" type="hidden" value="<?= $kategori ?>" />
<div id="penduduk" class="box box-info <?= ($kategori == 'penduduk') ?: 'collapsed-box'; ?>">
	<div class="box-header with-border">
		<h3 class="box-title">Statistik Penduduk</h3>
		<div class="box-tools">
			<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa <?= ($kategori == 'penduduk') ? 'fa-minus' : 'fa-plus'; ?>"></i></button>
		</div>
	</div>
	<div class="box-body no-padding">
		<ul class="nav nav-pills nav-stacked">
			<?php foreach($list_statistik_penduduk as $id => $nama): ?>
				<?php if( ! in_array($id, ['statistik/bantuan_penduduk'])): ?>
					<li <?= jecho($id, 'statistik/'.$lap, 'class="active"'); ?>><a href="<?= site_url(str_replace('/', '/clear/', $id))?>"><?= $nama; ?></a></li>
				<?php endif; ?>
			<?php endforeach; ?>
		</ul>
	</div>
</div>
<div id="keluarga" class="box box-info <?= ($kategori == 'keluarga') ?: 'collapsed-box'; ?>">
	<div class="box-header with-border">
		<h3 class="box-title">Statistik Keluarga</h3>
		<div class="box-tools">
			<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa <?= ($kategori == 'keluarga') ? 'fa-minus' : 'fa-plus'; ?>"></i></button>
		</div>
	</div>
	<div class="box-body no-padding">
		<ul class="nav nav-pills nav-stacked">
			<?php foreach($link_statistik_keluarga as $id => $nama): ?>
				<?php if( ! in_array($id, ['statistik/bantuan_keluarga'])): ?>
					<li <?= jecho($id, 'statistik/'.$lap, 'class="active"'); ?>><a href="<?= site_url(str_replace('/', '/clear/', $id))?>"><?= $nama; ?></a></li>
				<?php endif; ?>
			<?php endforeach; ?>
		</ul>
	</div>
</div>
<div id="bantuan" class="box box-info <?= ($kategori == 'bantuan') ?: 'collapsed-box'; ?>">
	<div class="box-header with-border">
		<h3 class="box-title">Statistik Program Bantuan</h3>
		<div class="box-tools">
			<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa <?= ($kategori == 'bantuan') ? 'fa-minus' : 'fa-plus'; ?>"></i></button>
		</div>
	</div>
	<div class="box-body no-padding">
		<ul class="nav nav-pills nav-stacked">
			<?php foreach ($list_bantuan as $bantuan): ?>
				<li <?= jecho($bantuan['lap'], $lap, 'class="active"'); ?>>
					<a href="<?= site_url("statistik/clear/$bantuan[lap]")?>"><?= $bantuan['nama']." (".$bantuan['lap'].")"?></a>
				</li>
			<?php endforeach; ?>
			<li <?= jecho('bantuan_penduduk', $lap, 'class="active"'); ?>><a href="<?=site_url('statistik/clear/bantuan_penduduk')?>">Penerima Bantuan (Penduduk)</a></li>
			<li <?= jecho('bantuan_keluarga', $lap, 'class="active"'); ?>><a href="<?=site_url('statistik/clear/bantuan_keluarga')?>">Penerima Bantuan (Keluarga)</a></li>
		</ul>
	</div>
</div>
