<div id="pageC">
<!-- Start of Space Admin -->
	<table class="inner">
		<tr style="vertical-align:top">
			<td style="background:#fff;padding:0px;">
				<div class="content-header">
				</div>
				<div id="contentpane">
					<div class="ui-layout-north panel">
						<h3>Import Data Desa</h3>
					</div>
					<div class="ui-layout-center" id="maincontent" style="padding: 5px;">
						<div class="left">
							<!--impor data xls-->
							<form action="<?php echo $form_action?>" method="post" enctype="multipart/form-data" id="excell">
	              <table class="form">
									<tr>
										<td width="500" colspan="3">
											<p font-size="14px";>
												Mempersiapkan data dengan bentuk excel untuk import ke dalam database SID:
												<br>
												<ol>
													<li value="1">Pastikan format data yang akan diimport sudah sesuai dengan aturan import data:</li>
													<dl>
													<dl>-> Boleh menggunakan tanda ' (petik satu) dalam penggunaan nama,
													<br><dl>-> Kolom Nama, Dusun, RW, RT dan NIK harus diisi. Tanda '-' bisa dipakai di mana RW atau RT tidak diketahui atau tidak ada,
													<br><dl>-> NIK dan Nomor KK harus didahului dengan tanda ' (petik satu) agar menjadi text pada cell Excel bukan bilangan (yang dibulatkan),
													<br><dl>-> NIK harus bilangan dengan 16 angka atau 0 untuk menunjukkan belum ada NIK,
													<br><dl>-> Data (Jenis Kelamin, Agama, Pendidikan, Pekerjaan, Status Perkawinan, Status Hubungan dalam Keluarga, Kewarganegaraan, Golongan darah) terwakili dengan Kode Nomor. Misal : laki-laki terwakili dengan nomor 1 dan perempuan dengan nomor 2<br>
													</dl>
													<li>Simpan (Save) file Excel sebagai .xls file (jika Anda memakai excel 2007 gunakan Save As pilih format .xls) </li>
													<li>Pastikan format excel ber-ekstensi .xls format Excel 2003</li>
													<li>Data yang dibutuhkan untuk Import dengan memenuhi urutan format dan aturan data pada tautan berikut.</a></li>
												</ol>
												UNDUH CONTOH FORMAT DAN ATURAN : <a class="uibutton confirm" href="<?php echo base_url()?>assets/import/FormatImportExcel.xls"><span class="fa fa-download"></span> Aturan Data dan Contoh Format</a><br> File pada tautan ini dapat dipergunakan untuk memasukkan data penduduk. Klik 'Enable Macros' pada waktu membuka file itu.<br>
											</p>
											<p style="margin-top: 20px;">
												<?php
													$max_upload = (int)(ini_get('upload_max_filesize'));
													$max_post = (int)(ini_get('post_max_size'));
													$memory_limit = (int)(ini_get('memory_limit'));
													$upload_mb = min($max_upload, $max_post, $memory_limit);
													echo "<p>Batas maksimal pengunggahan berkas <strong>".$upload_mb." MB.</strong></p>
														<p>Proses ini akan membutuhkan waktu beberapa menit, menyesuaikan dengan
														spesifikasi komputer server SID dan sambungan internet yang tersedia.</p>";
												?>
											</p>
										</td>
										<td>&nbsp;</td>
									</tr>
									<tr>
										<td width="150">
											Pilih File .xls:
										</td>
										<td width="250">
											<input name="userfile" type="file" />
										<td>
											<a href="#" onclick="document.getElementById('excell').submit();" class="uibutton special" value="Import" target="confirm2" message="Harap tunggu sampai proses import selesai. Prosses ini biasa memakan waktu antara 1 (satu) Menit hingga 45 Menit, tergantung kecepatan komputer dan juga jumlah data penduduk yang di masukkan.<div align='center'><img src='<?php echo base_url()?>assets/images/background/loading.gif'></div>" header="Proses Import Sedang Berjalan."><span class="fa fa-upload"></span> Import</a>
											<input type="checkbox" name="hapus_data" value='hapus' /> Hapus data penduduk sebelum import
										</td>
										<td>
											&nbsp;
										</td>
									</tr>
									<?php if(isset($_SESSION['gagal'])){?>
										<tr>
											<td width="150">
												<p>Jumlah Data Gagal
											</td>
											<td colspan="3">
												<?php echo $_SESSION['gagal']?>
											</td>
										</tr>
										<tr>
											<td width="150">
												<p>Letak Baris Data Gagal:
											</td>
											<td colspan="3">
												<?php echo $_SESSION['baris']?>
											</td>
										</tr>
										<tr>
											<td width="150">
												<p>Tota Data Berhasil:
											</td>
											<td colspan="3">
												<?php echo $_SESSION['sukses']?>
											</td>
										</tr>
									<?php }?>
								</table>
							</form>
								<!--impor data xls-->

	          </div>
						<div class="ui-layout-south panel bottom">
							<div class="left">
								<div class="table-info"></div>
			        </div>
			        <div class="right">
			        </div>
						</div>
					</div>
				</div>
			</td>
		</tr>
	</table>
</div>

<?php unset($_SESSION['sukses']);?>
<?php unset($_SESSION['baris']);?>
<?php unset($_SESSION['gagal']);?>
