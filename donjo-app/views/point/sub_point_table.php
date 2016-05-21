
<div id="pageC">
<table class="inner">
<tr style="vertical-align:top">
<?/*
<td class="side-point">

<fieldset>
<legend>Kategori point</legend>
<div class="lpoint">
<ul>
<li <?if($tip==1)echo "class='selected'";?>><a href="<?=site_url("point/index/1")?>">Atas</a></li>
<li <?if($tip==2)echo "class='selected'";?>><a href="<?=site_url("point/index/2")?>">Atas Kiri</a></li>

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
<h3>Manajemen Sub point</h3>
</div>
<div id="contentpane">
<form id="mainform" name="mainform" action="" method="post">
<div class="ui-layout-north panel">
<div class="left">
<div class="uibutton-group">
<a href="<?=site_url("point/ajax_add_sub_point/$point")?>" target="ajax-modal" rel="window" header="Tambah Sub point" class="uibutton tipsy south" title="Tambah Sub point"><span class="ui-icon ui-icon-plus">&nbsp;</span>Tambah point Baru</a>
<button type="button" title="Delete Data" onclick="deleteAllBox('mainform','<?=site_url("point/delete_all/")?>')" class="uibutton tipsy south"><span class="ui-icon ui-icon-trash">&nbsp;</span>Delete Data
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
<?foreach($subpoint as $data){?>
<tr>
<td align="center" width="2"><?=$data['no']?></td>
<td align="center" width="5">
<input type="checkbox" name="id_cb[]" value="<?=$data['id']?>" />
</td>
<td>
<a href="<?=site_url("point/ajax_add_sub_point/$point/$data[id]")?>" class="ui-icons icon-edit tipsy south" target="ajax-modal" rel="window" header="Edit Point" title="Edit Data"></a><a href="<?=site_url("point/delete_sub_point/$point/$data[id]")?>" class="ui-icons icon-remove tipsy south" title="Delete Data" target="confirm" message="Apakah Anda Yakin?" header="Hapus Data"></a><?if($data['enabled'] == '2'):?><a href="<?=site_url("point/point_lock_sub_point/$point/$data[id]")?>" class="ui-icons icon-lock tipsy south" title="Enable point"></a><?elseif($data['enabled'] == '1'): ?><a href="<?=site_url("point/point_unlock_sub_point/$point/$data[id]")?>" class="ui-icons icon-unlock tipsy south" title="Disable point"></a><?endif;?>
</td>
<td width="150"><?=$data['nama']?></td>
<td width="50"><?=$data['aktif']?></td>
<td align="center" width="50"><img src="<?=base_url("assets/images/gis/point")?>/<?=$data['simbol']?>"></td>
<td></td>
<?}?>
</tbody>
</table>
</div>
</form>
<div class="ui-layout-south panel bottom">
<div class="left"> 
<a href="<?=site_url()?>point/index/1" class="uibutton icon prev">Kembali</a>
</div>
<div class="right">
</div>
</div>
</div>
</td>
</tr>
</table>
</div>
