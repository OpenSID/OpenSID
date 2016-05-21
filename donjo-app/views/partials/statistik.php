<div class="themes bigfull">
<div class='title'>
<h2>Statistik Kependudukan berdasarkan <?=$heading;?></h2>
</div>
<div style="position:absolute;right:25px;top:20px;">
<a class="uibutton <?if($tipe==1){?>special<?}?> "href="<?=site_url("first/statistik/$st/1")?>">Bar Graph</a>
<a class="uibutton <?if($tipe==0){?>special<?}?> "href="<?=site_url("first/statistik/$st/0")?>">Pie Cart</a>
</div>
<div class='entry'>
<link href="<?=base_url()?>assets/front/general.css" rel="stylesheet" type="text/css" />
<?if($tipe==1){?>
<script type="text/javascript">
$(function () {
    var chart;
    $(document).ready(function () {

        chart = new Highcharts.Chart({
            chart: { renderTo: 'container'},
            title:0,
					xAxis: {
                        categories: [
						<? $i=0;foreach($stat as $data){$i++;?>
						  <?if($data['jumlah'] != "-" AND $data['nama']!= "TOTAL"){echo "'$i',";}?>
						<?}?>
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
						<? foreach($stat as $data){?>
							<?if($data['jumlah'] != "-" AND $data['nama']!= "TOTAL"){?>
								['<?=$data['nama']?>',<?=$data['jumlah']?>],
							<?}?>
						<?}?>
                ]
            }]
        });
    });
    
});
</script>
<?}else{?>

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
						<? foreach($stat as $data){?>
							<?if($data['jumlah'] != "-" AND $data['nama']!= "TOTAL"){?>
								['<?=$data['nama']?>',<?=$data['jumlah']?>],
							<?}?>
						<?}?>
                ]
            }]
        });
    });
    
});
</script>
<?}?>
<script src="<?=base_url()?>assets/highchart/highcharts.js"></script>
<script src="<?=base_url()?>assets/highchart/highcharts-more.js"></script>
<script src="<?=base_url()?>assets/highchart/exporting.js"></script>
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
        <? $i=0; $l=0; $p=0; foreach($stat as $data): ?>
		<tr>
          <td align="center" width="2"><?=$data['no']?></td>
          <td><?=$data['nama']?></td>
          <td><?=$data['jumlah']?></td>
          <td><?=$data['persen']?></td>
		  <td><?=$data['laki']?></td>
          <td><?=$data['persen1']?></td>
          <td><?=$data['perempuan']?></td>
          <td><?=$data['persen2']?></td>
		</tr>
		  <? $i=$i+$data['jumlah'];?>
		  <? $l=$l+$data['laki'];?>
		  <? $p=$p+$data['perempuan'];?>
        <? endforeach; ?>

		</tbody>
        </table>
    </div>
</div>
</div>
</div>
