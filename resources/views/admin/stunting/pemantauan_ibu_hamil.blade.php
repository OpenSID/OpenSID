@include('admin.layouts.components.asset_datatables')

@extends('admin.layouts.index')

@section('title')
    <h1>
        Stunting
        <small>Bulanan Ibu Hamil</small>
    </h1>
@endsection

@section('breadcrumb')
    <li class="active">Bulanan Ibu Hamil</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')

    @include('admin.stunting.widget')

    <div class="row">
        @include('admin.stunting.navigasi')

        <div class="col-md-9 col-lg-9">
            <div class="box box-info">
                <div class="box-header">
                    <div class="col-md-7 no-padding">
                        <div class="col-md-3">
                            <div class="form-group">
                                <select name="bulan" id="bulan" class="form-control input-sm">
                                    <option value="">Bulan</option>
                                    @foreach ($bulan as $key => $data)
                                        <option value="{{ $key + 1 }}">
                                            {{ $data }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <select id="tahun" name="tahun" class="form-control input-sm">
                                    <option value="">Tahun</option>
                                    @foreach ($tahun as $data)
                                        <option value="{{ $data->tahun }}">{{ $data->tahun }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <select id="posyandu" name="posyandu" class="form-control input-sm">
                                    <option value="">Posyandu</option>
                                    @foreach ($posyandu as $data)
                                        <option value="{{ $data->id }}">{{ $data->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5 no-padding">
                        @if (can('u'))
                            <a href="{{ ci_route('stunting/formIbuHamil') }}" class="btn btn-social btn-success btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-plus"></i> Tambah</a>
                        @endif
                        @if (can('h'))
                            <a href="#confirm-delete" title="Hapus Data" onclick="deleteAllBox('mainform', '{{ ci_route('stunting.deleteAllIbuHamil') }}')" class="btn btn-social btn-danger btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block hapus-terpilih"><i
                                    class='fa fa-trash-o'
                                ></i> Hapus</a>
                        @endif
                        <a id="excel" class="btn btn-social btn-success btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-file"></i> Ekspor ke excel</a>

                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    {!! form_open(null, 'id="mainform" name="mainform"') !!}
                    <div class="table-responsive">
                        <table id="tabeldata" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th rowspan="3" class="text-center" style="vertical-align: middle;"><input type="checkbox" id="checkall" /></th>
                                    <th rowspan="3" class="text-center padat" style="vertical-align: middle;">No</th>
                                    <th rowspan="3" class="text-center padat" style="vertical-align: middle;">Aksi</th>
                                    <th rowspan="3" class="text-center" style="vertical-align: middle;">NO KIA</th>
                                    <th rowspan="3" class="text-center" style="vertical-align: middle;">Nama Ibu</th>
                                    <th rowspan="3" class="text-center" style="vertical-align: middle;">Tanggal Periksa</th>
                                    <th rowspan="3" class="text-center" style="vertical-align: middle;">Status Kehamilan</th>
                                    <th rowspan="3" class="text-center" style="vertical-align: middle;">Hari Perkiraan Lahir</th>
                                </tr>
                                <tr>
                                    <th colspan="2" class="text-center" style="vertical-align: middle;">Usia Kehamilan dan
                                        Persalinan</th>
                                    <th colspan="8" class="text-center" style="vertical-align: middle;">Status Penerimaan
                                        Indikator</th>
                                </tr>
                                <tr>
                                    <th class="text-center" style="vertical-align: middle;">Usia Kehamilan (Bulan)</th>
                                    <th class="text-center" style="vertical-align: middle;">Tanggal Melahirkan</th>
                                    <th class="text-center" style="vertical-align: middle;">Pemeriksaan Kehamilan</th>
                                    <th class="text-center" style="vertical-align: middle;">Dapat & Konsumsi Pil Fe</th>
                                    <th class="text-center" style="vertical-align: middle;">Pemeriksaan Nifas</th>
                                    <th class="text-center" style="vertical-align: middle;">Konseling Gizi (Kelas IH)</th>
                                    <th class="text-center" style="vertical-align: middle;">Kunjungan Rumah</th>
                                    <th class="text-center" style="vertical-align: middle;">Kepemilikan Akses Air Bersih</th>
                                    <th class="text-center" style="vertical-align: middle;">Kepemilikan jamban</th>
                                    <th class="text-center" style="vertical-align: middle;">Jaminan Kesehatan</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @include('admin.layouts.components.konfirmasi_hapus')
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            var TableData = $('#tabeldata').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ ci_route('stunting.datatablesIbuHamil') }}",
                    data: function(req) {
                        req.bulan = $('#bulan').val();
                        req.tahun = $('#tahun').val();
                        req.posyandu = $('#posyandu').val();
                    },
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
                        data: 'kia.no_kia',
                        name: 'kia.no_kia',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'kia.ibu.nama',
                        name: 'kia.ibu.nama',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'tanggal_periksa',
                        name: 'tanggal_periksa',
                        searchable: false,
                        orderable: true
                    },
                    {
                        data: function(data) {
                            return data.status_kehamilan = (data.status_kehamilan == 1) ? "NORMAL" : ((data.status_kehamilan == 2) ? "RISTI" : ((data.status_kehamilan == 3) ? "KEK" : "-"))
                        },
                        name: 'status_kehamilan',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'kia.hari_perkiraan_lahir',
                        name: 'kia.hari_perkiraan_lahir',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: function(data) {
                            return data.usia_kehamilan ?? '-'
                        },
                        name: 'usia_kehamilan',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'tanggal_melahirkan',
                        name: 'tanggal_melahirkan',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: function(data) {
                            return data.pemeriksaan_kehamilan == 1 ? 'v' : 'x'
                        },
                        name: 'pemeriksaan_kehamilan',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: function(data) {
                            return data.konsumsi_pil_fe == 1 ? 'v' : 'x'
                        },
                        name: 'konsumsi_pil_fe',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: function(data) {
                            return data.pemeriksaan_nifas == 1 ? 'v' : 'x'
                        },
                        name: 'pemeriksaan_nifas',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: function(data) {
                            return data.konseling_gizi == 1 ? 'v' : 'x'
                        },
                        name: 'konseling_gizi',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: function(data) {
                            return data.kunjungan_rumah == 1 ? 'v' : 'x'
                        },
                        name: 'kunjungan_rumah',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: function(data) {
                            return data.akses_air_bersih == 1 ? 'v' : 'x'
                        },
                        name: 'akses_air_bersih',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: function(data) {
                            return data.kepemilikan_jamban == 1 ? 'v' : 'x'
                        },
                        name: 'kepemilikan_jamban',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: function(data) {
                            return data.jaminan_kesehatan == 1 ? 'v' : 'x'
                        },
                        name: 'jaminan_kesehatan',
                        searchable: true,
                        orderable: true
                    },
                ],
                order: [
                    [3, 'asc']
                ]
            });

            $('select[name="bulan"]').on('change', function() {
                $(this).val();
                TableData.ajax.reload();
            });

            $('select[name="tahun"]').on('change', function() {
                $(this).val();
                TableData.ajax.reload();
            });

            $('select[name="posyandu"]').on('change', function() {
                $(this).val();
                TableData.ajax.reload();
            });

            $(document).on('click', '#excel', function(e) {
                $.ajax({
                    url: "{{ ci_route('stunting.eksporIbuHamil') }}",
                    type: "GET",
                    data: {
                        bulan: $('#bulan').val(),
                        tahun: $('#tahun').val(),
                        posyandu: $('#posyandu').val(),
                    },
                    success: function(data) {
                        window.open(this.url, '_blank');
                    },
                })
            });

            if (hapus == 0) {
                TableData.column(0).visible(false);
            }

            if (ubah == 0) {
                TableData.column(2).visible(false);
            }
        });
    </script>
@endpush
