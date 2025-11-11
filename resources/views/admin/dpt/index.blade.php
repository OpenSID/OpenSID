@include('admin.layouts.components.asset_datatables')
@include('admin.layouts.components.datetime_picker')
@extends('admin.layouts.index')

@section('title')
    <h1>
        Data {{ $module_name }}
    </h1>
@endsection

@section('breadcrumb')
    <li class="active">Data {{ $module_name }}</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')
    <div class="box box-info">
        <div class="box-header with-border">
            <div class="col-sm-8 col-lg-9">
                <div class="row">
                    @include('admin.layouts.components.buttons.btn', [
                        'url' => 'pemilihan',
                        'judul' => 'Daftar Pemilihan',
                        'icon' => 'fa fa-list',
                        'type' => 'btn-success'
                    ])
                    @include('admin.layouts.components.tombol_cetak_unduh', [
                        'cetak' => "dpt/ajax_cetak/cetak",
                        'unduh' => "dpt/ajax_cetak/unduh"
                    ])
                    @include('admin.layouts.components.buttons.btn', [
                        'judul' => 'Daftar Pemilihan',
                        'icon' => 'fa fa-search',
                        'type' => 'btn-primary',
                        'modalTarget' => 'modal-search-form',
                        'judul' => 'Pencarian Spesifik',
                        'modal' => true,
                    ])
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
        <div class="box-header" style="border-bottom: 1px solid #f4f4f4;">
            <h4 class="text-center"><strong>DAFTAR CALON PEMILIH UNTUK TANGGAL PEMILIHAN <span id="info-tgl-pemilihan">{{ $tanggal_pemilihan }}</span></strong></h4>
        </div>
        <div class="box-body">
            <div class="row mepet">
                <div class="col-sm-2">
                    <select class="form-control input-sm select2" name="sex">
                        <option value="">Pilih Jenis Kelamin</option>
                        @foreach (\App\Enums\JenisKelaminEnum::all() as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
                @include('admin.layouts.components.wilayah')
            </div>
            <hr class="batas">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="tabeldata">
                    <thead>
                        <tr>
                            <th class="padat">NO</th>
                            <th nowrap>NIK</th>
                            <th nowrap>TAG ID CARD</th>
                            <th nowrap>NAMA</th>
                            <th nowrap>NO KK</th>
                            <th nowrap>JENIS KELAMIN</th>
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
    @include('admin.dpt.modal_search_form')
@endsection
@push('css')
    <style>
        .select2-results__option[aria-disabled=true] {
            display: none;
        }
    </style>
@endpush
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
                        req.dusun = $('#dusun').val()
                        req.rw = $('#rw').val()
                        req.rt = $('#rt').val()
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
                            return `<a href="{{ ci_route('penduduk.detail') }}/${row.id}" id="test" name="${row.id}">${row.nik}</a>`
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
                            return row.id_kk ? `<a href="{{ ci_route('keluarga.kartu_keluarga') }}/${row.id_kk}" >${row.keluarga.no_kk}</a>` : ``
                        },
                    },
                    {
                        data: 'jenis_kelamin',
                        name: 'jenis_kelamin',
                        searchable: false,
                        orderable: false,
                        defaultContent: ''
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
                        data: 'pendidikan_kk',
                        name: 'pendidikan_kk',
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
                        data: 'pekerjaan',
                        name: 'pekerjaan',
                        searchable: false,
                        orderable: false,
                    },
                    {
                        data: 'status_perkawinan',
                        name: 'status_perkawinan',
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

            $('select[name=sex], #dusun, #rw, #rt').change(function() {
                TableData.draw()
            })

            $('#btnSearchAdvance').click(function() {
                $(this).closest('.modal').modal('hide')
                TableData.draw()
            })
        });
    </script>
@endpush
