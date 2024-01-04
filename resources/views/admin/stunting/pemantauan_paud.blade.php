@include('admin.layouts.components.asset_datatables')

@extends('admin.layouts.index')

@section('title')
    <h1>
        Stunting
        <small>Sasaran Anak</small>
    </h1>
@endsection

@section('breadcrumb')
    <li class="active">Sasaran Anak</li>
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
                            <a href="{{ ci_route('stunting/formPaud') }}" class="btn btn-social btn-success btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-plus"></i> Tambah</a>
                        @endif
                        @if (can('h'))
                            <a href="#confirm-delete" title="Hapus Data" onclick="deleteAllBox('mainform', '{{ ci_route('stunting.deleteAllPaud') }}')" class="btn btn-social btn-danger btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block hapus-terpilih"><i
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
                                    <th rowspan="3" class="text-center" style="vertical-align: middle;">Nama Anak</th>
                                    <th rowspan="3" class="text-center" style="vertical-align: middle;">Tanggal Periksa</th>
                                    <th rowspan="3" class="text-center" style="vertical-align: middle;">Jenis Kelamin</th>
                                </tr>
                                <tr>
                                    <th colspan="2" class="text-center" style="vertical-align: middle;">Usia Menurut Kategori</th>
                                    <th colspan="12" class="text-center" style="vertical-align: middle;">Mengikuti Layanan PAUD (Parenting Bagi Orang Tua Anak Usia 2 - <
                                            3
                                            Tahun)
                                            Atau
                                            Kelas
                                            PAUD
                                            Bagi
                                            Anak
                                            3
                                            -
                                            6
                                            Tahun</th
                                        >
                                </tr>
                                <tr>
                                    <th class="text-center" style="vertical-align: middle;">Anak Usia 2 - < 3 Tahun</th>
                                    <th class="text-center" style="vertical-align: middle;">Anak Usia 3 - 6 Tahun</th>
                                    <th class="text-center" style="vertical-align: middle;">Januari</th>
                                    <th class="text-center" style="vertical-align: middle;">Februari</th>
                                    <th class="text-center" style="vertical-align: middle;">Maret</th>
                                    <th class="text-center" style="vertical-align: middle;">April</th>
                                    <th class="text-center" style="vertical-align: middle;">Mei</th>
                                    <th class="text-center" style="vertical-align: middle;">Juni</th>
                                    <th class="text-center" style="vertical-align: middle;">Juli</th>
                                    <th class="text-center" style="vertical-align: middle;">Agustus</th>
                                    <th class="text-center" style="vertical-align: middle;">September</th>
                                    <th class="text-center" style="vertical-align: middle;">Oktober</th>
                                    <th class="text-center" style="vertical-align: middle;">November</th>
                                    <th class="text-center" style="vertical-align: middle;">Desember</th>
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
                    url: "{{ ci_route('stunting.datatablesPaud') }}",
                    data: function(req) {
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
                        data: 'kia.anak.nama',
                        name: 'kia.anak.nama',
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
                            return data.kia.anak.sex == 1 ? 'LAKI-LAKI' : 'PEREMPUAN'
                        },
                        name: 'kia.anak.sex',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: function(data) {
                            return data.kategori_usia == 1 ? 'v' : '-'
                        },
                        name: 'kategori_usia',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: function(data) {
                            return data.kategori_usia == 2 ? 'v' : '-'
                        },
                        name: 'kategori_usia',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: function(data) {
                            return data.januari = (data.januari == 1) ? "-" : ((data.januari == 2) ? "v" : "x")
                        },
                        name: 'januari',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: function(data) {
                            return data.februari = (data.februari == 1) ? "-" : ((data.februari == 2) ? "v" : "x")
                        },
                        name: 'februari',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: function(data) {
                            return data.maret = (data.maret == 1) ? "-" : ((data.maret == 2) ? "v" : "x")
                        },
                        name: 'maret',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: function(data) {
                            return data.april = (data.april == 1) ? "-" : ((data.april == 2) ? "v" : "x")
                        },
                        name: 'april',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: function(data) {
                            return data.mei = (data.mei == 1) ? "-" : ((data.mei == 2) ? "v" : "x")
                        },
                        name: 'mei',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: function(data) {
                            return data.juni = (data.juni == 1) ? "-" : ((data.juni == 2) ? "v" : "x")
                        },
                        name: 'juni',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: function(data) {
                            return data.juli = (data.juli == 1) ? "-" : ((data.juli == 2) ? "v" : "x")
                        },
                        name: 'juli',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: function(data) {
                            return data.agustus = (data.agustus == 1) ? "-" : ((data.agustus == 2) ? "v" : "x")
                        },
                        name: 'agustus',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: function(data) {
                            return data.september = (data.september == 1) ? "-" : ((data.september == 2) ? "v" : "x")
                        },
                        name: 'september',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: function(data) {
                            return data.oktober = (data.oktober == 1) ? "-" : ((data.oktober == 2) ? "v" : "x")
                        },
                        name: 'oktober',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: function(data) {
                            return data.november = (data.november == 1) ? "-" : ((data.november == 2) ? "v" : "x")
                        },
                        name: 'november',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: function(data) {
                            return data.desember = (data.desember == 1) ? "-" : ((data.desember == 2) ? "v" : "x")
                        },
                        name: 'desember',
                        searchable: true,
                        orderable: true
                    },
                ],
                order: [
                    [3, 'asc']
                ]
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
                    url: "{{ ci_route('stunting.eksporPaud') }}",
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
