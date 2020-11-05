"use strict";

function _createForOfIteratorHelper(o, allowArrayLike) { var it; if (typeof Symbol === "undefined" || o[Symbol.iterator] == null) { if (Array.isArray(o) || (it = _unsupportedIterableToArray(o)) || allowArrayLike && o && typeof o.length === "number") { if (it) o = it; var i = 0; var F = function F() {}; return { s: F, n: function n() { if (i >= o.length) return { done: true }; return { done: false, value: o[i++] }; }, e: function e(_e) { throw _e; }, f: F }; } throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); } var normalCompletion = true, didErr = false, err; return { s: function s() { it = o[Symbol.iterator](); }, n: function n() { var step = it.next(); normalCompletion = step.done; return step; }, e: function e(_e2) { didErr = true; err = _e2; }, f: function f() { try { if (!normalCompletion && it["return"] != null) it["return"](); } finally { if (didErr) throw err; } } }; }

function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }

function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) { arr2[i] = arr[i]; } return arr2; }

var showStatus = true;
var charts;

function switchType(type, alpha) {
  charts.update({
    chart: {
      options3d: {
        alpha: alpha
      }
    },
    series: [{
      type: type
    }]
  });
}

function showZeroValue() {
  var show = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : false;

  if (show) {
    $(".nol").parent().show();
  } else {
    $(".nol").parent().hide();
  }
}

function showHideToggle() {
  $('#showData').click();
  showZeroValue(showStatus);
  showStatus = !showStatus;
  if (showStatus) $('#tampilkan').text('Tampilkan Nol');else $('#tampilkan').text('Sembunyikan Nol');
}

$(document).ready(function () {
  if ($('#peserta_program').length) {
    $('#peserta_program').DataTable({
      'processing': true,
      'serverSide': true,
      "pageLength": 10,
      'order': [],
      "ajax": {
        "url": bantuanUrl,
        "type": "POST",
        "data": {
          stat: $('#stat').val()
        }
      },
      //Set column definition initialisation properties.
      "columnDefs": [{
        "targets": [0, 3],
        //first column / numbering column
        "orderable": false //set not orderable

      }],
      'language': {
        'url': BASE_URL + '/assets/bootstrap/js/dataTables.indonesian.lang'
      },
      'drawCallback': function drawCallback() {
        $('.dataTables_paginate > .pagination').addClass('pagination-sm no-margin');
      }
    });
  }

  if ($('#statistics__graph').length) {
    showZeroValue(false);
    var categories = [];
    var data = [];

    var _iterator = _createForOfIteratorHelper(dataStats),
        _step;

    try {
      for (_iterator.s(); !(_step = _iterator.n()).done;) {
        var stat = _step.value;

        if (stat.nama !== 'TOTAL' && stat.nama !== 'JUMLAH' && stat.nama != 'PENERIMA') {
          var filteredData = [stat.nama, parseInt(stat.jumlah)];
          categories.push(stat.nama);
          data.push(filteredData);
        }
      }
    } catch (err) {
      _iterator.e(err);
    } finally {
      _iterator.f();
    }

    charts = new Highcharts.Chart({
      chart: {
        renderTo: 'statistics__graph',
        options3d: {
          enabled: enable3d,
          alpha: 45,
          beta: 10
        }
      },
      title: 0,
      yAxis: {
        showEmpty: false,
        title: {
          text: 'Jumlah Populasi'
        }
      },
      xAxis: {
        categories: categories
      },
      plotOptions: {
        series: {
          colorByPoint: true
        },
        column: {
          pointPadding: -0.1,
          borderWidth: 0,
          showInLegend: false,
          depth: 50,
          viewDistance: 25
        },
        pie: {
          allowPointSelect: true,
          cursor: 'pointer',
          showInLegend: false,
          depth: 30,
          innerSize: 30
        }
      },
      legend: {
        enabled: true
      },
      series: [{
        type: 'pie',
        name: 'Jumlah Populasi',
        shadow: 1,
        border: 1,
        data: data
      }]
    });
    $('#showData').click(function () {
      $('tr.lebih').show();
      $('#showData').hide();
      showZeroValue(false);
    });
    $('.button__switch').click(function () {
      var chartType = $(this).data('type');
      var alpha = chartType == 'pie' ? 45 : 20;
      $(this).addClass('button__switch--active');
      $(this).siblings('.button__switch').removeClass('button__switch--active');
      switchType(chartType, alpha);
    });
  }
});
$(document).ready(function () {
  $('.slick-featured').slick({
    infinite: true,
    slidesToShow: 1,
    speed: 500,
    fade: true,
    autoplay: true,
    asNavFor: '.slick-thumbnail',
    prevArrow: $('.slider__arrow--left'),
    nextArrow: $('.slider__arrow--right')
  });
  var childElems = $('.slick-thumbnail').children().length;
  var slidesToShow = childElems - 1 < 5 ? childElems - 1 : 5;
  $('.slick-thumbnail').slick({
    infinite: true,
    slidesToShow: slidesToShow,
    slidesToScroll: 1,
    asNavFor: '.slick-featured',
    arrows: false,
    focusOnSelect: true
  });
  $('.newsticker__list').marquee({
    duration: 5000,
    gap: 50,
    pauseOnHover: true,
    speed: 150
  });
  var activeElem = $('.nav-tabs>li:first-child').find('a').attr('href');
  $('.nav-tabs>li:first-child').find('a').addClass('active show');
  $('#arsip_artikel .tab-content div:first-child').addClass('active show');
  $(activeElem).addClass('active show');
  $(activeElem).removeClass('fade in');
  $('.nav-tabs li a').on('click', function (e) {
    e.preventDefault();
    var href = $(this).attr('href');
    $(this).parent().siblings().removeClass('active');
    $(this).parent().addClass('active');
    $(this).parents('.nav-tabs').siblings('.tab-content').children().removeClass('active show');
    $(this).parents('.nav-tabs').siblings('.tab-content').find(href).addClass('active show');
  });
  $('button[data-toggle=collapse]').click(function () {
    var dataTarget = $(this).attr('data-target');
    $(dataTarget).toggle('collapse');
  });

  if ($('[data-fancybox]').length) {
    $('[data-fancybox]').fancybox({
      gutter: 0,
      transitionEffect: 'fade'
    });
  }

  $(window).scroll(function () {
    if ($(this).scrollTop() - $('.header').height() > 0) {
      $('.header').addClass('header--sticky');
    } else {
      $('.header').removeClass('header--sticky');
    }
  });
  $('.button--menu').click(function () {
    $('.nav').toggleClass('nav--opened');
  });
});