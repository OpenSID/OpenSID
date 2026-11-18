$(function () {
  "use strict";

  var wrapper = $("<div />").addClass("alert alert-warning banner-notification d-flex align-items-center").attr("role", "alert");

  var textSpan = $("<span />").html("Website ini hanya sebagai demo aplikasi dengan fitur yg dibatasi, untuk mendapatkan akses penuh silakan kunjungi ");

  var link = $("<a />", { 
    href: "https://opendesa.id/"
  })
    .html("www.opendesa.id")
    .css("color", "inherit")
    .css("text-decoration", "underline");

  var messageDiv = $("<div />").css("flex", "1").append(textSpan).append(link);

  wrapper.append(messageDiv);

  $(".content-wrapper").first().prepend(wrapper);

  wrapper.hide(4).delay(500).slideDown();
});
