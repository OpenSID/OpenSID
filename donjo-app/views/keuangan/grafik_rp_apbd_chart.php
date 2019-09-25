<div id="<?= $type . '-' . $smt . '-' . $thn ?>" ></div>

<script type="text/javascript">
	$(document).ready(function (){
		var pointWidth = 25;
		var anggaran = [<?= join($anggaran, ',') ?>];
		var countData = anggaran.length
		var marginTop = 70;
		var marginRight = 10;
		var marginBottom = 50;
		var marginLeft = 100;
		var groupPadding = 1;
		var pointPadding = 0.3;
		var chartHeight = marginTop + marginBottom + ((pointWidth * countData) * (1 + groupPadding + pointPadding));
			
		Highcharts.setOptions({
			lang: {
				thousandsSep: '.'
			}
		})
		
		Highcharts.chart("<?= $type . '-' . $smt . '-' . $thn ?>", {
	    chart: {
				type: 'bar',
				marginTop: marginTop,
				marginRight: marginRight,
				marginBottom: marginBottom,
				marginLeft: marginLeft,
				height: chartHeight
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
          pointWidth: pointWidth,
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
	        data: [<?= join($anggaran, ',') ?>]
        },
        {
	        name: 'Realisasi',
	        dataLabels: {
				    formatter: function () {
				    	var index = this.series.data.indexOf(this.point);
				    	var pointB = this.series.chart.series[0].data[index].y;
				    	var percent = Highcharts.numberFormat(this.y / pointB * 100, 0);
				    	return ' (' + percent + ' %'+')';
				    }
			    },
	        data: [<?= join($realisasi, ',') ?>]
	    	}
	    ]
		});

	});
</script>