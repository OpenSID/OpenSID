<script>
	(function() {
		var infoWindow;
		window.onload = function() {
			<?php if (! empty($desa['lat']) && ! empty($desa['lng'])) : ?>
				var posisi = [<?= $desa['lat'] . ',' . $desa['lng'] ?>];
				var zoom = <?= $desa['zoom'] ?: 10 ?>;
			<?php elseif (! empty($desa['path'])) : ?>
				var wilayah_desa = <?= $desa['path'] ?>;
				var posisi = wilayah_desa[0][0];
				var zoom = <?= $desa['zoom'] ?: 10 ?>;
			<?php else : ?>
				var posisi = [-1.0546279422758742, 116.71875000000001];
				var zoom = 10;
			<?php endif; ?>

			//Inisialisasi tampilan peta
			var mymap = L.map('map').setView(posisi, zoom);

			<?php if (! empty($desa['path'])) : ?>
				mymap.fitBounds(<?= $desa['path'] ?>);
			<?php endif; ?>

			//Menampilkan overlayLayers Peta Semua Wilayah
			var marker_desa = [];
			var marker_dusun = [];
			var marker_rw = [];
			var marker_rt = [];
			var semua_marker = [];
			var markers = new L.MarkerClusterGroup();
			var markersList = [];


			//OVERLAY WILAYAH DESA
			<?php if (! empty($desa['path'])) : ?>
				set_marker_desa_content(marker_desa, <?= json_encode($desa) ?>, "<?= ucwords($this->setting->sebutan_desa) . ' ' . $desa['nama_desa'] ?>", "<?= favico_desa() ?>", '#isi_popup');
			<?php endif; ?>

			//OVERLAY WILAYAH DUSUN
			<?php if (! empty($dusun_gis)) : ?>
				set_marker_multi_content(marker_dusun, '<?= addslashes(json_encode($dusun_gis)) ?>', '<?= ucwords($this->setting->sebutan_dusun) ?>', 'dusun', '#isi_popup_dusun_', '<?= favico_desa() ?>');
			<?php endif; ?>

			//OVERLAY WILAYAH RW
			<?php if (! empty($rw_gis)) : ?>
				set_marker_content(marker_rw, '<?= addslashes(json_encode($rw_gis)) ?>', 'RW', 'rw', '#isi_popup_rw_', '<?= favico_desa() ?>');
			<?php endif; ?>

			//OVERLAY WILAYAH RT
			<?php if (! empty($rt_gis)) : ?>
				set_marker_content(marker_rt, '<?= addslashes(json_encode($rt_gis)) ?>', 'RT', 'rt', '#isi_popup_rt_', '<?= favico_desa() ?>');
			<?php endif; ?>

			//Menampilkan overlayLayers Peta Semua Wilayah
			var overlayLayers = overlayWil(marker_desa, marker_dusun, marker_rw, marker_rt, "<?= ucwords($this->setting->sebutan_desa) ?>", "<?= ucwords($this->setting->sebutan_dusun) ?>", true);

			//Menampilkan BaseLayers Peta
			var baseLayers = getBaseLayers(mymap, '<?= $this->setting->mapbox_key ?>');

			//Geolocation IP Route/GPS
			geoLocation(mymap);

			//Menambahkan zoom scale ke peta
			L.control.scale().addTo(mymap);

			//Mencetak peta ke PNG
			cetakPeta(mymap);

			//Menambahkan Legenda Ke Peta
			var legenda_desa = L.control({
				position: 'bottomright'
			});
			var legenda_dusun = L.control({
				position: 'bottomright'
			});
			var legenda_rw = L.control({
				position: 'bottomright'
			});
			var legenda_rt = L.control({
				position: 'bottomright'
			});

			mymap.on('overlayadd', function(eventLayer) {
				if (eventLayer.name === 'Peta Wilayah Desa') {
					setlegendPetaDesa(legenda_desa, mymap, <?= json_encode($desa) ?>, '<?= ucwords($this->setting->sebutan_desa) ?>', '<?= $desa['nama_desa'] ?>');
				}
				if (eventLayer.name === 'Peta Wilayah Dusun') {
					setlegendPeta(legenda_dusun, mymap, '<?= addslashes(json_encode($dusun_gis)) ?>', '<?= ucwords($this->setting->sebutan_dusun) ?>', 'dusun', '', '');
				}
				if (eventLayer.name === 'Peta Wilayah RW') {
					setlegendPeta(legenda_rw, mymap, '<?= addslashes(json_encode($rw_gis)) ?>', 'RW', 'rw', '<?= ucwords($this->setting->sebutan_dusun) ?>');
				}
				if (eventLayer.name === 'Peta Wilayah RT') {
					setlegendPeta(legenda_rt, mymap, '<?= addslashes(json_encode($rt_gis)) ?>', 'RT', 'rt', 'RW');
				}
			});

			mymap.on('overlayremove', function(eventLayer) {
				if (eventLayer.name === 'Peta Wilayah Desa') {
					mymap.removeControl(legenda_desa);
				}
				if (eventLayer.name === 'Peta Wilayah Dusun') {
					mymap.removeControl(legenda_dusun);
				}
				if (eventLayer.name === 'Peta Wilayah RW') {
					mymap.removeControl(legenda_rw);
				}
				if (eventLayer.name === 'Peta Wilayah RT') {
					mymap.removeControl(legenda_rt);
				}
			});

			// deklrasi variabel agar mudah di baca
			var all_area = '<?= addslashes(json_encode($area)) ?>';
			var all_garis = '<?= addslashes(json_encode($garis)) ?>';
			var all_lokasi = '<?= addslashes(json_encode($lokasi)) ?>';
			var all_lokasi_pembangunan = '<?= addslashes(json_encode($lokasi_pembangunan)) ?>';
			var all_persil = '<?= addslashes(json_encode($persil)) ?>';
			var LOKASI_SIMBOL_LOKASI = '<?= base_url() . LOKASI_SIMBOL_LOKASI ?>';
			var favico_desa = '<?= favico_desa() ?>';
			var LOKASI_FOTO_AREA = '<?= base_url() . LOKASI_FOTO_AREA ?>';
			var LOKASI_FOTO_GARIS = '<?= base_url() . LOKASI_FOTO_GARIS ?>';
			var LOKASI_FOTO_LOKASI = '<?= base_url() . LOKASI_FOTO_LOKASI ?>';
			var LOKASI_GALERI = '<?= base_url() . LOKASI_GALERI ?>';
			var info_pembangunan = '<?= site_url('pembangunan/') ?>';

			// Menampilkan OverLayer Area, Garis, Lokasi plus Lokasi Pembangunan, persil
			var layerCustom = tampilkan_layer_area_garis_lokasi_plus(mymap, all_area, all_garis, all_lokasi, all_lokasi_pembangunan, LOKASI_SIMBOL_LOKASI, favico_desa, LOKASI_FOTO_AREA, LOKASI_FOTO_GARIS, LOKASI_FOTO_LOKASI, LOKASI_GALERI, info_pembangunan, all_persil);

			//PENDUDUK
			<?php if ($layer_penduduk == 1 || $layer_keluarga == 1 && ! empty($penduduk)) : ?>
				//Data penduduk
				var penduduk = JSON.parse('<?= addslashes(json_encode($penduduk)) ?>');
				var jml = penduduk.length;
				var foto;
				var content;
				var point_style = L.icon({
					iconUrl: '<?= base_url(LOKASI_SIMBOL_LOKASI) ?>pend.png',
					iconSize: [22, 27],
					iconAnchor: [11, 27],
					popupAnchor: [0, -28],
				});
				for (var x = 0; x < jml; x++) {
					if (penduduk[x].lat || penduduk[x].lng) {
						foto = '<td style="text-align: center;"><img class="foto_pend" src="' + AmbilFoto(penduduk[x].foto, "kecil_", penduduk[x].id_sex) + '" alt="Foto Penduduk"/></td>';

						//Konten yang akan ditampilkan saat marker diklik
						content =
							'<table border=0 style="width:150px;max-width:200px"><tr>' + foto + '</tr>' +
							'<tr><td style="text-align: center;">' +
							'<p size="2.5" style="margin: 5px 0;">' + penduduk[x].nama +
							'<br/>' + penduduk[x].sex +
							'<br/>' + penduduk[x].umur + ' Tahun ' +
							'<br/>' + penduduk[x].agama +
							'<br/>' + penduduk[x].alamat + '</p>' +
							'<a class="btn btn-sm btn-primary" href="<?= site_url('penduduk/detail/1/0/') ?>' + penduduk[x].id + '" style="color:black;" target="ajax-modalx" rel="content" header="Rincian Data ' + penduduk[x].nama + '" >Data Rincian</a></td>' +
							'</tr></table>';
						//Menambahkan point ke marker
						semua_marker.push(turf.point([Number(penduduk[x].lng), Number(penduduk[x].lat)], {
							content: content,
							style: point_style
						}));
					}
				}
			<?php endif; ?>

			if (semua_marker.length != 0) {
				var geojson = L.geoJSON(turf.featureCollection(semua_marker), {
					pmIgnore: true,
					showMeasurements: true,
					onEachFeature: function(feature, layer) {
						layer.bindPopup(feature.properties.content);
						layer.bindTooltip(feature.properties.content);
					},
					style: function(feature) {
						if (feature.properties.style) {
							return feature.properties.style;
						}
					},
					pointToLayer: function(feature, latlng) {
						if (feature.properties.style) {
							return L.marker(latlng, {
								icon: feature.properties.style
							});
						} else
							return L.marker(latlng);
					}
				});

				markersList.push(geojson);
				markers.addLayer(geojson);
				mymap.addLayer(markers);

				//Mempusatkan tampilan map agar semua marker terlihat
				mymap.fitBounds(geojson.getBounds());
			}

			//Menampilkan Baselayer dan Overlayer
			var mainlayer = L.control.layers(baseLayers, overlayLayers, {
				position: 'topleft',
				collapsed: true
			}).addTo(mymap);
			var customlayer = L.control.groupedLayers('', layerCustom, {
				groupCheckboxes: true,
				position: 'topleft',
				collapsed: true
			}).addTo(mymap);

			$('#isi_popup').remove();
			$('#isi_popup_dusun').remove();
			$('#isi_popup_rw').remove();
			$('#isi_popup_rt').remove();

		}; //EOF window.onload

	})();
