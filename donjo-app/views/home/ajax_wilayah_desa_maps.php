<script>
		var posisi = [<?php echo $desa['lat'].",".$desa['lng']; ?>];
		var zoom = <?php echo $desa['zoom']; ?>;

		var peta_desa = L.map('map').setView(posisi, zoom);
		L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
			maxZoom: 18,
			attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors',
			id: 'mapbox.streets'
		}).addTo(peta_desa);
		
<?php //if(!empty($desa['path'])){
?>

// //Poligon wilayah desa yang tersimpan
// var daerah_desa = <?php echo $desa['path']; ?>;

// //Titik awal dan titik akhir poligon harus sama
// daerah_desa[0].push(daerah_desa[0][0]);

// //Tampilkan poligon desa untuk diedit		
// var poligon_desa = L.polygon(daerah_desa).addTo(peta_desa);

// //Fokuskan peta ke poligon
// peta_desa.fitBounds(poligon_desa.getBounds());

// <?php
// }?>

		var options = {
			position: 'topright', // toolbar position, options are 'topleft', 'topright', 'bottomleft', 'bottomright'
			drawMarker: false, // adds button to draw markers
			drawPolyline: false, // adds button to draw a polyline
			drawRectangle: false, // adds button to draw a rectangle
			drawPolygon: true, // adds button to draw a polygon
			drawCircle: false, // adds button to draw a cricle
			cutPolygon: true, // adds button to cut a hole in a polygon
			editMode: true, // adds button to toggle edit mode for all layers
			removalMode: true, // adds a button to remove layers
		};

		// add leaflet.pm controls to the map
		peta_desa.pm.addControls(options);

		peta_desa.on('pm:create', function(e) {
    	document.getElementById('path').value = getLatLong(e.shape, e.layer).toString();
		});

		function getLatLong(x, y) {
			var hasil;
			if (x == 'Rectangle' || x == 'Line' || x == 'Poly') {
				hasil = JSON.stringify(y._latlngs);
			} else {
				hasil = JSON.stringify(y._latlng);
			}
			hasil = hasil.replace(/\}/g, ']').replace(/(\{)/g, '[').replace(/(\"lat\"\:|\"lng\"\:)/g, '');
			return hasil
		}

</script>
<style>
#map {
  width: 420px;
  height: 320px;
  border: 1px solid #000;
}
</style>
	<div id="map"></div>
	<form action="<?php echo $form_action?>" method="post">
	<input type="hidden" id="path" name="path" >
	<div class="buttonpane" style="text-align: right; width:420px;position:absolute;bottom:0px;">
	<div class="uibutton-group">
		<button class="uibutton confirm" id="showData" type="submit"><span class="fa fa-save"></span> Simpan</button>
	</div>
	</div>
</form>
