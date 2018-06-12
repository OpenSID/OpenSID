										<div class="tab-pane <?php if($act_tab==3):?> active<?php endif?>">
											<div class="row">
												<div class="col-md-12">
													<div class="box-header with-border">
														<h3 class="box-title"><strong>Backup / Restore Database SID</strong></h3>
													</div>
													<div class="box-body">
														<div class="row">
															<div class="col-sm-12">
																	<form action="<?= $form_action?>" method="post" enctype="multipart/form-data" id="excell" class="form-horizontal">
																		<p>Proses ini untuk mengimpor data Buku Induk Penduduk (BIP) yang diperoleh dari Disdukcapil dalam format Excel.</p>
																		<p>BBIP yang dapat dibaca proses ini adalah yang tersusun berdasarkan keluarga, seperti contoh yang dapat dilihat pada tautan berikut :</P>
																			<a class="btn btn-social btn-flat btn-info btn-sm btn-sm visible-xs-block visible-sm-block visible-md-inline-block visible-lg-inline-block" href="<?= base_url()?>assets/import/format_bip_2012.xls" ><i class="fa fa-download"></i>Contoh BIP 2012</a>
																			<a class="btn btn-social btn-flat btn-info btn-sm btn-sm visible-xs-block visible-sm-block visible-md-inline-block visible-lg-inline-block" href="<?= base_url()?>assets/import/format_bip_2016.xls" ><i class="fa fa-download"></i>Contoh BIP 2016</a>
																			<a class="btn btn-social btn-flat btn-info btn-sm btn-sm visible-xs-block visible-sm-block visible-md-inline-block visible-lg-inline-block" href="<?= base_url()?>assets/import/format_bip_ektp.xls"><i class="fa fa-download"></i>Contoh BIP eKTP</a>
																			<a class="btn btn-social btn-flat btn-info btn-sm btn-sm visible-xs-block visible-sm-block visible-md-inline-block visible-lg-inline-block" href="<?= base_url()?>assets/import/format_bip_2016_luwutimur.xls"><i class="fa fa-download"></i>Contoh BIP 2016 Luwu Timur</a>
																		<p></p>
																		<p>Proses ini mengimpor data keluarga di semua worksheet di berkas BIP. Misalnya, apabila data BIP tersusun menjadi satu worksheet per dusun, proses ini akan mengimpor data semua dusun.</p>
																		<p class="text-muted text-red well well-sm no-shadow" style="margin-top: 10px;">
																			<small>
																				<strong>
																					Pastikan berkas BIP format Excel 2003, ber-ekstensi .xls <br>
																					Sebelum di-impor ganti semua format tanggal (seperti tanggal lahir) menjadi dd/mm/yyyy (misalnya 26/07/1964).
																				</strong>
																			</small>
																		</p>
																		<p>
																		 	<?php
																			  $max_upload = (int)(ini_get('upload_max_filesize'));
																			  $max_post = (int)(ini_get('post_max_size'));
																			  $memory_limit = (int)(ini_get('memory_limit'));
																				$upload_mb = min($max_upload, $max_post, $memory_limit);
																			?>
																			<p>Batas maksimal pengunggahan berkas <strong><?=$upload_mb?> MB.</strong></p>
																			<p>Proses ini akan membutuhkan waktu beberapa menit, menyesuaikan dengan spesifikasi
																				komputer server SID, banyaknya data dan sambungan internet yang tersedia.</p>
																		</p>
																		<table class="table table-bordered" >
																			<tbody>
																				<tr>
																					<td style="padding-top:20px;padding-bottom:10px;">
																						<div class="form-group">
																							<label for="file"  class="col-sm-2 control-label">Pilih File Excel:</label>
																							<div class="col-sm-6">
																								<div class="input-group input-group-sm">
																									<input type="text" class="form-control" id="file_path2" name="userfile">
																									<input type="file" class="hidden" id="file2" name="userfile">
																									<span class="input-group-btn">
																										<button type="button" class="btn btn-info btn-flat"  id="file_browser2"><i class="fa fa-search"></i> Browse</button>
																									</span>
																								</div>
																							</div>
																							<div class="col-sm-4 col-lg-2">
																								<a href="#" class="btn btn-block btn-success btn-sm"  title="Import Database" onclick="document.getElementById('excell').submit();"> <i class="fa fa-spin fa-refresh"></i> Import Database</a>
																							</div>
																						</div>
																						<div class="form-group">
																							<div class="col-sm-offset-2 col-sm-10">
																								<div class="checkbox">
																									<label>
																										<input type="checkbox" name="hapus_data" value="hapus"></input>
																										Hapus data penduduk sebelum import
																									</label>
																								</div>
																							</div>
																						</div>
																					</td>
																				</tr>
																				<?php if(isset($_SESSION['gagal'])):?>
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
																								<dt>Total Data Berhasil :</dt>
																								<dd><?= $_SESSION['sukses']?></dd>
																							</dl>
																						</td>
																					</tr>
																				<?php endif?>
																			</tbody>
																		</table>
																	</form>
															</div>
														</div>
													</div>
												</div>
											</div>
											<?php unset($_SESSION['sukses']);?>
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
