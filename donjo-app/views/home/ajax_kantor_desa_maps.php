<script>
(function() {
		var infoWindow;
 var mapOptions = {
		<?php if($desa['lat'] != ""){?>
 center: new google.maps.LatLng(<?php echo $desa['lat']?>,<?php echo $desa['lng']?>),
 zoom: <?php echo $desa['zoom']?>,
 mapTypeId: google.maps.MapTypeId.<?php echo strtoupper($desa['map_tipe'])?>
		<?php }else{?>
 center: new google.maps.LatLng(-7.885619783139936,110.39893195996092),
 zoom: 14,
 mapTypeId: google.maps.MapTypeId.ROADMAP
		<?php }?>
		 
 };
 var map = new google.maps.Map(document.getElementById("mapx"),mapOptions);
		
		var marker = new google.maps.Marker({<?php if($desa['lat'] != ""){?>
 		position: new google.maps.LatLng(<?php echo $desa['lat']?>,<?php echo $desa['lng']?>),
		<?php }else{?>
 		position: new google.maps.LatLng(-7.885619783139936,110.39893195996092),
		<?php }?>
 		map: map,
			draggable: true,
 		title:"<?php echo $desa['nama_desa']?>"});
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
			'<img src="<?php echo base_url()?>assets/files/logo/<?php echo $desa['logo']?>" alt="" width="50" height="60"/>' + 
			'<h3><?php echo $desa['nama_desa']?></h3>' +
			'<p>Lokasi Kantor Desa/Kelurahan</p>' +
			'</div>';
		 
		 infoWindow.setContent(content);
		 infoWindow.open(map, marker);
		
		});
		
		$('#showData').click(function(){ 
				document.getElementById('zoom').value = map.getZoom();
				document.getElementById('map_tipe').value = map.getMapTypeId();
		 });
		 
})();
</script>
<style>
#mapx {
 width: 420px;
 height: 320px;
 border: 1px solid #000;
}
</style>
<div id="mapx"></div>
<div class="buttonpane" style="text-align: right; width:400px;position:absolute;bottom:0px;">
	<div class="uibutton-group">
		<button id="showData" class="uibutton confirm" type="button" onclick="$('#window-lok').dialog('close');$('p.kan').show();">Simpan</button>
	</div>
</div>