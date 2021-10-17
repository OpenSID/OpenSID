<?php 
$form_action=site_url('layanan-mandiri/kehadiran/lapor');
?><div style='padding:20px'>

	<div class='row'>
		<div class="col-lg-4 col-md-6">Nama:</div> 
		<div class="col-lg-8 col-md-6"><?=@$aparat->pamong_info->nama;?></div>
	</div>
	<div class='row'>
		<div class="col-lg-4 col-md-6">Jabatan:</div> 
		<div class="col-lg-8 col-md-6"><?=@$aparat->pamong_info->jabatan;?></div>
	</div>
<form id="validasi" action="<?= $form_action; ?>" method="post" class="form-login">
	<input type='hidden' name='aparatid' value="<?=@$aparat->pamong_id;?>" />
	<div class="form-group form-login">
		Masukkan Detail Laporan
		<textarea name='lapor_txt'></textarea>
	</div>
	
	<div class="form-group form-login">
		<button type="submit" class="btn btn-block btn-block bg-green"><b>Saya Membuat Laporan</b></button>
	</div>
</form>
</div>