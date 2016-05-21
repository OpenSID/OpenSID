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
<?/*
<td class="side-menu">

<fieldset>
<legend>Kategori line</legend>
<div class="lmenu">
<ul>
<li <?if($tip==1)echo "class='selected'";?>><a href="<?=site_url("line/index/1")?>">Atas</a></li>
<li <?if($tip==2)echo "class='selected'";?>><a href="<?=site_url("line/index/2")?>">Atas Kiri</a></li>


<li ><a href="Samping">Samping</a></li>
<li ><a href="Tengah">Tengah</a></li>
<li ><a href="Bawah">Bawah</a></li>

</ul>
</div>
</fieldset>

</td>
*/?>
<td style="background:#fff;padding:0px;"> 
<div class="content-header">
<h3>Manajemen Kategori line</h3>
</div>
<div id="contentpane">
<form id="mainform" name="mainform" action="" method="post">
<div class="ui-layout-north panel">
<div class="left">
<div class="uibutton-group">
<a href="<?=site_url("line/form")?>" class="uibutton tipsy south" title="Tambah Data" ><span class="ui-icon ui-icon-plus">&nbsp;</span>Tambah Kategori Baru</a>
<button type="button" title="Delete Data" onclick="deleteAllBox('mainform','<?=site_url("line/delete_all/$p/$o")?>')" class="uibutton tipsy south"><span class="ui-icon ui-icon-trash">&nbsp;</span>Delete Data
</div>
</div>
</div>
<div class="ui-layout-center" id="maincontent" style="padding: 5px;">
<div class="table-panel top">
<div class="left">
<select name="filter" onchange="formAction('mainform','<?=site_url('line/filter')?>')">
<option value="">Semua</option>
<option value="1" <?if($filter==1) :?>selected<?endif?>>Enabled</option>
<option value="2" <?if($filter==2) :?>selected<?endif?>>Disabled</option>
</select>
</div>
<div class="right">
<input name="cari" id="cari" type="text" class="inputbox help tipped" size="20" value="<?=$cari?>" title="Search.."/>
<button type="button" onclick="$('#'+'mainform').attr('action','<?=site_url('line/search')?>');$('#'+'mainform').submit();" class="uibutton tipsy south"title="Cari Data"><span class="ui-icon ui-icon-search">&nbsp;</span>Search</button>
</div>
</div>
<table class="list">
<thead>
<tr>
<th>No</th>
<th><input type="checkbox" class="checkall"/></th>
<th width="80">Aksi</th>

 <? if($o==2): ?>
<th align="left"><a href="<?=site_url("line/index/$p/1")?>">Kategori<span class="ui-icon ui-icon-triangle-1-n">
<? elseif($o==1): ?>
<th align="left"><a href="<?=site_url("line/index/$p/2")?>">Kategori<span class="ui-icon ui-icon-triangle-1-s">
<? else: ?>
<th align="left"><a href="<?=site_url("line/index/$p/1")?>">Kategori<span class="ui-icon ui-icon-triangle-2-n-s">
<? endif; ?>&nbsp;</span></a></th>

<? if($o==4): ?>
<th align="left"><a href="<?=site_url("line/index/$p/3")?>">Aktif<span class="ui-icon ui-icon-triangle-1-n">
<? elseif($o==3): ?>
<th align="left"><a href="<?=site_url("line/index/$p/4")?>">Aktif<span class="ui-icon ui-icon-triangle-1-s">
<? else: ?>
<th align="left"><a href="<?=site_url("line/index/$p/3")?>">Aktif<span class="ui-icon ui-icon-triangle-2-n-s">
<? endif; ?>&nbsp;</span></a></th>
<th>Simbol</th>
<th></th>
</tr>
</thead>
<tbody>
<?foreach($main as $data){?>
<tr>
<td align="center" width="2"><?=$data['no']?></td>
<td align="center" width="5">
<input type="checkbox" name="id_cb[]" value="<?=$data['id']?>" />
</td>
<td>
<a href="<?=site_url("line/form/$p/$o/$data[id]")?>" class="ui-icons icon-edit tipsy south" title="Edit Data"></a><a href="<?=site_url("line/delete/$p/$o/$data[id]")?>" class="ui-icons icon-remove tipsy south" title="Delete Data" target="confirm" message="Apakah Anda Yakin?" header="Hapus Data"></a><?if($data['enabled'] == '2'):?><a href="<?=site_url('line/line_lock/'.$data['id'])?>" class="ui-icons icon-lock tipsy south" title="Enable line"></a><?elseif($data['enabled'] == '1'): ?><a href="<?=site_url('line/line_unlock/'.$data['id'])?>" class="ui-icons icon-unlock tipsy south" title="Disable line"></a><a href="<?=site_url("line/sub_line/$data[id]")?>" class="ui-icons icon-document-table tipsy south" title="Rincian Sub line"></a><a href="<?=site_url("line/ajax_add_sub_line/$data[id]")?>" target="ajax-modal" rel="window" header="Tambah Sub line <?=$data['nama']?>" class="ui-icons icon-plus tipsy south" title="Tambah Sub line"></a>
<?endif?>
</td>
<td width="150"><?=$data['nama']?></td>
<td width="50"><?=$data['aktif']?></td>
<td align="center" width="50"><img src="<?=base_url("assets/images/gis/line")?>/<?=$data['simbol']?>"></td>
<td></td>
</tr>
<?}?>
</tbody>
</table>
</div>
</form>
<div class="ui-layout-south panel bottom">
<div class="left"> 
<div class="table-info">
<form id="paging" action="<?=site_url('line')?>" method="post">
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
<a href="<?=site_url("line/index/$paging->start_link/$o")?>" class="uibutton">First</a>
<? endif; ?>
<? if($paging->prev): ?>
<a href="<?=site_url("line/index/$paging->prev/$o")?>" class="uibutton">Prev</a>
<? endif; ?>
</div>
<div class="uibutton-group">

<? for($i=$paging->start_link;$i<=$paging->end_link;$i++): ?>
<a href="<?=site_url("line/index/$i/$o")?>" <? jecho($p,$i,"class='uibutton special'")?> class="uibutton"><?=$i?></a>
<? endfor; ?>
</div>
<div class="uibutton-group">
<? if($paging->next): ?>
<a href="<?=site_url("line/index/$paging->next/$o")?>" class="uibutton">Next</a>
<? endif; ?>
<? if($paging->end_link): ?>
<a href="<?=site_url("line/index/$paging->end_link/$o")?>" class="uibutton">Last</a>
<? endif; ?>
</div>
</div>
</div>
</div>
</td>
</tr>
</table>
</div>
