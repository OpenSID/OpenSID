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

                     
                                 
                                
                                 <?php echo form_open('auth/reset_password/' . $code,'class="form-horizontal reset"');?>
                                 <legend>Reset Password</legend>

                                 <div class="form-group">
                                    
                                     <label for="new_password" class="col-md-4 control-label"><?php echo sprintf(lang('reset_password_new_password_label'), $min_password_length);?></label>
                                    <div class="col-md-6">
                                       <?php echo form_input($new_password);?>
                                    </div>
                                 </div>
                                 <div class="form-group">
                                    <label for="fieldemail" class="col-md-4 control-label"><?php echo lang('reset_password_new_password_confirm_label', 'new_password_confirm');?></label>
                                    <div class="col-md-6">
                                       <?php echo form_input($new_password_confirm);?>
                                    </div>
                                 </div>
                                 <?php echo form_input($user_id);?>
                                 <?php echo form_hidden($csrf); ?>
                                 <input type="submit" name="submit" class="btn-success btn" value="Kirim" style="margin-right: 29em">
                                 <!-- <?php echo form_submit('submit', lang('reset_password_submit_btn'),'class="btn-success btn"');?> -->
                                 </form>
                              
                           

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
   $(".reset").validate({
     rules: {
       Password:"required",
       new_confirm: {
         equalTo: "#new"
       }
     },
   });
</script>
<script>
   <?php
      if (!empty($message))
        {
      ?>
    $.notify({
         
         icon: 'glyphicon glyphicon-info-sign',
         title: '<b>Notification</b><br>',
         message: '<?php echo $message;?>',
     },
     {
         
         type: "danger noty-color col-md-3 col-md-offset-2",
         allow_dismiss: true,
         placement: {
             from: "top",
             align: "center"
         },
         offset: 20,
         spacing: 10,
         z_index: 1431,
         delay: 5000,
         timer: 1000,
         animate: {
             enter: 'animated bounceInDown',
             exit: 'animated bounceOutUp'
         }
     });
   <?php
      }
      ?> 
</script> 
