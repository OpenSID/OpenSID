
<form id="validasi" action="<?= $form_action; ?>" method="post" class="form-login">
	<div class="form-group form-login">
		<input type="text" class="form-control required  " name="nik" placeholder="Masukkan NIK" id="nik"  onclick='changeDef("nik")' />
	</div>
	<div class="form-group form-login">
		<input type="password" class="form-control required <?= jecho($cek_anjungan['keyboard'] == 1, TRUE, 'kbvnumber'); ?>" name="pin" placeholder="Masukkan PIN" id="pin" onclick='changeDef("pin")' />
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
<!-- keyboard -->
</div>