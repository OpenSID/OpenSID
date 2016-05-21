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
<legend>Kategori area</legend>
<div class="lmenu">
<ul>
<li <?if($tip==1)echo "class='selected'";?>><a href="<?=site_url("area/index/1")?>">Atas</a></li>
<li <?if($tip==2)echo "class='selected'";?>><a href="<?=site_url("area/index/2")?>">Atas Kiri</a></li>


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
<h3>Manajemen Properti / area</h3>
</div>
<div id="contentpane">
<form id="mainform" name="mainform" action="" method="post">
<div class="ui-layout-north panel">
<div class="left">
<div class="uibutton-group">
<a href="<?=site_url("area/form")?>" class="uibutton tipsy south" title="Tambah Data" ><span class="ui-icon ui-icon-plus">&nbsp;</span>Tambah Data Baru</a>
<button type="button" title="Delete Data" onclick="deleteAllBox('mainform','<?=site_url("area/delete_all/$p/$o")?>')" class="uibutton tipsy south"><span class="ui-icon ui-icon-trash">&nbsp;</span>Delete Data
</div>
</div>
</div>
<div class="ui-layout-center" id="maincontent" style="padding: 5px;">
<div class="table-panel top">
<div class="left">
		
		<select name="filter" onchange="formAction('mainform','<?=site_url('area/filter')?>')">
			<option value="">Semua</option>
			<option value="1" <?if($filter==1) :?>selected<?endif?>>Enabled</option>
			<option value="2" <?if($filter==2) :?>selected<?endif?>>Disabled</option>
		</select>

		<select name="polygon" onchange="formAction('mainform','<?=site_url('area/polygon')?>')">
			<option value="">Kategori</option>
			<?foreach($list_polygon AS $data){?>
			<option value="<?=$data['id']?>" <?if($polygon == $data['id']) :?>selected<?endif?>><?=$data['nama']?></option>
			<?}?>
		</select>
			
		<select name="subpolygon" onchange="formAction('mainform','<?=site_url('area/subpolygon')?>')">
			<option value="">Jenis</option>
			<?foreach($list_subpolygon AS $data){?>
			<option value="<?=$data['id']?>" <?if($subpolygon == $data['id']) :?>selected<?endif?>><?=$data['nama']?></option>
			<?}?>
		</select>
					
</div>
<div class="right">
<input name="cari" id="cari" type="text" class="inputbox help tipped" size="20" value="<?=$cari?>" title="Search.."/>
<button type="button" onclick="$('#'+'mainform').attr('action','<?=site_url('area/search')?>');$('#'+'mainform').submit();" class="uibutton tipsy south"title="Cari Data"><span class="ui-icon ui-icon-search">&nbsp;</span>Search</button>
</div>
</div>
<table class="list">
<thead>
<tr>
<th>No</th>
<th><input type="checkbox" class="checkall"/></th>
<th width="250">Aksi</th>

 <? if($o==2): ?>
<th align="left"><a href="<?=site_url("area/index/$p/1")?>">Kategori<span class="ui-icon ui-icon-triangle-1-n">
<? elseif($o==1): ?>
<th align="left"><a href="<?=site_url("area/index/$p/2")?>">Kategori<span class="ui-icon ui-icon-triangle-1-s">
<? else: ?>
<th align="left"><a href="<?=site_url("area/index/$p/1")?>">Kategori<span class="ui-icon ui-icon-triangle-2-n-s">
<? endif; ?>&nbsp;</span></a></th>

<? if($o==4): ?>
<th align="left"><a href="<?=site_url("area/index/$p/3")?>">Aktif<span class="ui-icon ui-icon-triangle-1-n">
<? elseif($o==3): ?>
<th align="left"><a href="<?=site_url("area/index/$p/4")?>">Aktif<span class="ui-icon ui-icon-triangle-1-s">
<? else: ?>
<th align="left"><a href="<?=site_url("area/index/$p/3")?>">Aktif<span class="ui-icon ui-icon-triangle-2-n-s">
<? endif; ?>&nbsp;</span></a></th>
<th>Kategori</th>
<th>Jenis</th>
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
<td><div class="uibutton-group">
<a href="<?=site_url("area/form/$p/$o/$data[id]")?>" class="uibutton tipsy south"><span class="icon-edit icon-large"> Ubah </span></a><a href="<?=site_url("area/delete/$p/$o/$data[id]")?>" class="uibutton tipsy south" title="Delete Data" target="confirm" message="Apakah Anda Yakin?" header="Hapus Data"><span class="icon-trash icon-large"></span></a><?/*if($data['enabled'] == '2'):?><a href="<?=site_url('area/area_lock/'.$data['id'])?>" class="ui-icons icon-lock tipsy south" title="Enable area"></a><?elseif($data['enabled'] == '1'): ?><a href="<?=site_url('area/area_unlock/'.$data['id'])?>" class="ui-icons icon-unlock tipsy south" title="Disable area"></a>*/?><a href="<?=site_url("area/ajax_area_maps/$p/$o/$data[id]")?>" target="ajax-modalz" rel="window" header="area <?=$data['nama']?>" class="uibutton tipsy south" title="area <?=$data['nama']?>"><span class="icon-plus icon-large"> Lokasi </span></a>
</div>
</td>
<td width="150"><?=$data['nama']?></td>
<td width="50"><?=$data['aktif']?></td>
<td width="220"><?=$data['kategori']?></td>
<td width="150"><?=$data['jenis']?></td>
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
<form id="paging" action="<?=site_url('area')?>" method="post">
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
<a href="<?=site_url("area/index/$paging->start_link/$o")?>" class="uibutton">First</a>
<? endif; ?>
<? if($paging->prev): ?>
<a href="<?=site_url("area/index/$paging->prev/$o")?>" class="uibutton">Prev</a>
<? endif; ?>
</div>
<div class="uibutton-group">

<? for($i=$paging->start_link;$i<=$paging->end_link;$i++): ?>
<a href="<?=site_url("area/index/$i/$o")?>" <? jecho($p,$i,"class='uibutton special'")?> class="uibutton"><?=$i?></a>
<? endfor; ?>
</div>
<div class="uibutton-group">
<? if($paging->next): ?>
<a href="<?=site_url("area/index/$paging->next/$o")?>" class="uibutton">Next</a>
<? endif; ?>
<? if($paging->end_link): ?>
<a href="<?=site_url("area/index/$paging->end_link/$o")?>" class="uibutton">Last</a>
<? endif; ?>
</div>
</div>
</div>
</div>
</td>
</tr>
</table>
</div>
