<!-- widget Statistik -->
<style type="text/css">
  .highcharts-xaxis-labels tspan {font-size: 8px;}
</style>
<div class="box box-info box-solid">
  <div class="box-header">
    <h3 class="box-title"><a href="<?= site_url("first/keuangan/1")?>"><i class="fa fa-bar-chart"></i> Statistik Keuangan Desa</a></h3>
  </div>
  <div class="box-body">
          <div id="chart"></div>
          <br>
          <hr>
          <div id="realisasi"></div>
  </div>
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
		        text: 'Tahun <?= $widget_keuangan['tahun_anggaran'] ?>'
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
		            text: 'Juta',
		            align: 'high'
		        },
		        labels: {
		            overflow: 'justify'
		        }
		    },
		    tooltip: {
		        valueSuffix: 'juta'
		    },
		    plotOptions: {
		        bar: {
		            dataLabels: {
		                enabled: true
		            }
		        }
		    },
		    credits: {
		        enabled: false
		    },
		    series: [{
		        name: 'Anggaran',
						color: '#2E8B57',
		        data: [<?= $widget_keuangan['pendapatan_desa'] ?>, 31, 635]
		    }, {
		        name: 'Realisasi',
						color: '#FFD700',
		        data: [<?= $widget_keuangan['realisasi_pendapatan_desa'] ?>, 156, 947]
		    }]
		});
	});

  Highcharts.chart('realisasi', {
      chart: {
          type: 'bar'
      },
      title: {
          text: 'Realisasi Anggaran (RA)'
      },
      subtitle: {
          text: 'Tahun <?= $widget_keuangan['tahun_anggaran'] ?>'
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
          valueSuffix: ' juta'
      },
      plotOptions: {
          bar: {
              dataLabels: {
                  enabled: true
              }
          }
      },
      credits: {
          enabled: false
      },
      series: [{
          name: 'Anggaran',
          color: '#2E8B57',
          data: [<?= $pendapatan_desa->AnggaranStlhPAK ?>, 31, 635]
      }, {
          name: 'Realisasi',
          color: '#FFD700',
          data: [<?= $realisasi_pendapatan_desa->AnggaranStlhPAK ?>, 156, 947]
      }]
  });
</script>
<!-- Highcharts -->
<script src="<?= base_url()?>assets/js/highcharts/highcharts.js"></script>
<script src="<?= base_url()?>assets/js/highcharts/exporting.js"></script>
<script src="<?= base_url()?>assets/js/highcharts/highcharts-more.js"></script>
