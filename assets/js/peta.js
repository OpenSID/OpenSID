/**
 * File ini:
 *
 * Javascript untuk Modul Pemetaan di OpenSID
 *
 * assets/js/peta.js
 *
 */

/**
 *
 * File ini bagian dari:
 *
 * OpenSID
 *
 * Sistem informasi desa sumber terbuka untuk memajukan desa
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * Hak Cipta 2016 - 2020 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 *
 * Dengan ini diberikan izin, secara gratis, kepada siapa pun yang mendapatkan salinan
 * dari perangkat lunak ini dan file dokumentasi terkait ("Aplikasi Ini"), untuk diperlakukan
 * tanpa batasan, termasuk hak untuk menggunakan, menyalin, mengubah dan/atau mendistribusikan,
 * asal tunduk pada syarat berikut:
 *
 * Pemberitahuan hak cipta di atas dan pemberitahuan izin ini harus disertakan dalam
 * setiap salinan atau bagian penting Aplikasi Ini. Barang siapa yang menghapus atau menghilangkan
 * pemberitahuan ini melanggar ketentuan lisensi Aplikasi Ini.
 *
 * PERANGKAT LUNAK INI DISEDIAKAN "SEBAGAIMANA ADANYA", TANPA JAMINAN APA PUN, BAIK TERSURAT MAUPUN
 * TERSIRAT. PENULIS ATAU PEMEGANG HAK CIPTA SAMA SEKALI TIDAK BERTANGGUNG JAWAB ATAS KLAIM, KERUSAKAN ATAU
 * KEWAJIBAN APAPUN ATAS PENGGUNAAN ATAU LAINNYA TERKAIT APLIKASI INI.
 *
 * @package	OpenSID
 * @author	Tim Pengembang OpenDesa
 * @copyright	Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright	Hak Cipta 2016 - 2020 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license	http://www.gnu.org/licenses/gpl.html	GPL V3
 * @link 	https://github.com/OpenSID/OpenSID
 */


$(document).ready(function()
{
	$('#resetme').click(function(){
		window.location.reload(false);
	});
});

function set_marker(marker, daftar_path, judul, nama_wil, favico_desa)
{
  var daftar = JSON.parse(daftar_path);
  var jml = daftar.length;
  var jml_path;
  for (var x = 0; x < jml;x++)
  {
    if (daftar[x].path)
    {
      daftar[x].path = JSON.parse(daftar[x].path)
      jml_path = daftar[x].path[0].length;
      for (var y = 0; y < jml_path; y++)
      {
        daftar[x].path[0][y].reverse();
      }

      var label = L.tooltip({
        permanent: true,
        direction: 'center',
        className: 'text',
      }).setContent(judul + ' ' + daftar[x][nama_wil]);

      var point_style = {
        iconSize: [1, 1],
        iconAnchor: [0.5, 0.5],
        labelAnchor: [0.3, 0],
        iconUrl: favico_desa
    	};

      var marker_style = {
        stroke: true,
        color: 'white',
        opacity: 1,
        weight: 3,
        fillColor: daftar[x].warna,
        fillOpacity: 0.8,
        dashArray: 4
      }

      daftar[x].path[0].push(daftar[x].path[0][0]);
      if (daftar[x].lng)
      {
        marker.push(turf.point([daftar[x].lng, daftar[x].lat], {content: label, style: L.icon(point_style)}));
      }
      marker.push(turf.polygon(daftar[x].path, {content: daftar[x][nama_wil], style: marker_style}));
    }
  }
}

function set_marker_desa(marker_desa, desa, judul, favico_desa)
{
	var daerah_desa = JSON.parse(desa['path']);
  var jml = daerah_desa[0].length;
  daerah_desa[0].push(daerah_desa[0][0]);
  for (var x = 0; x < jml; x++)
  {
    daerah_desa[0][x].reverse();
  }

  var style_polygon = {
		stroke: true,
		color: '#de2d26',
		opacity: 1,
		weight: 3,
		fillColor: desa['warna'],
		fillOpacity: 0.8,
    dashArray: 4
	};

  var point_style = stylePointLogo(favico_desa);
  if (desa['lng'])
  {
    marker_desa.push(turf.point([desa['lng'], desa['lat']], {content: desa, style: L.icon(point_style)}));
  }
  marker_desa.push(turf.polygon(daerah_desa, {content: desa, style: style_polygon}));
}

function set_marker_desa_content(marker_desa, desa, judul, favico_desa, contents)
{
	var daerah_desa = JSON.parse(desa['path']);
  var jml = daerah_desa[0].length;
  daerah_desa[0].push(daerah_desa[0][0]);
  for (var x = 0; x < jml; x++)
  {
    daerah_desa[0][x].reverse();
  }

	content = $(contents).html();

  var style_polygon = {
		stroke: true,
		color: '#de2d26',
		opacity: 1,
		weight: 3,
		fillColor: desa['warna'],
		fillOpacity: 0.8,
    dashArray: 4
	};

  var point_style = stylePointLogo(favico_desa);
  if (desa['lng'])
  {
    marker_desa.push(turf.point([desa['lng'], desa['lat']], {name: "kantor_desa", content: "Kantor Desa", style: L.icon(point_style)}));
  }
  marker_desa.push(turf.polygon(daerah_desa, {content: content, style: style_polygon}));
}

