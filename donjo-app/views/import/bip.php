										<div class="tab-pane <?php if ($act_tab==3): ?> active<?php endif ?>">
											<div class="row">
												<div class="col-md-12">
													<div class="box-header with-border">
														<h3 class="box-title"><strong>Impor Data Buku Induk Penduduk</strong></h3>
													</div>
													<div class="box-body">
														<div class="row">
															<div class="col-sm-12">
																<form action="<?= $form_action?>" method="post" enctype="multipart/form-data" id="excell" class="form-horizontal">
																	<p>Proses ini untuk mengimpor data Buku Induk Penduduk (BIP) yang diperoleh dari Disdukcapil dalam format Excel.</p>
																	<p>BIP yang dapat dibaca proses ini adalah yang tersusun berdasarkan keluarga, seperti contoh yang dapat dilihat pada tautan berikut :</P>
																	<a class="btn btn-social btn-flat btn-info btn-sm btn-margin visible-xs-block visible-sm-block visible-md-inline-block visible-lg-inline-block" href="<?= base_url()?>assets/import/format_bip_2012.xls" ><i class="fa fa-download"></i>Contoh BIP 2012</a>
																	<a class="btn btn-social btn-flat btn-info btn-sm btn-margin visible-xs-block visible-sm-block visible-md-inline-block visible-lg-inline-block" href="<?= base_url()?>assets/import/format_bip_2016.xls" ><i class="fa fa-download"></i>Contoh BIP 2016</a>
																	<a class="btn btn-social btn-flat btn-info btn-sm btn-margin visible-xs-block visible-sm-block visible-md-inline-block visible-lg-inline-block" href="<?= base_url()?>assets/import/format_bip_ektp.xls"><i class="fa fa-download"></i>Contoh BIP eKTP</a>
																	<a class="btn btn-social btn-flat btn-info btn-sm btn-margin visible-xs-block visible-sm-block visible-md-inline-block visible-lg-inline-block" href="<?= base_url()?>assets/import/format_bip_2016_luwutimur.xls"><i class="fa fa-download"></i>Contoh BIP 2016 Luwu Timur</a>
																	<a class="btn btn-social btn-flat btn-info btn-sm btn-margin visible-xs-block visible-sm-block visible-md-inline-block visible-lg-inline-block" href="<?= base_url()?>assets/import/format_siak.xls"><i class="fa fa-download"></i>Contoh Data SIAK</a>
																	<p></p>
																	<p>Proses ini mengimpor data keluarga di semua worksheet di berkas BIP. Misalnya, apabila data BIP tersusun menjadi satu worksheet per dusun, proses ini akan mengimpor data semua dusun.</p>
																	<p class="text-muted text-red well well-sm no-shadow" style="margin-top: 10px;">
																		<small>
																			<strong>
																				<i class="fa fa-info-circle text-red"></i> Pastikan berkas BIP format Excel 2003, ber-ekstensi .xls <br>
																				<i class="fa fa-info-circle text-red"></i> Sebelum di-impor ganti semua format tanggal (seperti tanggal lahir) menjadi dd/mm/yyyy (misalnya 26/07/1964).
																			</strong>
																		</small>
																	</p>
																	<p>
																		<p>Batas maksimal pengunggahan berkas <strong><?= max_upload() ?> MB.</strong></p>
																		<p>Proses ini akan membutuhkan waktu beberapa menit, menyesuaikan dengan spesifikasi
																			komputer server SID, banyaknya data dan sambungan internet yang tersedia.</p>
																	</p>
																	<table class="table table-bordered" >
																		<tbody>
																			<tr>
																				<td style="padding-top:20px;padding-bottom:10px;">
																					<div class="form-group">
																						<label for="file" class="col-md-3 control-label">Pilih File .xls:</label>
																						<div class="col-md-5">
																							<div class="input-group input-group-sm">
																								<input type="text" class="form-control" id="file_path2" name="userfile">
																								<input type="file" class="hidden" id="file2" name="userfile">
																								<span class="input-group-btn">
																									<button type="button" class="btn btn-info btn-flat"  id="file_browser2"><i class="fa fa-search"></i> Browse</button>
																								</span>
																							</div>
																							<p class="help-block"><input type="checkbox" name="hapus_data" value="hapus"></input>	Hapus data penduduk sebelum Impor</p>
																						</div>
																						<div class="col-md-2">
																							<a href="#" class="btn btn-block btn-success btn-sm"  title="Impor Database" onclick="document.getElementById('excell').submit();" data-toggle="modal" data-target="#loading"> <i class="fa fa-spin fa-refresh"></i> Impor</a>
																						</div>
																					</div>
																				</td>
																			</tr>
																			<?php if (isset($_SESSION['gagal'])): ?>
																				<tr>
																					<td>
																						<dl class="dl-horizontal">
																							<dt>Jumlah Data Gagal : </dt>
																							<dd><?= $_SESSION['gagal']?></dd>
																						</dl>
																					</td>
																				</tr>
																				<tr>
																					<td>
																						<dl class="dl-horizontal">
																							<dt>Letak Baris Data Gagal : </dt>
																							<dd><?= $_SESSION['baris']?></dd>
																						</dl>
																					</td>
																				</tr>
																				<tr>
																					<td>
																						<dl class="dl-horizontal">
																							<dt>Total Keluarga Terimpor :</dt>
																							<dd><?= $_SESSION['total_keluarga']?></dd>
																						</dl>
																					</td>
																				</tr>
																				<tr>
																					<td>
																						<dl class="dl-horizontal">
																							<dt>Total Penduduk Terimpor :</dt>
																							<dd><?= $_SESSION['total_penduduk']?></dd>
																						</dl>
																					</td>
																				</tr>
																			<?php endif ?>
																		</tbody>
																	</table>
																</form>
															</div>
														</div>
													</div>
												</div>
												<div class='modal fade' id='loading' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
													<div class='modal-dialog'>
														<div class='modal-content'>
															<div class='modal-header btn-warning'>
																<h4 class='modal-title' id='myModalLabel'>Proses Impor ......</h4>
															</div>
															<div class='modal-body'>
																Harap tunggu sampai proses impor selesai. Proses ini biasa memakan waktu antara 1 (satu) Menit hingga 45 Menit, tergantung kecepatan komputer dan juga jumlah data penduduk yang di masukkan..
																<div class='text-center'>
																	<img src='<?= base_url()?>assets/images/background/loading.gif'>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
											<?php unset($_SESSION['total_keluarga']);?>
											<?php unset($_SESSION['total_penduduk']);?>
											<?php unset($_SESSION['baris']);?>
											<?php unset($_SESSION['gagal']);?>
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
