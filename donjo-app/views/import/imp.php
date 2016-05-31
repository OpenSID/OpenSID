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

										<li value="1">Pastikan format data yang akan diimport sudah sesuai dengan aturan import data:
										<dl>
										<dl>-> Boleh menggunakan tanda ' (petik satu) dalam penggunaan nama, 
										<br><dl>-> Struktur RT RW, jika tidak ada dalam struktur wilayah desa diganti dengan tanda � (min/strip/dash)
										<br><dl>-> Data (Jenis Kelamin, Agama, Pendidikan, Pekerjaan, Status Perkawinan, Status Hubungan dalam Keluarga, Kewarganegaraan, Golongan darah, Jamkesmas, raskin, klasifikasi sosial ekonomi) terwakili dengan Kode Nomor. Misal : laki-laki terwakili dengan nomor 1 dan perempuan dengan nomor 2<br>
										</dl>
										<li>Simpan (Save) file Excel sebagai .xls file (jika Anda memakai excel 2007 gunakan Save As pilih format .xls) </ul>
										<li>Pastikan format excel ber-ekstensi .xls format Excel 2003</ul>

										<li>Data yang dibutuhkan untuk Import dengan memenuhi aturan data<a href="<?php echo base_url()?>assets/import/ATURANDATA.xls"> sebagai berikut</a><br>
										<li>Contoh urutan format dapat dilihat pada <a href="<?php echo base_url()?>assets/import/ContohFormat.xls">tautan berikut</a><br>
										</ol>
										</p>
									</td>
									<td>
									&nbsp;
									</td>
								</tr>
								<tr>
									<td width="150">
										Pilih File .xls:
									</td>
									<td width="250">
										<input name="userfile" type="file" />
									<td>
										<a href="#" onclick="document.getElementById('excell').submit();" class="uibutton special" value="Import" target="confirm2" message="Harap tunggu sampai proses import selesai. Prosses ini biasa memakan waktu antara 1 (satu) Menit hingga 45 Menit, tergantung kecepatan komputer dan juga jumlah data penduduk yang di masukkan.<div align='center'><img src='<?php echo base_url()?>assets/images/background/loading.gif'></div>" header="Proses Import Sedang Berjalan.">Import</a>
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
							
							<!--impor data siak-->
							<div>
								<h4>Impor Data SIAK</h4>
								<div>
									<?php
									if(strlen(@$_SESSION["SIAK"])>1){
										echo $_SESSION["SIAK"];
									}
									$_SESSION["SIAK"] = "";
									
									$max_upload = (int)(ini_get('upload_max_filesize'));
									$max_post = (int)(ini_get('post_max_size'));
									$memory_limit = (int)(ini_get('memory_limit'));
									$upload_mb = min($max_upload, $max_post, $memory_limit);
									echo "<p>Batas Maksimal Pengunggahan Berkas <strong>".$upload_mb." MB</strong></p>
									<p>Proses ini akan membutuhkan waktu beberapa menit, menyesuaikan dengan spesifikasi
									komputer server SID dan sambungan internet yang tersedia.</p>";
									
									?>
								</div>
								<div>
									<form id="mainform" action="<?php echo $form_action2; ?>" method="post" enctype="multipart/form-data">
									<table>
										<tr><td>Berkas DK :</td>
											<td><input type="file" name="file_dk" id="file_dk"/></td></tr>
										<tr><td>Berkas BW :</td>
											<td><input type="file" name="file_bw" id="file_bw"/></td></tr>
										<tr><td>&nbsp;</td>
											<td>
												<a onclick="formAction('mainform','<?php echo $form_action2 ?>')" class="uibutton special"  target="confirm2" message="Proses Import Sedang Berlangsung!!!" header="Harap Tunggu"> Import </a>
											</td></tr>
									</table>
									</form>
								</div>
							</div>
							<!--impor data siak-->
            </div>
				<div class="ui-layout-south panel bottom">
					<div class="left"> 
						<div class="table-info"></div>
        </div>
        <div class="right">
        </div>
			</div>
		</div>
	</td></tr></table>
</div>

<?php unset($_SESSION['sukses']);?>
<?php unset($_SESSION['baris']);?>
<?php unset($_SESSION['gagal']);?>
