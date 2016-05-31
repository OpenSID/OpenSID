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
		<fieldset>
		<legend>Statistik Penduduk</legend>
			<div id="sidecontent2" class="lmenu">
				<ul>		
				<a href="<?php echo site_url()?>statistik/index/13"><li <?php if($lap==13){?>class="selected"<?php }?>>
					Umur</li></a>	
				<a href="<?php echo site_url()?>statistik/index/0"><li <?php if($lap==0){?>class="selected"<?php }?>>
					Pendidikan dalam KK</li></a>
				<a href="<?php echo site_url()?>statistik/index/14"><li <?php if($lap==14){?>class="selected"<?php }?>>
					Pendidikan sedang Ditempuh</a></li>
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
					Penyandang Cacat</li></a>
				<?php /*<a href="<?php echo site_url()?>statistik/index/10"><li <?php if($lap==10){?>class="selected"<?php }?>>
					Sakit Menahun</li></a>	*/?>	
				<a href="<?php echo site_url()?>statistik/index/11"><li <?php if($lap==11){?>class="selected"<?php }?>>
					Penerima Jamkesmas</li></a>	
				<!--<a href="<?php echo site_url()?>statistik/index/15"><li <?php if($lap==15){?>class="selected"<?php }?>>
					Umur</li></a>	-->
				</ul>
			</div>
		</fieldset>
	</td>
<td style="background:#fff;padding:0px;"> 
<div class="content-header">
    <h3>Statistik</h3>
