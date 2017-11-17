<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
	<title><?php
		echo $this->setting->login_title
			. ' ' . ucwords($this->setting->sebutan_desa)
			. (($desa['nama_desa']) ? ' ' . unpenetration($desa['nama_desa']) : '')
			. get_dynamic_title_page_from_path();
	?></title>
  <link rel="stylesheet" href="<?php echo base_url()?>assets/css/login-new.css" media="screen" type="text/css" />
	<?php if(is_file("desa/css/siteman.css")): ?>
		<link type='text/css' href="<?php echo base_url()?>desa/css/siteman.css" rel='Stylesheet' />
	<?php endif; ?>
	<?php if(is_file(LOKASI_LOGO_DESA . "favicon.ico")): ?>
		<link rel="shortcut icon" href="<?php echo base_url()?><?php echo LOKASI_LOGO_DESA?>favicon.ico" />
	<?php else: ?>
		<link rel="shortcut icon" href="<?php echo base_url()?>favicon.ico" />
	<?php endif; ?>
</head>
<body class="login">
	<div id="loginform">
		<a href="<?php echo site_url()?>first">
		<div id="facebook">
			<div id="sid">SID</div>
			<div id="connect">ver.</div>
			<div id="logo"><img src="<?php echo base_url()?>assets/images/SID-e1351656852451.png"></div>
			<div id="desa"><?php echo ucwords($this->setting->sebutan_desa)?> <?php echo unpenetration($desa['nama_desa'])?></div>
			<div id="kec"><?php echo ucwords($this->setting->sebutan_kecamatan)?> <?php echo unpenetration($desa['nama_kecamatan'])?></div>
			<div id="kab"><?php echo ucwords($this->setting->sebutan_kabupaten)?> <?php echo unpenetration($desa['nama_kabupaten'])?></div>
		</div>
		</a>
		<div id="mainlogin">
			<div id="or"><?php echo substr(AmbilVersi(), 0, 4)?></div>
			<form action="<?php echo site_url('siteman/auth')?>" method="post">
			  <?php if($_SESSION['siteman_wait']==1) : ?>
					<div id="note">
		        <h2 style="padding:10px;">Gagal 3 kali, silakan coba kembali dalam <?php echo waktu_ind((time()- $_SESSION['siteman_timeout'])*(-1));?> lagi</h2>
			    </div>
			  <?php else: ?>
					<h1>Masukan Username dan Password</h1>
					<input name="username" type="text" placeholder="username" <?php if($_SESSION['siteman_wait']==1) echo 'disabled="disabled"'?> value="" required>
					<input name="password" type="password" placeholder="password" <?php if($_SESSION['siteman_wait']==1) echo 'disabled="disabled"'?> value="" required>
					<button type="submit" id="but">LOGIN</button>
					<?php  if($_SESSION['siteman']==-1){ ?>
						<div id="note">
							<p>Login Gagal. Username atau Password yang Anda masukkan salah!</p>
						</div>
			      <?php  if($_SESSION['siteman_try']){ ?>
				      <div id="note">
				        <p style="padding-top: 8px;">Kesempatan mencoba <?php echo ($_SESSION['siteman_try']-1); ?> kali lagi.</p>
				      </div>
			      <?php }?>
					<?php  } else if($_SESSION['siteman']==-2) { ?>
			 			<div id="note">
			 				Redaksi belum boleh login, SID belum memiliki sambungan internet!
			 			</div>
					<?php }?>
				<?php endif; ?>
			</form>
		</div>
		<div style="clear: both; line-height: 10px;">&nbsp;</div>
		<div id="facebook2">
			<div id="kab2">powered by: <a href="https://github.com/eddieridwan/OpenSID" target="_blank">OpenSID</a></div>
		</div>
	</div>
</body>
</html>
