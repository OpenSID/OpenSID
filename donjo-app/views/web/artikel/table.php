<script>
$(function() {
var keyword = <?//=$keyword?> ;
$( "#cari" ).autocomplete({
source: keyword
});
});
</script>

<div id="pageC">
<table class="inner">
<tr style="vertical-align:top">

<td class="side-menu">

<legend>Kategori Artikel</legend>
<div class="lmenu">
<ul>
<?foreach($list_kategori AS $data){?>
	<a href="<?=site_url("web/index/$data[id]")?>">
		<li <?if($cat == $data['id'])echo "class='selected'";?>>
			<?=$data['kategori']?>
		</li>
	</a>
<?}?>
	<li><a class="icon-plus-sign-alt icon-large" title="Ubah Data" href="<?=site_url("web/ajax_add_kategori")?>" target="ajax-modal" rel="window" header="Tambah Kategori Baru"> Tambah Kategori</a></li>
</ul>
</div>
</td>

<td style="background:#fff;padding:0px;"> 
<div class="content-header">
<h3>Manajemen <?=$kategori['kategori']?></h3>
</div>
<div id="contentpane">
<form id="mainform" name="mainform" action="" method="post">
<div class="ui-layout-north panel">
<div class="left">
<div class="uibutton-group">
<a href="<?=site_url("web/form/$cat")?>" class="uibutton tipsy south" title="Tambah Data" ><span class="icon-plus-sign icon-large">&nbsp;</span>Tambah <?=$kategori['kategori']?> Baru</a>
<button type="button" title="Hapus <?=$kategori['kategori']?>" onclick="deleteAllBox('mainform','<?=site_url("web/delete_all/$p/$o")?>')" class="uibutton tipsy south"><span class="icon-trash icon-large">&nbsp;</span>Hapus <?=$kategori['kategori']?>
</div>
</div>
<div class="right">
<button type="button" title="Hapus Kategori <?=$kategori['kategori']?>" onclick="deleteAllBox('mainform','<?=site_url("web/hapus/$cat/$p/$o")?>')" class="uibutton tipsy south"><span class="icon-trash icon-large">&nbsp;</span>Hapus Kategori <?=$kategori['kategori']?>
</div>
</div>
<div class="ui-layout-center" id="maincontent" style="padding: 5px;">
<div class="table-panel top">
<div class="left">
<select name="filter" onchange="formAction('mainform','<?=site_url('web/filter')?>')">
<option value="">Semua</option>
<option value="1" <?if($filter==1) :?>selected<?endif?>>Enabled</option>
<option value="2" <?if($filter==2) :?>selected<?endif?>>Disabled</option>
</select>
</div>
<div class="right">
<input name="cari" id="cari" type="text" class="inputbox help tipped" size="20" value="<?=$cari?>" title="Cari.." onkeypress="if (event.keyCode == 13) {$('#'+'mainform').attr('action','<?=site_url("web/search/$cat")?>');$('#'+'mainform').submit();}" />
<button type="button" onclick="$('#'+'mainform').attr('action','<?=site_url("web/search/$cat")?>');$('#'+'mainform').submit();" class="uibutton tipsy south"title="Cari Data"><span class="icon-search icon-large">&nbsp;</span>Cari</button>
</div>
</div>
<table class="list">
<thead>
<tr>
<th>No</th>
<th><input type="checkbox" class="checkall"/></th>
<th width="160">Aksi</th>

 <? if($o==2): ?>
<th align="left"><a href="<?=site_url("web/index/$p/1")?>">Judul<span class="ui-icon ui-icon-triangle-1-n">
<? elseif($o==1): ?>
<th align="left"><a href="<?=site_url("web/index/$p/2")?>">Judul<span class="ui-icon ui-icon-triangle-1-s">
<? else: ?>
<th align="left"><a href="<?=site_url("web/index/$p/1")?>">Judul<span class="ui-icon ui-icon-triangle-2-n-s">
<? endif; ?>&nbsp;</span></a></th>

<? if($o==4): ?>
<th align="left"><a href="<?=site_url("web/index/$p/3")?>">Enabled / Disabled<span class="ui-icon ui-icon-triangle-1-n">
<? elseif($o==3): ?>
<th align="left"><a href="<?=site_url("web/index/$p/4")?>">Enabled / Disabled<span class="ui-icon ui-icon-triangle-1-s">
<? else: ?>
<th align="left"><a href="<?=site_url("web/index/$p/3")?>">Enabled / Disabled<span class="ui-icon ui-icon-triangle-2-n-s">
<? endif; ?>&nbsp;</span></a></th>

