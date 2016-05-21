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
    <h3>Manajemen Dokumen</h3>
</div>
<div id="contentpane">    
	<form id="mainform" name="mainform" action="" method="post">
    <div class="ui-layout-north panel">
        <div class="left">
            <div class="uibutton-group">
                <a href="<?=site_url('dokumen/form')?>" class="uibutton tipsy south" title="Tambah Data" ><span class="icon-plus-sign icon-large">&nbsp;</span>Tambah Dokumen Baru</a>
                <button type="button" title="Hapus Data" onclick="deleteAllBox('mainform','<?=site_url("dokumen/delete_all/$p/$o")?>')" class="uibutton tipsy south"><span class="icon-trash icon-large">&nbsp;</span>Hapus Data
            </div>
        </div>
    </div>
    <div class="ui-layout-center" id="maincontent" style="padding: 5px;">
        <div class="table-panel top">
            <div class="left">
                <select name="filter" onchange="formAction('mainform','<?=site_url('dokumen/filter')?>')">
                    <option value="">Semua</option>
                    <option value="1" <?if($filter==1) :?>selected<?endif?>>Enabled</option>
                    <option value="2" <?if($filter==2) :?>selected<?endif?>>Disabled</option>
                </select>
            </div>
            <div class="right">
                <input name="cari" id="cari" type="text" class="inputbox help tipped" size="20" value="<?=$cari?>" title="Cari.." onkeypress="if (event.keyCode == 13) {$('#'+'mainform').attr('action','<?=site_url('dokumen/search')?>');$('#'+'mainform').submit();}" />
                <button type="button" onclick="$('#'+'mainform').attr('action','<?=site_url('dokumen/search')?>');$('#'+'mainform').submit();" class="uibutton tipsy south"  title="Cari Data"><span class="icon-search icon-large">&nbsp;</span>Cari</button>
            </div>
        </div>
        <table class="list">
		<thead>
            <tr>
                <th>No</th>
                <th><input type="checkbox" class="checkall"/></th>
                <th width="120">Aksi</th>
			
	 		<? if($o==2): ?>
				<th align="left"><a href="<?=site_url("dokumen/index/$p/1")?>">Judul Dokumen<span class="ui-icon ui-icon-triangle-1-n">
			<? elseif($o==1): ?>
				<th align="left"><a href="<?=site_url("dokumen/index/$p/2")?>">Judul Dokumen<span class="ui-icon ui-icon-triangle-1-s">
			<? else: ?>
				<th align="left"><a href="<?=site_url("dokumen/index/$p/1")?>">Judul Dokumen<span class="ui-icon ui-icon-triangle-2-n-s">
			<? endif; ?>&nbsp;</span></a></th>
			
			<? if($o==4): ?>
				<th align="left"><a href="<?=site_url("dokumen/index/$p/3")?>">Enabled / Disabled<span class="ui-icon ui-icon-triangle-1-n">
			<? elseif($o==3): ?>
				<th align="left"><a href="<?=site_url("dokumen/index/$p/4")?>">Enabled / Disabled<span class="ui-icon ui-icon-triangle-1-s">
			<? else: ?>
				<th align="left"><a href="<?=site_url("dokumen/index/$p/3")?>">Enabled / Disabled<span class="ui-icon ui-icon-triangle-2-n-s">
			<? endif; ?>&nbsp;</span></a></th>
			
			<? if($o==6): ?>
				<th align="left" width='150'><a href="<?=site_url("dokumen/index/$p/5")?>">Diupload pada<span class="ui-icon ui-icon-triangle-1-n">
			<? elseif($o==5): ?>
				<th align="left" width='150'><a href="<?=site_url("dokumen/index/$p/6")?>">Diupload pada<span class="ui-icon ui-icon-triangle-1-s">
			<? else: ?>
				<th align="left" width='150'><a href="<?=site_url("dokumen/index/$p/5")?>">Diupload pada<span class="ui-icon ui-icon-triangle-2-n-s">
			<? endif; ?>&nbsp;</span></a></th>
            <th width="200">File</th>
			</tr>
		</thead>
		<tbody>
        <?foreach($main as $data){?>
		<tr>
			<td align="center" width="2"><?=$data['no']?></td>
			<td align="center" width="5">
				<input type="checkbox" name="id_cb[]" value="<?=$data['id']?>" />
			</td>
			<td><div class="uibutton-group">
				<a href="<?=site_url("dokumen/form/$p/$o/$data[id]")?>" class="uibutton tipsy south" title="Ubah Data"><span class="icon-edit icon-large"> Ubah </span></a><a href="<?=site_url("dokumen/delete/$p/$o/$data[id]")?>" class="uibutton tipsy south" title="Hapus Data" target="confirm" message="Apakah Anda Yakin?" header="Hapus Data"><span class="icon-trash icon-large"><span></a><?if($data['enabled'] == '2'):?><a href="<?=site_url('dokumen/dokumen_lock/'.$data['id'])?>" class="uibutton tipsy south" title="Aktivasi dokumen"><span class="icon-lock icon-large"></span></a><?elseif($data['enabled'] == '1'): ?><a href="<?=site_url('dokumen/dokumen_unlock/'.$data['id'])?>" class="uibutton tipsy south" title="Non-aktifkan dokumen"><span class="icon-unlock icon-large"><span></a>
			<?endif?></div>
			  </td>
			  <td><?=$data['nama']?></td>
			  <td><?=$data['aktif']?></td>
			  <td><?=tgl_indo2($data['tgl_upload'])?></td>
<td><a href="<?=base_url()?>assets/front/dokumen/<?=underscore($data['satuan'])?>" ><?=$data['satuan']?></a></td>
		</tr>
        <?}?>
		</tbody>
    </table>
    </div>
	</form>
    <div class="ui-layout-south panel bottom">
        <div class="left"> 
		<div class="table-info">
          <form id="paging" action="<?=site_url('dokumen')?>" method="post">
		  <label>Tampilkan</label>
            <select name="per_page" onchange="$('#paging').submit()" >
              <option value="20" <? selected($per_page,20); ?> >20</option>
              <option value="50" <? selected($per_page,50); ?> >50</option>
              <option value="100" <? selected($per_page,100); ?> >100</option>
            </select>
            <label>Dari</label>
            <label><strong><?=$paging->num_rows?></strong></label>
            <label>Total Data</label>
          </form>
          </div>
        </div>
        <div class="right">
            <div class="uibutton-group">
            <? if($paging->start_link): ?>
				<a href="<?=site_url("dokumen/index/$paging->start_link/$o")?>" class="uibutton"  >Awal</a>
			<? endif; ?>
			<? if($paging->prev): ?>
				<a href="<?=site_url("dokumen/index/$paging->prev/$o")?>" class="uibutton"  >Prev</a>
			<? endif; ?>
            </div>
            <div class="uibutton-group">
                
				<? for($i=$paging->start_link;$i<=$paging->end_link;$i++): ?>
				<a href="<?=site_url("dokumen/index/$i/$o")?>" <? jecho($p,$i,"class='uibutton special'")?> class="uibutton"><?=$i?></a>
				<? endfor; ?>
            </div>
            <div class="uibutton-group">
			<? if($paging->next): ?>
				<a href="<?=site_url("dokumen/index/$paging->next/$o")?>" class="uibutton">Next</a>
			<? endif; ?>
			<? if($paging->end_link): ?>
                <a href="<?=site_url("dokumen/index/$paging->end_link/$o")?>" class="uibutton">Akhir</a>
			<? endif; ?>
            </div>
        </div>
    </div>
</div>
</td>
</tr>
</table>
</div>
