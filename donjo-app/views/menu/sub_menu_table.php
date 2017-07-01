
<div id="pageC">
<table class="inner">
<tr style="vertical-align:top">

<td class="side-menu">

<fieldset>
<legend>Kategori Menu</legend>
<div class="lmenu">
<ul>
<li <?php if($tip==1)echo "class='selected'";?>><a href="<?php echo site_url("menu/index/1")?>">Statis</a></li>
<li <?php if($tip==2)echo "class='selected'";?>><a href="<?php echo site_url("menu/index/2")?>">Dinamis</a></li>


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
<a href="<?php echo site_url("menu/ajax_add_sub_menu/$tip/$menu")?>" target="ajax-modalx" rel="window" header="Tambah Sub Menu" class="uibutton tipsy south" title="Tambah Sub Menu"><span class="fa fa-plus">&nbsp;</span>Tambah Menu Baru</a>
<button type="button" title="Hapus Data" onclick="deleteAllBox('mainform','<?php echo site_url("menu/delete_all_sub_menu/$tip/$menu")?>')" class="uibutton tipsy south"><span class="fa fa-trash">&nbsp;</span>Hapus Data
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
      <th>Aksi</th>
      <th align="center">Nama</th>
      <th align="center">Enabled</th>
      <th>Link</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach($submenu as $data){?>
      <tr>
        <td align="center" width="2"><?php echo $data['no']?></td>
        <td align="center" width="5">
          <input type="checkbox" name="id_cb[]" value="<?php echo $data['id']?>" />
        </td>
        <td>
          <div class="uibutton-group">
            <?php if($_SESSION['grup']==1): ?>
              <a href="<?php echo site_url("menu/urut/$tip/$data[id]/1/$menu")?>" class="uibutton tipsy south" title="Turun"><span class="fa fa-arrow-down"></span></a>
              <a href="<?php echo site_url("menu/urut/$tip/$data[id]/2/$menu")?>" class="uibutton tipsy south" title="Naik"><span class="fa fa-arrow-up"></span></a>
            <?php endif; ?>
            <a href="<?php echo site_url("menu/ajax_add_sub_menu/$tip/$menu/$data[id]")?>"  class="uibutton tipsy south fa-tipis" target="ajax-modalx" rel="window" header="Edit Menu" title="Ubah Data"><span class="fa fa-edit"></span> Ubah</a>
            <a href="<?php echo site_url("menu/delete_sub_menu/$tip/$menu/$data[id]")?>"  class="uibutton tipsy south" title="Hapus Data" target="confirm" message="Apakah Anda Yakin?" header="Hapus Data"><span class="fa fa-trash"></span></a>
            <?php if($data['enabled'] == '2'):?>
              <a href="<?php echo site_url("menu/menu_lock_sub_menu/$tip/$menu/$data[id]")?>"  class="uibutton tipsy south" title="Enable menu"><span class="fa fa-lock"></span></a>
            <?php elseif($data['enabled'] == '1'): ?>
              <a href="<?php echo site_url("menu/menu_unlock_sub_menu/$tip/$menu/$data[id]")?>"  class="uibutton tipsy south" title="Disable menu"><span class="fa fa-unlock"></span></a>
            <?php endif;?>
          </div>
        </td>
        <td><?php echo $data['nama']?></td>
        <td><?php echo $data['aktif']?></td>
        <td><?php echo $data['link']?></td>
      </tr>
    <?php }?>
  </tbody>
</table>
  </div>
  </form>
  <div class="ui-layout-south panel bottom">
  <div class="left">
  <a href="<?php echo site_url()?>menu/index/<?php echo $tip?>" class="uibutton icon prev">Kembali</a>
  </div>
  <div class="right">
  </div>
  </div>
  </div>
  </td>
  </tr>
</table>
</div>
