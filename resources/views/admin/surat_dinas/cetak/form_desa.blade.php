@include('admin.layouts.components.asset_validasi')
@include('admin.layouts.components.datetime_picker')

@extends('admin.layouts.index')

@push('css')
    <style>
        .batas {
            margin-right: -10px;
            margin-left: -10px;
            border-top: 1px solid #f4f4f4;
        }

        .form-horizontal .form-group {
            margin-right: -10px;
            margin-left: -10px;
        }

        .subtitle_head {
            margin-left: -10px;
            margin-right: -10px;
            /* background-color: #d81b60 !important; */
        }

        .subtitle_head label {
            padding-left: 10px;
            padding-right: 10px;
            padding-top: 5px;
            padding-bottom: 5px;
            margin-bottom: 0px;
            /* color: #ffffff !important; */
        }
    </style>
@endpush

@section('title')
    <h1>
        Surat {{ ucwords($surat['nama']) }}
    </h1>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ ci_route('surat_dinas_cetak') }}">Daftar Cetak Surat Dinas </a></li>
    <li class="active"> Surat {{ ucwords($surat['nama']) }}</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')

    <div class="box box-info">
        <div class="box-header with-border">
            <a href="{{ ci_route('surat_dinas_cetak') }}" class="btn btn-social btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Kembali Ke Daftar Wilayah">
                <i class="fa fa-arrow-circle-left "></i>Kembali Ke Daftar Cetak Surat Dinas
            </a>
        </div>
        <div class="box-body">
            {!! form_open($form_action, 'id="validasi" method="POST" class="form-surat form-horizontal"') !!}
            <input type="hidden" id="url_surat" name="url_surat" value="{{ $url }}">
            <input type="hidden" id="url_remote" name="url_remote" value="{{ ci_route('surat_dinas_cetak.nomor_surat_duplikat') }}">

            @include('admin.surat_dinas.cetak.nomor_surat')

            @if ($judul_kategori['individu'] != '-' && $surat['kode_isian'])
                <div class="form-group subtitle_head">
                    <label class="col-sm-3 control-label" for="status">{{ str_replace('_', ' ', strtoupper($judul_kategori['individu'] ?? 'Keterangan')) }}</label>
                </div>
            @endif
            @if ($surat->form_isian->individu->info)
                <div class="callout callout-warning">
                    <b>{{ $surat->form_isian->individu->info }}</b>
                </div>
            @endif

            @include('admin.surat_dinas.cetak.kode_isian')

            @includeWhen(isset($form_kategori), 'admin.surat_dinas.cetak.kategori_isian')

            @includeWhen((int) $surat->masa_berlaku > 0, 'admin.surat_dinas.cetak.form_tgl_berlaku')

            @includeWhen(count($lampiran) > 0 && !empty($lampiran[0]), 'admin.surat_dinas.cetak.lampiran')

            @include('admin.surat_dinas.cetak.sifat_surat')

            @include('admin.surat_dinas.cetak.surat_keluar')

            @include('admin.surat_dinas.cetak.form_pamong')

        </div>
        <div class="box-footer">
            <button type="reset" class="btn btn-social btn-danger btn-sm" onclick="reset_form($(this).val());"><i class="fa fa-times"></i> Batal</button>
            <button type="submit" class="btn btn-social btn-info btn-sm pull-right"><i class="fa fa-check"></i>
                Simpan</button>
        </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript">
        function pilihAnggota(elm) {
            let _checked = $(elm).is(':checked')

            if (_checked) {
                $('table.kis tr[data-row=' + $(elm).val() + '] input').prop('disabled', 0)
                $('table.kis tr[data-row=' + $(elm).val() + '] input.datepicker').datepicker({
                    weekStart: 1,
                    language: 'id',
                    format: 'dd-mm-yyyy',
                    autoclose: true
                });
            } else {
                $('table.kis tr[data-row=' + $(elm).val() + '] input').prop('disabled', 1)
            }
        }
        $('document').ready(function() {
            $('[data-visible-required=1]:visible').addClass('required')
            // kaitkan data 
            $('select[data-kaitkan]').each(function() {
                let _kaitkan = $(this).data('kaitkan')
                let _kategori = $(this).closest('.form-group').data('kategori')

                _kaitkan.forEach(element => {
                    for (let i in element.kode_isian_terkait) {
                        let _namaElm = element.kode_isian_terkait[i].replaceAll(/\s+/g, '_').toLowerCase()
                        $(`[name=${_namaElm}]`).removeClass('required')
                        $(`[name=${_namaElm}]`).closest('.form-group').addClass('hide')
                    }

                    for (let i in element.kategori_terkait) {
                        let _namaKategori = element.kategori_terkait[i]
                        $(`#kategori-${_namaKategori}`).addClass('hide')
                    }
                });

                $(this).change(function() {
                    let _aktifkanElm = $(this).data('kaitkan')
                    let _namaElm, _kategori = $(this).closest('.form-group').data('kategori')
                    _aktifkanElm.forEach(element => {
                        for (let j in element.kode_isian_terkait) {
                            _namaElm = element.kode_isian_terkait[j].replaceAll(/\s+/g, '_').toLowerCase()
                            $(`[name=${_namaElm}]`).removeClass('required')
                            $(`[name=${_namaElm}]`).closest('.form-group').addClass('hide')
                        }
                        for (let j in element.kategori_terkait) {
                            _namaKategori = element.kategori_terkait[j]
                            $(`#kategori-${_namaKategori}`).addClass('hide')
                        }
                        for (let i in element.nilai_isian) {
                            if (element.nilai_isian[i].includes($(this).val())) {
                                for (let j in element.kode_isian_terkait) {
                                    _namaElm = element.kode_isian_terkait[j].replaceAll(/\s+/g, '_').toLowerCase()
                                    $(`[name=${_namaElm}]`).addClass('required')
                                    $(`[name=${_namaElm}]`).closest('.form-group').removeClass('hide')
                                }

                            }
                        }
                        for (let i in element.nilai_isian) {
                            if (element.nilai_isian[i].includes($(this).val())) {
                                for (let j in element.kategori_terkait) {
                                    _namaKategori = element.kategori_terkait[j]
                                    $(`#kategori-${_namaKategori}`).removeClass('hide')
                                }

                            }
                        }
                    });
                })
            })
        });
    </script>
@endpush
