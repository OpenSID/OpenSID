<?php if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<div class="withscroll-padding">
	<?php foreach($aparatur_desa['daftar_perangkat'] as $data) : ?>
		<div class="perangkat">
		<div class="rowsame">
			<div class="perangkat-image">
				<img src="<?= $data['foto'] ?>" alt="<?= $data['nama'] ?>">
			</div>
			<div class="perangkat-title flexleft">
				<div>
				<h2><?= $data['jabatan'] ?></h2>
				<p><?= $data['nama'] ?></p>
				<?php if ($this->setting->tampilkan_kehadiran && $data['status_kehadiran'] == 'hadir') : ?>
					<div class="flexleft">
					<div class="ada flexleft bghijau">Hadir</div>
					</div>
				<?php else: ?>
					<div class="flexleft">
					<div class="tidakada flexleft bgmerah">Belum Hadir</div>
					</div>
				<?php endif ?>
				</div>
			</div>
		</div>	
		</div>
	<?php endforeach; ?>
</div>	