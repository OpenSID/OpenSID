<?php defined('BASEPATH') || exit('No direct script access allowed'); ?>

<?php $this->load->view("$folder_themes/plus/header_side"); ?>
<div class="mainpage">
	<div class="margin-side-10">
	<div class="mainbodyarea">
		<div class="bigarea hiddenrelative">
		<div class="bigarea-margin">
			<div class="col-default margin-top-10">
				<div class="bodypage bgwhite bordergrey">
					<div class="noprint">
					<div class="headstat-lebar flexcenter">
						<img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/pembangunan.png") ?>"/>
						<div>
						<h1 class="color-1">Pembangunan</h1>
						<h2><?= ucwords($this->setting->sebutan_desa); ?></h2>
						</div>
					</div>
					</div>
					<div class="pagebox">
						<?php if ($pembangunan): ?>
							<div class="pemb-detail">
								<div class="page">
								<div class="pemb-print-padding">
								<?php if (is_file(LOKASI_GALERI . $pembangunan->foto)): ?>
									<img style="width:100%;border-radius:5px;margin:0 0 15px;" src="<?= base_url(LOKASI_GALERI . $pembangunan->foto); ?>" alt="<?= $pembangunan->slug; ?>"/>
								<?php endif; ?>
								<div class="pemb-head">
									<div class="pemb-head-judul"><h1><?= $pembangunan->judul ?></h1></div>
								</div>
								<div class="flexcenter" style="width:100%;">
									<div class="pembtitle flexcenter borderwhite">
									Detail Pembangunan
									</div>
								</div>
								<table style="width:100%;" class="table-pemb">
									<tr><td class="pemb-ket">Lokasi</td><td style="width:20px;text-align:center;">:</td><td><?= ($pembangunan->alamat == "=== Lokasi Tidak Ditemukan ===") ? 'Lokasi tidak diketahui' : $pembangunan->alamat; ?></td></tr>
									<tr><td class="pemb-ket">Anggaran</td><td style="width:20px;text-align:center;">:</td><td>Rp. <?= number_format($pembangunan->anggaran,0) ?></td></tr>
									<tr><td class="pemb-ket">Volume</td><td style="width:20px;text-align:center;">:</td><td><?= $pembangunan->volume ?></td></tr>
									<tr><td class="pemb-ket">Sumber Dana</td><td style="width:20px;text-align:center;">:</td><td><?= $pembangunan->sumber_dana ?></td></tr>
									<tr><td class="pemb-ket">Tahun</td><td style="width:20px;text-align:center;">:</td><td><?= $pembangunan->tahun_anggaran ?></td></tr>
									<tr><td class="pemb-ket">Pelaksana</td><td style="width:20px;text-align:center;">:</td><td><?= $pembangunan->pelaksana_kegiatan ?></td></tr>
									<tr><td class="pemb-ket">Manfaat</td><td style="width:20px;text-align:center;">:</td><td><?= $pembangunan->manfaat ?></td></tr>
									<tr><td class="pemb-ket">Keterangan</td><td style="width:20px;text-align:center;">:</td><td><?= $pembangunan->keterangan ?></td></tr>
								</table>
								</div>
								</div>
								
								<?php if ($dokumentasi): ?>
								<div class="page">
								<div style="width:100%;float:none;">
								<div class="pemb-print-padding">
								<div class="pemb-doc bggrey1 bordergrey1">
									<div class="flexcenter" style="width:100%;">
										<div class="pembtitle flexcenter" style="border:none;">
										<div>
										Foto Dokumentasi
										<div class="forprint"><?= $pembangunan->judul ?></div>
										</div>
										</div>
									</div>
									<div class="rowsame margin-minlr-5">
									<?php foreach ($dokumentasi as $value): ?>
										<div class="pemb-doc-box bordergrey1 pembhover">
										<a data-fancybox="gallery" href="<?= base_url(LOKASI_GALERI . $value->gambar); ?>">
										<?php if (is_file(LOKASI_GALERI . $value->gambar)): ?>
										<img src="<?= base_url(LOKASI_GALERI . $value->gambar); ?>" alt="Foto Pembangunan <?= $value->persentase; ?>"/>
										<?php else: ?>
										<img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/pengganti.jpg") ?>" alt="Foto Pembangunan <?= $value->persentase; ?>"/>
										<?php endif; ?>
										<div class="pemb-doc-title hover-height flexcenter">
										<p>Foto Pembangunan <?= $value->persentase; ?></p>
										</div>
										</a>
										</div>
									<?php endforeach; ?>
									</div>
								</div>
								</div>
								</div>
								</div>
								<?php endif; ?>
								
								<div class="noprint">
								<div class="pemb-maplokasi">
									<div class="flexcenter" style="width:100%;">
										<div class="pembtitle flexcenter borderwhite" style="margin:0 0 -25px;border:none;z-index:10;">
										Titik Lokasi
										</div>
									</div>
									<div id="map" class="pembmap flexcenter" style="z-index:1;"></div>
								</div>
								<div class="flexcenter" style="width:100%;margin:20px 0 10px;">
									<?php
										$share = [
										'link' => site_url('pembangunan/' . $pembangunan->slug),
										'judul' => $pembangunan->judul,
										];
										$this->load->view("$folder_themes/commons/share", $share);
									?>
								</div>	
								</div>
							</div>
						<?php else: ?>
							<div class="blankdata">
								<img style="width:100%;height:auto;display:block;border-radius:5px;" src="<?= base_url("$this->theme_folder/$this->theme/assets/images/nature.jpg") ?>"/>
								<div class="blankdata-title">
								<h2>Mohon maaf,</h2>
									<h3>Data Pembangunan belum tersedia untuk saat ini...</h3>
								</div>
								<div class="blankdata-bottom">
									<img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/girl.png") ?>"/>
								</div>
							</div>
						<?php endif;?>
					</div>
				</div>
			</div>
			<?php $this->load->view("$folder_themes/plus/middle_page"); ?>
		</div>
		</div>
		<div class="rightsidearea">
			<?php $this->load->view("$folder_themes/plus/side"); ?>			
		</div>
	</div>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function() {
		let map_key = "<?= $this->setting->mapbox_key; ?>";
		let lat = "<?= $pembangunan->lat ?? $desa['lat']; ?>";
		let lng = "<?= $pembangunan->lng ?? $desa['lng']; ?>";
		let posisi = [lat, lng];
		let zoom = "<?= $desa['zoom'] ?? 10 ?>";
		let logo = L.icon({
			iconUrl: "<?= base_url('assets/images/gis/point/construction.png'); ?>",
            iconSize: [25, 25],
            shadowSize: [25, 32],
            iconAnchor: [20, 20],
            shadowAnchor: [5, 5],
            popupAnchor: [0, 0]
    });
		pembangunan = L.map('map').setView(posisi, zoom);
		getBaseLayers(pembangunan, map_key);
		pembangunan.addLayer(new L.Marker(posisi, {icon:logo}));
	});
</script>
