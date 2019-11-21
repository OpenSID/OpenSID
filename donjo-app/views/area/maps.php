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
	//Menggunakan https://github.com/codeofsumit/leaflet.pm
	//Inisialisasi tampilan peta
	var peta_area = L.map('map').setView(posisi, zoom);
	L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
	{
		maxZoom: 18,
		attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors',
		id: 'mapbox.streets'
	}).addTo(peta_area);

	//Tombol yang akan dimunculkan dipeta
	var options =
	{
		position: 'topright', // toolbar position, options are 'topleft', 'topright', 'bottomleft', 'bottomright'
		drawMarker: false, // adds button to draw markers
		drawCircleMarker: false, // adds button to draw markers
		drawPolyline: false, // adds button to draw a polyline
		drawRectangle: false, // adds button to draw a rectangle
		drawPolygon: true, // adds button to draw a polygon
		drawCircle: false, // adds button to draw a cricle
		cutPolygon: false, // adds button to cut a hole in a polygon
		editMode: true, // adds button to toggle edit mode for all layers
		removalMode: true, // adds a button to remove layers
	};

	//Menambahkan toolbar ke peta
	peta_area.pm.addControls(options);

	//Menambahkan Peta wilayah
	peta_area.on('pm:create', function(e)
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
		var polygon = L.polygon(p, { color: '#A9AAAA', weight: 4, opacity: 1 }).addTo(peta_area);

		polygon.on('pm:edit', function(e)
		{
			document.getElementById('path').value = getLatLong('Poly', e.target).toString();
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
	control.addTo(peta_area);

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
		//L.geoJson(JSON.parse(shape_for_db), { //jika ingin koordinat tidak dipotong/simplified
		L.geoJson(simplified, {
			pointToLayer: function (feature, latlng) {
				return L.circleMarker(latlng, { style: style });
			},
			onEachFeature: function (feature, layer) {
				coords.push(feature.geometry.coordinates);

			},

		}).addTo(peta_area);

		var jml = coords[0].length;
		coords[0].push(coords[0][0]);
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
	peta_area.on('pm:globalremovalmodetoggled', function(e)
	{
		document.getElementById('path').value = '';
	})

	//Merubah Peta wilayah yg sudah ada
	<?php if (!empty($area['path'])): ?>
		var daerah_area = <?=$area['path']?>;

		//Titik awal dan titik akhir poligon harus sama
		daerah_area[0].push(daerah_area[0][0]);

		var poligon_area = L.polygon(daerah_area).addTo(peta_area);
		poligon_area.on('pm:edit', function(e)
		{
			document.getElementById('path').value = getLatLong('Poly', e.target).toString();
		})
		setTimeout(function() {peta_area.invalidateSize();peta_area.fitBounds(poligon_area.getBounds());}, 500);

		var layer = poligon_area;
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

</style>
<!-- Menampilkan OpenStreetMap -->
<div class="content-wrapper">
	<section class="content-header">
		<h1>Peta Lokasi <?= $area['nama']?></h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?= site_url("area")?>"> Pengaturan Area </a></li>
			<li class="active">Peta Lokasi <?= $area['nama']?></li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-info">
					<form id="validasi" action="<?= $form_action?>" method="POST" enctype="multipart/form-data" class="form-horizontal">
						<div class="box-body">
							<div class="row">
								<div class="col-sm-12">
									<div id="map">
										<input type="hidden" id="path" name="path" value="<?= $area['path']?>">
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

	<script src="<?= base_url()?>assets/js/validasi.js"></script>
	<script src="<?= base_url()?>assets/js/jquery.validate.min.js"></script>
	<script src="<?= base_url()?>assets/js/leaflet.filelayer.js"></script>
	<script src="<?= base_url()?>assets/js/togeojson.js"></script>
	<script src="<?= base_url()?>assets/js/togpx.js"></script>
