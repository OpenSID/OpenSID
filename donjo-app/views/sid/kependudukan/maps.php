

<script>
	$(document).ready(function()
	{
		$('#simpan_penduduk').click(function()
		{
			var lat = $('#lat').val();
			var lng = $('#lng').val();
			$.ajax(
			{
				type: "POST",
				url: "<?=$form_action?>",
				dataType: 'json',
				data: {lat: lat, lng: lng},
			});
		});
	});
	(function()
	{
		setTimeout(function() {peta_desa.invalidateSize();}, 500);
		<?php if (!empty($penduduk['lat'])):	?>
			var posisi = [<?= $penduduk['lat'].",".$penduduk['lng']; ?>];
			var zoom = <?= $desa['zoom'] ?: 10; ?>;
		<?php else: ?>
			var posisi = [-7.885619783139936, 110.39893195996092];
			var zoom = 10;
		<?php	endif; ?>
		//Inisialisasi tampilan peta
		var peta_desa = L.map('map').setView(posisi, zoom);
		L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
			maxZoom: 18,
			attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors',
			id: 'mapbox.streets'
		}).addTo(peta_desa);
		var posisi_penduduk = L.marker(posisi, {draggable: <?php echo ($penduduk['status_dasar'] == 1  || !isset($penduduk['status_dasar']) ? "true" : "false"); ?>}).addTo(peta_desa);
		posisi_penduduk.on('dragend', function(e){
			document.getElementById('lat').value = e.target._latlng.lat;
			document.getElementById('lng').value = e.target._latlng.lng;
		})
	})();
</script>

<style>
	#map
	{
		width: 100%;
		height: 320px;
		border: 1px solid #000;
	}
</style>
	<div class='modal-body'>
		<div class="row">
			<div class="col-sm-12">
        <div id="map"></div>
        <input type="hidden" name="lat" id="lat" value="<?= $penduduk['lat']; ?>" />
        <input type="hidden" name="lng" id="lng" value="<?= $penduduk['lng']; ?>"/>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<button type="reset" class="btn btn-social btn-flat btn-danger btn-sm" data-dismiss="modal"><i class='fa fa-sign-out'></i> Tutup</button>
    <?php

			if ($edit == 1) {
				if($penduduk['status_dasar'] == 1 || !isset($penduduk['status_dasar'])): ?>
				 <button type="submit" class="btn btn-social btn-flat btn-info btn-sm" id="simpan_penduduk" data-dismiss="modal"><i class='fa fa-check'></i> Simpan</button>
			 <?php endif;
		 }
			  ?>
		  </div>
