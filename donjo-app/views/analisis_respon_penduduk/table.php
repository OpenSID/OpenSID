<script>
	$(function() {
		var keyword = <?php echo $keyword?> ;
		$( "#cari" ).autocomplete({
			source: keyword
		});
	});
</script>

<div id="pageC">
	<table class="inner">
<tr style="vertical-align:top">
		<td style="background:#fff;padding:0px;"> 
<div class="content-header">
</div>
<div id="contentpane">    
	<form id="mainform" name="mainform" action="" method="post">
    <div class="ui-layout-north panel">
    <h3>Entry Data Analisis Penduduk - <a href="<?php echo site_url()?>analisis_master/menu/<?php echo $_SESSION['analisis_master']?>"><a href="<?php echo site_url()?>analisis_master/menu/<?php echo $_SESSION['analisis_master']?>"><?php echo $analisis_master['nama']?></a></a> Periode : <?php echo $analisis_periode?></h3>
    </div>
    <div class="ui-layout-center" id="maincontent" style="padding: 5px;">
        <div class="table-panel top">
            <div class="left">
            </div>
            <div class="right">
                <input name="cari" id="cari" type="text" class="inputbox help tipped" size="40" value="<?php echo $cari?>" title="Cari.." onkeypress="if (event.keyCode == 13) {$('#'+'mainform').attr('action','<?php echo site_url('analisis_respon_penduduk/search')?>');$('#'+'mainform').submit();}" />
                <button type="button" onclick="$('#'+'mainform').attr('action','<?php echo site_url('analisis_respon_penduduk/search')?>');$('#'+'mainform').submit();" class="uibutton tipsy south"  title="Cari Data"><span class="icon-search icon-large">&nbsp;</span>Cari</button>
            </div>
        </div>
        <table class="list">
		<thead>
            <tr>
                <th width="10">No</th>
				
			<?php  if($o==2): ?>
				<th align="left" width='120'><a href="<?php echo site_url("analisis_respon_penduduk/index/$p/1")?>">NIK<span class="ui-icon ui-icon-triangle-1-n">&nbsp;</span></a></th>
			<?php  elseif($o==1): ?>
				<th align="left" width='120'><a href="<?php echo site_url("analisis_respon_penduduk/index/$p/2")?>">NIK<span class="ui-icon ui-icon-triangle-1-s">&nbsp;</span></a></th>
			<?php  else: ?>
				<th align="left" width='120'><a href="<?php echo site_url("analisis_respon_penduduk/index/$p/1")?>">NIK<span class="ui-icon ui-icon-triangle-2-n-s">&nbsp;</span></a></th>
			<?php  endif; ?>
			
	 		<?php  if($o==4): ?>
				<th align="left" width='250'><a href="<?php echo site_url("analisis_respon_penduduk/index/$p/3")?>">Nama<span class="ui-icon ui-icon-triangle-1-n">&nbsp;</span></a></th>
			<?php  elseif($o==3): ?>
				<th align="left" width='250'><a href="<?php echo site_url("analisis_respon_penduduk/index/$p/4")?>">Nama<span class="ui-icon ui-icon-triangle-1-s">&nbsp;</span></a></th>
			<?php  else: ?>
				<th align="left" width='250'><a href="<?php echo site_url("analisis_respon_penduduk/index/$p/3")?>">Nama<span class="ui-icon ui-icon-triangle-2-n-s">&nbsp;</span></a></th>
			<?php  endif; ?>
			
				<th width='50'>Aksi</th>
				<th width='50'>Status</th>
			
          <th></th>
			</tr>
		</thead>
		<tbody>
        <?php  foreach($main as $data): ?>
		<tr>
          <td align="center" width="2"><?php echo $data['no']?></td>
		  <td><?php echo $data['nik']?></td>
          <td><?php echo $data['nama']?></td>
          <td><div class="uibutton-group">
            <a href="<?php echo site_url("analisis_respon_penduduk/kuisioner/$p/$o/$data[id]")?>" class="uibutton south"><span class="icon-list icon-large"> Input Data </span></a>
			</div>
          </td>
          <td align="right"><?php echo $data['set']?></td>
          <td></td>
		  </tr>
        <?php  endforeach; ?>
		</tbody>
        </table>
    </div>
	</form>
    <div class="ui-layout-south panel bottom">
        <div class="left"> 
          <form id="paging" action="<?php echo site_url('analisis_respon_penduduk')?>" method="post">
<a href="<?php echo site_url()?>analisis_respon_penduduk/leave" class="uibutton icon prev">Kembali</a>
		  <label></label>
            <select name="per_page" onchange="$('#paging').submit()" >
              <option value="20" <?php  selected($per_page,20); ?> >20</option>
              <option value="50" <?php  selected($per_page,50); ?> >50</option>
              <option value="100" <?php  selected($per_page,100); ?> >100</option>
            </select>
            <label>Dari</label>
            <label><?php echo $paging->num_rows?></label>
            <label>Total Data</label>
          </form>
        </div>
        <div class="right">
            <div class="uibutton-group">
            <?php  if($paging->start_link): ?>
				<a href="<?php echo site_url("analisis_respon_penduduk/index/$paging->start_link/$o")?>" class="uibutton"  >Awal</a>
			<?php  endif; ?>
			<?php  if($paging->prev): ?>
				<a href="<?php echo site_url("analisis_respon_penduduk/index/$paging->prev/$o")?>" class="uibutton"  >Prev</a>
			<?php  endif; ?>
            </div>
            <div class="uibutton-group">
                
				<?php  for($i=$paging->start_link;$i<=$paging->end_link;$i++): ?>
				<a href="<?php echo site_url("analisis_respon_penduduk/index/$i/$o")?>" <?php  jecho($p,$i,"class='uibutton special'")?> class="uibutton"><?php echo $i?></a>
				<?php  endfor; ?>
            </div>
            <div class="uibutton-group">
			<?php  if($paging->next): ?>
				<a href="<?php echo site_url("analisis_respon_penduduk/index/$paging->next/$o")?>" class="uibutton">Next</a>
			<?php  endif; ?>
			<?php  if($paging->end_link): ?>
                <a href="<?php echo site_url("analisis_respon_penduduk/index/$paging->end_link/$o")?>" class="uibutton">Akhir</a>
			<?php  endif; ?>
            </div>
        </div>
    </div>
</div>
</td></tr></table>
</div>
