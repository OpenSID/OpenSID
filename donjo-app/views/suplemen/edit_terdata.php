
<?php if ($this->CI->cek_hak_akses('u')): ?>
<?php $this->load->view('global/validasi_form'); ?>
	<form action="<?= $form_action; ?>" method="post" id="validasi" enctype="multipart/form-data">
		<div class='modal-body'>
			<table class="table table-bordered table-striped table-hover table-rincian">
				<tbody>
					<tr>
						<td width="20%"><?= $judul_terdata_nama; ?></td>
						<td width="1">:</td>
						<td><?= $terdata_nama; ?></td>
					</tr>
					<tr>
						<td><?= $judul_terdata_info; ?></td>
						<td>:</td>
						<td><?= $terdata_info; ?></td>
					</tr>
				</tbody>
			</table>
			<div class="form-group">
				<label for="keterangan">Keterangan</label>
				<input type="hidden" name="id_suplemen" value="<?= $id_suplemen?>"/>
				<textarea name="keterangan" id="keterangan" class="form-control input-sm" maxlength="100" placeholder="Keterangan" rows="5"><?= $keterangan?></textarea>
			</div>
		</div>
		<div class="modal-footer">
			<button type="reset" class="btn btn-social btn-flat btn-danger btn-sm pull-left"><i class="fa fa-times"></i> Batal</button>
			<button type="submit" class="btn btn-social btn-flat btn-info btn-sm pull-right"><i class='fa fa-check'></i> Simpan</button>
		</div>
	</form>
<?php endif; ?>