<script>
(function() {
		var infoWindow;
        var mapOptions = {
		<?if($desa['lat'] != ""){?>
          center: new google.maps.LatLng(<?=$desa['lat']?>,<?=$desa['lng']?>),
          zoom: <?=$desa['zoom']?>,
          mapTypeId: google.maps.MapTypeId.<?=strtoupper($desa['map_tipe'])?>
		<?}else{?>
          center: new google.maps.LatLng(-7.885619783139936,110.39893195996092),
          zoom: 14,
          mapTypeId: google.maps.MapTypeId.ROADMAP
		<?}?>
		  
        };
        var map = new google.maps.Map(document.getElementById("map"),mapOptions);
		
		var marker = new google.maps.Marker({<?if($desa['lat'] != ""){?>
      		position: new google.maps.LatLng(<?=$desa['lat']?>,<?=$desa['lng']?>),
		<?}else{?>
      		position: new google.maps.LatLng(-7.885619783139936,110.39893195996092),
		<?}?>
      		map: map,
			draggable: true,
      		title:"<?=$desa['nama_desa']?>"});

		google.maps.event.addListener(marker, 'drag', function() {
			document.getElementById('lat').value = marker.getPosition().lat();
			document.getElementById('lng').value = marker.getPosition().lng();
			document.getElementById('zoom').value = map.getZoom();
			document.getElementById('map_tipe').value = map.getMapTypeId();
        }); 
			
		google.maps.event.addListener(marker, 'click', function() {
		  if (!infoWindow) {
			infoWindow = new google.maps.InfoWindow();
		  }
		  var content = '<div id="info">' +
			'<img src="<?=base_url()?>assets/images/logo/<?=$desa['logo']?>" alt="" width="50" height="60"/>' + 
			'<h3><?=$desa['nama_desa']?></h3>' +
			'<p>Sedangkan aku sedang bingung apa</p><p>yang harus ku lakukan</p>' +
			'<p><a href="http://www.svennerberg.com">Info lebih lanjut.</a></p>' +
			'</div>';
		  
		  infoWindow.setContent(content);
		  
		  infoWindow.open(map, marker);
		
		});
		
		$('#showData').click(function(){ 
				document.getElementById('zoom').value = map.getZoom();
				document.getElementById('map_tipe').value = map.getMapTypeId();
				this.form.submit();
		 });
		 
})();
</script>
<style>
#map {
  width: 420px;
  height: 320px;
  border: 1px solid #000;
}
</style>
<form action="<?=$form_action?>" method="post" id="validasi">
<div id="map"></div>
    <input type="hidden" name="lat" id="lat" value="<?=$desa['lat']?>"/>
    <input type="hidden" name="lng" id="lng"  value="<?=$desa['lng']?>"/>
    <input type="hidden" name="zoom" id="zoom"  value="<?=$desa['zoom']?>"/>
    <input type="hidden" name="map_tipe" id="map_tipe"  value="<?=$desa['map_tipe']?>"/>
<div class="buttonpane" style="text-align: right; width:400px;position:absolute;bottom:0px;">
<div class="uibutton-group">
	<button class="uibutton" type="button" onclick="$('#window').dialog('close');">Close</button>
		<input  class="uibutton confirm" id="showData"  value="Simpan" type="button"/>
</div>
</div>
</form>