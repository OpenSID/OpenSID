<style>
  #map
  {
    width:100%;
    height:62vh
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
    <h1>Peta Wilayah <?= $nama_wilayah ?></h1>
    <ol class="breadcrumb">
      <li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
      <?php foreach ($breadcrumb as $tautan): ?>
        <li><a href="<?= $tautan['link'] ?>"> <?= $tautan['judul'] ?></a></li>
      <?php endforeach; ?>
      <li class="active">Peta Wilayah <?= $wilayah ?></li>
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
                    <input type="hidden" id="path" name="path" value="<?= $wil_ini['path']?>">
                    <input type="hidden" name="id" id="id"  value="<?= $wil_ini['id']?>"/>
                    <input type="hidden" name="zoom" id="zoom"  value="<?= $wil_ini['zoom']?>"/>
                  </div>
                </div>
              </div>
            </div>
            <div class='box-footer'>
              <div class='col-xs-12'>
                <a href="<?= $tautan['link'] ?>" class="btn btn-social btn-flat bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Kembali"><i class="fa fa-arrow-circle-o-left"></i> Kembali</a>
								<a href="#" class="btn btn-social btn-flat btn-success btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" download="OpenSID.gpx" id="exportGPX"><i class='fa fa-download'></i> Export ke GPX</a>
                <button type='reset' class='btn btn-social btn-flat btn-danger btn-sm' id="resetme"><i class='fa fa-times'></i> Reset</button>
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
  var infoWindow;
  window.onload = function()
  {
    //Jika posisi kantor dusun belum ada, maka posisi peta akan menampilkan peta desa
    <?php if (!empty($wil_ini['lat']) && !empty($wil_ini['lng'])): ?>
      var posisi = [<?=$wil_ini['lat'].",".$wil_ini['lng']?>];
      var zoom = <?=$wil_ini['zoom']?>;
    <?php else: ?>
      var posisi = [<?=$wil_atas['lat'].",".$wil_atas['lng']?>];
      var zoom = <?=$wil_atas['zoom']?>;
    <?php endif; ?>

    //Inisialisasi tampilan peta
    var peta_wilayah = L.map('map').setView(posisi, zoom);

    //Menampilkan BaseLayers Peta
    var defaultLayer = L.tileLayer.provider('OpenStreetMap.Mapnik').addTo(peta_wilayah);

    //Menampilkan overlayLayers Peta Wilayah di Atasnya
    <?php if (!empty($wil_atas['path'])): ?>
      var daerah_wil_atas = <?=$wil_atas['path']?>;
      var poligon_wil_atas = L.polygon(daerah_wil_atas, {pmIgnore: true, color: 'red',opacity: 1, fillColor: 'blue', fillOpacity: 0.1, weight: 2});
      var overlayLayers = {
        'Peta Administratif': poligon_wil_atas
      };
    <?php else: ?>
      var overlayLayers = {
      };
    <?php endif; ?>

    var baseLayers = {
      'OpenStreetMap': defaultLayer,
      'OpenStreetMap H.O.T.': L.tileLayer.provider('OpenStreetMap.HOT'),
      'Mapbox Streets' : L.tileLayer('https://api.mapbox.com/v4/mapbox.streets/{z}/{x}/{y}@2x.png?access_token=<?=$this->setting->google_key?>', {attribution: '<a href="https://www.mapbox.com/about/maps">© Mapbox</a> <a href="https://openstreetmap.org/copyright">© OpenStreetMap</a> | <a href="https://mapbox.com/map-feedback/">Improve this map</a>'}),
      'Mapbox Outdoors' : L.tileLayer('https://api.mapbox.com/v4/mapbox.outdoors/{z}/{x}/{y}@2x.png?access_token=<?=$this->setting->google_key?>', {attribution: '<a href="https://www.mapbox.com/about/maps">© Mapbox</a> <a href="https://openstreetmap.org/copyright">© OpenStreetMap</a> | <a href="https://mapbox.com/map-feedback/">Improve this map</a>'}),
      'Mapbox Streets Satellite' : L.tileLayer('https://api.mapbox.com/v4/mapbox.streets-satellite/{z}/{x}/{y}@2x.png?access_token=<?=$this->setting->google_key?>', {attribution: '<a href="https://www.mapbox.com/about/maps">© Mapbox</a> <a href="https://openstreetmap.org/copyright">© OpenStreetMap</a> | <a href="https://mapbox.com/map-feedback/">Improve this map</a>'}),
    };

    //Menampilkan Peta wilayah yg sudah ada
    <?php if (!empty($wil_ini['path'])): ?>
      var daerah_wilayah = <?=$wil_ini['path']?>;

      //Titik awal dan titik akhir poligon harus sama
      daerah_wilayah[0].push(daerah_wilayah[0][0]);

      var poligon_wilayah = L.polygon(daerah_wilayah).addTo(peta_wilayah);
      poligon_wilayah.on('pm:edit', function(e)
      {
        document.getElementById('path').value = getLatLong('Poly', e.target).toString();
        document.getElementById('zoom').value = peta_wilayah.getZoom();
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

      peta_wilayah.panTo(poligon_wilayah.getBounds().getCenter());
      // setTimeout(function() {peta_wilayah.invalidateSize();peta_wilayah.fitBounds(poligon_wilayah.getBounds());}, 500);

    <?php endif; ?>

    //Tombol yang akan dimunculkan di peta
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

    //Menambahkan zoom scale ke peta
    L.control.scale().addTo(peta_wilayah);

    //Menambahkan toolbar ke peta
    peta_wilayah.pm.addControls(options);

    //Menambahkan Peta wilayah
    peta_wilayah.on('pm:create', function(e)
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
      var polygon = L.polygon(p, { color: '#A9AAAA', weight: 4, opacity: 1 }).addTo(peta_wilayah);

      polygon.on('pm:edit', function(e)
      {
        document.getElementById('path').value = getLatLong('Poly', e.target).toString();
        document.getElementById('zoom').value = peta_wilayah.getZoom();
      });

      peta_wilayah.fitBounds(polygon.getBounds());

      // set value setelah create polygon
      document.getElementById('path').value = getLatLong('Poly', layer).toString();
      document.getElementById('zoom').value = peta_wilayah.getZoom();
    });

    // update value zoom ketika ganti zoom
    peta_wilayah.on('zoomend', function(e){
        document.getElementById('zoom').value = peta_wilayah.getZoom();
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
    control.addTo(peta_wilayah);

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

      }).addTo(peta_wilayah);

      var jml = coords[0].length;
      coords[0].push(coords[0][0]);
      for (var x = 0; x < jml; x++)
      {
        coords[0][x].reverse();
      }

      polygon.on('pm:edit', function(e)
      {
        document.getElementById('path').value = JSON.stringify(coords);
        document.getElementById('zoom').value = peta_wilayah.getZoom();
      });

      document.getElementById('path').value = JSON.stringify(coords);
      document.getElementById('zoom').value = peta_wilayah.getZoom();
      peta_wilayah.fitBounds(polygon.getBounds());
    });

    //Geolocation IP Route/GPS
  	var lc = L.control.locate({
  		icon: 'fa fa-map-marker',
      locateOptions: {enableHighAccuracy: true},
      strings: {
          title: "Lokasi Saya",
  				popup: "Anda berada di sekitar {distance} {unit} dari titik ini"
      }

  	}).addTo(peta_wilayah);

  	peta_wilayah.on('locationfound', function(e) {
  	    peta_wilayah.setView(e.latlng)
  	});

    peta_wilayah.on('startfollowing', function() {
      peta_wilayah.on('dragstart', lc._stopFollowing, lc);
  	}).on('stopfollowing', function() {
      peta_wilayah.off('dragstart', lc._stopFollowing, lc);
  	});

    //Menghapus Peta wilayah
    peta_wilayah.on('pm:globalremovalmodetoggled', function(e)
    {
      document.getElementById('path').value = '';
    })

    L.control.layers(baseLayers, overlayLayers, {position: 'topleft', collapsed: true}).addTo(peta_wilayah);

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
