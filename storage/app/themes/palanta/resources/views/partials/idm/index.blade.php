@extends('theme::layouts.full-content')
@include('theme::commons.asset_highcharts')

@section('content')
<h1 class="text-h2">
    Status Indeks Desa Membangun (IDM) {{ $tahun }}
</h1>
<div class="box-def">
    <div class="box-def-inner">
        <div class="heading-module l-flex">
            <div class="heading-module-inner l-flex">
                <i class="fa fa-cog"></i>
                <h1>Indek Desa Membangun</h1>
            </div>
        </div>
        <div class="box box-info">
            <div class="box-body">
                <div id="status-error" style="display: none;">
                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 lg:gap-5 max-w-full">
                        <div class="alert alert-error px-3 py-5 my-3">
                            <p id="error-message"></p>
                        </div>
                    </div>
                </div>

                <div id="status-idm" style="display: none;">
                    <div class="row">
                        <div class="col-lg-12 col-xs-12">
                            <div class="row">
                                <div class="col-lg-3 col-xs-6">
                                    <div class="idm-box bg-blue l-flex">
                                        <div>
                                            <h3 id="skor-saat-ini"></h3>
                                            <p>SKOR IDM SAAT INI</p>
                                        </div>
                                        <i class="fa fa-line-chart"></i>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-xs-6">
                                    <div class="idm-box bg-yellow l-flex">
                                        <div>
                                            <h3 id="status-saat-ini"></h3>
                                            <p>STATUS IDM</p>
                                        </div>
                                        <i class="fa fa-warning"></i>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-xs-6">
                                    <div class="idm-box bg-red l-flex">
                                        <div>
                                            <h3 id="skor-minimal"></h3>
                                            <p>SKOR IDM MINIMAL</p>
                                        </div>
                                        <i class="fa fa-minus-square"></i>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-xs-6">
                                    <div class="idm-box bg-green l-flex">
                                        <div>
                                            <h3 id="target-status"></h3>
                                            <p>TARGET STATUS</p>
                                        </div>
                                        <i class="fa fa-tachometer"></i>
                                    </div>
                                </div>

                                <div class="col-lg-12 col-xs-12" style="margin-bottom:20px;">
                                    <div class="table-responsive">
                                        <table class="overflow-auto table-striped table text-sm capitalize">
                                            <tbody>
                                                <tr>
                                                    <th class="horizontal">PROVINSI</th>
                                                    <td id="nama-provinsi"></td>
                                                </tr>
                                                <tr>
                                                    <th class="horizontal">KABUPATEN</th>
                                                    <td id="nama-kabupaten"></td>
                                                </tr>
                                                <tr>
                                                    <th class="horizontal">
                                                        {{ strtoupper(setting('sebutan_kecamatan')) }}
                                                    </th>
                                                    <td id="nama-kecamatan"></td>
                                                </tr>
                                                <tr>
                                                    <th class="horizontal">{{ strtoupper(setting('sebutan_desa')) }}
                                                    </th>
                                                    <td id="nama-desa"></td>
                                                </tr>

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
                            <table class="table table-bordered dataTable table-striped table-hover tabel-daftar"
                                id="tabel-daftar">
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
</div>
@endsection

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function () {
            var tahun = '{{ $tahun }}';
            var route = '{{ route('api.idm', $tahun) }}';

            $.get(route, function (data) {
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
            }).fail(function (xhr, status, error) {
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