<div id="pageC">
<!-- Start of Space Admin -->
	<table class="inner">
	<tr style="vertical-align:top">
		<td style="background:#fff;padding:0px;">
			<div class="content-header">
			</div>
			<div id="contentpane">
				<div class="ui-layout-north panel">
					<h3>Import Data PPLS</h3>
				</div>
				<div class="ui-layout-center" id="maincontent" style="padding: 5px;">
					<div class="left">
							<!--impor data xls-->
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
										<br><dl>-> Data (Jenis Kelamin, Agama, Pendidikan, Pekerjaan, Status Perkawinan, Status Hubungan dalam Keluarga, Kewarganegaraan, Golongan darah, klasifikasi sosial ekonomi) terwakili dengan Kode Nomor. Misal : laki-laki terwakili dengan nomor 1 dan perempuan dengan nomor 2<br>
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
							<form action="<?php echo $form_action2?>" method="post" enctype="multipart/form-data">
									<td width="150">
										Rumah Tangga .xls:
									</td>
									<td width="250">
										<input name="userfile" type="file" />
									<td>
										<button class="uibutton special" type="submit"><span class="fa fa-upload"></span> Import</button>
									</td>
									<td>
										&nbsp;
									</td>
							</form>
								</tr>
								<tr>
							<form action="<?php echo $form_action3?>" method="post" enctype="multipart/form-data">
									<td width="150">
										Individu .xls:
									</td>
									<td width="250">
										<input name="userfile" type="file" />
									<td>
										<button class="uibutton special" type="submit"><span class="fa fa-upload"></span> Import</button>
									</td>
									<td>
										&nbsp;
									</td>
							</form>
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
							<!--impor data xls-->

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
