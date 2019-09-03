<?php
error_reporting(0
); 
?>
<!-- -------------------------------------------------------------------
|INSTALL MANAGER BY LABKODING.ID : NAZRUL
------------------------------------------------------------------- -->

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
	 <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta charset="UTF-8">
    <title>INSTALL MANAGER OPENSID</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
	 <!-- google lato font -->
	 <link href='https://fonts.googleapis.com/css?family=Lato:400,700,900,300' rel='stylesheet' type='text/css'>
	<!-- font awesome -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <!-- normalize & reset style -->
    <link rel="stylesheet" href="../install/assets/normalize.min.css"  type='text/css'>
    <link rel="stylesheet" href="../install/assets/reset.min.css"  type='text/css'>
    <!-- Bootstrap Core CSS -->
    <link href="../install/assets/bootstrap.min.css" rel="stylesheet">
	<style media="screen">body {
    font-family: 'Poppins';font-size: 15px;}.hide{display:none}body{background:url('assets/login.jpg') no-repeat center center fixed;-webkit-background-size:cover;-moz-background-size:cover;-o-background-size:cover;background-size:cover;color:#34495e;font-family:Lato,sans-serif}.installmodal-container{padding:30px;max-width:700px;width:100%!important;background-color:#f7f7f7;margin:0 auto;margin-top:150px;border-radius:2px;box-shadow:0 2px 2px rgba(0,0,0,.3);overflow:hidden}.modal-backdrop{background:0 0}.logo{margin-top:50px}.nav-tabs>li.active>a,.nav-tabs>li.active>a:focus,.nav-tabs>li.active>a:hover{background:0 0;border:none;background-color:#34495e;color:#fff;border-radius:3px}.nav-tabs>li>a{text-align:center;color:#34495e;font-weight:400;text-transform:uppercase;border:none;font-size:18px}.nav-tabs>li>a>p{font-weight:500;font-size:10px;margin:0;text-transform:capitalize}.nav-tabs>li>a:hover{background:0 0;border:none}.nav-tabs{border:none}.label-success{background-color:#1abc9c}.btn-next{background-color:#34495e;color:#fff;float:right}.btn-next:hover{background-color:#425c77;color:#fff}</style>
  </head>
  <body>

<?php  

require_once('includes/core_class.php');
require_once('includes/database_class.php');
$db_config_path="../donjo-app/config/database.php";
$db_config_path_desa="../desa-contoh/config/database.php";
$installOpensid="INSTALL_OPENSID"; ?>
<center><img alt="logo"class="logo"height="71px"src="../install/assets/logo.png"></center><div class="fade modal"id="install-modal"role="dialog"tabindex="-1"><div class="modal-dialog"><div class="installmodal-container"><?php if(is_file($installOpensid)){$step=isset($_GET['step'])?$_GET['step']:'0';switch($step){default:$error=FALSE;if(phpversion()<"5.3"){$error=TRUE;$check1="<span class='label label-danger'>Your PHP version is ".phpversion()."</span>";}else{$check1="<span class='label label-success'>v.".phpversion()."</span>";}if(!extension_loaded('mcrypt')){$error=TRUE;$check2="<span class='label label-danger'><i class='fa fa-times' aria-hidden='true'></i></span>";}else{$check2="<span class='label label-success'><i class='fa fa-check' aria-hidden='true'></i></span>";}if(!extension_loaded('mbstring')){$error=TRUE;$check4="<span class='label label-danger'><i class='fa fa-times' aria-hidden='true'></i></span>";}else{$check4="<span class='label label-success'><i class='fa fa-check' aria-hidden='true'></i></span>";}if(!extension_loaded('mysqli')){$error=TRUE;$check12="<span class='label label-danger'><i class='fa fa-times' aria-hidden='true'></i></span>";}else{$check12="<span class='label label-success'><i class='fa fa-check' aria-hidden='true'></i></span>";}if(!extension_loaded('gd')){$check5="<span class='label label-danger'><i class='fa fa-times' aria-hidden='true'></i></span>";}else{$check5="<span class='label label-success'><i class='fa fa-check' aria-hidden='true'></i></span>";}if(!extension_loaded('pdo')){$error=TRUE;$check6="<span class='label label-danger'><i class='fa fa-times' aria-hidden='true'></i></span>";}else{$check6="<span class='label label-success'><i class='fa fa-check' aria-hidden='true'></i></span>";}if(!extension_loaded('dom')){$check7="<span class='label label-danger'><i class='fa fa-times' aria-hidden='true'></i></span>";}else{$check7="<span class='label label-success'><i class='fa fa-check' aria-hidden='true'></i></span>";}if(!extension_loaded('curl')){$error=TRUE;$check8="<span class='label label-danger'><i class='fa fa-times' aria-hidden='true'></i></span>";}else{$check8="<span class='label label-success'><i class='fa fa-check' aria-hidden='true'></i></span>";}
if(!is_writeable($db_config_path)){$error=TRUE;$check9="<span class='label label-danger'>Silahkan Check donjo-app/config/database.php.</span>";}
else{$check9="<span class='label label-success'><i class='fa fa-check' aria-hidden='true'></i></span>";}
if(!is_writeable("../files")){$check10="<span class='label label-danger'>files folder is not writeable!</span>";}
else{$check10="<span class='label label-success'><i class='fa fa-check' aria-hidden='true'></i></span>";}
if(ini_get('allow_url_fopen')!="1"){$check11="<span class='label label-warning'>Allow_url_fopen is not enabled!</span>";}
else{$check11="<span class='label label-success'><i class='fa fa-check' aria-hidden='true'></i></span>";}
if(!is_writeable($db_config_path_desa)){$error=TRUE;$check12="<span class='label label-danger'>Silahkan Check desa-contoh/config/database.php.</span>";}
else{$check12="<span class='label label-success'><i class='fa fa-check' aria-hidden='true'></i></span>";}
 ?>
					<ul class="nav nav-tabs">
		            <li class="active"><a>1. Extension <p> Check</p></a></li>
					<li class=""><a>2.Database<p>Konfigurasi</p></a></li>
		            <li class=""><a>3.Selesai<p>Berhasil!</p></a></li>
		         </ul>
				<div class="tab-content">
	            <div class="tab-pane fade in active" id="Checklist">
						<h3>Pre-Install Pengecekan</h3>
			          <table class="table table-striped">
					      <thead><tr><th>Rekomendasi</th><th>Hasil</th></tr></thead>
					      <tbody>
								<tr><td>PHP 5.3+ </td><td><?php echo $check1; ?></td></tr>
								<tr><td>GD PHP extension</td><td><?php echo $check5; ?></td></tr>
								<tr><td>Mysqli PHP extension</td><td><?php echo $check12; ?></td></tr>
								<tr><td>Mcrypt PHP extension</td><td><?php echo $check2; ?></td></tr>
								<tr><td>MBString PHP extension</td><td><?php echo $check4; ?></td></tr>
								<tr><td>DOM PHP extension</td><td><?php echo $check7; ?></td></tr>
								<tr><td>files folder is writeable</td><td><?php echo $check10; ?></td></tr>
								<tr><td>PDO PHP extension</td><td><?php echo $check6; ?></td></tr>
								<tr><td>CURL PHP extension</td><td><?php echo $check8; ?></td></tr>
								<tr><td>Allow_url_fopen is enabled!</td><td><?php echo $check11; ?></td></tr>
								<tr><td>Database donjo-app (database.php) writeable</td><td><?php echo $check9; ?></td></tr>
								<tr><td>Database desa-contoh (database.php) writeable</td><td><?php echo $check12; ?></td></tr>
					      </tbody>
					    </table>
						 <form method="get"><input type="hidden" name="step" value="1" />
				 	 	<button type="submit" class="btn btn-next <?=$error ? 'disabled' : '';?>" href="">next ></button>
					</form>
					</div>
<?php
	break;
	case "1": ?>
	<ul class="nav nav-tabs">
		<li class=""><a>1. Extension <p> Check</p></a></li>
		<li class="active"><a>2. Database<p>Konfigurasi</p></a></li>
		<li class=""><a>3. Selesai<p>Berhasil!</p></a></li>
	</ul>
<div class="tab-content">
					<div class="tab-pane fade in active" id="Database">


							 <form id="install_form" method="post" action="?step=1">
							 <fieldset>
								<legend style="padding-top:20px">Pengaturan Database</legend>
								<p style="padding:3px;border: 1px solid #1ABC9C; border-radius:2px;color:#1ABC9C">Jika basis data tidak ada, sistem akan Otomatis  mencoba membuatnya.</p>
								<?php $hide='';if($_POST)
								{$core=new Core();$database=new Database();
									if($core->validate_post($_POST)==true){
										if($database->create_database($_POST)==false){echo "
									<p style='color:#ED5565'>Basis data tidak dapat dibuat, harap verifikasi pengaturan Anda.</p>";
									$error=1;}else if($database->create_tables($_POST)==false){echo "
									<p style='color:#ED5565'>Tabel database tidak dapat dibuat, harap verifikasi pengaturan Anda.</p>";
									$error=1;}else if($core->write_database($_POST)==false){echo "
									<p style='color:#ED5565'>File konfigurasi database tidak dapat ditulis, silakan chmod file desa-contoh/config/database.php ke 777</p>";
									$error=1;}else if($core->write_database($_POST)==false){echo "
										<p style='color:#ED5565'>File konfigurasi database tidak dapat ditulis, silakan chmod file desa-contoh/config/database.php ke 777</p>";
										$error=1;}else if($core->write_database1($_POST)==false){echo "
											<p style='color:#ED5565'>File konfigurasi database tidak dapat ditulis, silakan chmod file desa-contoh/config/database.php ke 777</p>";
											$error=1;}else if($core->write_database1($_POST)==false){echo "
												<p style='color:#ED5565'>File konfigurasi database tidak dapat ditulis, silakan chmod file desa-contoh/config/database.php ke 777</p>";
												
									$error=1;}if(!isset($error)){echo '
									<a href="index.php?step=2" class="label label-success" style="float:right;font-size:20px;"> sukses menuju ke langkah selanjutnya </a>';
									$hide='hide';}}} ?>
								<div class="form-group <?=$hide;?>">
									<label for="hostname">HOSTNAME</label>
									<input type="text" required id="hostname" class="form-control" name="hostname" />
								</div>
								<div class="form-group <?=$hide;?>">
									<label for="username">USERNAME</label>
									<input type="text" required id="username" class="form-control" name="username" />
								</div>
								<div class="form-group <?=$hide;?>">
									<label for="password">PASSWORD</label>
									<input type="password" id="password" class="form-control" name="password" />
								</div>
								<div class="form-group <?=$hide;?>">
									<label for="database">DATABASE</label>
									<input type="text" required id="database" class="form-control" name="database" />
								</div>
									<input type="submit" class="btn btn-next  <?=$hide;?>" value="Install" id="submit" />
							 </fieldset>
							 </form>
					</div>
<?php
	break;
	case "2": ?>
	
	<ul class="nav nav-tabs">
		<li class=""><a>1. Extension <p> Check</p></a></li>
		<li class=""><a>2. Database<p>Konfigurasi</p></a></li>
		<li class="active"><a>3. Selesai<p>Berhasil!</p></a></li>
	</ul>
<div class="tab-content">
					<div class="tab-pane fade in active" id="Done">
						<h1>Instalasi Opensid Berhasil!</h1>
						<div style="margin-bottom:10px;font-size:13px;padding:3px;background-color:#1ABC9C;color:white"><i class='fa fa-check' style="margin:0 7px" aria-hidden='true'></i> Anda dapat masuk sekarang menggunakan kredensial berikut:<br /><br />
            Username: <span style="font-weight:bold; letter-spacing:1px;">admin</span><br />Password: <span style="font-weight:bold; letter-spacing:1px;">sid304</span><br /><br /></div>
            <div class="bg-warning"><i class='icon-warning-sign'></i> Tolong jangan lupa untuk mengubah nama pengguna dan kata sandi.</div>
					<?php @unlink('INSTALL_OPENSID'); ?>
					<a href="<?php echo "http://".$_SERVER["SERVER_NAME"].substr($_SERVER["REQUEST_URI"], 0, -24); ?>" class="btn btn-next">Pergi ke Beranda</a>
					</div>
            </div>
				<?php } ?>
				<?php }else{ ?>
					<div class="tab-content">
						<h1><i class="fa fa-lock" aria-hidden="true" style="margin-right:10px"></i>Instalisasi Opensid Terkunci</h1>
				<?php } ?>
         </div>
       </div>

      <!-- jQuery -->
      <script type="text/javascript" src="../install/assets/jquery-2.2.2.min.js"></script>
      <!-- waves material design effect -->
      <script type="text/javascript" src="../install/assets/waves.min.js"></script>
      <!-- Bootstrap Core JavaScript -->
      <script type="text/javascript" src="../install/assets/bootstrap.min.js"></script>

      <script type="text/javascript">
      $(document).ready(function() {
         $('#install-modal').modal('show').on('hide.bs.modal', function (e) {
            e.preventDefault();
         });
      });
      </script>
   </body>
</html>
