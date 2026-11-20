@extends('theme::layouts.full-content')
@include('theme::commons.asset_highcharts')
@section('content')
    <div class="box-header">
        <div class="p-4">
            <h1 class="text-h3"><?= $title ?></h1>
            <form class="form form-horizontal" action="" method="get">
                <div class="flex space-x-2">
                    <div class="">
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
                    </div>
                    <div class="">
                        <div class="form-group">
                            <select name="tahun" id="tahun" class="form-control input-sm" title="Pilih salah satu">
                                @foreach ($dataTahun as $item)
                                    <option value="{{ $item->tahun }}" @selected($item->tahun == $tahun)>{{ $item->tahun }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="">
                        <div class="form-group">
                            <select name="id_posyandu" id="id_posyandu" class="form-control input-sm" title="Pilih salah satu">
                                <option value="">Semua</option>
                                @foreach ($posyandu as $item)
                                    <option value="{{ $item->id }}" @selected($item->id == $idPosyandu)>
                                        {{ $item->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-social btn-info btn-sm" id="cari">
                            <i class="fa fa-search"></i> Cari
                        </button>
                    </div>
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
            
            // BARU: Template untuk chart kelompok umur
            const templateKelompokUmur = document.createElement('template')
            templateKelompokUmur.innerHTML = `<div id="chart_kelompok_umur" style="min-height: 400px;"></div>`
            const kelompokUmurNode = templateKelompokUmur.content.firstElementChild
            
            // BARU: Template untuk chart status per umur
            const templateStatusPerUmur = document.createElement('template')
            templateStatusPerUmur.innerHTML = `
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mt-4">
                    <div id="chart_status_umur_0_5" style="min-height: 350px;"></div>
                    <div id="chart_status_umur_6_11" style="min-height: 350px;"></div>
                    <div id="chart_status_umur_12_23" style="min-height: 350px;"></div>
                </div>
            `
            const statusPerUmurNode = templateStatusPerUmur.content.firstElementChild
            
            const scorecardNode = document.createElement('div')
            
            const loadStunting = function(tahun, kuartal, idPosyandu) {
                const stuntingList = document.getElementById('stunting-list');
                $.ajax({
                    url: `{{ ci_route('internal_api.stunting') }}`,
                    data: {
                        'tahun': tahun,
                        'kuartal': kuartal,
                        'idPosyandu': idPosyandu
                    },
                    type: 'POST',
                    beforeSend: function() {
                        stuntingList.innerHTML = `@include('theme::commons.loading')`
                    },
                    dataType: 'json',
                    success: function(data) {
                        stuntingList.innerHTML = ''
                        const widgets = data.data[0]['attributes']['widgets']
                        const chartStuntingUmurData = data.data[0]['attributes']['chartStuntingUmurData']
                        const chartStuntingPosyanduData = data.data[0]['attributes']['chartStuntingPosyanduData']
                        const scorecard = data.data[0]['attributes']['scorecard']
                        
                        // BARU: Data untuk chart kelompok umur dan status per umur
                        const chartKelompokUmurData = data.data[0]['attributes']['chartKelompokUmurData']
                        const chartStatusPerUmurData = data.data[0]['attributes']['chartStatusPerUmurData']
                        
                        const widgetList = document.createElement('div')
                        widgetList.className = `grid grid-cols-1 lg:grid-cols-3 gap-5 container px-3 lg:px-5`
                        
                        stuntingList.appendChild(widgetList)
                        stuntingList.appendChild(stuntingUmurNode)
                        
                        // BARU: Tambahkan chart kelompok umur
                        stuntingList.appendChild(kelompokUmurNode)
                        
                        // BARU: Tambahkan chart status per umur
                        stuntingList.appendChild(statusPerUmurNode)
                        
                        // Chart posyandu dipindah ke bawah
                        stuntingList.appendChild(posyanduNode)
                        
                        stuntingList.appendChild(scorecardNode)
                        
                        widgets.forEach(element => {
                            widgetList.innerHTML +=
                                widgetTemplate.replace('@@bg-color', element['bg-color'])
                                .replace('@@icon', element.icon)
                                .replace('@@title', element.title)
                                .replace('@@total', element.total)
                        });

                        // generateChart(chartStuntingUmurData)
                        generatePosyandu(chartStuntingPosyanduData)
                        
                        // BARU: Generate chart kelompok umur
                        generateChartKelompokUmur(chartKelompokUmurData)
                        
                        // BARU: Generate chart status per umur
                        generateChartStatusPerUmur(chartStatusPerUmurData)
                        
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
            
            // BARU: Function untuk generate chart kelompok umur
            const generateChartKelompokUmur = function(chartKelompokUmurData) {
                Highcharts.chart(chartKelompokUmurData['id'], {
                    chart: {
                        type: 'pie'
                    },
                    title: {
                        text: chartKelompokUmurData['title'],
                        style: {
                            fontSize: '18px',
                            fontWeight: 'bold'
                        }
                    },
                    tooltip: {
                        pointFormat: '<b>{point.jumlah}</b> anak ({point.y:.1f}%)'
                    },
                    plotOptions: {
                        pie: {
                            allowPointSelect: true,
                            cursor: 'pointer',
                            colors: ['#3498db', '#9b59b6', '#e74c3c'],
                            dataLabels: {
                                enabled: true,
                                format: '<b>{point.name}</b><br>{point.jumlah} anak<br>{point.y:.1f}%',
                                style: {
                                    fontSize: '12px'
                                }
                            },
                            showInLegend: true
                        }
                    },
                    series: [{
                        name: 'Jumlah Anak',
                        colorByPoint: true,
                        data: chartKelompokUmurData['data']
                    }]
                })
            }
            
            // BARU: Function untuk generate chart status per umur
            const generateChartStatusPerUmur = function(chartStatusPerUmurData) {
                chartStatusPerUmurData.forEach(function(item) {
                    const colors = item.total > 0 ? ['#27ae60', '#f39c12', '#e74c3c'] : ['#95a5a6'];
                    
                    Highcharts.chart(item['id'], {
                        chart: {
                            type: 'pie'
                        },
                        title: {
                            text: item['title'],
                            style: {
                                fontSize: '14px',
                                fontWeight: 'bold'
                            }
                        },
                        subtitle: {
                            text: 'Total: ' + item['total'] + ' anak',
                            style: {
                                fontSize: '12px'
                            }
                        },
                        tooltip: {
                            pointFormat: '<b>{point.jumlah}</b> anak ({point.y:.1f}%)'
                        },
                        plotOptions: {
                            pie: {
                                allowPointSelect: true,
                                cursor: 'pointer',
                                colors: colors,
                                dataLabels: {
                                    enabled: true,
                                    format: item.total > 0 ? '<b>{point.name}</b><br>{point.jumlah} anak<br>({point.y:.1f}%)' : '<b>{point.name}</b>',
                                    style: {
                                        fontSize: '11px'
                                    }
                                },
                                showInLegend: true
                            }
                        },
                        series: [{
                            name: 'Status',
                            colorByPoint: true,
                            data: item['data']
                        }]
                    })
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