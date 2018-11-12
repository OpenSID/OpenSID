<!-- OpenStreetMap Js-->
<script src="<?= base_url()?>assets/js/leaflet.js"></script>
<script src="<?= base_url()?>assets/js/turf.min.js"></script>
<script src="<?= base_url()?>assets/js/leaflet.pm.min.js"></script>
<script>
	(function() {
		setTimeout(function() {peta_desa.invalidateSize();}, 500);
		//Jika posisi peta_desa belum ada, maka gunakan peta_desa default
		<?php if (!empty($lokasi['lat']) && !empty($lokasi['lng'])): ?>
			var posisi = [<?=$lokasi['lat'].",".$lokasi['lng']?>];
			var zoom = <?=$desa['zoom'] ?: 10?>;
		<?php else: ?>
			var posisi = [-7.885619783139936, 110.39893195996092];
			var zoom = 10;
		<?php endif; ?>
		//Inisialisasi tampilan peta
		var peta_desa = L.map('map_lokasi').setView(posisi, zoom);
		L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
			maxZoom: 18,
			attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors',
			id: 'map_lokasi'
		}).addTo(peta_desa);
		var lokasi_marker = L.marker(posisi, {draggable: true}).addTo(peta_desa);
		lokasi_marker.on('dragend', function(e){
			document.getElementById('lat').value = e.target._latlng.lat;
			document.getElementById('lng').value = e.target._latlng.lng;
		})
	})();
</script>
<style>
  #map_lokasi
  {
		z-index: 1;
    width: 100%;
    height: 320px;
    border: 1px solid #000;
  }
</style>
<form action="<?= $form_action?>" method="post" id="validasi">
	<div class='modal-body'>
		<div class="row">
			<div class="col-sm-12">
				<div id="map_lokasi"></div>
				<input type="hidden" name="lat" id="lat" value="<?= $lokasi['lat']?>"/>
    		<input type="hidden" name="lng" id="lng" value="<?= $lokasi['lng']?>" />>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<button type="reset" class="btn btn-social btn-flat btn-danger btn-sm" data-dismiss="modal"><i class='fa fa-sign-out'></i> Tutup</button>
		<button type="submit" class="btn btn-social btn-flat btn-info btn-sm"><i class='fa fa-check'></i> Simpan</button>
	</div>
</form>
