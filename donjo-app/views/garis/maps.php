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
  .leaflet-control-layers {
  	display: block;
  	position: relative;
  }
  .leaflet-control-locate a {
  font-size: 2em;
	}
</style>
<!-- Menampilkan OpenStreetMap -->
<div class="content-wrapper">
  <section class="content-header">
		<h1>Peta <?= $garis['nama']?></h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?= site_url("garis")?>"> Pengaturan garis </a></li>
			<li class="active">Peta <?= $garis['nama']?></li>
		</ol>
	</section>
  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="box box-info">
          <form action="<?= $form_action?>" method="POST" enctype="multipart/form-data" class="form-horizontal">
            <div class="box-body">
              <div class="row">
                <div class="col-sm-12">
                  <div id="map">
                    <input type="hidden" id="path" name="path" value="<?= $garis['path']?>">
                    <input type="hidden" name="id" id="id"  value="<?= $garis['id']?>"/>
                  </div>
                </div>
              </div>
            </div>
            <div class='box-footer'>
              <div class='col-xs-12'>
                <a href="<?= site_url("garis")?>" class="btn btn-social btn-flat bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Kembali"><i class="fa fa-arrow-circle-o-left"></i> Kembali</a>
                <a href="#" class="btn btn-social btn-flat btn-success btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" download="OpenSID.gpx" id="exportGPX"><i class='fa fa-download'></i> Export ke GPX</a>
								<button type='reset' class='btn btn-social btn-flat btn-danger btn-sm' id="resetme"><i class='fa fa-times'></i> Reset</button>
								<button type='submit' class='btn btn-social btn-flat btn-info btn-sm pull-right' id="simpan_kantor"><i class='fa fa-check'></i> Simpan</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </section>
</div>

