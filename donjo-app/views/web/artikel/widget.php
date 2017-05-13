<script>
$(function() {
var keyword = <?php //=$keyword?> ;
$( "#cari" ).autocomplete({
source: keyword
});
});
</script>

<div id="pageC">
<table class="inner">
<tr style="vertical-align:top">

<td style="background:#fff;padding:0px;">
<div class="content-header">
  <?php

  //echo var_dump($kategori);
  ?>
</div>
<div id="contentpane">
<form id="mainform" name="mainform" action="" method="post">
<div class="ui-layout-north panel">
<div class="left">
<div class="uibutton-group">
<a href="<?php echo site_url("web/form/$cat")?>" class="uibutton tipsy south" title="Tambah Data" ><span class="fa fa-plus-square ">&nbsp;</span>Tambah Widget Baru</a>
<?php if($_SESSION['grup']<4){?>
<button type="button" title="Hapus Widget" onclick="deleteAllBox('mainform','<?php echo site_url("web/delete_all/$cat/$p/$o")?>')" class="uibutton tipsy south"><span class="fa fa-trash ">&nbsp;</span>Hapus
<?php }?>
</div>
</div>
</div>
<div class="ui-layout-center" id="maincontent" style="padding: 5px;">
<div class="table-panel top">
<div class="left">
<select name="filter" onchange="formAction('mainform','<?php echo site_url("web/filter/$cat")?>')">
<option value="">Semua</option>
<option value="1" <?php if($filter==1) :?>selected<?php endif?>>Aktif</option>
<option value="2" <?php if($filter==2) :?>selected<?php endif?>>Non-aktif</option>
</select>
</div>
<div class="right">
<input name="cari" id="cari" type="text" class="inputbox help tipped" size="20" value="<?php echo $cari?>" title="Cari.." onkeypress="if (event.keyCode == 13) {$('#'+'mainform').attr('action','<?php echo site_url("web/search/$cat")?>');$('#'+'mainform').submit();}" />
<button type="button" onclick="$('#'+'mainform').attr('action','<?php echo site_url("web/search/$cat")?>');$('#'+'mainform').submit();" class="uibutton tipsy south"title="Cari Data"><span class="fa fa-search ">&nbsp;</span>Cari</button>
</div>
</div>

<table class="list">
  <thead>
    <tr>
      <th>No</th>
      <th><input type="checkbox" class="checkall"/></th>
      <th width="160">Aksi</th>
      <th align="left">Judul</th>
      <th align="left">Jenis Widget</th>
      <th align="left">Aktif / Non-aktif</th>
      <th align="left" width='250'>Diposting Pada</th>
    </tr>
  </thead>
  <tbody>
    <?php  foreach($main as $data){?>
      <tr <?php if($data['jenis_widget']==1){echo "style='background-color:#ffeeaa;'";}?>>
        <td align="center" width="2"><?php echo $data['no']?></td>
        <td align="center" width="5">
          <input type="checkbox" name="id_cb[]" value="<?php echo $data['id']?>" />
        </td>
        <td>
          <div class="uibutton-group">
            <a href="<?php echo site_url("web/widget_urut/$data[id]/1")?>" class="uibutton tipsy south" title="Turun"><span class="fa fa-arrow-down "></span></a>
            <a href="<?php echo site_url("web/widget_urut/$data[id]/2")?>" class="uibutton tipsy south" title="Naik"><span class="fa fa-arrow-up "></span></a>
            <?php if($data['jenis_widget']!=1):?>
              <a href="<?php echo site_url("web/form/$cat/$p/$o/$data[id]")?>" class="uibutton tipsy south" title="Ubah Data"><span class="fa fa-edit "> Ubah </span></a>
            <?php  endif?>
            <?php if($_SESSION['grup']<4){?>
              <?php if($data['jenis_widget']!=1):?>
                <a href="<?php echo site_url("web/delete/$cat/$p/$o/$data[id]")?>" class="uibutton tipsy south" title="Hapus Data" target="confirm" message="Apakah Anda Yakin?" header="Hapus Data"><span  class="fa fa-trash "></span></a>
              <?php  endif?>
              <?php  if($data['enabled'] == '2'):?>
                <a href="<?php echo site_url("web/artikel_lock/$cat/$data[id]")?>" class="uibutton tipsy south" title="Aktivasi artikel"><span class="fa fa-lock "></span></a>
              <?php  elseif($data['enabled'] == '1'): ?>
                <a href="<?php echo site_url("web/artikel_unlock/$cat/$data[id]")?>" class="uibutton tipsy south" title="Non-aktifkan artikel"><span class="fa fa-unlock "></span></a>
              <?php  endif?>
            <?php } ?>

          </div>
        </td>
        <td><?php echo $data['judul']?></td>
        <td>
          <?php if($data['jenis_widget'] == 1): ?>
            Sistem
          <?php elseif($data['jenis_widget'] == 2): ?>
            Statis
          <?php else: ?>
            Dinamis
          <?php endif ?>
        <td><?php echo $data['aktif']?></td>
        <td><?php echo tgl_indo2($data['tgl_upload'])?></td>
      </tr>
    <?php }?>
  </tbody>
</table>
</div>
</form>
<div class="ui-layout-south panel bottom">
<div class="left">
<div class="table-info">
<form id="paging" action="<?php echo site_url("web/pager/$cat")?>" method="post">
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
<a href="<?php echo site_url("web/widget/$paging->start_link/$o")?>" class="uibutton">Awal</a>
<?php  endif; ?>
<?php  if($paging->prev): ?>
<a href="<?php echo site_url("web/widget/$paging->prev/$o")?>" class="uibutton">Prev</a>
<?php  endif; ?>
</div>
<div class="uibutton-group">

<?php  for($i=$paging->start_link;$i<=$paging->end_link;$i++): ?>
<a href="<?php echo site_url("web/widget/$i/$o")?>" <?php  jecho($p,$i,"class='uibutton special'")?> class="uibutton"><?php echo $i?></a>
<?php  endfor; ?>
</div>
<div class="uibutton-group">
<?php  if($paging->next): ?>
<a href="<?php echo site_url("web/widget/$paging->next/$o")?>" class="uibutton">Next</a>
<?php  endif; ?>
<?php  if($paging->end_link): ?>
<a href="<?php echo site_url("web/widget/$paging->end_link/$o")?>" class="uibutton">Akhir</a>
<?php  endif; ?>
</div>
</div>
</div>
</div>
</td>
</tr>
</table>
</div>
