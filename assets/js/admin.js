$(".file-browser").click(function () {
  $(this).closest(".input-group").find(".file-input").click();
});

$(".file-path").click(function () {
  $(this).closest(".input-group").find(".file-browser").click();
});

$(".file-input").change(function () {
  var inputGroup = $(this).closest(".input-group");
  var filePath = $(this).val().split("\\").pop();
  inputGroup.find(".file-path").val(filePath);

  var preview = $(this).closest(".preview-img");
  var imgPreview = preview.find("img");

  previewImage(this, imgPreview);
});

/**
 * Menampilkan pratinjau gambar sebelum diunggah ke server.
 * Dilengkapi validasi berlapis untuk mencegah eksekusi file berbahaya (XSS via SVG, MIME spoofing, dll).
 *
 * @param {HTMLInputElement} input  - Elemen input file yang dipilih pengguna.
 * @param {string|jQuery}    target - Selector CSS atau jQuery object elemen <img> tujuan pratinjau.
 */
function previewImage(input, target) {
  /**
   * Konfigurasi validasi file unggahan.
   *
   * @property {number}   maxSizeBytes      - Batas maksimal ukuran file dalam byte (default: 2MB).
   * @property {string[]} allowedMimes      - Daftar MIME type yang diizinkan.
   * @property {string[]} allowedExtensions - Daftar ekstensi file yang diizinkan.
   */
  var IMAGE_UPLOAD_CONFIG = {
    maxSizeBytes: 2 * 1024 * 1024,
    allowedMimes: ["image/jpeg", "image/png", "image/gif", "image/webp"],
    allowedExtensions: ["jpg", "jpeg", "png", "gif", "webp"],
  };

  /**
   * Tanda tangan magic bytes untuk setiap format gambar yang didukung.
   * Digunakan untuk memverifikasi tipe file sesungguhnya dari isi biner,
   * bukan hanya dari atribut file.type yang dapat dimanipulasi.
   *
   * @property {number[]} bytes  - Urutan byte penanda format file.
   * @property {number}   offset - Posisi awal byte dalam file (0 = dari awal).
   */
  var MAGIC_BYTES = {
    jpeg: { bytes: [0xFF, 0xD8, 0xFF], offset: 0 },
    png:  { bytes: [0x89, 0x50, 0x4E, 0x47, 0x0D, 0x0A, 0x1A, 0x0A], offset: 0 },
    gif:  { bytes: [0x47, 0x49, 0x46, 0x38], offset: 0 },
    webp: { bytes: [0x57, 0x45, 0x42, 0x50], offset: 8 }, // RIFF????WEBP — penanda WEBP dimulai di byte ke-8
  };

  // Hentikan eksekusi jika input tidak memiliki file yang dipilih.
  if (!input.files || !input.files[0]) return;

  var file = input.files[0];

  // --- Layer 1: Validasi MIME Type ---
  // file.type dibaca dari metadata OS/browser, bukan dari isi file.
  // Layer ini hanya sebagai filter awal sebelum pengecekan yang lebih ketat.
  if (!IMAGE_UPLOAD_CONFIG.allowedMimes.includes(file.type)) {
    input.value = "";
    if (typeof swal !== "undefined" && typeof swal.fire === "function") {
      swal.fire({
        title: "Upload Gagal",
        text: "Format file tidak didukung. Gunakan JPG, PNG, GIF, atau WEBP.",
        icon: "warning",
        confirmButtonText: "OK",
      });
    } else {
      alert("Format file tidak didukung. Gunakan JPG, PNG, GIF, atau WEBP.");
    }
    return;
  }

  // --- Layer 2: Validasi Ekstensi File ---
  // Menangkap teknik double extension (contoh: evil.svg.png) yang lolos dari validasi MIME type.
  var ext = file.name.split(".").pop().toLowerCase();
  if (!IMAGE_UPLOAD_CONFIG.allowedExtensions.includes(ext)) {
    input.value = "";
    if (typeof swal !== "undefined" && typeof swal.fire === "function") {
      swal.fire({
        title: "Upload Gagal",
        text: "Ekstensi file tidak didukung. Gunakan JPG, PNG, GIF, atau WEBP.",
        icon: "warning",
        confirmButtonText: "OK",
      });
    } else {
      alert("Ekstensi file tidak didukung. Gunakan JPG, PNG, GIF, atau WEBP.");
    }
    return;
  }

  // --- Layer 3: Validasi Ukuran File ---
  // Mencegah serangan DoS berupa unggahan file berukuran besar yang dapat
  // menghabiskan memori browser dan menyebabkan tab crash (memory exhaustion).
  if (file.size > IMAGE_UPLOAD_CONFIG.maxSizeBytes) {
    input.value = "";
    if (typeof swal !== "undefined" && typeof swal.fire === "function") {
      swal.fire({
        title: "Upload Gagal",
        text: "Ukuran file terlalu besar. Maksimal 2MB.",
        icon: "warning",
        confirmButtonText: "OK",
      });
    } else {
      alert("Ukuran file terlalu besar. Maksimal 2MB.");
    }
    return;
  }

  // --- Layer 4: Validasi Magic Bytes ---
  // Membaca 12 byte pertama file secara biner untuk memverifikasi format sesungguhnya.
  // Menangkap serangan MIME spoofing di mana penyerang mengganti nama evil.svg menjadi evil.png
  // agar lolos dari Layer 1 dan Layer 2, namun isi file tetap berbahaya.

  // Batalkan FileReader sebelumnya jika user memilih file baru sebelum proses selesai.
  // Mencegah race condition yang dapat menyebabkan data tercampur antar file.
  if (input._activeReader) input._activeReader.abort();

  var headerReader = new FileReader();
  input._activeReader = headerReader; // Simpan referensi reader aktif untuk keperluan abort.

  headerReader.onload = function (e) {
    var arr = new Uint8Array(e.target.result);

    // Cocokkan byte awal file dengan tanda tangan masing-masing format.
    // File dinyatakan valid jika setidaknya satu format cocok pada offset yang benar.
    var isValid = Object.values(MAGIC_BYTES).some(function (sig) {
      return sig.bytes.every(function (byte, i) {
        return arr[sig.offset + i] === byte;
      });
    });

    if (!isValid) {
      input.value = "";
      if (typeof swal !== "undefined" && typeof swal.fire === "function") {
        swal.fire({
          title: "Upload Gagal",
          text: "File tidak valid. Isi file tidak sesuai dengan formatnya.",
          icon: "warning",
          confirmButtonText: "OK",
        });
      } else {
        alert("File tidak valid. Isi file tidak sesuai dengan formatnya.");
      }
      return;
    }

    // Semua layer validasi lolos — baca file sebagai Data URI untuk ditampilkan di pratinjau.
    var previewReader = new FileReader();
    input._activeReader = previewReader; // Perbarui referensi reader aktif.

    previewReader.onload = function (ev) {
      // Gunakan $(target) untuk memastikan kompatibilitas jQuery
      // sekaligus menghindari TypeError apabila target adalah raw DOM element.
      $(target).attr("src", ev.target.result);
    };

    previewReader.readAsDataURL(file);
  };

  // Baca hanya 12 byte pertama untuk efisiensi — cukup untuk mencocokkan semua magic bytes.
  headerReader.readAsArrayBuffer(file.slice(0, 12));
}

