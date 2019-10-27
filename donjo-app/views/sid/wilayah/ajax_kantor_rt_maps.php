<script>
	var infoWindow;
	window.onload = function()
	{

		//Jika posisi wilayah rt belum ada, maka posisi peta akan menampilkan posisi peta dusun
		<?php if (!empty($rt['lat'] && !empty($rt['lng']))): ?>
			var posisi = [<?=$rt['lat'].",".$rt['lng']?>];
			var zoom = <?=$rt['zoom'] ?: 16?>;
		<?php else: ?>
			var posisi = [<?=$dusun_rt['lat'].",".$dusun_rt['lng']?>];
			var zoom = <?=$dusun_rt['zoom'] ?: 16?>;
		<?php endif; ?>

                //Inisialisasi tampilan peta
		var peta_rt = L.map('mapx').setView(posisi, zoom);
		L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
			maxZoom: 18,
			attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors',
			id: 'mapbox.streets'
		}).addTo(peta_rt);

		var kantor_rt = L.marker(posisi, {draggable: true}).addTo(peta_rt);
		kantor_rt.on('dragend', function(e){
			$('#lat').val(e.target._latlng.lat);
			$('#lng').val(e.target._latlng.lng);
			$('#map_tipe').val("HYBRID");
			$('#zoom').val(peta_rt.getZoom());
		})

    		peta_rt.on('zoomstart zoomend', function(e){
			$('#zoom').val(peta_rt.getZoom());
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

			kantor_rt.setLatLng(latLng);
			peta_rt.setView(latLng,zoom);
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

			kantor_rt.setLatLng(latLng);
			peta_rt.setView(latLng, zoom);
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
		control.addTo(peta_rt);
		 
		control.loader.on('data:loaded', function (e) {
			peta_rt.removeLayer(kantor_rt);
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
			}).addTo(peta_rt)
		   
		   document.getElementById('lat').value = coords[0][1];
		   document.getElementById('lng').value = coords[0][0];         
		});

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
</style>
<!-- Menampilkan OpenStreetMap dalam Box modal bootstrap (AdminLTE)  -->
<div class="content-wrapper">
	<section class="content-header">
		<h1>Lokasi Kantor RT <?= $rt['rt']?> RW <?= $rt['rw']?> <?= ucwords($this->setting->sebutan_dusun." ".$rt['dusun'])?></h1>
		<ol class="breadcrumb">
      <li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?= site_url('sid_core')?>"> Daftar <?= ucwords($this->setting->sebutan_dusun)?></a></li>
      <li><a href="<?= site_url("sid_core/sub_rw/$id_dusun")?>"> Daftar RW</a></li>
			<li><a href="<?= site_url("sid_core/sub_rt/$id_dusun/$rw[rw]")?>"> Daftar RT</a></li>
			<li class="active">Lokasi Kantor RT </li>
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
					       <div id="mapx">
	                 <input type="hidden" name="zoom" id="zoom"  value="<?= $rt['zoom']?>"/>
						       <input type="hidden" name="map_tipe" id="map_tipe"  value="<?= $rt['map_tipe']?>"/>
	                 <input type="hidden" name="id" id="id"  value="<?= $rt['id']?>"/>
	                 <input type="hidden" name="rw" id="rw"  value="<?= $rt['rw']?>"/>
	                 <input type="hidden" name="dusun" id="dusun"  value="<?= $rt['dusun']?>"/>
	                 <input type="hidden" name="rt" id="rt"  value="<?= $rt['rt']?>"/>
                 </div>
								</div>
            	</div>
						</div>
			      <div class='box-footer'>
			        <div class='col-xs-12'>
                <div class="form-group">
									<label class="col-sm-3 control-label" for="lat">Lat</label>
									<div class="col-sm-9">
										<input type="text" class="form-control number" name="lat" id="lat" value="<?= $rt['lat']?>"/>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label" for="lat">Lng</label>
									<div class="col-sm-9">
										<input type="text" class="form-control number" name="lng" id="lng" value="<?= $rt['lng']?>" />
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
			var zoom = int($('#zoom').val());
			var map_tipe = $('#map_tipe').val();
      			var dusun = $('#dusun').val();
      			var rw = $('#rw').val();
      			var rt = $('#rt').val();
			$.ajax({
				type: "POST",
				url: "<?=$form_action?>",
				dataType: 'json',
				data: {lat: lat, lng: lng, zoom: zoom, map_tipe: map_tipe, id: id, rw: rw, dusun: dusun, rt: rt},
			});
		});
	});
</script>

<script src="<?= base_url()?>assets/js/validasi.js"></script>
<script src="<?= base_url()?>assets/js/jquery.validate.min.js"></script>
<script src="<?= base_url()?>assets/js/leaflet.filelayer.js"></script>
<script src="<?= base_url()?>assets/js/togeojson.js"></script>
