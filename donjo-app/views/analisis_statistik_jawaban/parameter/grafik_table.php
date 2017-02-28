<div id="pageD" style="height:90%"> 
 <div class="middin-north" style="padding-left: 10px;"> 
 </div>
 <div class="middin-west" style="padding: 5px;"> 
 
 <h4><?php echo $analisis_statistik_jawaban['pertanyaan']?></h4>
 <div class="top">
		<form id="mainform" name="mainform" action="" method="post">
 <div class="left">
			<select name="dusun" onchange="formAction('mainform','<?php echo site_url("analisis_statistik_jawaban/dusun3/$analisis_statistik_jawaban[id]")?>')">
					<option value="">Dusun</option>
					<?php foreach($list_dusun AS $data){?>
					<option value="<?php echo $data['dusun']?>" <?php if($dusun == $data['dusun']) :?>selected<?php endif?>><?php echo ununderscore(unpenetration($data['dusun']))?></option>
					<?php }?>
				</select>
				
				<?php if($dusun){?>
				<select name="rw" onchange="formAction('mainform','<?php echo site_url("analisis_statistik_jawaban/rw3/$analisis_statistik_jawaban[id]")?>')">
					<option value="">RW</option>
					<?php foreach($list_rw AS $data){?>
					<option value="<?php echo $data['rw']?>" <?php if($rw == $data['rw']) :?>selected<?php endif?>><?php echo $data['rw']?></option>
					<?php }?>
				</select>
				<?php }?>
				
				<?php if($rw){?>
				<select name="rt" onchange="formAction('mainform','<?php echo site_url("analisis_statistik_jawaban/rt3/$analisis_statistik_jawaban[id]")?>')">
					<option value="">RT</option>
					<?php foreach($list_rt AS $data){?>
					<option value="<?php echo $data['rt']?>" <?php if($rt == $data['rt']) :?>selected<?php endif?>><?php echo $data['rt']?></option>
					<?php }?>
				</select>
				<?php }?>
			</div>
	</form>
 </div>
 <table class="list">
		<thead>
 <tr>
 <th>No</th>
				<th>Jawaban</th>
				<th>Jumlah</th>
			</tr>
		</thead>
		<tbody>
 <?php foreach($main as $data): ?>
		<tr>
 <td align="center" width="2"><?php echo $data['no']?></td>
 <td><?php echo $data['jawaban']?></td>
 <td><?php echo $data['nilai']?></td>
		 </tr>
 <?php endforeach; ?>
		</tbody>
 </table> 
 <div style="position:absolute; bottom:20px;">
		<div class="left"> 
			<a href="<?php echo site_url()?>analisis_statistik_jawaban" class="uibutton icon prev">Kembali</a>
		</div>
 </div>
 </div>
 <div class="middin-center" style="padding: 5px;"> 
 
	<script src="<?php echo base_url()?>assets/js/highcharts/highcharts.js"></script>
	<script src="<?php echo base_url()?>assets/js/highcharts/highcharts-more.js"></script>
	<script src="<?php echo base_url()?>assets/js/highcharts/exporting.js"></script>
	<script type="text/javascript">
		$(document).ready(function() {hiRes ();});
		var chart;
		function hiRes () {
			chart = new Highcharts.Chart({
				chart: {
					renderTo: 'chart',
					border:0,
					defaultSeriesType: 'column'
				},
				title: {
					text: ''
				},
				xAxis: {
					title: {
						text:''
					},
					categories: [
					<?php $i=0;foreach($main as $data){$i++;?>
					 <?php if($data['nilai'] != "-"){echo "'$data[jawaban]',";}?>
					<?php }?>
					]
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
						pointPadding: 0,
						borderWidth: 0
					}
				},
					series: [{
					shadow:1,
					border:0,
					data: [
					<?php foreach($main as $data){?>
					 <?php if($data['jawaban'] != "TOTAL"){?>
					 <?php if($data['nilai'] != "-"){?>
							<?php echo $data['nilai']?>,
						<?php }?>
						<?php }?>
					<?php }?>]
			
				}]
			});
		};
				
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
 <div class="ui-layout-center" id="chart" style="padding: 5px;"></div>
</div>
</div>
</div>