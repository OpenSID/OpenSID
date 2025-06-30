@include('admin.layouts.components.asset_datatables')
@extends('admin.layouts.index')

@section('title')
    <h1>
        Data Sensus {{ $analisis_master['nama'] }}
    </h1>
@endsection

@section('breadcrumb')
    <li>Master Analisis</li>
    <li class="active">{{ $analisis_master['nama'] }}</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')
    <div class="row">
        <div class="col-md-4 col-lg-3">
            @include('analisis::master.menu')
        </div>
        <div class="col-md-8 col-lg-9">

            <div class="box box-info">
                <div class="box-header with-border">
                    <a
                        href="{{ ci_route('analisis_respon.' . $analisis_master['id'] . '.data_ajax') }}"
                        class="btn btn-social bg-purple btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"
                        title="Unduh data respon"
                        data-remote="false"
                        data-toggle="modal"
                        data-target="#modalBox"
                        data-title="Unduh Data Respon"
                    >
                        <i class="fa fa-download"></i>Unduh
                    </a>
                    @if (can('u'))
                        <a
                            href="{{ ci_route('analisis_respon.' . $analisis_master['id'] . '.import') }}"
                            class="btn btn-social bg-navy btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"
                            title="Impor Data Respon"
                            data-remote="false"
                            data-toggle="modal"
                            data-target="#modalBox"
                            data-title="Impor Data"
                        >
                            <i class="fa fa-upload"></i>Impor
                        </a>
                        @if ($analisis_master['format_impor'] == 1)
                            <a
                                href="{{ ci_route('analisis_respon.' . $analisis_master['id'] . '.form_impor_bdt') }}"
                                class="btn btn-social bg-olive btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"
                                title="Impor Data BDT 2015"
                                data-remote="false"
                                data-toggle="modal"
                                data-target="#modalBox"
                                data-title="Impor Data BDT 2015"
                            >
                                <i class="fa fa-upload"></i>Impor BDT 2015
                            </a>
                        @endif
                    @endif
                    <a href="{{ ci_route('analisis_master.menu', $analisis_master['id']) }}" class="btn btn-social btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-arrow-circle-left "></i>{{ $analisis_master['nama'] }}</a>
                </div>
                <div class="box-header with-border">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <tr>
                                <td width="150">Nama Analisis</td>
                                <td width="1">:</td>
                                <td><a href="{{ ci_route('analisis_master.menu', $analisis_master['id']) }}">{{ $analisis_master['nama'] }}</a>
                                </td>
                            </tr>
                            <tr>
                                <td>Subjek Analisis</td>
                                <td>:</td>
                                <td>{{ App\Enums\AnalisisRefSubjekEnum::valueOf($analisis_master['subjek_tipe']) }}</td>
                            </tr>
                            <tr>
                                <td>Periode</td>
                                <td>:</td>
                                <td>{{ $namaPeriode }}</td>
                            </tr>
                            @if ($analisis_master['gform_id'])
                                <tr>
                                    <td>Sinkronisasi Terakhir</td>
                                    <td>:</td>
                                    <td>{{ tgl_indo2($analisis_master['gform_last_sync']) }}</td>
                                </tr>
                            @endif
                        </table>
                    </div>
                    <div class="box-body">
                        <div class="row mepet">
                            <div class="col-sm-3">
                                <select class="form-control input-sm select2" id="isi">
                                    <option value=""> --- Semua --- </option>
                                    <option value="1">Sudah Terinput</option>
                                    <option value="2">Belum Terinput</option>
                                </select>
                            </div>
                            @include('admin.layouts.components.wilayah')
                        </div>
                        <hr class="batas">
                        {!! form_open(null, 'id="mainform" name="mainform"') !!}
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="table-responsive">
                                                <table id="tabeldata" class="table table-bordered table-striped dataTable table-hover">
                                                    <thead class="bg-gray disabled color-palette judul-besar">
                                                        <tr>
                                                            <th>No</th>
                                                            <th>Aksi</th>
                                                            <th>{{ $nomor }}</th>
                                                            <th>{{ $nama }}</th>
                                                            @if (in_array($analisis_master['subjek_tipe'], [App\Enums\AnalisisRefSubjekEnum::PENDUDUK, App\Enums\AnalisisRefSubjekEnum::KELUARGA, App\Enums\AnalisisRefSubjekEnum::RUMAH_TANGGA, App\Enums\AnalisisRefSubjekEnum::KELOMPOK]))
                                                                <th>L/P</th>
                                                            @endif
                                                            @if (in_array($analisis_master['subjek_tipe'], [App\Enums\AnalisisRefSubjekEnum::PENDUDUK, App\Enums\AnalisisRefSubjekEnum::KELUARGA, App\Enums\AnalisisRefSubjekEnum::RUMAH_TANGGA, App\Enums\AnalisisRefSubjekEnum::KELOMPOK, App\Enums\AnalisisRefSubjekEnum::RW, App\Enums\AnalisisRefSubjekEnum::RT]))
                                                                <th>{{ ucwords(setting('sebutan_dusun')) }}</th>
                                                                <th>RW</th>
                                                                @if ($analisis_master['subjek_tipe'] != App\Enums\AnalisisRefSubjekEnum::RW)
                                                                    <th>RT</th>
                                                                @endif
                                                            @endif
                                                            <th>Status</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    @endsection
    @push('scripts')
        <script>
            $(document).ready(function() {
                var TableData = $('#tabeldata').DataTable({
                    responsive: true,
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ ci_route('analisis_respon.' . $analisis_master['id'] . '.datatables') }}",
                        data: function(req) {
                            req.dusun = $('#dusun').val()
                            req.rw = $('#rw').val()
                            req.rt = $('#rt').val()
                            req.isi = $('#isi').val()
                        }
                    },
                    columns: [{
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
                        {!! json_encode($kolom[0]) !!},
                        {!! json_encode($kolom[1]) !!},
                        @if (in_array($analisis_master['subjek_tipe'], [App\Enums\AnalisisRefSubjekEnum::PENDUDUK, App\Enums\AnalisisRefSubjekEnum::KELUARGA, App\Enums\AnalisisRefSubjekEnum::RUMAH_TANGGA, App\Enums\AnalisisRefSubjekEnum::KELOMPOK]))
                            {
                                data: 'sex',
                                name: 'sex',
                                searchable: false,
                                orderable: false
                            },
                        @endif
                        @if (in_array($analisis_master['subjek_tipe'], [App\Enums\AnalisisRefSubjekEnum::PENDUDUK, App\Enums\AnalisisRefSubjekEnum::KELUARGA, App\Enums\AnalisisRefSubjekEnum::RUMAH_TANGGA, App\Enums\AnalisisRefSubjekEnum::KELOMPOK, App\Enums\AnalisisRefSubjekEnum::RW, App\Enums\AnalisisRefSubjekEnum::RT]))
                            {
                                data: 'dusun',
                                name: 'dusun',
                                searchable: false,
                                orderable: false
                            }, {
                                data: 'rw',
                                name: 'rw',
                                searchable: false,
                                orderable: false
                            },
                            @if ($analisis_master['subjek_tipe'] != App\Enums\AnalisisRefSubjekEnum::RW)
                                {
                                    data: 'rt',
                                    name: 'rt',
                                    searchable: false,
                                    orderable: false
                                },
                            @endif
                        @endif {
                            data: 'cek',
                            name: 'cek',
                            searchable: false,
                            orderable: false,
                            class: 'padat'
                        },
                    ],
                    order: [
                        [2, 'asc']
                    ]
                });

                $('#isi, #dusun, #rw, #rt').change(function() {
                    TableData.draw()
                })

            });
        </script>
    @endpush
