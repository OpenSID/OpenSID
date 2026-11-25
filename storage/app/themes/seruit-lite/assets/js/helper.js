/******/ (() => { // webpackBootstrap
/*!**************************!*\
  !*** ./src/js/helper.js ***!
  \**************************/
// seruit/src/js/helper.js

/**
 * Mengubah string menjadi format underscore (snake_case).
 * @param {string} string - Teks yang akan diubah.
 * @returns {string} Teks dalam format snake_case.
 */
window.underscore = function (string) {
  if (!string) return '';
  return string.replace(/\s/g, '_').toLowerCase();
};

/**
 * Mengubah huruf pertama setiap kata menjadi huruf kapital.
 * @param {string} str - Teks yang akan diubah.
 * @returns {string} Teks dengan setiap kata diawali huruf kapital.
 */
window.capitalizeFirstCharacterOfEachWord = function (str) {
  if (!str) return '';
  return str.toLowerCase().split(' ').map(function (word) {
    return word.charAt(0).toUpperCase() + word.slice(1);
  }).join(' ');
};

/**
 * Mengonversi angka menjadi format mata uang Rupiah.
 * @param {number} angka - Angka yang akan diformat.
 * @returns {string} String dalam format Rupiah (contoh: "Rp 1.000.000").
 */
window.formatRupiah = function (angka) {
  if (angka == null) return 'Rp 0';
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    minimumFractionDigits: 0
  }).format(angka);
};

/**
 * Mengamankan string HTML dengan mengubah karakter khusus menjadi entitasnya.
 * @param {string} text - Teks yang akan diamankan.
 * @returns {string} Teks yang aman untuk ditampilkan sebagai HTML.
 */
window.escapeHtml = function (text) {
  if (typeof text !== 'string') return '';
  var map = {
    '&': '&amp;',
    '<': '&lt;',
    '>': '&gt;',
    '"': '&quot;',
    "'": '&#039;'
  };
  return text.replace(/[&<>"']/g, function (m) {
    return map[m];
  });
};
/******/ })()
;