// ============================================================
// CSRF Utilities
// ============================================================

/**
 * Menyisipkan hidden input CSRF ke dalam form jika belum ada.
 * Hanya untuk form non-GET.
 * @param {HTMLFormElement} form
 */
function addCsrfField(form) {
    // Pastikan form.method ada dan string sebelum toUpperCase()
    if (!form.method || typeof form.method !== "string") {
        return;
    }

    if (form.method.toUpperCase() === "GET") {
        return;
    }

    const $form = $(form);
    const $input = $form.find(`input[name="${csrfParam}"]`);

    if (! $input.length) {
        $("<input>", {
            type : "hidden",
            name : csrfParam,
            value : $.cookie(csrfParam) || "",
        }).appendTo($form);
    }
}

/**
 * Menyisipkan CSRF field ke semua form yang ada di halaman
 * dan mendaftarkan listener untuk form yang di-submit secara dinamis.
 */
function csrf_semua_form() {
    $("form").each(function () {
        addCsrfField(this);
    });

    // Tangani form yang di-submit (termasuk yang dibuat secara dinamis)
    $(document).on("submit", "form", function () {
        addCsrfField(this);
    });
}

/**
 * Memperbarui nilai CSRF pada semua hidden input yang sudah ada di form,
 * biasanya dipanggil setelah AJAX selesai (token bisa diperbarui server).
 */
function refreshFormCsrf() {
    $(`form input[type="hidden"][name="${csrfParam}"]`).val($.cookie(csrfParam));
}

// ============================================================
// Submit Button Utilities
// ============================================================

/**
 * Menyimpan konten asli tombol submit sebelum diubah.
 * @param {HTMLElement} btn
 */
function storeOriginalSubmit(btn) {
    const $btn = $(btn);
    if (! $btn.data("originalSubmit")) {
        $btn.data("originalSubmit", btn.innerHTML);
    }
}

/**
 * Mengembalikan konten dan status tombol submit ke kondisi semula.
 * @param {HTMLElement} btn
 */
function restoreOriginalSubmit(btn) {
    const $btn = $(btn);
    const originalHtml = $btn.data("originalSubmit");

    if (originalHtml !== undefined) {
        btn.innerHTML = originalHtml;
        $btn.prop("disabled", false);
    }
}

// ============================================================
// AJAX Prefilter — sisipkan CSRF token ke semua request non-GET
// ============================================================

$.ajaxPrefilter(function (opts) {
    const safeMethods = ["HEAD", "GET", "OPTIONS"];

    if (opts.crossDomain || safeMethods.includes(opts.type?.toUpperCase())) {
        return;
    }

    const token = $.cookie(csrfParam);

    if (opts.data instanceof FormData) {
        opts.data.append(csrfParam, token);
    } else {
        const existing = opts.data ? `${opts.data}&` : "";
        opts.data = `${existing}${csrfParam}=${encodeURIComponent(token)}`;
    }
});

// ============================================================
// Document Ready
// ============================================================

$(function () {
    csrf_semua_form();

    // Nonaktifkan tombol submit & tampilkan spinner saat form dikirim
    $(document).on("submit", "form", function () {
        const $form = $(this);
        const $btn = $form
            .find("button[type=submit]:enabled:visible, input[type=submit]:enabled:visible")
            .first();

        if ($btn.length) {
            storeOriginalSubmit($btn[0]);
            if ($btn.is("button")) {
                $btn.prop("disabled", true)
                    .html("<i class='fa fa-spinner fa-spin'></i> Mohon tunggu...");
            }
        }

        // Jika form disubmit via AJAX, restore tombol submit setelah AJAX selesai
        // Deteksi submit via AJAX dengan event ajaxComplete pada form ini
        const restoreBtn = function () {
            if ($btn.length) {
                restoreOriginalSubmit($btn[0]);
            }
            $(document).off('ajaxComplete', restoreBtn);
        };
        $(document).on('ajaxComplete', restoreBtn);
    });

    // Pulihkan tombol submit jika validasi jQuery Validate gagal
    $(document).on("invalid-form.validate", "form", function () {
        $(this).find("button[type=submit], input[type=submit]").each(function () {
            restoreOriginalSubmit(this);
        });
    });

    // Perbarui CSRF token di semua form setiap kali AJAX selesai
    $(document).ajaxComplete(function () {
        refreshFormCsrf();
    });
});
