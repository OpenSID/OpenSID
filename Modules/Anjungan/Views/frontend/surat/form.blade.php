@extends('anjungan::frontend.beranda.index')

@push('css')
    <style>
        .judul-surat {
            font-weight: bold;
            margin: 0px 0px 14px 14px;
        }

        .form-horizontal .control-label {
            text-align: left;
        }

        form#validasi:not(label) {
            font-size: medium;
        }

        hr {
            margin-top: 0;
            margin-bottom: 10px;
        }

        .syarat-surat {
            margin: 0 12px 0 16px;
            font-size: large;
        }

        .syarat-surat li {
            margin-left: -21px;
        }

        .form-surat-wrapper {
            height: 320px;
            overflow-y: scroll
        }

        .footer-button {
            margin-top: 10px;
        }

        .pdf-viewer {
            width: 100%;
            height: 75vh;
            /* Adjust to fit modal */
        }
    </style>
@endpush

@section('content')
    <!-- Mulai Kolom Kanan -->
    <div class="area-content">
        <div class="area-content-inner">
            <section class="content-header">
                <div id="wrapper-mandiri">
                    <h3 class="judul-surat">Surat {{ $surat['nama'] }}</h3>
                    <hr>
                    <div class="form-surat-wrapper">
                        @if ($syarat_surat)
                            <div class="syarat-surat">
                                <div class="alert alert-warning">
                                    <span>Syarat Surat :</span>
                                    <ol>
                                        @foreach ($syarat_surat as $syarat)
                                            <li>{{ $syarat }}</li>
                                        @endforeach
                                    </ol>
                                </div>
                            </div>
                            <hr>
                        @endif
                        {{-- body --}}
                        <form id="validasi" action="{{ route('anjungan.surat.kirim', $permohonan['id']) }}" method="POST" class="form-surat form-horizontal">
                            <input type="hidden" id="url_surat" name="url_surat" value="{{ $url }}">
                            <input type="hidden" id="url_remote" name="url_remote" value="{{ site_url('surat/nomor_surat_duplikat') }}">
                            <div class="form-group cari_nik">
                                <label for="nik" class="col-sm-3 control-label">NIK / Nama {{ $pemohon }}</label>
                                <div class="col-sm-6 col-lg-4">
                                    <select class="form-control input-sm readonly-permohonan readonly-periksa" id="nik" name="nik" style="width:100%;">
                                        @if ($individu)
                                            <option value="{{ $individu['id'] }}" selected>{{ $individu['nik'] . ' - ' . $individu['tag_id_card'] . ' - ' . $individu['nama'] }}</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                            @includeWhen($individu, 'layanan_mandiri.surat.form_konfirmasi_pemohon')
                            <div class="row jar_form">
                                <label for="nomor" class="col-sm-3"></label>
                                <div class="col-sm-8">
                                    <input class="required" type="hidden" name="nik" value="{{ $individu['id'] }}">
                                </div>
                            </div>
                            @include('admin.surat.nomor_surat')
                            @include('layanan_mandiri.surat.form_kode_isian')
                            @include('layanan_mandiri.surat.form_tgl_berlaku')
                            <div class="form-group">
                                <label for="nomor" class="col-sm-3">No. HP</label>
                                <div class="col-sm-8">
                                    <input
                                        class="form-control input-sm bilangan_spasi required "
                                        type="text"
                                        name="no_hp_aktif"
                                        id="no_hp_aktif"
                                        placeholder="Ketik No. HP"
                                        maxlength="14"
                                        value="{{ auth_mandiri()->telepon }}"
                                    >
                                </div>
                            </div>
                            @include('layanan_mandiri.surat.form_pamong')
                            <hr>
                    </div>
                    {{-- footer --}}
                    <div class="footer-button">
                        @if ($anjungan)
                            <button type="reset" class="btn btn-social btn-danger btn-sm">
                                <i class="fa fa-times"></i> Batal
                            </button>
                        @elseif ($periksa)
                            <a href="{{ base_url('permohonan_surat_admin/konfirmasi/' . $periksa['id']) }}" class="btn btn-social btn-danger btn-sm" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Konfirmasi Belum Lengkap">
                                <i class="fa fa-times"></i> Belum Lengkap
                            </a>
                        @else
                            <button type="reset" onclick="$('#validasi').trigger('reset');" class="btn btn-social btn-danger btn-sm">
                                <i class="fa fa-times"></i> Batal
                            </button>
                        @endif

                        @if ($anjungan)
                            <button type="button" id="kirim-surat" class="btn btn-social btn-success btn-sm pull-right" style="margin-right: 5px;">
                                <i class="fa fa-file-text"></i> Lanjut
                            </button>
                        @else
                            <button type="button" id="cetak-surat" onclick="tambah_elemen_cetak('cetak_pdf');" class="btn btn-social btn-info btn-sm pull-right" style="margin-right: 5px;">
                                <i class="fa fa-file-word-o"></i> Lanjutkan Cetak
                            </button>
                        @endif

                        {{-- end footer --}}
                        </form>
                    </div>
                    {{-- <textarea id="isian_form" hidden="hidden">{{ $isian_form }}</textarea> --}}
                </div>
                {{-- end body --}}
            </section>
        </div>
    </div>
    </div>
    <!-- Batas Kolom Kanan -->
