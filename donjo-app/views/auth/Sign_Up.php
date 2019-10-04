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

                        <div class="page-links">
                            <a href="<?= site_url('auth/login') ?>">Login</a><a href="<?= site_url('Register') ?>" class="active">Register</a>
                        </div>
                        <form action="<?php bs() ?>index.php/Register/sign_up" method="post" id="myform">

                            <input type="text" id="first_name" name="first_name" class="form-control" value="<?php echo set_value('first_name'); ?>" placeholder="First Name" required>

                            <input type="text" id="last_name" name="last_name" class="form-control" value="<?= set_value('last_name'); ?>" placeholder="Last Name" required>

                            <input type="text" id="username" name="username" value="<?= set_value('username'); ?>" class="form-control"  placeholder="Username" required>
                            <div id="username_message" style="color: #17c314;font-weight: bold;font-size:20px"> </div>

                            <input type="email" id="email" name="email" class="form-control" value="<?= set_value('email'); ?>" placeholder="Email Addres" required/>
                           <div id="user_mail" style="color: #17c314;font-weight: bold;font-size:20px"></div>

                            <input type="password" id="password" name="password" minlength="8" class="form-control" placeholder="Password" required/>

                            <input type="password" id="confirm_password" name="confirm_password" class="form-control" placeholder="Confirm Password" required/>

                            <div class="form-button">
                                <button id="submit" type="submit" class="ibtn">Register</button>
                            </div>
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
    $("#myform").validate({
      rules: {
        password: "required",
        confirm_password: {
          equalTo: "#password"
        },
        captcha: {
          "required": true
        }
      },
      messages:
      {
        captcha: {
            "required": "Please enter the verifcation code.",
          }
      }
  });
  </script>

  <script>
    $(document).ready(function() {

       //This script is to check email validity
       $("#email").change(function() 
       {

          var email    = $("#email").val();

          $.ajax({
            url: '<?= base_url("index.php/Register/check_email") ?>',
            method: 'POST',
            dataType: 'TEXT',
            data: {myemail: email},
            success: function(result) 
            {
              var msg = result.split("::");

              if (msg[0] == "ok")
              {
                $("#user_mail").fadeIn();
                $("#user_mail").text("This Email Is Already Registered Please Try With Another.");
              }  
              else
              {
                $("#user_mail").fadeIn();
                $("#user_mail").html("<i class='fas fa-check-circle'></i> Success");
                $("#user_mail").delay(3000).fadeOut();
              }
            },
            error:function(result) 
            {
              // body...
              console.log(result);
            }
          })
       });

       //This script is to check Username validity
      $("#username").change(function() 
       {

          var username = $("#username").val();

          $.ajax({
            url: '<?= base_url("index.php/Register/check_username") ?>',
            method: 'POST',
            dataType: 'HTML',
            data: {u_name: username},
            success: function(result) 
            {
              var msg = result.split("::");

              if (msg[0] == "ok")
              {
                $("#username_message").fadeIn();
                $("#username_message").html('This User name Is Already Registered Please Try With Another.');
              }
              else
              {
                $("#username_message").fadeIn();
                $("#username_message").html("<i class='fas fa-check-circle'></i> Success ");
                $("#username_message").delay(3000).fadeOut();
              }
            },
            error:function(result) 
            {
              // body...
              console.log(result);
            }
          })
       });
    });
  </script>
  <!-- Notification Script -->
  <script>

    <?php
    $success = $this->session->flashdata('success');
    $error = $this->session->flashdata('error');
    if (!empty($success)) {
      ?>
     $.notify({
          
          icon: 'glyphicon glyphicon-info-sign',
          title: '<b>Notification</b><br>',
          message: '<?php echo $success ?>',
      },
      {
          
          
          type: "success success-noty col-sm-3",
          allow_dismiss: true,
          placement: {
              from: "top",
              align: "right"
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
if (!empty($error)) {
  ?>
     $.notify({
              
              icon: 'glyphicon glyphicon-info-sign',
              title: '<b>Notification</b><br>',
              message: '<?php echo $error ?>',
          },{
              type: "danger noty-color col-sm-3",
              allow_dismiss: true,
              placement: {
                  from: "top",
                  align: "right"
              },
              offset: 20,
              spacing: 10,
              z_index: 1431,
              delay: 5000,
              timer: 1000,
              animate: {
                  enter: 'animated fadeInDown',
                  exit: 'animated fadeOutUp'
              }
          });
     <?php 
  }
  ?>

  </script> 
  <!-- Notification Script -->
  <script>
  <?php
  if (!empty($message)) {
    ?>
     $.notify({
              
              icon: 'glyphicon glyphicon-info-sign',
              title: '<b>Notification</b><br>',
              message: '<?php echo $message ?>',
          },{
              
              
              type: "danger noty-color col-sm-3",
              allow_dismiss: true,
              placement: {
                  from: "top",
                  align: "right"
              },
              offset: 20,
              spacing: 10,
              z_index: 1431,
              delay: 5000,
              timer: 1000,
              animate: {
                  enter: 'animated fadeInDown',
                  exit: 'animated fadeOutUp'
              }
          });
     <?php 
  }
  ?>

  </script> 

  </body>
</html>
