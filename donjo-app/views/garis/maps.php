<script>

var infoWindow;
window.onload = function()
{

	//Jika posisi kantor rt belum ada, maka posisi peta akan menampilkan seluruh Indonesia
	<?php if (!empty($desa['lat']) && !empty($desa['lng'])): ?>
		var posisi = [<?=$desa['lat'].",".$desa['lng']?>];
		var zoom = <?=$desa['zoom'] ?: 18?>;
	<?php else: ?>
		var posisi = [-1.0546279422758742,116.71875000000001];
		var zoom = 4;
	<?php endif; ?>

	//Inisialisasi tampilan peta
	var peta_garis = L.map('map').setView(posisi, zoom);

	//Menampilkan BaseLayers Peta
	var defaultLayer = L.tileLayer.provider('OpenStreetMap.Mapnik').addTo(peta_garis);

	var baseLayers = {
		'OpenStreetMap': defaultLayer,
		'OpenStreetMap H.O.T.': L.tileLayer.provider('OpenStreetMap.HOT'),
		'Mapbox Streets' : L.tileLayer('https://api.mapbox.com/v4/mapbox.streets/{z}/{x}/{y}@2x.png?access_token=<?=$this->setting->google_key?>', {attribution: '<a href="https://www.mapbox.com/about/maps">© Mapbox</a> <a href="https://openstreetmap.org/copyright">© OpenStreetMap</a> | <a href="https://mapbox.com/map-feedback/">Improve this map</a>'}),
		'Mapbox Outdoors' : L.tileLayer('https://api.mapbox.com/v4/mapbox.outdoors/{z}/{x}/{y}@2x.png?access_token=<?=$this->setting->google_key?>', {attribution: '<a href="https://www.mapbox.com/about/maps">© Mapbox</a> <a href="https://openstreetmap.org/copyright">© OpenStreetMap</a> | <a href="https://mapbox.com/map-feedback/">Improve this map</a>'}),
		'Mapbox Streets Satellite' : L.tileLayer('https://api.mapbox.com/v4/mapbox.streets-satellite/{z}/{x}/{y}@2x.png?access_token=<?=$this->setting->google_key?>', {attribution: '<a href="https://www.mapbox.com/about/maps">© Mapbox</a> <a href="https://openstreetmap.org/copyright">© OpenStreetMap</a> | <a href="https://mapbox.com/map-feedback/">Improve this map</a>'}),
	};

	//Tombol yang akan dimunculkan dipeta
	var options =
	{
		position: 'topright', // toolbar position, options are 'topleft', 'topright', 'bottomleft', 'bottomright'
		drawMarker: false, // adds button to draw markers
		drawCircleMarker: false, // adds button to draw markers
		drawPolyline: true, // adds button to draw a polyline
		drawRectangle: false, // adds button to draw a rectangle
		drawPolygon: false, // adds button to draw a polygon
		drawCircle: false, // adds button to draw a cricle
		cutPolygon: false, // adds button to cut a hole in a polygon
		editMode: true, // adds button to toggle edit mode for all layers
		removalMode: true, // adds a button to remove layers
	};

	//Menambahkan toolbar ke peta
	peta_garis.pm.addControls(options);

	//Menambahkan Peta wilayah
	peta_garis.on('pm:create', function(e)
	{
		var type = e.layerType;
		var layer = e.layer;
		var latLngs;

		if (type === 'circle') {
			latLngs = layer.getLatLng();
		}
		else
			latLngs = layer.getLatLngs();

		var p = latLngs;
		var polygon = L.polyline(p, { color: '#A9AAAA', weight: 4, opacity: 1 }).addTo(peta_garis);

		polygon.on('pm:edit', function(e)
		{
			document.getElementById('path').value = getLatLong('Line', e.target).toString();
		})

	});

	//Unggah Peta dari file GPX/KML

	var style = {
		color: 'red',
		opacity: 1.0,
		fillOpacity: 1.0,
		weight: 2,
		clickable: true
	};

	L.Control.FileLayerLoad.LABEL = '<img class="icon" src="<?= base_url()?>assets/images/folder.svg" alt="file icon"/>';

	control = L.Control.fileLayerLoad({
		addToMap: false,
		formats: [
			'.gpx',
			'.geojson'
		],
		fitBounds: true,
		layerOptions: {
			style: style,
			pointToLayer: function (data, latlng) {
				return L.circleMarker(
					latlng,
					{ style: style }
				);
			},

		}
	});
	control.addTo(peta_garis);

	control.loader.on('data:loaded', function (e) {
		var type = e.layerType;
		var layer = e.layer;
		var coords=[];
		var geojson = layer.toGeoJSON();
		var options = {tolerance: 0.0001, highQuality: false};
		var simplified = turf.simplify(geojson, options);
		var shape_for_db = JSON.stringify(geojson);
		var gpxData = togpx(JSON.parse(shape_for_db));

		$("#exportGPX").on('click', function (event) {
			data = 'data:text/xml;charset=utf-8,' + encodeURIComponent(gpxData);

			$(this).attr({
				'href': data,
				'target': '_blank'
			});

		});

		var polygon =
		L.geoJson(JSON.parse(shape_for_db), { //jika ingin koordinat tidak dipotong/simplified
		//L.geoJson(simplified, {
			pointToLayer: function (feature, latlng) {
				return L.circleMarker(latlng, { style: style });
			},
			onEachFeature: function (feature, layer) {
				coords.push(feature.geometry.coordinates);

			},

		}).addTo(peta_garis);

		var jml = coords[0].length;
		for (var x = 0; x < jml; x++)
		{
			coords[0][x].reverse();
		}

		polygon.on('pm:edit', function(e)
		{
			document.getElementById('path').value = JSON.stringify(coords);
		});

		document.getElementById('path').value = JSON.stringify(coords);

	});


	//Menghapus Peta wilayah
	peta_garis.on('pm:globalremovalmodetoggled', function(e)
	{
		document.getElementById('path').value = '';
	})

	//Merubah Peta wilayah yg sudah ada
	<?php if (!empty($garis['path'])): ?>
		var daerah_garis = <?=$garis['path']?>;

		var poligon_garis = L.polyline(daerah_garis).addTo(peta_garis);
		poligon_garis.on('pm:edit', function(e)
		{
			document.getElementById('path').value = getLatLong('Line', e.target).toString();
		})
		setTimeout(function() {peta_garis.invalidateSize();peta_garis.fitBounds(poligon_garis.getBounds());}, 500);

		var layer = poligon_garis;
		var geojson = layer.toGeoJSON();
		var shape_for_db = JSON.stringify(geojson);
		var gpxData = togpx(JSON.parse(shape_for_db));

		$("#exportGPX").on('click', function (event) {
			data = 'data:text/xml;charset=utf-8,' + encodeURIComponent(gpxData);
			$(this).attr({
				'href': data,
				'target': '_blank'
			});
		});
	<?php endif; ?>

	//Fungsi
	function getLatLong(x, y)
	{
		var hasil;
		if (x == 'Rectangle' || x == 'Line' || x == 'Poly')
		{
			hasil = JSON.stringify(y._latlngs);
		}
		else
		{
			hasil = JSON.stringify(y._latlng);
		}
		hasil = hasil.replace(/\}/g, ']').replace(/(\{)/g, '[').replace(/(\"lat\"\:|\"lng\"\:)/g, '');
		return hasil;
	}

	L.control.layers(baseLayers, null, {position: 'topleft', collapsed: true}).addTo(peta_garis);

}; //EOF window.onload
</script>
<style>
#map
{
	width:100%;
	height:65vh
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
<!-- Menampilkan OpenStreetMap -->
<div class="content-wrapper">
	<section class="content-header">
		<h1>Peta Lokasi <?= $garis['nama']?></h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?= site_url("garis")?>"> Pengaturan garis </a></li>
			<li class="active">Peta Lokasi <?= $garis['nama']?></li>
		</ol>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-info">
					<form action="<?= $form_action?>" method="POST" enctype="multipart/form-data" class="form-horizontal">
						<div class="box-body">
							<div class="row">
								<div class="col-sm-12">
									<div id="map">
										<input type="hidden" id="path" name="path" value="<?= $garis['path']?>">
									</div>
								</div>
							</div>
						</div>
						<div class='box-footer'>
							<div class='col-xs-12'>
								<a href="#" class="btn btn-social btn-flat btn-info btn-sm" download="OpenSID.gpx" id="exportGPX"><i class='fa fa-download'></i> Export ke GPX</a>
								<button type='submit' class='btn btn-social btn-flat btn-info btn-sm pull-right'><i class='fa fa-check'></i> Simpan</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</section>
</div>

<script src="<?= base_url()?>assets/js/leaflet.filelayer.js"></script>
<script src="<?= base_url()?>assets/js/togeojson.js"></script>
