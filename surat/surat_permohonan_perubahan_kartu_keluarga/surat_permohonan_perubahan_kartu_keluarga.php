<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');?>

<script>
	$(function(){
		var nik = {};
		nik.results = [
			<?php  foreach($kepala_keluarga as $data){?>
				{id:'<?php echo $data['id']?>',name:"<?php echo $data['nik']." - ".($data['nama'])?>",info:"<?php echo ($data['alamat'])?>"},
			<?php  }?>
		];

		$('#nik').flexbox(nik, {
			resultTemplate: '<div><label>No nik : </label>{name}</div><div>{info}</div>',
			watermark: <?php  if($individu){?>'<?php echo $individu['nik']?> - <?php echo ($individu['nama'])?>'<?php  }else{?>'Ketik no nik di sini..'<?php  }?>,
			width: 260,
			noResultsText :'Tidak ada no nik yang sesuai..',
			onSelect: function() {
				$('#'+'main').submit();
		}
		});

	});
</script>


<style>
table.form.detail th{
	padding:5px;
	background:#fafafa;
	border-right:1px solid #eee;
}
table.form.detail td{
	padding:5px;
}
</style>

<div id="pageC">
	<table class="inner">
	<tr style="vertical-align:top">

	<td style="background:#fff;">
		<div id="contentpane">
			<div class="ui-layout-center" id="maincontent" style="padding: 5px;">
				<h3>Surat Permohonan Perubahan Kartu Keluarga (F-1.16 & F-1.01)</h3>
				<div id="form-cari-pemohon">
					<form action="" id="main" name="main" method="POST" class="formular">
					<table class="form">
					<tr>
						<td colspan="2" style="height: auto;">
				    	<div class="box-perhatian">
				      	<p><strong>Form ini menghasilkan:<br><br>
				      	1. Surat Permohonan Perubahan Kartu Keluarga<br>
				      	2. Lampiran F-1.16 FORMULIR PERMOHOHAN PERUBAHAN KARTU KELUARGA (KK) BARU WARGA NEGARA INDONESIA<br>
						3. Lampiran F-1.01 FORMULIR ISIAN BIODATA PENDUDUK UNTUK WNI (PER KELUARGA) untuk keluarga pemohon.<br><br>
				      	Pastikan semua biodata pemohon beserta keluarga sudah lengkap sebelum mencetak surat dan lampiran.<br>
				      	Untuk melengkapi data itu, ubah data pemohon dan anggota keluarganya di form isian penduduk di modul Penduduk.<br><br>
				      	Formulir di atas mengacu pada Peraturan Menteri Dalam Negeri Nomor 19 Tahun 2010.
				      	</strong></p>
				    	</div>
				    </td>
				  </tr>
						<tr>
							<th width="40%">NIK / Nama Pemohon</td>
							<td width="60%">
								<div id="nik" name="nik"></div>
							</td>
						</tr>
					</table>
					</form>
				</div>
				<div id="form-melengkapi-data-permohonan">
					<form id="validasi" action="" method="POST" target="_blank">
					<input type="hidden" name="nik" value="<?php echo $individu['id']?>" class="inputbox required" >
					<table class="form">
						<?php if($individu){?>
							<?php include("donjo-app/views/surat/form/konfirmasi_pemohon.php"); ?>
						<?php
						}
						?>
						<tr>
							<th width="40%">Nomor Surat</th>
							<td width="60%"><input name="nomor" type="text" class="inputbox required" size="12"/> <span>Terakhir: <?php echo $surat_terakhir['no_surat'];?> (tgl: <?php echo $surat_terakhir['tanggal']?>)</span></td>
						</tr>
						<tr>
						  <th>Alasan Permohonan</th>
						  <td>
							<input name="sebab_nama" type="hidden">
							<select name="sebab" class="required" onchange="$('input[name=sebab_nama]').val($(this).find(':selected').data('sebabnama'));">
							  <option value="">Pilih Alasan Permohonan Perubahan Kartu Keluarga</option>
							  <?php foreach($sebab as $id => $nama){?>
								<option value="<?php echo $id?>" data-sebabnama="<?php echo $nama; ?>" <?php if($id==$_SESSION['post']['sebab']) echo 'selected'?>><?php echo $nama; ?></option>
							  <?php }?>
							</select>
						  </td>
						</tr>
						<tr>
							<th width="40%">Alasan Lainnya</th>
							<td width="60%"><input name="alasan_lainnya" type="text" class="inputbox" size="50"/> <span>*)<i>Diisi apabila pilihan Alasan Permohonan yang dipilih adalah Lainnya.</i></span></td>
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
							<button class="uibutton" type="reset"><span class="fa fa-refresh"></span> Bersihkan</button>
							<?php if (SuratExport($url)) { ?><button type="button" onclick="$('#'+'validasi').attr('action','<?php echo $form_action2?>');$('#'+'validasi').submit();" class="uibutton confirm"><span class="fa fa-file-text">&nbsp;</span>Export Doc</button><?php } ?>
						</div>
					</div>
				</div>
			</form>
			</td>
		</tr>
	</table>
</div>
