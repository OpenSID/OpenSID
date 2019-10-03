<div class="content-wrapper">
<div class="static-content">
<div class="page-content">
<ol class="breadcrumb">
   <li><a href="">Home</a></li>
   <li><a href="#">change password</a></li>
   <li class="active"><a href="">change password form </a></li>
</ol>
<div class="container-fluid">
<div data-widget-group="group1">
   <div class="row">
      <div class="col-md-11">
         <div class="panel panel-midnightblue">
            <div class="panel-heading">
               <h2> <i class="ti ti-lock"></i> <?php echo lang('change_password_heading');?></h2>
               <div class="panel-ctrls">
                  <a href="#" class="button-icon"><i class="ti ti-file"></i></a>
                  <a href="#" class="button-icon"><i class="ti ti-mouse"></i></a>
                  <a href="#" class="button-icon"><i class="ti ti-settings"></i></a>
               </div>
            </div>
            <div class="panel-body">
               <div class="row">
                  <div class="col-md-5">
                     <?php echo form_open("auth/change_password",array("id"=>"change_pass"));?>
                     <div class="form-group">
                        <?php echo lang('change_password_old_password_label', 'old_password');?> <br />
                        <?php echo form_input($old_password);?>
                     </div>
                     <div class="form-group">
                        <label for="new_password"><?php echo sprintf(lang('change_password_new_password_label'), $min_password_length);?></label> <br />
                        <?php echo form_input($new_password);?>
                     </div>
                     <div class="form-group">
                        <?php echo lang('change_password_new_password_confirm_label', 'new_password_confirm');?> <br />
                        <?php echo form_input($new_password_confirm);?>
                     </div>
                     <?php echo form_input($user_id);?>
                     <?php echo form_submit('submit', lang('change_password_submit_btn'),'class="btn btn-toolbar"');?>
                     <?php echo form_close();?>
                  </div>
                  <div class="col-md-5">
                     <font color="red"><span ></span> <?php echo $message ?></font> 
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
