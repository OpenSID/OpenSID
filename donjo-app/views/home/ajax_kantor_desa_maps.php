<script>
(function() {
		var posisi = [<?php echo $desa['lat'].",".$desa['lng']; ?>];
    var zoom = <?php echo $desa['zoom']; ?>;

    var lokasi_kantor = L.map('mapx').setView(posisi, zoom);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      maxZoom: 18,
      attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors',
      id: 'mapbox.streets'
    }).addTo(lokasi_kantor);
    var kantor_desa = L.marker(posisi, {draggable: true}).addTo(lokasi_kantor);
    kantor_desa.on('dragend', function(e){
      document.getElementById('lat').value = e.target._latlng.lat;
		  document.getElementById('lng').value = e.target._latlng.lng;
		  document.getElementById('map_tipe').value = "HYBRID"
    })
    lokasi_kantor.on('zoomstart zoomend', function(e){
      document.getElementById('zoom').value = e.target._zoom
    })
})();
</script>
<style>
#mapx {
  width: 420px;
  height: 320px;
  border: 1px solid #000;
}
</style>
<form action="<?php echo $form_action?>" method="post" id="validasi">
<div id="mapx"></div>
    <input type="hidden" name="lat" id="lat" value="<?php echo $desa['lat']?>"/>
    <input type="hidden" name="lng" id="lng"  value="<?php echo $desa['lng']?>"/>
    <input type="hidden" name="zoom" id="zoom"  value="<?php echo $desa['zoom']?>"/>
    <input type="hidden" name="map_tipe" id="map_tipe"  value="<?php echo $desa['map_tipe']?>"/>
<div class="buttonpane" style="text-align: right; width:400px;position:absolute;bottom:0px;">
<div class="uibutton-group">
		<button class="uibutton confirm" id="showData" type="submit"><span class="fa fa-save"></span> Simpan</button>
</div>
</div>
</form>
