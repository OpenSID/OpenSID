<!DOCTYPE html>
<html lang="en" class="coming-soon">
<head>
    <meta charset="utf-8">
    <title>Reset Form</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-touch-fullscreen" content="yes">
    <meta name="author" content="KaijuThemes">

    <link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400italic,600' rel='stylesheet' type='text/css'>
    <link type="text/css" href="<?= base_url('public/assets/plugins/iCheck/skins/minimal/blue.css')?>" rel="stylesheet">
    <link type="text/css" href="<?= base_url('public/assets/fonts/font-awesome/css/font-awesome.min.css')?>" rel="stylesheet">
    <link type="text/css" href="<?= base_url('public/assets/fonts/themify-icons/themify-icons.css')?>" rel="stylesheet">               <!-- Themify Icons -->
    <link type="text/css" href="<?= base_url('public/assets/css/styles.css')?>" rel="stylesheet">
    <?= link_tag("public/assets/css/animate.css") ?>


    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries. Placeholdr.js enables the placeholder attribute -->
    <!--[if lt IE 9]>
        <link type="text/css" href="<?= base_url('public/assets/css/ie8.css')?>" rel="stylesheet">
        <script type="text/javascript" src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- The following CSS are included as plugins and can be removed if unused-->
    
    <style>
      .noty-color
      {
        background-color: #cc1c1c;
        color: white;
      }

    </style>

    </head>

    <body class="focused-form animated-content">
        
        
<div class="container" id="forgotpassword-form">
  <a href="index.html" class="login-logo">
   <img src="<?php bs('public/assets/img/logo.png') ?>" height="30" alt="">
  </a>
  <div class="row">
    <div class="col-md-4 col-md-offset-4">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h2><?php echo lang('forgot_password_heading');?></h2>
        </div>
        <div class="panel-body">
          <?php echo form_open("register/forgot_pass",'class="form-horizontal"');?>
            <div class="form-group mb-n">
              <div class="col-xs-12">
                  <?php if (!empty($error)): ?>

                    <font color="red" size="5"><?php echo $error;?></font>
                    
                  <?php endif ?>
                  <div class="input-group">             
                    <span class="input-group-addon">
                      <i class="ti ti-email"></i>
                    </span>
                      <input type="text" name="identity" id="identity" class="form-control" required="required"  placeholder="Email" />
                  </div>
              </div>
            </div>
        </div>
        <div class="panel-footer">
          <div class="clearfix">
            <a href="<?= site_url('Auth/login') ?>" class="btn btn-default pull-left">Go Back</a>
            <?php echo form_submit('submit', 'Reset','class="btn btn-primary pull-right"');?>
          </div>
        </div>
        <?php echo form_close();?>
      </div>
    </div>
  </div>
</div>

    
    
    <!-- Load site level scripts -->

<!-- <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script> -->

<script type="text/javascript" src="<?= base_url('public/assets/js/jquery-1.10.2.min.js')?>"></script>               <!-- Load jQuery -->
<script type="text/javascript" src="<?= base_url('public/assets/js/jqueryui-1.10.3.min.js')?>"></script>               <!-- Load jQueryUI -->
<script type="text/javascript" src="<?= base_url('public/assets/js/bootstrap.min.js')?>"></script>                 <!-- Load Bootstrap -->
<script type="text/javascript" src="<?= base_url('public/assets/js/enquire.min.js')?>"></script>                   <!-- Load Enquire -->

<script type="text/javascript" src="<?= base_url('public/assets/plugins/velocityjs/velocity.min.js')?>"></script>          <!-- Load Velocity for Animated Content -->
<script type="text/javascript" src="<?= base_url('public/assets/plugins/velocityjs/velocity.ui.min.js')?>"></script>

<script type="text/javascript" src="<?= base_url('public/assets/plugins/wijets/wijets.js')?>"></script>                <!-- Wijet -->

<script type="text/javascript" src="<?= base_url('public/assets/plugins/codeprettifier/prettify.js')?>"></script>        <!-- Code Prettifier  -->
<script type="text/javascript" src="<?= base_url('public/assets/plugins/bootstrap-switch/bootstrap-switch.js')?>"></script>    <!-- Swith/Toggle Button -->

<script type="text/javascript" src="<?= base_url('public/assets/plugins/bootstrap-tabdrop/js/bootstrap-tabdrop.js')?>"></script>  <!-- Bootstrap Tabdrop -->

<script type="text/javascript" src="<?= base_url('public/assets/plugins/iCheck/icheck.min.js')?>"></script>              <!-- iCheck -->

<script type="text/javascript" src="<?= base_url('public/assets/plugins/nanoScroller/js/jquery.nanoscroller.min.js')?>"></script> <!-- nano scroller -->

<script type="text/javascript" src="<?= base_url('public/assets/js/application.js')?>"></script>
<script type="text/javascript" src="<?= base_url('public/assets/demo/demo.js')?>"></script>
<script type="text/javascript" src="<?= base_url('public/assets/demo/demo-switcher.js')?>"></script>
  <script src="<?= base_url('public/assets/js/bootstrap-notify.js') ?>"></script>


<!-- End loading site level scripts -->
    <!-- Load page level scripts-->
    
    <!-- End loading page level scripts-->
    </body>
</html>

<script>
   <?php
      $success = $this->session->flashdata('success');
      $error   = $this->session->flashdata('error');
      if (!empty($success))
       {
      ?>
    $.notify({
         
         icon: 'glyphicon glyphicon-info-sign',
         title: '<b><i class="fa fa-exclamation-circle"></i> Notification</b><br>',
         message: "<?php echo $success ?>",
     },
     {
         
         
         type: "success success-noty col-md-3",
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
      if (!empty($error))
       {
      ?>
    $.notify({
             
             icon: 'glyphicon glyphicon-info-sign',
             title: '<b><i class="fa fa-info-circle"></i> Notification</b><br>',
             message: "<?php echo $error ?>",
         },{
             
             
             type: "danger error-noty col-md-3",
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
