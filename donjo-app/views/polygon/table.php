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
<?php /*
<td class="side-menu">

<fieldset>
<legend>Kategori polygon</legend>
<div class="lmenu">
<ul>
<li <?php if($tip==1)echo "class='selected'";?>><a href="<?php echo site_url("polygon/index/1")?>">Atas</a></li>
<li <?php if($tip==2)echo "class='selected'";?>><a href="<?php echo site_url("polygon/index/2")?>">Atas Kiri</a></li>


<li ><a href="Samping">Samping</a></li>
<li ><a href="Tengah">Tengah</a></li>
<li ><a href="Bawah">Bawah</a></li>

</ul>
</div>
</fieldset>

</td>
*/?>
<td style="background:#fff;padding:0px;">
<div class="content">
	<h3>Manajemen Kategori polygon</h3>
	<div style="padding:1em;margin:1em 0;border:solid 1px #c00;background:#fee;color:#c00;">Modul ini masih dalam tahap pengembangan. Ide-ide dan usulan mari kita kumpulkan untuk memperkaya khazanah SID</div>
</div>
<div id="contentpane">
<form id="mainform" name="mainform" action="" method="post">
<div class="ui-layout-north panel">
<div class="left">
<div class="uibutton-group">
<a href="<?php echo site_url("polygon/form")?>" class="uibutton tipsy south" title="Tambah Data" ><span class="fa fa-plus-square">&nbsp;</span>Tambah Kategori Baru</a>
<button type="button" title="Hapus Data" onclick="deleteAllBox('mainform','<?php echo site_url("polygon/delete_all/$p/$o")?>')" class="uibutton tipsy south"><span class="fa fa-trash">&nbsp;</span>Hapus Data</button>
</div>
</div>
</div>
<div class="ui-layout-center" id="maincontent" style="padding: 5px;">
<div class="table-panel top">
<div class="left">
<select name="filter" onchange="formAction('mainform','<?php echo site_url('polygon/filter')?>')">
<option value="">Semua</option>
<option value="1" <?php if($filter==1) :?>selected<?php endif?>>Enabled</option>
<option value="2" <?php if($filter==2) :?>selected<?php endif?>>Disabled</option>
</select>
</div>
<div class="right">
<input name="cari" id="cari" type="text" class="inputbox help tipped" size="20" value="<?php echo $cari?>" title="Search.."/>
<button type="button" onclick="$('#'+'mainform').attr('action','<?php echo site_url('polygon/search')?>');$('#'+'mainform').submit();" class="uibutton tipsy south"title="Cari Data"><span class="fa fa-search">&nbsp;</span>Cari</button>
</div>
</div>
<table class="list">
<thead>
<tr>
<th>No</th>
<th><input type="checkbox" class="checkall"/></th>
<th width="140">Aksi</th>

 <?php  if($o==2): ?>
<th align="left"><a href="<?php echo site_url("polygon/index/$p/1")?>">Kategori <span class="fa fa-sort-asc fa-sm">
<?php  elseif($o==1): ?>
<th align="left"><a href="<?php echo site_url("polygon/index/$p/2")?>">Kategori <span class="fa fa-sort-desc fa-sm">
<?php  else: ?>
<th align="left"><a href="<?php echo site_url("polygon/index/$p/1")?>">Kategori <span class="fa fa-sort fa-sm">
<?php  endif; ?>&nbsp;</span></a></th>