function set_marker_content(marker, daftar_path, judul, nama_wil, contents, favico_desa)
{
  var daftar = JSON.parse(daftar_path);
  var jml = daftar.length;
  var jml_path;
  for (var x = 0; x < jml;x++)
  {
    if (daftar[x].path)
    {
      daftar[x].path = JSON.parse(daftar[x].path)
      jml_path = daftar[x].path[0].length;
      for (var y = 0; y < jml_path; y++)
      {
        daftar[x].path[0][y].reverse();
      }

			content = $(contents + x).html();

      var label = L.tooltip({
        permanent: true,
        direction: 'center',
        className: 'text',
      }).setContent(judul + ' ' + daftar[x][nama_wil]);

      var point_style = {
        iconSize: [1, 1],
        iconAnchor: [0.5, 0.5],
        labelAnchor: [0.3, 0],
        iconUrl: favico_desa
    	};

      var marker_style = {
        stroke: true,
        color: 'white',
        opacity: 1,
        weight: 3,
        fillColor: daftar[x].warna,
        fillOpacity: 0.8,
        dashArray: 4
      }

      daftar[x].path[0].push(daftar[x].path[0][0]);
      if (daftar[x].lng)
      {
        marker.push(turf.point([daftar[x].lng, daftar[x].lat], {content: label, style: L.icon(point_style)}));
      }
      marker.push(turf.polygon(daftar[x].path, {name: judul, content: content, style: marker_style}));
    }
  }
}

function getBaseLayers(peta, access_token)
{
	//Menampilkan BaseLayers Peta
	var defaultLayer = L.tileLayer.provider('OpenStreetMap.Mapnik', {attribution: '<a href="https://openstreetmap.org/copyright">© OpenStreetMap</a> | <a href="https://github.com/OpenSID/OpenSID">OpenSID</a>'}).addTo(peta);

  if (access_token)
  {
    mbGLstr = L.mapboxGL({
      accessToken: access_token,
      style: 'mapbox://styles/mapbox/streets-v11',
      attribution: '<a href="https://www.mapbox.com/about/maps">© Mapbox</a> | <a href="https://github.com/OpenSID/OpenSID">OpenSID</a>',
    });

    mbGLsat = L.mapboxGL({
  		accessToken: access_token,
  		style: 'mapbox://styles/mapbox/satellite-v9',
  		attribution: '<a href="https://www.mapbox.com/about/maps">© Mapbox</a> | <a href="https://github.com/OpenSID/OpenSID">OpenSID</a>',
  	});

  	mbGLstrsat = L.mapboxGL({
  		accessToken: access_token,
  		style: 'mapbox://styles/mapbox/satellite-streets-v11',
  		attribution: '<a href="https://www.mapbox.com/about/maps">© Mapbox</a> | <a href="https://github.com/OpenSID/OpenSID">OpenSID</a>',
  	});

  } else {
    mbGLstr = L.tileLayer.provider('OpenStreetMap.Mapnik', {attribution: '<a href="https://openstreetmap.org/copyright">© OpenStreetMap</a> | <a href="https://github.com/OpenSID/OpenSID">OpenSID</a>'}).addTo(peta);
    mbGLsat = L.tileLayer.provider('OpenStreetMap.Mapnik', {attribution: '<a href="https://openstreetmap.org/copyright">© OpenStreetMap</a> | <a href="https://github.com/OpenSID/OpenSID">OpenSID</a>'}).addTo(peta);
    mbGLstrsat = L.tileLayer.provider('OpenStreetMap.Mapnik', {attribution: '<a href="https://openstreetmap.org/copyright">© OpenStreetMap</a> | <a href="https://github.com/OpenSID/OpenSID">OpenSID</a>'}).addTo(peta);
  }

	var baseLayers = {
		'OpenStreetMap': defaultLayer,
		'OpenStreetMap H.O.T.': L.tileLayer.provider('OpenStreetMap.HOT', {attribution: '<a href="https://openstreetmap.org/copyright">© OpenStreetMap</a> | <a href="https://github.com/OpenSID/OpenSID">OpenSID</a>'}),
    'Mapbox Streets' : mbGLstr,
		'Mapbox Satellite' : mbGLsat,
		'Mapbox Satellite-Street' : mbGLstrsat
	};
	return baseLayers;
}

function poligonWil(marker)
{
	var poligon_wil = L.geoJSON(turf.featureCollection(marker), {
    pmIgnore: true,
    showMeasurements: true,
    measurementOptions: {showSegmentLength: false},
    onEachFeature: function (feature, layer) {
    	if (feature.properties.name == 'kantor_desa')
    	{
    		// Beri classname berbeda, supaya bisa gunakan css berbeda
	      layer.bindPopup(feature.properties.content, {'className' : 'kantor_desa'});
    	}
    	else
    	{
	      layer.bindPopup(feature.properties.content);
    	}
      layer.bindTooltip(feature.properties.content, {sticky: true, direction: 'top'});
    },
    style: function(feature)
    {
      if (feature.properties.style)
      {
        return feature.properties.style;
      }
    },
    pointToLayer: function (feature, latlng)
    {
      if (feature.properties.style)
      {
        return L.marker(latlng, {icon: feature.properties.style});
      }
      else
      return L.marker(latlng);
    }
  });

	return poligon_wil;
}

