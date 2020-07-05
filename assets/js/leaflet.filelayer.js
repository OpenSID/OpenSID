/*
 * Load files *locally* (GeoJSON, KML, GPX) into the map
 * using the HTML5 File API.
 *
 * Requires Mapbox's togeojson.js to be in global scope
 * https://github.com/mapbox/togeojson
 */

(function (factory, window) {
    // define an AMD module that relies on 'leaflet'
    if (typeof define === 'function' && define.amd && window.toGeoJSON) {
        define(['leaflet'], function (L) {
            factory(L, window.toGeoJSON);
        });
    } else if (typeof module === 'object' && module.exports) {
        // require('LIBRARY') returns a factory that requires window to
        // build a LIBRARY instance, we normalize how we use modules
        // that require this pattern but the window provided is a noop
        // if it's defined
        module.exports = function (root, L, toGeoJSON) {
            if (L === undefined) {
                if (typeof window !== 'undefined') {
                    L = require('leaflet');
                } else {
                    L = require('leaflet')(root);
                }
            }
            if (toGeoJSON === undefined) {
                if (typeof window !== 'undefined') {
                    toGeoJSON = require('togeojson');
                } else {
                    toGeoJSON = require('togeojson')(root);
                }
            }
            factory(L, toGeoJSON);
            return L;
        };
    } else if (typeof window !== 'undefined' && window.L && window.toGeoJSON) {
        factory(window.L, window.toGeoJSON);
    }
}(function fileLoaderFactory(L, toGeoJSON) {
    var FileLoader = L.Layer.extend({
        options: {
            layer: L.geoJson,
            layerOptions: {},
            fileSizeLimit: 1024
        },

        initialize: function (map, options) {
            this._map = map;
            L.Util.setOptions(this, options);

            this._parsers = {
                geojson: this._loadGeoJSON,
                json: this._loadGeoJSON,
                gpx: this._convertToGeoJSON,
                kml: this._convertToGeoJSON
            };
        },

        load: function (file, ext) {
            var parser,
                reader;

            // Check file is defined
            if (this._isParameterMissing(file, 'file')) {
                return false;
            }

            // Check file size
            if (!this._isFileSizeOk(file.size)) {
                return false;
            }

            // Get parser for this data type
            parser = this._getParser(file.name, ext);
            if (!parser) {
                return false;
            }

            // Read selected file using HTML5 File API
            reader = new FileReader();
            reader.onload = L.Util.bind(function (e) {
                var layer;
                try {
                    this.fire('data:loading', { filename: file.name, format: parser.ext });
                    layer = parser.processor.call(this, e.target.result, parser.ext);
                    this.fire('data:loaded', {
                        layer: layer,
                        filename: file.name,
                        format: parser.ext
                    });
                } catch (err) {
                    this.fire('data:error', { error: err });
                }
            }, this);
            // Testing trick: tests don't pass a real file,
            // but an object with file.testing set to true.
            // This object cannot be read by reader, just skip it.
            if (!file.testing) {
                reader.readAsText(file);
            }
            // We return this to ease testing
            return reader;
        },

        loadMultiple: function (files, ext) {
            var readers = [];
            if (files[0]) {
              files = Array.prototype.slice.apply(files);
              while (files.length > 0) {
                readers.push(this.load(files.shift(), ext));
              }
            }
            // return first reader (or false if no file),
            // which is also used for subsequent loadings
            return readers;
        },

        loadData: function (data, name, ext) {
            var parser;
            var layer;

            // Check required parameters
            if ((this._isParameterMissing(data, 'data'))
              || (this._isParameterMissing(name, 'name'))) {
                return;
            }

            // Check file size
            if (!this._isFileSizeOk(data.length)) {
                return;
            }

            // Get parser for this data type
            parser = this._getParser(name, ext);
            if (!parser) {
                return;
            }

            // Process data
            try {
                this.fire('data:loading', { filename: name, format: parser.ext });
                layer = parser.processor.call(this, data, parser.ext);
                this.fire('data:loaded', {
                    layer: layer,
                    filename: name,
                    format: parser.ext
                });
            } catch (err) {
                this.fire('data:error', { error: err });
            }
        },

        _isParameterMissing: function (v, vname) {
            if (typeof v === 'undefined') {
                this.fire('data:error', {
                    error: new Error('Missing parameter: ' + vname)
                });
                return true;
            }
            return false;
        },

        _getParser: function (name, ext) {
            var parser;
            ext = ext || name.split('.').pop();
            parser = this._parsers[ext];
            if (!parser) {
                this.fire('data:error', {
                    error: new Error('Unsupported file type (' + ext + ')')
                });
                return undefined;
            }
            return {
                processor: parser,
                ext: ext
            };
        },

        _isFileSizeOk: function (size) {
            var fileSize = (size / 1024).toFixed(4);
            if (fileSize > this.options.fileSizeLimit) {
                this.fire('data:error', {
                    error: new Error(
                        'File size exceeds limit (' +
                        fileSize + ' > ' +
                        this.options.fileSizeLimit + 'kb)'
                    )
                });
                return false;
            }
            return true;
        },

        _loadGeoJSON: function _loadGeoJSON(content) {
            var layer;
            if (typeof content === 'string') {
                content = JSON.parse(content);
            }
            layer = this.options.layer(content, this.options.layerOptions);

            if (layer.getLayers().length === 0) {
                throw new Error('GeoJSON has no valid layers.');
            }

            if (this.options.addToMap) {
                layer.addTo(this._map);
            }
            return layer;
        },

        _convertToGeoJSON: function _convertToGeoJSON(content, format) {
            var geojson;
            // Format is either 'gpx' or 'kml'
            if (typeof content === 'string') {
                content = (new window.DOMParser()).parseFromString(content, 'text/xml');
            }
            geojson = toGeoJSON[format](content);
            return this._loadGeoJSON(geojson);
        }
    });

    var FileLayerLoad = L.Control.extend({
        statics: {
            TITLE: 'Load local file (GPX, KML, GeoJSON)',
            LABEL: '&#8965;'
        },
        options: {
            position: 'topleft',
            fitBounds: true,
            layerOptions: {},
            addToMap: true,
            fileSizeLimit: 1024
        },

        initialize: function (options) {
            L.Util.setOptions(this, options);
            this.loader = null;
        },

        onAdd: function (map) {
            this.loader = L.FileLayer.fileLoader(map, this.options);

            this.loader.on('data:loaded', function (e) {
                // Fit bounds after loading
                if (this.options.fitBounds) {
                    window.setTimeout(function () {
                        map.fitBounds(e.layer.getBounds());
                    }, 500);
                }
            }, this);

            // Initialize Drag-and-drop
            this._initDragAndDrop(map);

            // Initialize map control
            return this._initContainer();
        },

        _initDragAndDrop: function (map) {
            var callbackName;
            var thisLoader = this.loader;
            var dropbox = map._container;

            var callbacks = {
                dragenter: function () {
                    map.scrollWheelZoom.disable();
                },
                dragleave: function () {
                    map.scrollWheelZoom.enable();
                },
                dragover: function (e) {
                    e.stopPropagation();
                    e.preventDefault();
                },
                drop: function (e) {
                    e.stopPropagation();
                    e.preventDefault();

                    thisLoader.loadMultiple(e.dataTransfer.files);
                    map.scrollWheelZoom.enable();
                }
            };
            for (callbackName in callbacks) {
                if (callbacks.hasOwnProperty(callbackName)) {
                    dropbox.addEventListener(callbackName, callbacks[callbackName], false);
                }
            }
        },

        _initContainer: function () {
            var thisLoader = this.loader;

            // Create a button, and bind click on hidden file input
            var fileInput;
            var zoomName = 'leaflet-control-filelayer leaflet-control-zoom';
            var barName = 'leaflet-bar';
            var partName = barName + '-part';
            var container = L.DomUtil.create('div', zoomName + ' ' + barName);
            var link = L.DomUtil.create('a', zoomName + '-in ' + partName, container);
            link.innerHTML = L.Control.FileLayerLoad.LABEL;
            link.href = '#';
            link.title = L.Control.FileLayerLoad.TITLE;

            // Create an invisible file input
            fileInput = L.DomUtil.create('input', 'hidden', container);
            fileInput.type = 'file';
            fileInput.multiple = 'multiple';
            if (!this.options.formats) {
                fileInput.accept = '.gpx,.kml,.json,.geojson';
            } else {
                fileInput.accept = this.options.formats.join(',');
            }
            fileInput.style.display = 'none';
            // Load on file change
            fileInput.addEventListener('change', function () {
                thisLoader.loadMultiple(this.files);
                // reset so that the user can upload the same file again if they want to
                this.value = '';
            }, false);

            L.DomEvent.disableClickPropagation(container);
            L.DomEvent.on(link, 'click', function (e) {
                fileInput.click();
                e.preventDefault();
            });
            return container;
        }
    });

    L.FileLayer = {};
    L.FileLayer.FileLoader = FileLoader;
    L.FileLayer.fileLoader = function (map, options) {
        return new L.FileLayer.FileLoader(map, options);
    };

    L.Control.FileLayerLoad = FileLayerLoad;
    L.Control.fileLayerLoad = function (options) {
        return new L.Control.FileLayerLoad(options);
    };
}, window));
