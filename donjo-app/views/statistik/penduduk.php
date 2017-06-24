
<div id="pageC">
<!-- Start of Space Admin -->
	<table class="inner">
	<tr style="vertical-align:top">
	<td class="side-menu">
    <?php include("donjo-app/views/statistik/laporan/side-menu.php"); ?>
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
    			<a href="<?php echo site_url("statistik/cetak/$lap")?>" class="uibutton tipsy south" title="Cetak Data" target="_blank"><span class="fa fa-print">&nbsp;</span>Cetak Data</a>
    			<a href="<?php echo site_url("statistik/excel/$lap")?>" class="uibutton tipsy south" title="Data Excel" target="_blank"><span class="fa fa-file-text">&nbsp;</span>Data Excel</a>
    			<a href="<?php echo site_url("statistik/graph/$lap")?>" class="uibutton tipsy south" title="Grafik"><span class="fa fa-bar-chart">&nbsp;</span>Grafik Data</a>
    			<a href="<?php echo site_url("statistik/pie/$lap")?>" class="uibutton tipsy south" title="Grafik"><span class="fa fa-pie-chart">&nbsp;</span>Pie Chart</a>
    			<?php  if($lap=='13'){?>
    			<a href="<?php echo site_url("statistik/rentang_umur")?>" class="uibutton tipsy south" title="Rentang Umut"><span class="fa fa-sort-numeric-asc">&nbsp;</span>Atur Rentang Umur</a><?php  }?>
        </div>
      </div>
    </div>
    <div class="ui-layout-center" id="maincontent" style="padding: 5px;">
      <div class="table-panel top">
        <div class="left">
        </div>
        <div class="right">
        </div>
        <?php if($lap < 50): ?>
    			<h4 align="center">Tabel Data Kependudukan menurut <?php echo ($stat);?></h4>
        <?php else: ?>
          <h4 align="center">Tabel Data Peserta Program <?php echo ($program['nama'])?></h4>
        <?php endif; ?>
      </div>
       <table class="list">
    		<thead>
          <tr>
            <th>No</th>

      	 		<?php  if($o==2): ?>
      				<th width="250" align="left"><a href="<?php echo site_url("statistik/index/$lap/1")?>"><?php echo $judul_kelompok ?> <span class="fa fa-sort-asc fa-sm">&nbsp;</span></a></th>
      			<?php  elseif($o==1): ?>
      				<th width="250" align="left"><a href="<?php echo site_url("statistik/index/$lap/2")?>"><?php echo $judul_kelompok ?> <span class="fa fa-sort-desc fa-sm">&nbsp;</span></a></th>
      			<?php  else: ?>
      				<th width="250" align="left"><a href="<?php echo site_url("statistik/index/$lap/1")?>"><?php echo $judul_kelompok ?> <span class="fa fa-sort fa-sm">&nbsp;</span></a></th>
      			<?php  endif; ?>

      	 		<?php  if($o==6): ?>
      				<th width="100" align="left"><a href="<?php echo site_url("statistik/index/$lap/5")?>">Jumlah  <span class="fa fa-sort-asc fa-sm">&nbsp;</span></a></th>
      			<?php  elseif($o==5): ?>
      				<th width="100" align="left"><a href="<?php echo site_url("statistik/index/$lap/6")?>">Jumlah  <span class="fa fa-sort-desc fa-sm">&nbsp;</span></a></th>
      			<?php  else: ?>
      				<th width="100" align="left"><a href="<?php echo site_url("statistik/index/$lap/5")?>">Jumlah  <span class="fa fa-sort fa-sm">&nbsp;</span></a></th>
      			<?php  endif; ?>
            <th width="5"></th>

        		<?php  if($lap<20 OR ($lap>50 AND $program['sasaran']==1)){?>
        	 		<?php  if($o==4): ?>
        				<th width="60" align="left"><a href="<?php echo site_url("statistik/index/$lap/3")?>">Laki-Laki <span class="fa fa-sort-asc fa-sm">&nbsp;</span></a></th>
        			<?php  elseif($o==3): ?>
        				<th width="60" align="left"><a href="<?php echo site_url("statistik/index/$lap/4")?>">Laki-Laki <span class="fa fa-sort-desc fa-sm">&nbsp;</span></a></th>
        			<?php  else: ?>
        				<th width="60" align="left"><a href="<?php echo site_url("statistik/index/$lap/3")?>">Laki-Laki <span class="fa fa-sort fa-sm">&nbsp;</span></a></th>
        			<?php  endif; ?>
              <th width="5"></th>

        	 		<?php  if($o==8): ?>
        				<th width="75" align="left"><a href="<?php echo site_url("statistik/index/$lap/7")?>">Perempuan <span class="fa fa-sort-asc fa-sm">&nbsp;</span></a></th>
        			<?php  elseif($o==7): ?>
        				<th width="75" align="left"><a href="<?php echo site_url("statistik/index/$lap/8")?>">Perempuan <span class="fa fa-sort-desc fa-sm">&nbsp;</span></a></th>
        			<?php  else: ?>
        				<th width="75" align="left"><a href="<?php echo site_url("statistik/index/$lap/7")?>">Perempuan <span class="fa fa-sort fa-sm">&nbsp;</span></a></th>
          		<?php  endif; ?>
              <th width="5"></th>
              <th></th>
          	<?php  }?>
    			</tr>
    		</thead>
    		<tbody>

          <?php  foreach($main as $data): ?>
            <?php if($lap>50) $tautan_jumlah = site_url("program_bantuan/detail/1/$lap"); ?>
        		<tr>
              <td align="center" width="2"><?php echo $data['no']?></td>
              <td><?php echo strtoupper($data['nama']);?></td>
        			<td align="right">
          			<?php  if($lap==21 OR $lap==22 OR $lap==23 OR $lap==24 OR $lap==25 OR $lap==26 OR $lap==27){?>
            			<a href="<?php echo site_url("keluarga/statistik/$lap/$data[id]")?>"><?php echo $data['jumlah']?></a>
          			<?php  } else { ?>
                  <?php if($lap<50) $tautan_jumlah = site_url("penduduk/statistik/$lap/$data[id]"); ?>
            			<a href="<?php echo $tautan_jumlah ?>/0"><?php echo $data['jumlah']?></a>
          			<?php }?>
        			</td>
              <td><?php echo $data['persen'];?></td>
          		<?php  if($lap<20 OR ($lap>50 AND $program['sasaran']==1)){?>
                <?php if($lap<50) $tautan_jumlah = site_url("penduduk/statistik/$lap/$data[id]"); ?>
          		  <td align="right"><a href="<?php echo $tautan_jumlah?>/1"><?php echo $data['laki']?></a></td>
                <td><?php echo $data['persen1'];?></td>
                <td align="right"><a href="<?php echo $tautan_jumlah?>/2"><?php echo $data['perempuan']?></a></td>
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