<?php  if($o==4): ?>
<th align="left"><a href="<?php echo site_url("polygon/index/$p/3")?>">Aktif <span class="fa fa-sort-asc fa-sm">
<?php  elseif($o==3): ?>
<th align="left"><a href="<?php echo site_url("polygon/index/$p/4")?>">Aktif <span class="fa fa-sort-desc fa-sm">
<?php  else: ?>
<th align="left"><a href="<?php echo site_url("polygon/index/$p/3")?>">Aktif <span class="fa fa-sort fa-sm">
<?php  endif; ?>&nbsp;</span></a></th>
<th width="100">Warna</th>
<th></th>
</tr>
</thead>
<tbody>
<?php foreach($main as $data){?>
<tr>
<td align="center" width="2"><?php echo $data['no']?></td>
<td align="center" width="5">
<input type="checkbox" name="id_cb[]" value="<?php echo $data['id']?>" />
</td>
<td>
	<div class="uibutton-group">
		<a href="<?php echo site_url("polygon/sub_polygon/$data[id]")?>" class="uibutton tipsy south fa-tipis" title="Rincian Sub polygon"><span class="fa fa-bars"></span> Rincian</a>
		<a href="<?php echo site_url("polygon/form/$p/$o/$data[id]")?>" class="uibutton tipsy south" title="Ubah Data"><span class="fa fa-edit"></span></a>
		<a href="<?php echo site_url("polygon/delete/$p/$o/$data[id]")?>" class="uibutton tipsy south" title="Hapus Data" target="confirm" message="Apakah Anda Yakin?" header="Hapus Data"><span class="fa fa-trash"></span></a>
		<?php if($data['enabled'] == '2'):?>
			<a href="<?php echo site_url('polygon/polygon_lock/'.$data['id'])?>" class="uibutton tipsy south" title="Aktifkan polygon"><span class="fa fa-lock"></span></a>
		<?php elseif($data['enabled'] == '1'): ?>
			<a href="<?php echo site_url('polygon/polygon_unlock/'.$data['id'])?>" class="uibutton tipsy south" title="Non-aktifkan polygon"><span class="fa fa-unlock"></span></a>
			<a href="<?php echo site_url("polygon/ajax_add_sub_polygon/$data[id]")?>" "<?php echo $data['nama']?>" class="uibutton tipsy south" title="Tambah Sub polygon"><span class="fa fa-plus"></span></a>
		<?php endif?>
	</div>
</td>
<td width="150"><?php echo $data['nama']?></td>
<td width="50"><?php echo $data['aktif']?></td>
<td ><div style="background-color:#<?php echo $data['color']?>">&nbsp;<div></td>
<td></td>
</tr>
<?php }?>
</tbody>
</table>
</div>
</form>
<div class="ui-layout-south panel bottom">
<div class="left">
<div class="table-info">
<form id="paging" action="<?php echo site_url('polygon')?>" method="post">
<label>Tampilkan</label>
<select name="per_page" onchange="$('#paging').submit()" >
<option value="20" <?php  selected($per_page,20); ?> >20</option>
<option value="50" <?php  selected($per_page,50); ?> >50</option>
<option value="100" <?php  selected($per_page,100); ?> >100</option>
</select>
<label>Dari</label>
<label><strong><?php echo $paging->num_rows?></strong></label>
<label>Total Data</label>
</form>
</div>
</div>
<div class="right">
<div class="uibutton-group">
<?php  if($paging->start_link): ?>
<a href="<?php echo site_url("polygon/index/$paging->start_link/$o")?>" class="uibutton">First</a>
<?php  endif; ?>
<?php  if($paging->prev): ?>
<a href="<?php echo site_url("polygon/index/$paging->prev/$o")?>" class="uibutton">Prev</a>
<?php  endif; ?>
</div>
<div class="uibutton-group">

<?php  for($i=$paging->start_link;$i<=$paging->end_link;$i++): ?>
<a href="<?php echo site_url("polygon/index/$i/$o")?>" <?php  jecho($p,$i,"class='uibutton special'")?> class="uibutton"><?php echo $i?></a>
<?php  endfor; ?>
</div>
<div class="uibutton-group">
<?php  if($paging->next): ?>
<a href="<?php echo site_url("polygon/index/$paging->next/$o")?>" class="uibutton">Next <span class="fa fa-step-forward"></span></a>
<?php  endif; ?>
<?php  if($paging->end_link): ?>
<a href="<?php echo site_url("polygon/index/$paging->end_link/$o")?>" class="uibutton">Akhir <span class="fa fa-fast-forward"></span></a>
<?php  endif; ?>
</div>
</div>
</div>
</div>
</td>
</tr>
</table>
</div>
