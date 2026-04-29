/*
 * Leaflet.TextPath - Shows text along a polyline
 * Inspired by Tom Mac Wright article :
 * http://mapbox.com/osmdev/2012/11/20/getting-serious-about-svg/
 */

(function () {
  var __onAdd = L.Polyline.prototype.onAdd,
    __onRemove = L.Polyline.prototype.onRemove,
    __updatePath = L.Polyline.prototype._updatePath,
    __bringToFront = L.Polyline.prototype.bringToFront;

  var PolylineTextPath = {
    onAdd: function (map) {
      __onAdd.call(this, map);
      this._textRedraw();
    },

    onRemove: function (map) {
      map = map || this._map;
      if (map && this._textNode && this._textNode.parentNode)
        map._renderer._container.removeChild(this._textNode.parentNode);
      __onRemove.call(this, map);
    },

    bringToFront: function () {
      __bringToFront.call(this);
      this._textRedraw();
    },

    _updatePath: function () {
      __updatePath.call(this);
      this._textRedraw();
    },

    _textRedraw: function () {
      var text = this._text,
        options = this._textOptions;
      if (text) {
        this.setText(null).setText(text, options);
      }
    },

    setText: function (text, options) {
      this._text = text;
      this._textOptions = options;

      /* If not in SVG mode or Polyline not added to map yet return */
      /* setText will be called by onAdd, using value stored in this._text */
      if (
        !L.Browser.svg ||
        typeof this._map === "undefined" ||
        !this._map._renderer ||
        !this._path
      ) {
        return this;
      }

      /* Additional safety checks */
      if (!this._map._renderer._container || !this._path.setAttribute) {
        console.warn("Map renderer or path not ready for setText");
        return this;
      }

      var defaults = {
        repeat: false,
        fillColor: "black",
        attributes: {},
        below: false,
      };
      options = L.Util.extend(defaults, options);

      /* If empty text, hide */
      if (!text) {
        if (this._textNode && this._textNode.parentNode) {
          this._map._renderer._container.removeChild(this._textNode.parentNode);

          /* delete the node, so it will not be removed a 2nd time if the layer is later removed from the map */
          delete this._textNode;
        }
        return this;
      }

      text = text.replace(/ /g, "\u00A0"); // Non breakable spaces
      var id = "pathdef-" + L.Util.stamp(this);
      var svg = this._map._renderer._container;

      // Pastikan _path ada dan valid
      if (!this._path || !this._path.setAttribute) {
        console.warn("Path element not ready for text path");
        return this;
      }

      this._path.setAttribute("id", id);

      if (options.below) {
        svg.insertBefore(this._path, svg.firstChild);
      }

      /* Put it along the path using textPath */
      var textNode = L.SVG.create("text");
      var textPath = L.SVG.create("textPath");

      var dy = options.offset || this._path.getAttribute("stroke-width");

      textPath.setAttributeNS(
        "http://www.w3.org/1999/xlink",
        "xlink:href",
        "#" + id,
      );
      textNode.setAttribute("dy", dy);
      for (var attr in options.attributes)
        textNode.setAttribute(attr, options.attributes[attr]);
      textPath.appendChild(document.createTextNode(text));
      textNode.appendChild(textPath);
      this._textNode = textNode;

      if (!options.below) {
        svg.appendChild(textNode);
      } else {
        svg.insertBefore(textNode, svg.firstChild);
      }

      /* Center text according to the path's bounding box */
      if (options.center) {
        var textLength = textNode.getComputedTextLength();
        var pathLength = this._path.getTotalLength();
        /* Set the position for the left side of the textNode */
        textPath.setAttribute("startOffset", pathLength / 2 - textLength / 2);
      }

      /* Change label rotation (if required) */
      if (options.orientation) {
        var rotateAngle = 0;
        var pathStartPoint = this.getLatLngs()[0];
        var pathEndPoint = this.getLatLngs()[this.getLatLngs().length - 1];
        var pathStartPointScreen =
          this._map.latLngToContainerPoint(pathStartPoint);
        var pathEndPointScreen = this._map.latLngToContainerPoint(pathEndPoint);
        rotateAngle =
          (Math.atan2(
            pathEndPointScreen.y - pathStartPointScreen.y,
            pathEndPointScreen.x - pathStartPointScreen.x,
          ) *
            180) /
          Math.PI;
        /* If upside down, rotate 180° more */
        if (rotateAngle > 90 || rotateAngle < -90) {
          rotateAngle += 180;
          textPath.setAttribute("startOffset", "100%");
          textNode.setAttribute("text-anchor", "end");
        }

        textNode.setAttribute(
          "transform",
          "rotate(" +
            rotateAngle +
            " " +
            (pathStartPointScreen.x + pathEndPointScreen.x) / 2 +
            " " +
            (pathStartPointScreen.y + pathEndPointScreen.y) / 2 +
            ")",
        );
      }

      /* Initialize mouse events for the text node */
      if (this.options.interactive) {
        if (L.Browser.svg || !L.Browser.vml) {
          textNode.setAttribute("pointer-events", "visiblePainted");
        }

        L.DomEvent.on(textNode, "click", this._fireMouseEvent, this);
        L.DomEvent.on(textNode, "mouseover", this._fireMouseEvent, this);
        L.DomEvent.on(textNode, "mouseout", this._fireMouseEvent, this);
        L.DomEvent.on(textNode, "mousemove", this._fireMouseEvent, this);
      }

      if (options.repeat) {
        /* Reduce spacing in firefox */
        var browser = L.Browser.gecko
          ? "moz"
          : L.Browser.webkit
            ? "webkit"
            : "blink";
        textNode.setAttribute("style", browser + "-user-select: none");

        var textWidth = textNode.getBBox().width;
        var pathLen = this._path.getTotalLength();
        var num = Math.floor(pathLen / textWidth);
        if (num > 1) {
          var repeatText = new Array(num).join(" " + text + " ");
          textPath.firstChild.nodeValue = repeatText;

          /* Center */
          var textLength = textNode.getComputedTextLength();
          var pathLength = this._path.getTotalLength();
          textPath.setAttribute("startOffset", pathLength / 2 - textLength / 2);
        }
      }

      return this;
    },
  };

  L.Polyline.include(PolylineTextPath);

  L.LayerGroup.include({
    setText: function (text, options) {
      for (var layer in this._layers) {
        if (typeof this._layers[layer].setText === "function") {
          this._layers[layer].setText(text, options);
        }
      }
      return this;
    },
  });
})();
