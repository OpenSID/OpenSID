

<script>
	$(document).ready(function()
	{
		$('#simpan_penduduk').click(function()
		{
			if (!$('#validasi').valid()) return;

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
			var posisi = [<?= $desa['lat'].",".$desa['lng']; ?>];
			var zoom = 10;
		<?php	endif; ?>
		//Inisialisasi tampilan peta
		var peta_desa = L.map('map').setView(posisi, zoom);
		L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
			maxZoom: 18,
			attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors',
			id: 'mapbox.streets'
		}).addTo(peta_desa);
		var posisi_penduduk = L.marker(posisi, {draggable: <?= ($penduduk['status_dasar'] == 1  || !isset($penduduk['status_dasar']) ? "true" : "false"); ?>}).addTo(peta_desa);
		posisi_penduduk.on('dragend', function(e){
			$('#lat').val(e.target._latlng.lat);
			$('#lng').val(e.target._latlng.lng);
		})

		$('#lat').on("input",function(e) {
			if (!$('#validasi').valid())
			{
				$("#simpan_penduduk").attr('disabled', true);
				return;
			} else
			{
				$("#simpan_penduduk").attr('disabled', false);
			}
			let lat = $('#lat').val();
			let lng = $('#lng').val();
			let latLng = L.latLng({
				lat: lat,
				lng: lng
			});


			posisi_penduduk.setLatLng(latLng);
			peta_desa.setView(latLng,zoom);
		})

		$('#lng').on("input",function(e) {
			if (!$('#validasi').valid())
			{
				$("#simpan_penduduk").attr('disabled', true);
				return;
			} else
			{
				$("#simpan_penduduk").attr('disabled', false);
			}
			let lat = $('#lat').val();
			let lng = $('#lng').val();
			let latLng = L.latLng({
				lat: lat,
				lng: lng
			});

			posisi_penduduk.setLatLng(latLng);
			peta_desa.setView(latLng, zoom);
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
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<form id="validasi">
			<div class="col-sm-12 form-horizontal">
				<div class="form-group">
					<label class="col-sm-3 control-label" for="lat">Lat</label>
					<div class="col-sm-9">
						<input type="text" class="form-control number" name="lat" id="lat" value="<?= $penduduk['lat']; ?>" />
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label" for="lat">Lng</label>
					<div class="col-sm-9">
						<input type="text" class="form-control number" name="lng" id="lng" value="<?= $penduduk['lng']; ?>" />
					</div>
				</div>
			</div>
			<button type="reset" class="btn btn-social btn-flat btn-danger btn-sm" data-dismiss="modal"><i class='fa fa-sign-out'></i> Tutup</button>
			<?php if ($edit == 1): ?>
				<?php if ($penduduk['status_dasar'] == 1 || !isset($penduduk['status_dasar'])): ?>
					 <button type="submit" class="btn btn-social btn-flat btn-info btn-sm" id="simpan_penduduk" data-dismiss="modal"><i class='fa fa-check'></i> Simpan</button>
				<?php endif; ?>
			<?php endif; ?>
		</form>
	</div>

<script src="<?= base_url()?>assets/js/validasi.js"></script>
<script src="<?= base_url()?>assets/js/jquery.validate.min.js"></script>
