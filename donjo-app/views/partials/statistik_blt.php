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
						text: 'Statistik Kelas Sosial & Memperoleh Raskin'
					},
					xAxis: {
						title: {
							text: 'Kelas Sosial'
						},
 categories: [
						<?php $i=0;foreach($main as $data){$i++;?>
						 <?php echo "'$data[nama]',";?>
						<?php }?>
						]
					},
					yAxis: {
						title: {
							text: 'Populasi'
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
						name: 'Populasi',
						data: [
						<?php foreach($main as $data){?>
						 <?php echo $data['jumlah'].",";?>
						<?php }?>]
				
					},{
						name: 'Memperoleh Raskin',
						colorByPoint: false,
						color:'#80699B',
						data: [
						<?php foreach($main as $data){?>
						 <?php echo $data['raskin'].",";?>
						<?php }?>]
				
					}]
				});
				
				
			});
				
</script>
<?php 
	echo "
	<div class=\"box box-danger\">
		<div class=\"box-header with-border\">
			<h3 class=\"box-title\">Grafik Data Penerima Bantuan Langsung Tunai</h3>
			<div class=\"box-tools pull-right\">
				<div class=\"btn-group-xs\">";
					$strC = ($tipe==1)? "btn-primary":"btn-default";
					echo "<a href=\"".site_url("first/statistik/$st/1")."\" class=\"btn ".$strC." btn-xs\">Bar Graph</a>";
					$strC = ($tipe==0)? "btn-primary":"btn-default";
					echo "<a href=\"".site_url("first/statistik/$st/0")."\" class=\"btn ".$strC." btn-xs\">Pie Cart</a>
				</div>
			</div>
		</div>
		<div class=\"box-body\">
			<div id=\"container\"></div>
			<div id=\"contentpane\">
				<div class=\"ui-layout-north panel top\"></div>
				<div class=\"ui-layout-center\" id=\"chart\" style=\"padding: 5px;\"></div>
			</div>
		</div>
	</div>
	<div class=\"box box-danger\">
		<div class=\"box-header with-border\">
			<h3 class=\"box-title\">Tabel Data Penerima Bantuan Langsung Tunai</h3>
		</div>
		<div class=\"box-body\">
			<table class=\"table table-striped\">
				<thead>
				<tr>
					<th rowspan=\"2\">No</th>
					<th rowspan=\"2\">Kelompok</th>
					<th colspan=\"2\">Jumlah</th>
					<th colspan=\"2\">Laki-laki</th>
					<th colspan=\"2\">Perempuan</th>
					</tr>
				<tr>
					<th>n</th><th>%</th>
					<th>n</th><th>%</th>
					<th>n</th><th>%</th>
				</tr>
				</thead>
				<tbody>";
				$i=0; $l=0; $p=0;
				foreach($stat as $data){
					echo "<tr>
						<td class=\"angka\">".$data['no']."</td>
						<td>".$data['nama']."</td>
						<td class=\"angka\">".$data['jumlah']."</td>
						<td class=\"angka\">".$data['persen']."</td>
						<td class=\"angka\">".$data['laki']."</td>
						<td class=\"angka\">".$data['persen1']."</td>
						<td class=\"angka\">".$data['perempuan']."</td>
						<td class=\"angka\">".$data['persen2']."</td>
					</tr>";
					$i=$i+$data['jumlah']; 
					$l=$l+$data['laki']; $p=$p+$data['perempuan'];
				}
				echo "
				</tbody>
			</table>";
		
		echo "
		</div>
	</div>";
?>
<div class="themes bigfull">
<div class='title'>
<h2><a href="#">Grafik Statistik Penduduk</a></h2>
</div>
<div class='entry'>
<link href="<?php echo base_url()?>assets/front/general.css" rel="stylesheet" type="text/css" />
	<div id="container" style="min-width: 550px; height: 500px; margin: 0 auto"></div>
	<div id="contentpane">
 <div class="ui-layout-north panel top">
 </div>
 <div class="ui-layout-south panel bottom" style="max-height: 350px;overflow:auto; font-size:11px;">
 <table class="list" style="font-size:12px;">
		<thead>
 <tr>
 <th>No</th>
				<th>Statistik</th>
				<th>Populasi</th>
				<th>Memperoleh Bantuan Langsung Tunai</th>
 
			</tr>
		</thead>
		<tbody>
 <?php $i=0;$j=0;foreach($main as $data): ?>
		<tr>
 <td align="center" width="2"><?php echo $data['id']?></td>
 <td><?php echo $data['nama']?></td>
 <td><?php echo $data['jumlah']?></td>
 <td><?php echo $data['blt']?></td>
		 </tr><?php $i=$i+$data['jumlah'];?><?php $j=$j+$data['blt'];?>
 <?php endforeach; ?>
		<tr>
			<td colspan="2"><b>Jumlah</b></td>
			<td><b><?php echo $i;?></b></td>
			<td><b><?php echo $j;?></b></td>
		</tr>
		</tbody>
 </table>
 </div>
</div>
</div>
</div>