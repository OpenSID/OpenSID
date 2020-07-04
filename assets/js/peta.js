
$(document).ready(function()
{
	$('#resetme').click(function(){
		window.location.reload(false);
	});
});

function set_marker(marker, daftar_path, warna, judul, nama_wil)
{
  var marker_style = {
    stroke: true,
    color: '#FF0000',
    opacity: 1,
    weight: 2,
    fillColor: warna,
    fillOpacity: 0.5
  }
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
        daftar[x].path[0][y].reverse()
      }
      daftar[x].path[0].push(daftar[x].path[0][0])
      marker.push(turf.polygon(daftar[x].path, {content: judul + ' ' + daftar[x][nama_wil], style: marker_style}));
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

  var point_style = stylePointLogo(favico_desa);
  marker_desa.push(turf.polygon(daerah_desa, {content: judul, style: stylePolygonDesa(), style: L.icon(point_style)}))
  marker_desa.push(turf.point([desa['lng'], desa['lat']], {content: "Kantor Desa", style: L.icon(point_style)}));
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

  var point_style = stylePointLogo(favico_desa);
  marker_desa.push(turf.point([desa['lng'], desa['lat']], {name: "kantor_desa", content: "Kantor Desa", style: L.icon(point_style)}));
  marker_desa.push(turf.polygon(daerah_desa, {content: content, style: stylePolygonDesa(), style: L.icon(point_style)}))
}

function set_marker_content(marker, daftar_path, warna, judul, nama_wil, contents)
{
  var marker_style = {
    stroke: true,
    color: '#FF0000',
    opacity: 1,
    weight: 2,
    fillColor: warna,
    fillOpacity: 0.5
  }
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
        daftar[x].path[0][y].reverse()
      }

			content = $(contents + x).html();

      daftar[x].path[0].push(daftar[x].path[0][0])
      marker.push(turf.polygon(daftar[x].path, {content: content, style: marker_style}));
    }
  }
}

function getBaseLayers(peta, access_token)
{
	//Menampilkan BaseLayers Peta
	var defaultLayer = L.tileLayer.provider('OpenStreetMap.Mapnik', {attribution: '<a href="https://openstreetmap.org/copyright">© OpenStreetMap</a> | <a href="https://github.com/OpenSID/OpenSID">OpenSID</a>'}).addTo(peta);

	var mbGLsat = L.mapboxGL({
		accessToken: access_token,
		style: 'mapbox://styles/mapbox/satellite-v9',
		attribution: '<a href="https://www.mapbox.com/about/maps">© Mapbox</a> | <a href="https://github.com/OpenSID/OpenSID">OpenSID</a>',
	});

	var mbGLstrsat = L.mapboxGL({
		accessToken: access_token,
		style: 'mapbox://styles/mapbox/satellite-streets-v11',
		attribution: '<a href="https://www.mapbox.com/about/maps">© Mapbox</a> | <a href="https://github.com/OpenSID/OpenSID">OpenSID</a>',
	});

	var baseLayers = {
		'OpenStreetMap': defaultLayer,
		'OpenStreetMap H.O.T.': L.tileLayer.provider('OpenStreetMap.HOT', {attribution: '<a href="https://openstreetmap.org/copyright">© OpenStreetMap</a> | <a href="https://github.com/OpenSID/OpenSID">OpenSID</a>'}),
		'Mapbox Citra Satelit' : mbGLsat,
		'Mapbox Citra Satelit + Jalan' : mbGLstrsat,
	};
	return baseLayers;
}

function poligonWil(marker)
{
	var poligon_wil = L.geoJSON(turf.featureCollection(marker), {
    pmIgnore: true,
		showMeasurements: true,
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

function overlayWil(marker_desa, marker_dusun, marker_rw, marker_rt)
{
  var poligon_wil_desa = poligonWil(marker_desa);
  var poligon_wil_dusun = poligonWil(marker_dusun);
  var poligon_wil_rw = poligonWil(marker_rw);
  var poligon_wil_rt = poligonWil(marker_rt);

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
  hasil = hasil.replace(/\}/g, ']').replace(/(\{)/g, '[').replace(/(\"lat\"\:|\"lng\"\:)/g, '');
  return hasil;
}

function stylePolygonDesa()
{
	var style_polygon = {
		stroke: true,
		color: '#FF0000',
		opacity: 1,
		weight: 2,
		fillColor: '#8888dd',
		fillOpacity: 0.5
	};
	return style_polygon;
}

function stylePointLogo(url)
{
	var style = {
			iconSize: [32, 37],
			iconAnchor: [16, 37],
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
		weight: 2,
		clickable: true
	};
	return style;
}

function eximGpx(layerpeta)
{
	var control = L.Control.fileLayerLoad({
		addToMap: false,
		formats: [
			'.gpx',
			'.geojson'
		],
		fitBounds: true,
		layerOptions: {
			style: styleGpx(),
			pointToLayer: function (data, latlng) {
				return L.circleMarker(
					latlng,
					{ style: styleGpx() }
				);
			},

		}
	});
	control.addTo(layerpeta);

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

		})
		.addTo(layerpeta)

		var jml = coords[0].length;
		coords[0].push(coords[0][0]);
		for (var x = 0; x < jml; x++)
		{
			coords[0][x].reverse();
		}

		polygon.on('pm:edit', function(e)
		{
			document.getElementById('path').value = JSON.stringify(coords);
			document.getElementById('zoom').value = layerpeta.getZoom();
		});

		document.getElementById('path').value = JSON.stringify(coords);
		document.getElementById('zoom').value = layerpeta.getZoom();
		layerpeta.fitBounds(polygon.getBounds());
	});
	return control;
}

function geoLocation(layerpeta)
{
	var lc = L.control.locate({
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
		var polygon = L.polygon(p, { color: '#A9AAAA', weight: 4, opacity: 1 })
		.addTo(layerpeta)
		.showMeasurements();

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
		var polygon = L.polyline(p, { color: '#A9AAAA', weight: 4, opacity: 1 })
		.addTo(layerpeta)
		.showMeasurements();

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

function showCurrentPolygon(wilayah, layerpeta)
{
	var daerah_wilayah = wilayah;
	daerah_wilayah[0].push(daerah_wilayah[0][0]);
	var poligon_wilayah = L.polygon(wilayah)
	.addTo(layerpeta)
	.showMeasurements();

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

	control = L.Control.fileLayerLoad({
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
	control.addTo(layerpeta);

	control.loader.on('data:loaded', function (e) {
		layerpeta.removeLayer(lokasi_kantor);
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
	return showCurrentPoint;
}

function showCurrentLine(wilayah, layerpeta)
{
	var poligon_wilayah = L.polyline(wilayah)
	.addTo(layerpeta)
	.showMeasurements();

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
	var poligon_wilayah = L.polygon(wilayah)
	.addTo(layerpeta)
	.showMeasurements();

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
			positif: 'jumlahKasus',
			meninggal: 'meninggal',
			sembuh: 'sembuh'
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
		let tempData = data[region.attributes[prop]];
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
		const COVID_API_URL = 'https://indonesia-covid-19.mathdro.id/api/';
		const ENDPOINT_PROVINSI = 'provinsi/';

		try {
			$.ajax({
				async: true,
				cache: true,
				url: COVID_API_URL,
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
					url: COVID_API_URL + ENDPOINT_PROVINSI,
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
