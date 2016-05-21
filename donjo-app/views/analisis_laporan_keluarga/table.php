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
    <h3>Entry Data Analisis Keluarga - <a href="<?=site_url()?>analisis_master/menu/<?=$_SESSION['analisis_master']?>"><a href="<?=site_url()?>analisis_master/menu/<?=$_SESSION['analisis_master']?>"><?=$analisis_master['nama']?></a></a> Periode : <?=$analisis_periode?></h3>
    </div>
    <div class="ui-layout-center" id="maincontent" style="padding: 5px;">
        <div class="table-panel top">
            <div class="left">		
                <select name="klasifikasi" onchange="formAction('mainform','<?=site_url('analisis_laporan_keluarga/klasifikasi')?>')">
                    <option value="">Klasifikasi</option>
					<?foreach($list_klasifikasi AS $data){?>
                    <option value="<?=$data['id']?>" <?if($klasifikasi == $data['id']) :?>selected<?endif?>><?=$data['nama']?></option>
					<?}?>
                </select>
				
                <select name="dusun" onchange="formAction('mainform','<?=site_url('analisis_laporan_keluarga/dusun')?>')">
                    <option value="">Dusun</option>
					<?foreach($list_dusun AS $data){?>
                    <option value="<?=$data['dusun']?>" <?if($dusun == $data['dusun']) :?>selected<?endif?>><?=ununderscore(unpenetration($data['dusun']))?></option>
					<?}?>
                </select>
				
				<?if($dusun){?>
                <select name="rw" onchange="formAction('mainform','<?=site_url('analisis_laporan_keluarga/rw')?>')">
                    <option value="">RW</option>
					<?foreach($list_rw AS $data){?>
                    <option value="<?=$data['rw']?>" <?if($rw == $data['rw']) :?>selected<?endif?>><?=$data['rw']?></option>
					<?}?>
                </select>
				<?}?>
				
				<?if($rw){?>
                <select name="rt" onchange="formAction('mainform','<?=site_url('analisis_laporan_keluarga/rt')?>')">
                    <option value="">RT</option>
					<?foreach($list_rt AS $data){?>
                    <option value="<?=$data['rt']?>" <?if($rt == $data['rt']) :?>selected<?endif?>><?=$data['rt']?></option>
					<?}?>
                </select>
				<?}?>
				
            </div>
            <div class="right">
                <input name="cari" id="cari" type="text" class="inputbox help tipped" size="40" value="<?=$cari?>" title="Cari.." onkeypress="if (event.keyCode == 13) {$('#'+'mainform').attr('action','<?=site_url('analisis_laporan_keluarga/search')?>');$('#'+'mainform').submit();}" />
                <button type="button" onclick="$('#'+'mainform').attr('action','<?=site_url('analisis_laporan_keluarga/search')?>');$('#'+'mainform').submit();" class="uibutton tipsy south"  title="Cari Data"><span class="icon-search icon-large">&nbsp;</span>Cari</button>
            </div>
        </div>
        <table class="list">
		<thead>
            <tr>
                <th width="10">No</th>
				
			<? if($o==2): ?>
				<th align="left" width='120'><a href="<?=site_url("analisis_laporan_keluarga/index/$p/1")?>">Nomor KK<span class="ui-icon ui-icon-triangle-1-n">&nbsp;</span></a></th>
			<? elseif($o==1): ?>
				<th align="left" width='120'><a href="<?=site_url("analisis_laporan_keluarga/index/$p/2")?>">Nomor KK<span class="ui-icon ui-icon-triangle-1-s">&nbsp;</span></a></th>
			<? else: ?>
				<th align="left" width='120'><a href="<?=site_url("analisis_laporan_keluarga/index/$p/1")?>">Nomor KK<span class="ui-icon ui-icon-triangle-2-n-s">&nbsp;</span></a></th>
			<? endif; ?>
			
	 		<? if($o==4): ?>
				<th align="left" width='250'><a href="<?=site_url("analisis_laporan_keluarga/index/$p/3")?>">Kepala Keluarga<span class="ui-icon ui-icon-triangle-1-n">&nbsp;</span></a></th>
			<? elseif($o==3): ?>
				<th align="left" width='250'><a href="<?=site_url("analisis_laporan_keluarga/index/$p/4")?>">Kepala Keluarga<span class="ui-icon ui-icon-triangle-1-s">&nbsp;</span></a></th>
			<? else: ?>
				<th align="left" width='250'><a href="<?=site_url("analisis_laporan_keluarga/index/$p/3")?>">Kepala Keluarga<span class="ui-icon ui-icon-triangle-2-n-s">&nbsp;</span></a></th>
			<? endif; ?>
			
			
				<th width='50'>Status</th>
			
	 		<? if($o==6): ?>
				<th align="left" width='50'><a href="<?=site_url("analisis_laporan_keluarga/index/$p/5")?>">Nilai<span class="ui-icon ui-icon-triangle-1-n">&nbsp;</span></a></th>
			<? elseif($o==5): ?>
				<th align="left" width='50'><a href="<?=site_url("analisis_laporan_keluarga/index/$p/6")?>">Nilai<span class="ui-icon ui-icon-triangle-1-s">&nbsp;</span></a></th>
			<? else: ?>
				<th align="left" width='50'><a href="<?=site_url("analisis_laporan_keluarga/index/$p/5")?>">Nilai<span class="ui-icon ui-icon-triangle-2-n-s">&nbsp;</span></a></th>
			<? endif; ?>
			
	 		<? if($o==6): ?>
				<th align="left" width='100'><a href="<?=site_url("analisis_laporan_keluarga/index/$p/5")?>">Klasifikasi<span class="ui-icon ui-icon-triangle-1-n">&nbsp;</span></a></th>
			<? elseif($o==5): ?>
				<th align="left" width='100'><a href="<?=site_url("analisis_laporan_keluarga/index/$p/6")?>">Klasifikasi<span class="ui-icon ui-icon-triangle-1-s">&nbsp;</span></a></th>
			<? else: ?>
				<th align="left" width='100'><a href="<?=site_url("analisis_laporan_keluarga/index/$p/5")?>">Klasifikasi<span class="ui-icon ui-icon-triangle-2-n-s">&nbsp;</span></a></th>
			<? endif; ?>
				<th width='50'>Rincian</th>
          <th></th>
			</tr>
		</thead>
		<tbody>
        <? foreach($main as $data): ?>
		<tr>
          <td align="center" width="2"><?=$data['no']?></td>
		  <td><?=$data['no_kk']?></td>
          <td><?=$data['nama']?></td>
          <td align="right"><?=$data['set']?></td>
          <td align="right"><?=$data['nilai']?></td>
          <td align="right"><?=$data['klasifikasi']?></td>
          <td><div class="uibutton-group">
            <a href="<?=site_url("analisis_laporan_keluarga/kuisioner/$p/$o/$data[id]")?>" class="uibutton south"><span class="icon-list icon-large"> Rincian </span></a>
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
          <form id="paging" action="<?=site_url('analisis_laporan_keluarga')?>" method="post">
