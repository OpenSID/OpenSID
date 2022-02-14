<?php defined('BASEPATH') || exit('No direct script access allowed'); ?>

<?php if($pembangunan) : ?>
	<div class="single_category wow fadeInDown">
		<h2> <span class="bold_line"><span></span></span> <span class="solid_line"></span> <span class="title_text">Detail Pembangunan</span></h2>
	</div>
	<div class="box box-primary">
		<div class="box-body">
			<div class="row">
				<div class="col-sm-6">
					<div class="panel panel-primary">
						<div class="panel-heading">Data Pembangunan</div>
						<div class="panel-body">
							<center>
								<?php if (is_file(LOKASI_GALERI . $pembangunan->foto)): ?>
									<img width="auto" class="img-fluid img-thumbnail" src="<?= base_url(LOKASI_GALERI . $pembangunan->foto); ?>" alt="<?= $pembangunan->slug; ?>"/>
								<?php else: ?>
									<img width="auto" class="img-fluid img-thumbnail" src="<?= base_url('assets/images/404-image-not-found.jpg') ?>" alt="<?= $pembangunan->slug; ?>"/>
								<?php endif; ?>
							</center>
							<br/>
							<table class="table table-bordered">
								<tr>
									<th width="150px">Nama Kegiatan</th>
									<td width="20px">:</td>
									<td><?= $pembangunan->judul ?></td>
								</tr>
								<tr>
									<th>Alamat</th>
									<td width="20px">:</td>
									<td><?= $pembangunan->alamat ?></td>
								</tr>
								<tr>
									<th>Sumber Dana</th>
									<td width="20px">:</td>
									<td><?= $pembangunan->sumber_dana ?></td>
								</tr>
								<tr>
									<th>Anggaran</th>
									<td width="20px">:</td>
									<td>Rp. <?= number_format($pembangunan->anggaran,0) ?></td>
								</tr>
								<tr>
									<th>Volume</th>
									<td width="20px">:</td>
									<td><?= $pembangunan->volume?></td>
								</tr>
								<tr>
									<th>Pelaksana</th>
									<td width="20px">:</td>
									<td><?= $pembangunan->pelaksana_kegiatan ?></td>
								</tr>
								<tr>
									<th>Tahun</th>
									<td width="20px">:</td>
									<td><?= $pembangunan->tahun_anggaran ?></td>
								</tr>
								<tr>
									<th>Keterangan</th>
									<td width="20px">:</td>
									<td><?= $pembangunan->keterangan ?></td>
								</tr>
							</table>
						</div>
					</div>
				</div>

				<div class="col-sm-6">
					<div class="panel panel-primary">
						<div class="panel-heading">Progres Pembangunan</div>
						<div class="panel-body">
							<?php if ($dokumentasi): ?>
								<div class="row">
									<?php foreach ($dokumentasi as $value): ?>
										<div class="col-sm-6 text-center">
											<?php if (is_file(LOKASI_GALERI . $value->gambar)): ?>
												<img width="auto" class="img-fluid img-thumbnail" src="<?= base_url(LOKASI_GALERI . $value->gambar); ?>" alt="<?= $pembangunan->slug . '-' . $value->persentase; ?>"/>
											<?php else: ?>
												<img width="auto" class="img-fluid img-thumbnail" src="<?= base_url('assets/images/404-image-not-found.jpg') ?>" alt="<?= $pembangunan->slug . '-' . $value->persentase; ?>"/>
											<?php endif; ?>
											<b>Foto Pembangunan <?= $value->persentase; ?></b>
										</div>
									<?php endforeach; ?>
								</div>
							<?php else: ?>
								<div class="text-center">Belum ada progres</div>
							<?php endif; ?>
						</div>
					</div>
				</div>

				<div class="col-sm-12">
					<div class="panel panel-primary">
						<div class="panel-heading">Lokasi Pembangunan</div>
						<div class="panel-body" id="map" style="max-height:400px;">
						</div>
					</div>
				</div>
			</div>
			<?php

						$share = [
							'link' => site_url('pembangunan/' . $pembangunan->slug),
							'judul' => $pembangunan->judul,
						];
						$this->load->view("$folder_themes/commons/share", $share);

				?>
		</div>
	</div>
	<script type="text/javascript">
		$(document).ready(function() {
			let map_key = "<?= $this->setting->mapbox_key; ?>";
			let lat = "<?= $pembangunan->lat ?? $desa['lat']; ?>";
			let lng = "<?= $pembangunan->lng ?? $desa['lng']; ?>";
			let posisi = [lat, lng];
			let zoom = 15;
			let logo = L.icon({
				iconUrl: "<?= base_url('assets/images/gis/point/construction.png'); ?>",
			});

			pembangunan = L.map('map').setView(posisi, zoom);
			getBaseLayers(pembangunan, map_key);
			pembangunan.addLayer(new L.Marker(posisi, {icon:logo}));
		});
	</script>
<?php else: ?>
	<?php $this->load->view("$folder_themes/commons/not_found"); ?>
<?php endif; ?>