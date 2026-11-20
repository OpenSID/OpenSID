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
            type  : "hidden",
            name  : csrfParam,
            value : getCsrfToken(),
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
 * Menyimpan konten asli tombol sebelum diubah.
 * Gunakan === undefined agar innerHTML kosong pun tersimpan dengan benar.
 * @param {HTMLElement} btn
 */
function storeOriginalSubmit(btn) {
    const $btn = $(btn);
    if ($btn.data("originalSubmit") === undefined) {
        const original = $btn.is("input") ? $btn.val() : btn.innerHTML;
        $btn.data("originalSubmit", original);
    }
}

/**
 * Mengembalikan konten dan status tombol ke kondisi semula.
 * Mendukung tag <button>, <input[type=submit]>, maupun <a>.
 * @param {HTMLElement} btn
 */
function restoreOriginalSubmit(btn) {
    const $btn = $(btn);
    const original = $btn.data("originalSubmit");

    if (original === undefined) {
        return;
    }

    if ($btn.is("input")) {
        $btn.val(original).prop("disabled", false);
    } else if ($btn.is("a")) {
        $btn[0].innerHTML = original;
        $btn.removeClass("disabled").css("pointer-events", "");
    } else {
        $btn[0].innerHTML = original;
        $btn.prop("disabled", false);
    }

    // Hapus data agar bisa di-store ulang di submit berikutnya
    $btn.removeData("originalSubmit");
}

/**
 * Disable tombol dan tampilkan spinner.
 * Mendukung tag <button>, <input[type=submit]>, maupun <a>.
 * @param {HTMLElement|jQuery} btn
 */
function disableBtn(btn) {
    const $btn = $(btn);

    // Simpan konten/value asli tombol agar bisa di-restore setelah request selesai
    storeOriginalSubmit($btn[0]);

    // Cek apakah tombol memiliki teks (selain icon/spinner),
    // karena ada tombol yang hanya berisi icon tanpa teks
    let hasText = false;
    if ($btn.is("input")) {
        hasText = !!$btn.val().trim();
    } else {
        // Gunakan .text() bukan .html() agar tag <i> (icon) tidak ikut terhitung sebagai teks
        hasText = !!$btn.text().trim();
    }

    const spinner = "<i class='fa fa-spinner fa-spin'></i>";
    const spinnerText = `${spinner} Mohon tunggu...`;

    // Tombol pagination DataTable cukup tampilkan spinner tanpa teks "Mohon tunggu..."
    // karena teksnya sudah berupa angka/navigasi (1, 2, Sebelumnya, Selanjutnya)
    // yang akan tergantikan jika ditambah teks panjang
    const isPagination = $btn.closest('.dataTables_paginate').length > 0;

    if ($btn.is("input")) {
        // input[type=submit] tidak support innerHTML, perubahan konten harus via .val()
        $btn.val(hasText ? "Mohon tunggu..." : "").prop("disabled", true);
    } else if ($btn.is("a")) {
        // Tag <a> tidak support atribut disabled secara native,
        // gunakan class "disabled" + pointer-events untuk mencegah klik berikutnya
        $btn.html(isPagination || !hasText ? spinner : spinnerText)
            .addClass("disabled")
            .css("pointer-events", "none");
    } else {
        // <button> support .prop("disabled") secara native
        $btn.html(isPagination || !hasText ? spinner : spinnerText)
            .prop("disabled", true);
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
    } else if (opts.data !== null && typeof opts.data === 'object') {
        // DataTables (dan jQuery AJAX lain) mengirim data sebagai object —
        // jangan di-stringify, tambahkan property langsung
        opts.data[csrfParam] = token;
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

    // --------------------------------------------------------
    // ajaxSend — disable tombol & tampilkan spinner
    // Handles button, input[type=submit], dan tag <a>.
    // Simpan referensi tombol di jqXHR agar bisa di-restore
    // di ajaxComplete (saat ajaxComplete, activeElement sudah
    // berpindah ke body karena tombol dalam kondisi disabled).
    // --------------------------------------------------------
    $(document).ajaxSend(function (event, jqXHR, opts) {
        const btn = document.activeElement;

        if (! btn || ! $(btn).is("button, a, input[type=submit], input[type=button]")) {
            return;
        }

        disableBtn(btn);

        // Simpan di jqXHR agar ajaxComplete bisa restore tombol yang sama
        jqXHR.triggerBtn = btn;
    });

    // --------------------------------------------------------
    // ajaxComplete — restore tombol & refresh CSRF token
    // --------------------------------------------------------
    $(document).ajaxComplete(function (event, jqXHR, opts) {
        refreshFormCsrf();

        if (jqXHR.triggerBtn) {
            restoreOriginalSubmit(jqXHR.triggerBtn);
        }
    });

    // --------------------------------------------------------
    // Form submit — disable tombol & tampilkan spinner
    // Hanya untuk button dan input[type=submit] karena tag <a>
    // tidak bisa trigger submit form secara native.
    // Jika full page submit, browser akan reset tombol sendiri.
    // Jika via AJAX, ajaxComplete yang akan restore.
    // --------------------------------------------------------
    $(document).on("submit", "form", function () {
        const $btn = $(this)
            .find("button[type=submit]:enabled:visible, input[type=submit]:enabled:visible")
            .first();

        if (! $btn.length) {
            return;
        }

        disableBtn($btn);
    });
});