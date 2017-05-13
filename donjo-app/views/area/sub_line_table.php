
<div id="pageC">
<table class="inner">
<tr style="vertical-align:top">
<?php /*
<td class="side-area">

<fieldset>
<legend>Kategori area</legend>
<div class="larea">
<ul>
<li <?php if($tip==1)echo "class='selected'";?>><a href="<?php echo site_url("area/index/1")?>">Atas</a></li>
<li <?php if($tip==2)echo "class='selected'";?>><a href="<?php echo site_url("area/index/2")?>">Atas Kiri</a></li>

<?php /*
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
<h3>Manajemen Sub area</h3>
</div>
<div id="contentpane">
<form id="mainform" name="mainform" action="" method="post">
<div class="ui-layout-north panel">
<div class="left">
<div class="uibutton-group">
<a href="<?php echo site_url("area/ajax_add_sub_area/$area")?>" target="ajax-modal" rel="window" header="Tambah Sub area" class="uibutton tipsy south" title="Tambah Sub area"><span class="ui-icon ui-fa fa-plus-square">&nbsp;</span>Tambah area Baru</a>
<button type="button" title="Delete Data" onclick="deleteAllBox('mainform','<?php echo site_url("area/delete_all/")?>')" class="uibutton tipsy south"><span class="ui-icon ui-fa fa-trash">&nbsp;</span>Delete Data
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
<th width="50">Aksi</th>
<th align="center">Nama</th>
<th align="center">Enabled</th>
<th>Simbol</th>
<th></th>
</tr>
</thead>
<tbody>
<?php foreach($subarea as $data){?>
<tr>
<td align="center" width="2"><?php echo $data['no']?></td>
<td align="center" width="5">
<input type="checkbox" name="id_cb[]" value="<?php echo $data['id']?>" />
</td>
<td>
<a href="<?php echo site_url("area/ajax_add_sub_area/$area/$data[id]")?>" class="ui-icons fa fa-edit tipsy south" target="ajax-modal" rel="window" header="Edit area" title="Edit Data"></a><a href="<?php echo site_url("area/delete_sub_area/$area/$data[id]")?>" class="ui-icons fa fa-remove tipsy south" title="Delete Data" target="confirm" message="Apakah Anda Yakin?" header="Hapus Data"></a><?php if($data['enabled'] == '2'):?><a href="<?php echo site_url("area/area_lock_sub_area/$area/$data[id]")?>" class="ui-icons fa fa-lock tipsy south" title="Enable area"></a><?php elseif($data['enabled'] == '1'): ?><a href="<?php echo site_url("area/area_unlock_sub_area/$area/$data[id]")?>" class="ui-icons fa fa-unlock tipsy south" title="Disable area"></a><?php endif;?>
</td>
<td width="150"><?php echo $data['nama']?></td>
<td width="50"><?php echo $data['aktif']?></td>
<td align="center" width="50"><img src="<?php echo base_url(LOKASI_FOTO_AREA)?>/<?php echo $data['simbol']?>"></td>
<td></td>
<?php }?>
</tbody>
</table>
</div>
</form>
<div class="ui-layout-south panel bottom">
<div class="left">
<a href="<?php echo site_url()?>area/index/1" class="uibutton icon prev">Kembali</a>
</div>
<div class="right">
</div>
</div>
</div>
</td>
</tr>
</table>
</div>
