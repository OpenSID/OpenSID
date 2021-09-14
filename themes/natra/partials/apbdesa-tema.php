<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<style type="text/css">
	.progress-bar span
	{
		position: absolute;
		right: 20px;
	}
</style>
<div class="container" id="transparansi-footer" style="width: 100%; padding-top: 10px;">
	<?php foreach ($data_widget as $subdata_name => $subdatas): ?>
		<div class="col-md-4">
			<div align="center"><h2><?= ($subdatas['laporan'])?></h2></div><hr>
			<div align="center"><h4>Realisasi | Anggaran</h4></div><hr>
			<?php foreach ($subdatas as $key => $subdata): ?>
				<?php if($subdata['judul'] != NULL and $key != 'laporan' and $subdata['realisasi'] != 0 or $subdata['anggaran'] != 0): ?>
					<div class="progress-group"><?= $subdata['judul']; ?><br>
						<b>Rp. <?= number_format($subdata['realisasi']); ?> | Rp. <?= number_format($subdata['anggaran']); ?></b>
						<div class="progress progress-bar-striped" align="right" style="background-color: #27c8a2"><small></small>
							<div class="progress-bar progress-bar-danger progress-bar-striped" role="progressbar" style="width: <?= $subdata['persen'] ?>%" aria-valuenow="<?= $subdata['persen'] ?>" aria-valuemin="0" aria-valuemax="100"><span><?= $subdata['persen'] ?> %</span></div>
						</div>
					</div>
				<?php endif; ?>
			<?php endforeach; ?>
		</div>
	<?php endforeach; ?>
</div><hr>
