										<div class="tab-pane <?php if ($act_tab==2): ?> active<?php endif ?>">
											<div class="row">
												<div class="col-md-12">
													<div class="box-header with-border">
														<h3 class="box-title"><strong>Impor Data PPLS</strong></h3>
													</div>
													<div class="box-body">
														<div class="row">
															<div class="col-sm-12">
																<p>Mempersiapkan data dengan bentuk excel untuk Impor ke dalam database SID:</p>
																<p>
																	<div class="row">
																		<ol>
																			<li value="1">Pastikan format data yang akan diImpor sudah sesuai dengan aturan Impor data:</li>
																				<div class="row">
																					<ul>
																						<li>  Boleh menggunakan tanda ' (petik satu) dalam penggunaan nama</li>
																						<li> Struktur RT RW, jika tidak ada dalam struktur wilayah desa diganti dengan tanda � (min/strip/dash)</li>
																						<li> Data (Jenis Kelamin, Agama, Pendidikan, Pekerjaan, Status Perkawinan, Status Hubungan dalam Keluarga, Kewarganegaraan, Golongan darah, klasifikasi sosial ekonomi) terwakili dengan Kode Nomor. Misal : laki-laki terwakili dengan nomor 1 dan perempuan dengan nomor 2</li>
																					</ul>
																				</div>
																			<li>Simpan (Save) file Excel sebagai .xls file (jika Anda memakai excel 2007 gunakan Save As pilih format .xls) </li>
																			<li>Pastikan format excel ber-ekstensi .xls format Excel 2003</li>
																			<li>Data yang dibutuhkan untuk Impor dengan memenuhi aturan data berikut
																				<div class="timeline-footer row">
																					<a class="btn btn-social btn-flat btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block margin" href="<?= base_url()?>assets/import/ATURANDATA.xls"><i class="fa fa-download"></i>Unduh Aturan data</a>
																					<a class="btn btn-social btn-flat btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block margin" href="<?= base_url()?>assets/import/ContohFormat.xls" ><i class="fa fa-download"></i>Unduh Contoh Urutan Format</a>
																				</div>
																			</li>
																		</ol>
																	</div>
																</p>
																<table class="table table-bordered" >
																	<tbody>
																		<form action="<?=$form_action2?>" method="post" enctype="multipart/form-data"  class="form-horizontal">
																			<tr>
																				<td style="padding-top:20px;padding-bottom:10px;">
																					<div class="form-group">
																						<label for="file"  class="col-md-3 col-lg-3 control-label">Rumah Tangga .xls:</label>
																						<div class="col-sm-12 col-md-5 col-lg-5">
																							<div class="input-group input-group-sm">
																								<input type="text" class="form-control" id="file_path2" name="userfile">
																								<input type="file" class="hidden" id="file2" name="userfile">
																								<span class="input-group-btn">
																									<button type="button" class="btn btn-info btn-flat"  id="file_browser2"><i class="fa fa-search"></i> Browse</button>
																								</span>
																							</div>
																						</div>
																						<div class="col-sm-12 col-md-2 col-lg-2">
																							<button type="submit" class="btn btn-block btn-success btn-sm"><i class="fa fa-spin fa-refresh"></i> Impor</button>
																						</div>
																					</div>
																				</td>
																			</tr>
																		</form>
																		<form action="<?=$form_action3?>" method="post" enctype="multipart/form-data" class="form-horizontal">
																			<tr>
																				<td style="padding-top:20px;padding-bottom:10px;">
																					<div class="form-group">
																						<label for="file"  class="col-md-3 col-lg-3 control-label">Individu .xls: </label>
																						<div class="col-sm-12 col-md-5 col-lg-5">
																							<div class="input-group input-group-sm">
																								<input type="text" class="form-control" id="file_path" name="userfile">
																								<input type="file" class="hidden" id="file" name="userfile">
																								<span class="input-group-btn">
																									<button type="button" class="btn btn-info btn-flat"  id="file_browser"><i class="fa fa-search"></i> Browse</button>
																								</span>
																							</div>
																						</div>
																						<div class="col-sm-12 col-md-2 col-lg-2">
																							<button type="submit" class="btn btn-block btn-success btn-sm"><i class="fa fa-spin fa-refresh"></i> Impor</button>
																						</div>
																					</div>
																				</td>
																			</tr>
																		</form>
																		<?php if (isset($_SESSION['gagal'])): ?>
																			<tr>
																				<td>
																					<dl class="dl-horizontal">
																						<dt>Jumlah Data Gagal : </dt>
																						<dd><?=$_SESSION['gagal']?></dd>
																					</dl>
																				</td>
																			</tr>
																			<tr>
																				<td>
																					<dl class="dl-horizontal">
																						<dt>Letak Baris Data Gagal : </dt>
																						<dd><?=$_SESSION['baris']?></dd>
																					</dl>
																				</td>
																			</tr>
																			<tr>
																				<td>
																					<dl class="dl-horizontal">
																						<dt>Total Data Berhasil :</dt>
																						<dd><?=$_SESSION['sukses']?></dd>
																					</dl>
																				</td>
																			</tr>
																		<?php endif ?>
																	</tbody>
																</table>
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
