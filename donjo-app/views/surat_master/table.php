<script>
	$(function() {
		var keyword = <?php echo $keyword?> ;
		$( "#cari" ).autocomplete({
			source: keyword
		});
	});
</script>
<div id="pageC">
	<div>
	</div>
	<div id="contentpane">
		<form id="mainform" name="mainform" action="" method="post">
			<div class="ui-layout-north panel">
				<div class="left">
					<div class="uibutton-group">
						<a href="<?php echo site_url('surat_master/form')?>" class="uibutton tipsy south" title="Tambah Data" ><span class="icon-plus-sign icon-large">&nbsp;</span>Tambah Surat Baru</a>
						<button type="button" title="Hapus Data" onclick="deleteAllBox('mainform','<?php echo site_url("surat_master/delete_all/$p/$o")?>')" class="uibutton tipsy south"><span class="icon-trash icon-large">&nbsp;</span>Hapus Data
					</div>
				</div>
					<div class="right">
						<input name="cari" id="cari" type="text" class="inputbox help tipped" size="20" value="<?php echo $cari?>" title="Cari.." onkeypress="if (event.keyCode == 13) {$('#'+'mainform').attr('action','<?php echo site_url('surat_master/search')?>');$('#'+'mainform').submit();}" />
						<button type="button" onclick="$('#'+'mainform').attr('action','<?php echo site_url('surat_master/search')?>');$('#'+'mainform').submit();" class="uibutton tipsy south"  title="Cari Data"><span class="icon-search icon-large">&nbsp;</span>Cari</button>
					</div>
			</div>
			<div class="ui-layout-center" id="maincontent" style="padding: 5px;">
					<table class="list">
						<thead>
							<tr>
								<th width="10">No</th>
								<th><input type="checkbox" class="checkall"/></th>
								<th>Aksi</th>

							<?php  if($o==4): ?>
								<th align="left"><a href="<?php echo site_url("surat_master/index/$p/3")?>">Nama Surat<span class="ui-icon ui-icon-triangle-1-n">&nbsp;</span></a></th>
							<?php  elseif($o==3): ?>
								<th align="left"><a href="<?php echo site_url("surat_master/index/$p/4")?>">Nama Surat<span class="ui-icon ui-icon-triangle-1-s">&nbsp;</span></a></th>
							<?php  else: ?>
								<th align="left"><a href="<?php echo site_url("surat_master/index/$p/3")?>">Nama Surat<span class="ui-icon ui-icon-triangle-2-n-s">&nbsp;</span></a></th>
							<?php  endif; ?>

							<?php  if($o==6): ?>
								<th align="left"><a href="<?php echo site_url("surat_master/index/$p/5")?>">Kode / Klasifikasi<span class="ui-icon ui-icon-triangle-1-n">&nbsp;</span></a></th>
							<?php  elseif($o==5): ?>
								<th align="left"><a href="<?php echo site_url("surat_master/index/$p/6")?>">Kode / Klasifikasi<span class="ui-icon ui-icon-triangle-1-s">&nbsp;</span></a></th>
							<?php  else: ?>
								<th align="left"><a href="<?php echo site_url("surat_master/index/$p/5")?>">Kode / Klasifikasi<span class="ui-icon ui-icon-triangle-2-n-s">&nbsp;</span></a></th>
							<?php  endif; ?>

								<th width="">URL</th>
								<th width="">Lampiran</th>
								<th width="">Template Surat</th>
							</tr>
						</thead>
						<tbody>
					<?php  foreach($main as $data): ?>
				      <tr <?php if($data['jenis']==1){echo "style='background-color:#ffeeaa;'";}?>>
								<td align="center" width="2">
									<?php echo $data['no']?>
								</td>

								<td align="center" width="5">
									<input type="checkbox" name="id_cb[]" value="<?php echo $data['id']?>" <?php if($data['jenis']==1){echo " disabled= disabled";}?> />
								</td>
								<td>
									<div class="uibutton-group">
										<a href="<?php echo site_url("surat_master/form/$p/$o/$data[id]")?>" class="uibutton tipsy south" title="Ubah Data"><span class="icon-edit icon-large"> Ubah </span></a>
										<?php if($data['jenis']!=1): ?>
											<a href="<?php echo site_url("surat_master/delete/$p/$o/$data[id]")?>" class="uibutton tipsy south" title="Hapus Data" target="confirm" message="Apakah Anda Yakin?" header="Hapus Data"><span class="icon-trash icon-large"></span></a>
										<?php endif;?>
										<?php if($data['kunci'] == '0'):?>
											<a href="<?php echo site_url("surat_master/lock/$data[id]/$data[kunci]")?>" class="uibutton" target="confirm" message="Non-Aktifkan Surat <?php echo $data['nama']?>?" header="Aktivasi Surat" rel="window"><span class="icon-unlock icon-large"></span></a>
											<a href="<?php echo site_url("surat_master/favorit/$data[id]/$data[favorit]")?>" class="uibutton" target="confirm" message="Ubah Surat <?php echo $data['nama']?> dalam daftar surat Favorit?" header="Favorit" rel="window"><span class="<?php if($data['favorit']==1){?>icon-star-empty icon-large <?php }else{?> icon-star icon-large <?php }?>"></span></a>
										<?php elseif($data['kunci'] == '1'): ?>
											<a href="<?php echo site_url("surat_master/lock/$data[id]/$data[kunci]")?>" class="uibutton" target="confirm" message="Aktifkan Surat <?php echo $data['nama']?>?" header="Aktivasi Surat" rel="window"><span class="icon-lock icon-large"></span></a>
										<?php endif?>

									</div>
								</td>

								<td><?php echo $data['nama']?></td>
								<td><?php echo $data['kode_surat']?></td>
								<td><?php echo $data['url_surat']?></td>
								<td><?php echo $data['lampiran']?></td>
								<td>
									<div class="uibutton-group">
										<a href="<?php echo site_url("surat_master/kode_isian/$p/$o/$data[id]")?>" class="uibutton tipsy south" title="Kode Isian"><span class="icon-code icon-large"> Kode Isian </span></a>
										<a href="<?php echo site_url("surat_master/form_upload/$p/$o/$data[url_surat]")?>" class="uibutton tipsy south" title="Upload Template" target="ajax-modal" rel="window" header="Upload Template"><span class="icon-upload-alt icon-large"> Upload </span></a>

										<?php $surat = SuratExport($data['url_surat']); ?>
										<?php if ($surat != "") { ?>
										<a href="<?php echo base_url($surat)?>" class="uibutton tipsy south" title="Unduh Template"><span class="icon-download-alt icon-large"> Download </span></a>
										<?php } ?>

									</div>
								</td>
							</tr>
					<?php  endforeach; ?>
						</tbody>
					</table>
				</div>
			</form>
			<div class="ui-layout-south panel bottom">
				<div class="left">
					<div class="table-info">
						<form id="paging" action="<?php echo site_url('surat_master')?>" method="post">
							<label>Tampilkan </label>
							<select name="per_page" onchange="$('#paging').submit()" >
								<option value="20" <?php  selected($per_page,20); ?> >20</option>
								<option value="50" <?php  selected($per_page,50); ?> >50</option>
								<option value="100" <?php  selected($per_page,100); ?> >100</option>
							</select>
							<label>Dari</label>
							<label><?php echo $paging->num_rows?></label>
							<label>Total Data</label>
						</form>
					</div>
					</div>
					<div class="right">
							<div class="uibutton-group">
							<?php  if($paging->start_link): ?>
					<a href="<?php echo site_url("surat_master/index/$paging->start_link/$o")?>" class="uibutton"  >Awal</a>
				<?php  endif; ?>
				<?php  if($paging->prev): ?>
					<a href="<?php echo site_url("surat_master/index/$paging->prev/$o")?>" class="uibutton"  >Prev</a>
				<?php  endif; ?>
							</div>
							<div class="uibutton-group">

					<?php  for($i=$paging->start_link;$i<=$paging->end_link;$i++): ?>
					<a href="<?php echo site_url("surat_master/index/$i/$o")?>" <?php  jecho($p,$i,"class='uibutton special'")?> class="uibutton"><?php echo $i?></a>
					<?php  endfor; ?>
							</div>
							<div class="uibutton-group">
				<?php  if($paging->next): ?>
					<a href="<?php echo site_url("surat_master/index/$paging->next/$o")?>" class="uibutton">Next</a>
				<?php  endif; ?>
				<?php  if($paging->end_link): ?>
									<a href="<?php echo site_url("surat_master/index/$paging->end_link/$o")?>" class="uibutton">Akhir</a>
				<?php  endif; ?>
							</div>
					</div>
			</div>
	</div>
</div>