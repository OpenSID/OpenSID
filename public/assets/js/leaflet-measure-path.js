/*
 * Modified version of:
 * https://github.com/ProminentEdge/leaflet-measure-path
 *
 * License see: LICENSE.md.
 */

!(function() {
    'use strict';
  
    L.Marker.Measurement = L[L.Layer ? 'Layer' : 'Class'].extend({
      options: {
        pane: 'markerPane'
      },
  
      initialize: function(latlng, measurement, title, rotation, options) {
        L.setOptions(this, options);
  
        this._latlng = latlng;
        this._measurement = measurement;
        this._title = title;
        this._rotation = rotation;
      },
  
      addTo: function(map) {
        map.addLayer(this);
        return this;
      },
  
      onAdd: function(map) {
        this._map = map;
        var pane = this.getPane ? this.getPane() : map.getPanes().markerPane;
        var el = (this._element = L.DomUtil.create('div', 'leaflet-zoom-animated leaflet-measure-path-measurement', pane));
        var inner = L.DomUtil.create('div', '', el);
        inner.title = this._title;
        inner.innerHTML = this._measurement;
  
        map.on('zoomanim', this._animateZoom, this);
  
        this._setPosition();
      },
  
      onRemove: function(map) {
        map.off('zoomanim', this._animateZoom, this);
        var pane = this.getPane ? this.getPane() : map.getPanes().markerPane;
        pane.removeChild(this._element);
        this._map = null;
      },
  
      _setPosition: function() {
        L.DomUtil.setPosition(this._element, this._map.latLngToLayerPoint(this._latlng));
        this._element.style.transform += ' rotate(' + this._rotation + 'rad)';
      },
  
      _animateZoom: function(opt) {
        var pos = this._map._latLngToNewLayerPoint(this._latlng, opt.zoom, opt.center).round();
        L.DomUtil.setPosition(this._element, pos);
        this._element.style.transform += ' rotate(' + this._rotation + 'rad)';
      }
    });
  
    L.marker.measurement = function(latLng, measurement, title, rotation, options) {
      return new L.Marker.Measurement(latLng, measurement, title, rotation, options);
    };
  
    var formatDistance = function(d) {
      var unit, feet;
  
      if (this._measurementOptions.imperial) {
        feet = d / 0.3048;
        if (feet > 3000) {
          d = d / 1609.344;
          unit = 'mi';
        } else {
          d = feet;
          unit = 'ft';
        }
      } else {
        if (d > 1000) {
          d = d / 1000;
          unit = 'km';
        } else {
          unit = 'm';
        }
      }
  
      if (d < 100) {
        return d.toFixed(1) + ' ' + unit;
      } else {
        return Math.round(d) + ' ' + unit;
      }
    };
  
    var formatArea = function(a) {
      var unit, sqfeet;
  
      if (this._measurementOptions.imperial) {
        if (a > 404.685642) {
          a = a / 4046.85642;
          unit = 'ac';
        } else {
          a = a / 0.09290304;
          unit = 'ft²';
        }
      } else {
        if (a > 1000000) {
          a = a / 1000000;
          unit = 'km²';
        } else {
          unit = 'm²';
        }
      }
  
      if (a < 100) {
        return a.toFixed(1) + ' ' + unit;
      } else {
        return Math.round(a) + ' ' + unit;
      }
    };
  
    var RADIUS = 6378137;
    // ringArea function copied from geojson-area
    // (https://github.com/mapbox/geojson-area)
    // This function is distributed under a separate license,
    // see LICENSE.md.
    var ringArea = function ringArea(coords) {
      var rad = function rad(_) {
        return (_ * Math.PI) / 180;
      };
      var p1,
        p2,
        p3,
        lowerIndex,
        middleIndex,
        upperIndex,
        area = 0,
        coordsLength = coords.length;
  
      if (coordsLength > 2) {
        for (var i = 0; i < coordsLength; i++) {
          if (i === coordsLength - 2) {
            // i = N-2
            lowerIndex = coordsLength - 2;
            middleIndex = coordsLength - 1;
            upperIndex = 0;
          } else if (i === coordsLength - 1) {
            // i = N-1
            lowerIndex = coordsLength - 1;
            middleIndex = 0;
            upperIndex = 1;
          } else {
            // i = 0 to N-3
            lowerIndex = i;
            middleIndex = i + 1;
            upperIndex = i + 2;
          }
          p1 = coords[lowerIndex];
          p2 = coords[middleIndex];
          p3 = coords[upperIndex];
          area += (rad(p3.lng) - rad(p1.lng)) * Math.sin(rad(p2.lat));
        }
  
        area = (area * RADIUS * RADIUS) / 2;
      }
  
      return Math.abs(area);
    };
  
    var circleArea = function circleArea(d) {
      var rho = d / RADIUS;
      return 2 * Math.PI * RADIUS * RADIUS * (1 - Math.cos(rho));
    };
  
    var override = function(method, fn, hookAfter) {
      if (!hookAfter) {
        return function() {
          method.apply(this, arguments);
          return fn.apply(this, arguments);
        };
      } else {
        return function() {
          fn.apply(this, arguments);
          return method.apply(this, arguments);
        };
      }
    };
  
    L.Polyline.include({
      showMeasurements: function(options) {
        if (!this._map || this._measurementLayer) return this;
  
        this._measurementOptions = L.extend(
          {
            minPixelDistance: 30,
            showSegmentLength: true,
            showTotalLength: true,
            showTotalArea: true,
            showTotalAreaLength: false,
            lang: {
              segmentLength: 'Segment length',
              totalLength: 'Total length',
              totalArea: 'Total area',
              totalAreaLength: 'Total area length'
            },
            prefix: {
              segmentLength: '',
              totalLength: '',
              totalArea: '',
              totalAreaLength: ''
            }
          },
          options || {}
        );
  
        this._measurementLayer = L.layerGroup().addTo(this._map);
        this.updateMeasurements();
  
        this._map.on('zoomend', this.updateMeasurements, this);
  
        return this;
      },
  
      hideMeasurements: function() {
        this._map.off('zoomend', this.updateMeasurements, this);
  
        if (!this._measurementLayer) return this;
        this._map.removeLayer(this._measurementLayer);
        this._measurementLayer = null;
  
        return this;
      },
  
      onAdd: override(L.Polyline.prototype.onAdd, function() {
        if (this.options.showMeasurements) {
          this.showMeasurements(this.options.measurementOptions);
        }
      }),
  
      onRemove: override(
        L.Polyline.prototype.onRemove,
        function() {
          this.hideMeasurements();
        },
        true
      ),
  
      setLatLngs: override(L.Polyline.prototype.setLatLngs, function() {
        return this.updateMeasurements();
      }),
  
      spliceLatLngs: override(L.Polyline.prototype.spliceLatLngs, function() {
        return this.updateMeasurements();
      }),
  
      formatDistance: formatDistance,
      formatArea: formatArea,
  
      updateMeasurements: function() {
        if (!this._measurementLayer) return this;
  
        var latLngs = this.getLatLngs(),
          isPolygon = this instanceof L.Polygon,
          options = this._measurementOptions,
          totalDist = 0,
          distFormatter = this._measurementOptions.formatDistance || L.bind(this.formatDistance, this),
          areaFormatter,
          ll1,
          ll2,
          p1,
          p2,
          pixelDist,
          dist;
  
        if (latLngs && latLngs.length && L.Util.isArray(latLngs[0])) {
          // Outer ring is stored as an array in the first element,
          // use that instead.
          latLngs = latLngs[0];
        }
  
        this._measurementLayer.clearLayers();
  
        if (latLngs.length > 1) {
          for (var i = 1, len = latLngs.length; (isPolygon && i <= len) || i < len; i++) {
            ll1 = latLngs[i - 1];
            ll2 = latLngs[i % len];
            dist = ll1.distanceTo(ll2);
            totalDist += dist;
  
            p1 = this._map.latLngToLayerPoint(ll1);
            p2 = this._map.latLngToLayerPoint(ll2);
            pixelDist = p1.distanceTo(p2);
  
            // Show segment length
            if (options.showSegmentLength && pixelDist >= options.minPixelDistance) {
              L.marker
                .measurement(
                  this._map.layerPointToLatLng([(p1.x + p2.x) / 2, (p1.y + p2.y) / 2]),
                  options.prefix.segmentLength + distFormatter(dist),
                  options.lang.segmentLength,
                  this._getRotation(ll1, ll2),
                  options
                )
                .addTo(this._measurementLayer);
            }
          }
  
          // Show total length for polylines
          if (options.showTotalLength && !isPolygon) {
            L.marker
              .measurement(ll2, options.prefix.totalLength + distFormatter(totalDist), options.lang.totalLength, 0, options)
              .addTo(this._measurementLayer);
          }
        }
  
        // Area
        if (isPolygon && latLngs.length > 2) {
          // Show total area
          if (options.showTotalArea) {
            areaFormatter = options.formatArea || L.bind(this.formatArea, this);
            var area = ringArea(latLngs);
  
            // Total area when it is a polygon
            L.marker
              .measurement(this.getBounds().getCenter(), options.prefix.totalArea + areaFormatter(area), options.lang.totalArea, 0, options)
              .addTo(this._measurementLayer);
          }
  
          // Show total area length
          if (options.showTotalAreaLength) {
            if (!ll2) {
              // If ll2 is not yet defined use the last value
              ll2 = latLngs[latLngs.length - 1];
            }
  
            L.marker
              .measurement(ll2, options.prefix.totalAreaLength + distFormatter(totalDist), options.lang.totalAreaLength, 0, options)
              .addTo(this._measurementLayer);
          }
        }
  
        return this;
      },
  
      _getRotation: function(ll1, ll2) {
        var p1 = this._map.project(ll1),
          p2 = this._map.project(ll2);
  
        return Math.atan((p2.y - p1.y) / (p2.x - p1.x));
      }
    });
  
    L.Polyline.addInitHook(function() {
      if (this.options.showMeasurements) {
        this.showMeasurements();
      }
    });
  
    L.Circle.include({
      showMeasurements: function(options) {
        if (!this._map || this._measurementLayer) return this;
  
        this._measurementOptions = L.extend(
          {
            showArea: true,
            lang: {
              totalArea: 'Total area'
            }
          },
          options || {}
        );
  
        this._measurementLayer = L.layerGroup().addTo(this._map);
        this.updateMeasurements();
  
        this._map.on('zoomend', this.updateMeasurements, this);
  
        return this;
      },
  
      hideMeasurements: function() {
        this._map.on('zoomend', this.updateMeasurements, this);
  
        if (!this._measurementLayer) return this;
        this._map.removeLayer(this._measurementLayer);
        this._measurementLayer = null;
  
        return this;
      },
  
      onAdd: override(L.Circle.prototype.onAdd, function() {
        if (this.options.showMeasurements) {
          this.showMeasurements(this.options.measurementOptions);
        }
      }),
  
      onRemove: override(
        L.Circle.prototype.onRemove,
        function() {
          this.hideMeasurements();
        },
        true
      ),
  
      setLatLng: override(L.Circle.prototype.setLatLng, function() {
        this.updateMeasurements();
      }),
  
      setRadius: override(L.Circle.prototype.setRadius, function() {
        this.updateMeasurements();
      }),
  
      formatArea: formatArea,
  
      updateMeasurements: function() {
        if (!this._measurementLayer) return;
  
        var latLng = this.getLatLng(),
          options = this._measurementOptions,
          formatter = options.formatArea || L.bind(this.formatArea, this);
  
        this._measurementLayer.clearLayers();
  
        if (options.showArea) {
          formatter = options.formatArea || L.bind(this.formatArea, this);
          var area = circleArea(this.getRadius());
          L.marker.measurement(latLng, formatter(area), options.lang.totalArea, 0, options).addTo(this._measurementLayer);
        }
      }
    });
  
    L.Circle.addInitHook(function() {
      if (this.options.showMeasurements) {
        this.showMeasurements();
      }
    });
  })();
  