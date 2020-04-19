<script type="text/javascript" src="<?= base_url()?>assets/js/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?= base_url()?>assets/js/validasi.js"></script>
<script type="text/javascript" src="<?= base_url()?>assets/js/localization/messages_id.js"></script>
<form action="<?= $form_action?>" method="post" id="validasi">
	<input type="hidden" name="kk_level_lama" value="<?= $main['kk_level']?>">
	<div class='modal-body'>
		<div class="row">
			<div class="col-sm-12">
				<table id="tabel3" class="table table-hover" >
					<tr>
						<td style="padding-top : 10px;padding-bottom : 10px; width:40%;" >NIK</td>
						<td> : <?= $main['nik']?></td>
					</tr>
					<tr>
						<td style="padding-top : 10px;padding-bottom : 10px; width:40%;" >Nama Penduduk</td>
						<td> : <?= $main['nama']?></td>
					</tr>
				</table>
				<div class="box box-danger">
					<div class="box-body">
						<div class="form-group">
							<label for="kk_level">Hubungan</label>
							<select name="kk_level" class="form-control input-sm required" style="width:100%;">
								<option value=""> ----- Pilih Hubungan ----- </option>
								<?php foreach ($hubungan as $data): ?>
									<?php if ($data['id']>0): ?>
										<option value="<?= $data['id']?>" <?php if ($data['id']==$main['kk_level']): ?>selected<?php endif; ?>><?= $data['hubungan']?></option>
									<?php endif; ?>
								<?php endforeach; ?>
							</select>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="modal-footer">
	<button type="reset" class="btn btn-social btn-flat btn-danger btn-sm" data-dismiss="modal"><i class='fa fa-sign-out'></i> Tutup</button>
			<button type="submit" class="btn btn-social btn-flat btn-info btn-sm" id="ok"><i class='fa fa-check'></i> Simpan</button>
	</div>
</form>