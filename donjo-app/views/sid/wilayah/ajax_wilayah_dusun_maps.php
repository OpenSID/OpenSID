<script>

   var infoWindow;
	window.onload = function()
	{
         
         //Jika posisi kantor dusun belum ada, maka posisi peta akan menampilkan seluruh Indonesia
	<?php if (!empty($dusun['lat']) && !empty($dusun['lng'])): ?>
    var posisi = [<?=$dusun['lat'].",".$dusun['lng']?>];
    var zoom = <?=$dusun['zoom'] ?: 10?>;
	<?php else: ?>
    var posisi = [<?=$desa['lat'].",".$desa['lng']?>];
    var zoom = <?=$desa['zoom'] ?: 10?>;
	<?php endif; ?>
	//Menggunakan https://github.com/codeofsumit/leaflet.pm
	//Inisialisasi tampilan peta
  var peta_dusun = L.map('map').setView(posisi, zoom);
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
	{
    maxZoom: 18,
    attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors',
    id: 'mapbox.streets'
  }).addTo(peta_dusun);

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
  peta_dusun.pm.addControls(options);

  //Menambahkan Peta wilayah
  peta_dusun.on('pm:create', function(e)
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
    var polygon = L.polygon(p, { color: '#A9AAAA', weight: 4, opacity: 1 }).addTo(peta_dusun);
    
    polygon.on('pm:edit', function(e)
	{
        document.getElementById('path').value = getLatLong('Poly', e.target).toString();
    })

   });

  //Menghapus Peta wilayah
  peta_dusun.on('pm:globalremovalmodetoggled', function(e)
	{
        document.getElementById('path').value = '';
  })

    //Merubah Peta wilayah yg sudah ada
    <?php if (!empty($dusun['path'])): ?>
    var daerah_dusun = <?=$dusun['path']?>;
    var poligon_dusun = L.polygon(daerah_dusun).addTo(peta_dusun);
    poligon_dusun.on('pm:edit', function(e)
		{
        document.getElementById('path').value = getLatLong('Poly', e.target).toString();
    })
    setTimeout(function() {peta_dusun.invalidateSize();peta_dusun.fitBounds(poligon_dusun.getBounds());}, 500);
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
		height:65vh
	}

</style>
<!-- Menampilkan OpenStreetMap -->
<div class="content-wrapper">
	<section class="content-header">
		<h1>Peta Wilayah <?= ucwords($this->setting->sebutan_dusun." ".$dusun['dusun'])?> <?= ucwords($this->setting->sebutan_desa." ".$desa['nama_desa'])?></h1>
		<ol class="breadcrumb">
                        <li><a href="<?= site_url('sid_core')?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?= site_url("sid_core/form/$dusun[id]")?>"> Pengelolaan Data <?= ucwords($this->setting->sebutan_dusun)?></a></li>   
			<li class="active">Peta Wilayah <?= ucwords($this->setting->sebutan_dusun)?></li>                    
		</ol>
	</section>
	<section class="content" id="maincontent">
		<div class="row">
			<div class="col-md-12">
                                <div class="box box-info">
				<form id="validasi" action="<?= $form_action?>" method="POST" enctype="multipart/form-data" class="form-horizontal">
	
					<div class="box-body">
						<div class="row">
							<div class="col-sm-12">
										
				                              <div id="map">
                                                              <input type="hidden" id="path" name="path" value="<?= $dusun['path']?>">
                                                              <input type="hidden" name="id" id="id"  value="<?= $dusun['id']?>"/>
            	                                              </div>

							</div>
                                          	</div>
					</div>

        <div class='box-footer'>
        <div class='col-xs-12'>
          <button type='reset' class='btn btn-social btn-flat btn-danger btn-sm invisible' ><i class='fa fa-times'></i> Batal</button>
          <button type='submit' class='btn btn-social btn-flat btn-info btn-sm pull-right'><i class='fa fa-check'></i> Simpan</button>
        </div>
      </div>
                              </form>
                                                        
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
