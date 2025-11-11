@extends('admin.layouts.index')

@include('admin.layouts.components.asset_datatables')
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
            <x-tambah-button :url="'rtm/form'" modal="true" />
            <x-hapus-button confirmDelete="true" selectData="true" :url="'rtm/delete'" />
            <x-impor-button modal="true" :url="'suplemen/impor'" />
            @php
                $listCetakUnduh = [
                    [
                        'url' => "rtm/ajax_cetak/cetak",
                        'judul' => 'Cetak',
                        'icon' => 'fa fa-print',
                        'modal' => true,
                        'id'    => 'cetak_id',
                    ],
                    [
                        'url' => "rtm/ajax_cetak/unduh",
                        'judul' => 'Unduh',
                        'icon' => 'fa fa-download',
                        'modal' => true,
                        'id'    => 'unduh_id',
                    ]
                ];
            @endphp

            <x-split-button
                judul="Laporan"
                :list="$listCetakUnduh"
                :icon="'fa fa-arrow-circle-down'"
                :type="'bg-orange'"
            />
        </div>
        <div class="box-body">
            <div class="row mepet">
                <div class="col-sm-2">
                    <select id="status" class="form-control input-sm select2">
                        <option value="">Pilih Status</option>
                        @foreach ($status as $key => $item)
                            <option @selected($key == App\Enums\StatusEnum::YA) value="{{ $key }}">{{ $item }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-2">
                    <select id="jenis_kelamin" class="form-control input-sm select2">
                        <option value="">Pilih Jenis Kelamin</option>
                        @foreach ($jenis_kelamin as $key => $item)
                            <option value="{{ $key }}">{{ $item }}</option>
                        @endforeach
                    </select>
                </div>
                @include('admin.layouts.components.wilayah')
            </div>
            <hr class="batas">
            {!! form_open(null, 'id="mainform" name="mainform"') !!}
            @if ($judul_statistik)
                <h5 id="judul-statistik" class="box-title text-center"><b>{{ $judul_statistik }}</b></h5>
            @endif
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="tabeldata">
                    <thead>
                        <tr>
                            <th nowrap><input type="checkbox" id="checkall"></th>
                            <th nowrap>NO</th>
                            <th nowrap>AKSI</th>
                            <th nowrap>FOTO</th>
                            <th nowrap>NOMOR RUMAH TANGGA</th>
                            <th nowrap>KEPALA RUMAH TANGGA</th>
                            <th nowrap>NIK</th>
                            <th nowrap>DTKS</th>
                            <th nowrap>JUMLAH KK</th>
                            <th nowrap>JUMLAH ANGGOTA</th>
                            <th nowrap>ALAMAT</th>
                            <th nowrap>{{ strtoupper(setting('sebutan_dusun')) }}</th>
                            <th nowrap>RW</th>
                            <th nowrap>RT</th>
                            <th nowrap>TANGGAL TERDAFTAR</th>
                        </tr>
                    </thead>
                </table>
            </div>
            </form>
        </div>
    </div>

    @include('admin.layouts.components.konfirmasi_hapus')
    @include('admin.penduduk.rtm.impor')
    @include('admin.penduduk.rtm.dtks_modal')
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
        function show_confirm(el) {
            $('#versi')
                .replaceWith("{{ \App\Enums\Dtks\DtksEnum::VERSION_LIST[\App\Enums\Dtks\DtksEnum::VERSION_CODE] }}")
            $('#rtm_clear').attr('href', "{{ ci_route('rtm') }}");
            $('#tujuan').attr('href', $(el).attr('href'))
        }
        $(document).ready(function() {
            let filterColumn = {!! json_encode($filterColumn) !!}
            var TableData = $('#tabeldata').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ ci_route('rtm.datatables') }}",
                    data: function(req) {
                        req.status = $('#status').val();
                        req.jenis_kelamin = $('#jenis_kelamin').val();
                        req.dusun = $('#dusun').val();
                        req.rw = $('#rw').val();
                        req.rt = $('#rt').val();
                        if (filterColumn['tipe'] == 'bdt') {
                            req.bdt = filterColumn['status'];
                        }
                        if (filterColumn['tipe'] == 'dtsen') {
                            req.dtsen = filterColumn['status'];
                        }
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
                        data: 'no_kk',
                        name: 'no_kk',
                        searchable: true,
                        orderable: true,
                    },
                    {
                        data: 'kepala_keluarga.nama',
                        name: 'kepalaKeluarga.nama',
                        defaultContent: '-',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'kepala_keluarga.nik',
                        name: 'kepalaKeluarga.nik',
                        searchable: true,
                        orderable: false
                    },
                    {
                        data: 'terdaftar_dtks',
                        name: 'terdaftar_dtks',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'jumlah_kk',
                        name: 'jumlah_kk',
                        searchable: false,
                        orderable: false,
                        className: 'text-center'
                    },
                    {
                        data: 'anggota_count',
                        name: 'anggota_count',
                        searchable: false,
                        orderable: false,
                        className: 'text-center'
                    },
                    {
                        data: 'kepala_keluarga.alamat_wilayah',
                        name: 'alamat_wilayah',
                        searchable: false,
                        orderable: false,
                        defaultContent: '-',
                    },
                    {
                        data: 'kepala_keluarga.keluarga.wilayah.dusun',
                        name: 'dusun',
                        searchable: false,
                        orderable: false,
                        defaultContent: '-',
                    },
                    {
                        data: 'kepala_keluarga.keluarga.wilayah.rw',
                        name: 'rw',
                        searchable: false,
                        orderable: false,
                        defaultContent: '-',
                    },
                    {
                        data: 'kepala_keluarga.keluarga.wilayah.rt',
                        name: 'rt',
                        searchable: false,
                        orderable: false,
                        defaultContent: '-',
                    },
                    {
                        data: 'tgl_daftar',
                        name: 'tgl_daftar',
                        searchable: false,
                        orderable: true
                    },
                ],
                order: [
                    [4, 'asc']
                ]
            });

            if (hapus == 0) {
                TableData.column(0).visible(false);
            }

            let filterSelector = '#status, #jenis_kelamin, #dusun, #rw, #rt';

            // Saat user memilih dari Select2 hide judul statistik
            $(document).on('select2:select select2:clear', filterSelector, function (e) {
                $('#judul-statistik').hide();
            });

            $(filterSelector).change(function() {
                TableData.draw()
            })

            if (filterColumn) {
                if (filterColumn['status'] > 0) {
                    $('#status').val(filterColumn['status'])
                    $('#status').trigger('change')
                }

                if (filterColumn['dusun']) {
                    $('#dusun').val(filterColumn['dusun'])
                    $('#dusun').trigger('change')

                    if (filterColumn['rw']) {
                        $('#rw').val(filterColumn['dusun'] + '__' + filterColumn['rw'])
                        $('#rw').trigger('change')
                    }

                    if (filterColumn['rt']) {
                        $('#rt').find('optgroup[value="' + filterColumn['dusun'] + '__' + filterColumn['rw'] +
                            '"] option').filter(function() {
                            return $(this).text() == filterColumn['rt']
                        }).prop('selected', 1)
                        $('#rt').trigger('change')
                    }
                }

                if (filterColumn['sex']) {
                    $('#jenis_kelamin').val(filterColumn['sex'])
                    $('#jenis_kelamin').trigger('change')
                }
            }
        });
    </script>
@endpush
