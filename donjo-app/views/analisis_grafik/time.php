<div id="pageC"> 
<!-- Start of Space Admin -->
	<table class="inner">
	<tr style="vertical-align:top">
<td style="background:#fff;padding:0px;"> 
<script src="<?=base_url()?>assets/highchart/highcharts.js"></script>
<script src="<?=base_url()?>assets/highchart/highcharts-more.js"></script>
<script src="<?=base_url()?>assets/highchart/exporting.js"></script>
<script type="text/javascript">
			var chart;
			$(document).ready(function() {
				chart = new Highcharts.Chart({
					chart: {
						renderTo: 'chart',
						type: 'line'
					},
					title: {
						text: 'Statistik <?//=$stat?>'
					},
					xAxis: {
						title: {
							text: '<?//=$stat?>'
						},
                        categories: [<? $i=0;foreach($periode as $data){$i++;?><?echo "'$data[nama]'";?>,<?}?>]
					},
					yAxis: {
						title: {
							text: 'Jumlah Populasi'
						}
					},
			legend: {
				layout: 'vertical',
				backgroundColor: '#fefefe',
				align: 'left',
				verticalAlign: 'top',
				x: 70,
				y: 40,
				floating: true,
				shadow: true,
				enabled:true
			},
        plotOptions: {
            line: {
                dataLabels: {
                    enabled: true
                },
                enableMouseTracking: false
            }
        },
				    series: [<? foreach($main as $data){?>{
					name: '<?=$data['nama']?>',
					data: [<? foreach($data['jumlah'] as $dx){echo $dx['jml']+0?>,<?}?>]
					},<?}?>]
				});
			});
				
</script>
<style>
tr#total{
    background:#fffdc5;
    font-size:12px;
    white-space:nowrap;
    font-weight:bold;
}
</style>

<div id="contentpane">
    <div class="ui-layout-north panel top">
    </div>
    <div class="ui-layout-center" id="chart" style="padding: 5px;">                
        
    </div>
    <div class="ui-layout-south panel bottom" style="max-height: 150px;overflow:auto;">
        <table class="list">
		<thead>
            <tr>
                <th>No</th>
				<th align="left" align="center">Statistik</th>
				<? $i=0;foreach($periode as $data){$i++;?><th align="left" align="center"><?echo "$data[nama]";?></th><?}?>
			</tr>
		</thead>
		<tbody>
        <? foreach($main as $data): ?>
		<tr>
          <td align="center" width="2"><?=$data['no']?></td>
          <td><?=$data['nama']?></td>
		  <? foreach($data['jumlah'] as $dx){?><td><?=$dx['jml'];?></td><?}?>
		  </tr>
        <? endforeach; ?>
		</tbody>
        </table>
<div class="left"> 
<a href="<?=site_url()?>analisis_grafik/leave" class="uibutton icon prev">Kembali</a>
</div>
    </div>
</div>
</td></tr>
</table>
</div>
