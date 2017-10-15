<div id="pageC"> 
<!-- Start of Space Admin -->
<div id="contentpane">
	<div class="ui-layout-north panel">
		<h3>Import Database Kependudukan</h3>
	</div>
	<div class="ui-layout-center" id="maincontent" style="padding: 5px;">
		<div class="left">
				<!--impor data xls-->
				
		<h4>Import Data Penduduk dan Keluarga (Basis NIK dan Nomor KK)</h4>
				<form action="<?php echo $form_action?>" method="post" enctype="multipart/form-data" id="excell">
				<table class="form">
					<tr>
						<td width="500" colspan="3">
							<p font-size="14px";>
							Mempersiapkan database dengan tipe spreadsheet (.xls) untuk diimport ke dalam database SID:
							<br>
							<ol>
							<li value="1">Pastikan format data yang akan diimport sudah sesuai dengan aturan import data:
							<dl>
							<dl>-> Boleh menggunakan tanda ' (petik satu) dalam penggunaan nama, 
							<br><dl>-> Struktur RT RW, jika tidak ada dalam struktur wilayah desa diganti dengan tanda (min/strip/dash)
							<br><dl>-> Data (Jenis Kelamin, Agama, Pendidikan, Pekerjaan, Status Perkawinan, Status Hubungan dalam Keluarga, Kewarganegaraan, Golongan darah, Jamkesmas, raskin, klasifikasi sosial ekonomi) terwakili dengan Kode Nomor. Misal : laki-laki terwakili dengan nomor 1 dan perempuan dengan nomor 2<br>
							</dl>
							<li>Simpan (Save) file spreadsheet sebagai .xls file (jika Anda memakai excel 2007 gunakan Save As pilih format .xls) </ul>
							<li>Pastikan tipe spreadsheet berekstensi .xls format Excel 2003</ul>
							<li>Data yang dibutuhkan untuk import dengan memenuhi aturan data.
							</ol>
							UNDUH LAMPIRAN : <a class="uibutton confirm" href="<?php echo base_url()?>assets/import/ATURANDATA.xls">Aturan Data</a>
							<a class="uibutton confirm" href="<?php echo base_url()?>assets/import/ContohFormat.xls">Contoh Format</a><br>
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
							<a href="#" onclick="document.getElementById('excell').submit();" class="uibutton special" value="Import" target="confirm2" message="Harap tunggu sampai proses import selesai. Proses ini dapat memakan waktu antara 1 (satu) menit hingga 45 menit, tergantung kecepatan komputer dan jumlah data penduduk yang dimasukkan.<div align='center'><img src='<?php echo base_url()?>assets/images/background/loading.gif'></div>" header="Proses Import Sedang Berjalan.">Import Data Penduduk</a>
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
						<p>Total Data Berhasil:
						</td>
						<td colspan="3">
						
							<?php echo $_SESSION['sukses']?>
						</td>
					</tr>
				<?php }?>
				</table>
				</form>
				<!--impor data xls-->
		<br>
		<h4>Import Pengelompokan Data Rumah Tangga</h4>
				<form action="<?php echo $form_action3?>" method="post" enctype="multipart/form-data" id="kelompok">
				<table class="form">
					<tr>
						<td width="500" colspan="3">
							<p font-size="14px";>
							Pengelompokan data penduduk yang sudah tersimpan di dalam database SID, sehingga terkelompokkan secara otomatis berdasarkan nomor urut rumah tangga:
							<br>
							<ol>
							<li value="1">Pastikan format data yang akan diimport sudah sesuai dengan aturan import data:
							<li>Simpan (Save) file speradsheet sebagai .xls file (jika Anda memakai excel 2007 gunakan Save As pilih format .xls) </ul>
							<li>Pastikan tipe spreadsheet berekstensi .xls format Excel 2003</ul>
							<li>Data yang dibutuhkan untuk import dengan memenuhi aturan data.
							</ol>
							UNDUH LAMPIRAN : <a class="uibutton confirm" href="<?php echo base_url()?>assets/import/ATURANGRUP.xls">Aturan Data</a>
							<a class="uibutton confirm" href="<?php echo base_url()?>assets/import/ContohGrup.xls">Contoh Format</a><br>
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
							<a href="#" onclick="document.getElementById('kelompok').submit();" class="uibutton special" value="Import" target="confirm2" message="Harap tunggu sampai proses import selesai. Proses ini dapat memakan waktu antara 1 (satu) menit hingga 45 menit, tergantung kecepatan komputer dan juga jumlah data penduduk yang dimasukkan.<div align='center'><img src='<?php echo base_url()?>assets/images/background/loading.gif'></div>" header="Proses Import Sedang Berjalan.">Import Data Pengelompokan Rumah Tangga</a>
						</td>
						<td>
							&nbsp;
						</td>
					</tr>
				</table>
				</form>
				
		</div>
</div>
<div class="ui-layout-south panel bottom"></div>
</div>
</div>
<?php unset($_SESSION['sukses']);?>
<?php unset($_SESSION['baris']);?>
<?php unset($_SESSION['gagal']);?>