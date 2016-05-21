<div class="themes nobig2">
<div class='title'>
<h2><a href="#">Grafik Statistik Penduduk</a></h2>
</div>
<div class='entry'>
<script type="text/javascript" src="<?=base_url()?>assets/js/highcharts/highcharts.js"></script>
<script type="text/javascript">
			var chart;
			$(document).ready(function() {
				chart = new Highcharts.Chart({
					chart: {
						renderTo: 'chart',
						defaultSeriesType: 'column'
					},
					title: {
						text: 'Statistik Penduduk'
					},
					xAxis: {
						title: {
							text: 'Statistik'
						},
                        categories: [<? $i=0;foreach($stat as $data){$i++;?><?if($data['jumlah'] != "-"){echo $data['nama'].",";}?><?}?>]
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
                        enabled:true
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
						data: [<? foreach($stat as $data){?><?if($data['jumlah'] != "-"){echo $data['jumlah'].",";}?><?}?>]
					}]
				});
				
				
			});
				
</script>
<div id="chart" style="min-width: 550px; height: 550px; margin: 0 auto"></div>
</div>
</div>
