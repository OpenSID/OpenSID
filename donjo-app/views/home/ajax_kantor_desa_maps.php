<script>
	$(document).ready(function(){
		$('#simpan_kantor').click(function(){
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
				document.getElementById('lat').value = e.target._latlng.lat;
				document.getElementById('lng').value = e.target._latlng.lng;
				document.getElementById('map_tipe').value = "HYBRID"
				document.getElementById('zoom').value = peta_desa.getZoom();
			})
			peta_desa.on('zoomstart zoomend', function(e){
				document.getElementById('zoom').value = peta_desa.getZoom();
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
        <input type="hidden" name="lat" id="lat" value="<?= $desa['lat']?>"/>
        <input type="hidden" name="lng" id="lng"  value="<?= $desa['lng']?>"/>
        <input type="hidden" name="zoom" id="zoom"  value="<?= $desa['zoom']?>"/>
        <input type="hidden" name="map_tipe" id="map_tipe"  value="<?= $desa['map_tipe']?>"/>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<button type="reset" class="btn btn-social btn-flat btn-danger btn-sm" data-dismiss="modal"><i class='fa fa-sign-out'></i> Tutup</button>
		<button type="submit" class="btn btn-social btn-flat btn-info btn-sm" data-dismiss="modal" id="simpan_kantor"><i class='fa fa-check'></i> Simpan</button>
	</div>

