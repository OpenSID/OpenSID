/******/ (() => { // webpackBootstrap
/*!*********************************!*\
  !*** ./src/js/accessibility.js ***!
  \*********************************/
document.addEventListener('alpine:init', function () {
  Alpine.data('accessibilityWidget', function () {
    return {
      open: false,
      fontSize: 100,
      readableFont: false,
      highlightLinks: false,
      highlightTitles: false,
      highContrast: false,
      grayscale: false,
      init: function init() {
        var _this = this;
        this.fontSize = this.loadState('fontSize', 100);
        this.readableFont = this.loadState('readableFont', false);
        this.highlightLinks = this.loadState('highlightLinks', false);
        this.highlightTitles = this.loadState('highlightTitles', false);
        this.highContrast = this.loadState('highContrast', false);
        this.grayscale = this.loadState('grayscale', false);
        this.$watch('fontSize', function (val) {
          return _this.applyAndSave('fontSize', val, _this.applyFontSize);
        });
        this.$watch('readableFont', function (val) {
          return _this.applyAndSave('readableFont', val, _this.applyClass, 'font-readable');
        });
        this.$watch('highlightLinks', function (val) {
          return _this.applyAndSave('highlightLinks', val, _this.applyClass, 'highlight-links');
        });
        this.$watch('highlightTitles', function (val) {
          return _this.applyAndSave('highlightTitles', val, _this.applyClass, 'highlight-titles');
        });
        this.$watch('highContrast', function (val) {
          return _this.applyAndSave('highContrast', val, _this.applyHighContrast);
        });
        this.$watch('grayscale', function (val) {
          return _this.applyAndSave('grayscale', val, _this.applyClass, 'grayscale');
        });
        this.applyAll();
      },
      loadState: function loadState(key, defaultValue) {
        var value = localStorage.getItem('accessibility_' + key);
        if (value === null) return defaultValue;
        if (typeof defaultValue === 'boolean') return value === 'true';
        if (typeof defaultValue === 'number') return parseInt(value, 10);
        return value;
      },
      applyAndSave: function applyAndSave(key, value, applyFn) {
        localStorage.setItem('accessibility_' + key, value);
        for (var _len = arguments.length, args = new Array(_len > 3 ? _len - 3 : 0), _key = 3; _key < _len; _key++) {
          args[_key - 3] = arguments[_key];
        }
        applyFn.call.apply(applyFn, [this, value].concat(args));
      },
      applyAll: function applyAll() {
        this.applyFontSize(this.fontSize);
        this.applyClass(this.readableFont, 'font-readable');
        this.applyClass(this.highlightLinks, 'highlight-links');
        this.applyClass(this.highlightTitles, 'highlight-titles');
        this.applyHighContrast(this.highContrast);
        this.applyClass(this.grayscale, 'grayscale');
      },
      applyFontSize: function applyFontSize(value) {
        document.documentElement.style.fontSize = value + '%';
      },
      applyClass: function applyClass(isActive, className) {
        document.documentElement.classList.toggle(className, isActive);
      },
      applyHighContrast: function applyHighContrast(isActive) {
        this.applyClass(isActive, 'high-contrast');
        if (isActive) {
          // Mengirimkan custom event ke window
          window.dispatchEvent(new CustomEvent('enable-dark-mode'));
        }
      },
      adjustFontSize: function adjustFontSize(amount) {
        this.fontSize = Math.max(80, Math.min(150, this.fontSize + amount));
      },
      toggle: function toggle(feature) {
        this[feature] = !this[feature];
      },
      reset: function reset() {
        this.fontSize = 100;
        this.readableFont = false;
        this.highlightLinks = false;
        this.highlightTitles = false;
        this.highContrast = false;
        this.grayscale = false;
      }
    };
  });
});
/******/ })()
;