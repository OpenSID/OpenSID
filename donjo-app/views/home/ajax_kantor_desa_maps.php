<script>
	$(document).ready(function(){
		$('#simpan_kantor').click(function(){
			if (!$('#validasi').valid()) return;

			var lat = $('#lat').val();
			var lng = $('#lng').val();
			var zoom = $('#zoom').val();
			var map_tipe = $('#map_tipe').val();
			$.ajax({
				type: "POST",
				url: "<?=$form_action?>",
				dataType: 'json',
				data: {lat: lat, lng: lng, zoom: zoom, map_tipe: map_tipe},
			});
		});
	});
	(function() {
		setTimeout(function() {peta_desa.invalidateSize();}, 500);
		//Jika posisi wilayah desa belum ada, maka posisi peta akan menampilkan seluruh Indonesia
		<?php if (!empty($desa['lat'] && !empty($desa['lng']))): ?>
			var posisi = [<?=$desa['lat'].",".$desa['lng']?>];
			var zoom = <?=$desa['zoom'] ?: 4?>;
		<?php else: ?>
			var posisi = [-1.0546279422758742,116.71875000000001];
			var zoom = 4;
		<?php endif; ?>
		//Inisialisasi tampilan peta
		var peta_desa = L.map('mapx').setView(posisi, zoom);
		L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
			maxZoom: 18,
			attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors',
			id: 'mapbox.streets'
		}).addTo(peta_desa);
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
	})();
</script>

<style>
	#mapx
	{
	z-index: 1;
	width: 100%;
	height: 320px;
	border: 1px solid #000;
	}
</style>
<!-- Menampilkan OpenStreetMap dalam Box modal bootstrap (AdminLTE)  -->
	<div class='modal-body'>
		<div class="row">
			<div class="col-sm-12">
				<div id="mapx"></div>
				<input type="hidden" name="zoom" id="zoom"  value="<?= $desa['zoom']?>"/>
				<input type="hidden" name="map_tipe" id="map_tipe"  value="<?= $desa['map_tipe']?>"/>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<form id="validasi">
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
			<button type="reset" class="btn btn-social btn-flat btn-danger btn-sm" data-dismiss="modal"><i class='fa fa-sign-out'></i> Tutup</button>
			<button type="submit" class="btn btn-social btn-flat btn-info btn-sm" data-dismiss="modal" id="simpan_kantor"><i class='fa fa-check'></i> Simpan</button>
		</form>
	</div>

<script src="<?= base_url()?>assets/js/validasi.js"></script>
<script src="<?= base_url()?>assets/js/jquery.validate.min.js"></script>

