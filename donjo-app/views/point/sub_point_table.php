<div id="pageC">
<table class="inner">
<tr style="vertical-align:top">
<?php  /*
<td class="side-point">

<fieldset>
<legend>Kategori point</legend>
<div class="lpoint">
<ul>
<li <?php  if($tip==1)echo "class='selected'";?>><a href="<?php  echo site_url("point/index/1")?>">Atas</a></li>
<li <?php  if($tip==2)echo "class='selected'";?>><a href="<?php  echo site_url("point/index/2")?>">Atas Kiri</a></li>

<?php  /*
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
<a href="<?php  echo site_url("point/ajax_add_sub_point/$point")?>" target="ajax-modalc" rel="window" header="Tambah Sub point" class="uibutton tipsy south" title="Tambah point"><span class="fa fa-plus-square">&nbsp;</span>Tambah point Baru</a>
<button type="button" title="Hapus Data" onclick="deleteAllBox('mainform','<?php  echo site_url("point/delete_all/")?>')" class="uibutton tipsy south"><span class="fa fa-trash">&nbsp;</span>Hapus Data</button></button>
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
<th width="100">Aksi</th>
<th align="center">Nama</th>
<th align="center">Enabled</th>
<th>Simbol</th>
<th></th>
</tr>
</thead>
<tbody>
<?php  foreach($subpoint as $data){?>
<tr>
<td align="center" width="2"><?php  echo $data['no']?></td>
<td align="center" width="5">
<input type="checkbox" name="id_cb[]" value="<?php  echo $data['id']?>" />
</td>
<td>
	<div class="uibutton-group">
		<a href="<?php  echo site_url("point/ajax_add_sub_point/$point/$data[id]")?>" class="uibutton tipsy south fa fa-edit" target="ajax-modalc" rel="window" header="Edit Point" title="Ubah Data"></a>
		<a href="<?php  echo site_url("point/delete_sub_point/$point/$data[id]")?>" class="uibutton tipsy south fa fa-trash" title="Hapus Data" target="confirm" message="Apakah Anda Yakin?" header="Hapus Data"></a>
		<?php  if($data['enabled'] == '2'):?>
			<a href="<?php  echo site_url("point/point_lock_sub_point/$point/$data[id]")?>" class="uibutton tipsy south fa fa-lock" title="Aktifkan point"></a>
		<?php  elseif($data['enabled'] == '1'): ?>
			<a href="<?php  echo site_url("point/point_unlock_sub_point/$point/$data[id]")?>" class="uibutton tipsy south fa fa-unlock" title="Non-aktifkan point"></a>
		<?php  endif;?>
	</div>
</td>
<td width="150"><?php  echo $data['nama']?></td>
<td width="50"><?php  echo $data['aktif']?></td>
<td align="center" width="50"><img src="<?php  echo base_url("assets/images/gis/point")?>/<?php  echo $data['simbol']?>"></td>
<td></td>
<?php  }?>
</tbody>
</table>
</div>
</form>
<div class="ui-layout-south panel bottom">
<div class="left">
<a href="<?php  echo site_url()?>point/index/1" class="uibutton icon prev">Kembali</a>
</div>
<div class="right">
</div>
</div>
</div>
</td>
</tr>
</table>
</div>
