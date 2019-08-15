<div id="<?= $type . '-' . $smt . '-' . $thn ?>" ></div>

<script type="text/javascript">
	$(document).ready(function (){
		Highcharts.setOptions({
			lang: {
				thousandsSep: '.'
			}
		})
		Highcharts.chart("<?= $type . '-' . $smt . '-' . $thn ?>", {
		    chart: {
		        type: 'bar'
		    },
		    title: {
		        text: 'Realisasi Belanja Desa'
		    },
		    subtitle: {
		        text: "<?= 'Semester '.$smt.' Tahun '.$thn ?>"
		    },
		    xAxis: {
		        categories: [<?= join($bidang, ',')?>],
		    },
		    yAxis: {
		        min: 0,
		        title: {
		            text: 'Realisasi'
		        },
		        labels: {
		            overflow: 'justify',
		            enabled: false
		        }
		    },
		    tooltip: {
		        valueSuffix: ''
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
		    series: [{
		        name: 'Anggaran',
		        dataLabels: {
		        	formatter: function () {
		        		return 'Rp. ' + Highcharts.numberFormat(this.y, '.', ',');
		        	}
		        },
				color: '#2E8B57',
		        data: [<?= join($anggaran, ',') ?>]
	        },{
		        name: 'Realisasi',
		        dataLabels: {
				    formatter: function () {
				    	var index = this.series.index;
				    	var pointB = this.series.chart.series[0].data[index].y;
				    	var percent = Highcharts.numberFormat(this.y / pointB * 100, '.', ',');
				    	return 'Rp. ' + Highcharts.numberFormat(this.y, '.', ',') + ' (' +	percent + ' %'+')';
				    }
			    },
				color: '#3461eb',
		        data: [<?= join($realisasi, ',') ?>]
		    }]
		});
	});
</script>
<script src="<?= base_url()?>assets/js/highcharts/highcharts.js"></script>
<script src="<?= base_url()?>assets/js/highcharts/exporting.js"></script>
<script src="<?= base_url()?>assets/js/highcharts/highcharts-more.js"></script>