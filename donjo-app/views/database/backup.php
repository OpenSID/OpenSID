										<div class="tab-pane <?php if ($act_tab==4): ?> active<?php endif ?>">
											<div class="row">
												<div class="col-md-12">
													<div class="box-header with-border">
														<h3 class="box-title"><strong>Backup / Restore Database SID</strong></h3>
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
																<p>Backup yang dibuat dapat dipergunakan untuk me-restore database SID anda apabila ada masalah. Klik tombol Restore di bawah untuk menggantikan keseluruhan database SID dengan data hasil backup terdahulu.</p>
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
																								<input type="file" class="hidden" id="file" name="userfile" accept="application/sql">
																								<span class="input-group-btn">
																									<button type="button" class="btn btn-info btn-flat"  id="file_browser"><i class="fa fa-search"></i> Browse</button>
																								</span>
																							</div>
																						</div>
																						<div class="col-sm-12 col-md-3 col-lg-2">
																							<button type="submit" class="btn btn-block btn-success btn-sm"><i class="fa fa-spin fa-refresh"></i>  Restore</button>
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
												<div class="col-md-12">
													<div class="box-header with-border">
														<h3 class="box-title"><strong>Kosongkan Database SID</strong></h3>
													</div>
													<div class="box-body">
														<div class="row">
															<div class="col-sm-12">
																<p>Biasanya pada saat menginstall SID, desa mengimpor data awal yang merupakan
																  contoh yang disediakan agar pengguna dapat mempelajari fitur SID.
																  Data awal tersebut tentunya mengandung data contoh yang bukan data desa.</p>
																<p>Sebelum memasukkan data desa yang sebenarnya ke dalam database SID, data contoh tersebut perlu dikosongkan dulu.</p>
																<p>Klik tomboh <em>Kosongkan DB</em> di bawah untuk mengosongkan database SID siap untuk diisi dengan data desa.</p>
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
																Harap tunggu sampai proses Penghapusan Database selesai. Prosses ini bisa memakan waktu beberapa menit.
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
<script src="<?= base_url()?>assets/bootstrap/js/jquery.min.js"></script>
<script>
	$('#submit').click(function()
	{
		$('#kosongkan').submit();
		$('#confirmsubmit').hide();
		$('#loading').modal('show');
	});
</script>