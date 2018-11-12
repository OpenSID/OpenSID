<script>
	<?php if (!empty($desa['lat'] && !empty($desa['lng']))): ?>
		var posisi = [<?= $desa['lat'].",".$desa['lng']; ?>];
		var zoom = <?= $desa['zoom'] ?: 10; ?>;
	<?php else: ?>
			var posisi = [-7.885619783139936,110.39893195996092];
			var zoom = 10;
	<?php endif; ?>

		//Inisialisasi tampilan peta
		var peta_area = L.map('map_area').setView(posisi, zoom);
		L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
		{
			maxZoom: 18,
			attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors',
			id: 'peta_area'
		}).addTo(peta_area);

	<?php if (!empty($area['path'])): ?>

		//Poligon wilayah desa yang tersimpan
		var area_polygon = <?= $area['path']; ?>;

		//Titik awal dan titik akhir poligon harus sama
		area_polygon[0].push(area_polygon[0][0]);

		//Tampilkan poligon desa untuk diedit
		var area = L.polygon(area_polygon).addTo(peta_area);

		//Event untuk mengecek perubahan poligon
		area.on('pm:edit', function(e)
		{
			document.getElementById('path').value = getLatLong('Poly', e.target).toString();
		})

		//Fokuskan peta ke poligon
		peta_area.fitBounds(area.getBounds());
		setTimeout(function() {peta_area.invalidateSize();peta_area.fitBounds(area.getBounds());}, 500);
	<?php endif; ?>
	//Tombol yang akan dimunculkan dipeta
	var options = {
		position: 'topright', // toolbar position, options are 'topleft', 'topright', 'bottomleft', 'bottomright'
		drawMarker: false, // adds button to draw markers
		drawPolyline: false, // adds button to draw a polyline
		drawRectangle: false, // adds button to draw a rectangle
		drawPolygon: true, // adds button to draw a polygon
		drawCircle: false, // adds button to draw a cricle
		cutPolygon: false, // adds button to cut a hole in a polygon
		editMode: true, // adds button to toggle edit mode for all layers
		removalMode: true, // adds a button to remove layers
	};

	//Menambahkan toolbar ke peta
	peta_area.pm.addControls(options);

	//Event untuk menangkap polygon yang dibuat
	peta_area.on('pm:create', function(e)
	{
		//Ambil list poligon yang ada
		var keys = Object.keys(peta_area._layers);
		//Tambahkan event edit ke poligon yang telah dibuat
		peta_area._layers[keys[2]].on('pm:edit', function(f)
		{
			document.getElementById('path').value = getLatLong(e.shape, e.layer).toString();
		})
		document.getElementById('path').value = getLatLong(e.shape, e.layer).toString();
	});

	function getLatLong(x, y)
	{
		var hasil;
		if (x == 'Rectangle' || x == 'Line' || x == 'Poly')
		{
			hasil = JSON.stringify(y._latlngs);
		}
		else
		{
			hasil = JSON.stringify(y._latlng);
		}

		hasil = hasil.replace(/\}/g, ']').replace(/(\{)/g, '[').replace(/(\"lat\"\:|\"lng\"\:)/g, '');
		return hasil
	}
</script>
<style>
  #map_area
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
				<div id="map_area"></div>
				<input type="hidden" id="path" name="path" value="<?= $area['path']?>">
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<button type="reset" class="btn btn-social btn-flat btn-danger btn-sm" data-dismiss="modal"><i class='fa fa-sign-out'></i> Tutup</button>
		<button type="submit" class="btn btn-social btn-flat btn-info btn-sm"><i class='fa fa-check'></i> Simpan</button>
	</div>
</form>
