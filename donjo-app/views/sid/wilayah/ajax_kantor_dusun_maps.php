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
	(function() {
		setTimeout(function() {peta_dusun.invalidateSize();}, 500);
		//Jika posisi wilayah dusun belum ada, maka posisi peta akan menampilkan seluruh Indonesia
		<?php if (!empty($dusun['lat'] && !empty($dusun['lng']))): ?>
			var posisi = [<?=$dusun['lat'].",".$dusun['lng']?>];
			var zoom = <?=$dusun['zoom'] ?: 16?>;
		<?php else: ?>
			var posisi = [<?=$desa['lat'].",".$desa['lng']?>];
			var zoom = <?=$desa['zoom'] ?: 16?>;
		<?php endif; ?>
		//Inisialisasi tampilan peta
		var peta_dusun = L.map('mapx').setView(posisi, zoom);
		L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
			maxZoom: 18,
			attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors',
			id: 'mapbox.streets'
		}).addTo(peta_dusun);
		var kantor_dusun = L.marker(posisi, {draggable: true}).addTo(peta_dusun);
		kantor_dusun.on('dragend', function(e){
			$('#lat').val(e.target._latlng.lat);
			$('#lng').val(e.target._latlng.lng);
			$('#map_tipe').val("HYBRID");
			$('#zoom').val(peta_dusun.getZoom());
		})
		peta_dusun.on('zoomstart zoomend', function(e){
			$('#zoom').val(peta_dusun.getZoom());
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

			kantor_dusun.setLatLng(latLng);
			peta_dusun.setView(latLng,zoom);
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

			kantor_dusun.setLatLng(latLng);
			peta_dusun.setView(latLng, zoom);
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
				<input type="hidden" name="zoom" id="zoom"  value="<?= $dusun['zoom']?>"/>
				<input type="hidden" name="map_tipe" id="map_tipe"  value="<?= $dusun['map_tipe']?>"/>
                                <input type="hidden" name="id" id="id"  value="<?= $dusun['id']?>"/>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<form id="validasi">
                        <div class="form-group">
				<label class="col-sm-3 control-label" for="lat">Lat</label>
				<div class="col-sm-9">
					<input type="text" class="form-control number" name="lat" id="lat" value="<?= $dusun['lat']?>"/>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label" for="lat">Lng</label>
				<div class="col-sm-9">
					<input type="text" class="form-control number" name="lng" id="lng" value="<?= $dusun['lng']?>" />
				</div>
			</div>
			<button type="reset" class="btn btn-social btn-flat btn-danger btn-sm" data-dismiss="modal"><i class='fa fa-sign-out'></i> Tutup</button>
			<button type="submit" class="btn btn-social btn-flat btn-info btn-sm" data-dismiss="modal" id="simpan_kantor"><i class='fa fa-check'></i> Simpan</button>
		</form>
	</div>

<script src="<?= base_url()?>assets/js/validasi.js"></script>
<script src="<?= base_url()?>assets/js/jquery.validate.min.js"></script>

