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

<td class="side-menu">

<legend>Kategori Menu</legend>
<div class="lmenu">
<ul>
<a href="<?php echo site_url("menu/index/1")?>"><li <?php if($tip==1)echo "class='selected'";?>>Menu Statis</li></a>
<a href="<?php echo site_url("kategori")?>"><li <?php if($tip==2)echo "class='selected'";?>>Kategori/Menu Dinamis</li></a>

</ul>
</div>
</td>
<td style="background:#fff;padding:0px;">
<div class="content-header">
<h3>Manajemen Menu</h3>
</div>
<div id="contentpane">
<form id="mainform" name="mainform" action="" method="post">
<div class="ui-layout-north panel">
<div class="left">
<div class="uibutton-group">
<a href="<?php echo site_url("menu/form/$tip")?>" class="uibutton tipsy south" title="Tambah Data" ><span class="fa fa-plus-square">&nbsp;</span>Tambah Menu Baru</a>
<button type="button" title="Hapus Data" onclick="deleteAllBox('mainform','<?php echo site_url("menu/delete_all/$tip/$p/$o")?>')" class="uibutton tipsy south"><span class="fa fa-trash">&nbsp;</span>Hapus Data
</div>
</div>
</div>
<div class="ui-layout-center" id="maincontent" style="padding: 5px;">
<div class="table-panel top">
<div class="left">
<select name="filter" onchange="formAction('mainform','<?php echo site_url('menu/filter')?>')">
<option value="">Semua</option>
<option value="1" <?php if($filter==1) :?>selected<?php endif?>>Enabled</option>
<option value="2" <?php if($filter==2) :?>selected<?php endif?>>Disabled</option>
</select>
</div>
<div class="right">
<input name="cari" id="cari" type="text" class="inputbox help tipped" size="20" value="<?php echo $cari?>" title="Cari.." onkeypress="if (event.keyCode == 13) {$('#'+'mainform').attr('action','<?php echo site_url("menu/search/$tip")?>');$('#'+'mainform').submit();}" />
<button type="button" onclick="$('#'+'mainform').attr('action','<?php echo site_url("menu/search/$tip")?>');$('#'+'mainform').submit();" class="uibutton tipsy south"title="Cari Data"><span class="fa fa-search">&nbsp;</span>Cari</button>
</div>
</div>
<table class="list">
  <thead>
    <tr>
      <th>No</th>
      <th><input type="checkbox" class="checkall"/></th>
      <th width="200">Aksi</th>
      <?php  if($o==2): ?>
        <th align="left"><a href="<?php echo site_url("menu/index/$tip/$p/1")?>">Nama <span class="fa fa-sort-asc fa-sm">
      <?php  elseif($o==1): ?>
        <th align="left"><a href="<?php echo site_url("menu/index/$tip/$p/2")?>">Nama <span class="fa fa-sort-desc fa-sm">
      <?php  else: ?>
        <th align="left"><a href="<?php echo site_url("menu/index/$tip/$p/1")?>">Nama <span class="fa fa-sort fa-sm">
      <?php  endif; ?>&nbsp;</span></a></th>

      <?php  if($o==4): ?>
        <th align="center" width="120"><a href="<?php echo site_url("menu/index/$tip/$p/3")?>">Enabled/Disabled <span class="fa fa-sort-asc fa-sm">
      <?php  elseif($o==3): ?>
        <th align="center" width="120"><a href="<?php echo site_url("menu/index/$tip/$p/4")?>">Enabled/Disabled <span class="fa fa-sort-desc fa-sm">
      <?php  else: ?>
        <th align="center" width="120"><a href="<?php echo site_url("menu/index/$tip/$p/3")?>">Enabled/Disabled <span class="fa fa-sort fa-sm">
      <?php  endif; ?>&nbsp;</span></a></th>
		<th align="center">Link</th>
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
            <?php if($_SESSION['grup']==1): ?>
              <a href="<?php echo site_url("menu/urut/$tip/$data[id]/1")?>" class="uibutton tipsy south" title="Turun"><span class="fa fa-arrow-down"></span></a>
              <a href="<?php echo site_url("menu/urut/$tip/$data[id]/2")?>" class="uibutton tipsy south" title="Naik"><span class="fa fa-arrow-up"></span></a>
            <?php endif; ?>
            <a href="<?php echo site_url("menu/sub_menu/$tip/$data[id]")?>" class="uibutton tipsy south fa-tipis" title="Rincian Sub Menu"><span class="fa fa-bars"></span> Rincian</a>
            <a href="<?php echo site_url("menu/form/$tip/$data[id]")?>" class="uibutton tipsy south" title="Ubah Data"><span class="fa fa-edit"></span></a>
            <a href="<?php echo site_url("menu/delete/$tip/$data[id]")?>" class="uibutton tipsy south" title="Hapus Data" target="confirm" message="Apakah Anda Yakin?" header="Hapus Data"><span class="fa fa-trash"></span></a>
            <?php if($data['enabled'] == '2'):?>
              <a href="<?php echo site_url("menu/menu_lock/$tip/".$data['id'])?>" class="uibutton tipsy south" title="Aktivasi menu"><span class="fa fa-lock"></span></a><?php elseif($data['enabled'] == '1'): ?><a href="<?php echo site_url("menu/menu_unlock/$tip/".$data['id'])?>" class="uibutton tipsy south"  title="Non-aktifkan menu"><span class="fa fa-unlock"></span></a>
              <a href="<?php echo site_url("menu/ajax_add_sub_menu/$tip/$data[id]")?>" class="uibutton tipsy south" target="ajax-modalx" rel="window" header="Tambah Sub Menu <?php echo $data['nama']?>" class="uibutton tipsy south" title="Tambah Sub Menu"><span class="fa fa-plus-circle"></span></a>
            <?php endif?>
              </div>
        </td>
        <td><?php echo $data['nama']?></td>
        <td align="center"><?php echo $data['aktif']?></td>
        <td align="center"><?php echo $data['link']?></td>
      </tr>
    <?php }?>
  </tbody>
</table>
</div>
</form>
<div class="ui-layout-south panel bottom">
<div class="left">
<div class="table-info">
<form id="paging" action="<?php echo site_url('menu')?>" method="post">
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
<a href="<?php echo site_url("menu/index/$paging->start_link/$o")?>" class="uibutton"  ><span class="fa fa-fast-backward"></span> Awal</a>
<?php  endif; ?>
<?php  if($paging->prev): ?>
<a href="<?php echo site_url("menu/index/$paging->prev/$o")?>" class="uibutton">Prev</a>
<?php  endif; ?>
</div>
<div class="uibutton-group">

<?php  for($i=$paging->start_link;$i<=$paging->end_link;$i++): ?>
<a href="<?php echo site_url("menu/index/$i/$o")?>" <?php  jecho($p,$i,"class='uibutton special'")?> class="uibutton"><?php echo $i?></a>
<?php  endfor; ?>
</div>
<div class="uibutton-group">
<?php  if($paging->next): ?>
<a href="<?php echo site_url("menu/index/$paging->next/$o")?>" class="uibutton">Next <span class="fa fa-step-forward"></span></a>
<?php  endif; ?>
<?php  if($paging->end_link): ?>
<a href="<?php echo site_url("menu/index/$paging->end_link/$o")?>" class="uibutton">Akhir <span class="fa fa-fast-forward"></span></a>
<?php  endif; ?>
</div>
</div>
</div>
</div>
</td>
</tr>
</table>
</div>
