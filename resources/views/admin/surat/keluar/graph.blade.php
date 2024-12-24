@extends('admin.layouts.index')

@section('title')
    <h1>
        Grafik Surat Keluar
    </h1>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ ci_route('keluar') }}">Daftar Surat Keluar</a></li>
    <li class="breadcrumb-item active">Grafik Surat Keluar</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div id="container"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <!-- Highcharts -->
    <script src="{{ asset('js/highcharts/highcharts.js') }}"></script>
    <script src="{{ asset('js/highcharts/exporting.js') }}"></script>
    <script src="{{ asset('js/highcharts/highcharts-more.js') }}"></script>

    <script type="text/javascript">
        $(function() {
            var chart;
            $(document).ready(function() {
                // Build the chart
                chart = new Highcharts.Chart({
                    chart: {
                        renderTo: 'container',
                        plotBackgroundColor: null,
                        plotBorderWidth: null,
                        plotShadow: false
                    },
                    title: {
                        text: 'Surat Keluar'
                    },
                    tooltip: {
                        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b><br />Total: <b>{point.y}</b>',
                        percentageDecimals: 1
                    },
                    plotOptions: {
                        pie: {
                            allowPointSelect: true,
                            cursor: 'pointer',
                            dataLabels: {
                                enabled: false
                            },
                            showInLegend: true
                        }
                    },
                    series: [{
                        type: 'pie',
                        name: 'Persentase',
                        data: [
                            @foreach ($stat as $data)
                                @if ($data->log_surat_count > 0)
                                    ['{{ $data->nama }}', {{ $data->log_surat_count }}],
                                @endif
                            @endforeach
                        ]
                    }]
                });
            });
        });
    </script>
@endpush
