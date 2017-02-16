<script type="text/javascript" src="<?php echo base_url()?>assets/tiny_mce/jquery.tinymce.min.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>assets/tiny_mce/tinymce.min.js"></script>
<script>
$(function(){
 var nik = {};
 nik.results = [
		<?php foreach($list_penduduk as $data){?>
	 {id:'<?php echo $data['id']?>',name:"<?php echo $data['nik']." - ".($data['nama'])?>",info:"<?php echo ($data['alamat'])?>"},
		<?php }?>
		 ];
nik.total = nik.results.length;
$('#id_ketua').flexbox(nik, {
	resultTemplate: '<div><label>No nik : </label>{name}</div><div>{info}</div>',
	watermark: 'Ketik no nik di sini..',
 width: 260,
 noResultsText :'Tidak ada no nik yang sesuai..',
	 onSelect: function() {
		$('#'+'main').submit();
 } 
});
$("#nik_detail").show();
});
</script>
<div id="pageC">
	<table class="inner">
		<tr style="vertical-align:top">
			<td style="background:#fff;padding:0px;"> 
				<div class="content-header"></div>
				<div id="contentpane">
					<div class="ui-layout-north panel"><h3>Form Master kelompok</h3></div>
					<form id="validasi" action="<?php echo $form_action?>" method="POST" enctype="multipart/form-data">
						<div class="ui-layout-center" id="maincontent" style="padding: 5px;">
							<table class="form">
								<tr>
									<th>Nama kelompok</th>
									<td><input name="nama" type="text" class="inputbox" size="80" value="<?php echo $kelompok['nama']?>"/></td>
								</tr>
								<tr>
									<th>Nomor / Kode kelompok</th>
									<td><input name="kode" type="text" class="inputbox" size="80" value="<?php echo $kelompok['kode']?>"/></td>
								</tr>
								<tr>
									<th width="100">Master Kelompok</th>
									<td>
										<select name="id_master" onchange="formAction('mainform','<?php echo site_url('kelompok/filter')?>')" class="required">	
											<option value="">-- Mater Kelompok --</option>
											<?php foreach($list_master AS $data){?>
												<option value="<?php echo $data['id']?>" <?php if($kelompok['id_master'] == $data['id']) :?>selected<?php endif?>><?php echo $data['kelompok']?></option>
											<?php }?>
										</select>
									</td>
								</tr>
								<tr>
									<th>Nama/NIK Pimpinan</th>
									<td>
										<div id="id_ketua" name="id_ketua"></div>
									</td>
								</tr>
								<th colspan="2">Deskripsi kelompok</th>
								</tr>
								<tr>
									<td colspan="2">
										<textarea name="keterangan" style="width: 600px; height: 300px;resize:none;"><?php echo $kelompok['keterangan']?></textarea>
									</td>
								</tr> 
							</table>
						</div>
						<div class="ui-layout-south panel bottom">
							<div class="left"> 
								<a href="<?php echo site_url()?>kelompok" class="uibutton icon prev">Kembali</a>
							</div>
							<div class="right">
								<div class="uibutton-group">
									
									<button class="uibutton confirm" type="submit" >Simpan</button>
								</div>
							</div>
						</div>
					</form>
				</div>
			</td>
		</tr>
	</table>
</div>