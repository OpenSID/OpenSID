<script>
		<?php
			if(!empty($desa['lat'] && !empty($desa['lng']))){
		?>
			var posisi = [<?php echo $desa['lat'].",".$desa['lng']; ?>];
			var zoom = <?php echo $desa['zoom'] ?: 10; ?>;
		<?
			}else{
		?>
			var posisi = [-7.885619783139936,110.39893195996092];
			var zoom = 10;
		<?php
			}
		?>
		
		//Inisialisasi tampilan peta
		var peta_area = L.map('map_area').setView(posisi, zoom);
		L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
			maxZoom: 18,
			attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors',
			id: 'peta_area'
		}).addTo(peta_area);
		
<?php
	if(!empty($area['path'])){
?>

//Poligon wilayah desa yang tersimpan
var area_polygon = <?php echo $area['path']; ?>;

//Titik awal dan titik akhir poligon harus sama
area_polygon[0].push(area_polygon[0][0]);

//Tampilkan poligon desa untuk diedit		
var area = L.polygon(area_polygon).addTo(peta_area);

//Event untuk mengecek perubahan poligon
area.on('pm:edit', function(e){
	document.getElementById('path').value = getLatLong('Poly', e.target).toString();
})

//Fokuskan peta ke poligon
peta_area.fitBounds(area.getBounds());

<?php
	}
?>
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
		peta_area.on('pm:create', function(e) {
			//Ambil list poligon yang ada
			var keys = Object.keys(peta_area._layers);
			//Tambahkan event edit ke poligon yang telah dibuat
			peta_area._layers[keys[2]].on('pm:edit', function(f){
				document.getElementById('path').value = getLatLong(e.shape, e.layer).toString();
			})
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
#map_area {
  width: 420px;
  height: 320px;
  border: 1px solid #000;
}
</style>
	<div id="map_area"></div>
	<form action="<?php echo $form_action?>" method="post">
	<input type="hidden" id="path" name="path" value="<?php echo $area['path']?>">
	<div class="buttonpane" style="text-align: right; width:400px;position:absolute;bottom:0px;">
	<div class="uibutton-group">
		<button class="uibutton" type="button" onclick="$('#window').dialog('close');"><span class="fa fa-times"></span> Tutup</button>
		<button class="uibutton confirm" id="showData" type="submit"><span class="fa fa-save"></span> Simpan</button>
	</div>
	</div>
</form>