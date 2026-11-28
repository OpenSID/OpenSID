@extends('admin.layouts.index')

@section('title')
    <h1>Acak Data</h1>
@endsection

@section('breadcrumb')
    <li><a href="{{ url('database') }}">Pengaturan Database</a></li>
    <li class="active">Acak Data</li>
@endsection

@section('content')
    <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title"><strong>Acak Data</strong></h3>
        </div>
        <div class="box-body">
                <div class="alert alert-danger">
                    <strong><i class="fa fa-exclamation-triangle"></i> Peringatan!</strong>
                    Proses ini mengganti <em>semua</em> data identitas warga (NIK, nama, tanggal lahir,
                    nomor KK, telepon, e-mail, dan lainnya) dengan nilai sintetis, <strong>membersihkan
                    folder desa</strong>, lalu merapikan data agar lolos <em>/periksa</em>.
                    <strong>Data yang telah diacak tidak dapat dikembalikan.</strong>
                </div>

                <p>
                    Proses menggunakan algoritma HMAC-SHA256 berbasis <em>seed</em>.
                    Seed yang sama selalu menghasilkan output yang identik, sehingga proses
                    dapat diulang atau diverifikasi kapan saja.
                </p>

                <div class="form-group" style="max-width: 360px;">
                    <label for="acak-seed">Seed</label>
                    <input type="text" id="acak-seed" class="form-control input-sm"
                        value="{{ $seed ?? 'opensid-demo' }}" placeholder="opensid-demo">
                    <span class="help-block">
                        Gunakan seed yang sama untuk menghasilkan output identik di semua mesin.
                        Default: seed terakhir yang dipakai.
                    </span>
                </div>

                <div class="form-group" style="max-width: 360px;">
                    <label for="acak-foto">Foto warga &amp; pamong</label>
                    <select id="acak-foto" class="form-control input-sm">
                        <option value="blur" selected>Kaburkan wajah (butuh Python di server)</option>
                        <option value="avatar">Ganti dengan avatar kartun (deterministik)</option>
                        <option value="none">Kosongkan foto</option>
                    </select>
                </div>

                <div class="form-group" style="max-width: 360px;">
                    <label for="acak-foto-konten">Foto artikel, galeri &amp; media</label>
                    <select id="acak-foto-konten" class="form-control input-sm">
                        <option value="watermark" selected>Tempel watermark "FOTO DEMO"</option>
                        <option value="picsum">Ganti dengan foto acak (butuh internet)</option>
                        <option value="none">Biarkan apa adanya</option>
                    </select>
                    <span class="help-block">
                        Mode "blur" dan "foto acak" memerlukan dependensi tambahan; jika tidak
                        tersedia, proses tetap berjalan dan mencatat peringatan.
                    </span>
                </div>

                <div class="form-group" style="max-width: 360px;">
                    <label for="acak-surat">Arsip surat</label>
                    <select id="acak-surat" class="form-control input-sm">
                        <option value="sintetis" selected>Render ulang surat dari template (lebih lambat)</option>
                        <option value="placeholder">PDF placeholder "[ISI SURAT DIREDAKSI]"</option>
                    </select>
                    <span class="help-block">
                        "Render ulang" membentuk kembali isi surat dari template dengan data
                        sintetis; jika gagal untuk suatu surat, otomatis memakai placeholder.
                    </span>
                </div>

                <div class="checkbox">
                    <label>
                        <input type="checkbox" id="konfirmasi_sinkron">
                        Saya memastikan bahwa <strong>database</strong> dan <strong>folder desa</strong>
                        yang aktif saat ini berasal dari <strong>instalasi yang sama</strong>.
                        (Jika database baru saja di-restore dari instalasi lain, pastikan folder
                        desa yang sesuai juga sudah di-restore terlebih dahulu.)
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" id="konfirmasi_arsip">
                        Saya telah membuka
                        <a href="{{ url('database/bersih-folder') }}" target="_blank"><i class="fa fa-external-link"></i> Bersih Folder Desa</a>
                        dan telah meninjau file-file di <code>desa/arsip/</code> dan
                        <code>desa/upload/thumbs/</code> yang akan dihapus secara permanen
                        saat proses Acak Data berjalan.
                    </label>
                </div>

                <a href="#" id="btn-buka-modal-acak" class="btn btn-social btn-danger btn-sm disabled" aria-disabled="true">
                    <i class="fa fa-random"></i> Jalankan Acak Data
                </a>
        </div>
    </div>

    {{-- Modal konfirmasi → berubah menjadi tampilan progres (mengunci layar) saat berjalan --}}
    <div class="modal fade" id="confirm-acak" tabindex="-1" role="dialog" aria-labelledby="label-confirm-acak"
        data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true" id="acak-modal-x">&times;</button>
                    <h4 class="modal-title" id="label-confirm-acak">
                        <i class="fa fa-exclamation-triangle text-red"></i>&nbsp; Konfirmasi Acak Data
                    </h4>
                </div>
                <div class="modal-body" id="modal-body-acak">
                    <div class="alert alert-danger" style="margin-bottom: 12px;">
                        <strong><i class="fa fa-warning"></i> Tindakan ini tidak dapat dibatalkan.</strong>
                    </div>
                    <p>Seluruh data identitas warga — termasuk <strong>NIK, nama, tanggal lahir, nomor KK, telepon, dan e-mail</strong> — akan diganti dengan nilai sintetis secara permanen.</p>
                    <p>Sebelum melanjutkan, pastikan:</p>
                    <ul>
                        <li>Database asli <strong>sudah dibackup</strong> menggunakan fitur Backup di halaman Pengaturan Database.</li>
                        <li>Folder <code>desa/</code> <strong>sudah dibackup</strong> menggunakan fitur Backup/Restore Folder Desa.</li>
                        <li>Anda memahami bahwa data yang telah diacak <strong>tidak dapat dikembalikan</strong> ke nilai aslinya.</li>
                        <li>File di <code>desa/arsip/</code> dan <code>desa/upload/thumbs/</code> yang tidak dirujuk database akan <strong>dihapus permanen</strong> — Anda sudah meninjau daftarnya di atas dan menghilangkan centang file yang ingin dipertahankan.</li>
                    </ul>
                    <p class="text-muted"><small>Apakah Anda sudah melakukan backup dan siap melanjutkan?</small></p>
                </div>
                <div class="modal-footer" id="modal-footer-acak">
                    <button type="button" class="btn btn-social btn-default btn-sm" data-dismiss="modal" id="btn-acak-batal">
                        <i class="fa fa-arrow-left"></i> Belum, kembali
                    </button>
                    <button type="button" class="btn btn-social btn-danger btn-sm" id="btn-acak-lanjut">
                        <i class="fa fa-random"></i> Sudah backup, lanjutkan
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var openBtn = document.getElementById('btn-buka-modal-acak');
            var cbSin   = document.getElementById('konfirmasi_sinkron');
            var cbArs   = document.getElementById('konfirmasi_arsip');
            if (!openBtn || !cbSin || !cbArs || typeof window.jQuery === 'undefined') {
                return;
            }

            var bodyEl   = document.getElementById('modal-body-acak');
            var original = bodyEl.innerHTML;
            var footer   = document.getElementById('modal-footer-acak');
            var xBtn     = document.getElementById('acak-modal-x');
            var lanjut   = document.getElementById('btn-acak-lanjut');
            var batal    = document.getElementById('btn-acak-batal');

            // Aktifkan tombol buka-modal hanya bila kedua konfirmasi dicentang.
            function refresh() {
                if (cbSin.checked && cbArs.checked) {
                    openBtn.classList.remove('disabled');
                    openBtn.removeAttribute('aria-disabled');
                } else {
                    openBtn.classList.add('disabled');
                    openBtn.setAttribute('aria-disabled', 'true');
                }
            }
            cbSin.addEventListener('change', refresh);
            cbArs.addEventListener('change', refresh);
            refresh();

            openBtn.addEventListener('click', function (e) {
                e.preventDefault();
                if (openBtn.classList.contains('disabled')) {
                    return;
                }
                // Kembalikan modal ke keadaan konfirmasi setiap kali dibuka.
                bodyEl.innerHTML = original;
                footer.style.display = '';
                if (xBtn) { xBtn.style.display = ''; }
                lanjut.style.display = '';
                lanjut.disabled = false;
                lanjut.innerHTML = '<i class="fa fa-random"></i> Sudah backup, lanjutkan';
                batal.disabled = false;
                batal.innerHTML = '<i class="fa fa-arrow-left"></i> Belum, kembali';
                window.jQuery('#confirm-acak').modal('show');
            });

            lanjut.addEventListener('click', function () {
                lanjut.disabled = true;
                lanjut.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Memulai…';
                batal.disabled = true;

                var seedEl = document.getElementById('acak-seed');
                var seed   = seedEl ? seedEl.value.trim() : '';
                var fotoEl = document.getElementById('acak-foto');
                var foto   = fotoEl ? fotoEl.value : 'blur';
                var fkEl   = document.getElementById('acak-foto-konten');
                var fk     = fkEl ? fkEl.value : 'watermark';
                var surEl  = document.getElementById('acak-surat');
                var surat  = surEl ? surEl.value : 'sintetis';

                // Ubah modal menjadi progres yang mengunci: sembunyikan footer & tombol tutup.
                footer.style.display = 'none';
                if (xBtn) { xBtn.style.display = 'none'; }
                bodyEl.innerHTML =
                    '<div class="text-center" style="padding: 20px 0 10px;">' +
                        '<i class="fa fa-spinner fa-spin fa-2x text-muted"></i>' +
                    '</div>' +
                    '<p id="acak-step-text" class="text-center text-muted" style="margin-top: 12px; font-size: 13px;">Memulai proses…</p>' +
                    '<p class="text-center text-muted" style="font-size: 12px;">Mohon tunggu hingga proses selesai. Jangan menutup atau meninggalkan halaman ini.</p>';

                var startUrl = '{{ route('acak.jalankan') }}'
                    + '?seed=' + encodeURIComponent(seed)
                    + '&foto=' + encodeURIComponent(foto)
                    + '&foto_konten=' + encodeURIComponent(fk)
                    + '&surat=' + encodeURIComponent(surat);
                var statusUrl = '{{ route('acak.status') }}';

                var finished = false;
                var pollTimer = null;

                function fail(message) {
                    finished = true;
                    if (pollTimer) { clearTimeout(pollTimer); }
                    bodyEl.innerHTML =
                        '<div class="alert alert-danger" style="margin-bottom:0;"><i class="fa fa-times-circle"></i> ' +
                        '<strong>' + message + '</strong></div>';
                    footer.style.display = '';
                    if (xBtn) { xBtn.style.display = ''; }
                    lanjut.style.display = 'none';
                    batal.disabled = false;
                    batal.innerHTML = 'Tutup';
                }

                function setStep(text) {
                    var el = document.getElementById('acak-step-text');
                    if (el && text) { el.textContent = text; }
                }

                // Proses berjalan sebagai job latar belakang; halaman mem-poll status
                // sampai selesai/gagal. Tiap poll adalah request pendek, jadi kebal dari
                // timeout gateway (nginx/FPM) yang dulu memutus aliran SSE panjang.
                function poll(id) {
                    if (finished) { return; }
                    fetch(statusUrl + '?id=' + encodeURIComponent(id), { credentials: 'same-origin' })
                        .then(function (r) { return r.json(); })
                        .then(function (d) {
                            if (finished) { return; }
                            setStep(d.step || d.message);

                            if (d.status === 1 && d.redirect) {
                                finished = true;
                                setStep('Selesai! Mengarahkan ke halaman hasil…');
                                window.location.href = d.redirect;
                            } else if (d.status === -1) {
                                fail(d.message || 'Terjadi kesalahan saat mengacak data.');
                            } else if (d.status === 3) {
                                fail(d.message || 'Proses dibatalkan.');
                            } else {
                                pollTimer = setTimeout(function () { poll(id); }, 2000);
                            }
                        })
                        .catch(function () {
                            // Kegagalan sesaat saat poll bukan fatal — coba lagi.
                            if (!finished) {
                                pollTimer = setTimeout(function () { poll(id); }, 3000);
                            }
                        });
                }

                fetch(startUrl, { credentials: 'same-origin' })
                    .then(function (r) { return r.json().then(function (d) { return { ok: r.ok, body: d }; }); })
                    .then(function (res) {
                        if (!res.ok || !res.body || res.body.status !== true || !res.body.id) {
                            fail((res.body && res.body.message) || 'Gagal memulai proses acak.');
                            return;
                        }
                        setStep('Proses berjalan di latar belakang…');
                        poll(res.body.id);
                    })
                    .catch(function () {
                        fail('Gagal memulai proses acak.');
                    });
            });
        });
    </script>
@endsection
