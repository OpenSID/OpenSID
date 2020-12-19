											<div class="tab-pane <?php if ($act_tab==2): ?> active<?php endif ?>">
												<div class="row">
													<div class="col-md-12">
														<div class="box-header with-border">
															<h3 class="box-title"><strong>Impor Data Kependudukan</strong></h3>
														</div>
														<div class="box-body">
															<form action="<?= $form_action?>" method="post" enctype="multipart/form-data" id="excell" class="form-horizontal">
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
																						<a href="<?= base_url()?>assets/import/FormatImportExcel.xlsm" class="btn btn-social btn-flat btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block margin" wrap><i class="fa fa-download"></i> Aturan dan contoh format</a>
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
																							<p class="help-block"><input type="checkbox" name="hapus_data" value="hapus"></input>	Hapus data penduduk sebelum Impor</p>
																						</div>
																						<div class="col-sm-12 col-md-5 col-lg-4">
																							<a href="#" class="btn btn-block btn-success btn-sm" title=" Impor Data Penduduk Hapus data penduduk sebelum impor " onclick="document.getElementById('excell').submit();" data-toggle="modal" data-target="#loading"> <i class="fa fa-spin fa-refresh"></i> Impor Data Penduduk</a>
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
														</form>
													</div>
												</div>
												<div class="col-md-12">
													<div class="box-header with-border">
														<h3 class="box-title"><strong>Impor Pengelompokan Data Rumah Tangga</strong></h3>
													</div>
													<div class="box-body">
														<form action="<?=$form_action3?>" method="post" enctype="multipart/form-data" id="kelompok" class="form-horizontal">
															<div class="row">
																<div class="col-sm-12">
																	<p>Pengelompokan data penduduk yang sudah tersimpan di dalam database SID, sehingga terkelompokkan secara otomatis berdasarkan nomor urut rumah tangga: </p>
																	<p>
																		<div class="row">
																			<ol>
																				<li value="1">Pastikan format data yang akan diimpor sudah sesuai dengan aturan impor data</li>
																				<li>Simpan (Save) file spreadsheet sebagai .xlsx file</li>
																				<li>Pastikan format excel ber-ekstensi .xlsx (format Excel versi 2007 ke atas)</li>
																				<li>Data yang dibutuhkan untuk impor dengan memenuhi aturan data, lihat tautan di bawah ini :
																					<div class="timeline-footer row">
																						<a class="btn btn-social btn-flat btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block margin" href="<?= base_url()?>assets/import/ATURANGRUP.xlsx" ><i class="fa fa-download"></i> Aturan Data</a>
																						<a class="btn btn-social btn-flat btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block margin" href="<?= base_url()?>assets/import/ContohGrup.xlsx"><i class="fa fa-download"></i> Contoh Format</a>
																					</div>
																				</li>
																			</ol>
																		</div>
																	</p>
																	<p>Proses ini dapat memakan waktu antara 1 (satu) menit hingga 45 menit, tergantung kecepatan komputer dan juga jumlah data penduduk yang dimasukkan.</p>
																	<table class="table table-bordered" >
																		<tbody>
																			<tr>
																				<td style="padding-top:20px;padding-bottom:10px;">
																					<div class="form-group">
																						<label for="file" class="col-md-2 col-lg-3 control-label">Pilih File .xlsx:</label>
																						<div class="col-sm-12 col-md-5 col-lg-5">
																							<div class="input-group input-group-sm">
																								<input type="text" class="form-control" id="file_path2" name="userfile">
																								<input type="file" class="hidden" id="file2" name="userfile">
																								<span class="input-group-btn">
																									<button type="button" class="btn btn-info btn-flat"  id="file_browser2"><i class="fa fa-search"></i> Browse</button>
																								</span>
																							</div>
																						</div>
																						<div class="col-sm-12 col-md-5 col-lg-4">
																							<a href="#" class="btn btn-block btn-success btn-sm"  title="Impor Data Pengelompokan Rumah Tangga" onclick="document.getElementById('kelompok').submit();" data-toggle="modal" data-target="#loading"> <i class="fa fa-spin fa-refresh"></i> Impor Data Pengelompokan Rumah Tangga</a>
																						</div>
																					</div>
																				</td>
																			</tr>
																		</tbody>
																	</table>
																</div>
															</div>
														</form>
													</div>
													<div class='modal fade' id='loading' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
														<div class='modal-dialog'>
															<div class='modal-content'>
																<div class='modal-header btn-warning'>
																	<h4 class='modal-title' id='myModalLabel'>Proses Impor ......</h4>
																</div>
																<div class='modal-body'>
																	Harap tunggu sampai proses impor selesai. Proses ini biasa memakan waktu antara 1 (satu) Menit hingga 45 Menit, tergantung kecepatan komputer dan juga jumlah data penduduk yang di masukkan.
																	<div class='text-center'>
																		<img src='<?= base_url()?>assets/images/background/loading.gif'>
																	</div>
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
		</div>
	</section>
</div>
