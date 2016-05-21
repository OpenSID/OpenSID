<script>
(function() {
        var mapOptions = {
		<?if(!empty($lokasi)){?>
		<?if($lokasi['lat'] != ""){?>
          center: new google.maps.LatLng(<?=$lokasi['lat']?>,<?=$lokasi['lng']?>),
          zoom: 14, // from 0 to 23
          mapTypeId: google.maps.MapTypeId.ROADMAP // ROADMAP, TERRAIN, SATELLITE,HYBRID
		  <?}else{
			  if($desa['lat']!=""){?>
			  center: new google.maps.LatLng(<?=$desa['lat']?>,<?=$desa['lng']?>),
			  zoom: <?=$desa['zoom']?>, // from 0 to 23
			  mapTypeId: google.maps.MapTypeId.<?=strtoupper($desa['map_tipe'])?> // ROADMAP, TERRAIN, SATELLITE,HYBRID
			<?}else{?>
			  center: new google.maps.LatLng(-7.885619783139936,110.39893195996092),
			  zoom: 14, // from 0 to 23
			  mapTypeId: google.maps.MapTypeId.ROADMAP // ROADMAP, TERRAIN, SATELLITE,HYBRID
			<?}
		}}else{?>
			  center: new google.maps.LatLng(-7.885619783139936,110.39893195996092),
			  zoom: 14, // from 0 to 23
			  mapTypeId: google.maps.MapTypeId.ROADMAP // ROADMAP, TERRAIN, SATELLITE,HYBRID
		  <?}?>
        }; // end options
        var map = new google.maps.Map(document.getElementById("map"),mapOptions);
		
   // map = new google.maps.Map(document.getElementById('map'), options);
		var marker = new google.maps.Marker({<?if($lokasi['lat'] != ""){?>
      		position: new google.maps.LatLng(<?=$lokasi['lat']?>,<?=$lokasi['lng']?>),

		<?}else if($desa['lat'] != ""){?>
			position: new google.maps.LatLng(<?=$desa['lat']?>,<?=$desa['lng']?>),
		
		<?}else{?>
      		position: new google.maps.LatLng(-7.885619783139936,110.39893195996092),
		
		<?}?>
      		map: map,
			draggable: true, // SET DRAGGABLE TO TRUE
      		title:"<?=$lokasi['nama']?>"});

	// WE USE THE "drag" OR "dragend" EVENTS TO GET COORDS OF MARKER AND WRITE THEM
		google.maps.event.addListener(marker, 'drag', function() {
			document.getElementById('lat').value = marker.getPosition().lat();
			document.getElementById('lng').value = marker.getPosition().lng();
			//document.getElementById('zoom').value = map.getZoom();
			//document.getElementById('map_tipe').value = map.getMapTypeId();
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
    <input type="hidden" name="lat" id="lat" />
    <input type="hidden" name="lng" id="lng" />
 <?/*   <input type="hidden" name="zoom" id="zoom" />
    <input type="hidden" name="map_tipe" id="map_tipe" />*/?>
<div class="buttonpane" style="text-align: right; width:400px;position:absolute;bottom:0px;">
<div class="uibutton-group">
	<button class="uibutton" type="button" onclick="$('#window').dialog('close');">Close</button>
	<button class="uibutton confirm" type="submit">Simpan</button>
</div>
</div>
</form>