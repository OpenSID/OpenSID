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
								<div class="col-md-6">
									<div class="box box-danger">
										<div id="chart"></div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="box box-danger">
										<div id="chart2"></div>
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

	$(document).ready(function ()
	{
		Highcharts.chart('chart', {
		    chart: {
		        type: 'bar'
		    },
		    title: {
		        text: 'Pagu Anggaran (PA)'
		    },
		    subtitle: {
		        text: 'Tahun <?= $tahun_anggaran?>'
		    },
		    xAxis: {
		        categories: ['(PA) Pendapatan Desa', '(PA) Belanja Desa', '(PA) Pembiayaan Desa'],
		        // title: {
		        //     text: null
		        // }
		    },
		    yAxis: {
		        min: 0,
		        title: {
		            text: 'Population (millions)',
		            align: 'high'
		        },
		        labels: {
		            overflow: 'justify'
		        }
		    },
		    tooltip: {
		        valueSuffix: ' millions'
		    },
		    plotOptions: {
		        bar: {
		            dataLabels: {
		                enabled: true
		            }
		        }
		    },
		    legend: {
		        layout: 'vertical',
		        align: 'right',
		        verticalAlign: 'top',
		        x: -40,
		        y: 80,
		        floating: true,
		        borderWidth: 1,
		        backgroundColor: ((Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'),
		        shadow: true
		    },
		    credits: {
		        enabled: false
		    },
		    series: [{
		        name: 'Anggaran',
		        data: [107, 31, 635]
		    }, {
		        name: 'Realisasi',
		        data: [133, 156, 947]
		    }]
		});
		Highcharts.chart('chart2', {
		    chart: {
		        type: 'bar'
		    },
		    title: {
		        text: 'Realisasi Anggaran (RA)'
		    },
		    subtitle: {
		        text: 'Tahun <?= $tahun_anggaran?>'
		    },
		    xAxis: {
		        categories: ['(PA) Pendapatan Desa', '(PA) Belanja Desa', '(PA) Pembiayaan Desa'],
		        // title: {
		        //     text: null
		        // }
		    },
		    yAxis: {
		        min: 0,
		        title: {
		            text: 'Population (millions)',
		            align: 'high'
		        },
		        labels: {
		            overflow: 'justify'
		        }
		    },
		    tooltip: {
		        valueSuffix: ' millions'
		    },
		    plotOptions: {
		        bar: {
		            dataLabels: {
		                enabled: true
		            }
		        }
		    },
		    legend: {
		        layout: 'vertical',
		        align: 'right',
		        verticalAlign: 'top',
		        x: -40,
		        y: 80,
		        floating: true,
		        borderWidth: 1,
		        backgroundColor: ((Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'),
		        shadow: true
		    },
		    credits: {
		        enabled: false
		    },
				series: [{
		        name: 'Anggaran',
		        data: [107, 31, 635]
		    }, {
		        name: 'Realisasi',
		        data: [133, 156, 947]
		    }]
		});


	});
</script>
<!-- Highcharts -->
<script src="<?= base_url()?>assets/js/highcharts/highcharts.js"></script>
<script src="<?= base_url()?>assets/js/highcharts/exporting.js"></script>
<script src="<?= base_url()?>assets/js/highcharts/highcharts-more.js"></script>
