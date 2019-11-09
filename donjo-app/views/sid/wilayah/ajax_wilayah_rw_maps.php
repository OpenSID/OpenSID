<script>

var infoWindow;
window.onload = function()
{

  //Jika posisi kantor rw belum ada, maka posisi peta akan menampilkan seluruh Indonesia
	<?php if (!empty($rw['lat'] && !empty($rw['lng']))): ?>
		var posisi = [<?=$rw['lat'].",".$rw['lng']?>];
		var zoom = <?=$rw['zoom'] ?: 16?>;
	<?php else: ?>
		var posisi = [<?=$dusun_rw['lat'].",".$dusun_rw['lng']?>];
		var zoom = <?=$dusun_rw['zoom'] ?: 16?>;
	<?php endif; ?>
	//Menggunakan https://github.com/codeofsumit/leaflet.pm
	//Inisialisasi tampilan peta
  var peta_rw = L.map('map').setView(posisi, zoom);
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
	{
    maxZoom: 18,
    attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors',
    id: 'mapbox.streets'
  }).addTo(peta_rw);

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
  control.addTo(peta_rw);
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
  peta_rw.pm.addControls(options);

  //Menambahkan Peta wilayah
  peta_rw.on('pm:create', function(e)
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
    var polygon = L.polygon(p, { color: '#A9AAAA', weight: 4, opacity: 1 }).addTo(peta_rw);

    polygon.on('pm:edit', function(e)
    {
      document.getElementById('path').value = getLatLong('Poly', e.target).toString();
    })

  });

  //Menghapus Peta wilayah
  peta_rw.on('pm:globalremovalmodetoggled', function(e)
	{
    document.getElementById('path').value = '';
  })

  //Merubah Peta wilayah yg sudah ada
  <?php if (!empty($rw['path'])): ?>
  var daerah_rw = <?=$rw['path']?>;

  //Titik awal dan titik akhir poligon harus sama
  daerah_rw[0].push(daerah_rw[0][0]);

  var poligon_rw = L.polygon(daerah_rw).addTo(peta_rw);
  poligon_rw.on('pm:edit', function(e)
	{
    document.getElementById('path').value = getLatLong('Poly', e.target).toString();
  })
  setTimeout(function() {peta_rw.invalidateSize();peta_rw.fitBounds(poligon_rw.getBounds());}, 500);
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
		<h1>Peta Wilayah RW <?= $rw['rw']?> <?= ucwords($this->setting->sebutan_dusun." ".$rw['dusun'])?></h1>
		<ol class="breadcrumb">
      <li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?= site_url('sid_core')?>"> Daftar <?= ucwords($this->setting->sebutan_dusun)?></a></li>
			<li><a href="<?= site_url("sid_core/sub_rw/$id_dusun")?>"> Daftar RW</a></li>
			<li class="active">Peta Wilayah RW</li>
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
                    <input type="hidden" id="path" name="path" value="<?= $rw['path']?>">
                    <input type="hidden" name="id" id="id"  value="<?= $rw['id']?>"/>
                    <input type="hidden" name="rw" id="rw"  value="<?= $rw['rw']?>"/>
                    <input type="hidden" name="dusun" id="dusun"  value="<?= $rw['dusun']?>"/>
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
      var rw = $('#rw').val();
      var dusun = $('#dusun').val();
      $.ajax(
			{
        type: "POST",
        url: "<?=$form_action?>",
        dataType: 'json',
        data: {path: path, id: id, rw: rw, dusun: dusun},
			});
		});
	});
</script>
<script src="<?= base_url()?>assets/js/validasi.js"></script>
<script src="<?= base_url()?>assets/js/jquery.validate.min.js"></script>
<script src="<?= base_url()?>assets/js/leaflet.filelayer.js"></script>
<script src="<?= base_url()?>assets/js/togeojson.js"></script>
