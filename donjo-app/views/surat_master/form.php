<div id="pageC">
	<div class="content-header">

	</div>
	<div id="contentpane">
		<div class="ui-layout-north panel"><h3>Form Layanan Administrasi Surat</a></h3>
		</div>
		<form id="validasi" action="<?php echo $form_action?>" method="POST" enctype="multipart/form-data">
			<div class="ui-layout-center" id="maincontent" style="padding: 5px;">
				<table class="form">
					<tr>
						<th>Kode/Klasifikasi Surat</th>
						<td><input name="kode_surat" type="text" class="inputbox required" size="20" value="<?php echo $surat_master['kode_surat']?>"/></td>
					</tr>
					<tr>
						<th>Nama Layanan</th>
						<td>SURAT <input name="nama" type="text" class="inputbox required" size="50" value="<?php echo $surat_master['nama']?>"/></td>
					</tr>
					<?php if (strpos($form_action, 'insert') !== false) :?>
						<tr>
							<th>Pemohon Surat</th>
							<td>
						    <select name="pemohon_surat" class="required">
					        <option value="warga" selected>Warga</option>
					        <option value="non_warga">Bukan Warga</option>
							  </select>
							</td>
						</tr>
					<?php endif; ?>
				</table>
			</div>

			<div class="ui-layout-south panel bottom">
				<div class="left">
					<a href="<?php echo site_url()?>surat_master" class="uibutton icon prev">Kembali</a>
				</div>
				<div class="right">
					<div class="uibutton-group">
						<button class="uibutton" type="reset"><span class="fa fa-refresh"></span> Bersihkan</button>
						<button class="uibutton confirm" type="submit" ><span class="fa fa-save"></span> Simpan</button>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>
