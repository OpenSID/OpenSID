<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="article-single">
	<div class="container-page mb-20">
		<div class="headingpage border-grey-soft bg-white flexleft" style="border-radius:5px 5px 0 0;">
			<div class="headingpage-image border-grey-soft flexcenter">
				<img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/excavator.png") ?>" alt=""/>
			</div>
			<h2>PEMBANGUNAN <?= strtoupper($this->setting->sebutan_desa); ?></h2>
		</div>
		<div class="box-article bg-white" style="border-radius:0 0 5px 5px;">
			<div class="box-statis border-grey-soft" style="border-radius:0 0 5px 5px;">
				<?php if ($pembangunan): ?>
					<?php foreach ($pembangunan as $data): ?>
						<div class="pemb-row bg-grey-medium">
							<div class="column-pemb1">
								<div class="imglist">
									<div class="image-height-default">
										<img class="yall_lazy" src="<?= base_url("$this->theme_folder/$this->theme/assets/images/icon/spinner.svg") ?>" data-src="<?= is_file(LOKASI_GALERI . $data->foto) == TRUE ? base_url() . LOKASI_GALERI . $data->foto : base_url("$this->theme_folder/$this->theme/assets/images/pembangunan.png") ?>" alt="">
										<a href="<?= base_url() . LOKASI_GALERI . $data->foto; ?>"  data-fancybox="images">
											<div class="image-zoom">
												<img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/icon/fullscreen.svg") ?>" alt=""/>
											</div>
										</a>
									</div>
								</div>
							</div>	
							<div class="column-pemb2">
								<div class="p-15">
									<h1><?= htmlspecialchars_decode($data->judul) ?></h1>
									<table class="table border-grey-soft" style="border:none !important;width:100%;">
										<tr style="border:none !important;">
											<td class="border-grey-soft" style="vertical-align:top;">Tahun</td>
											<td class="border-grey-soft" style="vertical-align:top;text-align:center;">:</td>
											<td class="border-grey-soft" style="vertical-align:top;"><?= $data->tahun_anggaran ?></td>
										</tr>
										<tr style="border:none !important;">
											<td class="border-grey-soft" style="vertical-align:top;">Volume</td>
											<td class="border-grey-soft" style="vertical-align:top;text-align:center;">:</td>
											<td class="border-grey-soft" style="vertical-align:top;"><?= htmlspecialchars_decode($data->volume) ?></td>
										</tr>
										<tr style="border:none !important;">
											<td class="border-grey-soft" style="vertical-align:top;">Anggaran</td>
											<td class="border-grey-soft" style="vertical-align:top;text-align:center;">:</td>
											<td class="border-grey-soft" style="vertical-align:top;">Rp. <?= number_format($data->anggaran,0) ?></td>
										</tr>
									</table>
									<div class="flexcenter" style="position:relative;overflow:hidden;">
										<a href="https://www.google.com/maps/dir//<?= $data->lat?>,<?= $data->lng?>" title="Lokasi" rel="noopener noreferrer" target="_blank"><div class="tombol bg-grey-dark3 flexcenter" style="margin:5px 5px;"><img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/icon/location.svg") ?>" alt=""/><p>Lokasi</p></div></a>
										<a href="<?= site_url("pembangunan/$data->slug"); ?>" title="Detail"><div class="tombol bg-grey-dark3 flexcenter" style="margin:5px 5px;"><img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/icon/komentar.svg") ?>" alt=""/><p>Detail</p></div></a>
									</div>
								</div>
							</div>	
						</div>
					<?php endforeach; ?>
					<?php $this->load->view($folder_themes . "/commons/paging"); ?>
				<?php else: ?>
					<div class="no-found">
						<div class="margin-min10 flexcenter">
							<div class="no-found-title">
								<div class="pd-lr-10">
									<h2>404! PEMBANGUNAN BELUM TERSEDIA</h2>
									<h3>Maaf, belum tersedia data pembangunan <?= ucwords($this->setting->sebutan_desa); ?> pada halaman ini.</h3>
								</div>
							</div>
							<div class="no-found-image">
								<div class="pd-lr-10">
									<img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/nodata.png") ?>" alt=""/>
								</div>
							</div>
						</div>
					</div>
				<?php endif; ?>
			</div>	
		</div>
	</div>	
</div>
