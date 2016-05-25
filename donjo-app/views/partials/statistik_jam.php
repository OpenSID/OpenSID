<div class="themes bigfull">
<div class='title'>
<h2><a href="#">Statistik Kependudukan berdasarkan Penerimaan Jamkesmas</a></h2>
</div>
<div class='entry'>
<link href="<?php echo base_url()?>assets/front/general.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo base_url()?>assets/js/highcharts/highcharts.js"></script>
<script type="text/javascript">
			var chart;
			$(document).ready(function() {
				chart = new Highcharts.Chart({
					chart: {
						renderTo: 'container',
						defaultSeriesType: 'column'
					},
					title: {
						text: 'Diagram Batang Jumlah Penduduk Penerima Jamkesmas'
					},
					xAxis: {
						title: {
							text: 'Kelompok Penerima Jamkesmas'
						},
                        categories: [
						<?php  $i=0;foreach($main as $data){$i++;?>
						  <?php echo "'$data[nama]',";?>
						<?php }?>
						]
					},
					yAxis: {
						title: {
							text: 'Jumlah Penduduk'
						}
					},
					legend: {
						layout: 'vertical',
						backgroundColor: '#FFFFFF',
						align: 'left',
						verticalAlign: 'top',
						x: 100,
						y: 70,
						floating: true,
						shadow: true,
                        enabled:false
					},
					tooltip: {
						formatter: function() {
							return ''+
								this.x +': '+ this.y +'';
						}
					},
					plotOptions: {
						series: {
                            colorByPoint: true
                        },
                        column: {
							pointPadding: 0.2,
							borderWidth: 0
						}
					},
				        series: [{
						name: 'Jumlah Penduduk',
						data: [
						<?php  foreach($main as $data){?>
						  <?php echo $data['jumlah'].",";?>
						<?php }?>]
				
					},{
						name: 'Penerima Jamkesmas',
						colorByPoint: false,
						color : '#5B2D1D',
						data: [
						<?php  foreach($main as $data){?>
						  <?php echo $data['jamkesmas'].",";?>
						<?php }?>]
				
					}]
				});
				
				
			});
				
</script>
	<div id="container" style="min-width: 550px; height: 500px; margin: 0 auto"></div>
	<div id="contentpane">
    <div class="ui-layout-north panel top">
    </div>
    <div class="ui-layout-south panel bottom" style="max-height: 350px;overflow:auto; font-size:11px;">
        <table class="list"  style="font-size:12px;">
		<thead>
            <tr>
                <th>No</th>
				<th align="left" align="center">Jenis Kelompok</th>
				<th align="left" align="center">Jumlah Penduduk</th>
            
			</tr>
		</thead>
		<tbody>
        <?php   $i=0;$j=0;foreach($main as $data): ?>
		<tr>
          <td align="center" width="2"><?php echo $data['id']?></td>
          <td><?php echo $data['nama']?></td>
          <td><?php echo $data['jumlah']?></td>
		  </tr><?php  $i=$i+$data['jumlah'];?><?php  $j=$j+$data['jamkesmas'];?>
        <?php  endforeach; ?>
		<tr>
			<td colspan="2"><b>Jumlah</b></td>
			<td><b><?php  echo $i;?></b></td>
		</tr>
		</tbody>
        </table>
    </div>
</div>
</div>
</div>
