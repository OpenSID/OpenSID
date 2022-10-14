										<div class="tab-pane <?= jecho($act_tab, 2, 'active') ?>">
											<div class="row">
												<div class="col-md-12">
													<div class="box-header with-border">
														<h3 class="box-title"><strong>Migrasi Database Ke OpenSID <?= AmbilVersi() ?></strong></h3>
													</div>
													<div class="box-body">
														<div class="row">
															<div class="col-sm-12">
																<form action="<?= $form_action?>" method="post" enctype="multipart/form-data" id="excell" class="form-horizontal">
																	<p>Proses ini untuk mengubah database SID ke struktur database OpenSID <?= AmbilVersi() ?>.</p>
																	<p class="text-muted text-red well well-sm no-shadow" style="margin-top: 10px;">
																		<small>
																			<strong><i class="fa fa-info-circle text-red"></i> Sebelum melakukan migrasi ini, pastikan database SID anda telah dibackup.</strong>
																		</small>
																	</p>
																	<p>Apabila sesudah melakukan konversi ini, masih ditemukan masalah, laporkan di :</P>
																	<ul>
																		<li> <a href="https://github.com/OpenSID/OpenSID/issues">https://github.com/OpenSID/OpenSID/issues</a></li>
																		<li> <a href="https://www.facebook.com/groups/OpenSID/">https://www.facebook.com/groups/OpenSID/</a></li>
																	</ul>
																	<table class="table table-bordered" >
																		<tbody>
																			<tr>
																				<td style="padding-top:20px;padding-bottom:10px;">
																					<div class="form-group">
																						<div class="col-sm-5 col-md-4">
																							<a href="#" class="btn btn-block btn-danger btn-sm ajax"  title="Migrasi DB" onclick="document.getElementById('excell').submit();" data-toggle="modal" data-target="#loading" data-backdrop="false" data-keyboard="false"> <i class="fa fa-spin fa-refresh"></i> Migrasi Database Ke OpenSID <?= AmbilVersi()?></a>
																						</div>
																					</div>
																					<div class="ajax-content"></div>
																				</td>
																			</tr>
																		</tbody>
																	</table>
																</form>
															</div>
														</div>
													</div>
													<div class='modal fade' id='loading' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
														<div class='modal-dialog'>
															<div class='modal-content'>
																<div class='modal-header btn-warning'>
																	<h4 class='modal-title' id='myModalLabel'>Proses Migrasi ......</h4>
																</div>
																<div class='modal-body'>
																	Harap tunggu sampai proses migrasi selesai. Proses ini biasa memakan waktu beberapa menit.
																	<div class='text-center'>
																		<img src="<?= asset('images/background/loading.gif') ?>">
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
		</div>
	</section>
</div>
