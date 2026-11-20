/**
	MIT License http://www.opensource.org/licenses/mit-license.php
	Author Igor Vladyka <igor.vladyka@gmail.com> (https://github.com/Igor-Vladyka/leaflet.browser.print)
**/

/* Portrait mode sizes in mm for 0 lvl*/
L.Control.BrowserPrint.Size =  {
	A: {
		Width: 840,
		Height: 1188
	},
	B: {
		Width: 1000,
		Height: 1414
	},
	C: {
		Width: 916,
		Height: 1296
	},
	D: {
		Width: 770,
		Height: 1090
	},
	LETTER: {
		Width: 216,
		Height: 279
	},
	HALFLETTER: {
		Width: 140,
		Height: 216
	},
	LEGAL: {
		Width: 216,
		Height: 356
	},
	JUNIORLEGAL: {
		Width: 127,
		Height: 203
	},
	TABLOID: {
		Width: 279,
		Height: 432
	},
	LEDGER: {
		Width: 432,
		Height: 279
	}
};

L.Control.BrowserPrint.Mode = function(mode, title, pageSize, action, invalidateBounds) {
	if (!mode) {
		throw 'Print mode should be specified.';
	}

	this.Mode = mode;
	this.Title = title || mode;
	this.PageSize = (pageSize || 'A4').toUpperCase();
	this.PageSeries = ["A", "B", "C", "D"].indexOf(this.PageSize[0]) != -1 ? this.PageSize[0] : "";
	this.PageSeriesSize = this.PageSize.substring(this.PageSeries.length);
	this.Action = action || function(context, element) {
		return function() {
			context['_print' + element.Mode](element);
		};
	};
	this.InvalidateBounds = invalidateBounds;
};

L.Control.BrowserPrint.Mode.Landscape = "Landscape";
L.Control.BrowserPrint.Mode.Portrait = "Portrait";
L.Control.BrowserPrint.Mode.Auto = "Auto";
L.Control.BrowserPrint.Mode.Custom = "Custom";

L.Control.BrowserPrint.Mode.prototype.getPageMargin = function(type) {
	var size = this.getPaperSize();
	var marginInMm = ((size.Width + size.Height) / 39.9);
	var result;

	switch (type) {
		case "mm":
			result = marginInMm.toFixed(2) + "mm";
			break;
		case "in":
			result = (marginInMm / 25.4).toFixed(2) + "in";
			break;
		default:
			result = marginInMm;
			break;

	}
	return result;
};

L.Control.BrowserPrint.Mode.prototype.getPaperSize = function(){
	if (this.PageSeries) {
		var series = L.Control.BrowserPrint.Size[this.PageSeries];
		var w = series.Width;
		var h = series.Height;
		var switchSides = false;
		if (this.PageSeriesSize) {
			this.PageSeriesSize = +this.PageSeriesSize;
			switchSides = this.PageSeriesSize % 2 === 1;
			if (switchSides) {
				w = w / (this.PageSeriesSize - 1 || 1);
				h = h / (this.PageSeriesSize + 1);
			} else {
				w = w / this.PageSeriesSize;
				h = h / this.PageSeriesSize;
			}
		}

		return {
			Width: switchSides ? h : w,
			Height: switchSides ? w : h
		};
	} else {
		var size = L.Control.BrowserPrint.Size[this.PageSeriesSize];
		return {
			Width: size.Width,
			Height: size.Height
		};
	}
};

L.Control.BrowserPrint.Mode.prototype.getSize = function(){
	var size = this.getPaperSize();
	var margin = this.getPageMargin() * 2 * (window.devicePixelRatio || 1);

	size.Width = Math.floor(size.Width - margin) + 'mm';
	size.Height = Math.floor(size.Height - margin) + 'mm';

	return size;
};

L.control.browserPrint.mode = function(mode, title, type, action, invalidateBounds){
	return new L.Control.BrowserPrint.Mode(mode, title, type, action, invalidateBounds);
}

L.control.browserPrint.mode.portrait = function(title, pageSize, action) {
	return L.control.browserPrint.mode(L.Control.BrowserPrint.Mode.Portrait, title, pageSize, action, false);
};

L.control.browserPrint.mode.landscape = function(title, pageSize, action) {
	return L.control.browserPrint.mode(L.Control.BrowserPrint.Mode.Landscape, title, pageSize, action, false);
};

L.control.browserPrint.mode.auto = function(title, pageSize, action) {
	return L.control.browserPrint.mode(L.Control.BrowserPrint.Mode.Auto, title, pageSize, action, true);
};

L.control.browserPrint.mode.custom = function(title, pageSize, action) {
	return L.control.browserPrint.mode(L.Control.BrowserPrint.Mode.Custom, title, pageSize, action, true);
};
