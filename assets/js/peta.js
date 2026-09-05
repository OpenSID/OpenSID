var error_message = "";
var sebutan_dusun;
var layers = {};

function tryParseJson(str) {
    if (typeof str !== 'string') {
        return str;
    }
    try {
        var obj = JSON.parse(str);
        // Handle double-encoded JSON
        if (typeof obj === 'string') {
            return tryParseJson(obj);
        }
        return obj;
    } catch (e) {
        console.error("Gagal mem-parsing path JSON: ", str, e);
        return null;
    }
}

// Normalize polygon coordinates (ensure [lng,lat] order and closed rings)
function normalizePolygonCoords(polygonCoords) {
  try {
    if (!Array.isArray(polygonCoords)) return null;
    // polygonCoords expected as [ [ [x,y], ... ], [ ...holes ] ]
    const rings = polygonCoords.map((ring) => {
      const normalized = ring.map((pt) => {
        if (!Array.isArray(pt) || pt.length < 2) return null;
        const a = Number(pt[0]);
        const b = Number(pt[1]);
        if (Number.isNaN(a) || Number.isNaN(b)) return null;
        // If first number outside lat range, assume it's [lng,lat]
        if (Math.abs(a) > 90 && Math.abs(b) <= 90) return [a, b];
        // Otherwise assume [lat,lng] and swap to [lng,lat]
        return [b, a];
      }).filter(Boolean);
      if (normalized.length === 0) return null;
      const first = normalized[0];
      const last = normalized[normalized.length - 1];
      if (first[0] !== last[0] || first[1] !== last[1]) normalized.push(first);
      return normalized;
    }).filter(Boolean);
    if (rings.length === 0) return null;
    return rings;
  } catch (err) {
    return null;
  }
}

function safeTurfPolygon(polygonCoords, props) {
  const rings = normalizePolygonCoords(polygonCoords);
  if (!rings) return null;
  try {
    return turf.polygon(rings, props);
  } catch (err) {
    console.error('safeTurfPolygon error', err, polygonCoords);
    return null;
  }
}

function set_marker(marker, daftar_path, judul, nama_wil, favico_desa) {
  var daftar = JSON.parse(daftar_path);
  var jml_path;
  for (var x = 0; x < daftar.length; x++) {
    if (daftar[x].path) {
      daftar[x].path = JSON.parse(daftar[x].path);
      jml_path = daftar[x].path[0].length;
        for (var y = 0; y < jml_path; y++) {
          daftar[x].path[0][y].reverse();
        }
        var marker_style = setAreaStyle(daftar[x], false);
        daftar[x].path[0].push(daftar[x].path[0][0]);
        const poly = safeTurfPolygon(daftar[x].path, {
          content: daftar[x][nama_wil],
          style: marker_style,
        });
        if (poly) {
          marker.push(poly);
        } else {
          error_message += message(judul);
        }
    }
  }
}

function set_marker_multi(marker, daftar_path, judul, nama_wil, favico_desa) {
  if (nama_wil == "dusun") {
    sebutan_dusun = judul;
  }

  var daftar = JSON.parse(daftar_path);
  var jml_path;
  for (var x = 0; x < daftar.length; x++) {
    if (daftar[x].path) {
      daftar[x].path = JSON.parse(daftar[x].path);
      if (isValidMultiPolygonPath(daftar[x].path)) {
        var jml_path_x = daftar[x].path.length;
        for (var a = 0; a < jml_path_x; a++) {
          for (var b = 0; b < daftar[x].path[a].length; b++) {
            jml_path = daftar[x].path[a][0].length;
            for (var z = 0; z < jml_path; z++) {
              daftar[x].path[a][0][z].reverse();
            }
            var marker_style = setAreaStyle(daftar[x], false);
            daftar[x].path[a][0].push(daftar[x].path[a][0][0]);
            const poly = safeTurfPolygon(daftar[x].path[a], {
              content: daftar[x][nama_wil],
              style: marker_style,
            });
            if (poly) {
              marker.push(poly);
            } else {
              error_message += message(judul);
            }
          }
        }
      } else {
        error_message += message(
          null,
          daftar[x].dusun,
          daftar[x].rw,
          daftar[x].rt
        );
      }
    }
  }
}

function set_marker_desa(marker_desa, desa, judul, favico_desa) {
  var desa_path = JSON.parse(desa["path"]);
  var polygon_style = setAreaStyle(desa, false);

  if (isValidMultiPolygonPath(desa_path) || isValidPolygonPath(desa_path)) {
    const point_style = stylePointLogo(favico_desa);
    if (desa.lng && desa.lat) {
      marker_desa.push(turf.point([desa.lng, desa.lat], { content: desa, style: L.icon(point_style) }));
    }

    // Use global safeTurfPolygon helper to build a Turf polygon safely

    // Check if it's a MultiPolygon by checking the depth of the array
    if (Array.isArray(desa_path[0][0][0])) {
      desa_path.forEach(polygon => {
        const poly = safeTurfPolygon(polygon, { content: desa, style: polygon_style });
        if (poly) marker_desa.push(poly);
        else error_message += message(judul);
      });
    } else { // It's a single Polygon
      const poly = safeTurfPolygon(desa_path, { content: desa, style: polygon_style });
      if (poly) marker_desa.push(poly);
      else error_message += message(judul);
    }
  } else {    
    error_message += message(judul);
  }
}

function set_marker_desa_content(
  marker_desa,
  desa,
  judul,
  favico_desa,
  contents
) {
  var desa_path = tryParseJson(desa["path"]);
  if (!desa_path) {
    error_message += message(judul);
    return;
  }

  var jml = desa_path.length;
  var polygon_style = setAreaStyle(desa);

  content = $(contents).html();

  var point_style = stylePointLogo(favico_desa);
  if (desa["lng"]) {
    marker_desa.push(
      turf.point([desa["lng"], desa["lat"]], {
        name: "kantor_desa",
        content: "Kantor Desa",
        style: L.icon(point_style),
      })
    );
  }

  for (var x = 0; x < jml; x++) {
    const poly = safeTurfPolygon(desa_path[x], { content: content, style: polygon_style });
    if (poly) {
      marker_desa.push(poly);
    } else {
      error_message += message(judul);
    }
  }
}

function set_marker_persil_content(
  marker,
  daftar_path,
  judul,
  nama_wil,
  contents,
  favico_desa
) {
  var daftar = daftar_path == "null" ? new Array() : JSON.parse(daftar_path);
  var jml = daftar.length;
  var jml_path;

  for (var x = 0; x < jml; x++) {
    if (daftar[x].path) {
      var data = daftar[x];
      daftar[x].path = JSON.parse(daftar[x].path);
      jml_path = daftar[x].path[0].length;
      for (var y = 0; y < jml_path; y++) {
        daftar[x].path[0][y].reverse();
      }

      content = `
        <div class="persil">
          <h4>Leter C-Desa </h4>
          <h4><b>Nomor ${data.nomor}</b> </h4> 
          <hr>
          <table>
            <tbody>
              <tr>
                <td>Nama Pemilik Tanah </td>
                <td> : </td>
                <td> ${data.nama_kepemilikan} </td>
              </tr>
              <tr>
                <td>Kelas Tanah</td>
                <td> : </td>
                <td> ${data.kode} </td>
              </tr>
              <tr>
                <td>Lokasi</td>
                <td> : </td>
                <td> ${data.alamat} </td>
              </tr>
          </tbody></table>
        </div>
      `;
      var label = L.tooltip({
        permanent: true,
        direction: "center",
        className: "text",
      }).setContent(judul + " " + daftar[x][nama_wil]);

      var point_style = {
        iconSize: [1, 1],
        iconAnchor: [0.5, 0.5],
        labelAnchor: [0.3, 0],
        iconUrl: favico_desa,
      };

      var marker_style = setAreaStyle(daftar[x], false);
      daftar[x].path[0].push(daftar[x].path[0][0]);
      const poly = safeTurfPolygon(daftar[x].path, {
        name: judul,
        content: content,
        style: marker_style,
      });
      if (poly) {
        marker.push(poly);
      } else {
        error_message += message(judul);
      }
    }
  }
}

function set_marker_content(
  marker,
  daftar_path,
  judul,
  nama_wil,
  contents,
  favico_desa
) {
  var daftar = JSON.parse(daftar_path);
  var jml = daftar.length;
  var jml_path;
  for (var x = 0; x < jml; x++) {
    if (daftar[x].path) {
      daftar[x].path = JSON.parse(daftar[x].path);
      jml_path = daftar[x].path[0].length;
      for (var y = 0; y < jml_path; y++) {
        daftar[x].path[0][y].reverse();
      }
      content = $(contents + x).html();
      var marker_style = setAreaStyle(daftar[x], false);
      daftar[x].path[0].push(daftar[x].path[0][0]);
      const poly = safeTurfPolygon(daftar[x].path, {
        name: judul,
        content: content,
        style: marker_style,
      });
      if (poly) {
        marker.push(poly);
      } else {
        error_message += message(judul);
      }
    }
  }
}