// Notifikasi
window.setTimeout(function () {
  $("#notifikasi")
    .fadeTo(500, 0)
    .slideUp(500, function () {
      $(this).remove();
    });
}, 5000);

// Sidebar
if (
  typeof Storage !== "undefined" &&
  localStorage.getItem("sidebar") === "false"
) {
  $("#sidebar_collapse").addClass("sidebar-collapse");
}

// notifikasi swetalert
function _error(pesan) {
  Swal.fire({
    title: "Gagal!",
    html: pesan,
    icon: "error",
    confirmButtonText: "OK",
    timer: 5000,
  });
}

function _success(pesan) {
  Swal.fire({
    title: "Berhasil!",
    html: pesan,
    icon: "success",
    confirmButtonText: "OK",
    timer: 5000,
  });
}

$(".sidebar-toggle").on("click", function () {
  localStorage.setItem(
    "sidebar",
    $("#sidebar_collapse").hasClass("sidebar-collapse"),
  );
});

// Select2 default
$(".select2").select2();

//CheckBox All Selected
checkAllHeader("id_cb[]");
checkAllBody("#checkall", "#tabeldata", "id_cb[]");

function checkAllHeader(name = "id_cb[]") {
  $("table").on("click", "input[name='" + name + "']", function () {
    enableHapusTerpilih(name);
  });
  enableHapusTerpilih(name);
}

function checkAllBody(
  id = "#checkall",
  tabel = "#tabeldata",
  name = "id_cb[]",
) {
  $("table").on("click", id, function () {
    if ($(this).is(":checked")) {
      $(tabel + " input[type=checkbox]").each(function () {
        $(this).prop("checked", true);
      });
    } else {
      $(tabel + " input[type=checkbox]").each(function () {
        $(this).prop("checked", false);
      });
    }
    $(tabel + " input[type=checkbox]").change();
    enableHapusTerpilih(name);
  });
  $("[data-toggle=tooltip]").tooltip();
}

function enableHapusTerpilih(name = "id_cb[]") {
  if ($("input[name='" + name + "']:checked:not(:disabled)").length <= 0) {
    $(".aksi-terpilih").addClass("disabled");
    $(".hapus-terpilih").addClass("disabled");
    $(".hapus-terpilih").attr("href", "#");
  } else {
    $(".aksi-terpilih").removeClass("disabled");
    $(".hapus-terpilih").removeClass("disabled");
    $(".hapus-terpilih").attr("href", "#confirm-delete");
  }
}

/*
 * Fixes the search menu on mobile
 * Todo: hapus fungsi di bawah ini jika melakukan upgrade adminlte ke >= 4.2.1
 */
