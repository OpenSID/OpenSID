<div id="pageC">
<!-- Start of Space Admin -->
	<table class="inner">
	<tr style="vertical-align:top">
	<td class="side-menu">
    <?php include("donjo-app/views/statistik/laporan/side-menu.php"); ?>
	</td>
<td style="background:#fff;padding:0px;">
<script type="text/javascript">
$(function () {
    var chart;

    $(document).ready(function () {

    	// Build the chart
        chart = new Highcharts.Chart({
            chart: {
                renderTo: 'chart',
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: true
            },
            title: {
						text: 'Statistik <?php echo $stat?>'
			},
            plotOptions: {
                index: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true
                    },
                    showInLegend: true
                }
            },
					legend: {
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
                name: 'Populasi',
                data: [
						<?php  foreach($main as $data){?>
						  <?php if($data['nama'] != "TOTAL"){?>
							<?php if($data['jumlah'] != "-"){?>
								['<?php echo strtoupper($data['nama'])?>',<?php echo $data['jumlah']?>],
							<?php }?>
						<?php }?>
						<?php }?>
                ]
            }]
        });
    });

});
</script>
<script src="<?php echo base_url()?>assets/js/highcharts/highcharts.js"></script>
<script src="<?php echo base_url()?>assets/js/highcharts/highcharts-more.js"></script>
<script src="<?php echo base_url()?>assets/js/highcharts/exporting.js"></script>

<style>
tr#total{
    background:#fffdc5;
    font-size:12px;
    white-space:nowrap;
    font-weight:bold;
}
</style>
<div id="contentpane">
    <div class="ui-layout-north panel top">
    </div>
    <div class="ui-layout-center" id="chart" style="padding: 5px;">

    </div>
    <div class="ui-layout-south panel bottom" style="max-height: 150px;overflow:auto;">
        <table class="list">
		<thead>
            <tr>
                <th>No</th>
				<th align="left" align="center">Jenis Kelompok</th>
				<th align="left" align="center">Jumlah</th>
				<?php  if($lap<20){?>
				<th align="left" align="center" width="60">Laki-laki</th>
				<th align="left" align="center" width="60">Perempuan</th>
				<?php }?>

			</tr>
		</thead>
		<tbody>
        <?php  foreach($main as $data): ?>
		<tr>
          <td align="center" width="2"><?php echo $data['no']?></td>
          <td><?php echo strtoupper($data['nama'])?></td>
          <td><?php echo $data['jumlah']?></td>
		  <?php  if($lap<20){?>
		  <td><?php echo $data['laki']?></td>
          <td><?php echo $data['perempuan']?></td>
		  <?php }?>
		  </tr>
        <?php  endforeach; ?>
		</tbody>
        </table>
    </div>
</div>
</td></tr></table>
</div>
