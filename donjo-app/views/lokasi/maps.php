<script>
var infoWindow;
window.onload = function()
{
	<?php if (!empty($lokasi['lat']) && !empty($lokasi['lng'])): ?>
		var posisi = [<?=$lokasi['lat'].",".$lokasi['lng']?>];
		var zoom = 16;
	<?php else: ?>
		var posisi = [<?=$desa['lat'].",".$desa['lng']?>];
		var zoom = <?=$desa['zoom'] ?: 16?>;
	<?php endif; ?>

	//Inisialisasi tampilan peta
	var peta_lokasi = L.map('mapx').setView(posisi, zoom);

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
    var overlayLayers = overlayWil(marker_desa, marker_dusun, marker_rw, marker_rt);
  <?php else: ?>
    var overlayLayers = {};
  <?php endif; ?>

	//Menampilkan BaseLayers Peta
  var baseLayers = getBaseLayers(peta_lokasi, '<?=$this->setting->google_key?>');

	var lokasi_kantor = L.marker(posisi, {draggable: true}).addTo(peta_lokasi);
	lokasi_kantor.on('dragend', function(e){
		$('#lat').val(e.target._latlng.lat);
		$('#lng').val(e.target._latlng.lng);
	})

	peta_lokasi.on('zoomstart zoomend', function(e){
		$('#zoom').val(peta_lokasi.getZoom());
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
		peta_lokasi.setView(latLng,zoom);
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
		peta_lokasi.setView(latLng, zoom);
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
	control.addTo(peta_lokasi);

	control.loader.on('data:loaded', function (e) {
		peta_lokasi.removeLayer(lokasi_kantor);
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
		}).addTo(peta_lokasi)

		document.getElementById('lat').value = coords[0][1];
	});

	//Geolocation GPS
	var lc = L.control.locate({
		icon: 'fa fa-map-marker',
    strings: {
        title: "Lokasi Saya",
				locateOptions: {enableHighAccuracy: true},
				popup: "Anda berada disekitar {distance} {unit} dari titik ini"
    }

	}).addTo(peta_lokasi);

	peta_lokasi.on('locationfound', function(e) {
			$('#lat').val(e.latlng.lat);
			$('#lng').val(e.latlng.lng);
	    lokasi_kantor.setLatLng(e.latlng);
	    peta_lokasi.setView(e.latlng)
	});

	peta_lokasi.on('startfollowing', function() {
    peta_lokasi.on('dragstart', lc._stopFollowing, lc);
	}).on('stopfollowing', function() {
    peta_lokasi.off('dragstart', lc._stopFollowing, lc);
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
	L.control.scale().addTo(peta_lokasi);

	L.control.layers(baseLayers, overlayLayers, {position: 'topleft', collapsed: true}).addTo(peta_lokasi);

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
	.leaflet-control-locate a {
  font-size: 2em;
	}
</style>
<!-- Menampilkan OpenStreetMap dalam Box modal bootstrap (AdminLTE)  -->
<div class="content-wrapper">
	<section class="content-header">
		<h1>Lokasi <?= $lokasi['nama']?></h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?= site_url('plan')?>"> Pengaturan Lokasi</a></li>
			<li class="active">Lokasi <?= $lokasi['nama']?></li>
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
										<input type="hidden" name="id" id="id"  value="<?= $lokasi['id']?>"/>
									</div>
								</div>
							</div>
						</div>
						<div class='box-footer'>
							<div class='col-xs-12'>
								<div class="form-group">
									<label class="col-sm-3 control-label" for="lat">Lat</label>
									<div class="col-sm-9">
										<input type="text" class="form-control number" name="lat" id="lat" value="<?= $lokasi['lat']?>"/>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label" for="lat">Lng</label>
									<div class="col-sm-9">
										<input type="text" class="form-control number" name="lng" id="lng" value="<?= $lokasi['lng']?>" />
									</div>
								</div>
								<a href="<?= site_url('plan')?>" class="btn btn-social btn-flat bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Kembali"><i class="fa fa-arrow-circle-o-left"></i> Kembali</a>
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

			window.location.reload(false);

			var id = $('#id').val();
			var lat = $('#lat').val();
			var lng = $('#lng').val();

			$.ajax({
				type: "POST",
				url: "<?=$form_action?>",
				dataType: 'json',
				data: {lat: lat, lng: lng, id: id},
			});
		});
	});
</script>

<script src="<?= base_url()?>assets/js/leaflet.filelayer.js"></script>
<script src="<?= base_url()?>assets/js/togeojson.js"></script>
