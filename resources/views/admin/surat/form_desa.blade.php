@include('admin.layouts.components.asset_validasi')

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
    <li class="breadcrumb-item"><a href="{{ ci_route('surat') }}">Daftar Cetak Surat</a></li>
    <li class="active"> Surat {{ ucwords($surat['nama']) }}</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')

    <div class="box box-info">
        <div class="box-header with-border">
            <a href="{{ site_url('surat') }}" class="btn btn-social btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Kembali Ke Daftar Wilayah">
                <i class="fa fa-arrow-circle-left "></i>Kembali Ke Daftar Cetak Surat
            </a>
        </div>
        <div class="box-body">
            {!! form_open($form_action, 'id="validasi" method="POST" class="form-surat form-horizontal"') !!}
            <input type="hidden" id="url_surat" name="url_surat" value="{{ $url }}">
            <input type="hidden" id="url_remote" name="url_remote" value="{{ site_url('surat/nomor_surat_duplikat') }}">

            @include('admin.surat.nomor_surat')

            @php
                $sumberDataPenduduk = !is_array($surat->form_isian->individu->data) ? [$surat->form_isian->individu->data] : $surat->form_isian->individu->data ?? [];
            @endphp
            @if ($judul_kategori['individu'] != '-')
                <div class="form-group subtitle_head" data-json='{!! json_encode($sumberDataPenduduk) !!}'>
                    <label class="col-sm-3 control-label" for="status">{{ str_replace('_', ' ', strtoupper($judul_kategori['individu'] ?? 'Keterangan Pemohon')) }}</label>
                    @includeWhen(count($sumberDataPenduduk) > 1, 'admin.surat.opsi_sumber_penduduk', ['opsiSumberPenduduk' => $surat->form_isian->individu->data, 'kategori' => 'individu', 'pendudukLuar' => $pendudukLuar])
                </div>
            @endif
            @if ($surat->form_isian->individu->info)
                <div class="callout callout-warning">
                    <b>{{ $surat->form_isian->individu->info }}</b>
                </div>
            @endif
            @includeWhen(in_array(1, $sumberDataPenduduk), 'admin.surat.penduduk_desa', ['opsiSumberPenduduk' => $surat->form_isian->individu->data, 'kategori' => 'individu'])
            @foreach ($pendudukLuar as $index => $penduduk)
                @includeWhen(in_array($index, $sumberDataPenduduk), 'admin.surat.penduduk_luar_desa', ['index' => $index, 'opsiSumberPenduduk' => $surat->form_isian->individu->data, 'kategori' => 'individu', 'input' => explode(',', $penduduk['input'])])
            @endforeach

            @include('admin.surat.kode_isian')

            @includeWhen(isset($form_kategori), 'admin.surat.kategori_isian')

            @includeWhen((int) $surat->masa_berlaku > 0, 'admin.surat.form_tgl_berlaku')

            @includeWhen(count($lampiran) > 0 && !empty($lampiran[0]), 'admin.surat.lampiran')

            @include('admin.surat.form_pamong')

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
            const hash = window.location.hash.substring(1)
            if (hash.length) {
                const dataPenduduk = hash.split('#')
                $('select[name="individu[nik]"]').append(`<option selected value="${dataPenduduk[0]}">NIK/Tag ID Card : ${dataPenduduk[1]} - ${decodeURIComponent(dataPenduduk[2])}</option>`)
                $('select[name="individu[nik]"]').trigger('change')
            }



            $('[data-visible-required=1]:visible').addClass('required')
            $('#nik').select2({
                ajax: {
                    url: SITE_URL + 'surat/apipenduduksurat',
                    dataType: 'json',
                    data: function(params) {
                        return {
                            q: params.term || '',
                            page: params.page || 1,
                            surat: $(this).data('surat'),
                        };
                    },
                    cache: true
                },
                placeholder: function() {
                    $(this).data('placeholder');
                },
                minimumInputLength: 1,
                allowClear: true,
                escapeMarkup: function(markup) {
                    return markup;
                },
            }).autofocus;

            $('.select2-nik-ajax').select2({
                ajax: {
                    url: function() {
                        return $(this).data('url');
                    },
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        let _kecuali = []
                        // jika tidak berulang maka batasi pencarian penduduk
                        if (!$(this).data('sumber_penduduk_berulang')) {
                            $(`select.select2-nik-ajax.isi-penduduk-desa`).not($(this)).each(function(index, item) {
                                if (item.value) _kecuali.push(item.value)
                            })
                        }

                        return {
                            q: params.term || '', // search term
                            page: params.page || 1,
                            filter_sex: $(this).data('filter-sex'),
                            surat: $(this).data('surat'),
                            kategori: $(this).data('kategori'),
                            hubungan: $(`select[name="${$(this).data('hubungan')}[nik]"]`).val(),
                            kecuali: _kecuali
                        };
                    },
                    processResults: function(data, params) {
                        // parse the results into the format expected by Select2
                        // since we are using custom formatting functions we do not need to
                        // alter the remote JSON data, except to indicate that infinite
                        // scrolling can be used
                        // params.page = params.page || 1;

                        return {
                            results: data.results,
                            pagination: data.pagination
                        };
                    },
                    cache: true
                },
                templateResult: function(penduduk) {
                    if (!penduduk.id) {
                        return penduduk.text;
                    }
                    var _tmpPenduduk = penduduk.text.split('\n');
                    var $penduduk = $(
                        '<div>' + _tmpPenduduk[0] + '</div><div>' + _tmpPenduduk[1] + '</div>'
                    );
                    return $penduduk;
                },
                placeholder: '--  Cari NIK / Tag ID Card / Nama Penduduk --',
                minimumInputLength: 1,
            });

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
