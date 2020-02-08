
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

function getBaseLayers(peta, access_token)
{
	//Menampilkan BaseLayers Peta
	var defaultLayer = L.tileLayer.provider('OpenStreetMap.Mapnik').addTo(peta);

	var baseLayers = {
		'OpenStreetMap': defaultLayer,
		'OpenStreetMap H.O.T.': L.tileLayer.provider('OpenStreetMap.HOT'),
		'Mapbox Streets' : L.tileLayer('https://api.mapbox.com/v4/mapbox.streets/{z}/{x}/{y}@2x.png?access_token='+access_token, {attribution: '<a href="https://www.mapbox.com/about/maps">© Mapbox</a> <a href="https://openstreetmap.org/copyright">© OpenStreetMap</a> | <a href="https://mapbox.com/map-feedback/">Improve this map</a>'}),
		'Mapbox Outdoors' : L.tileLayer('https://api.mapbox.com/v4/mapbox.outdoors/{z}/{x}/{y}@2x.png?access_token='+access_token, {attribution: '<a href="https://www.mapbox.com/about/maps">© Mapbox</a> <a href="https://openstreetmap.org/copyright">© OpenStreetMap</a> | <a href="https://mapbox.com/map-feedback/">Improve this map</a>'}),
		'Mapbox Streets Satellite' : L.tileLayer('https://api.mapbox.com/v4/mapbox.streets-satellite/{z}/{x}/{y}@2x.png?access_token='+access_token, {attribution: '<a href="https://www.mapbox.com/about/maps">© Mapbox</a> <a href="https://openstreetmap.org/copyright">© OpenStreetMap</a> | <a href="https://mapbox.com/map-feedback/">Improve this map</a>'}),
	};
	return baseLayers;
}

function poligonWil(marker)
{
	var poligon_wil = L.geoJSON(turf.featureCollection(marker), {
    pmIgnore: true,
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
