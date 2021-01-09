<link rel="stylesheet" href="<?= base_url()?>assets/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="<?= base_url()?>assets/bootstrap/css/font-awesome.min.css">
<link rel="stylesheet" href="<?= base_url()?>assets/bootstrap/css/ionicons.min.css">
<link rel="stylesheet" href="<?= base_url()?>assets/css/leaflet.css" />
<link rel="stylesheet" href="<?= base_url()?>assets/css/fonts.googleapis.com.css" />
<link rel="stylesheet" href="<?= base_url()?>assets/css/ace.min.css" />
<link rel="stylesheet" href="<?= base_url()?>assets/css/ace-skins.min.css" />

<script src="<?= base_url() ?>assets/bootstrap/js/jquery.min.js"></script>
<script src="<?= base_url()?>assets/bootstrap/js/bootstrap.min.js"></script>
<script src="<?= base_url()?>assets/js/leaflet.js"></script>
<script src="<?= base_url()?>assets/js/ace-elements.min.js"></script>
<script src="<?= base_url()?>assets/js/ace.min.js"></script>

<div class="main-container ace-save-state" id="main-container">
	<div class="main-content">
		<div class="page-content">

			<div class="page-header">
				<div class="col-sm-1">
				<img src="<?= gambar_desa($config['logo']);?>" alt="" style="width:60px; height:auto">
				</div>
				<h1 class="judul"><center>
					PEMERINTAH <?= strtoupper($this->setting->sebutan_kabupaten . ' ' . $config['nama_kabupaten'] . ' <br>'); ?>
					<?= strtoupper($this->setting->sebutan_kecamatan . ' ' . $config['nama_kecamatan'] . ' ' . $this->setting->sebutan_desa . ' ' . $config['nama_desa'] . ' <br>'); ?>
					MONITORING PELAKSANAAN PEMBANGUNAN
				</center>
				</h1>
			</div>

			<div class="col-sm-6">
				<div class="panel panel-success">
					<div class="panel-heading">Data Pembangunan</div>
					<div class="panel-body">

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
				<div class="panel panel-success">
					<div class="panel-heading">Lokasi Pembangunan</div>
					<div class="panel-body">
						<div id="map" style="height: 340px;"></div>
					</div>
				</div>
			</div>

			<div class="col-sm-12">
				<div class="panel panel-success">
					<div class="panel-heading">Progress Pembangunan</div>
					<div class="panel-body">

						<div class="col-xs-4 col-sm-4 pricing-box">
							<div class="widget-box widget-color-blue">
								<div class="widget-header">
									<h6 class="widget-title bigger lighter">Gambar</h6>
								</div>
								<div class="widget-body">
									<div class="widget-main text-center">
										<img src="<?= base_url() . LOKASI_GALERI . $pembangunan->foto ?>" width="280px"  height="180px">
									</div>
									<div>
										<button class="btn btn-info btn-minier" data-toggle="modal" data-target="#sampul<?= $pembangunan->id ?>">
											<i class="ace-icon fa fa-eye"></i> View
										</button>
									</div>
								</div>
							</div>
						</div>

						<?php foreach ($dokumentasi as $key => $value): ?>
							<div class="col-xs-4 col-sm-4 pricing-box">
								<div class="widget-box widget-color-blue">
									<div class="widget-header">
										<h6 class="widget-title bigger lighter">Gambar Progres Pembangunan <?= $value->persentase ?></h6>
									</div>
									<div class="widget-body">
										<div class="widget-main text-center">
											<img src="<?= base_url() . LOKASI_GALERI . $value->gambar ?>" width="280px"  height="180px">
										</div>
										<div>
											<button class="btn btn-info btn-minier" data-toggle="modal" data-target="#<?= $value->id ?>">
												<i class="ace-icon fa fa-eye"></i> View
											</button>
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

<?php foreach ($dokumentasi as $key => $value): ?>
	<div class="modal fade" id="<?= $value->id ?>">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title"><?= 'Gambar Progres Pembangunan ' . $value->persentase ?></h4>
					</div>
					<div class="modal-body">
						<div class="text-center">
							<img src="<?= base_url() . LOKASI_GALERI . $value->gambar ?>" width="800px"  height="500px">
						</div>
					</div>
				</div>
			</div>
		</div>
	<?php endforeach; ?>

	<div class="modal fade" id="sampul<?= $pembangunan->id ?>">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title">Gambar</h4>
					</div>
					<div class="modal-body">
						<div class="text-center">
							<img src="<?= base_url() . LOKASI_GALERI . $pembangunan->foto ?>" width="800px"  height="500px">
						</div>
					</div>
				</div>
			</div>
		</div>

<script>
	var map = L.map('map').setView([<?= $pembangunan->lat?>,<?= $pembangunan->lng?>], 18);
			L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
					attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
			}).addTo(map);

		var logo = L.icon({
			iconUrl: '<?= favico_desa()?>',
			iconSize:     [32, 32], // size of the icon
			//iconAnchor:   [22, 94], // point of the icon which will correspond to marker's location
			popupAnchor:  [-1,1] // point from which the popup should open relative to the iconAnchor
		});

		var info_tempat = "<div class='media text-center'>";
			info_tempat += "<div class='media-center'>";
			info_tempat += "<img class='media-object' src='<?= base_url() . LOKASI_GALERI . $pembangunan->foto ?>' width='200px' height='100px'>";
			info_tempat += "</div>";
			info_tempat += "<div class='media-body '>";
			info_tempat += "<p><b><?= $pembangunan->judul ?></b></p>";
			info_tempat +="</div>";
			info_tempat +="</div>";

		L.marker([<?= $pembangunan->lat?>,<?= $pembangunan->lng?>],{icon:logo}).addTo(map)
    		.bindPopup(info_tempat).openPopup();
</script>
