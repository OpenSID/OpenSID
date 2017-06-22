<style>
table.form.detail th{
	padding:5px;
	background:#fafafa;
	border-right:1px solid #eee;
}
table.form.detail td{
	padding:5px;
}
table.form span.judul{
	padding-left: 10px;
	padding-right: 5px;
}
</style>

<div id="pageC">
	<table class="inner">
	<tr style="vertical-align:top">

	<td style="background:#fff;">
		<div id="contentpane">
			<div class="ui-layout-center" id="maincontent" style="padding: 5px;">
				<h3>Formulir Layanan : Surat Domisili Usaha Non Warga</h3>
				<div id="form-cari-pemohon">
					<form action="" id="main" name="main" method="POST" class="formular">
					<table class="form">
						<tr>
							<th>Nomor Surat</th>
							<td>
							<input name="nomor" type="text" class="inputbox required" size="12"/><span style="padding-left: 10px;">Terakhir: <?php echo $surat_terakhir['no_surat'];?> (tgl: <?php echo $surat_terakhir['tanggal']?>)</span>
							</td>
						</tr>
						<tr>
							<th>Nama</th>
							<td><input name="nama_non_warga" type="text" class="inputbox required" size="80"/></td>
						</tr>
						<tr>
							<th>Identitas</th>
							<td>
								<span class="judul" style="padding-left: 0px;">NIK KTP </span><input name="nik_non_warga" type="text" class="inputbox required" size="30"/>
								<span class="judul"> No. KK </span><input name="kk_non_warga" type="text" class="inputbox required" size="30"/>
							</td>
						<tr>
							<th>Tempat Lahir</th>
							<td>
								<input name="tempatlahir" type="text" class="inputbox required" size="30"/>
								<span class="judul"> Tanggal Lahir </span><input name="tanggallahir" type="text" class="inputbox required datepicker" size="20"/>
								<span class="judul"> Jenis Kelamin </span>
						    <select name="sex" class="required">
						      <option value="">Pilih jenis kelamin</option>
						      <?php foreach($sex as $data){?>
						        <option value="<?php echo ucwords(strtolower($data['nama']))?>"><?php echo $data['nama']?></option>
						      <?php }?>
							  </select>
							</td>
						</tr>
						<tr>
							<th>Warga Negara</th>
							<td>
						    <select name="warga_negara" class="required">
						      <option value="">Pilih warganegara</option>
						      <?php foreach($warganegara as $data){?>
						        <option value="<?php echo $data['id']=='3' ? ucwords(strtolower($data['nama'])) : strtoupper($data['nama'])?>"><?php echo $data['nama']?></option>
						      <?php }?>
							  </select>
							</td>
						</tr>
						<tr>
							<th>Agama</th>
							<td>
						    <select name="agama" class="required">
						      <option value="">Pilih Agama</option>
						      <?php foreach($agama as $data){?>
						        <option value="<?php echo $data['id']=='7' ? $data['nama'] : ucwords(strtolower($data['nama']))?>"><?php echo $data['nama']?></option>
						      <?php }?>
						    </select>
								<span class="judul"> Pekerjaan </span>
						    <select name="pekerjaan" class="required">
						      <option value="">Pilih Pekerjaan</option>
						      <?php  foreach($pekerjaan as $data){?>
						        <option value="<?php echo $data['nama']?>"><?php echo $data['nama']?></option>
						      <?php }?>
						    </select>
							</td>
						</tr>
						<tr>
							<th>Tempat Tinggal</th>
							<td><input name="alamat" type="text" class="inputbox required" size="140"/></td>
						</tr>
						<tr>
							<th>Nama Usaha</th>
							<td>
							<input name="nama_usaha" type="text" class="inputbox required" size="40"/><span class="judul"> Jenis Usaha </span>
							<input name="usaha" type="text" class="inputbox required" size="40"/>
							</td>
						</tr>
						<tr>
							<th>Nomor Akta / Tahun / Notaris</th>
							<td>
							<input name="akta_usaha" type="text" class="inputbox required" size="40"/><span class="judul"> Tahun </span><input name="akta_tahun" type="text" class="inputbox required" size="10"/><span class="judul"> Nama Notaris </span><input name="notaris" type="text" class="inputbox required" size="40"/>
							</td>
						</tr>
						<tr>
							<th>Jenis Bangunan</th>
							<td>
							<input name="bangunan" type="text" class="inputbox required" size="40"/><span class="judul"> Peruntukan Bangunan </span><input name="peruntukan_bangunan" type="text" class="inputbox required" size="40"/><span class="judul"> Status Bangunan </span><input name="status_bangunan" type="text" class="inputbox required" size="40"/>
							</td>
						</tr>
						<tr>
							<th>Alamat Usaha</th>
							<td>
							<input name="alamat_usaha" type="text" class="inputbox required" size="140"/>
							</td>
						</tr>
						<?php include("donjo-app/views/surat/form/_pamong.php"); ?>
					</table>
				</div>
			</div>
				<div class="ui-layout-south panel bottom">
					<div class="left">
						<a href="<?php echo site_url()?>surat" class="uibutton icon prev">Kembali</a>
					</div>
					<div class="right">
						<div class="uibutton-group">
							<button class="uibutton" type="reset">Clear</button>
							<?php if (SuratCetak($url)) { ?>
								<button type="button" onclick="$('#'+'main').attr('action','<?php echo $form_action?>');$('#'+'main').submit();" class="uibutton special"><span class="ui-icon ui-icon-print">&nbsp;</span>Cetak</button>
							<?php } ?>
							<?php if (SuratExport($url)) { ?>
								<button type="button" onclick="$('#'+'main').attr('action','<?php echo $form_action2?>');$('#'+'main').submit();" class="uibutton confirm"><span class="ui-icon ui-icon-document">&nbsp;</span>Export Doc</button>
							<?php } ?>
						</div>
					</div>
				</div>
			</form>
			</td>
		</tr>
	</table>
</div>
