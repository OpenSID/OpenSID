jQuery(document).ready(function ($) {
  // for hover dropdown menu
  $("ul.nav li.dropdown").hover(
    function () {
      $(this).find(".dropdown-menu").stop(true, true).delay(200).fadeIn(200);
    },
    function () {
      $(this).find(".dropdown-menu").stop(true, true).delay(200).fadeOut(200);
    },
  );
  // slick slider call
  $(".slick_slider").slick({
    dots: true,
    infinite: true,
    speed: 500,
    slidesToShow: 1,
    slide: "div",
    autoplay: true,
    autoplaySpeed: 2000,
    cssEase: "linear",
  });
  // slick slider2 call
  $(".slick_slider2").slick({
    dots: true,
    infinite: true,
    speed: 500,
    autoplay: true,
    autoplaySpeed: 2000,
    fade: true,
    slide: "div",
    cssEase: "linear",
  });
  //Check to see if the window is top if not then display button
  $(window).scroll(function () {
    if ($(this).scrollTop() > 300) {
      $(".scrollToTop").fadeIn();
    } else {
      $(".scrollToTop").fadeOut();
    }
  });
  //Click event to scroll to top
  $(".scrollToTop").click(function () {
    $("html, body").animate(
      {
        scrollTop: 0,
      },
      800,
    );
    return false;
  });
});

wow = new WOW({
  animateClass: "animated",
  offset: 100,
});
wow.init();

jQuery(document).ready(function ($) {
  // makes sure the whole site is loaded
  $("#status").fadeOut(); // will first fade out the loading animation
  $("#preloader").delay(100).fadeOut("slow"); // will fade out the white DIV that covers the website.
  $("body").delay(100).css({
    overflow: "visible",
  });

  if ($("#peserta_program").length) {
    let pesertaDatatable = $("#peserta_program").DataTable({
      processing: true,
      serverSide: true,
      order: [],
      ajax: {
        url: bantuanUrl,
        type: "GET",
        data: function (row) {
          return {
            "page[size]": row.length,
            "page[number]": row.start / row.length + 1,
            "filter[search]": row.search.value,
            sort:
              (row.order[0]?.dir === "asc" ? "" : "-") +
              row.columns[row.order[0]?.column]?.name,
          };
        },
        dataSrc: function (json) {
          json.recordsTotal = json.meta.pagination.total;
          json.recordsFiltered = json.meta.pagination.total;

          return json.data;
        },
      },
      columns: [
        {
          data: null,
          orderable: false,
          searchable: false,
        },
        {
          data: "attributes.nama",
          name: "nama",
        },
        {
          data: "attributes.kartu_nama",
          name: "kartu_nama",
        },
        {
          data: "attributes.kartu_alamat",
          name: "kartu_alamat",
          orderable: false,
          searchable: false,
        },
      ],
      order: [1, "asc"],
      language: {
        url: "".concat(
          BASE_URL,
          "/assets/bootstrap/js/dataTables.indonesian.lang",
        ),
      },
      drawCallback: function drawCallback() {
        $(".dataTables_paginate > .pagination").addClass(
          "pagination-sm no-margin",
        );
      },
    });

    pesertaDatatable.on("draw.dt", function () {
      var PageInfo = $("#peserta_program").DataTable().page.info();
      pesertaDatatable
        .column(0, {
          page: "current",
        })
        .nodes()
        .each(function (cell, i) {
          cell.innerHTML = i + 1 + PageInfo.start;
        });
    });
  }
});
