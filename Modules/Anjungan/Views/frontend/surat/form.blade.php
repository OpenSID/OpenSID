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
                            <button type="submit" id="kirim-surat" class="btn btn-social btn-success btn-sm pull-right" style="margin-right: 5px;">
                                <i class="fa fa-file-text"></i> Kirim
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
        });

        $(document).ready(function() {
            // Di form surat ubah isian admin menjadi disabled
            $("#periksa-permohonan .readonly-periksa").attr('disabled', true);

            if ($('#isian_form').val()) {
                setTimeout(function() {
                    isi_form();
                }, 100);
            }
        });

        document.getElementById('validasi').addEventListener('submit', function(event) {
            // event.preventDefault();

            setTimeout(() => {
                const form = document.getElementById('validasi');
                const elementsWithError = form.querySelectorAll('.has-error');
                console.log(elementsWithError.length);
                if (elementsWithError.length < 1) {
                    Swal.fire({
                        title: 'Surat siap cetak, menunggu verifikasi operator',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    })
                }

            }, 200);
        });

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
