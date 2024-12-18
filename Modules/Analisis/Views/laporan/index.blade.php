@include('admin.layouts.components.asset_datatables')
@include('admin.layouts.components.datetime_picker')
@extends('admin.layouts.index')

@section('title')
    <h1>
        Laporan Hasil Analisis
    </h1>
@endsection

@section('breadcrumb')
    <li>Master Analisis</li>
    <li>{{ $analisis_master['nama'] }}</li>
    <li class="active">Laporan Hasil Klasifikasi</li>
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
                        href="{{ ci_route('analisis_laporan.' . $analisis_master['id'] . '.dialog.cetak') }}"
                        class="btn btn-social bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"
                        data-remote="false"
                        data-toggle="modal"
                        data-target="#modalBox"
                        data-title="Cetak Laporan Hasil Analisis {{ $judul['asubjek'] }}"
                        title="Cetak"
                    ><i class="fa fa-print"></i>Cetak</a>
                    <a
                        href="{{ ci_route('analisis_laporan.' . $analisis_master['id'] . '.dialog.unduh') }}"
                        class="btn btn-social bg-navy btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"
                        data-remote="false"
                        data-toggle="modal"
                        data-target="#modalBox"
                        data-title="Cetak Laporan Hasil Analisis {{ $judul['asubjek'] }}"
                        title="Unduh"
                    ><i class="fa fa-download"></i>Unduh</a>
                    <a
                        href="{{ ci_route('analisis_laporan.' . $analisis_master['id'] . '.ajax_multi_jawab') }}"
                        class="btn btn-social bg-olive btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"
                        title="Filter Indikator"
                        data-remote="false"
                        data-toggle="modal"
                        data-target="#modalBox"
                        data-title="Filter Indikator"
                    ><i class="fa fa-search"></i>Filter Indikator</a>
                    <a href="{{ ci_route('analisis_master.menu', $analisis_master['id']) }}" class="btn btn-social btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Kembali Ke Daftar RW"><i class="fa fa-arrow-circle-left "></i>Kembali
                        Ke
                        {{ $analisis_master['nama'] }}</a>
                </div>
                <div class="box-header with-border">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover tabel-rincian">
                            <tr>
                                <td width="20%">Nama Analisis</td>
                                <td width="1%">:</td>
                                <td><a href="{{ ci_route('analisis_master.menu', $analisis_master['id']) }}">{{ $analisis_master['nama'] }}
                                    </a></td>
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
                        </table>
                    </div>
                </div>
                <div class="box-body">
                    <div class="row mepet">
                        <div class="col-sm-3">
                            <select class="form-control input-sm select2" id="klasifikasi">
                                <option value="">Semua Klasifikasi</option>
                                @foreach ($list_klasifikasi as $data)
                                    <option value="{{ $data['id'] }}" @selected($klasifikasi == $data['id'])>{{ $data['nama'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @include('admin.layouts.components.wilayah')
                    </div>
                    <hr class="batas">
                    <div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                        <form id="mainform" name="mainform" method="post">
                            <div class="table-responsive">
                                <table id="tabeldata" class="table table-bordered table-striped dataTable table-hover tabel-daftar">
                                    <thead class="bg-gray disabled color-palette">
                                        <tr>
                                            <th>No</th>
                                            <th>Aksi</th>
                                            <th>{{ $judul['nomor'] }}</th>
                                            @if (in_array($analisis_master['subjek_tipe'], [App\Enums\AnalisisRefSubjekEnum::PENDUDUK, App\Enums\AnalisisRefSubjekEnum::KELUARGA, App\Enums\AnalisisRefSubjekEnum::RUMAH_TANGGA]))
                                                <th>{{ $analisis_master['subjek_tipe'] == App\Enums\AnalisisRefSubjekEnum::PENDUDUK ? 'No. KK' : 'NIK KK' }}</th>
                                            @endif
                                            <th>{{ $judul['nama'] }}</th>
                                            @if (in_array($analisis_master['subjek_tipe'], [App\Enums\AnalisisRefSubjekEnum::PENDUDUK, App\Enums\AnalisisRefSubjekEnum::KELUARGA, App\Enums\AnalisisRefSubjekEnum::RUMAH_TANGGA, App\Enums\AnalisisRefSubjekEnum::KELOMPOK]))
                                                <th>Jenis Kelamin</th>
                                                <th>Alamat</th>
                                            @endif
                                            <th>Nilai</th>
                                            <th>Klasifikasi</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </form>
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
                    url: "{{ ci_route('analisis_laporan.' . $analisis_master['id'] . '.datatables') }}",
                    data: function(req) {
                        req.dusun = $('#dusun').val()
                        req.rw = $('#rw').val()
                        req.rt = $('#rt').val()
                        req.klasifikasi = $('#klasifikasi').val()
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
                    {!! json_encode($judul['kolom'][0]) !!},
                    @if (in_array($analisis_master['subjek_tipe'], [App\Enums\AnalisisRefSubjekEnum::PENDUDUK, App\Enums\AnalisisRefSubjekEnum::KELUARGA, App\Enums\AnalisisRefSubjekEnum::RUMAH_TANGGA]))
                        {
                            data: 'kk',
                            name: '{{ $analisis_master['subjek_tipe'] == App\Enums\AnalisisRefSubjekEnum::PENDUDUK ? 'no_kk' : 'nik' }}',
                        },
                    @endif
                    {!! json_encode($judul['kolom'][1]) !!},
                    @if (in_array($analisis_master['subjek_tipe'], [App\Enums\AnalisisRefSubjekEnum::PENDUDUK, App\Enums\AnalisisRefSubjekEnum::KELUARGA, App\Enums\AnalisisRefSubjekEnum::RUMAH_TANGGA, App\Enums\AnalisisRefSubjekEnum::KELOMPOK]))
                        {
                            data: 'sex',
                            name: 'sex',
                            searchable: false,
                            orderable: false
                        }, {
                            data: 'alamat',
                            name: 'alamat',
                            searchable: false,
                            orderable: false
                        },
                    @endif {
                        data: 'nilai',
                        name: 'nilai',
                        searchable: false
                    },
                    {
                        data: 'klasifikasi',
                        name: 'klasifikasi',
                        searchable: false,
                        orderable: false
                    },
                ],
                order: [
                    [2, 'asc']
                ]
            });

            $('#klasifikasi, #dusun, #rw, #rt').change(function() {
                TableData.draw()
            })

            $(document).on('shown.bs.modal', '#modalBox', function(event) {
                let link = $(event.relatedTarget);
                let title = link.data('title');
                let modal = $(this);
                console.log(modal.html())
                setTimeout(console.log(modal.find('form#validasi').attr('action')), 2000)
            });
        });
    </script>
@endpush