</div>
<div id="contentpane" style="overflow:auto;">    
	<form id="mainform" name="mainform" action="" method="post">
    <div class="ui-layout-north panel top">
        <div class="left">
            <div class="uibutton-group">
			<a href="<?php echo site_url("statistik/cetak/$lap")?>" class="uibutton tipsy south" title="Cetak Data" target="_blank"><span class="icon-print icon-large">&nbsp;</span>Cetak Data</a>
			<a href="<?php echo site_url("statistik/excel/$lap")?>" class="uibutton tipsy south" title="Data Excel" target="_blank"><span class="icon-file-text icon-large">&nbsp;</span>Data Excel</a>
			<a href="<?php echo site_url("statistik/graph/$lap")?>" class="uibutton tipsy south" title="Grafik"><span class="icon-bar-chart icon-large">&nbsp;</span>Grafik Data</a>
			<a href="<?php echo site_url("statistik/pie/$lap")?>" class="uibutton tipsy south" title="Grafik"><span class="icon-time icon-large">&nbsp;</span>Pie Chart</a>
			<?php  if($lap=='13'){?>
			<a href="<?php echo site_url("statistik/rentang_umur")?>" class="uibutton tipsy south" title="Rentang Umut"><span class="icon-resize-horizontal icon-large">&nbsp;</span>Atur Rentang Umur</a><?php  }?>
            </div>
        </div>
    </div>
    <div class="ui-layout-center" id="maincontent" style="padding: 5px;">
        <div class="table-panel top">
            <div class="left">
            </div>
            <div class="right">
            </div>
			<h4 align="center">Tabel Data Kependudukan menurut <?php echo ($stat);?></h4>
         </div>
       <table class="list">
		<thead>
            <tr>
                <th>No</th>
				
	 		<?php  if($o==2): ?>
				<th width="250" align="left"><a href="<?php echo site_url("statistik/index/$lap/1")?>">Jenis Kelompok<span class="ui-icon ui-icon-triangle-1-n">&nbsp;</span></a></th>
			<?php  elseif($o==1): ?>
				<th width="250" align="left"><a href="<?php echo site_url("statistik/index/$lap/2")?>">Jenis Kelompok<span class="ui-icon ui-icon-triangle-1-s">&nbsp;</span></a></th>
			<?php  else: ?>
				<th width="250" align="left"><a href="<?php echo site_url("statistik/index/$lap/1")?>">Jenis Kelompok<span class="ui-icon ui-icon-triangle-2-n-s">&nbsp;</span></a></th>
			<?php  endif; ?>
			
	 		<?php  if($o==6): ?>
				<th width="100" align="left"><a href="<?php echo site_url("statistik/index/$lap/5")?>">Jumlah <span class="ui-icon ui-icon-triangle-1-n">&nbsp;</span></a></th>
			<?php  elseif($o==5): ?>
				<th width="100" align="left"><a href="<?php echo site_url("statistik/index/$lap/6")?>">Jumlah <span class="ui-icon ui-icon-triangle-1-s">&nbsp;</span></a></th>
			<?php  else: ?>
				<th width="100" align="left"><a href="<?php echo site_url("statistik/index/$lap/5")?>">Jumlah <span class="ui-icon ui-icon-triangle-2-n-s">&nbsp;</span></a></th>
			<?php  endif; ?>
                <th width="5"></th>
				
		<?php  if($lap<20){?>
	 		<?php  if($o==4): ?>
				<th width="50" align="left"><a href="<?php echo site_url("statistik/index/$lap/3")?>">Laki-Laki<span class="ui-icon ui-icon-triangle-1-n">&nbsp;</span></a></th>
			<?php  elseif($o==3): ?>
				<th width="50" align="left"><a href="<?php echo site_url("statistik/index/$lap/4")?>">Laki-Laki<span class="ui-icon ui-icon-triangle-1-s">&nbsp;</span></a></th>
			<?php  else: ?>
				<th width="50" align="left"><a href="<?php echo site_url("statistik/index/$lap/3")?>">Laki-Laki<span class="ui-icon ui-icon-triangle-2-n-s">&nbsp;</span></a></th>
			<?php  endif; ?>
                <th width="5"></th>
						
	 		<?php  if($o==8): ?>
				<th width="50" align="left"><a href="<?php echo site_url("statistik/index/$lap/7")?>">Perempuan<span class="ui-icon ui-icon-triangle-1-n">&nbsp;</span></a></th>
			<?php  elseif($o==7): ?>
				<th width="50" align="left"><a href="<?php echo site_url("statistik/index/$lap/8")?>">Perempuan<span class="ui-icon ui-icon-triangle-1-s">&nbsp;</span></a></th>
			<?php  else: ?>
				<th width="50" align="left"><a href="<?php echo site_url("statistik/index/$lap/7")?>">Perempuan<span class="ui-icon ui-icon-triangle-2-n-s">&nbsp;</span></a></th>
		<?php  endif; ?>
                <th width="5"></th>
			
                <th></th>
            	<?php  }?>
			</tr>
		</thead>
		<tbody>

        <?php  foreach($main as $data): ?>
		<tr>
            <td align="center" width="2"><?php echo $data['no']?></td>
            <td><?php echo $data['nama'];?></td>
			<td align="right">
			<?php  if($lap==21 OR $lap==22 OR $lap==23 OR $lap==24 OR $lap==25 OR $lap==26 OR $lap==27){?>
			<a href="<?php echo site_url("keluarga/statistik/$lap/$data[id]")?>"><?php echo $data['jumlah']?></a>
			<?php  } else { ?>
			<a href="<?php echo site_url("penduduk/statistik/$lap/$data[id]")?>/0"><?php echo $data['jumlah']?></a>
			<?php }?>
			</td>
            <td><?php echo $data['persen'];?></td>
		<?php  if($lap<20){?>
		  <td align="right"><a href="<?php echo site_url("penduduk/statistik/$lap/$data[id]")?>/1"><?php echo $data['laki']?></a></td>
            <td><?php echo $data['persen1'];?></td>
          <td align="right"><a href="<?php echo site_url("penduduk/statistik/$lap/$data[id]")?>/2"><?php echo $data['perempuan']?></a></td>
            <td><?php echo $data['persen2'];?></td>
		<?php  }?>
            <td></td>
		  </tr>
        <?php  endforeach; ?>

		</tbody>
        </table>
    </div>
	</form>
    <div class="ui-layout-south panel bottom">
    </div>
</div>
</td></tr></table>
</div>