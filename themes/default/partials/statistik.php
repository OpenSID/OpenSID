<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

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
						  <?php if($data['jumlah'] != "-" AND $data['nama']!= "TOTAL" AND $data['nama']!= "JUMLAH"){echo "'$i',";}?>
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
							<?php if($data['jumlah'] != "-" AND $data['nama']!= "TOTAL" AND $data['nama']!= "JUMLAH"){?>
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
							<?php if($data['jumlah'] != "-" AND $data['nama']!= "TOTAL" AND $data['nama']!= "JUMLAH"){?>
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
<style>
	tr.lebih{
		display:none;
	}
</style>
<script>
$(function(){
	$('#showData').click(function(){
		$('tr.lebih').show();
		$('#showData').hide();
	});
});
</script>
<?php

	echo "
	<div class=\"box box-danger\">
		<div class=\"box-header with-border\">
			<h3 class=\"box-title\">Grafik Data Demografi Berdasar ". $heading."</h3>
			<div class=\"box-tools pull-right\">
				<div class=\"btn-group-xs\">";
					$strC = ($tipe==1)? "btn-primary":"btn-default";
					echo "<a href=\"".site_url("first/statistik/$st/1")."\" class=\"btn ".$strC." btn-xs\">Bar Graph</a>";
					$strC = ($tipe==0)? "btn-primary":"btn-default";
					echo "<a href=\"".site_url("first/statistik/$st/0")."\" class=\"btn ".$strC." btn-xs\">Pie Cart</a>
				</div>
			</div>
		</div>
		<div class=\"box-body\">
			<div id=\"container\"></div>
			<div id=\"contentpane\">
				<div class=\"ui-layout-north panel top\"></div>
			</div>
		</div>
	</div>

	<div class=\"box box-danger\">
		<div class=\"box-header with-border\">
			<h3 class=\"box-title\">Tabel Data Demografi Berdasar ". $heading."</h3>
		</div>
		<div class=\"box-body\">
			<div class=\"table-responsive\">
			<table class=\"table table-striped\">
				<thead>
				<tr>
					<th rowspan=\"2\">No</th>
					<th rowspan=\"2\" style='text-align:left;'>Kelompok</th>
					<th colspan=\"2\">Jumlah</th>";
          if($jenis_laporan == 'penduduk'){
            echo "<th colspan=\"2\">Laki-laki</th>
            <th colspan=\"2\">Perempuan</th>";
          }
					echo "
        </tr>
				<tr>
					<th style='text-align:right'>n</th><th style='text-align:right'>%</th>";
          if($jenis_laporan == 'penduduk'){
  					echo "<th style='text-align:right'>n</th><th style='text-align:right'>%</th>
  					<th style='text-align:right'>n</th><th style='text-align:right'>%</th>";
          }
          echo "
				</tr>
				</thead>
				<tbody>";
				$i=0; $l=0; $p=0;
				$hide="";$h=0;
				$jm = count($stat);
				foreach($stat as $data){
					$h++;
					if($h > 10 AND $jm > 11)$hide="lebih";
					echo "<tr class=\"$hide\">
						<td class=\"angka\">".$data['no']."</td>
						<td>".$data['nama']."</td>
						<td class=\"angka\">".$data['jumlah']."</td>
						<td class=\"angka\">".$data['persen']."</td>";
          if($jenis_laporan == 'penduduk'){
            echo "<td class=\"angka\">".$data['laki']."</td>
            <td class=\"angka\">".$data['persen1']."</td>
            <td class=\"angka\">".$data['perempuan']."</td>
            <td class=\"angka\">".$data['persen2']."</td>";
          }
					echo "</tr>";
					$i=$i+$data['jumlah'];
					$l=$l+$data['laki']; $p=$p+$data['perempuan'];
				}
				echo "
				</tbody>
			</table>";
			if($hide=="lebih"){
				echo "
				<div style='margin-left:20px;'>
				<button class='uibutton special' id='showData'>Selengkapnya...</button>
				</div>
				";
			}

		echo "
		</div>
		</div>
	</div>";
