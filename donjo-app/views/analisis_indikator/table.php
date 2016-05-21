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
    <h3>Manajemen Indikator Analisis - <a href="<?=site_url()?>analisis_master/menu/<?=$_SESSION['analisis_master']?>"><a href="<?=site_url()?>analisis_master/menu/<?=$_SESSION['analisis_master']?>"><?=$analisis_master['nama']?></a></a></h3>
        <div class="left">
            <div class="uibutton-group">
                <?if($analisis_master['lock']==1){?><a href="<?=site_url('analisis_indikator/form')?>" class="uibutton tipsy south" title="Tambah Data" ><span class="icon-plus-sign icon-large">&nbsp;</span>Tambah Indikator Baru</a>
                <button type="button" title="Hapus Data" onclick="deleteAllBox('mainform','<?=site_url("analisis_indikator/delete_all/$p/$o")?>')" class="uibutton tipsy south"><span class="icon-trash icon-large">&nbsp;</span>Hapus Data<?}?>
            </div>
        </div>
    </div>
    <div class="ui-layout-center" id="maincontent" style="padding: 5px;">
        <div class="table-panel top">
            <div class="left">
                <select name="tipe" onchange="formAction('mainform','<?=site_url('analisis_indikator/tipe')?>')">
                    <option value="">-- Filter by Tipe Indikator --</option>				
					<? foreach($list_tipe AS $data){?>
					<option value="<?=$data['id']?>" <?if($tipe == $data['id']) :?>selected<?endif?>><?=$data['tipe']?></option>
					<? }?>
                </select>
				&nbsp;
                <select name="kategori" onchange="formAction('mainform','<?=site_url('analisis_indikator/kategori')?>')">
                    <option value="">-- Filter by Kategori Indikator --</option>				
					<? foreach($list_kategori AS $data){?>
					<option value="<?=$data['id']?>" <?if($kategori == $data['id']) :?>selected<?endif?>><?=$data['kategori']?></option>
					<? }?>
                </select>
				&nbsp;
                <select name="filter" onchange="formAction('mainform','<?=site_url('analisis_indikator/filter')?>')">
                    <option value="">-- Filter by Aksi Analisis</option>
                    <option value="1" <?if($filter==1) :?>selected<?endif?>>Ya</option>
                    <option value="2" <?if($filter==2) :?>selected<?endif?>>Tidak</option>
                </select>
            </div>
            <div class="right">
                <input name="cari" id="cari" type="text" class="inputbox help tipped" size="20" value="<?=$cari?>" title="Cari.." onkeypress="if (event.keyCode == 13) {$('#'+'mainform').attr('action','<?=site_url('analisis_indikator/search')?>');$('#'+'mainform').submit();}" />
                <button type="button" onclick="$('#'+'mainform').attr('action','<?=site_url('analisis_indikator/search')?>');$('#'+'mainform').submit();" class="uibutton tipsy south"  title="Cari Data"><span class="icon-search icon-large">&nbsp;</span>Cari</button>
            </div>
        </div>
        <table class="list">
		<thead>
            <tr>
                <th width="10">No</th>
               <?if($analisis_master['lock']==1){?> <th><input type="checkbox" class="checkall"/></th>
                <th width="140">Aksi</th><?}?>
	 		<? if($o==2): ?>
				<th align="left" width="10"><a href="<?=site_url("analisis_indikator/index/$p/1")?>">Kode<span class="ui-icon ui-icon-triangle-1-n">&nbsp;</span></a></th>
			<? elseif($o==1): ?>
				<th align="left" width="10"><a href="<?=site_url("analisis_indikator/index/$p/2")?>">Kode<span class="ui-icon ui-icon-triangle-1-s">&nbsp;</span></a></th>
			<? else: ?>
				<th align="left" width="10"><a href="<?=site_url("analisis_indikator/index/$p/1")?>">Kode<span class="ui-icon ui-icon-triangle-2-n-s">&nbsp;</span></a></th>
			<? endif; ?>
			
	 		<? if($o==4): ?>
				<th align="left"><a href="<?=site_url("analisis_indikator/index/$p/3")?>">Pertanyaan<span class="ui-icon ui-icon-triangle-1-n">&nbsp;</span></a></th>
			<? elseif($o==3): ?>
				<th align="left"><a href="<?=site_url("analisis_indikator/index/$p/4")?>">Pertanyaan<span class="ui-icon ui-icon-triangle-1-s">&nbsp;</span></a></th>
			<? else: ?>
				<th align="left"><a href="<?=site_url("analisis_indikator/index/$p/3")?>">Pertanyaan<span class="ui-icon ui-icon-triangle-2-n-s">&nbsp;</span></a></th>
			<? endif; ?>
			
			<? if($o==6): ?>
				<th align="left" width='100'><a href="<?=site_url("analisis_indikator/index/$p/5")?>">Tipe Indikator<span class="ui-icon ui-icon-triangle-1-n">&nbsp;</span></a></th>
			<? elseif($o==5): ?>
				<th align="left" width='100'><a href="<?=site_url("analisis_indikator/index/$p/6")?>">Tipe Indikator<span class="ui-icon ui-icon-triangle-1-s">&nbsp;</span></a></th>
			<? else: ?>
				<th align="left" width='100'><a href="<?=site_url("analisis_indikator/index/$p/5")?>">Tipe Indikator<span class="ui-icon ui-icon-triangle-2-n-s">&nbsp;</span></a></th>
			<? endif; ?>
            
			<? if($o==6): ?>
				<th align="left" width='100'><a href="<?=site_url("analisis_indikator/index/$p/5")?>">Kategori Indikator<span class="ui-icon ui-icon-triangle-1-n">&nbsp;</span></a></th>
			<? elseif($o==5): ?>
				<th align="left" width='100'><a href="<?=site_url("analisis_indikator/index/$p/6")?>">Kategori Indikator<span class="ui-icon ui-icon-triangle-1-s">&nbsp;</span></a></th>
			<? else: ?>
				<th align="left" width='100'><a href="<?=site_url("analisis_indikator/index/$p/5")?>">Kategori Indikator<span class="ui-icon ui-icon-triangle-2-n-s">&nbsp;</span></a></th>
			<? endif; ?>
			
			<? if($o==2): ?>
				<th align="left" width='50'><a href="<?=site_url("analisis_indikator/index/$p/1")?>">Bobot<span class="ui-icon ui-icon-triangle-1-n">&nbsp;</span></a></th>
			<? elseif($o==1): ?>
				<th align="left" width='50'><a href="<?=site_url("analisis_indikator/index/$p/2")?>">Bobot<span class="ui-icon ui-icon-triangle-1-s">&nbsp;</span></a></th>
			<? else: ?>
				<th align="left" width='50'><a href="<?=site_url("analisis_indikator/index/$p/1")?>">Bobot<span class="ui-icon ui-icon-triangle-2-n-s">&nbsp;</span></a></th>
			<? endif; ?>
			
			<? if($o==2): ?>
				<th align="left" width='100'><a href="<?=site_url("analisis_indikator/index/$p/1")?>">Aksi Analisis<span class="ui-icon ui-icon-triangle-1-n">&nbsp;</span></a></th>
			<? elseif($o==1): ?>
				<th align="left" width='100'><a href="<?=site_url("analisis_indikator/index/$p/2")?>">Aksi Analisis<span class="ui-icon ui-icon-triangle-1-s">&nbsp;</span></a></th>
			<? else: ?>
				<th align="left" width='10'><a href="<?=site_url("analisis_indikator/index/$p/1")?>">Aksi Analisis<span class="ui-icon ui-icon-triangle-2-n-s">&nbsp;</span></a></th>
			<? endif; ?>
			</tr>
		</thead>
		<tbody>
        <? foreach($main as $data): ?>
		<tr>
          <td align="center" width="2"><?=$data['no']?></td>
			<?if($analisis_master['lock']==1){?>
			<td align="center" width="5">
				<input type="checkbox" name="id_cb[]" value="<?=$data['id']?>" />
			</td>
          <td><div class="uibutton-group">
            <?if($data['id_tipe']==1 OR $data['id_tipe']==2){?><a href="<?=site_url("analisis_indikator/parameter/$data[id]")?>" class="uibutton"><span class="icon-list icon-large"> Parameter</span></a><?}?><a href="<?=site_url("analisis_indikator/form/$p/$o/$data[id]")?>" class="uibutton tipsy south" title="Ubah Data"><span class="icon-edit icon-large"> Ubah </span></a><a href="<?=site_url("analisis_indikator/delete/$p/$o/$data[id]")?>" class="uibutton tipsy south" title="Hapus Data" target="confirm" message="Apakah Anda Yakin?" header="Hapus Data"><span class="icon-trash icon-large"></span></a>
			</div>
          </td>
		  <?}?>
          <td><label><?=$data['nomor']?></label></td>
          <td><?=$data['pertanyaan']?></td>
		  <td><?=$data['tipe_indikator']?></td>
          <td><?=$data['kategori']?></td>
          <td><?=$data['bobot']?></td>
          <td><?=$data['act_analisis']?></td>
		  </tr>
        <? endforeach; ?>
		</tbody>
        </table>
    </div>
	</form>
    <div class="ui-layout-south panel bottom">
        <div class="left"> 
          <form id="paging" action="<?=site_url('analisis_indikator')?>" method="post">
