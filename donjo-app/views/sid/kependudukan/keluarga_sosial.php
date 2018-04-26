
<!-- Start of Space Admin -->
	<table class="inner">
	<tr style="vertical-align:top">

	
<td style="background:#fff;padding:0px;"> 
<script type="text/javascript" src="<?php echo base_url()?>assets/js/highcharts/highcharts.js"></script>
<script type="text/javascript">
			var chart;
			$(document).ready(function() {
				chart = new Highcharts.Chart({
					chart: {
						renderTo: 'chart',
						defaultSeriesType: 'column'
					},
					title: {
						text: 'Statistik Kelas Sosial'
					},
					xAxis: {
						title: {
							text: 'Kelas Sosial'
						},
                        categories: [
						<?php  $i=0;foreach($main as $data){$i++;?>
						  <?php echo "'$data[nama]',";?>
						<?php }?>
						]
					},
					yAxis: {
						title: {
							text: 'Populasi'
						}
					},
					legend: {
						layout: 'vertical',
						backgroundColor: '#FFFFFF',
						align: 'left',
						verticalAlign: 'top',
						x: 100,
						y: 70,
						floating: true,
						shadow: true,
                        enabled:false
					},
					tooltip: {
						formatter: function() {
							return ''+
								this.x +': '+ this.y +'';
						}
					},
					plotOptions: {
						series: {
                            colorByPoint: true
                        },
                        column: {
							pointPadding: 0.2,
							borderWidth: 0
						}
					},
				        series: [{
						name: 'Populasi',
						data: [
						<?php  foreach($main as $data){?>
						  <?php echo $data['jumlah'].",";?>
						<?php }?>]
				
					}]
				});
				
				
			});
				
</script>
<div class="content-header">
    <h3>Data Keluarga</h3>
</div>
<div id="contentpane">    
	<form id="mainform" name="mainform" action="" method="post">
    <div class="ui-layout-north panel">
        <div class="left">
            <div class="uibutton-group">
				<select name="dusun" onchange="formAction('mainform','<?php echo site_url('keluarga/dusun/1')?>')">
                    <option value="">Dusun</option>
					<?php foreach($list_dusun AS $data){?>
                    <option value="<?php echo $data['dusun']?>" <?php if($dusun == $data['dusun']) :?>selected<?php endif?>><?php echo $data['dusun']?></option>
					<?php }?>
                </select>
				
				<?php if($dusun){?>
                <select name="rw" onchange="formAction('mainform','<?php echo site_url('keluarga/rw/1')?>')">
                    <option value="">RW</option>
					<?php foreach($list_rw AS $data){?>
                    <option value="<?php echo $data['rw']?>" <?php if($rw == $data['rw']) :?>selected<?php endif?>><?php echo $data['rw']?></option>
					<?php }?>
                </select>
				<?php }?>
				
				<?php if($rw){?>
                <select name="rt" onchange="formAction('mainform','<?php echo site_url('keluarga/rt/1')?>')">
                    <option value="">RT</option>
					<?php foreach($list_rt AS $data){?>
                    <option value="<?php echo $data['rt']?>" <?php if($rt == $data['rt']) :?>selected<?php endif?>><?php echo $data['rt']?></option>
					<?php }?>
                </select>
				<?php }?>
				
            </div>
        </div>
        <div class="right">
            <div class="uibutton-group">
<a href="<?php echo site_url()?>keluarga/clear" class="uibutton icon prev">Kembali</a>
            </div>
        </div>
    </div>
    <div class="ui-layout-center" id="chart" style="padding: 5px;">                
        
    </div>
    <div class="ui-layout-south panel bottom" style="max-height: 150px;overflow:auto;">
        <table class="list">
		<thead>
            <tr>
                <th>No</th>
				<th align="left" align="center">Statistik</th>
				<th align="left" align="center">Populasi</th>
            
			</tr>
		</thead>
		<tbody>
        <?php  foreach($main as $data): ?>
		<tr>
          <td align="center" width="2"><?php echo $data['id']?></td>
          <td><?php echo $data['nama']?></td>
          <td><?php echo $data['jumlah']?></td>
		  </tr>
        <?php  endforeach; ?>
		</tbody>
        </table>
    </div>
	</form>
</div>
</td></tr></table>
</div>