<script>
  var infoWindow;
  window.onload = function()
  {
  	<?php if (!empty($desa['lat']) && !empty($desa['lng'])): ?>
  		var posisi = [<?=$desa['lat'].",".$desa['lng']?>];
  		var zoom = <?=$desa['zoom'] ?: 18?>;
  	<?php else: ?>
  		var posisi = [-1.0546279422758742,116.71875000000001];
  		var zoom = 4;
  	<?php endif; ?>

  	//Inisialisasi tampilan peta
  	var peta_garis = L.map('map').setView(posisi, zoom);

  	//Menampilkan BaseLayers Peta
  	var defaultLayer = L.tileLayer.provider('OpenStreetMap.Mapnik').addTo(peta_garis);

  	var baseLayers = {
  		'OpenStreetMap': defaultLayer,
  		'OpenStreetMap H.O.T.': L.tileLayer.provider('OpenStreetMap.HOT'),
  		'Mapbox Streets' : L.tileLayer('https://api.mapbox.com/v4/mapbox.streets/{z}/{x}/{y}@2x.png?access_token=<?=$this->setting->google_key?>', {attribution: '<a href="https://www.mapbox.com/about/maps">© Mapbox</a> <a href="https://openstreetmap.org/copyright">© OpenStreetMap</a> | <a href="https://mapbox.com/map-feedback/">Improve this map</a>'}),
  		'Mapbox Outdoors' : L.tileLayer('https://api.mapbox.com/v4/mapbox.outdoors/{z}/{x}/{y}@2x.png?access_token=<?=$this->setting->google_key?>', {attribution: '<a href="https://www.mapbox.com/about/maps">© Mapbox</a> <a href="https://openstreetmap.org/copyright">© OpenStreetMap</a> | <a href="https://mapbox.com/map-feedback/">Improve this map</a>'}),
  		'Mapbox Streets Satellite' : L.tileLayer('https://api.mapbox.com/v4/mapbox.streets-satellite/{z}/{x}/{y}@2x.png?access_token=<?=$this->setting->google_key?>', {attribution: '<a href="https://www.mapbox.com/about/maps">© Mapbox</a> <a href="https://openstreetmap.org/copyright">© OpenStreetMap</a> | <a href="https://mapbox.com/map-feedback/">Improve this map</a>'}),
  	};

    //Menampilkan Peta wilayah yg sudah ada
    <?php if (!empty($garis['path'])): ?>
      var daerah_wilayah = <?=$garis['path']?>;

      var poligon_wilayah = L.polyline(daerah_wilayah).addTo(peta_garis);
      poligon_wilayah.on('pm:edit', function(e)
      {
        document.getElementById('path').value = getLatLong('Line', e.target).toString();
      })

      var layer = poligon_wilayah;
      var geojson = layer.toGeoJSON();
      var shape_for_db = JSON.stringify(geojson);
      var gpxData = togpx(JSON.parse(shape_for_db));

      $("#exportGPX").on('click', function (event) {
        data = 'data:text/xml;charset=utf-8,' + encodeURIComponent(gpxData);
        $(this).attr({
          'href': data,
          'target': '_blank'
        });
      });

      peta_garis.panTo(poligon_wilayah.getBounds().getCenter());
      // setTimeout(function() {peta_garis.invalidateSize();peta_garis.fitBounds(poligon_wilayah.getBounds());}, 500);

    <?php endif; ?>

    //Tombol yang akan dimunculkan di peta
    var options =
    {
  		position: 'topright', // toolbar position, options are 'topleft', 'topright', 'bottomleft', 'bottomright'
  		drawMarker: false, // adds button to draw markers
  		drawCircleMarker: false, // adds button to draw markers
  		drawPolyline: true, // adds button to draw a polyline
  		drawRectangle: false, // adds button to draw a rectangle
  		drawPolygon: false, // adds button to draw a polygon
  		drawCircle: false, // adds button to draw a cricle
  		cutPolygon: false, // adds button to cut a hole in a polygon
  		editMode: true, // adds button to toggle edit mode for all layers
  		removalMode: true, // adds a button to remove layers
  	};

    //Menambahkan zoom scale ke peta
    L.control.scale().addTo(peta_garis);

    //Menambahkan toolbar ke peta
    peta_garis.pm.addControls(options);

    //Menambahkan Peta wilayah
    peta_garis.on('pm:create', function(e)
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
      var polygon = L.polyline(p, { color: '#A9AAAA', weight: 4, opacity: 1 }).addTo(peta_garis);

      polygon.on('pm:edit', function(e)
      {
        document.getElementById('path').value = getLatLong('Line', e.target).toString();
      });

      peta_garis.fitBounds(polygon.getBounds());
    });

    //Unggah Peta dari file GPX/KML
    var style = {
      color: 'red',
      opacity: 1.0,
      fillOpacity: 1.0,
      weight: 2,
      clickable: true
    };

    L.Control.FileLayerLoad.LABEL = '<img class="icon" src="<?= base_url()?>assets/images/folder.svg" alt="file icon"/>';

    control = L.Control.fileLayerLoad({
      addToMap: false,
      formats: [
        '.gpx',
        '.geojson'
      ],
      fitBounds: true,
      layerOptions: {
        style: style,
        pointToLayer: function (data, latlng) {
          return L.circleMarker(
            latlng,
            { style: style }
          );
        },

      }
    });
    control.addTo(peta_garis);

    control.loader.on('data:loaded', function (e) {
      var type = e.layerType;
      var layer = e.layer;
      var coords=[];
      var geojson = layer.toGeoJSON();
      var options = {tolerance: 0.0001, highQuality: false};
      var simplified = turf.simplify(geojson, options);
      var shape_for_db = JSON.stringify(geojson);
      var gpxData = togpx(JSON.parse(shape_for_db));

      $("#exportGPX").on('click', function (event) {
        data = 'data:text/xml;charset=utf-8,' + encodeURIComponent(gpxData);

        $(this).attr({
          'href': data,
          'target': '_blank'
        });

      });

      var polygon =
      //L.geoJson(JSON.parse(shape_for_db), { //jika ingin koordinat tidak dipotong/simplified
      L.geoJson(simplified, {
        pointToLayer: function (feature, latlng) {
          return L.circleMarker(latlng, { style: style });
        },
        onEachFeature: function (feature, layer) {
          coords.push(feature.geometry.coordinates);
        },

      }).addTo(peta_garis);

      var jml = coords[0].length;
      for (var x = 0; x < jml; x++)
      {
        coords[0][x].reverse();
      }

      polygon.on('pm:edit', function(e)
      {
        document.getElementById('path').value = JSON.stringify(coords);
      });

      document.getElementById('path').value = JSON.stringify(coords);
      peta_garis.fitBounds(polygon.getBounds());
    });

    //Geolocation IP Route/GPS
  	var lc = L.control.locate({
  		icon: 'fa fa-map-marker',
      locateOptions: {enableHighAccuracy: true},
      strings: {
          title: "Lokasi Saya",
  				popup: "Anda berada di sekitar {distance} {unit} dari titik ini"
      }

  	}).addTo(peta_garis);

  	peta_garis.on('locationfound', function(e) {
  	    peta_garis.setView(e.latlng)
  	});

    peta_garis.on('startfollowing', function() {
      peta_garis.on('dragstart', lc._stopFollowing, lc);
  	}).on('stopfollowing', function() {
      peta_garis.off('dragstart', lc._stopFollowing, lc);
  	});

    //Menghapus Peta wilayah
    peta_garis.on('pm:globalremovalmodetoggled', function(e)
    {
      document.getElementById('path').value = '';
    })

    L.control.layers(baseLayers, null, {position: 'topleft', collapsed: true}).addTo(peta_garis);

    //Fungsi
    function getLatLong(x, y)
    {
      var hasil;
      if (x == 'Rectangle' || x == 'Line' || x == 'Line')
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
<script>
	$(document).ready(function(){
		$('#resetme').click(function(){
			$("#validasi1").validate({
				errorElement: "label",
				errorClass: "error",
				highlight:function (element){
					$(element).closest(".form-group").addClass("has-error");
				},
				unhighlight:function (element){
					$(element).closest(".form-group").removeClass("has-error");
				},
				errorPlacement: function (error, element) {
					if (element.parent('.input-group').length) {
						error.insertAfter(element.parent());
					} else {
						error.insertAfter(element);
					}
				}
			});

			window.location.reload(false);

		});
	});
</script>
<script src="<?= base_url()?>assets/js/leaflet.filelayer.js"></script>
<script src="<?= base_url()?>assets/js/togeojson.js"></script>
