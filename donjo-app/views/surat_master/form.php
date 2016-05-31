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
	<th>Kode Surat</th>
	<td><input name="kode_surat" type="text" class="inputbox required" size="12" value="<?php echo $surat_master['kode_surat']?>"/></td>
	</tr>
	<tr>
	<th>Nama Layanan</th>
	<td>SURAT <input name="nama" type="text" class="inputbox required" size="50" value="<?php echo $surat_master['nama']?>"/></td>
	</tr>
	</table>
	</div>
		 
	<div class="ui-layout-south panel bottom">
	<div class="left"> 
	<a href="<?php echo site_url()?>surat_master" class="uibutton icon prev">Kembali</a>
	</div>
	<div class="right">
	<div class="uibutton-group">
	<button class="uibutton" type="reset">Clear</button>
	<button class="uibutton confirm" type="submit" >Simpan</button>
	</div>
	</div>
	</div> </form>
	</div>
</div>
