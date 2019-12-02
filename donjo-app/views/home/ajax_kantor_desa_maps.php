<script>
var infoWindow;
window.onload = function()
{

	//Jika posisi wilayah desa belum ada, maka posisi peta akan menampilkan seluruh Indonesia
	<?php if (!empty($desa['lat'] && !empty($desa['lng']))): ?>
		var posisi = [<?=$desa['lat'].",".$desa['lng']?>];
		var zoom = <?=$desa['zoom'] ?: 16?>;
	<?php else: ?>
		var posisi = [-1.0546279422758742,116.71875000000001];
		var zoom = 4;
	<?php endif; ?>

	//Inisialisasi tampilan peta
	var peta_desa = L.map('mapx').setView(posisi, zoom);

	//Menampilkan BaseLayers Peta
	var defaultLayer = L.tileLayer.provider('OpenStreetMap.Mapnik').addTo(peta_desa);

	var baseLayers = {
		'OpenStreetMap': defaultLayer,
		'OpenStreetMap H.O.T.': L.tileLayer.provider('OpenStreetMap.HOT'),
		'Mapbox Streets' : L.tileLayer('https://api.mapbox.com/v4/mapbox.streets/{z}/{x}/{y}@2x.png?access_token=<?=$this->setting->google_key?>', {attribution: '<a href="https://www.mapbox.com/about/maps">© Mapbox</a> <a href="https://openstreetmap.org/copyright">© OpenStreetMap</a> | <a href="https://mapbox.com/map-feedback/">Improve this map</a>'}),
		'Mapbox Outdoors' : L.tileLayer('https://api.mapbox.com/v4/mapbox.outdoors/{z}/{x}/{y}@2x.png?access_token=<?=$this->setting->google_key?>', {attribution: '<a href="https://www.mapbox.com/about/maps">© Mapbox</a> <a href="https://openstreetmap.org/copyright">© OpenStreetMap</a> | <a href="https://mapbox.com/map-feedback/">Improve this map</a>'}),
		'Mapbox Streets Satellite' : L.tileLayer('https://api.mapbox.com/v4/mapbox.streets-satellite/{z}/{x}/{y}@2x.png?access_token=<?=$this->setting->google_key?>', {attribution: '<a href="https://www.mapbox.com/about/maps">© Mapbox</a> <a href="https://openstreetmap.org/copyright">© OpenStreetMap</a> | <a href="https://mapbox.com/map-feedback/">Improve this map</a>'}),
	};

	var kantor_desa = L.marker(posisi, {draggable: true}).addTo(peta_desa);
	kantor_desa.on('dragend', function(e){
		$('#lat').val(e.target._latlng.lat);
		$('#lng').val(e.target._latlng.lng);
		$('#map_tipe').val("HYBRID");
		$('#zoom').val(peta_desa.getZoom());
	})

	peta_desa.on('zoomstart zoomend', function(e){
		$('#zoom').val(peta_desa.getZoom());
	})

	$('#lat').on("input",function(e) {
		if (!$('#validasi').valid())
		{
			$("#simpan_kantor").attr('disabled', true);
			return;
		} else
		{
			$("#simpan_kantor").attr('disabled', false);
		}
		let lat = $('#lat').val();
		let lng = $('#lng').val();
		let latLng = L.latLng({
			lat: lat,
			lng: lng
		});

		kantor_desa.setLatLng(latLng);
		peta_desa.setView(latLng,zoom);
	})

	$('#lng').on("input",function(e) {
		if (!$('#validasi').valid())
		{
			$("#simpan_kantor").attr('disabled', true);
			return;
		} else
		{
			$("#simpan_kantor").attr('disabled', false);
		}
		let lat = $('#lat').val();
		let lng = $('#lng').val();
		let latLng = L.latLng({
			lat: lat,
			lng: lng
		});

		kantor_desa.setLatLng(latLng);
		peta_desa.setView(latLng, zoom);
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
	control.addTo(peta_desa);

	control.loader.on('data:loaded', function (e) {
		peta_desa.removeLayer(kantor_desa);
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
		}).addTo(peta_desa)

		document.getElementById('lat').value = coords[0][1];
		document.getElementById('lng').value = coords[0][0];
	});

	L.control.layers(baseLayers, null, {position: 'topleft', collapsed: true}).addTo(peta_desa);

}; //EOF window.onload
</script>
<style>
#mapx
{
	width:100%;
	height:50vh
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
</style>
<!-- Menampilkan OpenStreetMap dalam Box modal bootstrap (AdminLTE)  -->
<div class="content-wrapper">
	<section class="content-header">
		<h1>Lokasi Kantor <?= ucwords($this->setting->sebutan_desa." ".$desa['nama_desa'])?></h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?= site_url("hom_desa/konfigurasi")?>"> Identitas <?=ucwords($this->setting->sebutan_desa)?></a></li>
			<li class="active">Lokasi Kantor <?= ucwords($this->setting->sebutan_desa." ".$desa['nama_desa'])?></li>
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
										<input type="hidden" name="zoom" id="zoom"  value="<?= $desa['zoom']?>"/>
										<input type="hidden" name="map_tipe" id="map_tipe"  value="<?= $desa['map_tipe']?>"/>
										<input type="hidden" name="id" id="id"  value="<?= $desa['id']?>"/>
									</div>
								</div>
							</div>
						</div>
						<div class='box-footer'>
							<div class='col-xs-12'>
								<div class="form-group">
									<label class="col-sm-3 control-label" for="lat">Lat</label>
									<div class="col-sm-9">
										<input type="text" class="form-control number" name="lat" id="lat" value="<?= $desa['lat']?>"/>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label" for="lat">Lng</label>
									<div class="col-sm-9">
										<input type="text" class="form-control number" name="lng" id="lng" value="<?= $desa['lng']?>" />
									</div>
								</div>
								<button type='reset' class='btn btn-social btn-flat btn-danger btn-sm invisible' ><i class='fa fa-times'></i> Batal</button>
								<button type='submit' class='btn btn-social btn-flat btn-info btn-sm pull-right'><i class='fa fa-check'></i> Simpan</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</section>
</div>

<script>
	$(document).ready(function(){
		$('#simpan_kantor').click(function(){
			if (!$('#validasi').valid()) return;

			var id = $('#id').val();
			var lat = $('#lat').val();
			var lng = $('#lng').val();
			var zoom = $('#zoom').val();
			var map_tipe = $('#map_tipe').val();
			$.ajax({
				type: "POST",
				url: "<?=$form_action?>",
				dataType: 'json',
				data: {lat: lat, lng: lng, zoom: zoom, map_tipe: map_tipe, id: id},
			});
		});
	});
</script>

<script src="<?= base_url()?>assets/js/validasi.js"></script>
<script src="<?= base_url()?>assets/js/jquery.validate.min.js"></script>
<script src="<?= base_url()?>assets/js/leaflet.filelayer.js"></script>
<script src="<?= base_url()?>assets/js/togeojson.js"></script>
