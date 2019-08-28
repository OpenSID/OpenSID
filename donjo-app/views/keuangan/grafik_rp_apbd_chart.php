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
		        text: 'Realisasi Pelaksanaan APBDesa'
		    },
		    subtitle: {
		        text: "<?= 'Semester '.$smt.' Tahun '.$thn ?>"
		    },
		    xAxis: {
		        categories: ['(PA) Pendapatan Desa', '(PA) Belanja Desa', '(PA) Pembiayaan Desa'],
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
		        },
		        series: {
		            pointWidth: 25
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
		        		return 'Rp' + Highcharts.numberFormat(this.y, '.', ',');
		        	}
		        },
		        data: [<?= join($anggaran, ',') ?>]
	        },{
		        name: 'Realisasi',
		        dataLabels: {
				    formatter: function () {
				    	var index = this.series.index;
				    	var pointB = this.series.chart.series[0].data[index].y;
				    	var percent = Highcharts.numberFormat(this.y / pointB * 100, 0);
				    	return 'Rp' + Highcharts.numberFormat(this.y, '.', ',') + ' (' +	percent + ' %'+')';
				    }
			    },
		        data: [<?= join($realisasi, ',') ?>]
		    }]
		});
	});
</script>