function set_marker_multi_content(
  marker,
  daftar_path,
  judul,
  nama_wil,
  contents,
  favico_desa
) {
  var daftar = JSON.parse(daftar_path);
  var jml = daftar.length;
  var jml_path;
  for (var x = 0; x < jml; x++) {
    if (daftar[x].path) {
      daftar[x].path = JSON.parse(daftar[x].path);
      var jml_path_x = daftar[x].path.length;
      for (var a = 0; a < jml_path_x; a++) {
        for (var b = 0; b < daftar[x].path[a].length; b++) {
          jml_path = daftar[x].path[a][0].length;
          for (var z = 0; z < jml_path; z++) {
            daftar[x].path[a][0][z].reverse();
          }
          content = $(contents + x).html();
          var marker_style = setAreaStyle(daftar[x], false);
          daftar[x].path[a][0].push(daftar[x].path[a][0][0]);
          const poly = safeTurfPolygon(daftar[x].path[a], {
            name: judul,
            content: content,
            style: marker_style,
          });
          if (poly) {
            marker.push(poly);
          } else {
            error_message += message(judul);
          }
        }
      }
    }
  }
}

function getBaseLayers(peta, access_token, jenis_peta) {
  var isValid = validateTokenMapbox(access_token);

  var defaultLayer = L.tileLayer.provider("OpenStreetMap.Mapnik", {
    attribution:
      '<a href="https://openstreetmap.org/copyright">© OpenStreetMap</a> | <a href="https://github.com/OpenSID/OpenSID">OpenSID</a>',
  });

  var OpenStreetMap = L.tileLayer.provider("OpenStreetMap.HOT", {
    attribution:
      '<a href="https://openstreetmap.org/copyright">© OpenStreetMap</a> | <a href="https://github.com/OpenSID/OpenSID">OpenSID</a>',
  });

  let mbGLstr, mbGLsat, mbGLstrsat;
  let baseLayers;

  if (isValid && access_token) {
    mbGLstr = L.mapboxGL({
      accessToken: access_token,
      style: "mapbox://styles/mapbox/streets-v11",
      attribution:
        '<a href="https://www.mapbox.com/about/maps">© Mapbox</a> | <a href="https://github.com/OpenSID/OpenSID">OpenSID</a>',
    });

    mbGLsat = L.mapboxGL({
      accessToken: access_token,
      style: "mapbox://styles/mapbox/satellite-v9",
      attribution:
        '<a href="https://www.mapbox.com/about/maps">© Mapbox</a> | <a href="https://github.com/OpenSID/OpenSID">OpenSID</a>',
    });

    mbGLstrsat = L.mapboxGL({
      accessToken: access_token,
      style: "mapbox://styles/mapbox/satellite-streets-v11",
      attribution:
        '<a href="https://www.mapbox.com/about/maps">© Mapbox</a> | <a href="https://github.com/OpenSID/OpenSID">OpenSID</a>',
    });

    baseLayers = {
      OpenStreetMap: defaultLayer,
      "OpenStreetMap H.O.T.": OpenStreetMap,
      "Mapbox Streets": mbGLstr,
      "Mapbox Satellite": mbGLsat,
      "Mapbox Satellite-Street": mbGLstrsat,
    };
  } else {
    if (typeof Swal !== "undefined" && typeof Swal.fire === "function") {
      Swal.fire({
        toast: true,
        position: "top-end",
        icon: "warning",
        title: "Token Mapbox Tidak Valid",
        text: "Peta akan menggunakan OpenStreetMap",
        showConfirmButton: false,
        timer: 5000,
        timerProgressBar: true,
      });
    }

    mbGLstr = L.tileLayer.provider("OpenStreetMap.Mapnik", {
      attribution:
        '<a href="https://openstreetmap.org/copyright">© OpenStreetMap</a> | <a href="https://github.com/OpenSID/OpenSID">OpenSID</a>',
    });
    mbGLsat = L.tileLayer.provider("OpenStreetMap.Mapnik", {
      attribution:
        '<a href="https://openstreetmap.org/copyright">© OpenStreetMap</a> | <a href="https://github.com/OpenSID/OpenSID">OpenSID</a>',
    });
    mbGLstrsat = L.tileLayer.provider("OpenStreetMap.Mapnik", {
      attribution:
        '<a href="https://openstreetmap.org/copyright">© OpenStreetMap</a> | <a href="https://github.com/OpenSID/OpenSID">OpenSID</a>',
    });

    baseLayers = {
      OpenStreetMap: defaultLayer,
      "OpenStreetMap H.O.T.": OpenStreetMap,
    };
  }

  switch (jenis_peta) {
    case "1":
      defaultLayer.addTo(peta);
      break;
    case "2":
      OpenStreetMap.addTo(peta);
      break;
    case "3":
      mbGLstr.addTo(peta);
      break;
    case "4":
      mbGLsat.addTo(peta);
      break;
    default:
      mbGLstrsat.addTo(peta);
  }

  return baseLayers;
}

function validateTokenMapbox(access_token) {
  var isValid = false;

  $.ajax({
    url: `https://api.mapbox.com/styles/v1/mapbox/streets-v11?access_token=${access_token}`,
    type: 'GET',
    async: false,
    success: function (response) {
      isValid = true; // Token is valid
    },
    error: function (xhr, status, error) {
      console.error("Error validating token:", error);
    }
  });

  return isValid;
}

function wilayah_property(set_marker, set_content = false, tampil_luas = 0) {
  const showMeasurements = false;

  const wilayah_property = L.geoJSON(turf.featureCollection(set_marker), {
    pmIgnore: true,
    showMeasurements: showMeasurements,
    measurementOptions: {
      showSegmentLength: false,
    },
    onEachFeature: function (feature, layer) {
      var content = feature.properties.content;

      if (feature.geometry.type.includes("Polygon") && tampil_luas == "1" && typeof turf !== 'undefined') {
        var measurementContent = setMeasurementContent(feature);

        if (typeof content === 'object' && content !== null && content.nama_desa) {
            content = `<h4>Wilayah ${content.nama_desa}</h4><hr>${measurementContent}`;
        } else if (typeof content === 'string' && (content.includes('<div') || content.includes('<table'))) {
            content += `<hr>${measurementContent}`;
        } else if (typeof content === 'string') {
            content = `<h4>${content}</h4><hr>${measurementContent}`;
        }
      }

      if (feature.properties.name === "kantor_desa") {
        layer.bindPopup(feature.properties.content, {
          className: "kantor_desa",
        });
      } else if (set_content === true) {
        layer.bindPopup(content);
      }
      layer.bindTooltip(content, {
        sticky: true,
        direction: "top",
      });
    },
    style: function (feature) {
      if (feature.properties.style) {
        return feature.properties.style;
      }
    },
    pointToLayer: function (feature, latlng) {
      if (feature.properties.style) {
        return L.marker(latlng, { icon: feature.properties.style });
      } else {
        return L.marker(latlng);
      }
    },
  });

  return wilayah_property;
}


function overlayWil(
  marker_desa,
  marker_dusun,
  marker_rw,
  marker_rt,
  sebutan_desa,
  sebutan_dusun,
  set_content = false,
  tampil_luas
) {
  var poligon_wil_desa = wilayah_property(marker_desa, set_content, tampil_luas);
  var poligon_wil_dusun = wilayah_property(marker_dusun, set_content, tampil_luas);
  var poligon_wil_rw = wilayah_property(marker_rw, set_content, tampil_luas);
  var poligon_wil_rt = wilayah_property(marker_rt, set_content, tampil_luas);

  var peta_desa = "Peta Wilayah " + sebutan_desa;
  var peta_dusun = "Peta Wilayah " + sebutan_dusun;
  var overlayLayers = new Object();
  if (marker_desa.length > 0) overlayLayers[peta_desa] = poligon_wil_desa;
  overlayLayers[peta_dusun] = poligon_wil_dusun;
  overlayLayers["Peta Wilayah RW"] = poligon_wil_rw;
  overlayLayers["Peta Wilayah RT"] = poligon_wil_rt;

  return overlayLayers;
}

