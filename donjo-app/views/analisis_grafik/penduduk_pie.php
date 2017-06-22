<div id="pageC">
<!-- Start of Space Admin -->
	<table class="inner">
	<tr style="vertical-align:top">
	<td class="side-menu">
		<legend>Statistik Keluarga</legend>
			<div id="" class="lmenu">
				<ul>
				<a href="<?php echo site_url()?>statistik/index/21">
				<li <?php if($lap==21){?>class="selected"<?php }?>>
					Kelas Sosial</li></a>
				</ul>
			</div>

		<legend>Statistik Penduduk</legend>
			<div  id="sidecontent3" class="lmenu">
				<ul>
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
				<a href="<?php echo site_url()?>statistik/index/10"><li <?php if($lap==10){?>class="selected"<?php }?>>
					Sakit Menahun</li></a>
				<a href="<?php echo site_url()?>statistik/index/15"><li <?php if($lap==15){?>class="selected"<?php }?>>
					Umur</li></a>
				<a href="<?php echo site_url()?>statistik/index/13"><li <?php if($lap==13){?>class="selected"<?php }?>>
					Umur (Rincian)</li></a>
				</ul>
			</div>
		</td>
<td style="background:#fff;padding:0px;">
<script type="text/javascript">
$(function () {
    var chart;

    $(document).ready(function () {

    	// Build the chart
        chart = new Highcharts.Chart({
            chart: {
                renderTo: 'chart',
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: true
            },
            title: {
						text: 'Statistik <?php echo $stat?>'
			},
            plotOptions: {
                index: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true
                    },
                    showInLegend: true
                }
            },
					legend: {
						layout: 'vertical',
						backgroundColor: '#FFFFFF',
						align: 'right',
						verticalAlign: 'top',
						x: -30,
						y: 0,
						floating: true,
						shadow: true,
                        enabled:true
					},
            series: [{
                type: 'pie',
                name: 'Populasi',
                data: [
						<?php  foreach($main as $data){?>
						  <?php if($data['nama'] != "TOTAL"){?>
							<?php if($data['jumlah'] != "-"){?>
								['<?php echo $data['nama']?>',<?php echo $data['jumlah']?>],
							<?php }?>
						<?php }?>
						<?php }?>
                ]
            }]
        });
    });

});
</script>
<script src="<?php echo base_url()?>assets/js/highcharts/highcharts.js"></script>
<script src="<?php echo base_url()?>assets/js/highcharts/highcharts-more.js"></script>
<script src="<?php echo base_url()?>assets/js/highcharts/exporting.js"></script>

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
				<th>Statistik</th>
				<th>Jumlah</th>
				<?php  if($lap<20){?>
				<th width="60">Laki-laki</th>
				<th width="60">Perempuan</th>
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
</td></tr></table>
</div>
