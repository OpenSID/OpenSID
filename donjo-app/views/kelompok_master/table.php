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
</div>
<div id="contentpane">
	<form id="mainform" name="mainform" action="" method="post">
    <div class="ui-layout-north panel">
    <h3>Modul kelompok</h3>
        <div class="left">
            <div class="uibutton-group">
                <a href="<?php echo site_url('kelompok/clear')?>" class="uibutton tipsy south" title="Kelompok" ><span class="fa fa-list">&nbsp;</span>Kelompok</a>
                <a href="<?php echo site_url('kelompok_master/form')?>" class="uibutton tipsy south" title="Tambah Data" ><span class="fa fa-plus-square">&nbsp;</span>Tambah Master Kelompok Baru</a>
                <button type="button" title="Hapus Data" onclick="deleteAllBox('mainform','<?php echo site_url("kelompok_master/delete_all/$p/$o")?>')" class="uibutton tipsy south"><span class="fa fa-trash">&nbsp;</span>Hapus Data
            </div>
        </div>
            <div class="right">
                <input name="cari" id="cari" type="text" class="inputbox help tipped" size="40" value="<?php echo $cari?>" title="Cari.." onkeypress="if (event.keyCode == 13) {$('#'+'mainform').attr('action','<?php echo site_url('kelompok_master/search')?>');$('#'+'mainform').submit();}" />
                <button type="button" onclick="$('#'+'mainform').attr('action','<?php echo site_url('kelompok_master/search')?>');$('#'+'mainform').submit();" class="uibutton tipsy south"  title="Cari Data"><span class="fa fa-search">&nbsp;</span>Cari</button>
            </div>
    </div>
    <div class="ui-layout-center" id="maincontent" style="padding: 5px;">
        <div class="table-panel top">
            <div class="left">
            </div>
        </div>
        <table class="list">
		<thead>
            <tr>
                <th width="10">No</th>
                <th><input type="checkbox" class="checkall"/></th>
                <th width="100">Aksi</th>

	 		<?php  if($o==4): ?>
				<th align="left" width="200"><a href="<?php echo site_url("kelompok_master/index/$p/3")?>">Kelompok <span class="fa fa-sort-asc fa-sm">&nbsp;</span></a></th>
			<?php  elseif($o==3): ?>
				<th align="left" width="200"><a href="<?php echo site_url("kelompok_master/index/$p/4")?>">Kelompok <span class="fa fa-sort-desc fa-sm">&nbsp;</span></a></th>
			<?php  else: ?>
				<th align="left" width="200"><a href="<?php echo site_url("kelompok_master/index/$p/3")?>">Kelompok <span class="fa fa-sort fa-sm">&nbsp;</span></a></th>
			<?php  endif; ?>

                <th>Deskripsi Kelompok</th>
			</tr>
		</thead>
		<tbody>
        <?php  foreach($main as $data): ?>
		<tr>
          <td align="center" width="2"><?php echo $data['no']?></td>
			<td align="center" width="5">
				<input type="checkbox" name="id_cb[]" value="<?php echo $data['id']?>" />
			</td>
          <td><div class="uibutton-group">
            <a href="<?php echo site_url("kelompok_master/form/$p/$o/$data[id]")?>" class="uibutton tipsy south fa-tipis" title="Ubah Data"><span class="fa fa-edit"></span> Ubah</a><a href="<?php echo site_url("kelompok_master/delete/$p/$o/$data[id]")?>" class="uibutton tipsy south" title="Hapus Data" target="confirm" message="Apakah Anda Yakin?" header="Hapus Data"><span class="fa fa-trash"></span></a>
			</div>
          </td>
          <td><?php echo $data['kelompok']?></td>
		  <td><?php echo $data['deskripsi']?></td>
		  </tr>
        <?php  endforeach; ?>
		</tbody>
        </table>
    </div>
	</form>
    <div class="ui-layout-south panel bottom">
        <div class="left">
		<div class="table-info">
          <form id="paging" action="<?php echo site_url('kelompok_master')?>" method="post">
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
				<a href="<?php echo site_url("kelompok_master/index/$paging->start_link/$o")?>" class="uibutton"  ><span class="fa fa-fast-backward"></span> Awal</a>
			<?php  endif; ?>
			<?php  if($paging->prev): ?>
				<a href="<?php echo site_url("kelompok_master/index/$paging->prev/$o")?>" class="uibutton"  ><span class="fa fa-step-backward"></span> Prev</a>
			<?php  endif; ?>
            </div>
            <div class="uibutton-group">

				<?php  for($i=$paging->start_link;$i<=$paging->end_link;$i++): ?>
				<a href="<?php echo site_url("kelompok_master/index/$i/$o")?>" <?php  jecho($p,$i,"class='uibutton special'")?> class="uibutton"><?php echo $i?></a>
				<?php  endfor; ?>
            </div>
            <div class="uibutton-group">
			<?php  if($paging->next): ?>
				<a href="<?php echo site_url("kelompok_master/index/$paging->next/$o")?>" class="uibutton">Next <span class="fa fa-step-forward"></span></a>
			<?php  endif; ?>
			<?php  if($paging->end_link): ?>
                <a href="<?php echo site_url("kelompok_master/index/$paging->end_link/$o")?>" class="uibutton">Akhir <span class="fa fa-fast-forward"></span></a>
			<?php  endif; ?>
            </div>
        </div>
    </div>
</div>
</td></tr></table>
</div>
