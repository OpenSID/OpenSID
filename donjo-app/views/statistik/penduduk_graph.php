<div id="pageC"> 
<!-- Start of Space Admin -->
	<table class="inner">
	<tr style="vertical-align:top">
	<td class="side-menu">		
		<legend>Statistik Keluarga</legend>
			<div id="" class="lmenu">
				<ul>
				<a href="<?=site_url()?>statistik/index/22"><li <?if($lap==22){?>class="selected"<?}?>>
					Raskin</li></a>
				<a href="<?=site_url()?>statistik/index/23"><li <?if($lap==23){?>class="selected"<?}?>>
					BLSM</li></a>
				<a href="<?=site_url()?>statistik/index/25"><li <?if($lap==25){?>class="selected"<?}?>>
					PKH</li></a>
				<a href="<?=site_url()?>statistik/index/27"><li <?if($lap==27){?>class="selected"<?}?>>
					Bedah Rumah</li></a>
				</ul>
			</div>
		
		<legend>Statistik Penduduk</legend>
			<div  id="sidecontent3" class="lmenu">
				<ul>		
				<a href="<?=site_url()?>statistik/index/15"><li <?if($lap==15){?>class="selected"<?}?>>
					Umur</li></a>	
				<a href="<?=site_url()?>statistik/index/0"><li <?if($lap==0){?>class="selected"<?}?>>
					Pendidikan Dalam KK</li></a>
				<a href="<?=site_url()?>statistik/index/14"><li <?if($lap==14){?>class="selected"<?}?>>
					Pendidikan Sedang Ditempuh</a></li>
				<a href="<?=site_url()?>statistik/index/1"><li <?if($lap==1){?>class="selected"<?}?>>
					Pekerjaan</li></a>
				<a href="<?=site_url()?>statistik/index/2"><li <?if($lap==2){?>class="selected"<?}?>>
					Status Perkawinan</li></a>
				<a href="<?=site_url()?>statistik/index/3"><li <?if($lap==3){?>class="selected"<?}?>>
					Agama</li></a>
				<a href="<?=site_url()?>statistik/index/4"><li <?if($lap==4){?>class="selected"<?}?>>
					Jenis Kelamin</li></a>
				<a href="<?=site_url()?>statistik/index/5"><li <?if($lap==5){?>class="selected"<?}?>>
					Warga Negara</li></a>
				<a href="<?=site_url()?>statistik/index/6"><li <?if($lap==6){?>class="selected"<?}?>>
					Status Penduduk</li></a>
				<a href="<?=site_url()?>statistik/index/7"><li <?if($lap==7){?>class="selected"<?}?>>
					Golongan Darah</li></a>	
				<a href="<?=site_url()?>statistik/index/9"><li <?if($lap==9){?>class="selected"<?}?>>
					Cacat</li></a>
				<?/*<a href="<?=site_url()?>statistik/index/10"><li <?if($lap==10){?>class="selected"<?}?>>
					Sakit Menahun</li></a>
				<a href="<?=site_url()?>statistik/index/13"><li <?if($lap==13){?>class="selected"<?}?>>
					Umur (Detail)</li></a>		*/?>	
				<a href="<?=site_url()?>statistik/index/11"><li <?if($lap==11){?>class="selected"<?}?>>
					Jamkesmas</li></a>	
				</ul>
			</div>
		</td>
<td style="background:#fff;padding:0px;"> 
<script src="<?=base_url()?>assets/highchart/highcharts.js"></script>
<script src="<?=base_url()?>assets/highchart/highcharts-more.js"></script>
<script src="<?=base_url()?>assets/highchart/exporting.js"></script>
<script type="text/javascript">
			var chart;
			$(document).ready(function() {
				chart = new Highcharts.Chart({
					chart: {
						renderTo: 'chart',
						defaultSeriesType: 'column'
					},
					title: {
						text: 'Statistik <?=$stat?>'
					},
					xAxis: {
						title: {
							text: '<?=$stat?>'
						},
                        categories: [
						<? $i=0;foreach($main as $data){$i++;?>
						  <?if($data['jumlah'] != "-"){echo "'$i',";}?>
						<?}?>
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
						<? foreach($main as $data){?>
						  <?if($data['nama'] != "TOTAL"){?>
						  <?if($data['jumlah'] != "-"){?>
								['<?=$data['nama']?>',<?=$data['jumlah']?>],
							<?}?>
							<?}?>
						<?}?>]
				
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
				<? if($lap<20){?>
				<th align="left" align="center" width="60">Laki-laki</th>
				<th align="left" align="center" width="60">Perempuan</th>
            	<?}?>
			</tr>
		</thead>
		<tbody>
        <? foreach($main as $data): ?>
		<tr>
          <td align="center" width="2"><?=$data['no']?></td>
          <td><?=$data['nama']?></td>
          <td><?=$data['jumlah']?></td>
		  <? if($lap<20){?>
		  <td><?=$data['laki']?></td>
          <td><?=$data['perempuan']?></td>
		  <?}?>
		  </tr>
        <? endforeach; ?>
		</tbody>
        </table>
    </div>
</div>
</td></tr>
</table>
</div>
