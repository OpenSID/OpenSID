											<div class="tab-pane <?= jecho($act_tab, 2, 'active') ?>">
												<div class="row">
													<div class="col-md-12">
														<div class="box-header with-border">
															<h3 class="box-title"><strong>Impor Data Kependudukan</strong></h3>
														</div>
														<div class="box-body">
															<form action="<?= $form_action ?>" method="post" enctype="multipart/form-data" id="excell" class="form-horizontal">
																<div class="row">
																	<div class="col-sm-12">
																		<p>Mempersiapkan data dengan bentuk excel untuk Impor ke dalam database SID : </p>
																		<p>
																		<div class="row">
																			<ol>
																				<li value="1">Pastikan format data yang akan diImpor sudah sesuai dengan aturan Impor data:</li>
																				<div class="row">
																					<ul>
																						<li> Boleh menggunakan tanda ' (petik satu) dalam penggunaan nama,</li>
																						<li> Kolom Nama, Dusun, RW, RT dan NIK harus diisi. Tanda '-' bisa dipakai di mana RW atau RT tidak diketahui atau tidak ada,</li>
																						<li> Data Penduduk yang dapat menampilkan data RT/RW/Dusun pada tabel Kependudukan adalah Status Hubungan Dalam Keluarga = Kepala Keluarga atau penduduk yang memiliki Kepala Keluarga</li>
																						<li> NIK harus bilangan dengan 16 angka atau 0 untuk menunjukkan belum ada NIK,</li>
																						<li> Data (Jenis Kelamin, Agama, Pendidikan, Pekerjaan, Status Perkawinan, Status Hubungan dalam Keluarga, Kewarganegaraan, Golongan darah) terwakili dengan Kode Nomor. Misal : laki-laki terwakili dengan nomor 1 dan perempuan dengan nomor 2</li>
																					</ul>
																				</div>
																				<li>Simpan (Save) file Excel sebagai .xlsx file </li>
																				<li>Pastikan format excel ber-ekstensi .xlsx (format Excel versi 2007 ke atas)</li>
																				<li>Data yang dibutuhkan untuk Impor dengan memenuhi urutan format dan aturan data pada tautan di bawah ini :
																					<div class="timeline-footer row">
																						<a href="<?= asset('import/FormatImportExcel.xlsm') ?>" class="btn btn-social btn-flat btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block margin" wrap><i class="fa fa-download"></i> Aturan dan contoh format</a>
																						<a href="<?= asset('import/contoh_penduduk.xlsx') ?>" class="btn btn-social btn-flat btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block margin" wrap><i class="fa fa-download"></i> Contoh penduduk ekspor</a>
																					</div>
																				</li>
																			</ol>
																		</div>
																	</p>
																	<p>File pada tautan itu dapat dipergunakan untuk memasukkan data penduduk. Klik 'Enable Macros' pada waktu membuka file itu.</p>
																	<p>
																		<p>Batas maksimal pengunggahan berkas <strong><?= max_upload() ?> MB.</strong></p>
																		<p>Proses ini akan membutuhkan waktu beberapa menit, menyesuaikan dengan
																			spesifikasi komputer server SID dan sambungan internet yang tersedia.</p>
																	</p>
																	<table class="table table-bordered" >
																		<tbody>
																			<tr>
																				<td>
																					<div class="form-group">
																						<label for="file"  class="col-md-2 col-lg-3 control-label">Pilih File .xlsx:</label>
																						<div class="col-sm-12 col-md-5 col-lg-5">
																							<div class="input-group input-group-sm">
																								<input type="text" class="form-control" id="file_path" name="userfile">
																								<input type="file" class="hidden" id="file" name="userfile">
																								<span class="input-group-btn">
																									<button type="button" class="btn btn-info btn-flat"  id="file_browser"><i class="fa fa-search"></i> Browse</button>
																								</span>
																							</div>
																							<?php if ($boleh_hapus_penduduk): ?>
																								<p class="help-block"><input type="checkbox" name="hapus_data" value="hapus"></input>	Hapus data penduduk sebelum Impor</p>
																							<?php endif; ?>
																						</div>
																						<div class="col-sm-12 col-md-5 col-lg-4">
																							<a href="#" class="btn btn-block btn-success btn-sm" title=" Impor Data Penduduk Hapus data penduduk sebelum impor " onclick="document.getElementById('excell').submit();" data-toggle="modal" data-target="#loading"> <i class="fa fa-spin fa-refresh"></i> Impor Data Penduduk</a>
																						</div>
																					</div>
																				</td>
																			</tr>
																			<?php if ($pesan_impor = session('pesan_impor')): ?>
																				<tr>
																					<td>
																						<dl class="dl-horizontal">
																							<dt>Jumlah Data Gagal : </dt>
																							<dd><?= $pesan_impor['gagal']?></dd>
																						</dl>
																					</td>
																				</tr>
																				<tr>
																					<td>
																						<dl class="dl-horizontal">
																							<dt>Jumlah Data Ganda : </dt>
																							<dd><?= $pesan_impor['ganda']?></dd>
																						</dl>
																					</td>
																				</tr>
																				<?php if ($pesan_impor['pesan']) : ?>
																					<tr>
																						<td>
																							<dl class="dl-horizontal">
																								<dt>Rician Pesan : </dt>
																								<dd><?= $pesan_impor['pesan'] ?></dd>
																							</dl>
																						</td>
																					</tr>
																				<?php endif ?>
																				<tr>
																					<td>
																						<dl class="dl-horizontal">
																							<dt>Total Data Berhasil :</dt>
																							<dd><?= $pesan_impor['sukses'] ?></dd>
																						</dl>
																					</td>
																				</tr>
																			<?php endif ?>
																		</tbody>
																	</table>
																</div>
															</div>
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
	</section>
</div>