<a href="<?=site_url()?>analisis_laporan_keluarga/leave" class="uibutton icon prev">Kembali</a>
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
				<a href="<?=site_url("analisis_laporan_keluarga/index/$paging->start_link/$o")?>" class="uibutton"  >Awal</a>
			<? endif; ?>
			<? if($paging->prev): ?>
				<a href="<?=site_url("analisis_laporan_keluarga/index/$paging->prev/$o")?>" class="uibutton"  >Prev</a>
			<? endif; ?>
            </div>
            <div class="uibutton-group">
                
				<? for($i=$paging->start_link;$i<=$paging->end_link;$i++): ?>
				<a href="<?=site_url("analisis_laporan_keluarga/index/$i/$o")?>" <? jecho($p,$i,"class='uibutton special'")?> class="uibutton"><?=$i?></a>
				<? endfor; ?>
            </div>
            <div class="uibutton-group">
			<? if($paging->next): ?>
				<a href="<?=site_url("analisis_laporan_keluarga/index/$paging->next/$o")?>" class="uibutton">Next</a>
			<? endif; ?>
			<? if($paging->end_link): ?>
                <a href="<?=site_url("analisis_laporan_keluarga/index/$paging->end_link/$o")?>" class="uibutton">Akhir</a>
			<? endif; ?>
            </div>
        </div>
    </div>
</div>
</td></tr></table>
</div>
