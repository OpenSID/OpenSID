<div id="pageC">
	<div id="contentpane">
		<form id="mainform" name="mainform" action="" method="post">
			<div class="ui-layout-north panel">
				<h3>Dokumen / Kelengkapan Penduduk - <?php echo $penduduk['nama']?> [<?php echo $penduduk['nik']?>]</h3>
				<div class="left">
					<div class="uibutton-group">
						<a header="Form Dokumen" target="ajax-modal" rel="dokumen" href="<?php echo site_url("penduduk/dokumen_form/$penduduk[id]")?>" class="uibutton"><span class="fa fa-plus-square"></span> Tambah Dokumen</a>
						<button type="button" title="Hapus Data" onclick="deleteAllBox('mainform','<?php echo site_url("penduduk/delete_all_dokumen/$penduduk[id]")?>')" class="uibutton tipsy south"><span class="fa fa-trash">&nbsp;</span>Hapus
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
							<th width="2">No</th>
							<th width="20"><input type="checkbox" class="checkall"/></th>
							<th width="20">Aksi</th>
							<th width="220">Nama Dokumen</th>
							<th width="360">File</th>
							<th width="200">Tanggal Upload</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach($list_dokumen as $data){?>
							<tr>
								<td align="center" width="2"><?php echo $data['no']?></td>
								<td align="center" width="5">
									<input type="checkbox" name="id_cb[]" value="<?php echo $data['id']?>" />
								</td>
								<td>
									<div class="uibutton-group">
										<a href="<?php echo site_url("penduduk/delete_dokumen/$penduduk[id]/$data[id]")?>" class="uibutton tipsy south" title="Hapus Data" target="confirm" message="Apakah Anda Yakin?" header="Hapus Data"><span class="fa fa-trash"></span></a>
									</div>
								 </td>
								 <td><?php echo $data['nama']?></td>
								 <td><a href="<?php echo base_url().LOKASI_DOKUMEN?><?php echo urlencode($data['satuan'])?>" ><?php echo $data['satuan']?></a></td>
								 <td><?php echo tgl_indo2($data['tgl_upload'])?></td>
								 <td></td>
							</tr>
						<?php }?>
					</tbody>
				</table>
				<br>
			</div>
      <div class="ui-layout-south panel bottom">
				<div class="left">
					<div class="uibutton-group">
						<a href="<?php echo site_url("penduduk/detail/1/0/$penduduk[id]")?>" class="uibutton icon prev"><span class="icon-prev"></span> Kembali</a>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>