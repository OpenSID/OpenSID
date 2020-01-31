<script>
var infoWindow;
window.onload = function()
{
	//Jika posisi kantor dusun belum ada, maka posisi peta akan menampilkan peta desa
	<?php if (!empty($wil_ini['lat']) && !empty($wil_ini['lng'])): ?>
		var posisi = [<?=$wil_ini['lat'].",".$wil_ini['lng']?>];
		var zoom = <?=$wil_ini['zoom']?>;
	<?php else: ?>
		var posisi = [<?=$wil_atas['lat'].",".$wil_atas['lng']?>];
		var zoom = <?=$wil_atas['zoom']?>;
	<?php endif; ?>

	//Inisialisasi tampilan peta
	var peta_kantor = L.map('mapx').setView(posisi, zoom);

	//Menampilkan BaseLayers Peta
	var defaultLayer = L.tileLayer.provider('OpenStreetMap.Mapnik').addTo(peta_kantor);

	//1. Menampilkan overlayLayers Peta Semua Wilayah
	var marker_desa = [];
	var marker_dusun = [];
	var marker_rw = [];
	var marker_rt = [];

	//WILAYAH DESA
	<?php if (!empty($desa['path'])): ?>
	var daerah_desa = <?=$desa['path']?>;
	var daftar_desa = JSON.parse('<?=addslashes(json_encode($desa))?>');
	var jml = daerah_desa[0].length;
	daerah_desa[0].push(daerah_desa[0][0]);
	for (var x = 0; x < jml; x++)
	{
		daerah_desa[0][x].reverse();
	}
	var style_polygon = {
		stroke: true,
		color: '#FF0000',
		opacity: 1,
		weight: 2,
		fillColor: '#8888dd',
		fillOpacity: 0.5
	};
	<?php if (is_file(LOKASI_LOGO_DESA . "favicon.ico")): ?>
	var point_style = {
			iconSize: [32, 37],
			iconAnchor: [16, 37],
			popupAnchor: [0, -28],
			iconUrl: "<?= base_url()?><?= LOKASI_LOGO_DESA?>favicon.ico"
	};
	<?php else: ?>
	var point_style = {
			iconSize: [32, 37],
			iconAnchor: [16, 37],
			popupAnchor: [0, -28],
			iconUrl: "<?= base_url()?>favicon.ico"
	};
	<?php endif; ?>

	marker_desa.push(turf.polygon(daerah_desa, {content: "<?=ucwords($this->setting->sebutan_desa.' '.$desa['nama_desa'])?>", style: style_polygon, style: L.icon(point_style)}))
	marker_desa.push(turf.point([<?=$desa['lng'].",".$desa['lat']?>], {content: "Kantor Desa",style: L.icon(point_style)}));
	<?php endif; ?>

	//WILAYAH DUSUN
	<?php if (!empty($dusun_gis)): ?>
		var dusun_style = {
			stroke: true,
			color: '#FF0000',
			opacity: 1,
			weight: 2,
			fillColor: '#FFFF00',
			fillOpacity: 0.5
		}
		var daftar_dusun = JSON.parse('<?=addslashes(json_encode($dusun_gis))?>');
		var jml = daftar_dusun.length;
		var jml_path;
		for (var x = 0; x < jml;x++)
		{
			if (daftar_dusun[x].path)
			{
				daftar_dusun[x].path = JSON.parse(daftar_dusun[x].path)
				jml_path = daftar_dusun[x].path[0].length;
				for (var y = 0; y < jml_path; y++)
				{
					daftar_dusun[x].path[0][y].reverse()
				}

				daftar_dusun[x].path[0].push(daftar_dusun[x].path[0][0])
				marker_dusun.push(turf.polygon(daftar_dusun[x].path, {content: '<?=ucwords($this->setting->sebutan_dusun)?>' + ' ' + daftar_dusun[x].dusun, style: dusun_style}));
			}
		}
	<?php endif; ?>

	//WILAYAH RW
	<?php if (!empty($rw_gis)): ?>
		var rw_style = {
			stroke: true,
			color: '#FF0000',
			opacity: 1,
			weight: 2,
			fillColor: '#8888dd',
			fillOpacity: 0.5
		}
		var daftar_rw = JSON.parse('<?=addslashes(json_encode($rw_gis))?>');
		var jml = daftar_rw.length;
		var jml_path;
		for (var x = 0; x < jml;x++)
		{
			if (daftar_rw[x].path)
			{
				daftar_rw[x].path = JSON.parse(daftar_rw[x].path)
				jml_path = daftar_rw[x].path[0].length;
				for (var y = 0; y < jml_path; y++)
				{
					daftar_rw[x].path[0][y].reverse()
				}
				daftar_rw[x].path[0].push(daftar_rw[x].path[0][0])
				marker_rw.push(turf.polygon(daftar_rw[x].path, {content: 'RW' + ' ' + daftar_rw[x].rw, style: rw_style}));
			}
		}
	<?php endif; ?>

	//WILAYAH RT
	<?php if (!empty($rt_gis)): ?>
		var rt_style = {
			stroke: true,
			color: '#FF0000',
			opacity: 1,
			weight: 2,
			fillColor: '#008000',
			fillOpacity: 0.5,
		}
		var daftar_rt = JSON.parse('<?=addslashes(json_encode($rt_gis))?>');
		var jml = daftar_rt.length;
		var jml_path;
		for (var x = 0; x < jml;x++)
		{
			if (daftar_rt[x].path)
			{
				daftar_rt[x].path = JSON.parse(daftar_rt[x].path)
				jml_path = daftar_rt[x].path[0].length;
				for (var y = 0; y < jml_path; y++)
				{
					daftar_rt[x].path[0][y].reverse()
				}
				daftar_rt[x].path[0].push(daftar_rt[x].path[0][0])
				marker_rt.push(turf.polygon(daftar_rt[x].path, {content: 'RT' + ' ' + daftar_rt[x].rt, style: rt_style}));
			}
		}
	<?php endif; ?>

	//2. Menampilkan overlayLayers Peta Semua Wilayah
	<?php if (!empty($wil_atas['path'])): ?>
	var poligon_wil_desa = L.geoJSON(turf.featureCollection(marker_desa), {
		pmIgnore: true,
		onEachFeature: function (feature, layer) {
			layer.bindPopup(feature.properties.content);
			layer.bindTooltip(feature.properties.content);
		},
		style: function(feature)
		{
			if (feature.properties.style)
			{
				return feature.properties.style;
			}
		},
		pointToLayer: function (feature, latlng)
		{
			if (feature.properties.style)
			{
				return L.marker(latlng, {icon: feature.properties.style});
			}
			else
			return L.marker(latlng);
		}
	});

	var poligon_wil_dusun = L.geoJSON(turf.featureCollection(marker_dusun), {
		pmIgnore: true,
		onEachFeature: function (feature, layer) {
			layer.bindPopup(feature.properties.content);
			layer.bindTooltip(feature.properties.content);
		},
		style: function(feature)
		{
			if (feature.properties.style)
			{
				return feature.properties.style;
			}
		},
		pointToLayer: function (feature, latlng)
		{
			if (feature.properties.style)
			{
				return L.marker(latlng, {icon: feature.properties.style});
			}
			else
			return L.marker(latlng);
		}
	});

	var poligon_wil_rw = L.geoJSON(turf.featureCollection(marker_rw), {
		pmIgnore: true,
		onEachFeature: function (feature, layer) {
			layer.bindPopup(feature.properties.content);
			layer.bindTooltip(feature.properties.content);
		},
		style: function(feature)
		{
			if (feature.properties.style)
			{
				return feature.properties.style;
			}
		},
		pointToLayer: function (feature, latlng)
		{
			if (feature.properties.style)
			{
				return L.marker(latlng, {icon: feature.properties.style});
			}
			else
			return L.marker(latlng);
		}
	});

	var poligon_wil_rt = L.geoJSON(turf.featureCollection(marker_rt), {
		pmIgnore: true,
		onEachFeature: function (feature, layer) {
			layer.bindPopup(feature.properties.content);
			layer.bindTooltip(feature.properties.content);
		},
		style: function(feature)
		{
			if (feature.properties.style)
			{
				return feature.properties.style;
			}
		},
		pointToLayer: function (feature, latlng)
		{
			if (feature.properties.style)
			{
				return L.marker(latlng, {icon: feature.properties.style});
			}
			else
			return L.marker(latlng);
		}
	});

	var overlayLayers = {
		'Peta Wilayah Desa': poligon_wil_desa,
		'Peta Wilayah Dusun': poligon_wil_dusun,
		'Peta Wilayah RW': poligon_wil_rw,
		'Peta Wilayah RT': poligon_wil_rt
	};
	<?php else: ?>
	var overlayLayers = {};
	<?php endif; ?>

	var baseLayers = {
		'OpenStreetMap': defaultLayer,
		'OpenStreetMap H.O.T.': L.tileLayer.provider('OpenStreetMap.HOT'),
		'Mapbox Streets' : L.tileLayer('https://api.mapbox.com/v4/mapbox.streets/{z}/{x}/{y}@2x.png?access_token=<?=$this->setting->google_key?>', {attribution: '<a href="https://www.mapbox.com/about/maps">© Mapbox</a> <a href="https://openstreetmap.org/copyright">© OpenStreetMap</a> | <a href="https://mapbox.com/map-feedback/">Improve this map</a>'}),
		'Mapbox Outdoors' : L.tileLayer('https://api.mapbox.com/v4/mapbox.outdoors/{z}/{x}/{y}@2x.png?access_token=<?=$this->setting->google_key?>', {attribution: '<a href="https://www.mapbox.com/about/maps">© Mapbox</a> <a href="https://openstreetmap.org/copyright">© OpenStreetMap</a> | <a href="https://mapbox.com/map-feedback/">Improve this map</a>'}),
		'Mapbox Streets Satellite' : L.tileLayer('https://api.mapbox.com/v4/mapbox.streets-satellite/{z}/{x}/{y}@2x.png?access_token=<?=$this->setting->google_key?>', {attribution: '<a href="https://www.mapbox.com/about/maps">© Mapbox</a> <a href="https://openstreetmap.org/copyright">© OpenStreetMap</a> | <a href="https://mapbox.com/map-feedback/">Improve this map</a>'}),
	};

	var lokasi_kantor = L.marker(posisi, {draggable: true}).addTo(peta_kantor);

	lokasi_kantor.on('dragend', function(e){
		$('#lat').val(e.target._latlng.lat);
		$('#lng').val(e.target._latlng.lng);
		$('#map_tipe').val("HYBRID");
		$('#zoom').val(peta_kantor.getZoom());
	})

	peta_kantor.on('zoomstart zoomend', function(e){
		$('#zoom').val(peta_kantor.getZoom());
	})

	$('#lat').on("input",function(e) {
		if (!$('#validasi1').valid())
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

		lokasi_kantor.setLatLng(latLng);
		peta_kantor.setView(latLng,zoom);
	})

	$('#lng').on("input",function(e) {
		if (!$('#validasi1').valid())
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

		lokasi_kantor.setLatLng(latLng);
		peta_kantor.setView(latLng, zoom);
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
	control.addTo(peta_kantor);

	control.loader.on('data:loaded', function (e) {
		peta_kantor.removeLayer(lokasi_kantor);
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
		}).addTo(peta_kantor)

		document.getElementById('lat').value = coords[0][1];
		document.getElementById('lng').value = coords[0][0];
	});

	//Geolocation GPS
	var lc = L.control.locate({
		icon: 'fa fa-map-marker',
    strings: {
        title: "Lokasi Saya",
				locateOptions: {enableHighAccuracy: true},
				popup: "Anda berada disekitar {distance} {unit} dari titik ini"
    }

	}).addTo(peta_kantor);

	peta_kantor.on('locationfound', function(e) {
			$('#lat').val(e.latlng.lat);
			$('#lng').val(e.latlng.lng);
	    lokasi_kantor.setLatLng(e.latlng);
	    peta_kantor.setView(e.latlng)
	});

	peta_kantor.on('startfollowing', function() {
    peta_kantor.on('dragstart', lc._stopFollowing, lc);
	}).on('stopfollowing', function() {
    peta_kantor.off('dragstart', lc._stopFollowing, lc);
	});

	//Export ke GPX
	var geojson = lokasi_kantor.toGeoJSON();
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
	L.control.scale().addTo(peta_kantor);

	L.control.layers(baseLayers, overlayLayers, {position: 'topleft', collapsed: true}).addTo(peta_kantor);

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
		<h1>Lokasi Kantor <?= $nama_wilayah ?></h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<?php foreach ($breadcrumb as $tautan): ?>
        <li><a href="<?= $tautan['link'] ?>"> <?= $tautan['judul'] ?></a></li>
      <?php endforeach; ?>
			<li class="active">Lokasi Kantor <?= $wilayah ?></li>
		</ol>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-info">
					<form id="validasi1" action="<?= $form_action?>" method="POST" enctype="multipart/form-data" class="form-horizontal">
						<div class="box-body">
							<div class="row">
								<div class="col-sm-12">
									<div id="mapx">
										<input type="hidden" name="zoom" id="zoom"  value="<?= $wil_ini['zoom']?>"/>
										<input type="hidden" name="map_tipe" id="map_tipe"  value="<?= $wil_ini['map_tipe']?>"/>
										<input type="hidden" name="id" id="id"  value="<?= $wil_ini['id']?>"/>
									</div>
								</div>
							</div>
						</div>
						<div class='box-footer'>
							<div class='col-xs-12'>
								<div class="form-group">
									<label class="col-sm-3 control-label" for="lat">Latitude</label>
									<div class="col-sm-9">
										<input type="text" class="form-control number" name="lat" id="lat" value="<?= $wil_ini['lat']?>"/>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label" for="lat">Longitude</label>
									<div class="col-sm-9">
										<input type="text" class="form-control number" name="lng" id="lng" value="<?= $wil_ini['lng']?>" />
									</div>
								</div>
								<a href="<?= $tautan['link'] ?>" class="btn btn-social btn-flat bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Kembali"><i class="fa fa-arrow-circle-o-left"></i> Kembali</a>
								<a href="#" class="btn btn-social btn-flat btn-success btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" download="OpenSID.gpx" id="exportGPX"><i class='fa fa-download'></i> Export ke GPX</a>
								<button type='reset' class='btn btn-social btn-flat btn-danger btn-sm' id="resetme"><i class='fa fa-times'></i> Reset</button>
								<button type='submit' class='btn btn-social btn-flat btn-info btn-sm pull-right' id="simpan_kantor"><i class='fa fa-check'></i> Simpan</button>
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

			$("#validasi1").validate({
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

			if (!$('#validasi1').valid()) return;

			var id = $('#id').val();
			var lat = $('#lat').val();
			var lng = $('#lng').val();
			var zoom = int($('#zoom').val());
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

<script>
	$(document).ready(function(){
		$('#resetme').click(function(){
			$("#validasi1").validate({
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
