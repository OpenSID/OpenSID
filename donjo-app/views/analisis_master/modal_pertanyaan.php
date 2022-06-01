<script>
	$('#file_browser2').click(function(e) {
		e.preventDefault();
		$('#file2').click();
	});

	$('#file2').change(function() {
		$('#file_path2').val($(this).val());
	});

	$('#file_path2').click(function() {
		$('#file_browser2').click();
	});
</script>
<div class="modal fade" id="modalPertanyaan" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class='modal-dialog modal-lg'>
		<div class='modal-content'>
			<div class='modal-header'>
				<button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
				<h4 class='modal-title' id='myModalLabel'> Modal Pertanyaan</h4>
			</div>
			<form id="form-pertanyaan" action="<?= $form_action ?>" method="POST" enctype="multipart/form-data">
				<div class='modal-body'>
					<div class="row">
						<div class="col-sm-12">
							<div class="box box-info">
								<div class="box-body">
									<div class="form-group">
										<div class="row">
											<div class="col-sm-12">
												<label class="control-label">Pilih pertanyaan yang akan disimpan pada tabel berikut</label>
												<div class="table-responsive">
													<table class="table table-bordered table-striped dataTable table-hover">
														<thead class="bg-gray disabled color-palette">
															<tr>
																<th><input type="checkbox" id="select-all-question" checked data-waschecked="true" /></th>
																<th>NIK/KK</th>
																<th>Pertanyaan</th>
																<th>Tipe Pertanyaan</th>
																<th>Kategori / Variabel</th>
																<th>Bobot</th>
															</tr>
														</thead>
														<tbody>
															<?php foreach ($data_import['pertanyaan'] as $key => $data) : ?>
																<tr class="row-pertanyaan">
																	<input type="hidden" class="input-id" value=<?= $key ?>>
																	<input type="hidden" class="input-item-id" value=<?= $data['itemId'] ?>>
																	<td><input type="checkbox" class="input-is-selected" checked data-waschecked="true"></td>
																	<td class="padat"><input type="radio" class="input-is-nik-kk"></td>
																	<td class="input-pertanyaan"><?= $data['title'] ?></td>
																	<td>
																		<?php if ($data['type'] == 'MULTIPLE_CHOICE') : ?>
																			<select name="tipe_pertanyaan" class="form-control input-sm input-tipe">
																				<option value="0">Tipe Pertanyaan</option>
																				<option value="1" selected>Pilihan (Tunggal)</option>
																				<option value="2">Pilihan (Ganda)</option>
																				<option value="3">Isian Jumlah (Kuantitatif)</option>
																				<option value="4">Isian Teks (Kualitatif)</option>
																			</select>
																		<?php else : ?>
																			<select name="tipe_pertanyaan" class="form-control input-sm input-tipe">
																				<option value="0">Tipe Pertanyaan</option>
																				<option value="1">Pilihan (Tunggal)</option>
																				<option value="2">Pilihan (Ganda)</option>
																				<option value="3">Isian Jumlah (Kuantitatif)</option>
																				<option value="4" selected>Isian Teks (Kualitatif)</option>
																			</select>
																		<?php endif ?>
																	</td>
																	<td><input type="text" class="form-control input-sm input-kategori"></td>
																	<td><input type="number" class="form-control input-sm input-bobot" value="0"></td>
																</tr>
															<?php endforeach ?>
														</tbody>
													</table>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-social btn-flat btn-info btn-sm" id="btn-next-pertanyaan"><i class='fa fa-arrow-right'></i> Lanjutkan</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>