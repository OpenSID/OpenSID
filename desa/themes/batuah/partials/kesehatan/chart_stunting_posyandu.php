<div class="hiddenrelative" style="width:100%;margin:20px 0 0;">     
    <div id="chart_posyandu" style="width:100%;"></div>    
</div>
<script>
    $(document).ready(function(){        
        const posy=    Highcharts.chart('chart_posyandu', {
            chart: {
                type: 'column'
            },
            title: {
                text: 'Grafik Kasus Stunting per-Posyandu'
            },
            xAxis: {
                categories: <?= json_encode($chartStuntingPosyanduData['categories']) ?>
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Angka Kasus Stunting'
                }
            },            
            colors: ['#028EFA', '#5EE497', '#FDB13B'],
            series: <?= json_encode($chartStuntingPosyanduData['data']) ?>
            
        })                
    })
</script>