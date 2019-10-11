<style type="text/css">
	.nowrap { white-space: nowrap; }
</style>
<div class="content-wrapper">
	<section class="content-header">
		<h1>Laporan Keuangan</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li class="active">Laporan Keuangan</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<div class="row">
			<?php $this->load->view('keuangan/filter_laporan'); ?>
			<div class="col-md-9">
				<div class="box box-danger">
					<div class="box-body">
						<h4>Informasi Anggaran</h4>
						<div class="box box-danger">
							<div class="box-header with-border">
								<div class="col-md-4">
									<h5>Anggaran</h5>
									<h4><b id="data_anggaran">Rp . 0</b></h4>
								</div>
								<div class="col-md-4">
									<h5>Anggaran PAK</h5>
									<h4><b id="data_pak">Rp .0</b></h4>
								</div>
								<div class="col-md-4">
									<h5>Anggaran Setelah PAK</h5>
									<h4><b id="data_total">Rp . 0</b></h4>
								</div>
							</div>
							<div class="box-body">
								<div class="col-md-12">
									<div class="box box-danger">
										<div id="chart"></div>
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
		setData();
	});

	function setData()
	{
		var tahun = $('#tahun_anggaran').val();
		var semester = $('#semester').val();
		$('#alert').hide();

		get_anggaran(tahun, semester);
	}

	function numberWithDots(x) {
	    return (
	    	x.replace('.', ',').replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.')
	    )
	}

	function get_anggaran(tahun, semester)
	{
		$.ajax({
			type  : 'GET',
			url   : '<?php echo site_url('keuangan/anggaran/')?>' + tahun + "/" + semester,
			dataType : 'json',
			success : function(data){
				var anggaran = "Rp" + numberWithDots(data.data_anggaran.Anggaran);
				var pak = "Rp" + numberWithDots(data.data_anggaran.AnggaranPAK);
				var total = "Rp" + numberWithDots(data.data_anggaran.AnggaranStlhPAK);
				$('#data_anggaran').html(anggaran);
				$('#data_pak').html(pak);
				$('#data_total').html(total);

				Highcharts.setOptions({
					lang: {
						thousandsSep: '.'
					}
				})
				Highcharts.chart('chart', {
			    chart: {
		        type: 'bar'
			    },
			    title: {
		        text: 'Pagu Anggaran VS Realisasi'
			    },
			    subtitle: {
		        text: 'Tahun ' + tahun
			    },
			    xAxis: {
		        categories: ['Realisasi'],
			    },
			    yAxis: {
		        min: 0,
		        title: '',
		        labels: {
		          overflow: 'justify',
		          enabled: false
		        }
			    },
			    tooltip: {
		        valueSuffix: '',
						formatter: function () {
							return '<b>'+this.x+'</b><br/>'+this.series.name+ ': '+'Rp' + Highcharts.numberFormat(this.y, '.', ',');
						}
			    },
			    plotOptions: {
		        bar: {
	            dataLabels: {
                enabled: true
	            }
		        },
		        series: {
	            pointWidth: 25,
	            grouping: false
		        }
			    },
			    legend: {
		        layout: 'vertical',
		        align: 'right',
		        verticalAlign: 'top',
		        x: 0,
		        y: 0,
		        floating: true,
		        borderWidth: 1,
		        backgroundColor: ((Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'),
		        shadow: true
			    },
			    credits: {
		        enabled: false
			    },
			    series: [
			    	{
			        name: 'Anggaran',
							dataLabels: {
			        	formatter: function () {
			        		return 'Rp' + Highcharts.numberFormat(this.y, '.', ',');
			        	}
			        },
			        data: [parseInt(data.data_realisasi.anggaran.AnggaranStlhPAK)]
				    },
				    {
			        name: 'Realisasi',
							dataLabels: {
			        	formatter: function () {
					    	var pointB = this.series.chart.series[0].data[0].y;
					    	var percent = Highcharts.numberFormat(this.y / pointB * 100, 0);
					    	return ' (' + percent + ' %'+')';
			        	}
			        },
			        data: [parseInt(data.data_realisasi.realisasi.Nilai)]
				    }
			    ]
				});
			}

		});
	}
</script>