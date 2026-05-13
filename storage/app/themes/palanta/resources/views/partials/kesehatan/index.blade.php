@extends('theme::layouts.right-sidebar')

@section('content')
<div class="box-def">
    <div class="box-def-inner">
        <div class="c-flex" style="margin:20px 0 20px;text-align:center;width:100%;">
            <h1>{{ $title }}</h1>
        </div>
        <form class="form form-horizontal" action="" method="get">
            <div class="row mb-10" style="margin: 0 30px;">
                <div style="display: flex; flex-wrap: wrap; justify-content: flex-end;">
                    <div class="form-group" style="flex: 1;">
                        <select name="kuartal" id="kuartal" required class="form-control input-sm" style="width: 70%; height: 33px;" title="Pilih salah satu">
                            @foreach (kuartal2() as $item)
                            <option value="{{ $item['ke'] }}" @selected($item['ke']==$kuartal)>
                                Kuartal ke {{ $item['ke'] }}
                                ({{ $item['bulan'] }})
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group" style="flex: 1;">
                        <select name="tahun" id="tahun" class="form-control input-sm" style="width: 70%; height: 33px;" title="Pilih salah satu">
                            @foreach ($dataTahun as $item)
                            <option value="{{ $item->tahun }}" @selected($item->tahun == $tahun) >{{ $item->tahun }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group" style="flex: 1;">
                        <select name="id_posyandu" id="id_posyandu" class="form-control input-sm" style="width: 70%; height: 33px;" title="Pilih salah satu">
                            <option value="">Semua</option>
                            @foreach ($posyandu as $item)
                            <option value="{{ $item->id }}" @selected($item->id == $idPosyandu)>
                                {{ $item->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                    <button type="submit" class="btn btn-info btn-sm" id="cari">
                        <i class="fa fa-search"></i> Cari
                    </button>
                </div>
                </div>                                
            </div>
        </form>
        <div class="box-body text-sm py-2 space-y-4" id="stunting-list"></div>
    </div>
</div>
@endsection
@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.0/css/ionicons.min.css">
@endpush
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
        const loadStunting = function (tahun, kuartal, idPosyandu) {
            const stuntingList = document.getElementById('stunting-list');
            $.ajax({
                url: `{{ ci_route('internal_api.stunting') }}`,
                data: {'tahun' : tahun, 'kuartal': kuartal, 'idPosyandu': idPosyandu}, 
                type: "GET",
                beforeSend: function() {
                    stuntingList.innerHTML = `@include('theme::commons.loading')`
                },
                dataType: 'json',
                data: {
                    
                },
                success: function (data) {
                    stuntingList.innerHTML = ''
                    const widgets = data.data[0]['attributes']['widgets']                    
                    const chartStuntingUmurData = data.data[0]['attributes']['chartStuntingUmurData']
                    const chartStuntingPosyanduData = data.data[0]['attributes']['chartStuntingPosyanduData']
                    const scorecard = data.data[0]['attributes']['scorecard']
                    const widgetList = document.createElement('div') 
                    widgetList.className = 'row';
                    widgetList.style.padding = '10px';
                    stuntingList.appendChild(widgetList)
                    stuntingList.appendChild(stuntingUmurNode)
                    stuntingList.appendChild(posyanduNode)
                    stuntingList.appendChild(scorecardNode)
                    widgets.forEach(element => {                        
                        widgetList.innerHTML +=                            
                            widgetTemplate.replace('@@bg-color', (element['bg-color'] == 'bg-gray' ? 'bg-danger' : element['bg-color']))
                                    .replace('@@icon', element.icon)
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
            chartStuntingUmurData.forEach(function(item){
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
                    series: [
                        {
                            type: 'pie',
                            name: 'percentage',
                            colorByPoint: true,
                            data: item['data']
                        }
                    ]
                    
                })
            })
        }

        const generatePosyandu = function(chartStuntingPosyanduData){
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
        const generateScorecard = function(scorecard){
            const _url = `{{ ci_route('data-kesehatan.scorecard')}}`
            $.post(_url, { scorecard : scorecard }, (html) => scorecardNode.innerHTML = html)
        }
        loadStunting(tahun, kuartal, idPosyandu)
    });	
</script>
@endpush

