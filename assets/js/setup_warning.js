$(function () {
  "use strict";

  // Cek apakah ada data setup warning di window object
  if (typeof SETUP_WARNING !== 'undefined' && SETUP_WARNING) {
    var setupWarning = SETUP_WARNING;
    var alertClass = setupWarning.icon === 'warning' ? 'alert-warning' : 'alert-info';

    // Create wrapper menggunakan Bootstrap alert class
    var wrapper = $("<div />").addClass(`alert ${alertClass} banner-notification d-flex align-items-center`).attr("role", "alert");

    // Create message with action link
    var messageText = $("<span />").html(setupWarning.message + " ");
    
    var actionBtn = $("<a />", {
      href: setupWarning.redirect_url,
      html: setupWarning.button_text,
    }).css("color", "inherit").css("text-decoration", "underline");
    
    var message = $("<span />").css("flex", "1").append(messageText).append(actionBtn);

    // Create close button
    var closeBtn = $("<button />", {
      type: "button",
      class: "close",
      html: "&times;",
      click: function () {
        wrapper.slideUp(function () {
          $(this).remove();
        });
      },
    });

    wrapper.append(message).append(closeBtn);
    $(".content-wrapper").first().prepend(wrapper);

    wrapper.hide(4).delay(500).slideDown();
  }
});