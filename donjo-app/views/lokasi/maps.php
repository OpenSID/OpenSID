<script>
(function() {
//Jika posisi peta_lokasi belum ada, maka gunakan peta_lokasi default
<?php if(!empty($lokasi['lat']) && !empty($lokasi['lng'])): ?>
    var posisi = [<?=$lokasi['lat'].",".$lokasi['lng']?>];
    var zoom = <?=$desa['zoom'] ?: 10?>;
<?php else: ?>
    var posisi = [-7.885619783139936, 110.39893195996092];
    var zoom = 10;
<?php endif; ?>
    //Inisialisasi tampilan peta
    var peta_lokasi = L.map('map_lokasi').setView(posisi, zoom);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      	maxZoom: 18,
      	attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors',
      	id: 'map_lokasi'
    }).addTo(peta_lokasi);
    var lokasi_marker = L.marker(posisi, {draggable: true}).addTo(peta_lokasi);
    lokasi_marker.on('dragend', function(e){
      	document.getElementById('lat').value = e.target._latlng.lat;
		document.getElementById('lng').value = e.target._latlng.lng;
    })
})();
</script>
<style>
#map_lokasi {
  width: 420px;
  height: 320px;
  border: 1px solid #000;
}
</style>
<form action="<?=$form_action?>" method="post" id="validasi">
    <div id="map_lokasi"></div>
    <input type="hidden" name="lat" id="lat" value="<?=$lokasi['lat']?>"/>
    <input type="hidden" name="lng" id="lng" value="<?=$lokasi['lng']?>" />
<div class="buttonpane" style="text-align: right; width:400px;position:absolute;bottom:0px;">
    <div class="uibutton-group">
        <button class="uibutton" type="button" onclick="$('#window').dialog('close');"><span class="fa fa-times"></span> Tutup</button>
        <button class="uibutton confirm" type="submit"><span class="fa fa-save"></span> Simpan</button>
    </div>
</div>
</form>