function overlayWil(marker_desa, marker_dusun, marker_rw, marker_rt, sebutan_desa, sebutan_dusun)
{
  var poligon_wil_desa = poligonWil(marker_desa);
  var poligon_wil_dusun = poligonWil(marker_dusun);
  var poligon_wil_rw = poligonWil(marker_rw);
  var poligon_wil_rt = poligonWil(marker_rt);

  // var peta_desa = 'Peta Wilayah ' + sebutan_desa;
  // var peta_dusun = 'Peta Wilayah ' + sebutan_dusun;
  // var overlayLayers = new Object;
  // overlayLayers[peta_desa] = poligon_wil_desa;
  // overlayLayers[peta_dusun] = poligon_wil_dusun;
  // overlayLayers['Peta Wilayah RW'] = poligon_wil_rw;
  // overlayLayers['Peta Wilayah RT'] = poligon_wil_rt;
  var overlayLayers = {
    'Peta Wilayah Desa': poligon_wil_desa,
    'Peta Wilayah Dusun': poligon_wil_dusun,
    'Peta Wilayah RW': poligon_wil_rw,
    'Peta Wilayah RT': poligon_wil_rt
  };

  return overlayLayers;
}

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
  //hasil = hasil.replace(/\}/g, ']').replace(/(\{)/g, '[').replace(/(\"lat\"\:|\"lng\"\:)/g, '');
	hasil = hasil
	.replace(/\}/g, ']')
	.replace(/(\{)/g, '[')
	.replace(/(\"lat\"\:|\"lng\"\:)/g, '')
	.replace(/(\"alt\"\:)/g, '')
	.replace(/(\"ele\"\:)/g, '');

  return hasil;
}

function stylePolygonDesa()
{
  var style_polygon = {
		stroke: true,
		color: '#de2d26',
		opacity: 1,
		weight: 3,
		fillColor: warna,
		fillOpacity: 0.5,
    dashArray: 4
	};
	return style_polygon;
}

function stylePointLogo(url)
{
	var style = {
			iconSize: [32, 32],
			iconAnchor: [16, 32],
			popupAnchor: [0, -28],
			iconUrl: url
	};
	return style;
}

function editToolbarPoly()
{
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
	return options;
}

function editToolbarLine()
{
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
	return options;
}

function styleGpx()
{
	var style = {
		color: 'red',
		opacity: 1.0,
		fillOpacity: 1.0,
		weight: 3,
		clickable: true
	};
	return style;
}

function eximGpxPoly(layerpeta)
{
	controlGpxPoly = L.Control.fileLayerLoad({
		addToMap: true,
		formats: [
			'.gpx',
			'.kml'
		],
		fitBounds: true,
		layerOptions: {
			pointToLayer: function (data, latlng) {
				return L.marker(latlng);
			},

		}
	});
	controlGpxPoly.addTo(layerpeta);

	controlGpxPoly.loader.on('data:loaded', function (e) {
		var type = e.layerType;
		var layer = e.layer;
		var coords=[];
		var geojson = turf.flip(layer.toGeoJSON());
		var shape_for_db = JSON.stringify(geojson);

		var polygon =
		L.geoJson(JSON.parse(shape_for_db), {
			pointToLayer: function (feature, latlng) {
				return L.marker(latlng);
			},
			onEachFeature: function (feature, layer) {
				coords.push(feature.geometry.coordinates);
			}
		}).addTo(layerpeta)

			var jml = coords[0].length;
			for (var x = 0; x < jml; x++)
			{
				if (coords[0][x].length > 2)
				{
				coords[0][x].pop();
				};
			}

			document.getElementById('path').value =
			JSON.stringify(coords)
			.replace(']],[[', '],[')
			.replace(']],[[', '],[')
			.replace(']],[[', '],[')
			.replace(']],[[', '],[')
			.replace(']],[[', '],[')
			.replace(']],[[', '],[')
			.replace(']],[[', '],[')
			.replace(']],[[', '],[')
			.replace(']],[[', '],[')
			.replace(']],[[', '],[')
			.replace(']]],[[[', '],[')
			.replace(']]],[[[', '],[')
			.replace(']]],[[[', '],[')
			.replace(']]],[[[', '],[')
			.replace(']]],[[[', '],[')
			.replace('[[[[', '[[[')
			.replace(']]]]', ']]]')
			.replace('],null]', ']');
	});
	return controlGpxPoly;
}

function eximGpxPoint(layerpeta)
{
	controlGpxPoint = L.Control.fileLayerLoad({
		addToMap: false,
		formats: [
			'.gpx',
			'.kml'
		],
		fitBounds: true,
		layerOptions: {
			pointToLayer: function (data, latlng) {
				return L.marker(latlng);
			},

		}
	});
	controlGpxPoint.addTo(layerpeta);

	controlGpxPoint.loader.on('data:loaded', function (e) {
		var type = e.layerType;
		var layer = e.layer;
		var coords=[];
		var geojson = layer.toGeoJSON();
		var shape_for_db = JSON.stringify(geojson);

		var polygon =
		L.geoJson(JSON.parse(shape_for_db), {
			pointToLayer: function (feature, latlng) {
				return L.marker(latlng);
			},
			onEachFeature: function (feature, layer) {
				coords.push(feature.geometry.coordinates);
			}
		}).addTo(layerpeta)

			document.getElementById('lat').value = coords[0][1];
			document.getElementById('lng').value = coords[0][0];

	});

	return controlGpxPoint;
}

function eximShp(layerpeta)
{
	L.Control.Shapefile = L.Control.extend({

    onAdd: function(map) {
        var thisControl = this;

        var controlDiv = L.DomUtil.create('div', 'leaflet-control-command');

        // Create the leaflet control.
        var controlUI = L.DomUtil.create('div', 'leaflet-control-command-interior', controlDiv);

        // Create the form inside of the leaflet control.
        var form = L.DomUtil.create('form', 'leaflet-control-command-form', controlUI);
        form.action = '';
        form.method = 'post';
        form.enctype='multipart/form-data';

        // Create the input file element.
        var input = L.DomUtil.create('input', 'leaflet-control-command-form-input', form);
        input.id = 'file';
        input.type = 'file';
        input.name = 'uploadFile';
        input.style.display = 'none';

        L.DomEvent
            .addListener(form, 'click', function () {
                document.getElementById("file").click();
            })
            .addListener(input, 'change', function(){
                var input = document.getElementById('file');
                if (!input.files[0]) {
                    alert("Pilih file shapefile dalam format .zip");
                }
                else {
                    file = input.files[0];

                    fr = new FileReader();
                    fr.onload = receiveBinary;
                    fr.readAsArrayBuffer(file);
                }

                function receiveBinary() {
                    geojson = fr.result
                    var shpfile = new L.Shapefile(geojson).addTo(map);

                    shpfile.once('data:loaded', function (e) {

                  		var type = e.layerType;
                  		var layer = e.layer;
                  		var coords =[];
                      var geojson = turf.flip(shpfile.toGeoJSON());
                  		var shape_for_db = JSON.stringify(geojson);

                  		var polygon =
                  		L.geoJson(JSON.parse(shape_for_db), {
                  			pointToLayer: function (feature, latlng) {
                  				return L.circleMarker(latlng, { style: style });
                  			},
                  			onEachFeature: function (feature, layer) {
                  				coords.push(feature.geometry.coordinates);
                  			},

                  		})

                      var jml = coords[0].length;
                			for (var x = 0; x < jml; x++)
                			{
                				if (coords[0][x].length > 2)
                				{
                				coords[0][x].pop();
                				};
                			}

											document.getElementById('path').value =
											JSON.stringify(coords)
											.replace(']],[[', '],[')
											.replace(']],[[', '],[')
											.replace(']],[[', '],[')
											.replace(']],[[', '],[')
											.replace(']],[[', '],[')
											.replace(']],[[', '],[')
											.replace(']],[[', '],[')
											.replace(']],[[', '],[')
											.replace(']],[[', '],[')
											.replace(']],[[', '],[')
											.replace(']]],[[[', '],[')
											.replace(']]],[[[', '],[')
											.replace(']]],[[[', '],[')
											.replace(']]],[[[', '],[')
											.replace(']]],[[[', '],[')
											.replace('[[[[', '[[[')
											.replace(']]]]', ']]]')
											.replace('],null]', ']');

											layerpeta.fitBounds(shpfile.getBounds());

                  	});
                }
            });

        controlUI.title = 'Impor Shapefile (.Zip)';
        return controlDiv;
    },
});

L.control.shapefile = function(opts) {
    return new L.Control.Shapefile(opts);
};

L.control.shapefile({ position: 'topleft' }).addTo(layerpeta);

return eximShp;
}

function geoLocation(layerpeta)
{
	var lc = L.control.locate({
		drawCircle: false,
		icon: 'fa fa-map-marker',
		locateOptions: {enableHighAccuracy: true},
		strings: {
				title: "Lokasi Saya",
				popup: "Anda berada di sekitar {distance} {unit} dari titik ini"
		}

	}).addTo(layerpeta);

	layerpeta.on('locationfound', function(e) {
			layerpeta.setView(e.latlng)
	});

	layerpeta.on('startfollowing', function() {
		layerpeta.on('dragstart', lc._stopFollowing, lc);
	}).on('stopfollowing', function() {
		layerpeta.off('dragstart', lc._stopFollowing, lc);
	});
	return lc;
}

function hapusPeta(layerpeta)
{
	layerpeta.on('pm:globalremovalmodetoggled', function(e)
	{
		document.getElementById('path').value = '';
	});
	return hapusPeta;
}

function updateZoom(layerpeta)
{
	layerpeta.on('zoomend', function(e){
	document.getElementById('zoom').value = layerpeta.getZoom();
	});
	return updateZoom;
}

function addPetaPoly(layerpeta)
{
	layerpeta.on('pm:create', function(e)
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
		var polygon = L.polygon(p, { color: '#A9AAAA', weight: 4, opacity: 1, showMeasurements: true, measurementOptions: {showSegmentLength: false} })
		.addTo(layerpeta)

		polygon.on('pm:edit', function(e)
		{
			document.getElementById('path').value = getLatLong('Poly', e.target).toString();
			document.getElementById('zoom').value = layerpeta.getZoom();
		});

		layerpeta.fitBounds(polygon.getBounds());

		// set value setelah create polygon
		document.getElementById('path').value = getLatLong('Poly', layer).toString();
		document.getElementById('zoom').value = layerpeta.getZoom();
	});
	return addPetaPoly;
}

function addPetaLine(layerpeta)
{
	layerpeta.on('pm:create', function(e)
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
		var polygon = L.polyline(p, { color: '#A9AAAA', weight: 4, opacity: 1, showMeasurements: true, measurementOptions: {showSegmentLength: false} })
		.addTo(layerpeta)

		polygon.on('pm:edit', function(e)
		{
			document.getElementById('path').value = getLatLong('Line', e.target).toString();
		});

		layerpeta.fitBounds(polygon.getBounds());

		// set value setelah create polygon
		document.getElementById('path').value = getLatLong('Line', layer).toString();
	});
	return addPetaLine;
}

function showCurrentPolygon(wilayah, layerpeta, warna)
{
	var daerah_wilayah = wilayah;
	daerah_wilayah[0].push(daerah_wilayah[0][0]);
	var poligon_wilayah = L.polygon(wilayah, {
  showMeasurements: true,
  measurementOptions: {showSegmentLength: false}})
	.addTo(layerpeta)

	poligon_wilayah.on('pm:edit', function(e)
	{
		document.getElementById('path').value = getLatLong('Poly', e.target).toString();
		document.getElementById('zoom').value = layerpeta.getZoom();
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

	layerpeta.fitBounds(poligon_wilayah.getBounds());

	// set value setelah create polygon
	document.getElementById('path').value = getLatLong('Poly', layer).toString();
	document.getElementById('zoom').value = layerpeta.getZoom();

	return showCurrentPolygon;
}

function showCurrentPoint(posisi1, layerpeta)
{
	var lokasi_kantor = L.marker(posisi1, {draggable: true}).addTo(layerpeta);

	lokasi_kantor.on('dragend', function(e){
		$('#lat').val(e.target._latlng.lat);
		$('#lng').val(e.target._latlng.lng);
		$('#map_tipe').val("HYBRID");
		$('#zoom').val(layerpeta.getZoom());
	})

	layerpeta.on('zoomstart zoomend', function(e){
		$('#zoom').val(layerpeta.getZoom());
	})

	$('#lat').on("input",function(e) {
		if (!$('#validasi1').valid())
		{
			$("#simpan_kantor").attr('disabled', true);
			return;
		} else
		{
			$("#simpan_kantor").attr('disabled', false);
		}
		let lat = $('#lat').val();
		let lng = $('#lng').val();
		let latLng = L.latLng({
			lat: lat,
			lng: lng
		});

		lokasi_kantor.setLatLng(latLng);
		layerpeta.setView(latLng,zoom);
	})

	$('#lng').on("input",function(e) {
		if (!$('#validasi1').valid())
		{
			$("#simpan_kantor").attr('disabled', true);
			return;
		} else
		{
			$("#simpan_kantor").attr('disabled', false);
		}
		let lat = $('#lat').val();
		let lng = $('#lng').val();
		let latLng = L.latLng({
			lat: lat,
			lng: lng
		});

		lokasi_kantor.setLatLng(latLng);
		layerpeta.setView(latLng, zoom);
	});

	var geojson = lokasi_kantor.toGeoJSON();
	var shape_for_db = JSON.stringify(geojson);
	var gpxData = togpx(JSON.parse(shape_for_db));

	$("#exportGPX").on('click', function (event) {
		data = 'data:text/xml;charset=utf-8,' + encodeURIComponent(gpxData);
		$(this).attr({
			'href': data,
			'target': '_blank'
		});
	});

	var lc = L.control.locate({
		drawCircle: false,
		icon: 'fa fa-map-marker',
		strings: {
				title: "Lokasi Saya",
				locateOptions: {enableHighAccuracy: true},
				popup: "Anda berada disekitar {distance} {unit} dari titik ini"
		}

	}).addTo(layerpeta);

	layerpeta.on('locationfound', function(e) {
			$('#lat').val(e.latlng.lat);
			$('#lng').val(e.latlng.lng);
			lokasi_kantor.setLatLng(e.latlng);
			layerpeta.setView(e.latlng)
	});

	layerpeta.on('startfollowing', function() {
		layerpeta.on('dragstart', lc._stopFollowing, lc);
	}).on('stopfollowing', function() {
		layerpeta.off('dragstart', lc._stopFollowing, lc);
	});

	return showCurrentPoint;
}

function showCurrentLine(wilayah, layerpeta)
{
	var poligon_wilayah = L.polyline(wilayah, {showMeasurements: true, measurementOptions: {showSegmentLength: false}})
	.addTo(layerpeta)

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

	layerpeta.fitBounds(poligon_wilayah.getBounds());

	// set value setelah create polygon
	document.getElementById('path').value = getLatLong('Line', layer).toString();

	return showCurrentLine;
}

function showCurrentArea(wilayah, layerpeta)
{
	var daerah_wilayah = wilayah;
	daerah_wilayah[0].push(daerah_wilayah[0][0]);
	var poligon_wilayah = L.polygon(wilayah, {showMeasurements: true, measurementOptions: {showSegmentLength: false}})
	.addTo(layerpeta)

	poligon_wilayah.on('pm:edit', function(e)
	{
		document.getElementById('path').value = getLatLong('Poly', e.target).toString();
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

	layerpeta.fitBounds(poligon_wilayah.getBounds());

	// set value setelah create polygon
	document.getElementById('path').value = getLatLong('Poly', layer).toString();

	return showCurrentArea;
}

function setMarkerCustom(marker, layercustom)
{
	if (marker.length != 0)
	{
		var geojson = L.geoJSON(turf.featureCollection(marker), {
			pmIgnore: true,
      showMeasurements: true,
      measurementOptions: {showSegmentLength: false},
			onEachFeature: function (feature, layer) {
				layer.bindPopup(feature.properties.content);
				layer.bindTooltip(feature.properties.content);
			},
			style: function(feature)
			{
				if (feature.properties.style)
				{
					return feature.properties.style;
				}
			},
			pointToLayer: function (feature, latlng)
			{
				if (feature.properties.style)
				{
					return L.marker(latlng, {icon: feature.properties.style});
				}
				else
				return L.marker(latlng);
			}
		});

		layercustom.addLayer(geojson);
	}

	return setMarkerCustom;
}

function setMarkerCluster(marker, markersList, markers)
{
	if (marker.length != 0)
	{
		var geojson = L.geoJSON(turf.featureCollection(marker), {
			pmIgnore: true,
      showMeasurements: true,
      measurementOptions: {showSegmentLength: false},
			onEachFeature: function (feature, layer) {
				layer.bindPopup(feature.properties.content);
				layer.bindTooltip(feature.properties.content);
			},
			style: function(feature)
			{
				if (feature.properties.style)
				{
					return feature.properties.style;
				}
			},
			pointToLayer: function (feature, latlng)
			{
				if (feature.properties.style)
				{
					return L.marker(latlng, {icon: feature.properties.style});
				}
				else
				return L.marker(latlng);
			}
		});

		markersList.push(geojson);
		markers.addLayer(geojson);
	}

	return setMarkerCluster;
}

function set_marker_area(marker, daftar_path, foto_area)
{
  var daftar = JSON.parse(daftar_path);
  var jml = daftar.length;
  var jml_path;
  var foto;
  var content_area;
  var lokasi_gambar = foto_area;

  for (var x = 0; x < jml;x++)
  {
    if (daftar[x].path)
    {
      daftar[x].path = JSON.parse(daftar[x].path)
      jml_path = daftar[x].path[0].length;
      for (var y = 0; y < jml_path; y++)
      {
        daftar[x].path[0][y].reverse()
      }

      if (daftar[x].foto)
      {
        foto = '<img src="'+lokasi_gambar+'sedang_'+daftar[x].foto+'" style=" width:200px;height:140px;border-radius:3px;-moz-border-radius:3px;-webkit-border-radius:3px;border:2px solid #555555;"/>';
      }
      else
      foto = "";

      var area_style = {
        stroke: true,
        opacity: 1,
        weight: 3,
        fillColor: daftar[x].color,
        fillOpacity: 0.5
      }

      content_area =
      '<div id="content">'+
      '<div id="siteNotice">'+
      '</div>'+
      '<h4 id="firstHeading" class="firstHeading">'+daftar[x].nama+'</h4>'+
      '<div id="bodyContent">'+ foto +
      '<p>'+daftar[x].desk+'</p>'+
      '</div>'+
      '</div>';

      daftar[x].path[0].push(daftar[x].path[0][0])
      marker.push(turf.polygon(daftar[x].path, {content: content_area, style: area_style}));
    }
  }
}

function set_marker_garis(marker, daftar_path, foto_garis)
{
  var daftar = JSON.parse(daftar_path);
  var jml = daftar.length;
  var coords;
  var lengthOfCoords;
  var foto;
  var content_garis;
  var lokasi_gambar = foto_garis;

  for (var x = 0; x < jml;x++)
  {
    if (daftar[x].path)
    {
      daftar[x].path = JSON.parse(daftar[x].path)
      coords = daftar[x].path;
      lengthOfCoords = coords.length;
      for (i = 0; i < lengthOfCoords; i++)
      {
        holdLon = coords[i][0];
        coords[i][0] = coords[i][1];
        coords[i][1] = holdLon;
      }

      if (daftar[x].foto)
      {
        foto = '<img src="'+lokasi_gambar+'sedang_'+daftar[x].foto+'" style=" width:200px;height:140px;border-radius:3px;-moz-border-radius:3px;-webkit-border-radius:3px;border:2px solid #555555;"/>';
      }
      else
      foto = "";

      content_garis =
      '<div id="content">'+
      '<div id="siteNotice">'+
      '</div>'+
      '<h4 id="firstHeading" class="firstHeading">'+daftar[x].nama+'</h4>'+
      '<div id="bodyContent">'+ foto +
      '<p>'+daftar[x].desk+'</p>'+
      '</div>'+
      '</div>';

      var garis_style = {
        stroke: true,
        opacity: 1,
        weight: 3,
        color: daftar[x].color
      }

      marker.push(turf.lineString(coords, {content: content_garis, style: garis_style}));
    }
  }
}

function set_marker_lokasi(marker, daftar_path, path_icon, foto_lokasi)
{
  var daftar = JSON.parse(daftar_path);
  var jml = daftar.length;
  var foto;
  var content_lokasi;
  var lokasi_gambar = foto_lokasi;
  var path_foto = path_icon;
  var point_style = {
    iconSize: [32, 32],
    iconAnchor: [16, 32],
    popupAnchor: [0, -28],
  };

  for (var x = 0; x < jml; x++)
  {
    if (daftar[x].lat)
    {
      point_style.iconUrl = path_foto+daftar[x].simbol;

      if (daftar[x].foto)
      {
        foto = '<img src="'+lokasi_gambar+'sedang_'+daftar[x].foto+'" style=" width:200px;height:140px;border-radius:3px;-moz-border-radius:3px;-webkit-border-radius:3px;border:2px solid #555555;"/>';
      }
      else
      foto = '';

      content_lokasi =
      '<div id="content">'+
      '<div id="siteNotice">'+
      '</div>'+
      '<h4 id="firstHeading" class="firstHeading">'+daftar[x].nama+'</h4>'+
      '<div id="bodyContent">'+ foto +
      '<p>'+daftar[x].desk+'</p>'+
      '</div>'+
      '</div>';

      marker.push(turf.point([daftar[x].lng, daftar[x].lat], {content: content_lokasi, style: L.icon(point_style)}));
    }
  }
}

//Menampilkan OverLayer Area, Garis, Lokasi
function tampilkan_layer_area_garis_lokasi(peta, daftar_path, daftar_garis, daftar_lokasi, path_icon, foto_area, foto_garis, foto_lokasi)
{
  var marker_area = [];
  var marker_garis = [];
  var marker_lokasi = [];
  var markers = new L.MarkerClusterGroup();
  var markersList = [];

	var layer_area = L.featureGroup();
	var layer_garis = L.featureGroup();
	var layer_lokasi = L.featureGroup();

	var layerCustom = {
		"Infrastruktur Desa": {
			"Infrastruktur (Area)": layer_area,
			"Infrastruktur (Garis)": layer_garis,
			"Infrastruktur (Lokasi)": layer_lokasi
		}
	};

	//OVERLAY AREA
	if (daftar_path) {
		set_marker_area(marker_area, daftar_path, foto_area);
	}

	//OVERLAY GARIS
	if (daftar_garis) {
		set_marker_garis(marker_garis, daftar_garis, foto_garis);
	}

	//OVERLAY LOKASI DAN PROPERTI
	if (daftar_lokasi) {
		set_marker_lokasi(marker_lokasi, daftar_lokasi, path_icon, foto_lokasi);
	}

	setMarkerCustom(marker_area, layer_area);
	setMarkerCustom(marker_garis, layer_garis);
	setMarkerCluster(marker_lokasi, markersList, markers);

	peta.on('layeradd layerremove', function () {
		peta.eachLayer(function (layer) {
			if(peta.hasLayer(layer_lokasi)) {
				peta.addLayer(markers);
			} else {
				peta.removeLayer(markers);
        peta._layersMaxZoom = 19;
			}
		});
	});

	return layerCustom;
}

$(document).ready(function()
{
	$('#modalKecil').on('show.bs.modal', function(e)
	{
		var link = $(e.relatedTarget);
		var title = link.data('title');
		var modal = $(this)
		modal.find('.modal-title').text(title)
		$(this).find('.fetched-data').load(link.attr('href'));
	});

  $('#modalSedang').on('show.bs.modal', function(e)
	{
		var link = $(e.relatedTarget);
		var title = link.data('title');
		var modal = $(this)
		modal.find('.modal-title').text(title)
		$(this).find('.fetched-data').load(link.attr('href'));
	});

  $('#modalBesar').on('show.bs.modal', function(e)
	{
		var link = $(e.relatedTarget);
		var title = link.data('title');
		var modal = $(this)
		modal.find('.modal-title').text(title)
		$(this).find('.fetched-data').load(link.attr('href'));
	});
	return false;
})

const regions = {
	indonesia: {
		id: 1,
		attributes: {
			wilayah: 'name',
			positif: 'confirmed',
			meninggal: 'deaths',
			sembuh: 'recovered'
		}
	},
	provinsi: {
		id: 2,
		attributes: {
			wilayah: 'provinsi',
			positif: 'kasusPosi',
			meninggal: 'kasusMeni',
			sembuh: 'kasusSemb'
		}
	}
}

function numberFormat(num) {
	return new Intl.NumberFormat('id-ID').format(num);
}

function parseToNum(data) {
	return parseFloat(data.toString().replace(/,/g, ''));
}

function showCovidData(data, region) {
	const elem = region.id === regions.indonesia.id ? '#covid-nasional' : '#covid-provinsi';
	Object.keys(region.attributes).forEach(function (prop) {
		let tempData = (region.id === regions.indonesia.id && prop !== 'wilayah') ? data[region.attributes[prop]]['value'] : data[region.attributes[prop]];
		let finalData = prop === 'wilayah' ? tempData.toUpperCase() : numberFormat(parseToNum(tempData));
		$(elem).find(`[data-name=${prop}]`).html(`${finalData}`);
	});

	$(elem).find('.shimmer').removeClass('shimmer');
}

function showError(elem = '') {
	$(`${elem} .shimmer`).html('<span class="small"><i class="fa fa-exclamation-triangle"></i> Gagal memuat...</span>');
	$(`${elem} .shimmer`).removeClass('shimmer');
}

$(document).ready(function () {
	if ($('#covid-nasional').length) {
		const ENDPOINT_NASIONAL = 'https://covid19.mathdro.id/api/countries/indonesia';
		const ENDPOINT_PROVINSI = 'https://indonesia-covid-19.mathdro.id/api/provinsi';

		try {
			$.ajax({
				async: true,
				cache: true,
				url: ENDPOINT_NASIONAL,
				success: function (response) {
					const data = response;
					data.name = 'Indonesia';
					showCovidData(data, regions.indonesia);
				},
				error: function (error) {
					showError('#covid-nasional');
				}
			})
		} catch (error) {
			showError('#covid-nasional');
		}

		if (KODE_PROVINSI) {
			try {
				$.ajax({
					async: true,
					cache: true,
					url: ENDPOINT_PROVINSI,
					success: function (response) {
						const data = response.data.filter(data => data.kodeProvi == KODE_PROVINSI);
						data.length ? showCovidData(data[0], regions.provinsi) : showError('#covid-provinsi');
					},
					error: function (error) {
						showError('#covid-provinsi');
					}
				})
			} catch (error) {
				showError('#covid-provinsi')
			}
		}

	}
})

//Cetak Peta ke PNG
function cetakPeta(layerpeta)
{
  L.control.browserPrint({
    documentTitle: "Peta_Wilayah",
    printModes: [
      L.control.browserPrint.mode.auto("Auto"),
      L.control.browserPrint.mode.landscape("Landscape"),
      L.control.browserPrint.mode.portrait("Portrait")
    ],
  }).addTo(layerpeta);

  L.Control.BrowserPrint.Utils.registerLayer(L.MarkerClusterGroup, 'L.MarkerClusterGroup', function (layer, utils) {
    return layer;
  });

  L.Control.BrowserPrint.Utils.registerLayer(L.MapboxGL, 'L.MapboxGL', function(layer, utils) {
      return L.mapboxGL(layer.options);
    }
  );

  window.print = function () {
    return domtoimage
    .toPng(document.querySelector(".grid-print-container"))
    .then(function (dataUrl) {
      var link = document.createElement('a');
      link.download = layerpeta.printControl.options.documentTitle || "exportedMap" + '.png';
      link.href = dataUrl;
      link.click();
    });
  };
  return cetakPeta;
}

//Menambahkan legend ke peta dusun/rw/rt
function setlegendPeta(legenda, layerpeta, legendData, judul, nama_wil, judul_wil_atas)
{
  var daftar = JSON.parse(legendData);
  var div = L.DomUtil.create('div', 'info legend');
  var labels = ['<strong>Legenda' + ' ' + ' - ' +  ' ' + judul + '</strong>'];

  for (var x = 0; x < daftar.length; x++)
  {
    if (daftar[x].path)
    {
      legenda.onAdd = function (layerpeta) {
        var categories = [judul + ' ' + daftar[x][nama_wil]];
        if (judul === 'RT') {
          var categories = [judul + ' ' + daftar[x][nama_wil]  + ' ' + judul_wil_atas + ' ' + daftar[x].rw + ' ' + daftar[x].dusun];
        }
        if (judul === 'RW') {
          var categories = [judul + ' ' + daftar[x][nama_wil] + ' ' + judul_wil_atas + ' ' + daftar[x].dusun];
        }
        for (var i = 0; i < categories.length; i++)
          {
          div.innerHTML +=
          labels.push(
            '<i class="circle" style="background:' + daftar[x].warna + '"></i> ' +
            (categories[i] ? categories[i] + '<br>' : '+'));
          }
        div.innerHTML = labels.join('<br>');
        return div;
      }
      legenda.addTo(layerpeta);
    }
  }
  setlegendPrint(legenda, layerpeta, legendData, judul, nama_wil, judul_wil_atas);
  return setlegendPeta;
}

function setlegendPrint(legenda, layerpeta, legendData, judul, nama_wil, judul_wil_atas)
{
  layerpeta.on("browser-print-start", function(e){

    var daftar = JSON.parse(legendData);
    var div = L.DomUtil.create('div', 'info legend');
    var labels = ['<strong>Legenda' + ' ' + ' - ' +  ' ' + judul + '</strong>'];

    for (var x = 0; x < daftar.length; x++)
    {
      if (daftar[x].path)
      {
        legenda.onAdd = function (layerpeta) {
          var categories = [judul + ' ' + daftar[x][nama_wil]];
          if (judul === 'RT') {
            var categories = [judul + ' ' + daftar[x][nama_wil]  + ' ' + judul_wil_atas + ' ' + daftar[x].rw + ' ' + daftar[x].dusun];
          }
          if (judul === 'RW') {
            var categories = [judul + ' ' + daftar[x][nama_wil] + ' ' + judul_wil_atas + ' ' + daftar[x].dusun];
          }
          for (var i = 0; i < categories.length; i++)
            {
            div.innerHTML +=
            labels.push(
              '<i class="circle" style="background:' + daftar[x].warna + '"></i> ' +
              (categories[i] ? categories[i] + '<br>' : '+'));
            }
          div.innerHTML = labels.join('<br>');
          return div;
        }
        legenda.addTo(e.printMap);
      }
    }
  });
  return setlegendPrint;
}

//Menambahkan legend ke peta desa
function setlegendPetaDesa(legenda, layerpeta, legendData, judul, nama_wil)
{
  var daftar = JSON.parse(legendData['path']);

  for (var x = 0; x < daftar.length; x++)
  {
    legenda.onAdd = function (layerpeta) {
      var div = L.DomUtil.create('div', 'info legend');
      var labels = ['<strong>Legenda' + ' ' + ' - ' +  ' ' + judul + '</strong>'];
      var categories = [judul + ' ' + legendData['nama_desa']];
      for (var i = 0; i < categories.length; i++)
        {
          div.innerHTML +=
          labels.push(
            '<i class="circle" style="background:' + legendData['warna'] + '"></i> ' +
            (categories[i] ? categories[i] + '<br>' : '+'));
          }
        div.innerHTML = labels.join('<br>');
        return div;
    }
    legenda.addTo(layerpeta);
  }

  layerpeta.on("browser-print-start", function(e){
    L.control.scale({position: 'bottomleft'}).addTo(e.printMap);
    legenda.addTo(e.printMap);
  });

  return setlegendPetaDesa;
}
