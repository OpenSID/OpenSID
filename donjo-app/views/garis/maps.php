<script type="text/javascript" src="<?= base_url()?>assets/js/polygon.min.js"></script>
<script>
	function PolygonCreator(map)
	{
		this.map=map;this.pen=new Pen(this.map);
		var thisOjb=this;
		var jalur = "";
		this.event=google.maps.event.addListener(thisOjb.map,'click',function(event){
			thisOjb.pen.draw(event.latLng);jalur+=event.latLng;jalur+=";";
		});

		this.showData=function()
		{
			return this.pen.getData();
		}

		this.showColor=function()
		{
			return this.pen.getColor();
		}
		this.showJalur=function()
		{
			return jalur;
		}

		this.destroy=function()
		{
			this.pen.deleteMis();
			if (null!=this.pen.polygon)
			{
				this.pen.polygon.remove();
			}
		google.maps.event.removeListener(this.event);
		}
	}

	$(function()
	{
		var options =
		{
			<?php if ($desa['lat']!=""):?>
				center: new google.maps.LatLng(<?= $desa['lat']?>,<?= $desa['lng']?>),
				zoom: <?= $desa['zoom']?>,
				mapTypeId: google.maps.MapTypeId.<?= strtoupper($desa['map_tipe'])?>
			<?php else:?>
				center: new google.maps.LatLng(-7.885619783139936,110.39893195996092),
				zoom: 14,
				mapTypeId: google.maps.MapTypeId.ROADMAP
			<?php endif;?>
    };
    var map = new google.maps.Map(document.getElementById('map'), options);


	<?php
		$path = preg_split("/\;/", $garis['path']);?>
		var path = [<?php foreach ($path AS $p): if ($p!=""):?> new google.maps.LatLng<?=$p?>, <?php endif; endforeach;?>]

    // Creating the polyline object
    var polyline = new google.maps.Polyline(
		{
      path: path,
      strokeColor: "#00ff00",
      strokeOpacity: 0.6,
      strokeWeight: 5
    });

    // Adding the polyline to the map
    polyline.setMap(map);


	<?php /*
		$path_desa = preg_split("/\;/", $desa['path']);
		echo "var path_desa = [";foreach ($path_desa AS $p){if ($p!=""){echo"new google.maps.LatLng".$p.",";}}echo"];";?>
		var desa = new google.maps.Polygon({
		  paths: path_desa,
		  map: map,
		  strokeColor: '#11ddff',
		  strokeOpacity: 0.6,
		  strokeWeight: 1,
		  fillColor: '#11ddff',
		  fillOpacity: 0.25
		});
	*/?>
		google.maps.event.addListener(polyline, 'mouseover', function(e)
		{
			polyline.setOptions(
			{
				fillColor: '#0000ff',
				strokeColor: '#0000ff'
			});
		});

		google.maps.event.addListener(polyline, 'mouseout', function(e) {
		  polyline.setOptions(
			{
				fillColor: '#11ff00',
				strokeColor: '#11ff00'
		 	});
		});

		var creator = new PolygonCreator(map);
		 $('#reset').click(function()
		 {
		 		creator.destroy();
		 		creator=null;

		 		creator=new PolygonCreator(map);
				document.getElementById('dataPanel').value = creator.showData();
		 });

		$('#showData').click(function()
		{
		 		$('#dataPanel').empty();
		 		if (null==creator.showJalur()):
					this.form.submit();
		 		else:
					document.getElementById('dataPanel').value = creator.showJalur();
					this.form.submit();
		 		endif;
		 });

	});
</script>
<style>
	#map
	{
		z-index: 1;
		width: 100%;
		height: 320px;
		border: 1px solid #000;
	}
</style>
<form action="<?= $form_action?>" method="post" id="validasi">
	<div class='modal-body'>
		<div class="row">
			<div class="col-sm-12">
				<div id="map"></div>
				<input type="hidden" id="dataPanel" name="path"  value="<?= $garis['path']?>">
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<button type="reset" class="btn btn-social btn-flat btn-danger btn-sm" data-dismiss="modal"><i class='fa fa-sign-out'></i> Tutup</button>
		<button type="submit" class="btn btn-social btn-flat btn-info btn-sm"><i class='fa fa-check'></i> Simpan</button>
	</div>
</form>
