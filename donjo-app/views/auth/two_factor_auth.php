<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>
			<?=$this->setting->login_title
				. ' ' . ucwords($this->setting->sebutan_desa)
				. (($desa['nama_desa']) ? ' ' . $desa['nama_desa']: '')
				. get_dynamic_title_page_from_path();
			?>
		</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" href="<?= base_url()?>assets/css/login-style.css" media="screen" type="text/css" />
		<link rel="stylesheet" href="<?= base_url()?>assets/css/login-form-elements.css" media="screen" type="text/css" />
		<link rel="stylesheet" href="<?= base_url()?>assets/bootstrap/css/bootstrap.bar.css" media="screen" type="text/css" />
                
		<?php if (is_file("desa/css/siteman.css")): ?>
			<link type='text/css' href="<?= base_url()?>desa/css/siteman.css" rel='Stylesheet' />
		<?php endif; ?>
		<?php if (is_file(LOKASI_LOGO_DESA ."favicon.ico")): ?>
			<link rel="shortcut icon" href="<?= base_url()?><?=LOKASI_LOGO_DESA?>favicon.ico" />
		<?php else: ?>
			<link rel="shortcut icon" href="<?= base_url()?>favicon.ico" />
		<?php endif; ?>
	</head>
	<body class="login">
		<div class="top-content">
			<div class="inner-bg">
				<div class="container">
					<div class="row">
						<div class="col-sm-6 col-sm-offset-3 form-box">
							<div class="form-top">
								<a href="<?=site_url(); ?>first/"><img src="<?=LogoDesa($desa['logo']);?>" alt="<?=$desa['nama_desa']?>" class="img-responsive" /></a>
								<div class="login-footer-top"><h1><?=ucwords($this->setting->sebutan_desa)?> <?=$desa['nama_desa']?></h1>
									<h3>
										<br /><?=$desa['alamat_kantor']?><br />Kodepos <?=$desa['kode_pos']?>
										<br /><?=ucwords($this->setting->sebutan_kecamatan)?> <?=$desa['nama_kecamatan']?><br /><?=ucwords($this->setting->sebutan_kabupaten)?> <?=$desa['nama_kabupaten']?>
									</h3>
								</div>
								<hr />
							</div>
							<div class="form-bottom">

      <div class="container-fluid">
         <nav class="navbar navbar-inverse" style="">
            <div class="container-fluid">
               <!-- Brand and toggle get grouped for better mobile display -->
               <div class="navbar-header">
                  <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                  <span class="sr-only">Toggle navigation</span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
                  </button>
                  <a class="navbar-brand" href="#"><img src="<?php bs() ?>public/assets/img/login.png" alt="" width="100"></a>
               </div>
               <!-- Collect the nav links, forms, and other content for toggling -->
               <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                  <ul class="nav navbar-nav navbar-right">
                     <li><a href="<?php bs() ?>Auth/Logout"><span class="glyphicon glyphicon-off"></span> Logout</a></li>
                  </ul>
               </div>
               <!-- /.navbar-collapse -->
            </div>
            <!-- /.container-fluid -->
         </nav>
         <!-- Collect the nav links, forms, and other content for toggling -->
      </div>
      <!-- /.container-fluid -->
      <div class="form-gap"></div>
      <div class="container">
         <div class="row">
            <div class="col-md-6 col-md-offset-3">
               <?php if (!empty($this->session->flashdata('error'))): ?>
               <div class="alert alert-danger" style="background-color:#ab2e2b;color :white;border:0px">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                  <i class="glyphicon glyphicon-bell"></i> <?php echo $this->session->flashdata('error'); ?>
               </div>
               <?php endif ?>  
               <?php if (!empty($this->session->flashdata('success'))): ?>
               <div class="alert alert-danger" style="background-color:#23bb07;color :white;border:0px">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                  <i class="fa fa-bell" aria-hidden="true"></i> <?php echo $this->session->flashdata('success'); ?>
               </div>
               <?php endif ?> 
               <div class="panel panel-default" style="background:rgba(255,255,255,0.5);">
                  <div class="panel-body">
                     <div class="text-center">
                        <h3><i class="fa fa-lock fa-4x"></i></h3>
                        <h2 class="text-center">Two Factor Authentication</h2>
                        <p>Please Enter Verification Code.</p>
                        <div class="panel-body">
                           <form id="verify_form" action="<?php bs() ?>Auth/authentication" role="form" autocomplete="off" class="form" method="post">
                              <div class="form-group">
                                 <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-asterisk color-blue"></i></span>
                                    <input name="code" placeholder="Plese Enter 6 digits Code" class="form-control" type="number" required>
                                 </div>
                              </div>
                              <?php echo form_error('code','<div class="error">', '</div>'); ?><br>
                              <div class="form-group">
                                 <input name="recover-submit" class="btn btn-lg btn-custom btn-block" value="Submit" type="submit">
                              </div>
                           </form>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      
<hr/>
								<div class="login-footer-bottom">powered by: <a href="https://github.com/OpenSID/OpenSID" target="_blank">OpenSID</a> <?= substr(AmbilVersi(), 0, 11)?></div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>

<script>
         // code verification form validation
         $("#verify_form").validate();
      </script>
