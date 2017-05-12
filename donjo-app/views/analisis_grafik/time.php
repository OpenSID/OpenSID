<div id="pageC"> 
<!-- Start of Space Admin -->
	<table class="inner">
	<tr style="vertical-align:top">
<td style="background:#fff;padding:0px;"> 
<script src="<?php echo base_url()?>assets/js/highcharts/highcharts.js"></script>
<script src="<?php echo base_url()?>assets/js/highcharts/highcharts-more.js"></script>
<script src="<?php echo base_url()?>assets/js/highcharts/exporting.js"></script>
<script type="text/javascript">
			var chart;
			$(document).ready(function() {
				chart = new Highcharts.Chart({
					chart: {
						renderTo: 'chart',
						type: 'line'
					},
					title: {
						text: 'Statistik'
					},
					xAxis: {
						title: {
							text: ''
						},
 categories: [<?php $i=0;foreach($periode as $data){$i++;?><?php echo "'$data[nama]'";?>,<?php }?>]
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
				 series: [<?php foreach($main as $data){?>{
					name: '<?php echo $data['nama']?>',
					data: [<?php foreach($data['jumlah'] as $dx){echo $dx['jml']+0?>,<?php }?>]
					},<?php }?>]
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
				<th>Statistik</th>
				<?php $i=0;foreach($periode as $data){$i++;?><th><?php echo "$data[nama]";?></th><?php }?>
			</tr>
		</thead>
		<tbody>
 <?php foreach($main as $data): ?>
		<tr>
 <td align="center" width="2"><?php echo $data['no']?></td>
 <td><?php echo $data['nama']?></td>
		 <?php foreach($data['jumlah'] as $dx){?><td><?php echo $dx['jml'];?></td><?php }?>
		 </tr>
 <?php endforeach; ?>
		</tbody>
 </table>
<div class="left"> 
<a href="<?php echo site_url()?>analisis_grafik/leave" class="uibutton icon prev">Kembali</a>
</div>
 </div>
</div>
</td></tr>
</table>
</div>