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

<td class="side-menu">

<legend>Kategori Menu</legend>
<div class="lmenu">
<ul>
<a href="<?=site_url("menu/index/1")?>"><li <?if($tip==1)echo "class='selected'";?>>Menu Statis</li></a>
<a href="<?=site_url("menu/index/2")?>"><li <?if($tip==2)echo "class='selected'";?>>Menu Dinamis</li></a>

</ul>
</div>
</td>
<td style="background:#fff;padding:0px;"> 
<div class="content-header">
<h3>Manajemen Menu</h3>
</div>
<div id="contentpane">
<form id="mainform" name="mainform" action="" method="post">
<div class="ui-layout-north panel">
<div class="left">
<div class="uibutton-group">
<a href="<?=site_url("menu/form/$tip")?>" class="uibutton tipsy south" title="Tambah Data" ><span class="icon-plus-sign icon-large">&nbsp;</span>Tambah Menu Baru</a>
<button type="button" title="Hapus Data" onclick="deleteAllBox('mainform','<?=site_url("menu/delete_all/$tip/$p/$o")?>')" class="uibutton tipsy south"><span class="icon-trash icon-large">&nbsp;</span>Hapus Data
</div>
</div>
</div>
<div class="ui-layout-center" id="maincontent" style="padding: 5px;">
<div class="table-panel top">
<div class="left">
<select name="filter" onchange="formAction('mainform','<?=site_url('menu/filter')?>')">
<option value="">Semua</option>
<option value="1" <?if($filter==1) :?>selected<?endif?>>Enabled</option>
<option value="2" <?if($filter==2) :?>selected<?endif?>>Disabled</option>
</select>
</div>
<div class="right">
<input name="cari" id="cari" type="text" class="inputbox help tipped" size="20" value="<?=$cari?>" title="Cari.." onkeypress="if (event.keyCode == 13) {$('#'+'mainform').attr('action','<?=site_url("menu/search/$tip")?>');$('#'+'mainform').submit();}" />
<button type="button" onclick="$('#'+'mainform').attr('action','<?=site_url("menu/search/$tip")?>');$('#'+'mainform').submit();" class="uibutton tipsy south"title="Cari Data"><span class="icon-search icon-large">&nbsp;</span>Cari</button>
</div>
</div>
<table class="list">
<thead>
<tr>
<th>No</th>
<th><input type="checkbox" class="checkall"/></th>
<th width="160">Aksi</th>

 <? if($o==2): ?>
<th align="left"><a href="<?=site_url("menu/index/$p/1")?>">Nama<span class="ui-icon ui-icon-triangle-1-n">
<? elseif($o==1): ?>
<th align="left"><a href="<?=site_url("menu/index/$p/2")?>">Nama<span class="ui-icon ui-icon-triangle-1-s">
<? else: ?>
<th align="left"><a href="<?=site_url("menu/index/$p/1")?>">Nama<span class="ui-icon ui-icon-triangle-2-n-s">
<? endif; ?>&nbsp;</span></a></th>

<? if($o==4): ?>
<th align="left"><a href="<?=site_url("menu/index/$p/3")?>">Enabled / Disabled<span class="ui-icon ui-icon-triangle-1-n">
<? elseif($o==3): ?>
<th align="left"><a href="<?=site_url("menu/index/$p/4")?>">Enabled / Disabled<span class="ui-icon ui-icon-triangle-1-s">
<? else: ?>
<th align="left"><a href="<?=site_url("menu/index/$p/3")?>">Enabled / Disabled<span class="ui-icon ui-icon-triangle-2-n-s">
<? endif; ?>&nbsp;</span></a></th>
<th>Link</th>
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
<a href="<?=site_url("menu/sub_menu/$tip/$data[id]")?>" class="uibutton tipsy south" title="Rincian Sub Menu"><span class="icon-list icon-large"> Rincian</span></a>
<a href="<?=site_url("menu/form/$tip/$data[id]")?>" class="uibutton tipsy south" title="Ubah Data"><span class="icon-edit icon-large"></span></a>
<a href="<?=site_url("menu/delete/$tip/$data[id]")?>" class="uibutton tipsy south" title="Hapus Data" target="confirm" message="Apakah Anda Yakin?" header="Hapus Data"><span class="icon-trash icon-large"></span></a><?if($data['enabled'] == '2'):?>
<a href="<?=site_url("menu/menu_lock/$tip/".$data['id'])?>"  title="Aktivasi menu"><span class="icon-lock icon-large"></span></a><?elseif($data['enabled'] == '1'): ?><a href="<?=site_url("menu/menu_unlock/$tip/".$data['id'])?>" class="uibutton tipsy south"  title="Non-aktifkan menu"><span class="icon-unlock icon-large"></span></a>
<a href="<?=site_url("menu/ajax_add_sub_menu/$tip/$data[id]")?>" class="uibutton tipsy south" target="ajax-modalx" rel="window" header="Tambah Sub Menu <?=$data['nama']?>" class="uibutton tipsy south" title="Tambah Sub Menu"><span class="icon-plus-sign-alt icon-large"></span></a>
<?endif?></div>
</td>
<td><?=$data['nama']?></td>
<td><?=$data['aktif']?></td>
<td><?=$data['link']?></td>
</tr>
<?}?>
</tbody>
</table>
</div>
</form>
<div class="ui-layout-south panel bottom">
<div class="left"> 
<div class="table-info">
<form id="paging" action="<?=site_url('menu')?>" method="post">
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
<a href="<?=site_url("menu/index/$paging->start_link/$o")?>" class="uibutton">Awal</a>
<? endif; ?>
<? if($paging->prev): ?>
<a href="<?=site_url("menu/index/$paging->prev/$o")?>" class="uibutton">Prev</a>
<? endif; ?>
</div>
<div class="uibutton-group">

<? for($i=$paging->start_link;$i<=$paging->end_link;$i++): ?>
<a href="<?=site_url("menu/index/$i/$o")?>" <? jecho($p,$i,"class='uibutton special'")?> class="uibutton"><?=$i?></a>
<? endfor; ?>
</div>
<div class="uibutton-group">
<? if($paging->next): ?>
<a href="<?=site_url("menu/index/$paging->next/$o")?>" class="uibutton">Next</a>
<? endif; ?>
<? if($paging->end_link): ?>
<a href="<?=site_url("menu/index/$paging->end_link/$o")?>" class="uibutton">Akhir</a>
<? endif; ?>
</div>
</div>
</div>
</div>
</td>
</tr>
</table>
</div>
