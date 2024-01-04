@include('admin.layouts.components.asset_datatables')
@include('admin.layouts.components.datetime_picker')
@extends('admin.layouts.index')

@section('title')
    <h1>
        Data Calon Pemilih
    </h1>
@endsection

@section('breadcrumb')
    <li class="active">Data Calon Pemilih</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')
    <div class="box box-info">
        <div class="box-header with-border">
            <div class="col-sm-8 col-lg-9">
                <div class="row">
                    <a href="{{ site_url('pemilihan') }}" class="btn btn-social btn-success btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Daftar Pemilihan"><i class="fa fa-list"></i>Daftar Pemilihan</a>
                    <a
                        href="{{ ci_route('dpt.ajax_cetak.cetak') }}"
                        class="btn btn-social bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"
                        title="Cetak Data"
                        target="_blank"
                        data-remote="false"
                        data-toggle="modal"
                        data-target="#modalBox"
                        data-title="Cetak Data"
                    ><i class="fa fa-print "></i> Cetak</a>
                    <a
                        href="{{ ci_route('dpt.ajax_cetak.unduh') }}"
                        class="btn btn-social bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"
                        title="Unduh Data"
                        data-remote="false"
                        data-toggle="modal"
                        data-target="#modalBox"
                        data-title="Unduh Data"
                        target="_blank"
                    ><i class="fa fa-download"></i> Unduh</a>
                    <a
                        href="#"
                        data-remote="false"
                        data-toggle="modal"
                        data-target="#modal-search-form"
                        data-title="Pencarian Spesifik"
                        class="btn btn-social btn-primary btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"
                        title="Pencarian Spesifik"
                    ><i class='fa fa-search'></i> Pencarian Spesifik</a>
                </div>
            </div>
            <div class="col-sm-4 col-md-3">
                <div class="row">
                    <div class="input-group">
                        <span class="input-group-addon input-sm">Tanggal Pemilihan</span>
                        <div class="input-group input-group-sm date">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <input class="form-control input-sm datepicker pull-right" name="tgl_pemilihan" type="text" value="{{ $tanggal_pemilihan }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="box-header">
            <h4 class="text-center"><strong>DAFTAR CALON PEMILIH UNTUK TANGGAL PEMILIHAN <span id="info-tgl-pemilihan">{{ $tanggal_pemilihan }}</span></strong></h4>
        </div>
        <div class="box-header with-border form-inline">
            <div class="row col-md-12 search-dropdown">
                <select class="form-control input-sm" name="sex">
                    <option value="">Jenis Kelamin</option>
                    @foreach ($jenis_kelamin as $data)
                        <option value="{{ $data->id }}">{{ set_ucwords($data->nama) }}</option>
                    @endforeach
                </select>
                <select class="form-control input-sm" id="dusun" name="dusun">
                    <option value="">Pilih Dusun</option>
                    @foreach ($dusun as $item)
                        <option value="{{ $item->dusun }}">{{ $item->dusun }}</option>
                    @endforeach
                </select>
                <select class="form-control input-sm hide" id="rw" name="rw">
                    <option value="">Pilih RW</option>
                </select>
                <select class="form-control input-sm  hide" id="rt" name="rt">
                    <option value="">Pilih RT</option>
                </select>
            </div>
        </div>
        <div class="box-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="tabeldata">
                    <thead>
                        <tr>
                            <th class="padat">NO</th>
                            <th nowrap>NIK</th>
                            <th nowrap>TAG ID CARD</th>
                            <th nowrap>NAMA</th>
                            <th nowrap>NO KK</th>
                            <th nowrap>ALAMAT</th>
                            <th nowrap>{{ strtoupper(setting('sebutan_dusun')) }}</th>
                            <th nowrap>RW</th>
                            <th nowrap>RT</th>
                            <th nowrap>PENDIDIKAN DALAM KK</th>
                            <th nowrap class="info-umur">UMUR PADA {{ $tanggal_pemilihan }}</th>
                            <th nowrap>PEKERJAAN</th>
                            <th nowrap>KAWIN</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@endsection
@include('admin.layouts.components.filter_wilayah')
@include('admin.dpt.modal_search_form')
@push('scripts')
    <script>
        $(document).ready(function() {
            var TableData = $('#tabeldata').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ ci_route('dpt.datatables') }}",
                    data: function(req) {
                        req.tgl_pemilihan = $('input[name=tgl_pemilihan]').val()
                        req.sex = $('select[name=sex]').val()
                        req.dusun = $('select[name=dusun]').val()
                        req.rw = $('select[name=rw]').val()
                        req.rt = $('select[name=rt]').val()
                        req.advanced = {
                            umur: {
                                min: $('input[name=umur_min]').val(),
                                max: $('input[name=umur_max]').val(),
                                satuan: $('select[name=umur]').val()
                            },
                            search: $('.search-advance').find('select').not('select[name=umur]').serialize()
                        }
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        class: 'padat',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'nik',
                        name: 'nik',
                        class: 'padat',
                        searchable: true,
                        orderable: true,
                        render: function(data, type, row) {
                            return `<a href="{{ ci_route('penduduk.detail.1.0') }}/${row.id}" id="test" name="${row.id}">${row.nik}</a>`
                        },
                    },
                    {
                        data: 'tag_id_card',
                        name: 'tag_id_card',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'nama',
                        name: 'nama',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'keluarga.no_kk',
                        name: 'keluarga.no_kk',
                        searchable: false,
                        orderable: true,
                        defaultContent: '',
                        render: function(data, type, row) {
                            return row.id_kk ? `<a href="{{ ci_route('keluarga.kartu_keluarga.1.0') }}/${row.id_kk}" >${row.keluarga.no_kk}</a>` : ``
                        },
                    },
                    {
                        data: 'alamat_sekarang',
                        name: 'alamat_sekarang',
                        searchable: false,
                        orderable: false,
                    },
                    {
                        data: 'dusun',
                        name: 'dusun',
                        searchable: false,
                        orderable: false,
                    },
                    {
                        data: 'rw',
                        name: 'rw',
                        searchable: false,
                        orderable: false,
                    },
                    {
                        data: 'rt',
                        name: 'rt',
                        searchable: false,
                        orderable: false,
                    },
                    {
                        data: 'pendidikan_k_k.nama',
                        name: 'pendidikan_k_k.nama',
                        searchable: false,
                        orderable: false,
                        defaultContent: ''
                    },
                    {
                        data: 'tanggallahir',
                        name: 'tanggallahir',
                        class: 'padat',
                        searchable: false,
                        orderable: true,
                        render: function(data, type, row) {
                            return row.umur_pemilihan
                        },
                    },
                    {
                        data: 'pekerjaan.nama',
                        name: 'pekerjaan.nama',
                        searchable: false,
                        orderable: false,
                    },
                    {
                        data: 'status_kawin.nama',
                        name: 'status_kawin.nama',
                        searchable: false,
                        orderable: false,
                        defaultContent: ''
                    },
                ],
                order: [
                    [1, 'asc']
                ],
                pageLength: 50,
            });

            $('input[name=tgl_pemilihan]').change(function() {
                TableData.draw()
                $('span#info-tgl-pemilihan').text($(this).val())
                $('th.info-umur').text('UMUR PADA ' + $(this).val())
            })

            document.querySelector('.search-dropdown').querySelectorAll('select').forEach($item => $item.addEventListener('change', function() {
                TableData.draw()
            }))

            $('#btnSearchAdvance').click(function() {
                $(this).closest('.modal').modal('hide')
                TableData.draw()
            })
        });
    </script>
@endpush
