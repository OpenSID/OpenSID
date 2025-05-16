<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php if($aparatur_desa['daftar_perangkat']) : ?>
<?php if ($w_cos): ?>
<?php foreach ($w_cos as $data): ?>
<?php $widget = trim($data['isi']) ?>
<?php if ($data["jenis_widget"] == 1): ?>
<?php if ($data["isi"] == "aparatur_desa.php"): ?>
<div class="aparatur-style">
	<div class="ptb-10">
		<div class="container-page">
			<div class="head-module-center border-grey-soft flexcenter" style="margin-bottom:10px;"><h1><?= ucwords(setting('sebutan_pemerintah_desa')) ?></h1></div>
			<div class="aparatur bg-gradient-hor">
				<div class="margin-min5">
					<div class="carouselcustom js-flickity" data-flickity='{ "autoPlay": true, "groupCells": true, "groupCells": 1, "wrapAround": true, "cellAlign": "center" }'>
						<?php foreach($aparatur_desa['daftar_perangkat'] as $data) : ?>
							<div class="carouselcustom-item">
								<div class="pd-lr-5">
									<div class="aparatur-box border-grey-soft">
										<?php if ($data['foto']): ?>
											<div class="image-aparatur">
												<img class="yall_lazy" src="<?= base_url("$this->theme_folder/$this->theme/assets/images/icon/spinner.svg") ?>" data-src="<?= $data['foto'] ?>" alt="">
											</div>		
										<?php endif;?>	
										<div class="aparatur-cover"></div>
										<div class="bottom-gradient"></div>
										<div class="foto-title" style="text-align:center;">
											<h2 style="color:#fff;"><?= $data['jabatan'] ?></h2>
											<h3 style="margin:5px 0 0;"><?= $data['nama'] ?></h3>
											<?php if(setting('tampilkan_kehadiran')) : ?>
											<?php if ($data['status_kehadiran'] == 'hadir'): ?>
												<span class="badge hadir"><?= cekKehadiran('kehadiran', 'Ada di Kantor'); ?></span>
											<?php endif ?>
											<?php if ($data['tanggal'] == date('Y-m-d') && $data['status_kehadiran'] != 'hadir'): ?>
												<span class="badge lupa-lapor"><?= ucwords($data['status_kehadiran']) ?></span>
											<?php endif ?>
											<?php if ($data['kehadiran'] == 1 && $data['tanggal'] != date('Y-m-d')): ?>
												<span class="badge tidak-hadir"><?= cekKehadiran('ketidakhadiran', 'Tidak Ada di Kantor'); ?></span>
											<?php endif ?>
											<?php endif; ?>
										</div>
									</div>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php endif; ?>
<?php endif; ?>
<?php endforeach; ?>
<?php endif; ?>
<?php endif;?>
