<script>
var infoWindow;
window.onload = function()
{
	//Jika posisi kantor dusun belum ada, maka posisi peta akan menampilkan peta desa
	<?php if (!empty($penduduk['lat'])):	?>
		var posisi = [<?= $penduduk['lat'].",".$penduduk['lng']; ?>];
		var zoom = <?= $desa['zoom'] ?: 10; ?>;
	<?php else: ?>
		var posisi = [<?= $desa['lat'].",".$desa['lng']; ?>];
		var zoom = 10;
	<?php	endif; ?>

	//Inisialisasi tampilan peta
	var peta_penduduk = L.map('mapx').setView(posisi, zoom);

	//Menampilkan BaseLayers Peta
	var defaultLayer = L.tileLayer.provider('OpenStreetMap.Mapnik').addTo(peta_penduduk);

	var baseLayers = {
		'OpenStreetMap': defaultLayer,
		'OpenStreetMap H.O.T.': L.tileLayer.provider('OpenStreetMap.HOT'),
		'Mapbox Streets' : L.tileLayer('https://api.mapbox.com/v4/mapbox.streets/{z}/{x}/{y}@2x.png?access_token=<?=$this->setting->google_key?>', {attribution: '<a href="https://www.mapbox.com/about/maps">© Mapbox</a> <a href="https://openstreetmap.org/copyright">© OpenStreetMap</a> | <a href="https://mapbox.com/map-feedback/">Improve this map</a>'}),
		'Mapbox Outdoors' : L.tileLayer('https://api.mapbox.com/v4/mapbox.outdoors/{z}/{x}/{y}@2x.png?access_token=<?=$this->setting->google_key?>', {attribution: '<a href="https://www.mapbox.com/about/maps">© Mapbox</a> <a href="https://openstreetmap.org/copyright">© OpenStreetMap</a> | <a href="https://mapbox.com/map-feedback/">Improve this map</a>'}),
		'Mapbox Streets Satellite' : L.tileLayer('https://api.mapbox.com/v4/mapbox.streets-satellite/{z}/{x}/{y}@2x.png?access_token=<?=$this->setting->google_key?>', {attribution: '<a href="https://www.mapbox.com/about/maps">© Mapbox</a> <a href="https://openstreetmap.org/copyright">© OpenStreetMap</a> | <a href="https://mapbox.com/map-feedback/">Improve this map</a>'}),
	};

	var posisi_penduduk = L.marker(posisi, {draggable: <?= ($penduduk['status_dasar'] == 1  || !isset($penduduk['status_dasar']) ? "true" : "false"); ?>}).addTo(peta_penduduk);
	posisi_penduduk.on('dragend', function(e){
		$('#lat').val(e.target._latlng.lat);
		$('#lng').val(e.target._latlng.lng);
	})

	$('#lat').on("input",function(e) {
		if (!$('#validasi').valid())
		{
			$("#simpan_penduduk").attr('disabled', true);
			return;
		} else
		{
			$("#simpan_penduduk").attr('disabled', false);
		}
		let lat = $('#lat').val();
		let lng = $('#lng').val();
		let latLng = L.latLng({
			lat: lat,
			lng: lng
		});

		posisi_penduduk.setLatLng(latLng);
		peta_penduduk.setView(latLng,zoom);
	})

	$('#lng').on("input",function(e) {
		if (!$('#validasi').valid())
		{
			$("#simpan_penduduk").attr('disabled', true);
			return;
		} else
		{
			$("#simpan_penduduk").attr('disabled', false);
		}
		let lat = $('#lat').val();
		let lng = $('#lng').val();
		let latLng = L.latLng({
			lat: lat,
			lng: lng
		});

		posisi_penduduk.setLatLng(latLng);
		peta_penduduk.setView(latLng, zoom);
	})

	//Unggah Peta dari file GPX/KML
	L.Control.FileLayerLoad.LABEL = '<img class="icon" src="<?= base_url()?>assets/images/folder.svg" alt="file icon"/>';

	control = L.Control.fileLayerLoad({
		addToMap: false,
		formats: [
			'.gpx',
			'.kml'
		],
		fitBounds: true,
		layerOptions: {
			pointToLayer: function (data, latlng) {
				return L.marker(latlng);
			},

		}
	});
	control.addTo(peta_penduduk);

	control.loader.on('data:loaded', function (e) {
		peta_penduduk.removeLayer(posisi_penduduk);
		var type = e.layerType;
		var layer = e.layer;
		var coords=[];
		var geojson = layer.toGeoJSON();
		var shape_for_db = JSON.stringify(geojson);

		var polygon =
		L.geoJson(JSON.parse(shape_for_db), {
			pointToLayer: function (feature, latlng) {
				return L.marker(latlng);
			},
			onEachFeature: function (feature, layer) {
				coords.push(feature.geometry.coordinates);
			}
		}).addTo(peta_penduduk)

		document.getElementById('lat').value = coords[0][1];
		document.getElementById('lng').value = coords[0][0];
	});

	//Geolocation GPS
	var lc = L.control.locate({
		icon: 'fa fa-map-marker',
    strings: {
        title: "Lokasi Saya",
				locateOptions: {enableHighAccuracy: true},
				popup: "Anda berada di sekitar {distance} {unit} dari titik ini"
    }

	}).addTo(peta_penduduk);

	peta_penduduk.on('locationfound', function(e) {
			$('#lat').val(e.latlng.lat);
			$('#lng').val(e.latlng.lng);
	    posisi_penduduk.setLatLng(e.latlng);
	    peta_penduduk.setView(e.latlng)
	});

	peta_penduduk.on('startfollowing', function() {
    peta_penduduk.on('dragstart', lc._stopFollowing, lc);
	}).on('stopfollowing', function() {
    peta_penduduk.off('dragstart', lc._stopFollowing, lc);
	});

	//Export ke GPX
	var geojson = posisi_penduduk.toGeoJSON();
	var shape_for_db = JSON.stringify(geojson);
	var gpxData = togpx(JSON.parse(shape_for_db));

	$("#exportGPX").on('click', function (event) {
		data = 'data:text/xml;charset=utf-8,' + encodeURIComponent(gpxData);
		$(this).attr({
			'href': data,
			'target': '_blank'
		});
	});

	//Menambahkan zoom scale ke peta
	L.control.scale().addTo(peta_penduduk);

	L.control.layers(baseLayers, null, {position: 'topleft', collapsed: true}).addTo(peta_penduduk);

}; //EOF window.onload
</script>
<style>
	#mapx
	{
		width:100%;
		height:45vh
	}
	.icon {
		max-width: 70%;
		max-height: 70%;
		margin: 4px;
	}
	.leaflet-control-layers {
  	display: block;
  	position: relative;
  }
	.leaflet-control-locate a {
  font-size: 2em;
	}
