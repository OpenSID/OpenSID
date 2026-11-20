@include('admin.layouts.components.asset_datatables')
@extends('admin.layouts.index')

@section('title')
    <h1>
        Rekap Catatan Harian Kerja
    </h1>
@endsection

@section('breadcrumb')
    <li class="active">Rekap Catatan Harian</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')

    <div class="box box-info">
        <div class="box-header with-border">
            <div class="row" style="display: flex; align-items: flex-end; flex-wrap: wrap;">
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="pamong" class="margin-right-xs">Pilih Perangkat
                            {{ ucwords(setting('sebutan_desa')) }}</label>
                        <select name="pamong" id="pamong" class="form-control select2" style="width: 100%;">
                            <option value="">Semua Perangkat</option>
                            @foreach ($listPamong as $p)
                                <option value="{{ $p->pamong_id }}" {{ $pamong == $p->pamong_id ? 'selected' : '' }}>
                                    {{ $p->pamong_nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group">
                        <label for="bulan" class="margin-right-xs">Pilih Bulan</label>
                        <select name="bulan" id="bulan" class="form-control select2" style="width: 100%;">
                            @foreach (bulan() as $key => $value)
                                <option value="{{ (int) $key }}" {{ (int) $bulan == (int) $key ? 'selected' : '' }}>
                                    {{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group">
                        <label for="tahun" class="margin-right-xs">Pilih Tahun</label>
                        <select name="tahun" id="tahun" class="form-control select2" style="width: 100%;">
                            @for ($year = (int) $minTahun; $year <= (int) date('Y'); $year++)
                                <option value="{{ $year }}" {{ (int) $tahun === $year ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endfor
                        </select>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group">
                        <div class="btn-group btn-group-sm">
                            <button type="button" class="btn btn-primary btn-sm btn-social dropdown-toggle"
                                data-toggle="dropdown" aria-expanded="false">
                                <i class="fa fa-download"></i> Cetak/Unduh <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="javascript:void(0);" onclick="cetakPDF()"><i class="fa fa-file-pdf-o"></i>
                                        Cetak PDF</a></li>
                                <li><a href="javascript:void(0);" onclick="unduhExcel()"><i class="fa fa-file-excel-o"></i>
                                        Unduh Excel</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="box-body">
            <div class="table-responsive">
                <table id="tabeldata" class="table table-bordered table-striped table-hover">
                    <thead class="bg-gray color-palette">
                        <tr>
                            <th class="padat">No</th>
                            <th>Tanggal</th>
                            <th>Nama Perangkat</th>
                            <th>Jabatan</th>
                            <th>Uraian Kegiatan</th>
                            <th>Hasil yang Diharapkan</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            const table = $('#tabeldata').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                pageLength: 50,
                ordering: false,
                ajax: {
                    url: SITE_URL + 'kehadiran_rekap_catatan/datatables',
                    data: function(d) {
                        d.bulan = $('select[name="bulan"]').val() || '{{ $bulan }}';
                        d.tahun = $('select[name="tahun"]').val() || '{{ $tahun }}';
                        d.pamong = $('select[name="pamong"]').val() || '';
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        class: 'padat',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'tanggal',
                        name: 'tanggal'
                    },
                    {
                        data: 'nama_pamong',
                        name: 'nama_pamong',
                        orderable: false
                    },
                    {
                        data: 'jabatan',
                        name: 'jabatan',
                        orderable: false
                    },
                    {
                        data: 'uraian_kegiatan',
                        name: 'uraian_kegiatan'
                    },
                    {
                        data: 'hasil_diharapkan',
                        name: 'hasil_diharapkan'
                    },
                ]
            });

            // Auto-filter when bulan, tahun, or pamong changes
            $('#bulan, #tahun, #pamong').on('change', function() {
                table.draw();
            });

            // Reload on filter change
            $('form').on('submit', function(e) {
                e.preventDefault();
                table.draw();
                return false;
            });

            // Function untuk cetak PDF dengan filter
            window.cetakPDF = function() {
                const bulan = $('select[name="bulan"]').val() || '';
                const tahun = $('select[name="tahun"]').val() || '';
                const pamong = $('select[name="pamong"]').val() || '';

                let url = SITE_URL + 'kehadiran_rekap_catatan/cetak?bulan=' + bulan + '&tahun=' + tahun;
                if (pamong) {
                    url += '&pamong=' + pamong;
                }
                window.open(url, '_blank');
            };

            // Function untuk ekspor Excel dengan filter
            window.unduhExcel = function() {
                const bulan = $('select[name="bulan"]').val() || '';
                const tahun = $('select[name="tahun"]').val() || '';
                const pamong = $('select[name="pamong"]').val() || '';

                let url = SITE_URL + 'kehadiran_rekap_catatan/ekspor?bulan=' + bulan + '&tahun=' + tahun;
                if (pamong) {
                    url += '&pamong=' + pamong;
                }
                window.open(url, '_blank');
            };
        });
    </script>
@endpush
