@include('admin.layouts.components.datetime_picker')
@include('admin.layouts.components.asset_datatables')
@extends('admin.layouts.index')

@section('title')
    <h1>
        Rekapitulasi Kehadiran
    </h1>
@endsection

@section('breadcrumb')
    <li class="active">Rekapitulasi Kehadiran</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')

    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#tab_harian" data-toggle="tab">Rekapitulasi Harian</a></li>
            <li><a href="#tab_bulanan" data-toggle="tab">Rekapitulasi Bulanan</a></li>
        </ul>
        <div class="tab-content">
            <!-- TAB HARIAN -->
            <div class="tab-pane active" id="tab_harian">
                <div class="box box-info" style="border-top: none; box-shadow: none; margin-bottom: 0;">
                    <div class="box-header with-border" style="padding-left: 0; padding-right: 0;">
                        <div class="row" style="display: flex; align-items: flex-end; flex-wrap: wrap;">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Rentang Tanggal</label>
                                    <input type="text" name="daterange" class="form-control input-sm" id="date-range">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Perangkat {{ ucwords(setting('sebutan_desa')) }}</label>
                                    <select id="pamong" name="pamong" class="form-control input-sm required select2" style="width: 100%;">
                                        <option value="">Semua Perangkat</option>
                                        @foreach ($pamong as $data)
                                            <option value="{{ $data->pamong_id }}">{{ $data->pamong_nama ?: ($data->penduduk->nama ?? '-') }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Status Kehadiran</label>
                                    <select id="status" name="status" class="form-control input-sm select2" style="width: 100%;">
                                        <option value="">Semua Status</option>
                                        @foreach ($kehadiran as $item)
                                            <option value="{{ $item->status_kehadiran }}">{{ ucwords($item->status_kehadiran) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <button type="button" id="excel" class="btn btn-success btn-sm"><span class="far fa-file-excel"></span> Ekspor ke excel</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="box-body" style="padding-left: 0; padding-right: 0;">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" id="tabeldata" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th class="padat">NO</th>
                                        <th>NAMA</th>
                                        <th>JABATAN</th>
                                        <th>TANGGAL</th>
                                        <th>JAM MASUK</th>
                                        <th>JAM KELUAR</th>
                                        <th>TOTAL WAKTU</th>
                                        <th class="padat">STATUS</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TAB BULANAN -->
            <div class="tab-pane" id="tab_bulanan">
                <div class="box box-info" style="border-top: none; box-shadow: none; margin-bottom: 0;">
                    <div class="box-header with-border" style="padding-left: 0; padding-right: 0;">
                        <div class="row" style="display: flex; align-items: flex-end; flex-wrap: wrap;">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Bulan</label>
                                    <select id="bulan_rekap" name="bulan" class="form-control input-sm select2" style="width: 100%;">
                                        @for ($m = 1; $m <= 12; $m++)
                                            <option value="{{ str_pad($m, 2, '0', STR_PAD_LEFT) }}" {{ date('m') == $m ? 'selected' : '' }}>
                                                {{ \Carbon\Carbon::create(null, $m, 1)->locale('id')->getTranslatedMonthName() }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Tahun</label>
                                    <select id="tahun_rekap" name="tahun" class="form-control input-sm select2" style="width: 100%;">
                                        @for ($y = date('Y') - 5; $y <= date('Y') + 1; $y++)
                                            <option value="{{ $y }}" {{ date('Y') == $y ? 'selected' : '' }}>
                                                {{ $y }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="btn-group btn-group-sm">
                                        <button type="button" id="btn-filter-bulanan" class="btn btn-primary"><span class="fa fa-search"></span> Cari</button>
                                        <button type="button" id="btn-cetak-bulanan" class="btn btn-info"><span class="fa fa-print"></span> Cetak</button>
                                        <button type="button" id="btn-excel-bulanan" class="btn btn-success"><span class="far fa-file-excel"></span> Ekspor ke excel</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="box-body" style="padding-left: 0; padding-right: 0;">
                        <div id="rekap_bulanan_container">
                            <div class="text-center text-muted" style="padding: 30px;">
                                <i class="fa fa-info-circle fa-2x"></i>
                                <p style="margin-top: 10px;">Silakan klik tombol <strong>Cari</strong> untuk memuat data rekapitulasi bulanan.</p>
                            </div>
                        </div>
                    </div>
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
                    url: "{{ ci_route('kehadiran_rekapitulasi.datatables') }}",
                    method: 'POST',
                    data: function(req) {
                        req.daterange = $('#date-range').val();
                        req.status = $('#status').val();
                        req.pamong = $('#pamong').val();
                    },
                },
                columns: [{
                        data: 'DT_RowIndex',
                        class: 'padat',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: function(data) {
                            return (data.pamong.penduduk?.nama) ? data.pamong.penduduk?.nama : (data.pamong.pamong_nama ?? '-')
                        },
                        name: 'pamong.pamong_nama',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'jabatan',
                        name: 'pamong.jabatan.nama',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'tanggal',
                        name: 'tanggal',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'jam_masuk',
                        name: 'jam_masuk',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'jam_keluar',
                        name: 'jam_keluar',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'total',
                        name: 'total',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'status_kehadiran',
                        name: 'status_kehadiran',
                        searchable: true,
                        orderable: true
                    },
                ],
                order: [
                    [3, 'desc']
                ]
            });

            $('input[name="daterange"]').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
                TableData.ajax.reload();
            });

            $('input[name="daterange"]').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
                TableData.ajax.reload();
            });

            $('select[name="status"]').on('change', function() {
                TableData.ajax.reload();
            });

            $('select[name="pamong"]').on('change', function() {
                TableData.ajax.reload();
            });

            $(document).on('click', '#excel', function(e) {
                $.ajax({
                    url: "{{ ci_route('kehadiran_rekapitulasi.ekspor') }}",
                    type: "GET",
                    data: {
                        daterange: $('#date-range').val(),
                        status: $('#status').val(),
                        pamong: $('#pamong').val(),
                    },
                    success: function(data) {
                        window.open(this.url, '_blank');
                    },
                })
            });

            // Logic untuk Rekapitulasi Bulanan
            function loadRekapBulanan() {
                var bulan = $('#bulan_rekap').val();
                var tahun = $('#tahun_rekap').val();
                $('#rekap_bulanan_container').html('<div class="text-center" style="padding: 30px;"><i class="fa fa-spinner fa-spin fa-2x"></i><p style="margin-top: 10px;">Memuat data...</p></div>');
                
                $.ajax({
                    url: "{{ ci_route('kehadiran_rekapitulasi.bulanan') }}",
                    type: "GET",
                    data: {
                        bulan: bulan,
                        tahun: tahun
                    },
                    success: function(response) {
                        $('#rekap_bulanan_container').html(response);
                    },
                    error: function() {
                        $('#rekap_bulanan_container').html('<div class="alert alert-danger text-center">Gagal memuat data rekapitulasi.</div>');
                    }
                });
            }

            // Load data bulanan saat tab diklik jika belum dimuat
            var sudahDimuatBulanan = false;
            $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                if ($(e.target).attr("href") === "#tab_bulanan" && !sudahDimuatBulanan) {
                    loadRekapBulanan();
                    sudahDimuatBulanan = true;
                }
            });

            $(document).on('click', '#btn-filter-bulanan', function() {
                loadRekapBulanan();
            });

            $(document).on('click', '#btn-cetak-bulanan', function(e) {
                e.preventDefault();
                var bulan = $('#bulan_rekap').val();
                var tahun = $('#tahun_rekap').val();
                var url = "{{ ci_route('kehadiran_rekapitulasi.bulanan.dialog', ['aksi' => 'cetak']) }}?bulan=" + bulan + "&tahun=" + tahun;
                
                $('#modalBox').find('.modal-title').text('Cetak Rekapitulasi Bulanan');
                $('#modalBox').modal('show');
                $('#modalBox').find('.fetched-data').html('<div class="modal-body text-center"><i class="fa fa-spinner fa-spin fa-2x"></i><p>Memuat form...</p></div>');
                
                $.get(url, function(response) {
                    $('#modalBox').find('.fetched-data').html(response);
                    $('#modalBox').find('.select2').select2({
                        dropdownParent: $('#modalBox')
                    });
                });
            });

            $(document).on('click', '#btn-excel-bulanan', function(e) {
                e.preventDefault();
                var bulan = $('#bulan_rekap').val();
                var tahun = $('#tahun_rekap').val();
                var url = "{{ ci_route('kehadiran_rekapitulasi.bulanan.dialog', ['aksi' => 'excel']) }}?bulan=" + bulan + "&tahun=" + tahun;
                
                $('#modalBox').find('.modal-title').text('Unduh Rekapitulasi Bulanan');
                $('#modalBox').modal('show');
                $('#modalBox').find('.fetched-data').html('<div class="modal-body text-center"><i class="fa fa-spinner fa-spin fa-2x"></i><p>Memuat form...</p></div>');
                
                $.get(url, function(response) {
                    $('#modalBox').find('.fetched-data').html(response);
                    $('#modalBox').find('.select2').select2({
                        dropdownParent: $('#modalBox')
                    });
                });
            });

            $(".select2").select2();
        });
    </script>
@endpush
