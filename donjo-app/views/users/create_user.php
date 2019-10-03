<div class="content-wrapper">
   <div class="static-content">
      <div class="page-content">
         <section class="content-header">
		<h1>Tambah Pengguna Baru</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?= site_url('users')?>"> Manajemen Pengguna</a></li>
			<li class="active">Tambah Pengguna Baru</li>
		</ol>
	</section>

         <section class="content" id="maincontent">
	 
		<div data-widget-group="group1">

			<div class="box box-info">
					<div class="box-header with-border">
						<a href="<?=site_url()?>users" class="btn btn-social btn-flat btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-arrow-circle-o-left"></i> Kembali Ke Manajemen Pengguna</a> </div>
					</div>
            
	<div class="panel panel-info" data-widget='{"draggable": "false"}'>
              
               <div class="panel-body">
                  <?= form_open('Users/add_user',array('id'=>'user_form_validation','class'=>'form-horizontal')); ?>
                  <div class="form-group">
                     <label for="fieldname" class="col-md-3 control-label">Nama Awal</label>
                     <div class="col-md-6">
                        <input type="text" id="first_name" name="first_name" class="form-control" placeholder="Nama awal" required/>
                     </div>
                  </div>
                  <div class="form-group">
                     <label for="fieldname" class="col-md-3 control-label">Nama Akhir</label>
                     <div class="col-md-6">
                        <input type="text" id="last_name" name="last_name" class="form-control" placeholder="Nama akhir" required/>
                     </div>
                  </div>
                  <div class="form-group">
                     <label for="fieldname" class="col-md-3 control-label">Username</label>
                     <div class="col-md-6">
                        <input type="text" id="username" name="username" class="form-control" placeholder="Username" required/>
                        <div id="username_message" style="color: red;font-weight: bold;"> </div>
                     </div>
                  </div>
                  <?php
                     if($identity_column!=='email') 
                       {
                           echo '<p>';
                           echo lang('create_user_identity_label', 'identity');
                           echo '<br />';
                           echo form_error('identity');
                           echo form_input($identity);
                           echo '</p>';
                       }
                     ?>
                  <div class="form-group">
                     <label for="fieldname" class="col-md-3 control-label">Alamat Email</label>
                     <div class="col-md-6">
                        <input type="email" id="email" name="email" class="form-control" placeholder="admin@opendesa.id" required/>
                        <div id="user_mail" style="color: red;font-weight: bold;"></div>
                     </div>
                  </div>
                  <div class="form-group">
                     <label for="fieldname" class="col-md-3 control-label">Organisasi</label>
                     <div class="col-md-6">
                        <input type="text" id="company" name="company" class="form-control" placeholder="Desa xxxxxx" required/>
                     </div>
                  </div>
                  <div class="form-group">
                     <label for="fieldname" class="col-md-3 control-label">No.Telp.</label>
                     <div class="col-md-6">
                        <input type="text" id="email" name="phone" class="form-control" placeholder="081-1111-1111" required/>
                     </div>
                  </div>
                  <div class="form-group">
                     <label for="fieldname" class="col-md-3 control-label">Password</label>
                     <div class="col-md-6">
                        <input type="password" id="password" name="password" class="form-control" placeholder="***********" required minlength="8" />
                     </div>
                  </div>
                  <div class="form-group">
                     <label for="fieldname" class="col-md-3 control-label">Konfirmasi Password</label>
                     <div class="col-md-6">
                        <input type="password" id="password" name="confirm_password" class="form-control" placeholder="***********" required/>
                     </div>
                  </div>
                  <div class="form-group">
                     <label for="fieldname" class="col-md-3 control-label">Pilih Group</label>
                     <div class="col-md-6">
                        <select class="form-control" name="group" id="" required>
                           <option value="">Pilih Group</option>
                           <?php foreach ($groups as $key => $value): ?>
                           <option value="<?php echo $value->id ?>"><?php echo $value->name ?></option>
                           <?php endforeach ?>
                        </select>
                     </div>
                  </div>
                  <div class="form-group">
                     <div class="col-md-6 col-md-offset-3">
                        <input type="submit" class="finish btn-success btn" value="Kirim">
                     </div>
                     </fieldset>
                     </form>
                  </div>
               </div>
            
	
        </div>
       </div>
      </section>
   </div>
   <!-- .container-fluid -->
</div>
<!-- #page-content -->
</div>
<script>
   $(document).ready(function() {
   
      //This script is to check email validity
      $("#email").change(function() 
      {
   
         var email = $("#email").val();
   
         $.ajax({
           url: '<?= site_url("Register/check_email") ?>',
           method: 'POST',
           dataType: 'TEXT',
           data: {myemail: email},
           success: function(result) 
           {
             var msg = result.split("::");
   
             if (msg[0] == "ok")
             {
               $("#user_mail").fadeIn();
               $("#user_mail").text("Alamat Email Ini Sudah Terdafar, Coba Dengan Alamat Email Lainnya.");
             }  
             else
             {
               $("#user_mail").fadeIn();
               $("#user_mail").html("<span class='fa fa-check-circle text-success'> Success</span>");
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
           url: '<?= site_url("Register/check_username") ?>',
           method: 'POST',
           dataType: 'HTML',
           data: {u_name: username},
           success: function(result) 
           {
             var msg = result.split("::");
   
             if (msg[0] == "ok")
             {
               $("#username_message").fadeIn();
               $("#username_message").html('Username Ini Sudah Terdafar, Coba Dengan Username Lainnya.');
             }
             else
             {
               $("#username_message").fadeIn();
               $("#username_message").html("<span class='fa fa-check-circle text-success'> Success</span>");
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
