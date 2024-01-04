@extends('admin.layouts.index')

@push('css')
    <style>
        .radius {
            border-radius: 5px;
        }
    </style>
@endpush

@section('title')
    <h1>
        Status IDM Desa
    </h1>
@endsection

@section('breadcrumb')
    <li class="active">Status IDM Desa</li>
@endsection

@section('content')

    @include('admin.layouts.components.notifikasi')

    @include('admin.status_desa.navigasi')

    <div class="box box-info">
        <div class="box-header with-border">
            {!! form_open(ci_route('status_desa'), 'class="form-inline" id="mainform" name="mainform"') !!}
            <label for="tahun">IDM Tahun </label>
            <select class="form-control input-sm" name="tahun" onchange="$('#mainform').submit()">
                <option value="" disabled>Pilih Tahun</option>
                @foreach (tahun(2020) as $thn)
                    <option value="{{ $thn }}" @selected($tahun === $thn)>{{ $thn }}</option>
                @endforeach
            </select>
            @if (can('u'))
                <a class="btn btn-social btn-success btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" {!! cek_koneksi_internet() == false || is_null($idm->error_msg) ? 'disabled title="Perangkat tidak terhubung dengan jaringan"' : 'href="' . ci_route('status_desa.perbarui_idm', $tahun) . '"' !!}><i class="fa fa-refresh"></i>Perbarui</a>
                @if (empty($idm->error_msg))
                    <a class="btn btn-social btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" href="{{ ci_route('status_desa.simpan', $tahun) }}"><i class="fa fa-check-circle"></i>Simpan</a>
                @endif
            @endif
            </form>
        </div>
        <div class="box-body">
            @if ($idm->error_msg)
                <div class="alert alert-danger">
                    {!! $idm->error_msg !!}
                </div>
            @else
                <div class="row">
                    <div class="col-lg-6 col-xs-12">
                        <div class="row">
                            <div class="col-lg-6 col-xs-12">
                                <div class="small-box bg-blue radius">
                                    <div class="inner">
                                        <h3>{{ number_format($idm->SUMMARIES->SKOR_SAAT_INI, 4) }}</h3>
                                        <p>SKOR IDM SAAT INI</p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-stats-bars"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-xs-12">
                                <div class="small-box bg-yellow radius">
                                    <div class="inner">
                                        <h3>{{ $idm->SUMMARIES->STATUS }}</h3>
                                        <p>STATUS IDM</p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion-ios-pulse-strong"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-xs-12">
                                <div class="small-box bg-red radius">
                                    <div class="inner">
                                        <h3>{{ number_format($idm->SUMMARIES->SKOR_MINIMAL, 4) }}</h2>
                                            <p>SKOR IDM MINIMAL</p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-ios-pie"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-xs-12">
                                <div class="small-box bg-green radius">
                                    <div class="inner">
                                        <h3>{{ $idm->SUMMARIES->TARGET_STATUS }}</h3>
                                        <p>TARGET STATUS</p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-arrow-graph-up-right"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-12 col-xs-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped dataTable table-hover">
                                        <tbody>
                                            <tr>
                                                <td width="30%">PROVINSI</td>
                                                <td width="1">:</td>
                                                <td>{{ $idm->IDENTITAS[0]->nama_provinsi }}</td>
                                            </tr>
                                            <tr>
                                                <td>KABUPATEN</td>
                                                <td> : </td>
                                                <td>{{ $idm->IDENTITAS[0]->nama_kab_kota }}</td>
                                            </tr>
                                            <tr>
                                                <td>{{ strtoupper($setting->sebutan_kecamatan) }}</td>
                                                <td> : </td>
                                                <td>{{ $idm->IDENTITAS[0]->nama_kecamatan }}</td>
                                            </tr>
                                            <tr>
                                                <td>{{ strtoupper($setting->sebutan_desa) }}</td>
                                                <td> : </td>
                                                <td>{{ $idm->IDENTITAS[0]->nama_desa }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6 col-xs-12">
                        <figure class="highcharts-figure">
                            <div id="container"></div>
                        </figure>
                    </div>
                </div>

                <div class="row">
                    <hr>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <div class="table-responsive">
                            <table class="table table-bordered dataTable table-striped table-hover tabel-daftar">
                                <thead class="bg-gray color-palette">
                                    <tr>
                                        <th rowspan="2" class="padat">NO</th>
                                        <th rowspan="2">INDIKATOR IDM</th>
                                        <th rowspan="2">SKOR</th>
                                        <th rowspan="2">KETERANGAN</th>
                                        <th rowspan="2" nowrap>KEGIATAN YANG DAPAT DILAKUKAN</th>
                                        <th rowspan="2">+NILAI</th>
                                        <th colspan="6" class="text-center">YANG DAPAT MELAKSANAKAN KEGIATAN</th>
                                    </tr>
                                    <tr>
                                        <th>PUSAT</th>
                                        <th>PROVINSI</th>
                                        <th>KABUPATEN</th>
                                        <th>DESA</th>
                                        <th>CSR</th>
                                        <th>LAINNYA</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($idm->ROW as $data)
                                        <tr class="{{ empty($data->NO) && (print 'judul') }} ">
                                            <td class="text-center">{{ $data->NO }}</td>
                                            <td style="min-width: 150px;">{{ $data->INDIKATOR }}</td>
                                            <td class="padat">{{ $data->SKOR }}</td>
                                            <td style="min-width: 250px;">{{ $data->KETERANGAN }}</td>
                                            <td>{{ $data->KEGIATAN }}</td>
                                            <td class="padat">{{ $data->NILAI }}</td>
                                            <td>{{ $data->PUSAT }}</td>
                                            <td>{{ $data->PROV }}</td>
                                            <td>{{ $data->KAB }}</td>
                                            <td>{{ $data->DESA }}</td>
                                            <td>{{ $data->CSR }}</td>
                                            <td>{{ $data->LAINNYA }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

@endsection

@if (!$idm->error_msg)
    @push('scripts')
        @include('admin.layouts.components.asset_highcharts')
        <script>
            $(document).ready(function() {

                var tahun = {{ $tahun }};
                var iks = {{ $idm->ROW[35]->SKOR }};
                var ike = {{ $idm->ROW[48]->SKOR }};
                var ikl = {{ $idm->ROW[52]->SKOR }};

                Highcharts.chart('container', {
                    chart: {
                        type: 'pie',
                        options3d: {
                            enabled: true,
                            alpha: 45
                        }
                    },
                    title: {
                        text: 'Indeks Desa Membangun (IDM) ' + tahun
                    },
                    subtitle: {
                        text: 'SKOR : IKS, IKE, IKL'
                    },

                    plotOptions: {
                        series: {
                            colorByPoint: true
                        },
                        pie: {
                            allowPointSelect: true,
                            cursor: 'pointer',
                            showInLegend: true,
                            depth: 45,
                            innerSize: 70,
                            dataLabels: {
                                enabled: true,
                                format: '<b>{point.name}</b>: {point.y:,.2f} / {point.percentage:.1f} %'
                            }
                        }
                    },
                    series: [{
                        name: 'SKOR',
                        shadow: 1,
                        border: 1,
                        data: [
                            ['IKS', iks],
                            ['IKE', ike],
                            ['IKL', ikl]
                        ]
                    }]
                });
            });
        </script>
    @endpush
@endif
