<script>

var infoWindow;
window.onload = function()
{
 //Jika posisi kantor rt belum ada, maka posisi peta akan menampilkan seluruh Indonesia
	<?php if (!empty($rt['lat']) && !empty($rt['lng'])): ?>
    var posisi = [<?=$rt['lat'].",".$rt['lng']?>];
    var zoom = <?=$rt['zoom'] ?: 18?>;
	<?php else: ?>
    var posisi = [<?=$dusun_rt['lat'].",".$dusun_rt['lng']?>];
    var zoom = <?=$dusun_rt['zoom'] ?: 18?>;
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

  //Tombol uplad import file GPX
  var style = {
    color: 'red',
    opacity: 1.0,
    fillOpacity: 1.0,
    weight: 4,
    clickable: false
  };

  L.Control.FileLayerLoad.LABEL = '<img class="icon" src="<?= base_url()?>assets/images/folder.svg" alt="file icon"/>';
  control = L.Control.fileLayerLoad({
    fitBounds: true,
    layerOptions: {
      style: style,
      pointToLayer: function (data, latlng) {
        return L.circleMarker(
          latlng,
          { style: style }
        );
      }
    }
  });
  control.addTo(peta_rt);
  control.loader.on('data:loaded', function (e) {
    var layer = e.layer;
    console.log(layer);
  });

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

  //Titik awal dan titik akhir poligon harus sama
  daerah_rt[0].push(daerah_rt[0][0]);

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
		height:65vh
	}
  .icon {
    max-width: 70%;
    max-height: 70%;
    margin: 4px;
  }

</style>
<!-- Menampilkan OpenStreetMap -->
<div class="content-wrapper">
	<section class="content-header">
		<h1>Peta Wilayah RT <?= $rt['rt']?> RW <?= $rt['rw']?> <?= ucwords($this->setting->sebutan_dusun." ".$rt['dusun'])?></h1>
		<ol class="breadcrumb">
      <li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?= site_url('sid_core')?>"> Daftar <?= ucwords($this->setting->sebutan_dusun)?></a></li>
      <li><a href="<?= site_url("sid_core/sub_rw/$id_dusun")?>"> Daftar RW</a></li>
			<li><a href="<?= site_url("sid_core/sub_rt/$id_dusun/$rw[rw]")?>"> Daftar RT</a></li>
			<li class="active">Peta Wilayah RT </li>
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
                    <input type="hidden" id="path" name="path" value="<?= $rt['path']?>">
                    <input type="hidden" name="id" id="id"  value="<?= $rt['id']?>"/>
                    <input type="hidden" name="rw" id="rw"  value="<?= $rt['rw']?>"/>
                    <input type="hidden" name="dusun" id="dusun"  value="<?= $rt['dusun']?>"/>
                    <input type="hidden" name="rt" id="rt"  value="<?= $rt['rt']?>"/>
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
      var dusun = $('#dusun').val();
      var rw = $('#rw').val();
      var rt = $('#rt').val();
      $.ajax(
			{
        type: "POST",
        url: "<?=$form_action?>",
        dataType: 'json',
        data: {path: path, id: id, rw: rw, dusun: dusun, rt: rt},
			});
		});
	});
</script>
<script src="<?= base_url()?>assets/js/validasi.js"></script>
<script src="<?= base_url()?>assets/js/jquery.validate.min.js"></script>
<script src="<?= base_url()?>assets/js/leaflet.filelayer.js"></script>
<script src="<?= base_url()?>assets/js/togeojson.js"></script>
