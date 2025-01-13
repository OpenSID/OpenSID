@include('theme::commons.asset_highcharts')
<div class="box box-primary box-solid">
    <div class="box-header">
        <h3 class="box-title"><a href="{{ site_url('data-statistik/jenis-kelamin') }}"><i class="fa fa-chart-pie mr-2 mr-1"></i>{{ $judul_widget }}</a></h3>
    </div>
    <div class="box-body">
        <script type="text/javascript">
            $(function() {
                var chart_widget;
                $(document).ready(function() {
                    // Build the chart
                    chart_widget = new Highcharts.Chart({
                        chart: {
                            renderTo: 'container_widget',
                            plotBackgroundColor: null,
                            plotBorderWidth: null,
                            plotShadow: false
                        },
                        title: {
                            text: 'Jumlah Penduduk'
                        },
                        yAxis: {
                            title: {
                                text: 'Jumlah'
                            }
                        },
                        xAxis: {
                            categories: [
                                @foreach ($stat_widget as $data)
                                    @if ($data['jumlah'] > 0 && $data['nama'] != 'JUMLAH')
                                        ['{{ $data['jumlah'] }} <br> {{ $data['nama'] }}'],
                                    @endif
                                @endforeach
                            ]
                        },
                        legend: {
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
                            type: 'column',
                            name: 'Populasi',
                            data: [
                                @foreach ($stat_widget as $data)
                                    @if ($data['jumlah'] > 0 && $data['nama'] != 'JUMLAH')
                                        ['{{ $data['nama'] }}', {{ $data['jumlah'] }}],
                                    @endif
                                @endforeach
                            ]
                        }]
                    });
                });
            });
        </script>
        <div id="container_widget" style="width: 100%; height: 300px; margin: 0 auto"></div>
    </div>
</div>
