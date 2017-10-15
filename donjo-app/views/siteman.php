<!DOCTYPE html>
<html>
<head>
 <meta charset="UTF-8">
 <title>SID 3.10 Login</title>
 <link rel="stylesheet" href="<?php echo base_url()?>assets/css/login-new.css" media="screen" type="text/css" />
</head>
<body>
	<div id="loginform">
		<a href="<?php echo site_url()?>first">
		<div id="facebook">
			<div id="sid">SID</div>
			<div id="connect">ver.</div>
			<div id="logo"><img src="<?php echo base_url()?>assets/images/SID-e1351656852451.png"></div>
			<div id="desa">Desa <?php echo unpenetration($desa['nama_desa'])?></div>
			<div id="kec">Kecamatan <?php echo unpenetration($desa['nama_kecamatan'])?></div>
			<div id="kab">Kabupaten <?php echo unpenetration($desa['nama_kabupaten'])?></div>
		</div>
		</a>
		<div id="mainlogin">
		<div id="or">3.10</div>
		<h1>Masukkan Username dan Password</h1>
		<form action="<?php echo site_url('siteman/auth')?>" method="post">
		<input name="username" type="text" placeholder="username" value="" required>
		<input name="password" type="password" placeholder="password" value="" required>
		<button type="submit" id="but">LOGIN</button>
			<?php if($_SESSION['siteman']==-1){?>
			<div id="note">
				Login Gagal. Username atau Password yang Anda masukkan salah!
			</div>
			<?php }elseif($_SESSION['siteman']== -2){?>
			<div id="note">
				Tidak ada aktivitas dalam jangka waktu yang cukup lama. Demi keamanan silakan Login kembali.
			</div>
			<?php } unset($_SESSION['siteman']);?>
		</form>
		</div>
		<div id="facebook2">
			<div id="kab2"><a href="http://combine.or.id" target="_blank"><img align=center src="<?php echo base_url()?>assets/images/logo-combine.png"></a></div>
		</div>
	</div>
</body>
</html>