@endsection

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            // Di form surat ubah isian admin menjadi disabled
            $("#wrapper-mandiri .readonly-permohonan").attr('disabled', true);
            $("#wrapper-mandiri .tdk-permohonan textarea").removeClass('required');
            $("#wrapper-mandiri .tdk-permohonan select").removeClass('required');
            $("#wrapper-mandiri .tdk-permohonan input").removeClass('required');

            // Di form surat ubah isian admin menjadi disabled
            $("#periksa-permohonan .readonly-periksa").attr('disabled', true);

            if ($('#isian_form').val()) {
                setTimeout(function() {
                    isi_form();
                }, 100);
            }

            $('#kirim-surat').on('click', function(e) {
                e.preventDefault();

                // Reset error sebelumnya
                $('.is-invalid').removeClass('is-invalid');

                let firstInvalidField = null;

                $('.required').each(function() {
                    if (!$(this).val().trim()) {
                        $(this).addClass('is-invalid');

                        // Simpan input pertama yang kosong untuk difokuskan
                        if (!firstInvalidField) {
                            firstInvalidField = $(this);
                        }
                    }
                });

                // Jika ada field yang kosong, fokus ke field pertama yang belum diisi
                if (firstInvalidField) {
                    firstInvalidField.focus();
                    let event = new KeyboardEvent('keydown', {
                        key: 'Enter',
                        bubbles: true
                    });
                    firstInvalidField[0].dispatchEvent(event);

                    return;
                }

                Swal.fire({
                    title: 'Membuat pratinjau..',
                    timerProgressBar: true,
                    didOpen: () => {
                        Swal.showLoading()
                    },
                    allowOutsideClick: () => false
                });

                $.ajax({
                    url: `{{ route('anjungan.surat.kirim', $permohonan['id']) }}?preview=true`,
                    type: 'post',
                    xhrFields: {
                        responseType: 'blob'
                    },
                    data: $("#validasi").serialize(),
                    success: function(response, status, xhr) {
                        var filename = "";
                        var disposition = xhr.getResponseHeader('Content-Disposition');
                        if (disposition) {
                            var filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
                            var matches = filenameRegex.exec(disposition);
                            if (matches !== null && matches[1]) filename = matches[1].replace(
                                /['"]/g, '');
                        }
                        try {
                            var blob = new Blob([response], {
                                type: 'application/pdf'
                            });
                            if (typeof window.navigator.msSaveBlob !== 'undefined') {
                                //   IE workaround for "HTML7007: One or more blob URLs were revoked by closing the blob for which they were created. These URLs will no longer resolve as the data backing the URL has been freed."
                                window.navigator.msSaveBlob(blob, filename);
                            } else {
                                var URL = window.URL || window.webkitURL;
                                var downloadUrl = URL.createObjectURL(blob);
                                Swal.fire({
                                    width: '90%',
                                    title: 'Pratinjau',
                                    html: `
                                        <object data="${downloadUrl}#toolbar=0" class="pdf-viewer" type="application/pdf"></object>
                                    `,
                                    showCancelButton: false,
                                    showConfirmButton: false,
                                    footer: `
                                        <button id="closeSwal" class="btn btn-social btn-danger btn-sm">
                                            <i class="fa fa-times"></i> Tutup
                                        </button>
                                        &ensp;
                                        <button id="printPdf" class="btn btn-social btn-success btn-sm">
                                            <i class="fa fa-print"></i> Cetak
                                        </button>
                                    `,
                                    allowOutsideClick: false,
                                    didOpen: () => {
                                        document.getElementById("closeSwal").addEventListener("click", () => Swal.close());
                                        document.getElementById("printPdf").addEventListener("click", () => cetak_pdf());
                                    }
                                });

                            }
                        } catch (ex) {
                            alert(ex);
                        }
                    }
                }).fail(function(response, status, xhr) {
                    Swal.fire({
                        title: xhr.statusText,
                        icon: 'error',
                        text: response.statusText,
                    })
                });
            });
        });

        function cetak_pdf() {
            Swal.fire({
                title: 'Membuat surat...',
                timerProgressBar: true,
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            $.ajax({
                url: `{{ route('anjungan.surat.kirim', $permohonan['id']) }}?preview=cetak`,
                type: 'POST',
                data: $("#validasi").serialize(),
                xhrFields: {
                    responseType: 'blob'
                },
                success: function(response, status, xhr) {
                    var filename = "document.pdf"; // Default filename
                    var disposition = xhr.getResponseHeader('Content-Disposition');

                    if (disposition) {
                        var filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
                        var matches = filenameRegex.exec(disposition);
                        if (matches !== null && matches[1]) {
                            filename = matches[1].replace(/['"]/g, '');
                        }
                    }

                    var blob = new Blob([response], {
                        type: 'application/pdf'
                    });
                    var downloadUrl = window.URL.createObjectURL(blob);

                    // Open PDF in new tab and auto-print
                    var win = window.open(downloadUrl);
                    if (win) {
                        $('#kirim-surat').hide();

                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: 'Surat Selesai Dibuat',
                            showConfirmButton: false,
                            timer: 1500
                        });

                        win.onload = function() {
                            win.print();
                            setTimeout(() => {
                                window.location.href = "{{ route('anjungan.permohonan') }}"; // Redirect after printing
                            }, 3000); // Delay to ensure print is triggered
                        };
                    } else {
                        Swal.fire({
                            title: "Popup blocked!",
                            text: "Izinkan pop-up untuk melihat dan mencetak PDF.",
                            icon: "warning"
                        });
                    }
                },
                error: function(xhr) {
                    Swal.fire({
                        title: 'Error!',
                        icon: 'error',
                        text: xhr.statusText || 'Terjadi kesalahan saat membuat surat.',
                    });
                }
            });
        }

        function isi_form() {
            var isian_form = JSON.parse($('#isian_form').val(), function(key, value) {

                if (key) {
                    var elem = $('*[name=' + key + ']');
                    elem.val(value);
                    elem.change();
                    // Kalau isian hidden, akan ada isian lain untuk menampilkan datanya
                    if (elem.is(":hidden")) {
                        var show = $('#' + key + '_show');
                        show.val(value);
                        show.change();
                    }
                }
            });
        }

        function tambah_elemen_cetak($nilai) {
            $('<input>').attr({
                type: 'hidden',
                name: 'submit_cetak',
                value: $nilai
            }).appendTo($('#validasi'));

            $('#validasi').submit();

            if ($('.box-body').find('.has-error').length < 1) {
                $('#next').removeClass('hide');
                $('#cetak-surat').remove();
            }
        }
    </script>
@endpush
