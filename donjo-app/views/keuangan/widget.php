<style type="text/css">
	.nowrap { white-space: nowrap; }
</style>
<script>
	$(function()
	{
		var keyword = <?= $keyword?> ;
		$( "#cari" ).autocomplete(
		{
			source: keyword,
			maxShowItems: 10,
		});
	});
</script>
<div class="content-wrapper">
	<section class="content-header">
		<h1>Widget Keuangan</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li class="active">Widget Keuangan</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-danger">
					<div class="box-header with-border">
						<h4>Informasi Anggaran  <?= $tahun_anggaran ?> </h4>
					</div>
					<div class="box-body">
						<div class="box box-danger">
							<div class="box-header with-border">
								<div class="col-md-4">
									<h5>Anggaran</h5>
									<h4><b>Rp . <?= $anggaran_keuangan ?></b></h4>
								</div>
								<div class="col-md-4">
									<h5>Anggaran PAK</h5>
									<h4><b>Rp . <?= $anggaranPAK ?></b></h4>
								</div>
								<div class="col-md-4">
									<h5>Anggaran Setelah PAK</h5>
									<h4><b>Rp . <?= $anggaranStlhPAK ?></b></h4>
								</div>
							</div>
							<div class="box-body">
								<div class="box box-danger">
									<div class="box-header with-border">
										<h4>Pendapatan Desa</h4>
									</div>
									<div class="box-body">
										<div class="col-md-4">
											<div id="chart"></div>
										</div>
										<div class="col-md-8">
											<div id="chart2"> </div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>

<script type="text/javascript">
var chart;
var chart2;
	$(document).ready(function ()
	{
		Highcharts.chart('chart2', {
    chart: {
        type: 'spline'
    },
    title: {
        text: 'Data Pendapatan Desa <?= $tahun_anggaran?>'
    },
    // subtitle: {
    //     text: 'Source: WorldClimate.com'
    // },
    xAxis: {
        categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
            'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
    },
    yAxis: {
        title: {
            text: 'Temperature'
        },
        labels: {
            formatter: function () {
                return 'Rp '+ this.value ;
            }
        }
    },
    tooltip: {
        crosshairs: true,
        shared: true
    },
    plotOptions: {
        spline: {
            marker: {
                radius: 4,
                lineColor: '#666666',
                lineWidth: 1
            }
        }
    },
    series: [{
        name: 'Anggaran',
        // marker: {
        //     symbol: 'square'
        // },
        data: [7.0, 6.9, 9.5, 14.5, 18.2, 21.5, 25.2, {
            y: 26.5,
        }, 23.3, 18.3, 13.9, 9.6]

    },
		 {
        name: 'Anggaran PAK',
        // marker: {
        //     symbol: 'diamond'
        // },
        data: [{
            y: 5.9,
        }, 7.2, 9.7, 9.5, 10.9, 17.2, 11.0, 14.6, 13.2, 13.3, 4.6, 6.8]
    },
		{
			 name: 'Anggaran Setelah PAK',
			 // marker: {
				// 	 symbol: 'diamond'
			 // },
			 data: [{
					 y: 3.9,
			 }, 4.2, 5.7, 8.5, 11.9, 15.2, 17.0, 16.6, 14.2, 10.3, 6.6, 4.8]
	 }
	]
});


		chart = new Highcharts.Chart({
			chart:
			{
				renderTo: 'chart',
				plotBackgroundColor: null,
				plotBorderWidth: null,
				plotShadow: false
			},
			title:
			{
				text: 'Data Pendapatan Desa'
			},
			subtitle:
			{
				text: 'Tahun Anggaran <?= $tahun_anggaran?>'
			},
			plotOptions:
			{
				index:
				{
					allowPointSelect: true,
					cursor: 'pointer',
					dataLabels:
					{
						enabled: true
					},
					showInLegend: true
				}
			},
			legend:
			{
				layout: 'vertical',
				backgroundColor: '#FFFFFF',
				align: 'right',
				verticalAlign: 'top',
				x: -30,
				y: 0,
				floating: true,
				shadow: true,
        enabled:true
			},
			series: [{
				type: 'pie',
				name: 'Pendapatan Desa',
				data: [
					{
            name: 'Anggaran',
            y: 61.41,
            sliced: true,
            selected: true
        }, {
            name: 'Anggaran PAK',
            y: 11.84
        }, {
            name: 'Anggaran Setelah PAK',
            y: 10.85
        }
				]
			}]
		});
	});
</script>
<!-- Highcharts -->
<script src="<?= base_url()?>assets/js/highcharts/highcharts.js"></script>
<script src="<?= base_url()?>assets/js/highcharts/exporting.js"></script>
<script src="<?= base_url()?>assets/js/highcharts/highcharts-more.js"></script>
