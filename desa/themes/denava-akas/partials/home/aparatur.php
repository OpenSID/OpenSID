<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="withscroll">
	<div class="mlr-10">
		<div class="panelopen">
			<div style="position:relative;overflow:hidden;">
				<?php $count = 0;
				foreach ($aparatur_desa['daftar_perangkat'] as $data): ?>
					<?php if ($count < 1): ?>
						<div class="pimpinan bg-gradient-hor">
							<div class="flexrow" style="margin:0;">
								<div class="pimpinan-title flexleft">
									<div>
										<h2><?= $data['nama'] ?></h2>
										<h3><?= $data['jabatan'] ?></h3>
										<?php if(setting('tampilkan_kehadiran')) : ?>
										<?php if ($data['status_kehadiran'] == 'hadir'): ?>
										<br><span class="badge hadir"><small><?= cekKehadiran('kehadiran', 'Ada di Kantor'); ?></small></span>
										<?php endif ?>
										<?php if ($data['tanggal'] == date('Y-m-d') && $data['status_kehadiran'] != 'hadir'): ?>
										<br><span class="badge lupa-lapor"><small><?= ucwords($data['status_kehadiran']) ?></small></span>
										<?php endif ?>
										<?php if ($data['kehadiran'] == 1 && $data['tanggal'] != date('Y-m-d')): ?>
										<br><span class="badge tidak-hadir"><small><?= cekKehadiran('ketidakhadiran', 'Tidak Ada di Kantor'); ?></small></span>
										<?php endif ?>
										<?php endif ?>
									</div>
								</div>
								<div class="pimpinan-image">
									<div class="image-pimpinan <?= ((setting('tampilkan_kehadiran') && $data['kehadiran'] == 1 && $data['tanggal'] != date('Y-m-d')) ? 'greystyle' : '') ?>">
										<img class="yall_lazy" src="<?= base_url("$this->theme_folder/$this->theme/assets/images/icon/spinner.svg") ?>" data-src="<?= $data['foto'] ?>" alt="">
									</div>
								</div>
							</div>
						</div>
					<?php endif; ?>
					<?php $count++; ?>
				<?php endforeach; ?>
				<?php $count = 0; ?>
				<?php foreach ($aparatur_desa['daftar_perangkat'] as $data): ?>
					<?php if ($count > 0): ?>
						<div class="arsip-right flexleft">
							<div class="arsip-right-image <?= ((setting('tampilkan_kehadiran') && $data['kehadiran'] == 1 && $data['tanggal'] != date('Y-m-d')) ? 'greystyle' : '') ?>">
								<?php if ($data['foto']): ?>
									<img class="yall_lazy" src="<?= base_url("$this->theme_folder/$this->theme/assets/images/icon/spinner.svg") ?>" data-src="<?= $data['foto'] ?>" alt="">
								<?php endif;?>	
							</div>
							<div class="arsip-right-title flexleft">
								<div>
									<h2><b><?= $data['nama'] ?></b></h2>
									<small><?= $data['jabatan'] ?></small>
									<?php if(setting('tampilkan_kehadiran')) : ?>
									<?php if ($data['status_kehadiran'] == 'hadir'): ?>
									<br><span class="badge hadir"><small><?= cekKehadiran('kehadiran', 'Ada di Kantor'); ?></small></span>
									<?php endif ?>
									<?php if ($data['tanggal'] == date('Y-m-d') && $data['status_kehadiran'] != 'hadir'): ?>
									<br><span class="badge lupa-lapor"><small><?= ucwords($data['status_kehadiran']) ?></small></span>
									<?php endif ?>
									<?php if ($data['kehadiran'] == 1 && $data['tanggal'] != date('Y-m-d')): ?>
									<br><span class="badge tidak-hadir"><small><?= cekKehadiran('ketidakhadiran', 'Tidak Ada di Kantor'); ?></small></span>
									<?php endif ?>
									<?php endif ?>
								</div>
							</div>
						</div>
					<?php endif; ?>
					<?php $count++; ?>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</div>
