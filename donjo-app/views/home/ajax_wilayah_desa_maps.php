<script type="text/javascript" src="<?php echo base_url()?>assets/js/polygon.min.js"></script>
<script>
	function PolygonCreator(map){
		this.map=map;this.pen=new Pen(this.map);
		var thisOjb=this;
		this.event=google.maps.event.addListener(thisOjb.map,'click',function(event){thisOjb.pen.draw(event.latLng);});
		
		this.showData=function(){return this.pen.getData();}
		
		this.showColor=function(){return this.pen.getColor();}
		
		this.destroy=function(){
			this.pen.deleteMis();
			if(null!=this.pen.polygon){
				this.pen.polygon.remove();
			}
		google.maps.event.removeListener(this.event);
		}
	}
	
	$(function(){

    var options = {
		<?php if($desa['lat']!=""){?>
		  center: new google.maps.LatLng(<?php echo $desa['lat']?>,<?php echo $desa['lng']?>),
		  zoom: <?php echo $desa['zoom']?>,
		  mapTypeId: google.maps.MapTypeId.<?php echo strtoupper($desa['map_tipe'])?>
		<?php }else{?>
		  center: new google.maps.LatLng(-7.885619783139936,110.39893195996092),
		  zoom: 14,
		  mapTypeId: google.maps.MapTypeId.ROADMAP
		<?php }?>
    };
    var map = new google.maps.Map(document.getElementById('map'), options);
    	 
<?php 
			$path = preg_split("/\;/", $desa['path']);
			echo "var path = [";foreach($path AS $p){if($p!=""){echo"new google.maps.LatLng".$p.",";}}echo"];";?>
			
			var desa = new google.maps.Polygon({
			  paths: path,
			  map: map,
			  strokeColor: '#ff0000',
			  strokeOpacity: 0.6,
			  strokeWeight: 1,
			  fillColor: '#ff0000',
			  fillOpacity: 0.35
			});
			
			google.maps.event.addListener(desa, 'mouseover', function(e) {
			  desa.setOptions({
				fillColor: '#0000ff',
				strokeColor: '#0000ff'
			  });
			});

			google.maps.event.addListener(desa, 'mouseout', function(e) {
			  desa.setOptions({
				fillColor: '#ff0000',
				strokeColor: '#ff0000'
			  });
			});
			
		var creator = new PolygonCreator(map);
		 $('#reset').click(function(){ 
		 		creator.destroy();
		 		creator=null;
		 		
		 		creator=new PolygonCreator(map);
				document.getElementById('dataPanel').value = creator.showData();
		 });		 
		 
		$('#showData').click(function(){ 
				document.getElementById('zoom').value = map.getZoom();
				document.getElementById('map_tipe').value = map.getMapTypeId();
		 		$('#dataPanel').empty();
		 		if(null==creator.showData()){
					this.form.submit();
		 		}else{
					document.getElementById('dataPanel').value = creator.showData();
					this.form.submit();
		 		}
		 });
		 
	});	
	
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
    <input type="hidden" name="lat" id="lat" value="<?php echo $desa['lat']?>"/>
    <input type="hidden" name="lng" id="lng" value="<?php echo $desa['lng']?>"/>
    <input type="hidden" name="zoom" id="zoom" value="<?php echo $desa['zoom']?>"/>
    <input type="hidden" name="map_tipe" id="map_tipe"  value="<?php echo $desa['map_tipe']?>"/>
	<input type="hidden" id="dataPanel" name="path"  value="<?php echo $desa['path']?>">
	<div class="buttonpane" style="text-align: right; width:420px;position:absolute;bottom:0px;">
	<div class="uibutton-group">
		<button class="uibutton" type="reset"><span class="fa fa-refresh"></span> Bersihkan</button>
		<button class="uibutton confirm" id="showData" type="submit"><span class="fa fa-save"></span> Simpan</button>
	</div>
	</div>
</form>