+(function ($) {
  "use strict";

  var DataKey = "lte.layout";

  var Default = {
    slimscroll: true,
    resetHeight: true,
  };

  var Selector = {
    wrapper: ".wrapper",
    contentWrapper: ".content-wrapper",
    layoutBoxed: ".layout-boxed",
    mainFooter: ".main-footer",
    mainHeader: ".main-header",
    sidebar: ".sidebar",
    controlSidebar: ".control-sidebar",
    fixed: ".fixed",
    sidebarMenu: ".sidebar-menu",
    logo: ".main-header .logo",
  };

  var ClassName = {
    fixed: "fixed",
    holdTransition: "hold-transition",
  };

  var Layout = function (options) {
    this.options = options;
    this.bindedResize = false;
    this.activate();
  };

  Layout.prototype.activate = function () {
    this.fix();
    this.fixSidebar();

    $("body").removeClass(ClassName.holdTransition);

    if (this.options.resetHeight) {
      $("body, html, " + Selector.wrapper).css({
        height: "auto",
        "min-height": "100%",
      });
    }

    if (!this.bindedResize) {
      $(window).resize(
        function () {
          this.fix();
          this.fixSidebar();

          $(Selector.logo + ", " + Selector.sidebar).one(
            "webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend",
            function () {
              this.fix();
              this.fixSidebar();
            }.bind(this),
          );
        }.bind(this),
      );

      this.bindedResize = true;
    }

    $(Selector.sidebarMenu).on(
      "expanded.tree",
      function () {
        this.fix();
        this.fixSidebar();
      }.bind(this),
    );

    $(Selector.sidebarMenu).on(
      "collapsed.tree",
      function () {
        this.fix();
        this.fixSidebar();
      }.bind(this),
    );
  };

  Layout.prototype.fix = function () {
    // Remove overflow from .wrapper if layout-boxed exists
    $(Selector.layoutBoxed + " > " + Selector.wrapper).css(
      "overflow",
      "hidden",
    );

    // Get window height and the wrapper height
    var footerHeight = $(Selector.mainFooter).outerHeight() || 0;
    var neg = $(Selector.mainHeader).outerHeight() + footerHeight;
    var windowHeight = $(window).height();
    var sidebarHeight = $(Selector.sidebar).height() || 0;

    // Set the min-height of the content and sidebar based on
    // the height of the document.
    if ($("body").hasClass(ClassName.fixed)) {
      $(Selector.contentWrapper).css("min-height", windowHeight - footerHeight);
    } else {
      var postSetHeight;

      if (windowHeight >= sidebarHeight) {
        $(Selector.contentWrapper).css("min-height", windowHeight - neg);
        postSetHeight = windowHeight - neg;
      } else {
        $(Selector.contentWrapper).css("min-height", sidebarHeight);
        postSetHeight = sidebarHeight;
      }

      // Fix for the control sidebar height
      var $controlSidebar = $(Selector.controlSidebar);
      if (typeof $controlSidebar !== "undefined") {
        if ($controlSidebar.height() > postSetHeight)
          $(Selector.contentWrapper).css(
            "min-height",
            $controlSidebar.height(),
          );
      }
    }
  };

  Layout.prototype.fixSidebar = function () {
    // Make sure the body tag has the .fixed class
    if (!$("body").hasClass(ClassName.fixed)) {
      if (typeof $.fn.slimScroll !== "undefined") {
        $(Selector.sidebar).slimScroll({ destroy: true }).height("auto");
      }
      return;
    }

    // Enable slimscroll for fixed layout
    if (this.options.slimscroll) {
      if (typeof $.fn.slimScroll !== "undefined") {
        // Destroy if it exists
        // $(Selector.sidebar).slimScroll({ destroy: true }).height('auto')

        // Add slimscroll
        $(Selector.sidebar).slimScroll({
          height: $(window).height() - $(Selector.mainHeader).height() + "px",
          color: "rgba(0,0,0,0.2)",
          size: "3px",
        });
      }
    }
  };

  // Plugin Definition
  // =================
  function Plugin(option) {
    return this.each(function () {
      var $this = $(this);
      var data = $this.data(DataKey);

      if (!data) {
        var options = $.extend(
          {},
          Default,
          $this.data(),
          typeof option === "object" && option,
        );
        $this.data(DataKey, (data = new Layout(options)));
      }

      if (typeof option === "string") {
        if (typeof data[option] === "undefined") {
          throw new Error("No method named " + option);
        }
        data[option]();
      }
    });
  }

  var old = $.fn.layout;

  $.fn.layout = Plugin;
  $.fn.layout.Constuctor = Layout;

  // No conflict mode
  // ================
  $.fn.layout.noConflict = function () {
    $.fn.layout = old;
    return this;
  };

  // Layout DATA-API
  // ===============
  Plugin.call($("body"));
})(jQuery);

$(document).ready(function () {
  $('.autoselect').on('click', function() {
    if($(this).val() == 0) {
      $(this).select();
    }    
  });
});