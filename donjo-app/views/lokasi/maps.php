<script>
(function() {
        var mapOptions = {
		<?php  if(!empty($lokasi)){?>
		<?php  if($lokasi['lat'] != ""){?>
          center: new google.maps.LatLng(<?php  echo $lokasi['lat']?>,<?php  echo $lokasi['lng']?>),
          zoom: 14, // from 0 to 23
          mapTypeId: google.maps.MapTypeId.ROADMAP // ROADMAP, TERRAIN, SATELLITE,HYBRID
		  <?php  }else{
			  if($desa['lat']!=""){?>
			  center: new google.maps.LatLng(<?php  echo $desa['lat']?>,<?php  echo $desa['lng']?>),
			  zoom: <?php  echo $desa['zoom']?>, // from 0 to 23
			  mapTypeId: google.maps.MapTypeId.<?php  echo strtoupper($desa['map_tipe'])?> // ROADMAP, TERRAIN, SATELLITE,HYBRID
			<?php  }else{?>
			  center: new google.maps.LatLng(-7.885619783139936,110.39893195996092),
			  zoom: 14, // from 0 to 23
			  mapTypeId: google.maps.MapTypeId.ROADMAP // ROADMAP, TERRAIN, SATELLITE,HYBRID
			<?php  }
		}}else{?>
			  center: new google.maps.LatLng(-7.885619783139936,110.39893195996092),
			  zoom: 14, // from 0 to 23
			  mapTypeId: google.maps.MapTypeId.ROADMAP // ROADMAP, TERRAIN, SATELLITE,HYBRID
		  <?php  }?>
        }; // end options
        var map = new google.maps.Map(document.getElementById("map"),mapOptions);
		
   // map = new google.maps.Map(document.getElementById('map'), options);
		var marker = new google.maps.Marker({<?php  if($lokasi['lat'] != ""){?>
      		position: new google.maps.LatLng(<?php  echo $lokasi['lat']?>,<?php  echo $lokasi['lng']?>),

		<?php  }else if($desa['lat'] != ""){?>
			position: new google.maps.LatLng(<?php  echo $desa['lat']?>,<?php  echo $desa['lng']?>),
		
		<?php  }else{?>
      		position: new google.maps.LatLng(-7.885619783139936,110.39893195996092),
		
		<?php  }?>
      		map: map,
			draggable: true, // SET DRAGGABLE TO TRUE
      		title:"<?php  echo $lokasi['nama']?>"});

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
<form action="<?php  echo $form_action?>" method="post" id="validasi">
<div id="map"></div>
    <input type="hidden" name="lat" id="lat" />
    <input type="hidden" name="lng" id="lng" />
 <?php  /*   <input type="hidden" name="zoom" id="zoom" />
    <input type="hidden" name="map_tipe" id="map_tipe" />*/?>
<div class="buttonpane" style="text-align: right; width:400px;position:absolute;bottom:0px;">
<div class="uibutton-group">
	<button class="uibutton" type="button" onclick="$('#window').dialog('close');"><span class="fa fa-times"></span> Tutup</button>
	<button class="uibutton confirm" type="submit"><span class="fa fa-save"></span> Simpan</button>
</div>
</div>
</form>