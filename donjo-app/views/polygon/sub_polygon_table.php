
<div id="pageC">
<table class="inner">
<tr style="vertical-align:top">
<?/*
<td class="side-polygon">

<fieldset>
<legend>Kategori polygon</legend>
<div class="lpolygon">
<ul>
<li <?if($tip==1)echo "class='selected'";?>><a href="<?=site_url("polygon/index/1")?>">Atas</a></li>
<li <?if($tip==2)echo "class='selected'";?>><a href="<?=site_url("polygon/index/2")?>">Atas Kiri</a></li>

<?/*
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
<h3>Manajemen Sub polygon</h3>
</div>
<div id="contentpane">
<form id="mainform" name="mainform" action="" method="post">
<div class="ui-layout-north panel">
<div class="left">
<div class="uibutton-group">
<a href="<?=site_url("polygon/ajax_add_sub_polygon/$polygon")?>" class="uibutton tipsy south" title="Tambah Sub polygon"><span class="ui-icon ui-icon-plus">&nbsp;</span>Tambah polygon Baru</a>
<button type="button" title="Delete Data" onclick="deleteAllBox('mainform','<?=site_url("polygon/delete_all/")?>')" class="uibutton tipsy south"><span class="ui-icon ui-icon-trash">&nbsp;</span>Delete Data
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
<th width="100">Warna</th>
<th>Simbol</th>
<th></th>
</tr>
</thead>
<tbody>
<?foreach($subpolygon as $data){?>
<tr>
<td align="center" width="2"><?=$data['no']?></td>
<td align="center" width="5">
<input type="checkbox" name="id_cb[]" value="<?=$data['id']?>" />
</td>
<td>
<a href="<?=site_url("polygon/ajax_add_sub_polygon/$polygon/$data[id]")?>" class="ui-icons icon-edit tipsy south" title="Edit Data"></a><a href="<?=site_url("polygon/delete_sub_polygon/$polygon/$data[id]")?>" class="ui-icons icon-remove tipsy south" title="Delete Data" target="confirm" message="Apakah Anda Yakin?" header="Hapus Data"></a><?if($data['enabled'] == '2'):?><a href="<?=site_url("polygon/polygon_lock_sub_polygon/$polygon/$data[id]")?>" class="ui-icons icon-lock tipsy south" title="Enable polygon"></a><?elseif($data['enabled'] == '1'): ?><a href="<?=site_url("polygon/polygon_unlock_sub_polygon/$polygon/$data[id]")?>" class="ui-icons icon-unlock tipsy south" title="Disable polygon"></a><?endif;?>
</td>
<td width="150"><?=$data['nama']?></td>
<td width="50"><?=$data['aktif']?></td>
<td ><div style="background-color:#<?=$data['color']?>">&nbsp;<div></td>
<td align="center" width="50"><img src="<?=base_url("assets/images/gis/polygon")?>/<?=$data['simbol']?>"></td>
<td></td>
<?}?>
</tbody>
</table>
</div>
</form>
<div class="ui-layout-south panel bottom">
<div class="left"> 
<a href="<?=site_url()?>polygon/index/1" class="uibutton icon prev">Kembali</a>
</div>
<div class="right">
</div>
</div>
</div>
</td>
</tr>
</table>
</div>
