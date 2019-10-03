<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign UP</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.1/css/all.css" integrity="sha384-5sAR7xN1Nv6T6+dT2mhtzEpVJvfS3NScPQTrOxhwjIuvcA67KV2R5Jz6kr4abQsz" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="<?= bs() ?>public/assets/css/iofrm-style.css">
    <link rel="stylesheet" type="text/css" href="<?= bs() ?>public/assets/css/iofrm-theme9.css">
    
</head>

<body>
<div class="form-body">
        <div class="row">
            <div class="img-holder">
                <div class="bg"></div>
                <div class="info-holder">
                    <h3>Get more things done with Loggin platform.</h3>
                    <p>Access to the most powerfull tool in the entire design and web industry.</p>
                    <img src="<?= bs() ?>public/assets/img/graphic5.svg" alt="image">
                </div>
            </div>
            <div class="form-holder">
                <?php echo validation_errors(); ?>
                <div class="form-content">
                    <div class="form-items">
                        <div class="website-logo-inside">
                            <a href="index.html">
                                <!-- <div class="logo"> -->
                                    <img class="logo-size" src="<?= bs() ?>public/assets/img/login.png" alt="">
                                <!-- </div> -->
                            </a>
                        </div>
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

                            <div class="g-recaptcha" data-sitekey="6LfaLzIUAAAAAHpYFZW__WFypmev0w8rgz7AtPBN"
                              data-callback="YourOnSubmitFn"></div>

                            <div class="form-button">
                                <button id="submit" type="submit" class="ibtn">Register</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="http://brandio.io/envato/iofrm/html/js/jquery.min.js"></script>
    <script src="http://brandio.io/envato/iofrm/html/js/popper.min.js"></script>
    <script src="http://brandio.io/envato/iofrm/html/js/bootstrap.min.js"></script>
    <script src="<?= bs() ?>public/assets/js/main.js"></script>
</body>

</html>

<script src="<?= base_url('public/assets/js/bootstrap-notify.js') ?>"></script>
<script type="text/javascript" src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.15.0/jquery.validate.min.js"></script>

  <script src='https://www.google.com/recaptcha/api.js'></script>

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
