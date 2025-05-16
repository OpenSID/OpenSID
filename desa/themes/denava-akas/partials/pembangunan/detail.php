<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div id="printableArea" class="article-single">
	<div class="container-page mb-20">
		<div class="headingpage border-grey-soft bg-white flexleft" style="border-radius:5px 5px 0 0;">
			<div class="headingpage-image border-grey-soft flexcenter"><img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/excavator.png") ?>" alt=""/></div>
			<h2>Pembangunan <?= ucwords($this->setting->sebutan_desa); ?></h2>
		</div><div class="box-article bg-white" style="border-radius:0 0 5px 5px;">
			<div class="box-statis border-grey-soft" style="border-radius:0 0 5px 5px;">
				<div class="pembstyle">	
					<div style="width:100%;float:left;text-align:center;"> 
						<div class="album-head">
							<div class="album-head-pemb">
								<img title="<?= $pembangunan->keterangan; ?>" loading="lazy" class="yall_lazy" src="<?= base_url("$this->theme_folder/$this->theme/assets/images/icon/spinner.svg") ?>" data-src="<?= is_file(LOKASI_GALERI . $pembangunan->foto) == TRUE ? base_url() . LOKASI_GALERI . $pembangunan->foto : base_url("$this->theme_folder/$this->theme/assets/images/pembangunan.png") ?>">
							</div>
							<div class="album-head-title">
								<div class="flexcenter">
									<a data-fancybox="gallery" href="<?= base_url() . LOKASI_GALERI . $pembangunan->foto; ?>">
										<div class="album-headtitle-image flexcenter">	
											<img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/icon/fullscreen.svg") ?>" alt=""/>
										</div>
									</a>
								</div>
								<h2>Pembangunan</h2>
								<h1><?= htmlspecialchars_decode($pembangunan->judul) ?></h1>
							</div>
						</div>
					</div>
					<div class="head-module-center border-grey-soft flexcenter" style="margin-bottom:0;border:none;"><h2>Detail Pembangunan</h2></div>
					<div class="pemb-detail border-grey-soft mb-20">
						<div class="margin-min10">
							<div class="column-2">
								<div class="pd-lr-10">
									<table style="width:100%;">
										<tr><td style="width:25%;">Lokasi</td><td style="width:10px;text-align:center;">:</td><td><?= ($pembangunan->alamat == "=== Lokasi Tidak Ditemukan ===") ? 'Lokasi tidak diketahui' : htmlspecialchars_decode($pembangunan->alamat); ?></td></tr>
										<tr><td style="width:25%;">Anggaran</td><td style="width:10px;text-align:center;">:</td><td>Rp. <?= number_format($pembangunan->anggaran,0) ?></td></tr>
										<tr><td style="width:25%;">Volume</td><td style="width:10px;text-align:center;">:</td><td><?= htmlspecialchars_decode($pembangunan->volume) ?></td></tr>
										<tr><td style="width:25%;">Sumber Dana</td><td style="width:10px;text-align:center;">:</td><td><?= $pembangunan->sumber_dana ?></td></tr>
									</table>
								</div>
							</div>
							<div class="column-2">
								<div class="pd-lr-10">
									<table style="width:100%;">
										<tr><td style="width:25%;">Tahun</td><td style="width:10px;text-align:center;">:</td><td><?= $pembangunan->tahun_anggaran ?></td></tr>
										<tr><td style="width:25%;">Pelaksana</td><td style="width:10px;text-align:center;">:</td><td><?= htmlspecialchars_decode($pembangunan->pelaksana_kegiatan) ?></td></tr>
										<tr><td style="width:25%;">Manfaat</td><td style="width:10px;text-align:center;">:</td><td><?= htmlspecialchars_decode($pembangunan->manfaat) ?></td></tr>
										<tr><td style="width:25%;">Keterangan</td><td style="width:10px;text-align:center;">:</td><td><?= htmlspecialchars_decode($pembangunan->keterangan) ?></td></tr>
									</table>
								</div>
							</div>
						</div>
					</div>
					<div class="head-module-center border-grey-soft flexcenter" style="border:none;margin-bottom:10px;"><h2>Foto Dokumentasi</h2></div>
					<div style="width:100%;float:left;text-align:center;" class="mb-20"> 
						<?php if ($dokumentasi): ?>
							<div class="margin-min5">
								<?php foreach ($dokumentasi as $value): ?>
									<a data-fancybox="gallery" href="<?= base_url(LOKASI_GALERI . $value->gambar); ?>">
										<div class="subfoto-inner">
											<div class="pd-lr-5">
												<div class="image-height-default">
													<?php if (is_file(LOKASI_GALERI . $value->gambar)): ?>
														<img class="yall_lazy" src="<?= base_url("$this->theme_folder/$this->theme/assets/images/icon/spinner.svg") ?>" data-src="<?= base_url(LOKASI_GALERI . $value->gambar); ?>" alt="Foto Pembangunan <?= $value->persentase; ?>"/>
													<?php else: ?>
														<img class="yall_lazy" src="<?= base_url("$this->theme_folder/$this->theme/assets/images/icon/spinner.svg") ?>" data-src="<?= $latar_website ? $latar_website : base_url('assets/front/css/images/latar_website.jpg'); ?>" alt="Foto Pembangunan <?= $value->persentase; ?>"/>
													<?php endif; ?>
													<div class="bottom-gradient"></div>
													<div class="pemb-item-title bg-gradient2" style="text-align:center;">
														Foto Pembangunan <?= $value->persentase; ?>
													</div>
												</div>
											</div>
										</div>
									</a>
								<?php endforeach; ?>
							</div>
						<?php else: ?>
							<div class="no-photo mb-20 flexcenter" style="margin-top:20px;">
								<img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/nofdata1.png") ?>"/>
								<p style="text-align:left;margin:0 0 0 10px !important;">foto dokumentasi<br/>kegiatan pembangunan ini<br/>belum tersedia.</p>
							</div>
						<?php endif; ?>
					</div>		
					
					<div style="width:100%;float:left;text-align:center;" class="mb-20"> 
						<div class="head-module-center border-grey-soft flexcenter" style="border:none;margin-bottom:10px;"><h2>Lokasi</h2></div>
						<div id="map" class="map-pemb"></div>
					</div>
					
					<div style="width:100%;float:left;text-align:center;" class="mb-20"> 
						<?php
						$share = ['link' => site_url('pembangunan/' . $pembangunan->slug), 'judul' => $pembangunan->judul, ];
						$this->load->view($folder_themes . "/commons/share", $share);
						?>
					</div>			

				</div>	
			</div>	
		</div>	
	</div>	
</div>
<script type="text/javascript">
	$(document).ready(function() {
		let map_key = "<?= setting('mapbox_key') ?>";
		let jenis_peta = "<?= setting('jenis_peta') ?>";
		let lat = "<?= $pembangunan->lat ?? $desa['lat']; ?>";
		let lng = "<?= $pembangunan->lng ?? $desa['lng']; ?>";
		let posisi = [lat, lng];
		let zoom = 15;
		let logo = L.icon({
			iconUrl: "<?= setting('icon_pembangunan_peta') ?>",
			iconSize: [25, 25],
			shadowSize: [25, 32],
			iconAnchor: [20, 20],
			shadowAnchor: [5, 5],
			popupAnchor: [0, 0]
		});
		pembangunan = L.map('map').setView(posisi, zoom);
		baseLayers = getBaseLayers(pembangunan, map_key, jenis_peta);
		pembangunan.addLayer(new L.Marker(posisi, {icon:logo}));
		L.control.layers(baseLayers, null, {position: 'topright', collapsed: true}).addTo(pembangunan);
	});
</script>
