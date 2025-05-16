<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="container-page">
	<div class="box-container ptb-10">
		<div class="layanan border-grey-soft bg-white-radial ptb-10 pd-lr-20">
			<div class="flexrow" style="margin:0;">
				<div class="layanan-intro">
					<div class="relative-row">
						<img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/layanan.png") ?>" alt=""/>
						<div class="layanan-absolute flexright">
							<h1 style="text-align:center;"><span class="color-1">Layanan</span><br/>Mandiri</h1>
						</div>
					</div>
				</div>
				<div class="layanan-login bg-white border-grey-soft flexcenter">
					<div class="relative-row" style="width:100%;">
						<?php if( ! isset($_SESSION['mandiri']) OR $_SESSION['mandiri']<>1) : ?>
						<div class="flexcenter mb-30">
							<?php if ($w_cos): ?>
							<?php $photo_found = false; ?>
							<?php foreach ($w_cos as $data): ?>
								<?php if (strtoupper(strip_tags($data['judul'])) == strtoupper('logo')): 
								$photo_found = true;
								break; ?>
								<?php endif; ?>
							<?php endforeach; ?>
							<?php if ($photo_found): ?>
								<?php foreach ($w_cos as $data): ?>
									<?php if (strtoupper(strip_tags($data['judul'])) == strtoupper('logo')): ?>
										<img src="<?= to_base64(LOKASI_GAMBAR_WIDGET . $data['foto']); ?>" alt="">
									<?php endif; ?>
								<?php endforeach; ?>
							<?php elseif (IS_PREMIUM && IS_240710 && theme_config('logo_header') == TRUE) : ?>
								<img src="<?= base_url(theme_config('logo_header')); ?>" height="100px" alt="" <?= theme_config('rotate_logo') ? "class='rotating-image'" : ""; ?>/>
							<?php else: ?>
								<img src="<?= gambar_desa($desa['logo']);?>" alt="" <?= theme_config('rotate_logo') ? "class='rotating-image'" : ""; ?>/>
							<?php endif; ?>
						<?php endif; ?>
						</div>
						<div class="flexcenter">
							<a href="<?= site_url('layanan-mandiri/masuk') ?>" target="_blank" id="but" class="layanan-masuk bg-color3 flexcenter">
								<span style="color: white;">Login Layanan Mandiri</span> <img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/icon/admin.svg") ?>" alt=""/>
							</a>
						</div>
						<?php else : ?>
							<div class="relative-row" style="width:100%;text-align:center;">
								<p>Saat ini anda masih online<br/>di fitur Layanan Mandiri<br/><?= ucwords($this->setting->sebutan_desa); ?> <?= ucwords(($desa['nama_desa']) ? ' ' . $desa['nama_desa'] : ''); ?></p>
								<div class="flexcenter">
									<a href="<?= site_url('layanan-mandiri/masuk'); ?>" target="_blank">
										<button type="submit" class="layanan-masuk bg-color3 flexcenter">MASUK HALAMAN <img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/icon/admin.svg") ?>" alt=""/></button>
									</a>
								</div>
							</div>
						<?php endif; ?>
					</div>
				</div>
				<div class="layanan-info border-grey-soft">
					<div class="layanan-info-latar">
						<img class="yall_lazy" src="<?= base_url("$this->theme_folder/$this->theme/assets/images/icon/spinner.svg") ?>" data-src="<?= gambar_desa($desa['kantor_desa'], TRUE)?>" alt=""/>
					</div>
					<div class="layanan-info-title bg-color4">
						<p>Hubungi <?= ucwords(setting('sebutan_pemerintah_desa')) ?> untuk mendapatkan PIN</p>
					</div>
					<div class="layanan-info-image">
						<img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/online.png") ?>" alt=""/>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
