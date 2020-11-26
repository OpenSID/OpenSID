										<div class="tab-pane <?php if ($act_tab==1): ?>active<?php endif ?>">
											<div class="row">
												<div class="col-md-12">
													<div class="card-header with-border">
														<h3 class="box-title"><strong>Ekspor Data <?= ucwords($this->setting->sebutan_desa); ?></strong></h3>
													</div>
													<div class="card-body">
														<div class="row">
															<div class="col-md-8">
																<table class="table table-striped table-hover">
																	<tr>
																		<td class="col-sm-10">Ekspor Data Penduduk (Format .xlsx untuk di impor ke database SID melalui menu Impor Database)</td>
																		<td class="col-sm-2">
																			<a href="<?= site_url("database")?>/export_excel" class="btn btn-flat btn-info btn-xs"><i class="fa fa-download"></i> Unduh</a>
																		</td>
																	</tr>
																	<tr>
																		<td class="col-sm-10">Ekspor Data Penduduk untuk diimpor ke database OpenDK (.xlsx)</td>
																		<td class="col-sm-2">
																			<a href="<?= site_url("database")?>/export_excel/opendk" class="btn btn-flat btn-info btn-xs"><i class="fa fa-download"></i> Unduh</a>
																		</td>
																	</tr>
																	<tr>
																		<td>Ekspor Data Dasar Kependudukan (.sid)</td>
																		<td>
																			<a href="<?= site_url("database")?>/export_dasar" class="btn btn-flat btn-info btn-xs"><i class="fa fa-download"></i> Unduh</a>
																		</td>
																	</tr>
																	<tr>
																		<td>Ekspor Data CSV (.csv)</td>
																		<td>
																			<a href="<?= site_url("database")?>/export_csv" class="btn btn-flat btn-info btn-xs"><i class="fa fa-download"></i> Unduh</a>
																		</td>
																	</tr>
																</table>
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
