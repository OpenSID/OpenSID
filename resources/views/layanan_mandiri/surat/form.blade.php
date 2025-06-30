@extends('layanan_mandiri.layouts.index')

@push('css')
    <style>
        .judul-surat {
            /* margin: -10px; */
            font-size: 18px;
            font-weight: bold;
        }
    </style>
@endpush

@section('content')
    <div class="box box-solid" id="wrapper-mandiri">
        <div class="box-header with-border bg-green">
            <h4 class="box-title">Surat</h4>
        </div>
        <div class="box-body box-line">
            <h1 class="judul-surat">Surat {{ $surat['nama'] }}</h1>
            <div class="box-body permohonan-surat">

                <form id="validasi" action="{{ $form_action }}" method="POST" class="form-surat form-horizontal">
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
                    @include('layanan_mandiri.surat.form_pamong')

                </form>
                <textarea id="isian_form" hidden="hidden">{{ $isian_form }}</textarea>
            </div>
        </div>
        <div class="box-footer">
            @if ($mandiri)
                <button type="reset" onclick="window.history.back();" class="btn btn-social btn-danger btn-sm">
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

            @if ($mandiri)
                <button type="button" onclick="$('#validasi').attr('action', '{{ base_url('layanan-mandiri/surat/kirim/' . $permohonan['id']) }}'); $('#validasi').submit();" class="btn btn-social btn-success btn-sm pull-right" style="margin-right: 5px;">
                    <i class="fa fa-send"></i> Kirim
                </button>
            @else
                <button type="button" id="cetak-surat" onclick="tambah_elemen_cetak('cetak_pdf');" class="btn btn-social btn-info btn-sm pull-right" style="margin-right: 5px;">
                    <i class="fa fa-file-word-o"></i> Lanjutkan Cetak
                </button>
            @endif

            <a href="{{ base_url('keluar/masuk') }}" id="next" class="btn btn-social btn-info btn-sm btn-sm pull-right visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" style="display: none !important;">
                ke Permohonan Surat<i class="fa fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
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
