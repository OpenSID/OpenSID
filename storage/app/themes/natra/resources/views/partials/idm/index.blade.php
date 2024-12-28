@extends('theme::layouts.full-content')
@include('theme::commons.asset_highcharts')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/AdminLTE.css') }}" />
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{ asset('bootstrap/css/ionicons.min.css') }}">
    @if (is_file(theme_asset('css/first.css')))
        <link rel="stylesheet" href="{{ theme_asset('css/first.css') }}" />
    @endif
    @if (is_file(theme_asset('css/desa-web.css')))
        <link type='text/css' href="{{ theme_asset('css/desa-web.css') }}" rel='stylesheet' />
    @endif
    @if (is_file('desa/css/natra/desa-web.css'))
        <link type='text/css' href="{{ base_url("desa/css/{$theme}/desa-web.css") }}" rel='Stylesheet' />
    @endif
    <style>
        .small-box .icon {
            top: -15px;
            font-size: 85px;
        }
    </style>
@endpush

@section('content')
    <div class="single_page_area">
        <h2 class="post_titile">Status Indeks Desa Membangun (IDM) {{ $tahun }}</h2>
        <div class="box box-info">
            <div id="status-error" style="display: none;">
                <div class="box-body">
                    <div class="alert alert-danger">
                        <p id="error-message"></p>
                    </div>
                </div>
            </div>

            <div id="status-idm" style="display: none;">
                <div class="box-body">
                    <div class="row">
                        <div class="col-lg-6 col-xs-12">
                            <div class="row">
                                <div class="col-lg-6 col-xs-12">
                                    <div class="small-box bg-blue">
                                        <div class="inner">
                                            <h3 id="skor-saat-ini"></h3>
                                            <p>SKOR IDM SAAT INI</p>
                                        </div>
                                        <div class="icon">
                                            <i class="ion ion-stats-bars"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-xs-12">
                                    <div class="small-box bg-yellow">
                                        <div class="inner">
                                            <h3 id="status-saat-ini"></h3>
                                            <p>STATUS IDM</p>
                                        </div>
                                        <div class="icon">
                                            <i class="ion-ios-pulse-strong"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-xs-12">
                                    <div class="small-box bg-red">
                                        <div class="inner">
                                            <h3 id="skor-minimal"></h3>
                                            <p>SKOR IDM MINIMAL</p>
                                        </div>
                                        <div class="icon">
                                            <i class="ion ion-ios-pie"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-xs-12">
                                    <div class="small-box bg-green">
                                        <div class="inner">
                                            <h3 id="target-status"></h3>
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
                                                    <td id="nama-provinsi"></td>
                                                </tr>
                                                <tr>
                                                    <td>KABUPATEN</td>
                                                    <td> : </td>
                                                    <td id="nama-kabupaten"></td>
                                                </tr>
                                                <tr>
                                                    <td>{{ strtoupper(setting('sebutan_kecamatan')) }}</td>
                                                    <td> : </td>
                                                    <td id="nama-kecamatan"></td>
                                                </tr>
                                                <tr>
                                                    <td>{{ strtoupper(setting('sebutan_desa')) }}</td>
                                                    <td> : </td>
                                                    <td id="nama-desa"></td>
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

                    <div class="col-md-8">
                        <figure class="highcharts-figure">
                            <div id="container"></div>
                        </figure>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <div class="table-responsive">
                            <table class="table table-bordered dataTable table-striped table-hover" id="tabel-daftar">
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
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            var tahun = '{{ $tahun }}';
            var route = '{{ route('api.idm', $tahun) }}';

            $.get(route, function(data) {
                if (data['error_msg']) {
                    $('#status-error').show();
                    $('#status-idm').hide();
                    $('#error-message').text(data['error_msg']);
                    return;
                }

                $('#status-idm').show();
                $('#status-error').hide();

                var summaries = data['data'][0]['attributes']['SUMMARIES'];
                var row = data['data'][0]['attributes']['ROW'];
                var identitas = data['data'][0]['attributes']['IDENTITAS'][0];
                var iks = parseFloat(row[35].SKOR ?? 0);
                var ike = parseFloat(row[48].SKOR ?? 0);
                var ikl = parseFloat(row[52].SKOR ?? 0);
                console.log(row);


                // Skor
                $('#skor-saat-ini').text(parseFloat(summaries.SKOR_SAAT_INI).toFixed(4));
                $('#status-saat-ini').text(summaries.STATUS);
                $('#skor-minimal').text(parseFloat(summaries.SKOR_MINIMAL).toFixed(4));
                $('#target-status').text(summaries.TARGET_STATUS);

                // Highcharts
                loadHighcharts(tahun, iks, ike, ikl);

                // Identitas
                $('#nama-provinsi').text(identitas.nama_provinsi);
                $('#nama-kabupaten').text(identitas.nama_kab_kota);
                $('#nama-kecamatan').text(identitas.nama_kecamatan);
                $('#nama-desa').text(identitas.nama_desa);

                // Tabel
                row.forEach(item => {
                    var tr = `
					<tr class="${item.NO ?? ''}">
						<td class="text-center">${item.NO ?? ''}</td>
						<td style="min-width: 150px;">${item.INDIKATOR?? ''}</td>
						<td class="padat">${item.SKOR ?? ''}</td>
						<td style="min-width: 250px;">${item.KETERANGAN ?? ''}</td>
						<td>${item.KEGIATAN ?? ''}</td>
						<td class="padat">${item.NILAI?? ''}</td>
						<td>${item.PUSAT ?? ''}</td>
						<td>${item.PROV ?? ''}</td>
						<td>${item.KAB ?? ''}</td>
						<td>${item.DESA ?? ''}</td>
						<td>${item.CSR  ?? ''}</td>
						<td>${item.LAINNYA}</td>
					</tr>
					`;

                    $('#tabel-daftar tbody').append(tr);
                });
            }).fail(function(xhr, status, error) {
                $('#status-error').show();
                $('#status-idm').hide();
                $('#error-message').text('Data IDM tahun ' + tahun + ' tidak ditemukan.');
            });

            // Highcharts
            function loadHighcharts(tahun, iks, ike, ikl) {
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
                            ['IKS', parseFloat(iks)],
                            ['IKE', parseFloat(ike)],
                            ['IKL', parseFloat(ikl)]
                        ]
                    }]
                });
            }
        });
    </script>
@endpush