</style>
<!-- Menampilkan OpenStreetMap dalam Box modal bootstrap (AdminLTE)  -->
<div class="content-wrapper">
	<section class="content-header">
		<h1>Lokasi Tempat Tinggal <?= $penduduk['nama']?></h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<?php switch ($edit) {
				case '0': ?>
					<li><a href="<?= site_url("penduduk")?>"> Daftar Penduduk</a></li>
				<?php break; ?>
				<?php case '1': ?>
					<li><a href="<?= site_url("penduduk/form/$p/$o/$id/1")?>"> Biodata Penduduk</a></li>
					<li><a href=#> Lokasi Tempat Tinggal</a></li>
				<?php break; ?>
				<?php case '2': ?>
					<li><a href="<?= site_url("penduduk")?>"> Daftar Penduduk</a></li>
				<?php break; ?>
			<?php } ?>
		</ol>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-info">
					<form id="validasi" action="<?= $form_action?>" method="POST" enctype="multipart/form-data" class="form-horizontal">
						<div class="box-body">
							<div class="row">
								<div class="col-sm-12">
									<div id="mapx">
									</div>
								</div>
							</div>
						</div>
						<div class='box-footer'>
							<div class='col-xs-12'>
								<div class="form-group">
									<label class="col-sm-3 control-label" for="lat">Latitude</label>
									<div class="col-sm-9">
										<?php switch ($edit) {
											case '0': ?>
											<input readonly="readonly" class="form-control number" name="lat1" id="lat1" value="<?= $penduduk['lat']; ?>"/>
											<?php break; ?>
											<?php case '1': ?>
											<input type="text" class="form-control number" name="lat" id="lat" value="<?= $penduduk['lat']; ?>"/>
											<?php break; ?>
											<?php case '2': ?>
											<input type="text" class="form-control number" name="lat" id="lat" value="<?= $penduduk['lat']; ?>"/>
											<?php break; ?>
										<?php } ?>
									</div>
								</div>

								<div class="form-group">
									<label class="col-sm-3 control-label" for="lng">Longitude</label>
									<div class="col-sm-9">
										<?php switch ($edit) {
											case '0': ?>
											<input readonly="readonly" class="form-control number" name="lng1" id="lng1" value="<?= $penduduk['lng']; ?>"/>
											<?php break; ?>
											<?php case '1': ?>
											<input type="text" class="form-control number" name="lng" id="lng" value="<?= $penduduk['lng']; ?>"/>
											<?php break; ?>
											<?php case '2': ?>
											<input type="text" class="form-control number" name="lng" id="lng" value="<?= $penduduk['lng']; ?>"/>
											<?php break; ?>
										<?php } ?>
									</div>
								</div>

								<?php switch ($edit) {
									case '0': ?>
									<a href="<?=site_url("penduduk")?>" class="btn btn-social btn-flat bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Kembali"><i class="fa fa-arrow-circle-o-left"></i> Kembali</a>
									<?php break; ?>
									<?php case '1': ?>
									<a href="<?=site_url("penduduk/form/$p/$o/$id/1")?>" class="btn btn-social btn-flat bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Kembali"><i class="fa fa-arrow-circle-o-left"></i> Kembali</a>
									<a href="#" class="btn btn-social btn-flat btn-success btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" download="OpenSID.gpx" id="exportGPX"><i class='fa fa-download'></i> Export ke GPX</a>
									<button type='reset' class='btn btn-social btn-flat btn-danger btn-sm' id="resetme"><i class='fa fa-times'></i> Reset</button>
									<?php if ($penduduk['status_dasar'] == 1 || !isset($penduduk['status_dasar'])): ?>
										<button type='submit' class='btn btn-social btn-flat btn-info btn-sm pull-right' id="simpan_penduduk"><i class='fa fa-check'></i> Simpan</button>
									<?php endif; ?>
									<?php break; ?>
									<?php case '2': ?>
									<a href="<?=site_url("penduduk")?>" class="btn btn-social btn-flat bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Kembali"><i class="fa fa-arrow-circle-o-left"></i> Kembali</a>
									<a href="#" class="btn btn-social btn-flat btn-success btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" download="OpenSID.gpx" id="exportGPX"><i class='fa fa-download'></i> Export ke GPX</a>
									<button type='reset' class='btn btn-social btn-flat btn-danger btn-sm' id="resetme"><i class='fa fa-times'></i> Reset</button>
									<?php if ($penduduk['status_dasar'] == 1 || !isset($penduduk['status_dasar'])): ?>
										<button type='submit' class='btn btn-social btn-flat btn-info btn-sm pull-right' id="simpan_penduduk"><i class='fa fa-check'></i> Simpan</button>
									<?php endif; ?>
									<?php break; ?>
								<?php } ?>

							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</section>
</div>

<script>
$(document).ready(function()
{
	$('#simpan_penduduk').click(function()
	{
		if (!$('#validasi').valid()) return;

		var lat = $('#lat').val();
		var lng = $('#lng').val();
		$.ajax(
		{
			type: "POST",
			url: "<?=$form_action?>",
			dataType: 'json',
			data: {lat: lat, lng: lng},
		});
	});
});
</script>

<script>
	$(document).ready(function(){
		$('#resetme').click(function(){
			$("#validasi").validate({
				errorElement: "label",
				errorClass: "error",
				highlight:function (element){
					$(element).closest(".form-group").addClass("has-error");
				},
				unhighlight:function (element){
					$(element).closest(".form-group").removeClass("has-error");
				},
				errorPlacement: function (error, element) {
					if (element.parent('.input-group').length) {
						error.insertAfter(element.parent());
					} else {
						error.insertAfter(element);
					}
				}
			});
			window.location.reload(false);
		});
	});
</script>

<script src="<?= base_url()?>assets/js/leaflet.filelayer.js"></script>
<script src="<?= base_url()?>assets/js/togeojson.js"></script>
