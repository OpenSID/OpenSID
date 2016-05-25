<div id="pageC"> 
<!-- Start of Space Admin -->
	<table class="inner">
	<tr style="vertical-align:top">
	<td class="side-menu">		
		<legend>Statistik Keluarga</legend>
			<div id="" class="lmenu">
				<ul>
				<a href="<?php echo site_url()?>statistik/index/22"><li <?php if($lap==22){?>class="selected"<?php }?>>
					Raskin</li></a>
				<a href="<?php echo site_url()?>statistik/index/23"><li <?php if($lap==23){?>class="selected"<?php }?>>
					BLSM</li></a>
				<a href="<?php echo site_url()?>statistik/index/25"><li <?php if($lap==25){?>class="selected"<?php }?>>
					PKH</li></a>
				<a href="<?php echo site_url()?>statistik/index/27"><li <?php if($lap==27){?>class="selected"<?php }?>>
					Bedah Rumah</li></a>
				</ul>
			</div>
		
		<legend>Statistik Penduduk</legend>
			<div  id="sidecontent3" class="lmenu">
				<ul>		
				<a href="<?php echo site_url()?>statistik/index/15"><li <?php if($lap==15){?>class="selected"<?php }?>>
					Umur</li></a>	
				<a href="<?php echo site_url()?>statistik/index/0"><li <?php if($lap==0){?>class="selected"<?php }?>>
					Pendidikan Dalam KK</li></a>
				<a href="<?php echo site_url()?>statistik/index/14"><li <?php if($lap==14){?>class="selected"<?php }?>>
					Pendidikan Sedang Ditempuh</a></li>
				<a href="<?php echo site_url()?>statistik/index/1"><li <?php if($lap==1){?>class="selected"<?php }?>>
					Pekerjaan</li></a>
				<a href="<?php echo site_url()?>statistik/index/2"><li <?php if($lap==2){?>class="selected"<?php }?>>
					Status Perkawinan</li></a>
				<a href="<?php echo site_url()?>statistik/index/3"><li <?php if($lap==3){?>class="selected"<?php }?>>
					Agama</li></a>
				<a href="<?php echo site_url()?>statistik/index/4"><li <?php if($lap==4){?>class="selected"<?php }?>>
					Jenis Kelamin</li></a>
				<a href="<?php echo site_url()?>statistik/index/5"><li <?php if($lap==5){?>class="selected"<?php }?>>
					Warga Negara</li></a>
				<a href="<?php echo site_url()?>statistik/index/6"><li <?php if($lap==6){?>class="selected"<?php }?>>
					Status Penduduk</li></a>
				<a href="<?php echo site_url()?>statistik/index/7"><li <?php if($lap==7){?>class="selected"<?php }?>>
					Golongan Darah</li></a>	
				<a href="<?php echo site_url()?>statistik/index/9"><li <?php if($lap==9){?>class="selected"<?php }?>>
					Cacat</li></a>
				<?php /*<a href="<?php echo site_url()?>statistik/index/10"><li <?php if($lap==10){?>class="selected"<?php }?>>
					Sakit Menahun</li></a>
				<a href="<?php echo site_url()?>statistik/index/13"><li <?php if($lap==13){?>class="selected"<?php }?>>
					Umur (Detail)</li></a>		*/?>	
				<a href="<?php echo site_url()?>statistik/index/11"><li <?php if($lap==11){?>class="selected"<?php }?>>
					Jamkesmas</li></a>	
				</ul>
			</div>
		</td>
<td style="background:#fff;padding:0px;"> 
<script src="<?php echo base_url()?>assets/highchart/highcharts.js"></script>
<script src="<?php echo base_url()?>assets/highchart/highcharts-more.js"></script>
<script src="<?php echo base_url()?>assets/highchart/exporting.js"></script>
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
						<?php  $i=0;foreach($main as $data){$i++;?>
						  <?php if($data['jumlah'] != "-"){echo "'$i',";}?>
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
						border:1,
						data: [
						<?php  foreach($main as $data){?>
						  <?php if($data['nama'] != "TOTAL"){?>
						  <?php if($data['jumlah'] != "-"){?>
								['<?php echo $data['nama']?>',<?php echo $data['jumlah']?>],
							<?php }?>
							<?php }?>
						<?php }?>]
				
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
          <td><?php echo $data['nama']?></td>
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
</td></tr>
</table>
</div>
