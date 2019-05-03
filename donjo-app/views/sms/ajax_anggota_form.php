<form action="<?= $form_action?>" method="post" id="validasi">
	<div class="box-body">
		<div class="row">
			<div class="col-sm-12">
				<div class="box box-danger">
					<div class="box-body">
						<div class="table-responsive">
							<table id="tabel2" class="table table-bordered dataTable table-hover">
								<thead class="bg-gray disabled color-palette">
									<tr>
										<th><input type="checkbox" id="checkallanggota"/></th>
										<th>No</th>
										<th>Nama</th>
										<th>Jenis Kelamin</th>
										<th>Alamat</th>
										<th>No HP</th>
									</tr>
								</thead>
								<tbody>
									<?php $no=1; foreach ($main as $data): ?>
										<tr>
											<td><input type="checkbox" name="id_cb[]" value="<?=$data['id_kontak']?>" /></td>
											<td><?= $no;?></td>
											<td><?= $data['nama']?></td>
											<td><?= $data['sex']?></td>
											<td><?= $data['alamat_sekarang']?></td>
											<td><?= $data['no_hp']?></td>
										</tr>
										<?php $no++; ?>
									<?php endforeach; ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="reset" class="btn btn-social btn-flat btn-danger btn-sm" data-dismiss="modal"><i class='fa fa-sign-out'></i> Tutup</button>
				<button type="submit" class="btn btn-social btn-flat btn-info btn-sm" id="ok"><i class='fa fa-check'></i> Simpan</button>
			</div>
		</div>
	</div>
</form>
<script>checkAll("#checkallanggota");</script>
