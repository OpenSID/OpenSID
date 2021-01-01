										<div class="tab-pane <?php if ($act_tab==7): ?>active<?php endif ?>">
											<div class="row">
												<div class="col-md-12">
													<div class="box-header with-border">
														<h3 class="box-title"><strong>Sinkronisasi Database Penduduk OpenSID dengan OpenDK</strong></h3>
													</div>
													<div class="box-body">
														<div class="row">
															<div class="col-sm-12">
																<p>Proses ini akan menyinkronkan data penduduk di OpenSID dengan data penduduk di OpenDK.</p>
																<form id="sinkronkan" action="<?= $form_action ?>" method="POST" enctype="multipart/form-data" class="form-horizontal">
																	<table class="table table-bordered">
																		<tbody>
																			<tr>
																				<td style="padding-top:20px;padding-bottom:10px;">
																					<div class="form-group">
																						<label for="file" class="col-md-4 col-lg-3 control-label">Sinkronkan Data</label>
																						<div class="col-sm-12 col-md-3 col-lg-3">
																							<a href="#" form="sinkronkan" class="btn btn-social btn-flat btn-block btn-danger btn-sm"  title="Sinkronkan dengan OpenDK" data-toggle="modal" data-target="#confirmsubmit"><i class="fa fa-refresh"></i> Sinkronkan Database OpenDK</a>
																						</div>
																					</div>
																				</td>
																			</tr>
																			<?php if (isset($_SESSION['response'])): ?>
																			<tr>
																				<td style="padding-top:20px;padding-bottom:10px;">
																					<div class="form-group">
																						<label for="file" class="col-md-4 col-lg-3 control-label">Response</label>
																						<div class="col-sm-12 col-md-3 col-lg-9">
																							<textarea class="form-control" id="json" rows="12"></textarea>
																						</div>
																					</div>
																				</td>
																			</tr>
																			<script>
																				let pretty = JSON.stringify(<?= $_SESSION['response'] ?>, undefined, 2);
																				document.getElementById('json').value = pretty;
																			</script>
																			<?php endif ?>
																		</tbody>
																	</table>
																	<table class="table table-bordered">
																		<tbody>
																			<tr>
																				<p>API Key OpenDK Server</p>
																				<td style="padding-top:20px;padding-bottom:10px;">
																					<div class="form-group">
																						<label for="token" class="col-sm-3 control-label" for="token">API Key</label>
																						<div class="col-sm-8">
																							<textarea rows="4" class="form-control input-sm" name="token" id="token" placeholder="API Key OpenDK"></textarea>
																							<br>
																							<button class="btn btn-social btn-flat btn-info btn-sm" id="btn_simpan"><i class='fa fa-key'></i>Buat Key</button>
																							<button type="button" class="btn btn-social btn-flat btn-primary btn-sm" onclick="copyToClipboard('#token')"><i class='fa fa-copy'></i>Salin Key ke Clipboard</button>
																						</div>
																					</div>
																				</td>
																			</tr>
																		</tbody>
																	</table>
																</form>
															</div>
														</div>
													</div>
												</div>
												<div class='modal fade' id='confirmsubmit' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
													<div class='modal-dialog'>
														<div class='modal-content'>
															<div class='modal-header'>
																<button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
																<h4 class='modal-title' id='myModalLabel'><i class='fa fa-exclamation-triangle text-red'></i> &nbsp;Konfirmasi</h4>
															</div>
															<div class='modal-body btn-danger'>
																Proses sinkronisasi ini akan menambah/mengubah/menghapus data penduduk di OpenDK.
															</div>
															<div class='modal-footer'>
																<button type='button' class='btn btn-social btn-flat btn-warning btn-sm' data-dismiss='modal'><i class='fa fa-sign-out'></i> Tutup</button> <a href="#" id="submit" class='btn btn-social btn-flat btn-success btn-sm'><i class='fa fa-refresh'></i> Sinkron</a>
															</div>
														</div>
													</div>
												</div>
												<div class='modal fade' id='loading' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
													<div class='modal-dialog'>
														<div class='modal-content'>
															<div class='modal-header btn-warning'>
																<h4 class='modal-title' id='myModalLabel'>Proses Sinkronisasi Database ......</h4>
															</div>
															<div class='modal-body'>
																Harap tunggu sampai proses Sinkronisasi Database selesai. Proses ini bisa memakan waktu beberapa menit tergantung jumlah data penduduk.
																<div class='text-center'>
																	<img src='<?= base_url()?>assets/images/background/loading.gif'>
																</div>
															</div>
														</div>
													</div>
												</div>
												<?php unset($_SESSION['response']) ?>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
<script src="<?= base_url()?>assets/js/axios.min.js"></script>
<script>
	const api_opendk_server = '<?= $this->setting->api_opendk_server ?>';
	const api_opendk_user = '<?= $this->setting->api_opendk_user ?>';
	const api_opendk_password = '<?= $this->setting->api_opendk_password ?>';

	$('#submit').click(function() {
		$('#sinkronkan').submit();
		$('#confirmsubmit').hide();
		$('#loading').modal('show');
	});

	async function get_token() {
		let response = await axios({
			'method': 'post',
			'header': {
				'Accept': 'application/json'
			},
			'url': api_opendk_server + '/api/v1/auth/login',
			'data': {
				'email': api_opendk_user,
				'password': api_opendk_password
			}
		});

		let infodk = response.data;
		$('[name="token"]').val(infodk.access_token);
	}

	$('#btn_simpan').on('click', function() {
		get_token();
		return false;
	});

	function copyToClipboard(element) {
		$(element).select();
		document.execCommand("copy");
	}

</script>
