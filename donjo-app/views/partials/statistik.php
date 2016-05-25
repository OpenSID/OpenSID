<div class="themes bigfull">
<div class='title'>
<h2>Statistik Kependudukan berdasarkan <?php echo $heading;?></h2>
</div>
<div style="position:absolute;right:25px;top:20px;">
<a class="uibutton <?php if($tipe==1){?>special<?php }?> "href="<?php echo site_url("first/statistik/$st/1")?>">Bar Graph</a>
<a class="uibutton <?php if($tipe==0){?>special<?php }?> "href="<?php echo site_url("first/statistik/$st/0")?>">Pie Cart</a>
</div>
<div class='entry'>
<link href="<?php echo base_url()?>assets/front/general.css" rel="stylesheet" type="text/css" />
<?php if($tipe==1){?>
<script type="text/javascript">
$(function () {
    var chart;
    $(document).ready(function () {

        chart = new Highcharts.Chart({
            chart: { renderTo: 'container'},
            title:0,
					xAxis: {
                        categories: [
						<?php  $i=0;foreach($stat as $data){$i++;?>
						  <?php if($data['jumlah'] != "-" AND $data['nama']!= "TOTAL"){echo "'$i',";}?>
						<?php }?>
						]
					},
				plotOptions: {
					series: {
						colorByPoint: true
					},
					column: {
						pointPadding: -0.1,
						borderWidth: 0
					}
				},
					legend: {
                        enabled:false
					},
            series: [{
                type: 'column',
                name: 'Jumlah Populasi',
				shadow:1,
				border:1,
                data: [
						<?php  foreach($stat as $data){?>
							<?php if($data['jumlah'] != "-" AND $data['nama']!= "TOTAL"){?>
								['<?php echo $data['nama']?>',<?php echo $data['jumlah']?>],
							<?php }?>
						<?php }?>
                ]
            }]
        });
    });
    
});
</script>
<?php }else{?>

<script type="text/javascript">
$(function () {
    var chart;
    
    $(document).ready(function () {
    	
    	// Build the chart
        chart = new Highcharts.Chart({
            chart: {
                renderTo: 'container'
            },
            title:0,
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    showInLegend: true
                }
            },
            series: [{
                type: 'pie',
                name: 'Jumlah Populasi',
				shadow:1,
				border:1,
                data: [
						<?php  foreach($stat as $data){?>
							<?php if($data['jumlah'] != "-" AND $data['nama']!= "TOTAL"){?>
								['<?php echo $data['nama']?>',<?php echo $data['jumlah']?>],
							<?php }?>
						<?php }?>
                ]
            }]
        });
    });
    
});
</script>
<?php }?>
<script src="<?php echo base_url()?>assets/highchart/highcharts.js"></script>
<script src="<?php echo base_url()?>assets/highchart/highcharts-more.js"></script>
<script src="<?php echo base_url()?>assets/highchart/exporting.js"></script>
<div id="container" style="min-width: 550px; height: 700px; margin: 0 auto"></div><div id="contentpane">
    <div class="ui-layout-north panel top">
    </div>
    <div class="ui-layout-center" id="chart" style="padding: 5px;">                
        
    </div>
    <div class="ui-layout-south panel bottom" style="max-height: 350px;overflow:auto; font-size:11px;">
        <table class="list"  style="font-size:12px;">
		<thead>
            <tr>
                <th>No</th>
				<th align="left" align="center">Jenis Kelompok</th>
				<th align="left" align="center">Jumlah</th>
				<th></th>
				<th align="left" align="center" width="60">Laki-laki</th>
				<th></th>
				<th align="left" align="center" width="60">Perempuan</th>
				<th></th>
            
			</tr>
		</thead>
		<tbody>
        <?php  $i=0; $l=0; $p=0; foreach($stat as $data): ?>
		<tr>
          <td align="center" width="2"><?php echo $data['no']?></td>
          <td><?php echo $data['nama']?></td>
          <td><?php echo $data['jumlah']?></td>
          <td><?php echo $data['persen']?></td>
		  <td><?php echo $data['laki']?></td>
          <td><?php echo $data['persen1']?></td>
          <td><?php echo $data['perempuan']?></td>
          <td><?php echo $data['persen2']?></td>
		</tr>
		  <?php  $i=$i+$data['jumlah'];?>
		  <?php  $l=$l+$data['laki'];?>
		  <?php  $p=$p+$data['perempuan'];?>
        <?php  endforeach; ?>

		</tbody>
        </table>
    </div>
</div>
</div>
</div>
