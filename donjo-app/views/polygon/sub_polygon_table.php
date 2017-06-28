
<div id="pageC">
<table class="inner">
<tr style="vertical-align:top">
<?php /*
<td class="side-polygon">

<fieldset>
<legend>Kategori polygon</legend>
<div class="lpolygon">
<ul>
<li <?php if($tip==1)echo "class='selected'";?>><a href="<?php echo site_url("polygon/index/1")?>">Atas</a></li>
<li <?php if($tip==2)echo "class='selected'";?>><a href="<?php echo site_url("polygon/index/2")?>">Atas Kiri</a></li>

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
<h3>Manajemen Sub polygon</h3>
</div>
<div id="contentpane">
<form id="mainform" name="mainform" action="" method="post">
<div class="ui-layout-north panel">
<div class="left">
<div class="uibutton-group">
<a href="<?php echo site_url("polygon/ajax_add_sub_polygon/$polygon")?>" class="uibutton tipsy south" title="Tambah Sub polygon"><span class="fa fa-plus-square">&nbsp;</span>Tambah polygon Baru</a>
<button type="button" title="Hapus Data" onclick="deleteAllBox('mainform','<?php echo site_url("polygon/delete_all/")?>')" class="uibutton tipsy south"><span class="fa fa-trash">&nbsp;</span>Hapus Data</button>
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
<th width="60">Aksi</th>
<th align="center">Nama</th>
<th align="center">Enabled</th>
<th width="100">Warna</th>
<th></th>
</tr>
</thead>
<tbody>
<?php foreach($subpolygon as $data){?>
<tr>
<td align="center" width="2"><?php echo $data['no']?></td>
<td align="center" width="5">
<input type="checkbox" name="id_cb[]" value="<?php echo $data['id']?>" />
</td>
<td>
	<div class="uibutton-group">
		<a href="<?php echo site_url("polygon/ajax_add_sub_polygon/$polygon/$data[id]")?>" class="uibutton tipsy south" title="Ubah Data"><span class="fa fa-edit"></span></a>
		<a href="<?php echo site_url("polygon/delete_sub_polygon/$polygon/$data[id]")?>" class="uibutton tipsy south" title="Hapus Data" target="confirm" message="Apakah Anda Yakin?" header="Hapus Data"><span class="fa fa-trash"></span></a>
		<?php if($data['enabled'] == '2'):?><a href="<?php echo site_url("polygon/polygon_lock_sub_polygon/$polygon/$data[id]")?>" class="uibutton tipsy south" title="Aktifkan polygon"><span class="fa fa-lock"></span></a><?php elseif($data['enabled'] == '1'): ?><a href="<?php echo site_url("polygon/polygon_unlock_sub_polygon/$polygon/$data[id]")?>" class="uibutton tipsy south" title="Non-aktifkan polygon"><span class="fa fa-unlock"></span></a><?php endif;?>
	</div>
</td>
<td width="150"><?php echo $data['nama']?></td>
<td width="50"><?php echo $data['aktif']?></td>
<td ><div style="background-color:#<?php echo $data['color']?>">&nbsp;<div></td>
<td></td>
<?php }?>
</tbody>
</table>
</div>
</form>
<div class="ui-layout-south panel bottom">
<div class="left">
<a href="<?php echo site_url()?>polygon/index/1" class="uibutton icon prev">Kembali</a>
</div>
<div class="right">
</div>
</div>
</div>
</td>
</tr>
</table>
</div>
