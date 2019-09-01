<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<script type="text/javascript" src="<?= base_url()?>assets/js/highcharts/highcharts.js"></script>
<script type="text/javascript">
			var chart;
			$(document).ready(function() {
				chart = new Highcharts.Chart({
					chart: {
						renderTo: 'container',
						defaultSeriesType: 'column'
					},
					title: {
						text: 'Statistik Kelas Sosial'
					},
					xAxis: {
						title: {
							text: 'Kelas Sosial'
						},
                        categories: [
						<?php  $i=0;foreach($main as $data){$i++;?>
						  <?= "'$data[nama]',";?>
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
						<?php  foreach($main as $data){?>
						  <?= $data['jumlah'].",";?>
						<?php }?>]

					}]
				});


			});

</script>

<?php

	echo "
	<div class=\"single_page_area\">
		<div class=\"box-header with-border\">
			<h2 class=\"post_titile\">  <i class=\"si si-bar-chart\"></i> Grafik Statistik Kependudukan berdasarkan Indeks Kemiskinan</h2>
		</div>
		<div class=\"box-body\">
			<div id=\"container\"></div>
			<div id=\"contentpane\">
				<div class=\"ui-layout-north panel top\"></div>
				<div class=\"ui-layout-center\" id=\"chart\" style=\"padding: 5px;\"></div>
			</div>
		</div>
	</div>

	<div class=\"single_page_area\">
		<div class=\"box-header with-border\">
			<h3 class=\"box-title\">Tabel</h3>
		</div>
		<div class=\"box-body\">
			<table class=\"table table-striped\">
				<thead>
				<tr>
					<th>#</th>
					<th>Kelompok</th>
					<th>Jumlah</th>
					</tr>
				</thead>
				<tbody>";
				$i=0;
				foreach($main as $data){
					echo "<tr>
						<td class=\"angka\">".$data['id']."</td>
						<td>".$data['nama']."</td>
						<td class=\"angka\">".$data['jumlah']."</td>
					</tr>";
					$i=$i+$data['jumlah'];
				}
				echo "
				</tbody>
				<tfooter><tr><th colspan=\"2\" class=\"angka\">JUMLAH</th><th>".$i."</th></tr></tfooter>
			</table>";

		echo "
		</div>
	</div>";
?>

