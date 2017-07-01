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
    <h3>Manajemen Galeri Album <?php echo $sub['nama']?></h3>
        <div class="left">
            <div class="uibutton-group">
                <a href="<?php echo site_url("gallery/form_sub_gallery/$gallery")?>" class="uibutton tipsy south" title="Tambah Data" ><span class="fa fa-plus">&nbsp;</span>Tambah Gambar Baru</a>
							<?php if($_SESSION['grup']<4){?>
                <button type="button" title="Hapus Data" onclick="deleteAllBox('mainform','<?php echo site_url("gallery/delete_all_sub_gallery/$p/$o")?>')" class="uibutton tipsy south"><span class="fa fa-trash">&nbsp;</span>Hapus Data
							<?php } ?>
            </div>
        </div>
    </div>
    <div class="ui-layout-center" id="maincontent" style="padding: 5px;">
        <div class="table-panel top">
            <div class="left">
                <select name="filter" onchange="formAction('mainform','<?php echo site_url("gallery/filter/$gallery")?>')">
                    <option value="">Semua</option>
                    <option value="1" <?php if($filter==1) :?>selected<?php endif?>>Aktif</option>
                    <option value="2" <?php if($filter==2) :?>selected<?php endif?>>Non-aktif</option>
                </select>
            </div>
            <div class="right">
                <input name="cari" id="cari" type="text" class="inputbox help tipped" size="20" value="<?php echo $cari?>" title="Cari.." onkeypress="if (event.keyCode == 13) {$('#'+'mainform').attr('action','<?php echo site_url("gallery/search/$gallery")?>');$('#'+'mainform').submit();}" />
                <button type="button" onclick="$('#'+'mainform').attr('action','<?php echo site_url("gallery/search/$gallery")?>');$('#'+'mainform').submit();" class="uibutton tipsy south"  title="Cari Data"><span class="fa fa-search">&nbsp;</span>Cari</button>
            </div>
        </div>
        <table class="list">
		<thead>
            <tr>
                <th>No</th>
                <th><input type="checkbox" class="checkall"/></th>
                <th width="120">Aksi</th>

								<?php  if($o==2): ?>
									<th align="left"><a href="<?php echo site_url("gallery/index/$p/1")?>">Judul Gambar <span class="fa fa-sort-asc fa-sm">
								<?php  elseif($o==1): ?>
									<th align="left"><a href="<?php echo site_url("gallery/index/$p/2")?>">Judul Gambar <span class="fa fa-sort-desc fa-sm">
								<?php  else: ?>
									<th align="left"><a href="<?php echo site_url("gallery/index/$p/1")?>">Judul Gambar <span class="fa fa-sort fa-sm">
								<?php  endif; ?>&nbsp;</span></a></th>

								<?php  if($o==4): ?>
									<th align="left"><a href="<?php echo site_url("gallery/index/$p/3")?>">Aktif / Non-aktif <span class="fa fa-sort-asc fa-sm">
								<?php  elseif($o==3): ?>
									<th align="left"><a href="<?php echo site_url("gallery/index/$p/4")?>">Aktif / Non-aktif <span class="fa fa-sort-desc fa-sm">
								<?php  else: ?>
									<th align="left"><a href="<?php echo site_url("gallery/index/$p/3")?>">Aktif / Non-aktif <span class="fa fa-sort fa-sm">
								<?php  endif; ?>&nbsp;</span></a></th>

								<?php  if($o==6): ?>
									<th align="left" width='180'><a href="<?php echo site_url("gallery/index/$p/5")?>">Diupload pada <span class="fa fa-sort-asc fa-sm">
								<?php  elseif($o==5): ?>
									<th align="left" width='180'><a href="<?php echo site_url("gallery/index/$p/6")?>">Diupload pada <span class="fa fa-sort-desc fa-sm">
								<?php  else: ?>
									<th align="left" width='180'><a href="<?php echo site_url("gallery/index/$p/5")?>">Diupload pada <span class="fa fa-sort fa-sm">
								<?php  endif; ?>&nbsp;</span></a></th>
			</tr>
		</thead>
		<tbody>
        <?php foreach($sub_gallery as $data){?>
		<tr>
			<td align="center" width="2"><?php echo $data['no']?></td>
			<td align="center" width="5">
				<input type="checkbox" name="id_cb[]" value="<?php echo $data['id']?>" />
			</td>
			<td>
			<div class="uibutton-group">
				<a href="<?php echo site_url("gallery/form_sub_gallery/$gallery/$data[id]")?>" class="uibutton tipsy south fa-tipis" title="Ubah Data"><span class="fa fa-edit"></span> Ubah</a>
			<?php if($_SESSION['grup']<4){?>
				<a href="<?php echo site_url("gallery/delete_sub_gallery/$gallery/$data[id]")?>"  class="uibutton tipsy south" title="Hapus Data" target="confirm" message="Apakah Anda Yakin?" header="Hapus Data"><span class="fa fa-trash"></span></a><?php if($data['enabled'] == '2'):?>
				<a href="<?php echo site_url('gallery/gallery_lock/'.$data['id']."/$gallery")?>"  class="uibutton tipsy south" title="Aktifkan gambar"><span class="fa fa-lock"></span></a><?php elseif($data['enabled'] == '1'): ?>
				<a href="<?php echo site_url('gallery/gallery_unlock/'.$data['id']."/$gallery")?>"  class="uibutton tipsy south" title="Non-aktifkan gambar"><span class="fa fa-unlock"></span></a>
			<?php endif?>
        <?php }?>
			</div>
			  </td>
			  <td><label class="tipsy west" title="<img width=200 height=134 src=<?php echo AmbilGaleri($data['gambar'], 'kecil') ?>>"><?php echo $data['nama']?></label></td>
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
          <form id="paging" action="<?php echo site_url('gallery')?>" method="post">
        <a href="<?php echo site_url("gallery/clear")?>" class="uibutton">Kembali</a>
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
				<a href="<?php echo site_url("gallery/index/$paging->start_link/$o")?>" class="uibutton"  ><span class="fa fa-fast-backward"></span> Awal</a>
			<?php  endif; ?>
			<?php  if($paging->prev): ?>
				<a href="<?php echo site_url("gallery/index/$paging->prev/$o")?>" class="uibutton"  ><span class="fa fa-step-backward"></span> Prev</a>
			<?php  endif; ?>
            </div>
            <div class="uibutton-group">

				<?php  for($i=$paging->start_link;$i<=$paging->end_link;$i++): ?>
				<a href="<?php echo site_url("gallery/index/$i/$o")?>" <?php  jecho($p,$i,"class='uibutton special'")?> class="uibutton"><?php echo $i?></a>
				<?php  endfor; ?>
            </div>
            <div class="uibutton-group">
			<?php  if($paging->next): ?>
				<a href="<?php echo site_url("gallery/index/$paging->next/$o")?>" class="uibutton">Next <span class="fa fa-step-forward"></span></a>
			<?php  endif; ?>
			<?php  if($paging->end_link): ?>
                <a href="<?php echo site_url("gallery/index/$paging->end_link/$o")?>" class="uibutton">Akhir <span class="fa fa-fast-forward"></span></a>
			<?php  endif; ?>
            </div>
        </div>
    </div>
</div>
</td>
</tr>
</table>
</div>