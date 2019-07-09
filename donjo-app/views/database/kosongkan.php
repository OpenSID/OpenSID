										<div class="tab-pane <?php if ($act_tab==6): ?> active<?php endif ?>">
											<div class="row">
												<div class="col-md-12">
													<div class="box-header with-border">
														<h3 class="box-title"><strong>Kosongkan Database SID</strong></h3>
													</div>
													<div class="box-body">
														<div class="row">
															<div class="col-sm-12">
																<p>Biasanya pada saat menginstall SID, <?= $this->setting->sebutan_desa ?> mengimpor data awal yang merupakan
																  contoh yang disediakan agar pengguna dapat mempelajari fitur SID.
																  Data awal tersebut tentunya mengandung data contoh yang bukan data <?= $this->setting->sebutan_desa ?>.</p>
																<p>Sebelum memasukkan data <?= $this->setting->sebutan_desa ?> yang sebenarnya ke dalam database SID, data contoh tersebut perlu dikosongkan dulu.</p>
																<p>Klik tombol <em>Kosongkan DB</em> di bawah untuk mengosongkan database SID siap untuk diisi dengan data <?= $this->setting->sebutan_desa ?>.</p>
																<p class="text-muted text-red well well-sm no-shadow" style="margin-top: 10px;">
																	<small><strong><i class="fa fa-info-circle text-red"></i> Sebelum melalukan proses ini, backup dulu database SID.</strong></small>
																</p>
																<form id="kosongkan" action="<?= site_url("database/kosongkan_db")?>" method="post" class="form-horizontal">
																	<table class="table table-bordered">
																		<tbody>
																			<tr>
																				<td style="padding-top:20px;padding-bottom:10px;">
																					<div class="form-group">
																						<label for="file" class="col-md-4 col-lg-3 control-label">Kosongkan Database SID</label>
																						<div class="col-sm-12 col-md-3 col-lg-2">
																							<a href="#" form="kosongkan" class="btn btn-social btn-flat btn-block btn-danger btn-sm"  title="Kosongkan DB" data-toggle="modal" data-target="#confirmsubmit"><i class="fa fa-trash-o"></i> Kosongkan DB</a>
																						</div>
																						<p class="help-block col-sm-12 col-md-5 col-lg-5"><input type="checkbox" name="kosongkan_menu" value='kosongkan'></input>	Juga kosongkan contoh menu statis dan dinamis</p>
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
																Apakah anda yakin? Proses ini akan menghapus semua data penduduk dan data masukan lainnya.
															</div>
															<div class='modal-footer'>
																<button type='button' class='btn btn-social btn-flat btn-warning btn-sm' data-dismiss='modal'><i class='fa fa-sign-out'></i> Tutup</button> <a href="#" id="submit" class='btn btn-social btn-flat btn-danger btn-sm'><i class='fa fa-times'></i> Hapus</a>
															</div>
														</div>
													</div>
												</div>
												<div class='modal fade' id='loading' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
													<div class='modal-dialog'>
														<div class='modal-content'>
															<div class='modal-header btn-warning'>
																<h4 class='modal-title' id='myModalLabel'>Proses Penghapusan Database ......</h4>
															</div>
															<div class='modal-body'>
																Harap tunggu sampai proses Penghapusan Database selesai. Proses ini bisa memakan waktu beberapa menit.
																<div class='text-center'>
																	<img src='<?= base_url()?>assets/images/background/loading.gif'>
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
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
<script>
	$('#submit').click(function()
	{
		$('#kosongkan').submit();
		$('#confirmsubmit').hide();
		$('#loading').modal('show');
	});
</script>
