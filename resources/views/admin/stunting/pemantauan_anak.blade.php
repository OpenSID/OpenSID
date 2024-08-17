@include('admin.layouts.components.asset_datatables')

@extends('admin.layouts.index')

@section('title')
    <h1>
        Stunting
        <small>Bulanan Anak</small>
    </h1>
@endsection

@section('breadcrumb')
    <li class="active">Bulanan Anak</li>
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
                            <a href="{{ ci_route('stunting/formAnak') }}" class="btn btn-social btn-success btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-plus"></i> Tambah</a>
                        @endif
                        @if (can('h'))
                            <a href="#confirm-delete" title="Hapus Data" onclick="deleteAllBox('mainform', '{{ ci_route('stunting.deleteAllAnak') }}')" class="btn btn-social btn-danger btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block hapus-terpilih"><i
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
                                    <th rowspan="3" class="text-center" style="vertical-align: middle;">Tanggal Lahir Anak</th>
                                    <th rowspan="3" class="text-center" style="vertical-align: middle;">Status Gizi Anak</th>
                                    <th rowspan="3" class="text-center" style="vertical-align: middle;">Berat Badan Anak</th>
                                    <th rowspan="3" class="text-center" style="vertical-align: middle;">Tinggi Badan Anak</th>
                                </tr>
                                <tr>
                                    <th colspan="2" class="text-center" style="vertical-align: middle;">Umur dan Status Tikar</th>
                                    <th colspan="11" class="text-center" style="vertical-align: middle;">Indikator Layanan</th>
                                </tr>
                                <tr>
                                    <th class="text-center" style="vertical-align: middle;">Umur (Bulan)</th>
                                    <th class="text-center" style="vertical-align: middle;">Hasil (M/K/H)</th>
                                    <th class="text-center" style="vertical-align: middle;">Pemberian Imunisasi Dasar</th>
                                    <th class="text-center" style="vertical-align: middle;">Pengukuran Berat Badan</th>
                                    <th class="text-center" style="vertical-align: middle;">Pengukuran Tinggi Badan</th>
                                    <th class="text-center" style="vertical-align: middle;">Konseling Gizi Ayah</th>
                                    <th class="text-center" style="vertical-align: middle;">Konseling Gizi Ibu</th>
                                    <th class="text-center" style="vertical-align: middle;">Kunjungan Rumah</th>
                                    <th class="text-center" style="vertical-align: middle;">Kepemilikan Akses Air Bersih</th>
                                    <th class="text-center" style="vertical-align: middle;">Kepemilikan Jamban Sehat</th>
                                    <th class="text-center" style="vertical-align: middle;">Akta Lahir</th>
                                    <th class="text-center" style="vertical-align: middle;">Jaminan Kesehatan</th>
                                    <th class="text-center" style="vertical-align: middle;">Pengasuhan (PAUD)</th>
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
                    url: "{{ ci_route('stunting.datatablesAnak') }}",
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
                        data: 'kia.anak.tanggallahir',
                        name: 'kia.anak.tanggallahir',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: function(data) {
                            if (data.status_gizi == 1) status = "N"
                            else if (data.status_gizi == 2) status = "GK"
                            else if (data.status_gizi == 3) status = "GB"
                            else status = "S"
                            return status
                        },
                        name: 'status_gizi',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'berat_badan',
                        name: 'berat_badan',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'tinggi_badan',
                        name: 'tinggi_badan',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'umur_bulan',
                        name: 'umur_bulan',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: function(data) {
                            if (data.status_tikar == 1) status = "TD"
                            else if (data.status_tikar == 2) status = "M"
                            else if (data.status_tikar == 3) status = "K"
                            else status = "H"
                            return status
                        },
                        name: 'status_tikar',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: function(data) {
                            return data.pemberian_imunisasi_dasar == 1 ? 'v' : 'x'
                        },
                        name: 'pemberian_imunisasi_dasar',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: function(data) {
                            return data.pengukuran_berat_badan == 1 ? 'v' : 'x'
                        },
                        name: 'pengukuran_berat_badan',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: function(data) {
                            return data.pengukuran_tinggi_badan == 1 ? 'v' : 'x'
                        },
                        name: 'pengukuran_tinggi_badan',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: function(data) {
                            return data.konseling_gizi_ayah == 1 ? 'v' : 'x'
                        },
                        name: 'konseling_gizi_ayah',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: function(data) {
                            return data.konseling_gizi_ibu == 1 ? 'v' : 'x'
                        },
                        name: 'konseling_gizi_ibu',
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
                            return data.air_bersih == 1 ? 'v' : 'x'
                        },
                        name: 'air_bersih',
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
                            return data.akta_lahir == 1 ? 'v' : 'x'
                        },
                        name: 'akta_lahir',
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
                    {
                        data: function(data) {
                            return data.pengasuhan_paud == 1 ? 'v' : 'x'
                        },
                        name: 'pengasuhan_paud',
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
                    url: "{{ ci_route('stunting.eksporAnak') }}",
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
