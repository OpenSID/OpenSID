<script>
	$(function() {
		var keyword = <?php echo $keyword?> ;
		$( "#cari" ).autocomplete({
			source: keyword
		});
	});
</script>
<div id="pageC">
<div class="content-header">
</div>
<div id="contentpane">
	<form id="mainform" name="mainform" action="" method="post">
 <div class="ui-layout-north panel">
 <h3>Manajemen Periode Analisis - <a href="<?php echo site_url()?>analisis_master/menu/<?php echo $_SESSION['analisis_master']?>"><?php echo $analisis_master['nama']?></a></h3>
 <div class="left">
 <div class="uibutton-group">
 <a href="<?php echo site_url('analisis_periode/form')?>" class="uibutton tipsy south" title="Tambah Data" ><span class="fa fa-plus-square">&nbsp;</span>Tambah Periode Baru</a>
 <button type="button" title="Hapus Data" onclick="deleteAllBox('mainform','<?php echo site_url("analisis_periode/delete_all/$p/$o")?>')" class="uibutton tipsy south"><span class="fa fa-trash">&nbsp;</span>Hapus Data</button>
 </div>
 </div>
 </div>
 <div class="ui-layout-center" id="maincontent" style="padding: 5px;">
 <div class="table-panel top">
 <div class="left">
 <select name="state" onchange="formAction('mainform','<?php echo site_url('analisis_periode/state')?>')">
 <option value="">-- Filter by Status Analisis --</option>
					<?php foreach($list_state AS $data){?>
					<option value="<?php echo $data['id']?>" <?php if($state == $data['id']) :?>selected<?php endif?>><?php echo $data['nama']?></option>
					<?php }?>
 </select>
 </div>
 <div class="right">
 <input name="cari" id="cari" type="text" class="inputbox help tipped" size="20" value="<?php echo $cari?>" title="Cari.." onkeypress="if (event.keyCode == 13) {$('#'+'mainform').attr('action','<?php echo site_url('analisis_periode/search')?>');$('#'+'mainform').submit();}" />
 <button type="button" onclick="$('#'+'mainform').attr('action','<?php echo site_url('analisis_periode/search')?>');$('#'+'mainform').submit();" class="uibutton tipsy south" title="Cari Data"><span class="fa fa-search">&nbsp;</span>Cari</button>
 </div>
 </div>
 <table class="list">
		<thead>
 <tr>
 <th width="10">No</th>
 <th><input type="checkbox" class="checkall"/></th>
 <th width="100">Aksi</th>

	 		<?php if($o==4): ?>
				<th  align="center" width='150'><a href="<?php echo site_url("analisis_periode/index/$p/3")?>">Periode <span class="fa fa-sort-asc fa-sm">&nbsp;</span></a></th>
			<?php elseif($o==3): ?>
				<th  align="center" width='150'><a href="<?php echo site_url("analisis_periode/index/$p/4")?>">Periode <span class="fa fa-sort-desc fa-sm">&nbsp;</span></a></th>
			<?php else: ?>
				<th  align="center" width='150'><a href="<?php echo site_url("analisis_periode/index/$p/3")?>">Periode <span class="fa fa-sort fa-sm">&nbsp;</span></a></th>
			<?php endif; ?>

	 		<?php if($o==4): ?>
				<th align="center" width='150'><a href="<?php echo site_url("analisis_periode/index/$p/3")?>">Tahun Pelaksanaan <span class="fa fa-sort-asc fa-sm">&nbsp;</span></a></th>
			<?php elseif($o==3): ?>
				<th align="center" width='150'><a href="<?php echo site_url("analisis_periode/index/$p/4")?>">Tahun Pelaksanaan <span class="fa fa-sort-desc fa-sm">&nbsp;</span></a></th>
			<?php else: ?>
				<th align="center" width='150'><a href="<?php echo site_url("analisis_periode/index/$p/3")?>">Tahun Pelaksanaan <span class="fa fa-sort fa-sm">&nbsp;</span></a></th>
			<?php endif; ?>

 <th>Tahap Pendataan</th>
 <th>Keterangan</th>
 <th>Aktif</th>
			</tr>
		</thead>
		<tbody>
 <?php foreach($main as $data): ?>
		<tr>
 <td align="center" width="2"><?php echo $data['no']?></td>
			<td align="center" width="5">
				<input type="checkbox" name="id_cb[]" value="<?php echo $data['id']?>" />
			</td>
 <td><div class="uibutton-group">
 <a href="<?php echo site_url("analisis_periode/form/$p/$o/$data[id]")?>" class="uibutton tipsy south fa-tipis" title="Ubah Data"><span class="fa fa-edit"></span> Ubah</a><a href="<?php echo site_url("analisis_periode/delete/$p/$o/$data[id]")?>" class="uibutton tipsy south" title="Hapus Data" target="confirm" message="Apakah Anda Yakin?" header="Hapus Data"><span class="fa fa-trash"></span></a>
			</div>
 </td>
		 <td align="center"><?php echo $data['nama']?></td>
		 <td align="center"><?php echo $data['tahun_pelaksanaan']?></td>
		 <td><?php echo $data['status']?></td>
		 <td><?php echo $data['keterangan']?></td>
		 <td align="center"><?php echo $data['aktif']?></td>
		 </tr>
 <?php endforeach; ?>
		</tbody>
 </table>
 </div>
	</form>
 <div class="ui-layout-south panel bottom">
 <div class="left">
		<div class="table-info">
 <form id="paging" action="<?php echo site_url('analisis_periode')?>" method="post">
<a href="<?php echo site_url()?>analisis_periode/leave" class="uibutton icon prev">Kembali</a>
		 <label></label>
 <select name="per_page" onchange="$('#paging').submit()" >
 <option value="20" <?php selected($per_page,20); ?> >20</option>
 <option value="50" <?php selected($per_page,50); ?> >50</option>
 <option value="100" <?php selected($per_page,100); ?> >100</option>
 </select>
 <label>Dari</label>
 <label><?php echo $paging->num_rows?></label>
 <label>Total Data</label>
 </form>
 </div>
 </div>
 <div class="right">
 <div class="uibutton-group">
 <?php if($paging->start_link): ?>
				<a href="<?php echo site_url("analisis_periode/index/$paging->start_link/$o")?>" class="uibutton" ><span class="fa fa-fast-backward"></span> Awal</a>
			<?php endif; ?>
			<?php if($paging->prev): ?>
				<a href="<?php echo site_url("analisis_periode/index/$paging->prev/$o")?>" class="uibutton" ><span class="fa fa-step-backward"></span> Prev</a>
			<?php endif; ?>
 </div>
 <div class="uibutton-group">

				<?php for($i=$paging->start_link;$i<=$paging->end_link;$i++): ?>
				<a href="<?php echo site_url("analisis_periode/index/$i/$o")?>" <?php jecho($p,$i,"class='uibutton special'")?> class="uibutton"><?php echo $i?></a>
				<?php endfor; ?>
 </div>
 <div class="uibutton-group">
			<?php if($paging->next): ?>
				<a href="<?php echo site_url("analisis_periode/index/$paging->next/$o")?>" class="uibutton">Next <span class="fa fa-step-forward"></span></a>
			<?php endif; ?>
			<?php if($paging->end_link): ?>
 <a href="<?php echo site_url("analisis_periode/index/$paging->end_link/$o")?>" class="uibutton">Akhir <span class="fa fa-fast-forward"></span></a>
			<?php endif; ?>
 </div>
 </div>
 </div>
</div>
</div>