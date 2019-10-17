<script>

   var infoWindow;
	window.onload = function()
	{
         
         //Jika posisi kantor rt belum ada, maka posisi peta akan menampilkan seluruh Indonesia
	<?php if (!empty($rt['lat']) && !empty($rt['lng'])): ?>
    var posisi = [<?=$rt['lat'].",".$rt['lng']?>];
    var zoom = <?=$rt['zoom'] ?: 10?>;
	<?php else: ?>
    var posisi = [-1.0546279422758742,116.71875000000001];
    var zoom = 4;
	<?php endif; ?>
	//Menggunakan https://github.com/codeofsumit/leaflet.pm
	//Inisialisasi tampilan peta
  var peta_rt = L.map('map').setView(posisi, zoom);
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
	{
    maxZoom: 18,
    attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors',
    id: 'mapbox.streets'
  }).addTo(peta_rt);

  //Tombol yang akan dimunculkan dipeta
  var options =
	{
    position: 'topright', // toolbar position, options are 'topleft', 'topright', 'bottomleft', 'bottomright'
    drawMarker: false, // adds button to draw markers
    drawCircleMarker: false, // adds button to draw markers
    drawPolyline: false, // adds button to draw a polyline
    drawRectangle: false, // adds button to draw a rectangle
    drawPolygon: true, // adds button to draw a polygon
    drawCircle: false, // adds button to draw a cricle
    cutPolygon: false, // adds button to cut a hole in a polygon
    editMode: true, // adds button to toggle edit mode for all layers
    removalMode: true, // adds a button to remove layers
  };

  //Menambahkan toolbar ke peta
  peta_rt.pm.addControls(options);

  //Menambahkan Peta wilayah
  peta_rt.on('pm:create', function(e)
	{
    var type = e.layerType;
    var layer = e.layer;
    var latLngs;

    if (type === 'circle') {
       latLngs = layer.getLatLng();
    }
    else
       latLngs = layer.getLatLngs();

    var p = latLngs;
    var polygon = L.polygon(p, { color: '#A9AAAA', weight: 4, opacity: 1 }).addTo(peta_rt);
    
    polygon.on('pm:edit', function(e)
	{
        document.getElementById('path').value = getLatLong('Poly', e.target).toString();
    })

   });

  //Menghapus Peta wilayah
  peta_rt.on('pm:globalremovalmodetoggled', function(e)
	{
        document.getElementById('path').value = '';
  })

    //Merubah Peta wilayah yg sudah ada
    <?php if (!empty($rt['path'])): ?>
    var daerah_rt = <?=$rt['path']?>;
    var poligon_rt = L.polygon(daerah_rt).addTo(peta_rt);
    poligon_rt.on('pm:edit', function(e)
		{
        document.getElementById('path').value = getLatLong('Poly', e.target).toString();
    })
    setTimeout(function() {peta_rt.invalidateSize();peta_rt.fitBounds(poligon_rt.getBounds());}, 500);
    <?php endif; ?>

   //Fungsi
  function getLatLong(x, y)
	{
    var hasil;
    if (x == 'Rectangle' || x == 'Line' || x == 'Poly')
		{
      hasil = JSON.stringify(y._latlngs);
    }
		else
		{
      hasil = JSON.stringify(y._latlng);
    }
    hasil = hasil.replace(/\}/g, ']').replace(/(\{)/g, '[').replace(/(\"lat\"\:|\"lng\"\:)/g, '');
    return hasil;
  }
  
        }; //EOF window.onload
</script>
<style>
	#map
	{
		width:100%;
		height:72vh
	}

</style>
<!-- Menampilkan OpenStreetMap -->
<div class="content-wrapper">
	<section class="content-header">
		<h1>Peta Wilayah RT <?= $rt['rt']?> RW <?= $rt['rw']?> <?= ucwords($this->setting->sebutan_dusun." ".$rt['dusun'])?> <?= ucwords($this->setting->sebutan_desa." ".$desa['nama_desa'])?></h1>
		<ol class="breadcrumb">
                        
                        <form id="validasi" action="<?= $form_action?>" method="POST" enctype="multipart/form-data" class="form-horizontal">
			<button type="submit" class="btn btn-social btn-flat btn-info btn-sm" id="simpan_wilayah"><i class='fa fa-check'></i> Simpan</button> 
                         
		</ol>
	</section>
	<section class="content" id="maincontent">
		<div class="row">
			<div class="col-md-12">
                                <div class="box box-info">
					
					<div class="box-body">
						<div class="row">
							<div class="col-sm-12">
										
				       <div id="map">
                                       <input type="hidden" id="path" name="path" value="<?= $rt['path']?>">
                                       <input type="hidden" name="id" id="id"  value="<?= $rt['id']?>"/>
            	                       </div>
                          </form>
							</div>
                                          	</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>

<script>

  $(document).ready(function(){
      $('#simpan_kantor').click(function(){
      if (!$('#validasi').valid()) return;

      var id = $('#id').val();
      var path = $('#path').val();
      $.ajax(
			{
        type: "POST",
        url: "<?=$form_action?>",
        dataType: 'json',
        data: {path: path, id: id},
			});
		});
	});
</script>
