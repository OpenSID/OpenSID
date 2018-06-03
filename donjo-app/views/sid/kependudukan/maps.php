<script>
$(document).ready(function(){
    $('#simpan_penduduk').click(function(){
        var lat = $('#lat').val();
        var lng = $('#lng').val();
        $.ajax({
            type: "POST",
            url: "<?=$form_action?>",
            dataType: 'json',
            data: {lat: lat, lng: lng},
        });
        $(this).closest('.ui-dialog-content').dialog('close');
    });
});
(function() {
	<?php
		if(!empty($penduduk['lat'])){
		?>
			var posisi = [<?php echo $penduduk['lat'].",".$penduduk['lng']; ?>];
      		var zoom = <?php echo $desa['zoom'] ?: 10; ?>;
		<?
			}else{
		?>
			var posisi = [-7.885619783139936, 110.39893195996092];
      		var zoom = 10;
		<?php
			}
		?>
    //Inisialisasi tampilan peta
    var peta_penduduk = L.map('map').setView(posisi, zoom);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      maxZoom: 18,
      attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors',
      id: 'mapbox.streets'
    }).addTo(peta_penduduk);
    var posisi_penduduk = L.marker(posisi, {draggable: true}).addTo(peta_penduduk);
    posisi_penduduk.on('dragend', function(e){
      	document.getElementById('lat').value = e.target._latlng.lat;
		document.getElementById('lng').value = e.target._latlng.lng;
    })

})();
</script>
<style>
#map {
  width: 420px;
  height: 320px;
  border: 1px solid #000;
}
</style>
<div id="map"></div>
<input type="hidden" name="lat" id="lat" value="<?php echo $penduduk['lat']; ?>" />
<input type="hidden" name="lng" id="lng" value="<?php echo $penduduk['lng']; ?>"/>
<div class="buttonpane" style="text-align: right; width:420px;position:absolute;bottom:0px;">
<div class="uibutton-group">
    <button class="uibutton reset" type="button" onclick="$(this).closest('.ui-dialog-content').dialog('close');">Batal</button>
	<button class="uibutton confirm" type="submit" id="simpan_penduduk"><span class="fa fa-save"></span> Simpan</button>
</div>
</div>
