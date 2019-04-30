										<div class="tab-pane <?php if ($act_tab==4): ?> active<?php endif ?>">
											<div class="row">
												<div class="col-md-12">
													<div class="box-header with-border">
														<h3 class="box-title"><strong>Backup Database SID</strong></h3>
													</div>
													<div class="box-body">
														<div class="row">
															<div class="col-sm-12">
																<form class="form-horizontal">
																	<table class="table table-bordered">
																		<tbody>
																			<tr>
																				<td style="padding-top:20px;padding-bottom:10px;">
																					<div class="form-group">
																						<label for="file"  class="col-md-4 col-lg-3 control-label">Backup Seluruh Database SID (.sql)</label>
																						<div class="col-sm-12 col-md-3 col-lg-2">
																							<a href="<?= site_url("database")?>/exec_backup" class="btn btn-social btn-flat btn-block btn-info btn-sm"><i class="fa fa-download"></i>  Unduh</a>
																						</div>
																					</div>
																				</td>
																			</tr>
																		</tbody>
																	</table>
																</form>
																<p>Proses Unduh akan mengunduh keseluruhan database SID anda.</p>
																<div class="row">
																	<ul>
																		<li> Usahakan untuk melakukan backup secara rutin dan terjadwal. </li>
																		<li> Backup yang dihasilkan sebaiknya disimpan di komputer terpisah dari server SID. </li>
																	</ul>
																</div>
																<p class="text-muted text-red well well-sm no-shadow" style="margin-top: 10px;">
																	<small><strong><i class="fa fa-info-circle text-red"></i> Proses ini tidak melakukan backup folder desa. Folder desa berisi berkas desa dan perlu dibackup juga secara rutin.</strong></small>
																</p>
															</div>
														</div>
													</div>
												</div>
												<div class="col-md-12">
													<div class="box-header with-border">
														<h3 class="box-title"><strong>Restore Database SID</strong></h3>
													</div>
													<div class="box-body">
														<div class="row">
															<div class="col-sm-12">
																<p>Backup yang dibuat dapat dipergunakan untuk mengembalikan database SID anda apabila ada masalah. Klik tombol Restore di bawah untuk menggantikan keseluruhan database SID dengan data hasil backup terdahulu.</p>
																<form action="<?= $form_action?>" method="post" enctype="multipart/form-data" class="form-horizontal">
																	<?php if (strlen(@$_SESSION["SIAK"])>1): ?>
																			<?=$_SESSION["SIAK"]?>
																	<?php endif ?>
																	<?php
																	  $_SESSION["SIAK"] = "";
																	  $max_upload = (int)(ini_get('upload_max_filesize'));
																	  $max_post = (int)(ini_get('post_max_size'));
																	  $memory_limit = (int)(ini_get('memory_limit'));
																		$upload_mb = min($max_upload, $max_post, $memory_limit);
																	?>
																	<p>Batas maksimal pengunggahan berkas <strong><?=$upload_mb?> MB.</strong></p>
																	<p>Proses ini akan membutuhkan waktu beberapa menit, menyesuaikan dengan spesifikasi
																	  komputer server SID dan sambungan internet yang tersedia.</p>
																	<p></p>
																	<table class="table table-bordered table-hover" >
																		<tbody>
																			<tr>
																				<td style="padding-top:20px;padding-bottom:10px;">
																					<div class="form-group">
																						<label for="file"  class="col-md-2 col-lg-3 control-label">Pilih File .Sql:</label>
																						<div class="col-sm-12 col-md-5 col-lg-5">
																							<div class="input-group input-group-sm">
																								<input type="text" class="form-control" id="file_path" name="userfile">
																								<input type="file" class="hidden" id="file" name="userfile" data-submit="restore" accept="application/sql">
																								<span class="input-group-btn">
																									<button type="button" class="btn btn-info btn-flat" id="file_browser"><i class="fa fa-search"></i> Browse</button>
																								</span>
																							</div>
																						</div>
																						<div class="col-sm-12 col-md-3 col-lg-2">
																							<button type="submit" id="restore" class="btn btn-block btn-success btn-sm" disabled="disabled"><i class="fa fa-spin fa-refresh"></i>  Restore</button>
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
