<div id="pageC"> 
<!-- Start of Space Admin -->
	<table class="inner">
	<tr style="vertical-align:top">
	<td class="side-menu">
		<legend>Statistik Penduduk</legend>
			<div id="sidecontent3" class="lmenu">
				<ul>		
				<a href="<?php echo site_url()?>statistik/graph/13"><li <?php if($lap==15){?>class="selected"<?php }?>>
					Umur</li></a>	
				<a href="<?php echo site_url()?>statistik/graph/0"><li <?php if($lap==0){?>class="selected"<?php }?>>
					Pendidikan Dalam KK</li></a>
				<a href="<?php echo site_url()?>statistik/graph/14"><li <?php if($lap==14){?>class="selected"<?php }?>>
					Pendidikan Sedang Ditempuh</a></li>
				<a href="<?php echo site_url()?>statistik/graph/1"><li <?php if($lap==1){?>class="selected"<?php }?>>
					Pekerjaan</li></a>
				<a href="<?php echo site_url()?>statistik/graph/2"><li <?php if($lap==2){?>class="selected"<?php }?>>
					Status Perkawinan</li></a>
				<a href="<?php echo site_url()?>statistik/graph/3"><li <?php if($lap==3){?>class="selected"<?php }?>>
					Agama</li></a>
				<a href="<?php echo site_url()?>statistik/graph/4"><li <?php if($lap==4){?>class="selected"<?php }?>>
					Jenis Kelamin</li></a>
				<a href="<?php echo site_url()?>statistik/graph/5"><li <?php if($lap==5){?>class="selected"<?php }?>>
					Warga Negara</li></a>
				<a href="<?php echo site_url()?>statistik/graph/6"><li <?php if($lap==6){?>class="selected"<?php }?>>
					Status Penduduk</li></a>
				<a href="<?php echo site_url()?>statistik/graph/7"><li <?php if($lap==7){?>class="selected"<?php }?>>
					Golongan Darah</li></a>	
				<a href="<?php echo site_url()?>statistik/graph/9"><li <?php if($lap==9){?>class="selected"<?php }?>>
					Cacat</li></a>
				</ul>
			</div>
		</td>
<td style="background:#fff;padding:0px;"> 
<script src="<?php echo base_url()?>assets/js/highcharts/highcharts.js"></script>
<script src="<?php echo base_url()?>assets/js/highcharts/highcharts-more.js"></script>
<script src="<?php echo base_url()?>assets/js/highcharts/exporting.js"></script>
<script type="text/javascript">
var chart;
$(document).ready(function() {
	chart = new Highcharts.Chart({
		chart: {
			renderTo: 'chart',
			defaultSeriesType: 'column'
		},
		title: {
			text: 'Statistik <?php echo $stat?>'
		},
		xAxis: {
			title: {
				text: '<?php echo $stat?>'
			},
		categories: [
			<?php $all = count($main); 
			$i=0;						
			foreach($main as $data){
				$i++;
				//if($all <= 12){
				//	if($data['jumlah'] != 0)
						echo "'".$data['nama']."',";
				//}else echo "'".$i."',";
			}
			?>
			]
			<?php if($all > 30){?>
			,labels: {
				rotation: -45,
				align: 'right',
				style: {
					fontSize: '9px',
					width: '80px',
					lineHeight: '8px',
				}
			}
			<?php }else{?>
			,labels: {
				style: {
					fontSize: '10px',
				}
			}
			<?php } ?>
		},
		yAxis: {
			title: {
				text: 'Jumlah Populasi'
			}
		},
		legend: {
			layout: 'vertical',
		enabled:false
		},
		plotOptions: {
			series: {
			colorByPoint: true
		},
		column: {
			pointPadding: -0.2,
			borderWidth: 1,
			shadow: 0.5,
			}
		},
		series: [{
			name: 'Populasi',
			data: [
			<?php 
			foreach($main as $data){
				if($data['nama'] != "TOTAL"){
					if($all <= 12){
						if($data['jumlah'] != 0){
							echo $data['jumlah'].",";
						}
					}else{
						if($data['jumlah'] != 0){
							echo "['".$data['nama']."',".$data['jumlah']."],";
						}
					}
				}
			}?>]
		}]
	});
});
</script>
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
				<th>Kategori Kelompok</th>
				<th>Jumlah</th>
				<?php if($lap<20){?>
				<th width="60">Laki-laki</th>
				<th width="60">Perempuan</th>
 	<?php }?>
			</tr>
		</thead>
		<tbody>
 <?php foreach($main as $data): ?>
		<tr>
 <td align="center" width="2"><?php echo $data['no']?></td>
 <td><?php echo $data['nama']?></td>
 <td><?php echo $data['jumlah']?></td>
		 <?php if($lap<20){?>
		 <td><?php echo $data['laki']?></td>
 <td><?php echo $data['perempuan']?></td>
		 <?php }?>
		 </tr>
 <?php endforeach; ?>
		</tbody>
 </table>
 </div>
</div>
</td></tr>
</table>
</div>