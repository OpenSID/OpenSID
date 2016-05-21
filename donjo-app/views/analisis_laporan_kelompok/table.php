<script>
	$(function() {
		var keyword = <?=$keyword?> ;
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
    <h3>Entry Data Analisis Kelompok - <a href="<?=site_url()?>analisis_master/menu/<?=$_SESSION['analisis_master']?>"><a href="<?=site_url()?>analisis_master/menu/<?=$_SESSION['analisis_master']?>"><?=$analisis_master['nama']?></a></a> Periode : <?=$analisis_periode?></h3>
    </div>
    <div class="ui-layout-center" id="maincontent" style="padding: 5px;">
        <div class="table-panel top">
            <div class="left">
            </div>
            <div class="right">
                <input name="cari" id="cari" type="text" class="inputbox help tipped" size="40" value="<?=$cari?>" title="Cari.." onkeypress="if (event.keyCode == 13) {$('#'+'mainform').attr('action','<?=site_url('analisis_laporan_kelompok/search')?>');$('#'+'mainform').submit();}" />
                <button type="button" onclick="$('#'+'mainform').attr('action','<?=site_url('analisis_laporan_kelompok/search')?>');$('#'+'mainform').submit();" class="uibutton tipsy south"  title="Cari Data"><span class="icon-search icon-large">&nbsp;</span>Cari</button>
            </div>
        </div>
        <table class="list">
		<thead>
            <tr>
                <th width="10">No</th>
				
			<? if($o==2): ?>
				<th align="left" width='220'><a href="<?=site_url("analisis_laporan_kelompok/index/$p/1")?>">Nama Kelompok<span class="ui-icon ui-icon-triangle-1-n">&nbsp;</span></a></th>
			<? elseif($o==1): ?>
				<th align="left" width='220'><a href="<?=site_url("analisis_laporan_kelompok/index/$p/2")?>">Nama Kelompok<span class="ui-icon ui-icon-triangle-1-s">&nbsp;</span></a></th>
			<? else: ?>
				<th align="left" width='220'><a href="<?=site_url("analisis_laporan_kelompok/index/$p/1")?>">Nama Kelompok<span class="ui-icon ui-icon-triangle-2-n-s">&nbsp;</span></a></th>
			<? endif; ?>
			
	 		<? if($o==4): ?>
				<th align="left" width='150'><a href="<?=site_url("analisis_laporan_kelompok/index/$p/3")?>">Ketua Kelompok<span class="ui-icon ui-icon-triangle-1-n">&nbsp;</span></a></th>
			<? elseif($o==3): ?>
				<th align="left" width='150'><a href="<?=site_url("analisis_laporan_kelompok/index/$p/4")?>">Ketua Kelompok<span class="ui-icon ui-icon-triangle-1-s">&nbsp;</span></a></th>
			<? else: ?>
				<th align="left" width='150'><a href="<?=site_url("analisis_laporan_kelompok/index/$p/3")?>">Ketua Kelompok<span class="ui-icon ui-icon-triangle-2-n-s">&nbsp;</span></a></th>
			<? endif; ?>
			
			
				<th width='50'>Status</th>
			
	 		<? if($o==6): ?>
				<th align="left" width='50'><a href="<?=site_url("analisis_laporan_kelompok/index/$p/5")?>">Nilai<span class="ui-icon ui-icon-triangle-1-n">&nbsp;</span></a></th>
			<? elseif($o==5): ?>
				<th align="left" width='50'><a href="<?=site_url("analisis_laporan_kelompok/index/$p/6")?>">Nilai<span class="ui-icon ui-icon-triangle-1-s">&nbsp;</span></a></th>
			<? else: ?>
				<th align="left" width='50'><a href="<?=site_url("analisis_laporan_kelompok/index/$p/5")?>">Nilai<span class="ui-icon ui-icon-triangle-2-n-s">&nbsp;</span></a></th>
			<? endif; ?>
			
	 		<? if($o==6): ?>
				<th align="left" width='100'><a href="<?=site_url("analisis_laporan_kelompok/index/$p/5")?>">Klasifikasi<span class="ui-icon ui-icon-triangle-1-n">&nbsp;</span></a></th>
			<? elseif($o==5): ?>
				<th align="left" width='100'><a href="<?=site_url("analisis_laporan_kelompok/index/$p/6")?>">Klasifikasi<span class="ui-icon ui-icon-triangle-1-s">&nbsp;</span></a></th>
			<? else: ?>
				<th align="left" width='100'><a href="<?=site_url("analisis_laporan_kelompok/index/$p/5")?>">Klasifikasi<span class="ui-icon ui-icon-triangle-2-n-s">&nbsp;</span></a></th>
			<? endif; ?>
				<th width='50'>Rincian</th>
          <th></th>
			</tr>
		</thead>
		<tbody>
        <? foreach($main as $data): ?>
		<tr>
          <td align="center" width="2"><?=$data['no']?></td>
		  <td><?=$data['nama']?></td>
          <td><?=$data['ketua']?></td>
          <td align="right"><?=$data['set']?></td>
          <td align="right"><?=$data['nilai']?></td>
          <td align="right"><?=$data['klasifikasi']?></td>
          <td><div class="uibutton-group">
            <a href="<?=site_url("analisis_laporan_kelompok/kuisioner/$p/$o/$data[id]")?>" class="uibutton south"><span class="icon-list icon-large"> Rincian </span></a>
			</div>
          </td>
          <td></td>
		  </tr>
        <? endforeach; ?>
		</tbody>
        </table>
    </div>
	</form>
    <div class="ui-layout-south panel bottom">
        <div class="left"> 
          <form id="paging" action="<?=site_url('analisis_laporan_kelompok')?>" method="post">
<a href="<?=site_url()?>analisis_laporan_kelompok/leave" class="uibutton icon prev">Kembali</a>
		  <label></label>
            <select name="per_page" onchange="$('#paging').submit()" >
              <option value="20" <? selected($per_page,20); ?> >20</option>
              <option value="50" <? selected($per_page,50); ?> >50</option>
              <option value="100" <? selected($per_page,100); ?> >100</option>
            </select>
            <label>Dari</label>
            <label><?=$paging->num_rows?></label>
            <label>Total Data</label>
          </form>
        </div>
        <div class="right">
            <div class="uibutton-group">
            <? if($paging->start_link): ?>
				<a href="<?=site_url("analisis_laporan_kelompok/index/$paging->start_link/$o")?>" class="uibutton"  >Awal</a>
			<? endif; ?>
			<? if($paging->prev): ?>
				<a href="<?=site_url("analisis_laporan_kelompok/index/$paging->prev/$o")?>" class="uibutton"  >Prev</a>
			<? endif; ?>
            </div>
            <div class="uibutton-group">
                
				<? for($i=$paging->start_link;$i<=$paging->end_link;$i++): ?>
				<a href="<?=site_url("analisis_laporan_kelompok/index/$i/$o")?>" <? jecho($p,$i,"class='uibutton special'")?> class="uibutton"><?=$i?></a>
				<? endfor; ?>
            </div>
            <div class="uibutton-group">
			<? if($paging->next): ?>
				<a href="<?=site_url("analisis_laporan_kelompok/index/$paging->next/$o")?>" class="uibutton">Next</a>
			<? endif; ?>
			<? if($paging->end_link): ?>
                <a href="<?=site_url("analisis_laporan_kelompok/index/$paging->end_link/$o")?>" class="uibutton">Akhir</a>
			<? endif; ?>
            </div>
        </div>
    </div>
</div>
</td></tr></table>
</div>
