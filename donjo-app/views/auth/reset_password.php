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
      <link type="text/css" href="<?= base_url('public/assets/fonts/themify-icons/themify-icons.css')?>" rel="stylesheet">
      <!-- Themify Icons -->
      <link type="text/css" href="<?= base_url('public/assets/css/styles.css')?>" rel="stylesheet">
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
         .error
         {
         color: red;
         }
      </style>
   </head>
   <!-- Body Starts -->
   <body class="animated-content">
      <div class="row" >
         <div class="col-md-10 col-md-offset-3 col-sm-offset-3 col-lg-offset-3">
            <div class="static-content-wrapper">
               <div class="static-content">
                  <div class="page-content">
                     <ol class="breadcrumb">
                     </ol>
                     <div class="container-fluid">
                        <div data-widget-group="group1">
                           <div class="panel panel-midnightblue" data-widget='{"draggable": "false"}' style="background-color: #f34a50">
                              <div class="panel-heading" style="background-color: #f34a50">
                                 <h2> <i class="fa fa-repeat"></i><?php echo lang('reset_password_heading');?></h2>
                                 <div class="panel-ctrls" data-actions-container="" data-action-collapse='{"target": ".panel-body"}'></div>
                              </div>
                              <div class="panel-body">
                                 <!-- <form action="setup.php" id="wizard" method="post" class="form-horizontal"> -->
                                 <?php echo form_open('auth/reset_password/' . $code,'class="form-horizontal reset"');?>
                                 <legend>Reset Password</legend>
                                 <div class="form-group">
                                    <!-- <label for="fieldname" class="col-md-3 control-label">Hostname</label> -->
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
                                 <input type="submit" name="submit" class="btn-success btn" value="Change" style="margin-right: 29em">
                                 <!-- <?php echo form_submit('submit', lang('reset_password_submit_btn'),'class="btn-success btn"');?> -->
                                 </form>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <!-- .container-fluid -->
         </div>
         <!-- #page-content -->
      </div>
      <footer role="contentinfo">
         <div class="clearfix">
            <ul class="list-unstyled list-inline pull-left">
               <li>
                  <h6 style="margin: 0;">
               </li>
            </ul>
            <button class="pull-right btn btn-link btn-xs hidden-print" id="back-to-top"><i class="ti ti-arrow-up"></i></button>
         </div>
      </footer>
      </div>
      </div>
      </div>
      <!-- Body Ends-->
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
      <script type="text/javascript" src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.15.0/jquery.validate.min.js"></script>
      <!-- End loading site level scripts -->
      <!-- Load page level scripts-->
      <!-- End loading page level scripts-->
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