<a href="<?=site_url()?>analisis_indikator/leave" class="uibutton icon prev">Kembali</a>
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
				<a href="<?=site_url("analisis_indikator/index/$paging->start_link/$o")?>" class="uibutton"  >Awal</a>
			<? endif; ?>
			<? if($paging->prev): ?>
				<a href="<?=site_url("analisis_indikator/index/$paging->prev/$o")?>" class="uibutton"  >Prev</a>
			<? endif; ?>
            </div>
            <div class="uibutton-group">
                
				<? for($i=$paging->start_link;$i<=$paging->end_link;$i++): ?>
				<a href="<?=site_url("analisis_indikator/index/$i/$o")?>" <? jecho($p,$i,"class='uibutton special'")?> class="uibutton"><?=$i?></a>
				<? endfor; ?>
            </div>
            <div class="uibutton-group">
			<? if($paging->next): ?>
				<a href="<?=site_url("analisis_indikator/index/$paging->next/$o")?>" class="uibutton">Next</a>
			<? endif; ?>
			<? if($paging->end_link): ?>
                <a href="<?=site_url("analisis_indikator/index/$paging->end_link/$o")?>" class="uibutton">Akhir</a>
			<? endif; ?>
            </div>
        </div>
    </div>
</div>
</td></tr></table>
</div>
