@include('admin.layouts.components.asset_datatables')
@include('admin.layouts.components.highchartjs')

@extends('admin.layouts.index')

@section('title')
    <h1>Statistik Kependudukan</h1>
@endsection

@section('breadcrumb')
    <li class="active">Statistik Kependudukan {{ $dusun }}</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-4">
            @include('admin.statistik.side')
        </div>
        <div class="col-md-8">
            <div class="box box-info">
                <div class="box-header with-border">
                    <a
                        href="{{ ci_route('statistik.bantuan.' . $lap . '.dialog.program.cetak') }}"
                        class="btn btn-social bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"
                        title="Cetak Laporan"
                        data-remote="false"
                        data-toggle="modal"
                        data-target="#modalBox"
                        data-title="Cetak Laporan"
                    >
                        <i class="fa fa-print "></i>Cetak
                    </a>
                    <a
                        href="{{ ci_route('statistik.bantuan.' . $lap . '.dialog.program.unduh') }}"
                        class="btn btn-social bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"
                        title="Unduh Laporan"
                        data-remote="false"
                        data-toggle="modal"
                        data-target="#modalBox"
                        data-title="Unduh Laporan"
                    >
                        <i class="fa fa-print "></i>Unduh
                    </a>
                    <a class="btn btn-social bg-orange btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block grafikType" title="Grafik Data" id="grafikType" onclick="grafikType();">
                        <i class="fa fa-bar-chart"></i>Grafik Data
                    </a>
                    <a class="btn btn-social btn-primary btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block pieType" title="Pie Data" id="pieType" onclick="pieType();">
                        <i class="fa fa-pie-chart"></i>Pie Data
                    </a>
                </div>
                <div class="box-body">
                    <h4 class="box-title text-center"><b>{{ $label }}</b></h4>
                    <div id="chart" hidden="true"></div>
                </div>
                <hr class="batas">
                <div class="box-body">
                    <div class="row mepet">
                        @include('admin.layouts.components.wilayah', ['colDusun' => 'col-sm-3'])
                    </div>
                    <hr class="batas">
                    <div class="table-responsive">
                        <table class="table table-bordered dataTable table-striped table-hover tabel-daftar" id="tabeldata">
                            <thead class="bg-gray color-palette">
                                <tr>
                                    <th rowspan="2">No</th>
                                    <th rowspan="2">{{ $judul_kelompok }}</th>
                                    <th colspan="2">Jumlah</th>
                                    <th colspan="2">Laki-Laki</th>
                                    <th colspan="2">Perempuan</th>
                                </tr>
                                <tr>
                                    <th>Total</th>
                                    <th>Persen</th>
                                    <th>Total</th>
                                    <th>Persen</th>
                                    <th>Total</th>
                                    <th>Persen</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    <p class="text-muted text-justify text-red"><b>Catatan:</b>
                        <br>
                        1. Jumlah PESERTA termasuk peserta yang mungkin tidak aktif lagi.<br>
                        2. Jumlah BUKAN PESERTA dan TOTAL menghitung peserta aktif saja.
                    </p>
                </div>
            </div>

            @include('admin.statistik.bantuan.peserta')
        </div>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            var TableData = $('#tabeldata').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                paging: false,
                searching: false,
                info: false,
                ajax: {
                    url: "{{ ci_route('statistik.bantuan.' . $lap . '.datatables') }}",
                    data: function(req) {
                        req.dusun = $('#dusun').val();
                        req.rw = $('#rw').val();
                        req.rt = $('#rt').val();
                    }
                },
                columns: [{
                        defaultContent: '',
                        class: 'padat',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'nama',
                        name: 'nama',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'jumlah',
                        className: 'text-right',
                        name: 'jumlah',
                        searchable: false,
                        orderable: true
                    },
                    {
                        data: 'persen',
                        name: 'persen',
                        searchable: false,
                        className: 'text-right',
                        orderable: true
                    },
                    {
                        data: 'laki',
                        name: 'laki',
                        searchable: false,
                        className: 'text-right',
                        orderable: true
                    },
                    {
                        data: 'persen1',
                        name: 'persen1',
                        searchable: false,
                        className: 'text-right',
                        orderable: true
                    },
                    {
                        data: 'perempuan',
                        name: 'perempuan',
                        searchable: false,
                        className: 'text-right',
                        orderable: true
                    },
                    {
                        data: 'persen2',
                        name: 'persen2',
                        searchable: false,
                        className: 'text-right',
                        orderable: true
                    },
                ],
                order: [],
            });

            $('#tahun, #status, #dusun, #rw, #rt').change(function() {
                TableData.draw()
            })

        });

        var serverData = [];
        var chartType;
        $('#tabeldata').on('xhr.dt', function(e, settings, json) {
            serverData = json.data;
            if ($('#chart').find('.highcharts-container').length > 0) {
                if (chartType == 'column') {
                    grafikType();
                } else {
                    pieType();
                }
            }
        });

        var chart;

        function prepareChartData(data) {
            var categories = [];
            var seriesData = [];

            var ignoredNames = ['TOTAL', 'JUMLAH', 'BELUM MENGISI', 'PENERIMA'];

            data.forEach(function(item, index) {
                var jumlah = $(item.jumlah).text();
                if (!ignoredNames.includes(item.nama) && item.jumlah != '-') {
                    jumlah = parseInt(jumlah);
                    categories.push(index + 1);
                    seriesData.push([item.nama.toUpperCase(), jumlah]);
                }
            });

            return {
                categories: categories,
                seriesData: seriesData
            };
        }

        function grafikType() {
            var chartData = prepareChartData(serverData);

            chart = new Highcharts.Chart({
                chart: {
                    renderTo: 'chart',
                    defaultSeriesType: 'column'
                },
                title: 0,
                xAxis: {
                    title: {
                        text: '{{ $stat }}'
                    },
                    categories: chartData.categories
                },
                yAxis: {
                    title: {
                        text: 'Jumlah Populasi'
                    }
                },
                legend: {
                    layout: 'vertical',
                    enabled: false
                },
                plotOptions: {
                    series: {
                        colorByPoint: true
                    },
                    column: {
                        pointPadding: 0,
                        borderWidth: 0
                    }
                },
                series: [{
                    shadow: 1,
                    border: 1,
                    data: chartData.seriesData
                }]
            });
            chartType = 'column';

            $('#chart').removeAttr('hidden');
        }

        function pieType() {
            var chartData = prepareChartData(serverData);

            chart = new Highcharts.Chart({
                chart: {
                    renderTo: 'chart',
                    plotBackgroundColor: null,
                    plotBorderWidth: null,
                    plotShadow: false
                },
                title: 0,
                plotOptions: {
                    index: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        dataLabels: {
                            enabled: true
                        },
                        showInLegend: true
                    }
                },
                legend: {
                    layout: 'vertical',
                    backgroundColor: '#FFFFFF',
                    align: 'right',
                    verticalAlign: 'top',
                    x: -30,
                    y: 0,
                    floating: true,
                    shadow: true,
                    enabled: true
                },
                series: [{
                    type: 'pie',
                    name: 'Populasi',
                    data: chartData.seriesData
                }]
            });
            chartType = 'pie';

            $('#chart').removeAttr('hidden');
        }
    </script>
@endpush
