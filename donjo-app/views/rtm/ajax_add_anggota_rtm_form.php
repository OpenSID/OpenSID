<?php if ($this->CI->cek_hak_akses('u')): ?>
<?php $this->load->view('global/validasi_form'); ?>
	<script>
		$(function ()
		{
			$('.select2').select2()
		})
	</script>
	<form action="<?= $form_action?>" method="post" id="validasi">
		<div class='modal-body'>
			<div class="row">
				<div class="col-sm-12">
					<div class="box-body">
						<div class="table-responsive">
							<table id="tabel2" class="table table-bordered dataTable table-hover nowrap">
								<thead class="bg-gray disabled color-palette">
									<tr>
										<th>No</th>
										<th>NIK</th>
										<th>Nama</th>
										<th>Hubungan</th>
									</tr>
								</thead>
								<tbody>
									<?php $no = 1;

    foreach ($main as $data): ?>
										<tr>
											<td><?= $no;
        $no++; ?></td>
											<td><?= $data['nik']?></td>
											<td><?= $data['nama']?></td>
										<td><?= $data['hubungan']?></td>
										</tr>
									<?php endforeach; ?>
							</tbody>
						</table>
						</div>
					</div>
				</div>
				<div class="col-sm-12">
					<div class="box box-danger">
						<div class="box-body">
							<div class="form-group">
								<label for="nik">NIK / Nama Penduduk</label>
								<select class="form-control input-sm select2 required"  id="nik" name="nik" style="width:100%;">
									<option option value="">-- Silakan Cari NIK / Nama Penduduk--</option>
									<?php foreach ($penduduk as $data): ?>
										<option value="<?= $data['id']?>">NIK :<?= $data['nik'] . ' - ' . $data['nama']?></option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="reset" class="btn btn-social btn-flat btn-danger btn-sm" data-dismiss="modal"><i class='fa fa-sign-out'></i> Tutup</button>
				<button type="submit" class="btn btn-social btn-flat btn-info btn-sm" id="ok"><i class='fa fa-check'></i> Simpan</button>
			</div>
		</div>
	</form>
<?php endif; ?>