<? if($o==6): ?>
<th align="left" width='150'><a href="<?=site_url("web/index/$p/5")?>">Diposting Pada<span class="ui-icon ui-icon-triangle-1-n">
<? elseif($o==5): ?>
<th align="left" width='150'><a href="<?=site_url("web/index/$p/6")?>">Diposting Pada<span class="ui-icon ui-icon-triangle-1-s">
<? else: ?>
<th align="left" width='150'><a href="<?=site_url("web/index/$p/5")?>">Diposting Pada<span class="ui-icon ui-icon-triangle-2-n-s">
<? endif; ?>&nbsp;</span></a></th>

</tr>
</thead>
<tbody>
<? foreach($main as $data){?>
<tr>
<td align="center" width="2"><?=$data['no']?></td>
<td align="center" width="5">
<input type="checkbox" name="id_cb[]" value="<?=$data['id']?>" />
</td>
<td><div class="uibutton-group">
<a href="<?=site_url("web/form/$cat/$p/$o/$data[id]")?>" class="uibutton tipsy south" title="Ubah Data"><span class="icon-edit icon-large"> Ubah </span></a>
<a href="<?=site_url("web/delete/$cat/$p/$o/$data[id]")?>" class="uibutton tipsy south" title="Hapus Data" target="confirm" message="Apakah Anda Yakin?" header="Hapus Data"><span  class="icon-trash icon-large"></span></a>
<? if($data['enabled'] == '2'):?>
	<a href="<?=site_url("web/artikel_lock/$cat/$data[id]")?>" class="uibutton tipsy south" title="Aktivasi artikel"><span class="icon-lock icon-large"></span></a>
	<? elseif($data['enabled'] == '1'): ?>
	<a href="<?=site_url("web/artikel_unlock/$cat/$data[id]")?>" class="uibutton tipsy south" title="Non-aktifkan artikel"><span class="icon-unlock icon-large"></span></a>
	<a href="<?=site_url("web/headline/$cat/$p/$o/$data[id]")?>" class="uibutton tipsy south" title="Klik Untuk Jadikan Headline"><span class="<? if($data['headline']==1){?>icon-star-empty icon-large" title="Headline Saat Ini"<? }else{?> icon-star icon-large" <? }?>target="confirm" message="Jadikan Artikel Berikut Sebagai Headline News?" header="Headline"></span></a>
	<a href="<?=site_url("web/slide/$cat/$p/$o/$data[id]")?>" class="uibutton tipsy south" title="Klik Untuk Jadikan Slide" message="Masukkan ke dalam slide?"><span class="<? if($data['headline']==3){?>icon-pause icon-large" title="Keluarkan dari slide" message="Keluarkan dari slide?"<? }else{?> icon-play icon-large"  <? }?>target="confirm"  header="Slide"></span></a>
<? endif?></div>
</td>
<td><?=$data['judul']?></td>
<td><?=$data['aktif']?></td>
<td><?=tgl_indo2($data['tgl_upload'])?></td>
</tr>
<?}?>
</tbody>
</table>
</div>
</form>
<div class="ui-layout-south panel bottom">
<div class="left"> 
<div class="table-info">
<form id="paging" action="<?=site_url('web')?>" method="post">
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
<a href="<?=site_url("web/index/$paging->start_link/$cat/$o")?>" class="uibutton">Awal</a>
<? endif; ?>
<? if($paging->prev): ?>
<a href="<?=site_url("web/index/$paging->prev/$cat/$o")?>" class="uibutton">Prev</a>
<? endif; ?>
</div>
<div class="uibutton-group">

<? for($i=$paging->start_link;$i<=$paging->end_link;$i++): ?>
<a href="<?=site_url("web/index/$cat/$i/$o")?>" <? jecho($p,$i,"class='uibutton special'")?> class="uibutton"><?=$i?></a>
<? endfor; ?>
</div>
<div class="uibutton-group">
<? if($paging->next): ?>
<a href="<?=site_url("web/index/$paging->next/$cat/$o")?>" class="uibutton">Next</a>
<? endif; ?>
<? if($paging->end_link): ?>
<a href="<?=site_url("web/index/$paging->end_link/$cat/$o")?>" class="uibutton">Akhir</a>
<? endif; ?>
</div>
</div>
</div>
</div>
</td>
</tr>
</table>
</div>