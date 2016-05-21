<div id="pageC"> 
<!-- Start of Space Admin -->
	<table class="inner">
	<tr style="vertical-align:top">
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
						border:0,
						defaultSeriesType: 'column'
					},
					title: {
						text: 'Statistik <?//=$stat?>'
					},
					xAxis: {
						title: {
							text: '<?//=$stat?>'
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
						border:0,
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
	<form id="mainform" name="mainform" action="" method="post">
    <div class="ui-layout-north panel top">
            <div class="left">		
                <select name="dusun" onchange="formAction('mainform','<?=site_url('analisis_grafik/dusun')?>')">
                    <option value="">Dusun</option>
					<?foreach($list_dusun AS $data){?>
                    <option value="<?=$data['dusun']?>" <?if($dusun == $data['dusun']) :?>selected<?endif?>><?=ununderscore(unpenetration($data['dusun']))?></option>
					<?}?>
                </select>
				
				<?if($dusun){?>
                <select name="rw" onchange="formAction('mainform','<?=site_url('analisis_grafik/rw')?>')">
                    <option value="">RW</option>
					<?foreach($list_rw AS $data){?>
                    <option value="<?=$data['rw']?>" <?if($rw == $data['rw']) :?>selected<?endif?>><?=$data['rw']?></option>
					<?}?>
                </select>
				<?}?>
				
				<?if($rw){?>
                <select name="rt" onchange="formAction('mainform','<?=site_url('analisis_grafik/rt')?>')">
                    <option value="">RT</option>
					<?foreach($list_rt AS $data){?>
                    <option value="<?=$data['rt']?>" <?if($rt == $data['rt']) :?>selected<?endif?>><?=$data['rt']?></option>
					<?}?>
                </select>
				<?}?>
				
            </div>
    </div>
	</form>
    <div class="ui-layout-center" id="chart" style="padding: 5px;">                
        
    </div>
    <div class="ui-layout-south panel bottom" style="max-height: 150px;overflow:auto;">
        <table class="list">
		<thead>
            <tr>
                <th>No</th>
				<th align="left" align="center">Statistik</th>
				<th align="left" align="center">Jumlah</th>
			</tr>
		</thead>
		<tbody>
        <? foreach($main as $data): ?>
		<tr>
          <td align="center" width="2"><?=$data['no']?></td>
          <td><?=$data['nama']?></td>
          <td><?=$data['jumlah']?></td>
		  </tr>
        <? endforeach; ?>
		</tbody>
        </table>
<div class="left"> 
<a href="<?=site_url()?>analisis_grafik/leave" class="uibutton icon prev">Kembali</a>
</div>
    </div>
</div>
</td></tr>
</table>
</div>
