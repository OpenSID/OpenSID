
<form id="validasi" action="<?= $form_action; ?>" method="post" class="form-login">
	<div class="form-group form-login">
		<input type="text" class="form-control required  " required name="nik" placeholder="Masukkan NIK" id="nik_keyboard"  onclick2='changeDef("nik_keyboard")' />
	</div>
	<div class="form-group form-login">
		<input type="password" class="form-control required<?= jecho($cek_anjungan['keyboard'] == 1, TRUE, 'kbvnumber'); ?>" name="pin" required placeholder="Masukkan PIN " id="pin_keyboard" onclick2='changeDef("pin_keyboard")' />
	</div>
	<div class="form-group">
		<center><input type="checkbox" id="checkbox"> Tampilkan PIN</center>
	</div>
	<div class="form-group form-login">
		<button type="submit" class="btn btn-block btn-block bg-green"><b>MASUK</b></button>
	</div>
</form>
<p align="center">Silakan hubungi operator <?= $this->setting->sebutan_desa; ?> untuk mendapatkan kode PIN anda.</p>
<div>  
	<a href='<?=site_url('kehadiran/masuk');?>?form=3' class="btn btn-block btn-block bg-green">
	<b>Login Dengan Formulir</b>
	</a>
<!-- keyboard -->
</div>