function getLatLong(x, y) {
  var hasil;
  if (x == "Rectangle" || x == "Line" || x == "Poly") {
    hasil = JSON.stringify(y._latlngs);
  } else if (x == "multi") {
    hasil = JSON.stringify(y);
  } else {
    hasil = JSON.stringify(y._latlng);
  }

  hasil = hasil
    .replace(/\}/g, "]")
    .replace(/(\{)/g, "[")
    .replace(/(\"lat\"\:|\"lng\"\:)/g, "")
    .replace(/(\"alt\"\:)/g, "")
    .replace(/(\"ele\"\:)/g, "");

  return hasil;
}

function stylePointLogo(url) {
  var style = {
    iconSize: [32, 32],
    iconAnchor: [16, 32],
    popupAnchor: [0, -28],
    iconUrl: url,
  };
  return style;
}

function editToolbarPoly() {
  var options = {
    position: "topright", // toolbar position, options are 'topleft', 'topright', 'bottomleft', 'bottomright'
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

function editToolbarLine() {
  var options = {
    position: "topright", // toolbar position, options are 'topleft', 'topright', 'bottomleft', 'bottomright'
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

function styleGpx() {
  var style = {
    color: "red",
    opacity: 1.0,
    fillOpacity: 1.0,
    weight: 3,
    clickable: true,
  };
  return style;
}

function eximGpxRegion(layerpeta, multi = false) {
  L.Control.FileLayerLoad.LABEL =
    '<img class="icon-map" src="' +
    BASE_URL +
    'assets/images/gpx.png" alt="file icon"/>';
  L.Control.FileLayerLoad.TITLE = "Impor GPX/KML";

  const controlGpxPoly = L.Control.fileLayerLoad({
    addToMap: true,
    formats: [".gpx", ".kml"],
    fitBounds: true,
    layerOptions: {
      pointToLayer: function (data, latlng) {
        return L.marker(latlng);
      },
    },
  });
  controlGpxPoly.addTo(layerpeta);

  controlGpxPoly.loader.on("data:loaded", function (e) {    
    var type = e.layerType;
    var layer = e.layer;
    var coords = [];
    var geojson = turf.flip(layer.toGeoJSON());
    var shape_for_db = JSON.stringify(geojson);
    var polygon = L.geoJson(JSON.parse(shape_for_db), {
      pointToLayer: function (feature, latlng) {
        return L.marker(latlng);
      },
      onEachFeature: function (feature, layer) {
        coords.push(feature.geometry.coordinates);
      },
    }).addTo(layerpeta);

    var path = get_path_import(coords, multi);
    
    const pathJson = JSON.parse(path)
    if (isValidMultiPolygonPath(pathJson) || isValidPolygonPath(pathJson)){
      document.getElementById("path").value = path;
      controlGpxPoly.options.fitBounds = true;
    } else {
      controlGpxPoly.options.fitBounds = false;
      document.getElementById("path").value = "";
      _error('Peta tidak valid')
    }
  });

  return controlGpxPoly;
}

function eximGpxPoint(layerpeta) {
  L.Control.FileLayerLoad.LABEL =
    '<img class="icon-map" src="' +
    BASE_URL +
    'assets/images/gpx.png" alt="file icon"/>';
  L.Control.FileLayerLoad.TITLE = "Impor GPX/KML";

  controlGpxPoint = L.Control.fileLayerLoad({
    addToMap: false,
    formats: [".gpx", ".kml"],
    fitBounds: false,
    layerOptions: {
      pointToLayer: function (data, latlng) {
        layerpeta.eachLayer(function (layer) {
          if (layer instanceof L.Marker) {
            layer.remove();
          }
        });
        return L.marker(latlng);
      },
    },
  });
  controlGpxPoint.addTo(layerpeta);

  controlGpxPoint.loader.on("data:loaded", function (e) {
    var type = e.layerType;
    var layer = e.layer;
    var coords = [];
    var geojson = layer.toGeoJSON();
    var shape_for_db = JSON.stringify(geojson);
    let clearGPX = true;
    L.geoJson(JSON.parse(shape_for_db), {
      pointToLayer: function (feature, latlng) {
        return L.marker(latlng);
      },
      onEachFeature: function (feature, layer) {
        if (feature.geometry.type == "Point") {
          coords.push(feature.geometry.coordinates);
          layerpeta.setView([coords[0][1], coords[0][0]], layerpeta.getZoom());
        } else {
          clearGPX = false;
          _error("Pilih file GPX dengan tipe Point");
        }
      },
    }).addTo(layerpeta);

    if(coords.length > 0 && clearGPX){
      _success("Berhasil memuat GPX");
    }

    document.getElementById("lat").value = coords[0][1];
    document.getElementById("lng").value = coords[0][0];

  });

  return controlGpxPoint;
}

function eximShp(layerpeta, multi = false) {
  L.Control.Shapefile = L.Control.extend({
    onAdd: function (map) {
      var thisControl = this;

      var controlDiv = L.DomUtil.create(
        "div",
        "leaflet-control-zoom leaflet-bar leaflet-control leaflet-control-command"
      );

      // Create the leaflet control.
      var controlUI = L.DomUtil.create(
        "div",
        "leaflet-control-command-interior",
        controlDiv
      );

      // Create the form inside of the leaflet control.
      var form = L.DomUtil.create(
        "form",
        "leaflet-control-command-form",
        controlUI
      );
      form.action = "";
      form.method = "post";
      form.enctype = "multipart/form-data";

      // Create the input file element.
      var input = L.DomUtil.create(
        "input",
        "leaflet-control-command-form-input",
        form
      );
      input.id = "file";
      input.type = "file";
      input.accept = ".zip";
      input.name = "uploadFile";
      input.style.display = "none";

      L.DomEvent.addListener(form, "click", function () {
        document.getElementById("file").click();
      }).addListener(input, "change", function () {
        var input = document.getElementById("file");
        if (!input.files[0]) {
          _error("Pilih file shapefile dalam format .zip");
        } else {
          file = input.files[0];
          fr = new FileReader();
          fr.onload = receiveBinary;
          fr.readAsArrayBuffer(file);
        }

        function receiveBinary() {
          geojson = fr.result;
          var shpfile = new L.Shapefile(geojson).addTo(map);

          shpfile.once("data:loaded", function (e) {
            var type = e.layerType;
            var layer = e.layer;
            var coords = [];
            var geojson = turf.flip(shpfile.toGeoJSON());
            var shape_for_db = JSON.stringify(geojson);

            var polygon = L.geoJson(JSON.parse(shape_for_db), {
              pointToLayer: function (feature, latlng) {
                return L.circleMarker(latlng, { style: style });
              },
              onEachFeature: function (feature, layer) {
                coords.push(feature.geometry.coordinates);
              },
            });

            var jml = coords[0].length;
            for (var x = 0; x < jml; x++) {
              if (coords[0][x].length > 2) {
                coords[0][x].pop();
              }
            }

            var path = get_path_import(coords, multi);

            if (multi == true) {
              coords = new Array(coords);
            }

            const pathJson = JSON.parse(path)
            if (isValidMultiPolygonPath(pathJson) || isValidPolygonPath(pathJson)){
              document.getElementById("path").value = path;
              layerpeta.fitBounds(shpfile.getBounds());
            }else {
              document.getElementById("path").value = "";
              _error('Peta tidak valid')
            }
          });
        }
      });

      controlUI.title = "Impor Shapefile (.Zip)";

      return controlDiv;
    },
  });

  L.control.shapefile = function (opts) {
    return new L.Control.Shapefile(opts);
  };

  L.control.shapefile({ position: "topleft" }).addTo(layerpeta);

  return eximShp;
}

function geoLocation(layerpeta) {
  var lc = L.control
    .locate({
      drawCircle: false,
      icon: "fa fa-map-marker",
      locateOptions: { enableHighAccuracy: true },
      strings: {
        title: "Lokasi Saya",
        popup: "Anda berada di sekitar {distance} {unit} dari titik ini",
      },
    })
    .addTo(layerpeta);

  layerpeta.on("locationfound", function (e) {
    layerpeta.setView(e.latlng);
  });

  layerpeta
    .on("startfollowing", function () {
      layerpeta.on("dragstart", lc._stopFollowing, lc);
    })
    .on("stopfollowing", function () {
      layerpeta.off("dragstart", lc._stopFollowing, lc);
    });
  return lc;
}

function hapusPeta(layerpeta) {
  layerpeta.on("pm:globalremovalmodetoggled", function (e) {
    document.getElementById("path").value = "";
  });
  return hapusPeta;
}

function hapuslayer(layerpeta) {
  layerpeta.on("pm:remove", function (e) {
    var type = e.layerType;
    var layer = e.layer;
    var latLngs;

    // set value setelah create polygon
    var last_path = document.getElementById("path").value;
    var new_path = getLatLong("Poly", layer).toString();
    last_path = last_path
      .replace(new_path, "")
      .replace(",,", ",")
      .replace("[,", "[")
      .replace(",]", "]");
    document.getElementById("path").value = last_path;
    document.getElementById("zoom").value = layerpeta.getZoom();
  });

  return hapusPeta;
}

function updateZoom(layerpeta) {
  layerpeta.on("zoomend", function (e) {
    document.getElementById("zoom").value = layerpeta.getZoom();
  });
  return updateZoom;
}

function addPetaPoly(layerpeta) {
  layerpeta.on("pm:create", function (e) {
    var type = e.layerType;
    var layer = e.layer;
    var latLngs;

    if (type === "circle") {
      latLngs = layer.getLatLng();
    } else latLngs = layer.getLatLngs();

    var p = latLngs;
    var polygon = L.polygon(p, {
      color: "#A9AAAA",
      weight: 4,
      opacity: 1,
      showMeasurements: true,
      measurementOptions: { showSegmentLength: false },
    }).addTo(layerpeta);

    polygon.on("pm:edit", function (e) {
      document.getElementById("path").value = getLatLong(
        "Poly",
        e.target
      ).toString();
      document.getElementById("zoom").value = layerpeta.getZoom();
    });

    layerpeta.fitBounds(polygon.getBounds());

    // set value setelah create polygon
    document.getElementById("path").value = getLatLong(
      "Poly",
      layer
    ).toString();
    document.getElementById("zoom").value = layerpeta.getZoom();
  });
  return addPetaPoly;
}

function addPetaLine(layerpeta, jenis, tebal, ) {
  var jenis = jenis ?? "solid";
  var tebal = tebal ?? 1;
  var warna = warna ?? "#A9AAAA";

  layerpeta.on("pm:create", function (e) {
    var type = e.layerType;
    var layer = e.layer;
    var latLngs;

    if (type === "circle") {
      latLngs = layer.getLatLng();
    } else latLngs = layer.getLatLngs();

    var p = latLngs;
    var polygon = L.polyline(p, {
      color: warna,
      weight: tebal,
      opacity: 1,
      dashArray: jenis_garis(jenis),
      showMeasurements: true,
      measurementOptions: { showSegmentLength: false },
    }).addTo(layerpeta);

    polygon.on("pm:edit", function (e) {
      document.getElementById("path").value = getLatLong(
        "Line",
        e.target
      ).toString();
    });

    layerpeta.fitBounds(polygon.getBounds());

    // set value setelah create polygon
    document.getElementById("path").value = getLatLong(
      "Line",
      layer
    ).toString();
  });
  return addPetaLine;
}

function old_value(id_path) {
  return layers[id_path];
}

function addPetaMultipoly(layerpeta) {
  layerpeta.on("pm:create", function (e) {
    var type = e.layerType;
    var layer = e.layer;
    var latLngs;

    // set value setelah create polygon
    if (document.getElementById("path").value == "") {
      document.getElementById("path").value = "[]";
    }

    var last_path = JSON.parse(document.getElementById("path").value);
    var new_path = JSON.parse(getLatLong("Poly", layer).toString());
    last_path.push(new_path); // gabungkan value lama dengan yang baru

    e.layer.on("pm:edit", function (f) {
      var id_path = f.target._leaflet_id;
      var _path = new Array();

      for (i in layerpeta._layers) {
        if (layerpeta._layers[i]._path != undefined && layers[i]) {
          try {
            _path.push(layerpeta._layers[i]._latlngs);
          } catch (e) {
            _error("problem with " + e + layerpeta._layers[i]);
          }
        }
      }

      var new_path = getLatLong("multi", _path).toString();
      document.getElementById("path").value = new_path;
      document.getElementById("zoom").value = layerpeta.getZoom();
    });
    layers[e.layer._leaflet_id] = last_path[0];

    document.getElementById("path").value = JSON.stringify(last_path);
    document.getElementById("zoom").value = layerpeta.getZoom();
  });
  return addPetaPoly;
}

function showCurrentPolygon(wilayah, layerpeta, data_wilayah, tampil_luas, nama_wilayah) {
  if (!isValidPolygonPath(wilayah)) {
    return false;
  }

  var poligon_wilayah_style = setAreaStyle(data_wilayah, true);
  var daerah_wilayah = wilayah;
  daerah_wilayah[0].push(daerah_wilayah[0][0]); // tutup polygon

  // Tambahkan style warna dari parameter
  var poligon_wilayah = L.polygon(wilayah, poligon_wilayah_style).addTo(layerpeta);

  var feature = poligon_wilayah.toGeoJSON();
  var content = nama_wilayah;

  if (tampil_luas === "1" && typeof turf !== 'undefined') {
    var measurementContent = setMeasurementContent(feature);

    content = `<h4>${content}</h4><hr>${measurementContent}`;
  }

  poligon_wilayah.bindPopup(content);
  poligon_wilayah.bindTooltip(nama_wilayah, {
    sticky: true,
    direction: "top",
  });

  poligon_wilayah.on("pm:edit", function (e) {
    document.getElementById("path").value = getLatLong("Poly", e.target).toString();
    document.getElementById("zoom").value = layerpeta.getZoom();
  });

  var layer = poligon_wilayah;
  var geojson = layer.toGeoJSON();
  var shape_for_db = JSON.stringify(geojson);
  var gpxData = togpx(JSON.parse(shape_for_db));

  $("#exportGPX").on("click", function (event) {
    data = "data:text/xml;charset=utf-8," + encodeURIComponent(gpxData);
    $(this).attr({
      href: data,
      target: "_blank",
    });
  });

  layerpeta.fitBounds(poligon_wilayah.getBounds());

  document.getElementById("path").value = getLatLong("Poly", layer).toString();
  document.getElementById("zoom").value = layerpeta.getZoom();

  return true;
}


function showCurrentMultiPolygon(wilayah, layerpeta, data_wilayah, tampil_luas, nama_wilayah) {
  if (!isValidMultiPolygonPath(wilayah) && !isValidPolygonPath(wilayah)) {
    return false;
  }

  var area_wilayah = JSON.parse(JSON.stringify(wilayah));
  var bounds = [];

  var path = [];
  for (var i = 0; i < wilayah.length; i++) {
    var daerah_wilayah = area_wilayah[i];
    daerah_wilayah[0].push(daerah_wilayah[0][0]);

    var poligon_wilayah_style = setAreaStyle(data_wilayah, true);
    var poligon_wilayah = L.polygon(daerah_wilayah, poligon_wilayah_style).addTo(layerpeta);

    var feature = poligon_wilayah.toGeoJSON();
    var content = nama_wilayah;

    if (tampil_luas === "1" && typeof turf !== 'undefined') {
      var measurementContent = setMeasurementContent(feature);

      content = `<h5 class="text-center">${content}</h5><hr>${measurementContent}`;
    }

    poligon_wilayah.bindPopup(content);
    poligon_wilayah.bindTooltip(nama_wilayah, {
      sticky: true,
      direction: "top",
    });

    layers[poligon_wilayah._leaflet_id] = wilayah[i];
    poligon_wilayah.on("pm:edit", function (e) {
      var old_path = getLatLong("Poly", {
        _latlngs: layers[e.target._leaflet_id],
      }).toString();
      var new_path = getLatLong("Poly", e.target).toString();
      var value_path = document.getElementById("path").value;

      document.getElementById("path").value = value_path.replace(
        old_path,
        new_path
      );
      document.getElementById("zoom").value = layerpeta.getZoom();
      layers[e.target._leaflet_id] = JSON.parse(
        JSON.stringify(e.target._latlngs)
      );
    });

    var geojson = poligon_wilayah.toGeoJSON();
    var shape_for_db = JSON.stringify(geojson);
    var gpxData = togpx(JSON.parse(shape_for_db));

    $("#exportGPX").on("click", function (event) {
      var data = "data:text/xml;charset=utf-8," + encodeURIComponent(gpxData);
      $(this).attr({
        href: data,
        target: "_blank",
      });
    });

    bounds.push(poligon_wilayah.getBounds());
    path.push(poligon_wilayah._latlngs);
  }

  layerpeta.fitBounds(bounds);
  document.getElementById("path").value = getLatLong("multi", path).toString();
  document.getElementById("zoom").value = layerpeta.getZoom();
  return true;
}


function showCurrentPoint(posisi1, layerpeta, mode = true) {
  var lokasi_kantor = L.marker(posisi1, { draggable: mode }).addTo(layerpeta);
  lokasi_kantor.on("dragend", function (e) {
    $("#lat").val(e.target._latlng.lat);
    $("#lng").val(e.target._latlng.lng);
    $("#map_tipe").val("HYBRID");
    $("#zoom").val(layerpeta.getZoom());
  });

  layerpeta.on("zoomstart zoomend", function (e) {
    $("#zoom").val(layerpeta.getZoom());
  });

  var geojson = lokasi_kantor.toGeoJSON();
  var shape_for_db = JSON.stringify(geojson);
  var gpxData = togpx(JSON.parse(shape_for_db));

  $("#exportGPX").on("click", function (event) {
    data = "data:text/xml;charset=utf-8," + encodeURIComponent(gpxData);
    $(this).attr({
      href: data,
      target: "_blank",
    });
  });

  if ($("a[title='Lokasi Saya']").length == 0) {
    var lc = L.control
    .locate({
      drawCircle: false,
      icon: "fa fa-map-marker",
      strings: {
        title: "Lokasi Saya",
        locateOptions: { enableHighAccuracy: true },
        popup: "Anda berada disekitar {distance} {unit} dari titik ini",
      },
    })
    .addTo(layerpeta);
  }

  layerpeta.on("locationfound", function (e) {
    $("#lat").val(e.latlng.lat);
    $("#lng").val(e.latlng.lng);
    lokasi_kantor.setLatLng(e.latlng);
    layerpeta.setView(e.latlng);
  });

  layerpeta
    .on("startfollowing", function () {
      layerpeta.on("dragstart", lc._stopFollowing, lc);
    })
    .on("stopfollowing", function () {
      layerpeta.off("dragstart", lc._stopFollowing, lc);
    });

  return showCurrentPoint;
}

function showCurrentLine(wilayah, layerpeta, jenis, tebal, warna, tampil_luas) {
  var jenis = jenis ?? "solid";
  var tebal = tebal ?? 1;
  var warna = warna ?? "#A9AAAA";

  var poligon_wilayah = L.polyline(wilayah, {
    color: warna,
    weight: tebal,
    opacity: 1,
    dashArray: jenis_garis(jenis),
    showMeasurements: true,
    measurementOptions: { showSegmentLength: false },
  }).addTo(layerpeta);

  luas(poligon_wilayah, tampil_luas);

  poligon_wilayah.on("pm:edit", function (e) {
    document.getElementById("path").value = getLatLong(
      "Line",
      e.target
    ).toString();
  });

  var layer = poligon_wilayah;
  var geojson = layer.toGeoJSON();
  var shape_for_db = JSON.stringify(geojson);
  var gpxData = togpx(JSON.parse(shape_for_db));

  $("#exportGPX").on("click", function (event) {
    data = "data:text/xml;charset=utf-8," + encodeURIComponent(gpxData);
    $(this).attr({
      href: data,
      target: "_blank",
    });
  });

  layerpeta.fitBounds(poligon_wilayah.getBounds());

  // set value setelah create polygon
  document.getElementById("path").value = getLatLong("Line", layer).toString();

  return showCurrentLine;
}

function showCurrentArea(wilayah, layerpeta, tampil_luas, nama_wilayah = 'Area') {
  if (!isValidPolygonPath(wilayah)) {
    return false;
  }

  var daerah_wilayah = wilayah;
  daerah_wilayah[0].push(daerah_wilayah[0][0]);
  var poligon_wilayah = L.polygon(wilayah, {
    showMeasurements: false,
    measurementOptions: { showSegmentLength: false },
  }).addTo(layerpeta);

  var feature = poligon_wilayah.toGeoJSON();
  var content = nama_wilayah;

  if (tampil_luas === "1" && typeof turf !== 'undefined') {
    var measurementContent = setMeasurementContent(feature);
    content = `<h4>${content}</h4><hr>${measurementContent}`;
  }

  poligon_wilayah.bindPopup(content);
  poligon_wilayah.bindTooltip(nama_wilayah, {
    sticky: true,
    direction: "top",
  });

  poligon_wilayah.on("pm:edit", function (e) {
    document.getElementById("path").value = getLatLong(
      "Poly",
      e.target
    ).toString();
  });

  var layer = poligon_wilayah;
  var geojson = layer.toGeoJSON();
  var shape_for_db = JSON.stringify(geojson);
  var gpxData = togpx(JSON.parse(shape_for_db));

  $("#exportGPX").on("click", function (event) {
    data = "data:text/xml;charset=utf-8," + encodeURIComponent(gpxData);
    $(this).attr({
      href: data,
      target: "_blank",
    });
  });

  layerpeta.fitBounds(poligon_wilayah.getBounds());

  // set value setelah create polygon
  document.getElementById("path").value = getLatLong("Poly", layer).toString();

  return showCurrentArea;
}

function setMarkerCustom(marker, layercustom, tampil_luas) {
  if (marker.length != 0) {
    if (tampil_luas == "1") {
      var geojson = L.geoJSON(turf.featureCollection(marker), {
        pmIgnore: true,
        showMeasurements: true,
        measurementOptions: {
          showSegmentLength: false,
        },
        onEachFeature: function (feature, layer) {
          layer.bindPopup(feature.properties.content);

          // Jika ini adalah jalan yang memiliki nama, tampilkan sebagai label permanen.
          if (feature.properties.showLabel && feature.properties.nama_jalan) {
            layer.bindTooltip(feature.properties.nama_jalan, {
                permanent: true, // Selalu terlihat
                direction: 'center',
                className: 'road-label' // Class untuk styling kustom jika perlu
            });
          } else {
            // Untuk fitur lain, gunakan tooltip standar (muncul saat hover).
            layer.bindTooltip(feature.properties.content, {
              sticky: true,
              direction: "top",
            });
          }
        },
        style: function (feature) {
          if (feature.properties.style) {
            return feature.properties.style;
          }
        },
        pointToLayer: function (feature, latlng) {
          if (feature.properties.style) {
            return L.marker(latlng, { icon: feature.properties.style });
          } else return L.marker(latlng);
        },
      });
    } else {
      var geojson = L.geoJSON(turf.featureCollection(marker), {
        pmIgnore: true,
        showMeasurements: false,
        measurementOptions: {
          showSegmentLength: false,
        },
        onEachFeature: function (feature, layer) {
          layer.bindPopup(feature.properties.content);

          // Jika ini adalah jalan yang memiliki nama, tampilkan sebagai label permanen.
          if (feature.properties.showLabel && feature.properties.nama_jalan) {
            layer.bindTooltip(feature.properties.nama_jalan, {
                permanent: true, // Selalu terlihat
                direction: 'center',
                className: 'road-label' // Class untuk styling kustom jika perlu
            });
          } else {
            // Untuk fitur lain, gunakan tooltip standar (muncul saat hover).
            layer.bindTooltip(feature.properties.content, {
              sticky: true,
              direction: "top",
            });
          }
        },
        style: function (feature) {
          if (feature.properties.style) {
            return feature.properties.style;
          }
        },
        pointToLayer: function (feature, latlng) {
          if (feature.properties.style) {
            return L.marker(latlng, {
              icon: feature.properties.style,
            });
          } else return L.marker(latlng);
        },
      });
    }

    layercustom.addLayer(geojson);
  }

  return setMarkerCustom;
}

/**
 * Setup TextPath untuk menampilkan nama jalan sepanjang garis
 * @param {Object} feature - GeoJSON feature dengan properties nama jalan
 * @param {Object} layer - Leaflet layer (polyline/linestring)
 */
function setupRoadNameTextPath(feature, layer) {
  if (
    feature.properties.showLabel
    && feature.properties.nama_jalan
    && layer.setText && typeof layer.setText === 'function'
  ) {
    layer.on('add', function() {
      try {
        // Delay to ensure layer is fully initialized
        setTimeout(() => {
          if (layer._map) { // Check if layer is still on map
            layer.setText(feature.properties.nama_jalan, {
              repeat: false,
              center: true,
              below: false,
              attributes: {
                'fill': '#2c3e50',
                'font-weight': 'bold',
                'font-size': '12px',
                'font-family': 'Arial, sans-serif'
              }
            });
          }
        }, 100);
      } catch (e) {
        console.warn('Error setting text path for road:', feature.properties.nama_jalan, e);
      }
    });

    layer.on('remove', function() {
      try {
        layer.setText(null);
      } catch (e) {
        console.warn('Error removing text path for road:', feature.properties.nama_jalan, e);
      }
    });
  }
}

function setMarkerCluster(marker, markersList, markers, tampil_luas) {
  if (marker.length != 0) {
    if (tampil_luas == "1") {
      var geojson = L.geoJSON(turf.featureCollection(marker), {
        pmIgnore: true,
        showMeasurements: true,
        measurementOptions: {
          showSegmentLength: false,
        },
        onEachFeature: function (feature, layer) {
          layer.bindPopup(feature.properties.content);
          layer.bindTooltip(feature.properties.content);
        },
        style: function (feature) {
          if (feature.properties.style) {
            return feature.properties.style;
          }
        },
        pointToLayer: function (feature, latlng) {
          if (feature.properties.style) {
            return L.marker(latlng, {
              icon: feature.properties.style,
            });
          } else return L.marker(latlng);
        },
      });
    } else {
      var geojson = L.geoJSON(turf.featureCollection(marker), {
        pmIgnore: true,
        showMeasurements: false,
        measurementOptions: {
          showSegmentLength: false,
        },
        onEachFeature: function (feature, layer) {
          layer.bindPopup(feature.properties.content);
          layer.bindTooltip(feature.properties.content);
        },
        style: function (feature) {
          if (feature.properties.style) {
            return feature.properties.style;
          }
        },
        pointToLayer: function (feature, latlng) {
          if (feature.properties.style) {
            return L.marker(latlng, {
              icon: feature.properties.style,
            });
          } else return L.marker(latlng);
        },
      });
    }

    markersList.push(geojson);
    markers.addLayer(geojson);
  }

  return setMarkerCluster;
}

function set_marker_area(marker, daftar_path, foto_area) {
  var daftar = daftar_path == "null" ? new Array() : tryParseJson(daftar_path);
  var jml = daftar.length;
  var jml_path;
  var lokasi_gambar = foto_area;

  for (var x = 0; x < jml; x++) {
    if (daftar[x].path) {
      const path = tryParseJson(daftar[x].path);
      if (!path) {
        continue;
      }

      var area_style = setAreaStyle(daftar[x], false);
      const popUp = popUpContent(daftar[x], lokasi_gambar);

      // Cek apakah ini MultiPolygon atau Polygon tunggal.
      // MultiPolygon memiliki 4 tingkat kedalaman array: [[[[lon, lat]]]]
      // Polygon tunggal memiliki 3 tingkat kedalaman array: [[[lon, lat]]]
      const isMultiPolygon = Array.isArray(path) && Array.isArray(path[0]) && Array.isArray(path[0][0]) && Array.isArray(path[0][0][0]);

      if (isMultiPolygon) {
        // Ini adalah MultiPolygon, loop setiap poligon di dalamnya
        path.forEach(polygonCoords => {
          const poly = safeTurfPolygon(polygonCoords, {
            content: popUp,
            style: area_style,
          });
          if (poly) {
            marker.push(poly);
          } else {
            error_message += message(daftar[x].nama || null);
          }
        });
      } else {
        // Ini diasumsikan sebagai Polygon tunggal
        const poly = safeTurfPolygon(path, {
          content: popUp,
          style: area_style,
        });
        if (poly) {
          marker.push(poly);
        } else {
          error_message += message(daftar[x].nama || null);
        }
      }
    }
  }
}

function set_marker_garis(marker, daftar_path, foto_garis) {
  var daftar = daftar_path == "null" ? new Array() : JSON.parse(daftar_path);
  var jml = daftar.length;
  var coords;
  var lengthOfCoords;
  var lokasi_gambar = foto_garis;

  for (var x = 0; x < jml; x++) {
    if (daftar[x].path) {
      daftar[x].path = JSON.parse(daftar[x].path);
      coords = daftar[x].path;
      lengthOfCoords = coords.length;
      for (i = 0; i < lengthOfCoords; i++) {
        holdLon = coords[i][0];
        coords[i][0] = coords[i][1];
        coords[i][1] = holdLon;
      }

      var garis_style = {
        stroke: true,
        opacity: 1,
        weight: daftar[x].tebal,
        color: daftar[x].color,
        dashArray: jenis_garis(daftar[x].jenis_garis),
      };

      marker.push(
        turf.lineString(coords, {
          content: popUpContent(daftar[x], lokasi_gambar),
          style: garis_style,
          nama_jalan: daftar[x].nama || '',
          showLabel: !!(daftar[x].nama && daftar[x].nama.trim()),
        })
      );
    }
  }
}

function set_marker_lokasi(marker, daftar_path, path_icon, foto_lokasi) {
  var daftar = daftar_path == "null" ? new Array() : JSON.parse(daftar_path);
  var jml = daftar.length;
  var lokasi_gambar = foto_lokasi;
  var path_foto = path_icon;
  var point_style = {
    iconSize: [32, 32],
    iconAnchor: [16, 32],
    popupAnchor: [0, -28],
  };

  for (var x = 0; x < jml; x++) {
    if (daftar[x].lat) {
      point_style.iconUrl = path_foto + daftar[x].simbol;

      marker.push(
        turf.point([daftar[x].lng, daftar[x].lat], {
          content: popUpContent(daftar[x], lokasi_gambar),
          style: L.icon(point_style),
        })
      );
    }
  }
}

function set_marker_lokasi_pembangunan(
  marker,
  daftar_path,
  path_icon,
  foto_lokasi,
  link_progress
) {
  var daftar = daftar_path == "null" ? new Array() : JSON.parse(daftar_path);
  var jml = daftar.length;
  var foto;
  var content_lokasi;
  var lokasi_gambar = foto_lokasi;

  for (var x = 0; x < jml; x++) {
    if (daftar[x].lat) {
      if (daftar[x].foto) {
        foto =
          '<img src="' +
          lokasi_gambar +
          daftar[x].foto +
          '" style=" width:300px;height:240px;border-radius:1px;-moz-border-radius:3px;-webkit-border-radius:1px;"/>';
      } else foto = "";

      content_lokasi =
        '<div id="content">' +
        '<h4><b style="color:red"><center>Kegiatan Pembangunan</center></b></h4>' +
        '<div id="bodyContent">' +
        foto +
        "</div>" +
        "<table>" +
        "<tr>" +
        '<td width="100px">Nama Kegiatan</td>' +
        '<td width="10px">:</td>' +
        '<td><b style="color:red">' +
        daftar[x].judul +
        "</b></td>" +
        "</tr>" +
        "<tr>" +
        '<td width="100px">Lokasi</td>' +
        '<td width="10px">:</td>' +
        "<td>" +
        daftar[x].alamat +
        "</td>" +
        "</tr>" +
        "<tr>" +
        '<td width="100px">Sumber Dana</td>' +
        '<td width="10px">:</td>' +
        "<td>" +
        daftar[x].sumber_dana +
        "</td>" +
        "</tr>" +
        "<tr>" +
        '<td width="100px">Anggaran</td>' +
        '<td width="10px">:</td>' +
        '<td class="rupiah">Rp. ' +
        formatRupiah(daftar[x].anggaran) +
        "</td>" +
        "</tr>" +
        "<tr>" +
        '<td width="100px">Volume</td>' +
        '<td width="10px">:</td>' +
        "<td>" +
        daftar[x].volume +
        "</td>" +
        "</tr>" +
        "<tr>" +
        '<td width="100px">Pelaksana</td>' +
        '<td width="10px">:</td>' +
        "<td>" +
        daftar[x].pelaksana_kegiatan +
        "</td>" +
        "</tr>" +
        "<tr>" +
        '<td width="100px">Tahun Anggaran</td>' +
        '<td width="10px">:</td>' +
        "<td>" +
        daftar[x].tahun_anggaran +
        "</td>" +
        "</tr>" +
        "</table>" +
        '<center><a href="' +
        link_progress +
        "/" +
        daftar[x].slug +
        '" target="_blank" class="btn btn-flat bg-red btn-sm"><i class="fa fa-info"></i> Selengkapnya</a>' +
        "</div>";

      marker.push(
        turf.point([daftar[x].lng, daftar[x].lat], {
          content: content_lokasi,
          style: L.icon({ iconSize: [16, 16], iconUrl: path_icon }),
        })
      );
    }
  }
}

//Menampilkan OverLayer Area, Garis, Lokasi
function tampilkan_layer_area_garis_lokasi(
  peta,
  daftar_path,
  daftar_garis,
  daftar_lokasi,
  path_icon,
  foto_area,
  foto_garis,
  foto_lokasi,
  tampil_luas
) {
  var marker_area = [];
  var marker_garis = [];
  var marker_lokasi = [];
  var markers = new L.MarkerClusterGroup();
  var markersList = [];

  var layer_area = L.featureGroup();
  var layer_garis = L.featureGroup();
  var layer_lokasi = L.featureGroup();

  var sebutan_desa = (typeof setting !== 'undefined' && setting?.sebutan_desa || 'desa')
    .replace(/\b\w/g, l => l.toUpperCase())
    .toLowerCase()
    .replace(/^\w/, c => c.toUpperCase());
  
  var layerCustom = {
    [`Infrastruktur ${sebutan_desa}`]: {
      "Infrastruktur (Area)": layer_area,
      "Infrastruktur (Garis)": layer_garis,
      "Infrastruktur (Lokasi)": layer_lokasi,
    },
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

  setMarkerCustom(marker_area, layer_area, tampil_luas);
  setMarkerCustom(marker_garis, layer_garis, tampil_luas);
  setMarkerCluster(marker_lokasi, markersList, markers, tampil_luas);

  peta.on("layeradd layerremove", function () {
    peta.eachLayer(function (layer) {
      if (peta.hasLayer(layer_lokasi)) {
        peta.addLayer(markers);
      } else {
        peta.removeLayer(markers);
        peta._layersMaxZoom = 19;
      }
    });
  });

  return layerCustom;
}

//Menampilkan OverLayer Area, Garis, Lokasi plus Lokasi Pembangunan
function tampilkan_layer_area_garis_lokasi_plus(
  peta,
  daftar_path,
  daftar_garis,
  daftar_lokasi,
  daftar_lokasi_pembangunan,
  path_icon,
  path_icon_pembangunan,
  foto_area,
  foto_garis,
  foto_lokasi,
  foto_lokasi_pembangunan,
  link_progress,
  daftar_persil,
  tampil_luas
) {
  var marker_area = [];
  var marker_garis = [];
  var marker_lokasi = [];
  var marker_persil = [];
  var marker_lokasi_pembangunan = [];
  var markers = new L.MarkerClusterGroup();
  var markersList = [];
  var markersP = new L.MarkerClusterGroup();
  var markersListP = [];

  var layer_area = L.featureGroup();
  var layer_garis = L.featureGroup();
  var layer_lokasi = L.featureGroup();
  var layer_lokasi_pembangunan = L.featureGroup();

  var sebutan_desa = (typeof setting !== 'undefined' && setting?.sebutan_desa || 'desa')
    .replace(/\b\w/g, l => l.toUpperCase())
    .toLowerCase()
    .replace(/^\w/, c => c.toUpperCase());

  var layerCustom = {
    [`Infrastruktur ${sebutan_desa}`]: {
      "Infrastruktur (Area)": layer_area,
      "Infrastruktur (Garis)": layer_garis,
      "Infrastruktur (Lokasi)": layer_lokasi,
      "Infrastruktur (Lokasi Pembangunan)": layer_lokasi_pembangunan,
    },
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

  //OVERLAY LOKASI PEMBANGUNAN
  if (daftar_lokasi_pembangunan) {
    set_marker_lokasi_pembangunan(
      marker_lokasi_pembangunan,
      daftar_lokasi_pembangunan,
      path_icon_pembangunan,
      foto_lokasi_pembangunan,
      link_progress
    );
  }

  //OVERLAY C-desa
  if (daftar_persil) {
    var layer_persil = L.featureGroup();
    layerCustom[`Infrastruktur ${sebutan_desa}`]["Letter C-Desa"] = layer_persil;
    set_marker_persil_content(
      marker_persil,
      daftar_persil,
      "Persil",
      "nomor",
      "#isi_popup_persil_",
      path_icon_pembangunan
    );
    setMarkerCustom(marker_persil, layer_persil, tampil_luas);
  }

  setMarkerCustom(marker_area, layer_area, tampil_luas);
  setMarkerCustom(marker_garis, layer_garis, tampil_luas);
  setMarkerCluster(marker_lokasi, markersList, markers, tampil_luas);
  setMarkerCluster(
    marker_lokasi_pembangunan,
    markersListP,
    markersP,
    tampil_luas
  );

  peta.on("layeradd layerremove", function () {
    peta.eachLayer(function (layer) {
      if (peta.hasLayer(layer_lokasi)) {
        peta.addLayer(markers);
      } else {
        peta.removeLayer(markers);
        peta._layersMaxZoom = 19;
      }
    });
  });

  peta.on("layeradd layerremove", function () {
    peta.eachLayer(function (layer) {
      if (peta.hasLayer(layer_lokasi_pembangunan)) {
        peta.addLayer(markersP);
      } else {
        peta.removeLayer(markersP);
        peta._layersMaxZoom = 19;
      }
    });
  });

  return layerCustom;
}

function clearMap(peta) {
  for (i in peta._layers) {
    if (peta._layers[i]._path != undefined) {
      try {
        peta.removeLayer(peta._layers[i]);
      } catch (e) {
        console.log("problem with " + e + peta._layers[i]);
      }
    }
  }
}

$(document).ready(function () {
  $("#modalKecil").on("show.bs.modal", function (e) {
    var link = $(e.relatedTarget);
    var title = link.data("title");
    var modal = $(this);
    modal.find(".modal-title").text(title);
    $(this).find(".fetched-data").load(link.attr("href"));
  });

  $("#modalSedang").on("show.bs.modal", function (e) {
    var link = $(e.relatedTarget);
    var title = link.data("title");
    var modal = $(this);
    modal.find(".modal-title").text(title);
    $(this).find(".fetched-data").load(link.attr("href"));
  });

  $("#modalBesar").on("show.bs.modal", function (e) {
    var link = $(e.relatedTarget);
    var title = link.data("title");
    var modal = $(this);
    modal.find(".modal-title").text(title);
    $(this).find(".fetched-data").load(link.attr("href"));
  });
  return false;
});

//Cetak Peta ke PNG
function cetakPeta(layerpeta) {
  L.control
    .browserPrint({
      documentTitle: "Peta_Wilayah",
      printModes: [
        L.control.browserPrint.mode.landscape("Landscape"),
        L.control.browserPrint.mode.portrait("Portrait"),
      ],
    })
    .addTo(layerpeta);

  L.Control.BrowserPrint.Utils.registerLayer(
    L.MarkerClusterGroup,
    "L.MarkerClusterGroup",
    function (layer, utils) {
      return layer;
    }
  );

  L.Control.BrowserPrint.Utils.registerLayer(
    L.MapboxGL,
    "L.MapboxGL",
    function (layer, utils) {
      return L.mapboxGL(layer.options);
    }
  );

  // Ensure street name labels are visible during print/export
  layerpeta.on('browser-print-start', function(e) {
    try {
      // Make sure text path labels remain visible in print
      e.printMap.eachLayer(function(layer) {
        if (layer && layer._text && layer.setText && typeof layer.setText === 'function') {
          try {
            // Re-apply text path to ensure it appears in print
            setTimeout(function() {
              if (layer._path && layer._map) {
                layer.setText(layer._text, layer._textOptions);
              }
            }, 50);
          } catch (err) {
            console.warn('Error re-applying text path on print:', err);
          }
        }
      });
    } catch (e) {
      console.warn('Error during browser print start:', e);
    }
  });

  return cetakPeta;
}

//Menambahkan legend ke peta dusun/rw/rt
function setlegendPeta(
  legenda,
  layerpeta,
  legendData,
  judul,
  nama_wil,
  judul_wil_atas
) {
  var daftar = JSON.parse(legendData);
  var div = L.DomUtil.create("div", "info legend");
  var labels = ["<strong>Legenda" + " " + " - " + " " + judul + "</strong>"];

  for (var x = 0; x < daftar.length; x++) {
    if (daftar[x].path) {
      legenda.onAdd = function (layerpeta) {
        var categories = [judul + " " + daftar[x][nama_wil]];
        if (judul === "RT") {
          var categories = [
            judul +
              " " +
              daftar[x][nama_wil] +
              " " +
              judul_wil_atas +
              " " +
              daftar[x].rw +
              " " +
              daftar[x].dusun,
          ];
        }
        if (judul === "RW") {
          var categories = [
            judul +
              " " +
              daftar[x][nama_wil] +
              " " +
              judul_wil_atas +
              " " +
              daftar[x].dusun,
          ];
        }
        for (var i = 0; i < categories.length; i++) {
          div.innerHTML += labels.push(
            '<i class="circle" style="background:' +
              daftar[x].warna +
              '"></i> ' +
              (categories[i] ? categories[i] + "<br>" : "+")
          );
        }
        div.innerHTML = labels.join("<br>");
        return div;
      };
      legenda.addTo(layerpeta);
    }
  }
  setlegendPrint(
    legenda,
    layerpeta,
    legendData,
    judul,
    nama_wil,
    judul_wil_atas
  );
  return setlegendPeta;
}

function setlegendPrint(
  legenda,
  layerpeta,
  legendData,
  judul,
  nama_wil,
  judul_wil_atas
) {
  layerpeta.on("browser-print-start", function (e) {
    var daftar = JSON.parse(legendData);
    var div = L.DomUtil.create("div", "info legend");
    var labels = ["<strong>Legenda" + " " + " - " + " " + judul + "</strong>"];

    for (var x = 0; x < daftar.length; x++) {
      if (daftar[x].path) {
        legenda.onAdd = function (layerpeta) {
          var categories = [judul + " " + daftar[x][nama_wil]];
          if (judul === "RT") {
            var categories = [
              judul +
                " " +
                daftar[x][nama_wil] +
                " " +
                judul_wil_atas +
                " " +
                daftar[x].rw +
                " " +
                daftar[x].dusun,
            ];
          }
          if (judul === "RW") {
            var categories = [
              judul +
                " " +
                daftar[x][nama_wil] +
                " " +
                judul_wil_atas +
                " " +
                daftar[x].dusun,
            ];
          }
          for (var i = 0; i < categories.length; i++) {
            div.innerHTML += labels.push(
              '<i class="circle" style="background:' +
                daftar[x].warna +
                '"></i> ' +
                (categories[i] ? categories[i] + "<br>" : "+")
            );
          }
          div.innerHTML = labels.join("<br>");
          return div;
        };
        legenda.addTo(e.printMap);
      }
    }
  });
  return setlegendPrint;
}

//Menambahkan legend ke peta desa
function setlegendPetaDesa(legenda, layerpeta, legendData, judul, nama_wil) {
  var daftar = JSON.parse(legendData["path"]);

  for (var x = 0; x < daftar.length; x++) {
    legenda.onAdd = function (layerpeta) {
      var div = L.DomUtil.create("div", "info legend");
      var labels = [
        "<strong>Legenda" + " " + " - " + " " + judul + "</strong>",
      ];
      var categories = [judul + " " + legendData["nama_desa"]];
      for (var i = 0; i < categories.length; i++) {
        div.innerHTML += labels.push(
          '<i class="circle" style="background:' +
            legendData["warna"] +
            '"></i> ' +
            (categories[i] ? categories[i] + "<br>" : "+")
        );
      }
      div.innerHTML = labels.join("<br>");
      return div;
    };
    legenda.addTo(layerpeta);
  }

  layerpeta.on("browser-print-start", function (e) {
    L.control.scale({ position: "bottomleft" }).addTo(e.printMap);
    legenda.addTo(e.printMap);
  });

  return setlegendPetaDesa;
}

function get_path_import(coords, multi = false) {
  var path = JSON.stringify(coords);

  // Hapus Z-coordinate jika ada
  path = path.replace(/,0]/g, "]");

  if (multi == true) {
    // Jika `multi` true, kita asumsikan `coords` sudah dalam format yang benar untuk MultiPolygon
    // dan hanya perlu dibungkus dalam array jika belum.
    if (path.startsWith("[[") && path.endsWith("]]")) {
      // Sudah dalam format yang mungkin benar, tidak perlu dibungkus lagi.
    } else {
      path = `[${path}]`;
    }
  }

  return path;
}

function jenis_garis(jenis) {
  if (jenis == "dotted") {
    dashArray = "1,15";
  } else if (jenis == "dashed") {
    dashArray = "10,15";
  } else {
    // solid
    dashArray = "0";
  }

  return dashArray;
}

function popUpContent(daftar, lokasi_gambar) {
  var foto;
  var content_area;
  if (daftar.foto) {
    foto =
      '<img src="' +
      daftar.foto_lokasi +
      '" style="max-width:200px;height:auto;border-radius:3px;-moz-border-radius:3px;-webkit-border-radius:3px;border:2px solid #555555;"/>';
  } else foto = "";

  content_area =
    '<div id="content">' +
    '<div id="siteNotice">' +
    "</div>" +
    '<h4 id="firstHeading" class="firstHeading text-center">' +
    daftar.nama +
    "</h4>" +
    '<div id="bodyContent"><center>' +
    foto +
    "</center>" +
    '<p style="white-space: pre-line">' +
    daftar.desk +
    "</p>" +
    "</div>" +
    "</div>";

  return content_area;
}

function luas(map, tampil_luas) {
  if (tampil_luas == "1") {
    return map.showMeasurements();
  }

  return map.hideMeasurements();
}

function isValidMultiPolygonPath(geojson) {  
  try {
    if (!Array.isArray(geojson)) return false;

    // Try to interpret geojson as an array of polygons (MultiPolygon)
    const polygons = geojson
      .map((poly) => {
        // poly is expected to be an array of rings
        return normalizePolygonCoords(poly) || null;
      })
      .filter(Boolean);

    if (polygons.length === 0) return false;

    return polygons.every((poly) =>
      Array.isArray(poly) && poly.length > 0 && poly.every((ring) => Array.isArray(ring) && ring.length >= 4)
    );
  } catch (error) {
    return false;
  }
}

function isValidPolygonPath(path) {  
  try {
    if (!Array.isArray(path)) return false;

    // Try normalizing directly
    let rings = normalizePolygonCoords(path);

    // If normalize failed, try common wrapper levels
    if (!rings && Array.isArray(path[0])) {
      rings = normalizePolygonCoords(path[0]);
    }

    if (!rings) return false;

    // Each ring must have at least 4 points (closed)
    return rings.every((ring) => Array.isArray(ring) && ring.length >= 4);
  } catch (error) {
    return false;
  }
}

function view_error_path() {
  if (error_message) {
    $(".content").prepend(
      '<div class="callout callout-danger id="error-path">' +
        error_message +
        "</div>"
    );
  } else {
    $("#error-path").remove();
  }
}

function message(desa = null, dusun = null, rw = null, rt = null) {
  let message = "- Peta Wilayah <b>";

  if (desa) {
    message += " " + desa;
  }

  if (dusun) {
    message += sebutan_dusun + " " + dusun;
  }

  if (rw && rw != "0") {
    message += " RW " + rw;
  }

  if (rt && rt != "0") {
    message += " RT " + rt;
  }

  return message + "</b> tidak valid.<br>";
}

function resetPoint(layer_peta, posisi, zoom) {
  $("#reset-peta").click(function () {
    $("#lat").val(posisi[0]);
    $("#lng").val(posisi[1]);
    layer_peta.eachLayer(function (layer) {
      if (layer instanceof L.Marker) {
        layer_peta.removeLayer(layer);
      }
    });

    layer_peta.setView(posisi, zoom);
    showCurrentPoint(posisi, layer_peta);
  });
}

function resetPolygon(layer_peta, wilayah, posisi, zoom, multi, data_wilayah, TAMPIL_LUAS, nama_wilayah) {
  $("#reset-peta").click(function () {
    $("#path").val(wilayah);
    layer_peta.eachLayer(function (layer) {
      if (layer instanceof L.Polygon) {
        layer_peta.removeLayer(layer);
      }
    });

    layer_peta.setView(posisi, zoom);

    if (wilayah) {
      if (multi) {
        showCurrentMultiPolygon(wilayah, layer_peta, data_wilayah, TAMPIL_LUAS, nama_wilayah);
        addPetaMultipoly(layer_peta);
      } else {
        showCurrentPolygon(wilayah, layer_peta, data_wilayah, TAMPIL_LUAS, nama_wilayah);
        addPetaPoly(layer_peta);
      }
    }
  });
}

function setAreaStyle(config, luas = false) {
  return {
    stroke: true,
    color: config.border ?? "#ffffffff",
    opacity: 1,
    weight: luas ? 1 : 3,
    fillColor: config.warna ?? "#ff0000ff",
    fillOpacity: luas ? 0.3 : 0.8,
    dashArray: 4,
    ...(luas && {
      showMeasurements: true,
      measurementOptions: { showSegmentLength: false },
    })
  }
}

function setMeasurementContent(feature) {
  var area = turf.area(feature);
  var perimeter = turf.length(feature, {units: 'meters'});

  var area_m2 = area.toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' m²';
  var area_ha = (area / 10000).toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' ha';
  
  var perimeter_m = perimeter.toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' m';
  var perimeter_km = (perimeter / 1000).toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' km';
  
  return '<div class="leaflet-measure-tooltip">' +
    '<h5 class="text-center">Pengukuran Wilayah</h5>' +
    '<div class="leaflet-measure-result-area"><strong>Luas</strong>: ' + area_m2 + ' (' + area_ha + ')</div>' +
    '<div class="leaflet-measure-result-distance"><strong>Keliling</strong>: ' + perimeter_m + ' (' + perimeter_km + ')</div>' +
  '</div>'
}
