@extends('admin.layouts.index')
@include('admin.layouts.components.asset_datatables')
@include('admin.layouts.components.datetime_picker')
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
            <div class="row">
                <div class="col-sm-12">
                    @if (can('h') && data_lengkap())
                        <x-btn-button
                        url=""
                        judul="Kembalikan Status Terpilih"
                        icon="fa fa-undo"
                        type="btn-success hapus-terpilih"
                        modal="true"
                        confirm="true"
                        confirmTarget="confirm-delete"
                        onclick="aksiBorongan('mainform', '{{ ci_route('penduduk_log.kembalikan_status_all') }}')"
                    />
                    @endif
                    @php
                        $listCetakUnduh = [
                            [
                                'url'   => "penduduk_log/ajax_cetak/cetak",
                                'modal' => true,
                                'judul' => "Cetak",
                                'icon'  => "fa fa-print",
                            ],
                            [
                                'url'   => "penduduk_log/ajax_cetak/unduh",
                                'modal' => true,
                                'judul' => "Unduh",
                                'icon'  => "fa fa-download",
                            ],
                        ];
                        @endphp

                        <x-split-button 
                            judul="Cetak/Unduh"
                            :list="$listCetakUnduh"
                            icon="fa fa-arrow-circle-down"
                            type="bg-purple"
                            target="true"
                        />
                </div>
            </div>
        </div>
        <div class="box-body">
            <div class="row mepet">
                <div class="col-sm-2">
                    <select class="form-control input-sm select2" id="kode_peristiwa">
                        <option value="">Pilih Jenis Peristiwa</option>
                        @foreach ($list_jenis_peristiwa as $key => $val)
                            <option value="{{ $key }}" @selected($defaultFilter['kode_peristiwa'] == $key)>{{ set_ucwords($val) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-2">
                    <select class="form-control input-sm select2" id="tahun" width="100%">
                        <option value="">Pilih Tahun</option>
                        @for ($t = date('Y'); $t >= $tahun_log_pertama; $t--)
                            <option value={{ $t }} @selected($defaultFilter['tahun'] == $t)>{{ $t }}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-sm-2">
                    <select class="form-control input-sm select2" id="bulan" width="100%">
                        <option value="">Pilih Bulan</option>
                        @foreach (bulan() as $no_bulan => $nama_bulan)
                            <option value="{{ $no_bulan }}" @selected($defaultFilter['bulan'] == $no_bulan)>{{ $nama_bulan }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-2">
                    <select class="form-control input-sm select2" id="jenis_kelamin">
                        <option value="">Pilih Jenis Kelamin</option>
                        @foreach ($list_sex as $key => $val)
                            <option value="{{ $key }}" @selected($defaultFilter['sex'] == $key)>{{ set_ucwords($val) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-2">
                    <select class="form-control input-sm select2" id="agama">
                        <option value="">Pilih Agama</option>
                        @foreach ($list_agama as $key => $val)
                            <option value="{{ $key }}">{{ set_ucwords($val) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row mepet" style="margin-top:10px;">
                @include('admin.layouts.components.wilayah')
            </div>
            <hr class="batas">
            {!! form_open(null, 'id="mainform" id="mainform"') !!}
            @if ($judul_statistik)
                <h5 id="judul-statistik" class="box-title text-center"><b>{{ $judul_statistik }}</b></h5>
            @endif
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="tabeldata" data-statistikfilter='{!! json_encode($statistikFilter) !!}'>
                    <thead>
                        <tr>
                            <th nowrap><input type="checkbox" id="checkall"></th>
                            <th nowrap>NO</th>
                            <th nowrap>AKSI</th>
                            <th nowrap>FOTO</th>
                            <th nowrap>NIK</th>
                            <th nowrap>NAMA</th>
                            <th nowrap>NO. KK / NAMA KK</th>
                            <th nowrap>JENIS KELAMIN</th>
                            <th nowrap>{{ strtoupper(setting('sebutan_dusun')) }}</th>
                            <th nowrap>RW</th>
                            <th nowrap>RT</th>
                            <th nowrap>UMUR</th>
                            <th nowrap>STATUS MENJADI</th>
                            <th nowrap>TGL PERISTIWA</th>
                            <th nowrap>TGL LAPOR</th>
                            <th nowrap>CATATAN PERISTIWA</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
            </form>
        </div>
    </div>
    @include('admin.layouts.components.konfirmasi', ['periksa_data' => true, 'pertanyaan' => $pertanyaan])
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            let filterColumn = {!! json_encode($filterColumn) !!}
            var TableData = $('#tabeldata').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ ci_route('penduduk_log.datatables') }}",
                    data: function(req) {
                        req.kode_peristiwa = $('#kode_peristiwa').val();
                        req.tahun = $('#tahun').val();
                        req.bulan = $('#bulan').val();
                        req.jenis_kelamin = $('#jenis_kelamin').val();
                        req.agama = $('#agama').val();
                        req.dusun = $('#dusun').val();
                        req.rw = $('#rw').val();
                        req.rt = $('#rt').val();
                        req.statistikfilter = $('#tabeldata').data('statistikfilter')
                    }
                },
                columns: [{
                        data: 'ceklist',
                        class: 'padat',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'DT_RowIndex',
                        class: 'padat',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'aksi',
                        class: 'aksi',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'foto',
                        name: 'foto',
                        searchable: false,
                        orderable: false,
                        defaultContent: ''
                    },
                    {
                        data: 'penduduk.nik',
                        name: 'penduduk.nik',
                        render: function(item, data, row) {
                            return `<a href='{{ ci_route('penduduk.detail') }}/${row.penduduk.id}'>${item}</a>`
                        },
                        searchable: true,
                        orderable: true,
                        defaultContent: ''
                    },
                    {
                        data: 'penduduk.nama',
                        name: 'penduduk.nama',
                        render: function(item, data, row) {
                            return `<a href='{{ ci_route('penduduk.detail') }}/${row.penduduk.id}'>${item.toUpperCase()}</a>`
                        },
                        searchable: true,
                        orderable: true,
                        defaultContent: ''
                    },
                    {
                        data: 'keluarga.no_kk',
                        name: 'keluarga.no_kk',
                        render: function(item, data, row) {
                            return !item ? '' :
                                `<a href='{{ ci_route('keluarga.kartu_keluarga') }}/${row.penduduk.id_kk}'>${item}</a><br>${row.kepala_keluarga.toUpperCase()}`
                        },
                        searchable: true,
                        orderable: true,
                        defaultContent: ''
                    },
                    {
                        data: 'penduduk.jenis_kelamin',
                        name: 'penduduk.jenis_kelamin',
                        searchable: false,
                        orderable: false,
                        defaultContent: '-',
                    },
                    {
                        data: 'penduduk.wilayah.dusun',
                        name: 'dusun',
                        searchable: false,
                        orderable: false,
                        defaultContent: '-',
                    },
                    {
                        data: 'penduduk.wilayah.rw',
                        name: 'rw',
                        searchable: false,
                        orderable: false,
                        defaultContent: '-',
                    },
                    {
                        data: 'penduduk.wilayah.rt',
                        name: 'rt',
                        searchable: false,
                        orderable: false,
                        defaultContent: '-',
                    },
                    {
                        data: 'umur',
                        name: 'penduduk.tanggallahir',
                        searchable: false,
                        orderable: true,
                        defaultContent: '-',
                    },
                    {
                        data: 'status_menjadi',
                        name: 'status_menjadi',
                        searchable: false,
                        orderable: false,
                        defaultContent: '-',
                    },
                    {
                        data: 'tgl_peristiwa',
                        name: 'tgl_peristiwa',
                        searchable: false,
                        orderable: true
                    },
                    {
                        data: 'tgl_lapor',
                        name: 'tgl_lapor',
                        searchable: false,
                        orderable: true
                    },
                    {
                        data: 'catatan',
                        name: 'catatan',
                        searchable: false,
                        orderable: false
                    },

                ],
                order: [
                    [13, 'desc']
                ],
            });

            if (hapus == 0) {
                TableData.column(0).visible(false);
            }
            if (ubah == 0) {
                TableData.column(2).visible(false);
            }

            $('#kode_peristiwa, #bulan, #tahun ,#agama, #jenis_kelamin, #dusun, #rw, #rt').change(function() {
                TableData.draw()
            })
        });
    </script>
@endpush
