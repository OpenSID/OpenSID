<style>
table.form.detail th{
    padding:5px;
    background:#fafafa;
    border-right:1px solid #eee;
}
table.form.detail td{
    padding:5px;
}
</style>
<div id="pageC">
<table class="inner">
<tr style="vertical-align:top">
<td class="side-menu">
<legend>Menu Surat Keluar</legend>
<div class="lmenu">
<ul>
<li ><a href="<?php echo site_url('keluar')?>">Surat Keluar</a></li>
<li ><a href="<?php echo site_url('keluar/perorangan')?>">Rekam Surat Perorangan</a></li>
<li class="selected"><a href="<?php echo site_url('keluar/graph')?>">Grafik surat keluar</a></li>
</ul>
</div>

</td>
<td style="background:#fff;padding:5px;"> 

<div class="content-header">
   
</div>
<div id="contentpane">
<div class="ui-layout-north panel">
 <h3>Grafik Surat Keluar</h3></div>
<div class="ui-layout-center" id="maincontent" style="padding: 5px;">
<table class="form">


<div class="block"><head>

		<script type="text/javascript">
$(function () {
    var chart;
    
    $(document).ready(function () {
    	
    	// Build the chart
        chart = new Highcharts.Chart({
            chart: {
                renderTo: 'container',
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false
            },
            title: {
                text: 'Surat Keluar'
            },
            tooltip: {
        	    pointFormat: '{series.name}: <b>{point.percentage}%</b>',
            	percentageDecimals: 1
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: false
                    },
                    showInLegend: true
                }
            },
            series: [{
                type: 'pie',
                name: 'Prosentase',
                data: [
                 				<?php  foreach($stat as $data){?>
							<?php if($data['jumlah'] != "-"){?>
								['<?php echo $data['nama']?>',<?php echo $data['jumlah']?>],
							<?php }?>
						<?php }?>
                ]
            }]
        });
    });
    
});
		</script>
	</head>
	<body>
<script type="text/javascript" src="<?php echo base_url()?>assets/js/highcharts/highcharts.js"></script>



<div id="container" style="min-width: 500px; height: 500px; margin: 0 auto"></div>

	</body>
	
	


</table>
</div>
   
<div class="ui-layout-south panel bottom">
<div class="left">     

</div>
<div class="right">
<div class="uibutton-group">

</div>
</div>
</div> </form>
</div>
</td></tr></table>
</div>
