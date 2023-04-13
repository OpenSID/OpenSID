<?php if ($this->CI->cek_hak_akses('u')) : ?>
	<?php $this->load->view('global/validasi_form'); ?>
	<form action="<?= $form_action ?>" method="post" id="validasi">
		<div class='modal-body'>
			<div class="row">
				<div class="col-sm-12">
					<div class="form-group">
						<label for="nik">NIK / Nama Penduduk</label>
						<select class="form-control input-sm select2 required" id="nik" name="nik" style="width:100%;">
							<option option value="">-- Silakan Cari NIK / Nama Penduduk--</option>
							<?php foreach ($penduduk as $data) : ?>
								<option value="<?= $data['id_kk'] ?>">NIK :<?= $data['nik'] . ' - ' . $data['nama'] ?></option>
							<?php endforeach; ?>
						</select>
					</div>
					<div class="table-responsive">
						<table id="keluarga" class="table table-bordered dataTable table-hover nowrap">
							<thead class="bg-gray disabled color-palette">
								<tr>
									<th>#</th>
									<th>No</th>
									<th>NIK</th>
									<th>Nama</th>
									<th>Hubungan</th>
								</tr>
							</thead>
						</table>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="reset" class="btn btn-social btn-flat btn-danger btn-sm" data-dismiss="modal"><i class='fa fa-sign-out'></i> Tutup</button>
				<button type="submit" class="btn btn-social btn-flat btn-info btn-sm" id="ok"><i class='fa fa-check'></i> Simpan</button>
			</div>
		</div>
	</form>
	<script>
		$(function() {
			$('#nik').select2()
		});

		$('#nik').on('select2:select', function (e) {
			var table = $('#keluarga').DataTable({
				responsive: true,
				processing: true,
				serverSide: false,
				ajax: {
					url: `<?= site_url('rtm/datables_anggota/') ?>${e.params.data.id}`,
				},
				'columns': [
					{
						'data': function(data) {
							return `<td><input type="checkbox" name="id_cb[]" value="${data.id}" /></td>`
						}
					},
					{'data': 'no'},
					{'data': 'nik'},
					{'data': 'nama'},
					{'data': 'kk_level'},
				],
			});

			table.destroy();
		});
	</script>
<?php endif; ?>