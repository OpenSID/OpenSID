@extends('theme::layouts.full-content')
@include('theme::commons.asset_highcharts')
@section('content')
    <div class="box-header">
        <div class="container">
            <h1 class="text-h3">{{ $title }}</h1>
            <form class="form form-inline" action="" method="get">
                <div class="form-group">
                    <select name="kuartal" id="kuartal" required class="form-control input-sm" title="Pilih salah satu">
                        @foreach (kuartal2() as $item)
                            <option value="{{ $item['ke'] }}" @selected($item['ke'] == $kuartal)>
                                Kuartal ke {{ $item['ke'] }}
                                ({{ $item['bulan'] }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <select name="tahun" id="tahun" class="form-control input-sm" title="Pilih salah satu">
                        @foreach ($dataTahun as $item)
                            <option value="{{ $item->tahun }}" @selected($tahun == $item->tahun)>{{ $item->tahun }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <select name="id_posyandu" id="id_posyandu" class="form-control input-sm" title="Pilih salah satu">
                        <option value="">Semua</option>
                        @foreach ($posyandu as $item)
                            <option value="{{ $item->id }}" @selected($item->id == $idPosyandu)>
                                {{ $item->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-social btn-info btn-sm" id="cari">
                        <i class="fa fa-search"></i> Cari
                    </button>
                </div>

            </form>
        </div>
        <div class="box-body text-sm py-2 space-y-4" id="stunting-list">

        </div>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            const tahun = document.getElementById('tahun').value
            const kuartal = document.getElementById('kuartal').value
            const idPosyandu = document.getElementById('id_posyandu').value
            const widgetTemplate = `@include('theme::partials.kesehatan.widget_item')`
            const templateStunting = document.createElement('template')
            templateStunting.innerHTML = `@include('theme::partials.kesehatan.chart_stunting_umur')`
            const stuntingUmurNode = templateStunting.content.firstElementChild
            const templatePosyandu = document.createElement('template')
            templatePosyandu.innerHTML = `@include('theme::partials.kesehatan.chart_stunting_posyandu')`
            const posyanduNode = templatePosyandu.content.firstElementChild
            const scorecardNode = document.createElement('div')
            const listIcon = ['fa-female', 'fa-child', 'fa-female', 'fa-child', 'fa-child', 'fa-child', 'fa-child']
            const loadStunting = function(tahun, kuartal, idPosyandu) {
                const stuntingList = document.getElementById('stunting-list');
                $.ajax({
                    url: `{{ ci_route('internal_api.stunting') }}`,
                    data: {
                        'tahun': tahun,
                        'kuartal': kuartal,
                        'idPosyandu': idPosyandu
                    },
                    type: "GET",
                    beforeSend: function() {
                        stuntingList.innerHTML = `@include('theme::commons.loading')`
                    },
                    dataType: 'json',
                    data: {

                    },
                    success: function(data) {
                        stuntingList.innerHTML = ''
                        const widgets = data.data[0]['attributes']['widgets']
                        const chartStuntingUmurData = data.data[0]['attributes']['chartStuntingUmurData']
                        const chartStuntingPosyanduData = data.data[0]['attributes']['chartStuntingPosyanduData']
                        const scorecard = data.data[0]['attributes']['scorecard']
                        const widgetList = document.createElement('div')
                        widgetList.className = `container row`
                        stuntingList.appendChild(widgetList)
                        stuntingList.appendChild(stuntingUmurNode)
                        stuntingList.appendChild(posyanduNode)
                        stuntingList.appendChild(scorecardNode)
                        widgets.forEach(element => {
                            widgetList.innerHTML +=
                                widgetTemplate.replace('@@bg-color', (element['bg-color'] == 'bg-gray' ? 'bg-danger' : element['bg-color']))
                                .replace('@@icon', listIcon[element.icon] ?? 'fa-female')
                                .replace('@@title', element.title)
                                .replace('@@total', element.total)

                        });

                        generateChart(chartStuntingUmurData)
                        generatePosyandu(chartStuntingPosyanduData)
                        generateScorecard(scorecard)
                    }
                });
            }

            const generateChart = function(chartStuntingUmurData) {
                chartStuntingUmurData.forEach(function(item) {
                    Highcharts.chart(item['id'], {
                        chart: {
                            type: 'pie'
                        },
                        title: {
                            text: item['title']
                        },
                        tooltip: {
                            valueSuffix: '%'
                        },
                        plotOptions: {
                            series: {
                                allowPointSelect: true,
                                cursor: 'pointer',
                                colors: ['blue', 'red'],
                                showInLegend: true,
                            },
                            pie: {
                                dataLabels: {
                                    enabled: true,
                                    distance: -50,
                                    format: '{point.y:,.1f} %'
                                }
                            }
                        },
                        series: [{
                            type: 'pie',
                            name: 'percentage',
                            colorByPoint: true,
                            data: item['data']
                        }]

                    })
                })
            }

            const generatePosyandu = function(chartStuntingPosyanduData) {
                Highcharts.chart('chart_posyandu', {
                    chart: {
                        type: 'column'
                    },
                    title: {
                        text: 'Grafik Kasus Stunting per-Posyandu'
                    },
                    xAxis: {
                        categories: chartStuntingPosyanduData['categories']
                    },
                    yAxis: {
                        min: 0,
                        title: {
                            text: 'Angka Kasus Stunting'
                        }
                    },
                    colors: ['#028EFA', '#5EE497', '#FDB13B'],
                    series: chartStuntingPosyanduData['data']

                })
            }
            const generateScorecard = function(scorecard) {
                const _url = `{{ ci_route('data-kesehatan.scorecard') }}`
                $.post(_url, {
                    scorecard: scorecard
                }, (html) => scorecardNode.innerHTML = html)
            }
            loadStunting(tahun, kuartal, idPosyandu)
        });
    </script>
@endpush
