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
<td style="background:#fff;padding:0px;">
<div class="content-header">
<h3>Manajemen Komentar</h3>
</div>

<div id="contentpane">
<form id="mainform" name="mainform" action="" method="post">
<div class="ui-layout-north panel">
<div class="left">
<div class="uibutton-group">
<button type="button" title="Hapus Data" onclick="deleteAllBox('mainform','<?php echo site_url("lapor/delete_all/$p/$o")?>')" class="uibutton tipsy south"><span class="fa fa-trash">&nbsp;</span>Hapus Data</button>
</div>
</div>
</div>
<div class="ui-layout-center" id="maincontent" style="padding: 5px;">
<div class="table-panel top">
<div class="left">
<select name="filter" onchange="formAction('mainform','<?php echo site_url('lapor/filter')?>')">
<option value="">Semua</option>
<option value="1" <?php if($filter==1) :?>selected<?php endif?>>Sudah Tindak Lanjut</option>
<option value="2" <?php if($filter==2) :?>selected<?php endif?>>Belum Tindak Lanjut</option>
</select>
</div>
<div class="right">
<input name="cari" id="cari" type="text" class="inputbox help tipped" size="20" value="<?php echo $cari?>" title="Cari.." onkeypress="if (event.keyCode == 13) {$('#'+'mainform').attr('action','<?php echo site_url('lapor/search')?>');$('#'+'mainform').submit();}" />
<button type="button" onclick="$('#'+'mainform').attr('action','<?php echo site_url('lapor/search')?>');$('#'+'mainform').submit();" class="uibutton tipsy south"title="Cari Data"><span class="fa fa-search">&nbsp;</span>Cari</button>
</div>
</div>
<table class="list">
<thead>
<tr>
<th>No</th>
<th><input type="checkbox" class="checkall"/></th>
<th width="80">Aksi</th>

<th style="white-space: nowrap;">Pengirim</th>
<th style="white-space: nowrap;">NIK</th>
 <?php  if($o==2): ?>
<th align="center"><a href="<?php echo site_url("lapor/index/$p/1")?>">Isi Pesan <span class="fa fa-sort-asc fa-sm">
<?php  elseif($o==1): ?>
<th align="center"><a href="<?php echo site_url("lapor/index/$p/2")?>">Isi Pesan <span class="fa fa-sort-desc fa-sm">
<?php  else: ?>
<th align="center"><a href="<?php echo site_url("lapor/index/$p/1")?>">Isi Pesan <span class="fa fa-sort fa-sm">
<?php  endif; ?>&nbsp;</span></a></th>

<?php  if($o==0): ?>
<th align="center" width="150"><a href="<?php echo site_url("lapor/index/$p/3")?>">Sudah Tindak Lanjut <span class="fa fa-sort-asc fa-sm">
<?php  elseif($o==3): ?>
<th align="center" width="150"><a href="<?php echo site_url("lapor/index/$p/4")?>">Sudah Tindak Lanjut <span class="fa fa-sort-desc fa-sm">
<?php  else: ?>
<th align="center" width="150"><a href="<?php echo site_url("lapor/index/$p/3")?>">Sudah Tindak Lanjut <span class="fa fa-sort fa-sm">
<?php  endif; ?>&nbsp;</span></a></th>

<?php  if($o==6): ?>
<th align="left" width='200'><a href="<?php echo site_url("lapor/index/$p/5")?>">Diupload Pada <span class="fa fa-sort-asc fa-sm">
<?php  elseif($o==5): ?>
<th align="left" width='200'><a href="<?php echo site_url("lapor/index/$p/6")?>">Diupload Pada <span class="fa fa-sort-desc fa-sm">
<?php  else: ?>
<th align="left" width='200'><a href="<?php echo site_url("lapor/index/$p/5")?>">Diupload Pada <span class="fa fa-sort fa-sm">
<?php  endif; ?>&nbsp;</span></a></th>

</tr>
</thead>
<tbody>
<?php foreach($main as $data){?>
<tr <?php if($data['enabled']!=1){echo "style='background-color:#ffeeaa;'";}?>>
<td align="center" width="2"><?php echo $data['no']?></td>
<td align="center" width="5">
<input type="checkbox" name="id_cb[]" value="<?php echo $data['id']?>" />
</td>
<td> <div class="uibutton-group">
<a href="<?php echo site_url("lapor/delete/$p/$o/$data[id]")?>" class="uibutton tipsy south fa-tipis" title="Hapus Data" target="confirm" message="Apakah Anda Yakin?" header="Hapus Data"><span class="fa fa-trash"></span> Hapus</a><?php if($data['enabled'] == '2'):?>
<a href="<?php echo site_url('lapor/komentar_lock/'.$data['id'])?>" class="uibutton tipsy south" title="Tindak-lanjuti laporan"><span class="fa fa-check"></span></a><?php elseif($data['enabled'] == '1'): ?>
<a href="<?php echo site_url('lapor/komentar_unlock/'.$data['id'])?>" class="uibutton tipsy south" title="Kembalikan ke status awal"><span class="fa fa-unlock"></span></a>
<?php endif?></div>
</td>
<td style="white-space: nowrap;"><?php echo $data['owner']?></td>
<td style="white-space: nowrap;"><?php echo $data['email']?></td>
<td><?php echo $data['komentar']?></td>
<td align="center"><?php echo $data['aktif']?></td>
<td align="center"><?php echo tgl_indo2($data['tgl_upload'])?></td>
</tr>
<?php }?>
</tbody>
</table>
</div>
</form>
<div class="ui-layout-south panel bottom">
<div class="left">
<div class="table-info">
<form id="paging" action="<?php echo site_url('komentar')?>" method="post">
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
<a href="<?php echo site_url("komentar/index/$paging->start_link/$o")?>" class="uibutton"  ><span class="fa fa-fast-backward"></span> Awal</a>
<?php  endif; ?>
<?php  if($paging->prev): ?>
<a href="<?php echo site_url("komentar/index/$paging->prev/$o")?>" class="uibutton">Prev</a>
<?php  endif; ?>
</div>
<div class="uibutton-group">

<?php  for($i=$paging->start_link;$i<=$paging->end_link;$i++): ?>
<a href="<?php echo site_url("komentar/index/$i/$o")?>" <?php  jecho($p,$i,"class='uibutton special'")?> class="uibutton"><?php echo $i?></a>
<?php  endfor; ?>
</div>
<div class="uibutton-group">
<?php  if($paging->next): ?>
<a href="<?php echo site_url("komentar/index/$paging->next/$o")?>" class="uibutton">Next <span class="fa fa-step-forward"></span></a>
<?php  endif; ?>
<?php  if($paging->end_link): ?>
<a href="<?php echo site_url("komentar/index/$paging->end_link/$o")?>" class="uibutton">Akhir <span class="fa fa-fast-forward"></span></a>
<?php  endif; ?>
</div>
</div>
</div>
</div>
</td>
</tr>
</table>
</div>
