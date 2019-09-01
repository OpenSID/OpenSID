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
								['<?= $data['nama']?>',<?= $data['jumlah']?>],
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
								['<?= $data['nama']?>',<?= $data['jumlah']?>],
							<?php }?>
						<?php }?>
                ]
            }]
        });
    });

});
</script>
<?php }?>
<script src="<?= base_url()?>assets/js/highcharts/highcharts.js"></script>
<script src="<?= base_url()?>assets/js/highcharts/highcharts-more.js"></script>
<script src="<?= base_url()?>assets/js/highcharts/exporting.js"></script>
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
	<div class=\"block block-themed\">
	<div class=\"block-header block-header-default\">
			<h2 class=\"h4 font-w400 block-title\"> <i class=\"si si-bar-chart\"></i> Grafik Data Demografi Berdasar ". $heading."</h2>
		</div>
		<div class=\"block-content\">
			<div class=\"box-tools pull-right\">
				<div class=\"btn-group-xs\">";
					$strC = ($tipe==1)? "bbtn btn-rounded btn-noborder btn-danger min-width-125 mb-10":"btn btn-rounded btn-noborder btn-secondary min-width-125 mb-10";
					echo "<a href=\"".site_url("first/statistik/$st/1")."\" class=\"btn ".$strC." btn-xs\">Bar Graph</a>";
					$strC = ($tipe==0)? "btn btn-rounded btn-noborder btn-danger min-width-125 mb-10":"btn btn-rounded btn-noborder btn-secondary min-width-125 mb-10";
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

	<div class=\"block block-themed\">
		<div class=\"block-header block-header-default\">
			<h2 class=\"h4 font-w400 block-title\">Tabel ". $heading."</h2>
		</div>
		<div class=\"block-content\" data-toggle=\"slimscroll\"  data-height=\"500px\"  data-color=\"#ef5350\"  data-opacity=\"1\"  data-always-visible=\"true\" >
			<div class=\"table-responsive\">
			<table class=\"table table-striped\">
				<thead>
				<tr>
					<th rowspan=\"2\" style='text-align:center'>No</th>
					<th rowspan=\"2\" style='text-align:center;'>Kelompok</th>
					<th colspan=\"2\" style='text-align:center'>Jumlah</th>";
					if($jenis_laporan == 'penduduk'){
						echo "<th colspan=\"2\" style='text-align:center'>Laki-laki</th>
						<th colspan=\"2\" style='text-align:center'>Perempuan</th>";
          			}
					echo "
        		</tr>
				<tr>
					<th style='text-align:center'>n</th><th style='text-align:center'>%</th>";
          if($jenis_laporan == 'penduduk'){
  					echo "<th style='text-align:center'>n</th><th style='text-align:center'>%</th>
  					<th style='text-align:center'>n</th><th style='text-align:center'>%</th>";
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
						<td class=\"angka\" style='text-align:center'>".$data['no']."</td>
						<td>".$data['nama']."</td>
						<td class=\"angka\" style='text-align:center'>".$data['jumlah']."</td>
						<td class=\"angka\" style='text-align:center'>".$data['persen']."</td>";
          if($jenis_laporan == 'penduduk'){
            echo "<td class=\"angka\" style='text-align:center'>".$data['laki']."</td>
            <td class=\"angka\" style='text-align:center'>".$data['persen1']."</td>
            <td class=\"angka\" style='text-align:center'>".$data['perempuan']."</td>
            <td class=\"angka\" style='text-align:center'>".$data['persen2']."</td>";
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
				<button class='btn btn-rounded btn-noborder btn-success min-width-125 mb-10' id='showData'>Selengkapnya...</button>
				</div>
				";
			}

		echo "
		</div>
		</div>
	</div>";
