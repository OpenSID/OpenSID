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
		setTimeout(function() {peta_rw.invalidateSize();}, 500);
		//Jika posisi wilayah rw belum ada, maka posisi peta akan menampilkan seluruh Indonesia
		<?php if (!empty($rw['lat'] && !empty($rw['lng']))): ?>
			var posisi = [<?=$rw['lat'].",".$rw['lng']?>];
			var zoom = <?=$rw['zoom'] ?: 16?>;
		<?php else: ?>
			var posisi = [<?=$dusun['lat'].",".$dusun['lng']?>];
                        var zoom = <?=$dusun['zoom'] ?: 18?>;
		<?php endif; ?>
		//Inisialisasi tampilan peta
		var peta_rw = L.map('mapx').setView(posisi, zoom);
		L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
			maxZoom: 18,
			attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors',
			id: 'mapbox.streets'
		}).addTo(peta_rw);
		var kantor_rw = L.marker(posisi, {draggable: true}).addTo(peta_rw);
		kantor_rw.on('dragend', function(e){
			$('#lat').val(e.target._latlng.lat);
			$('#lng').val(e.target._latlng.lng);
			$('#map_tipe').val("HYBRID");
			$('#zoom').val(peta_rw.getZoom());
		})
		peta_rw.on('zoomstart zoomend', function(e){
			$('#zoom').val(peta_rw.getZoom());
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

			kantor_rw.setLatLng(latLng);
			peta_rw.setView(latLng,zoom);
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

			kantor_rw.setLatLng(latLng);
			peta_rw.setView(latLng, zoom);
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
				<input type="hidden" name="zoom" id="zoom"  value="<?= $rw['zoom']?>"/>
				<input type="hidden" name="map_tipe" id="map_tipe"  value="<?= $rw['map_tipe']?>"/>
                                <input type="hidden" name="id" id="id"  value="<?= $rw['id']?>"/>                                
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<form id="validasi">
                        <div class="form-group">
				<label class="col-sm-3 control-label" for="lat">Lat</label>
				<div class="col-sm-9">
					<input type="text" class="form-control number" name="lat" id="lat" value="<?= $rw['lat']?>"/>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label" for="lat">Lng</label>
				<div class="col-sm-9">
					<input type="text" class="form-control number" name="lng" id="lng" value="<?= $rw['lng']?>" />
				</div>
			</div>
			<button type="reset" class="btn btn-social btn-flat btn-danger btn-sm" data-dismiss="modal"><i class='fa fa-sign-out'></i> Tutup</button>
			<button type="submit" class="btn btn-social btn-flat btn-info btn-sm" data-dismiss="modal" id="simpan_kantor"><i class='fa fa-check'></i> Simpan</button>
		</form>
	</div>

<script src="<?= base_url()?>assets/js/validasi.js"></script>
<script src="<?= base_url()?>assets/js/jquery.validate.min.js"></script>