</script>
<!-- TODO: Pindahkan ke external css -->
<style>
	#map {
		width: 100%;
		height: 85vh
	}

	.leaflet-popup-content {
		height: auto;
		overflow-y: auto;
	}

	table {
		table-layout: fixed;
		white-space: normal !important;
	}

	td {
		word-wrap: break-word;
	}

	.persil {
		min-width: 350px;
	}

	.persil td {
		padding-right: 1rem;
	}
</style>
<div class="content-wrapper">
	<form id="mainform_map" name="mainform_map" method="post">
		<div class="row">
			<div class="col-md-12">
				<div id="map">
					<?php include 'donjo-app/views/gis/cetak_peta.php'; ?>
					<div class="leaflet-top leaflet-right">
						<div class="leaflet-control-layers leaflet-bar leaflet-control">
							<a class="leaflet-control-control icos" href="#" title="Control Panel" role="button" aria-label="Control Panel" onclick="$('#target1').toggle();$('#target1').removeClass('hidden');$('#target2').hide();"><i class="fa fa-gears"></i></a>
							<a class="leaflet-control-control icos" href="#" title="Legenda" role="button" aria-label="Legenda" onclick="$('#target2').toggle();$('#target2').removeClass('hidden');$('#target1').hide();"><i class="fa fa-list"></i></a>
						</div>
						<?php $this->load->view('gis/content_desa.php', ['desa' => $desa, 'list_ref' => $list_ref, 'wilayah' => ucwords($this->setting->sebutan_desa . ' ' . $desa['nama_desa'])]) ?>
						<?php $this->load->view('gis/content_dusun.php', ['dusun_gis' => $dusun_gis, 'list_ref' => $list_ref, 'wilayah' => ucwords($this->setting->sebutan_dusun . ' ')]) ?>
						<?php $this->load->view('gis/content_rw.php', ['rw_gis' => $rw_gis, 'list_ref' => $list_ref, 'wilayah' => ucwords($this->setting->sebutan_dusun . ' ')]) ?>
						<?php $this->load->view('gis/content_rt.php', ['rt_gis' => $rt_gis, 'list_ref' => $list_ref, 'wilayah' => ucwords($this->setting->sebutan_dusun . ' ')]) ?>
						<div id="target1" class="leaflet-control-layers leaflet-control-layers-expanded leaflet-control hidden" aria-haspopup="true" style="max-width: 250px;">
							<div class="leaflet-control-layers-overlays">
								<div class="leaflet-control-layers-group" id="leaflet-control-layers-group-2">
									<span class="leaflet-control-layers-group-name">CARI PENDUDUK</span>
									<div class="leaflet-control-layers-separator"></div>
									<div class="form-group">
										<label>Status Penduduk</label>
										<select class="form-control input-sm " name="filter" onchange="formAction('mainform_map','<?= site_url('gis/filter') ?>')">
											<option value="">Pilih Status Penduduk </option>
											<?php foreach ($list_status_penduduk as $data) : ?>
												<option value="<?= $data['id'] ?>" <?= selected($filter, $data['id']); ?>><?= $data['nama'] ?></option>
											<?php endforeach; ?>
										</select>
									</div>
									<div class="form-group">
										<label>Jenis Kelamin</label>
										<select class="form-control input-sm " name="sex" onchange="formAction('mainform_map','<?= site_url('gis/sex') ?>')">
											<option value="">Pilih Jenis Kelamin </option>
											<?php foreach ($list_jenis_kelamin as $data) : ?>
												<option value="<?= $data['id'] ?>" <?= selected($sex, $data['id']); ?>><?= $data['nama'] ?></option>
											<?php endforeach; ?>
										</select>
									</div>
									<div class="form-group">
										<label><?= ucwords($this->setting->sebutan_dusun) ?></label>
										<select class="form-control input-sm " name="dusun" onchange="formAction('mainform_map','<?= site_url('gis/dusun') ?>')">
											<option value="">Pilih Dusun</option>
											<?php foreach ($list_dusun as $data) : ?>
												<option value="<?= $data['dusun'] ?>" <?= selected($dusun, $data['dusun']); ?>><?= $data['dusun'] ?></option>
											<?php endforeach; ?>
										</select>
									</div>
									<?php if ($dusun) : ?>
										<div class="form-group">
											<label>RW</label>
											<select class="form-control input-sm " name="rw" onchange="formAction('mainform_map','<?= site_url('gis/rw') ?>')">
												<option value="">Pilih RW</option>
												<?php foreach ($list_rw as $data) : ?>
													<option value="<?= $data['rw'] ?>" <?= selected($rw, $data['rw']); ?>><?= $data['rw'] ?></option>
												<?php endforeach; ?>
											</select>
										</div>
										<?php if ($rw) : ?>
											<div class="form-group">
												<label>RT</label>
												<select class="form-control input-sm " name="rt" onchange="formAction('mainform_map','<?= site_url('gis/rt') ?>')">
													<option value="">Pilih RT</option>
													<?php foreach ($list_rt as $data) : ?>
														<option value="<?= $data['rt'] ?>" <?= selected($rt, $data['rt']); ?>><?= $data['rt'] ?></option>
													<?php endforeach; ?>
												</select>
											</div>
										<?php endif; ?>
									<?php endif; ?>
									<div class="col-sm-12">
										<div class="form-group row">
											<label>Cari</label>
											<div class="box-tools">
												<div class="input-group input-group-sm pull-right">
													<input name="cari" id="cari" class="form-control" placeholder="cari..." type="text" value="<?= html_escape($cari) ?>" onkeypress="if (event.keyCode == 13):$('#'+'mainform_map').attr('action', '<?= site_url('gis/search') ?>');$('#'+'mainform_map').submit();endif">
													<div class="input-group-btn">
														<button type="submit" class="btn btn-default" onclick="$('#'+'mainform_map').attr('action', '<?= site_url('gis/search') ?>');$('#'+'mainform_map').submit();"><i class="fa fa-search"></i></button>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="form-group">
										<a href="<?= site_url('gis/ajax_adv_search') ?>" class="btn btn-block btn-social bg-olive btn-sm" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Pencarian Spesifik" title="Pencarian Spesifik">
											<i class="fa fa-search"></i> Pencarian Spesifik
										</a>
										<a href="<?= site_url('gis/clear') ?>" class="btn btn-block btn-social bg-orange btn-sm">
											<i class="fa fa-refresh"></i> Bersihkan
										</a>
									</div>
								</div>
							</div>
						</div>
						<div id="target2" class="leaflet-control-layers leaflet-control-layers-expanded leaflet-control hidden" aria-haspopup="true" style="max-height: 315px;">
							<div class="leaflet-control-layers-overlays">
								<div class="leaflet-control-layers-group" id="leaflet-control-layers-group-3">
									<span class="leaflet-control-layers-group-name">LEGENDA</span>
									<div class="leaflet-control-layers-separator"></div>
									<label>
										<input class="leaflet-control-layers-selector" type="checkbox" name="layer_penduduk" value="1" onchange="handle_pend(this);" <?php if ($layer_penduduk == 1) : ?>checked<?php endif; ?>>
										<span> Penduduk </span>
									</label>
									<label>
										<input class="leaflet-control-layers-selector" type="checkbox" name="layer_keluarga" value="1" onchange="handle_kel(this);" <?php if ($layer_keluarga == 1) : ?>checked<?php endif; ?>>
										<span> Keluarga</span>
									</label>
								</div>
							</div>
						</div>
					</div>
					<div class="leaflet-bottom leaflet-left">
						<div id="qrcode">
							<div class="panel-body-lg">
								<a href="https://github.com/OpenSID/OpenSID">
									<img src="<?= base_url() ?>assets/images/opensid.png" alt="OpenSID">
								</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
</div>
</form>
</div>
<script>
	function handle_pend(cb) {
		formAction('mainform_map', '<?= site_url('gis') ?>/layer_penduduk');
	}

	function handle_kel(cb) {
		formAction('mainform_map', '<?= site_url('gis') ?>/layer_keluarga');
	}

	function AmbilFoto(foto, ukuran = "kecil_", sex) {
		//Jika penduduk ada foto, maka pakai foto tersebut
		//Jika tidak, pakai foto default
		if (foto) {
			ukuran_foto = ukuran || null
			file_foto = '<?= base_url() . LOKASI_USER_PICT; ?>' + ukuran_foto + foto;
		} else {
			file_foto = sex == '2' ? '<?= FOTO_DEFAULT_WANITA ?>' : '<?= FOTO_DEFAULT_PRIA ?>';
		}

		return file_foto;
	}

	function AmbilFotoLokasi(foto, ukuran = "kecil_") {
		ukuran_foto = ukuran || null
		file_foto = '<?= base_url() . LOKASI_FOTO_LOKASI; ?>' + ukuran_foto + foto;
		return file_foto;
	}
</script>