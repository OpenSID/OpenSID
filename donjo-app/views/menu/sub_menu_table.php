
<div id="pageC">
<table class="inner">
<tr style="vertical-align:top">

<td class="side-menu">

<fieldset>
<legend>Kategori Menu</legend>
<div class="lmenu">
<ul>
<li <?if($tip==1)echo "class='selected'";?>><a href="<?=site_url("menu/index/1")?>">Statis</a></li>
<li <?if($tip==2)echo "class='selected'";?>><a href="<?=site_url("menu/index/2")?>">Dinamis</a></li>


</ul>
</div>
</fieldset>

</td>

<td style="background:#fff;padding:0px;"> 
<div class="content-header">
<h3>Manajemen Sub Menu</h3>
</div>
<div id="contentpane">
<form id="mainform" name="mainform" action="" method="post">
<div class="ui-layout-north panel">
<div class="left">
<div class="uibutton-group">
<a href="<?=site_url("menu/ajax_add_sub_menu/$tip/$menu")?>" target="ajax-modalx" rel="window" header="Tambah Sub Menu" class="uibutton tipsy south" title="Tambah Sub Menu"><span class="icon-plus icon-large">&nbsp;</span>Tambah Menu Baru</a>
<button type="button" title="Delete Data" onclick="deleteAllBox('mainform','<?=site_url("menu/delete_all_sub_menu/$tip/$menu")?>')" class="uibutton tipsy south"><span class="icon-trash icon-large">&nbsp;</span>Hapus Data
</div>
</div>
</div>
<div class="ui-layout-center" id="maincontent" style="padding: 5px;">
<div class="table-panel top">
<div class="left">
</div>
<div class="right">
</div>
</div>
<table class="list">
<thead>
<tr>
<th>No</th>
<th><input type="checkbox" class="checkall"/></th>
<th width="120">Aksi</th>
<th align="center">Nama</th>
<th align="center">Enabled</th>
<th>Link</th>
</tr>
</thead>
<tbody>
<?foreach($submenu as $data){?>
<tr>
<td align="center" width="2"><?=$data['no']?></td>
<td align="center" width="5">
<input type="checkbox" name="id_cb[]" value="<?=$data['id']?>" />
</td>
<td><div class="uibutton-group">
<a href="<?=site_url("menu/ajax_add_sub_menu/$tip/$menu/$data[id]")?>"  class="uibutton tipsy south" target="ajax-modalx" rel="window" header="Edit Menu" title="Edit Data"><span class="icon-edit icon-large"> Edit </span></a><a href="<?=site_url("menu/delete_sub_menu/$tip/$menu/$data[id]")?>"  class="uibutton tipsy south" title="Delete Data" target="confirm" message="Apakah Anda Yakin?" header="Hapus Data"><span class="icon-trash icon-large"></span></a><?if($data['enabled'] == '2'):?><a href="<?=site_url("menu/menu_lock_sub_menu/$tip/$menu/$data[id]")?>"  class="uibutton tipsy south" title="Enable menu"><span class="icon-lock icon-large"></span></a><?elseif($data['enabled'] == '1'): ?><a href="<?=site_url("menu/menu_unlock_sub_menu/$tip/$menu/$data[id]")?>"  class="uibutton tipsy south" title="Disable menu"><span class="icon-unlock icon-large"></span></a><?endif;?></div>
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
<a href="<?=site_url()?>menu/index/<?=$tip?>" class="uibutton icon prev">Kembali</a>
</div>
<div class="right">
</div>
</div>
</div>
</td>
</tr>
</table>
</div>
