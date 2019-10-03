<div class="content-wrapper">
   <div class="static-content">
      <div class="page-content">
         <section class="content-header">
		<h1>Profile Pengguna</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li class="active">Profile Pengguna</li>
		</ol>
	</section>

         <section class="content" id="maincontent">
	 
		<div data-widget-group="group1">
            
        
			<div class="box box-info">
					<div class="box-header with-border">
						<a href="<?=site_url()?>hom_sid" class="btn btn-social btn-flat btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-arrow-circle-o-left"></i> Home</a> </div>
					</div>
            
               <div class="row">
               <div class="col-sm-3">
                  <div class="panel panel-profile">
                     <div class="panel-body">
                        <img src="<?php bs() ?>assets/files/user_pict/<?php echo $user_profile->user_img ?>" class="img-circle" width="200" alt="">
                        <?php 
                           $user = $this->ion_auth->user()->row();
                        ?>
                        <div class="name"><?php echo $user->username; ?></div>
                        <div class="info"><?php echo $user->email ?></div>
                        
                     </div>
                  </div>
                  <!-- panel -->
                  <div class="list-group list-group-alternate mb-n nav nav-tabs">
                     <a href="#tab-about" 	role="tab" data-toggle="tab" class="list-group-item active">
                     <i class="ti ti-user"></i> Profile </a>
                     <a href="#change_pic" role="tab" data-toggle="tab" class="list-group-item">
                     <i class="ti ti-exchange-vertical"></i> Ganti Foto Profile</a>
                     <a href="#chang_pass" role="tab" data-toggle="tab" class="list-group-item">
                     <i class="ti ti-unlock"></i> Ganti Password</a>
                     <a href="#tab-edit" role="tab" data-toggle="tab" class="list-group-item">
                     <i class="ti ti-pencil"></i> Ubah Data Pengguna</a>
                  </div>
               </div>
               <!-- col-sm-3 -->
               <div class="col-sm-9">
                  <div class="tab-content">
                     <div class="tab-pane" id="chang_pass">
                        <div class="panel panel-default">
                           <div class="panel-heading">
                              <h2>Ganti Password</h2>
                           </div>
                           <div class="panel-body">
                              <div class="table-responsive">
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
                                 </div>
                              </div>
                              <!-- /.table-responsive -->
                           </div>
                           <!-- /.panel-body -->
                        </div>
                     </div>
                     <!-- #tab-projects -->
                     <div class="tab-pane active" id="tab-about">
                        <div class="panel panel-default">
                           <div class="panel-heading">
                              <h2>Profile</h2>
                           </div>
                           <div class="panel-body">
                              <div class="about-area">
                                 <h4>Informasi Personal</h4>
                                 <div class="table-responsive">
                                    <table class="table about-table">
                                       <tbody>
                                          <tr>
                                             <th>Nama Lengkap</th>
                                             <td><?php echo $user_profile->first_name." ".$user_profile->last_name ?></td>
                                          </tr>
                                          <tr>
                                             <th>Email</th>
                                             <td><?php echo $user_profile->email ?></td>
                                          </tr>
                                          <tr>
                                             <th>Mobile No</th>
                                             <td><?php echo $user_profile->phone ?></td>
                                          </tr>
                                       </tbody>
                                    </table>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="tab-pane" id="change_pic">
                        <div class="panel">
                           <div class="panel-heading">
                              <h2>Ganti Foto Profile</h2>
                           </div>
                           <div class="panel-body">
                              <div class="row">
                                 <div class="col-md-12">
                                    <form class="form-horizontal" enctype="multipart/form-data" method="post" action="<?= site_url("users/update_avatar") ?>" role="form">
                                       <div class="form-group">
                                          <div class="col-lg-6"> 
                                             <?php 
                                                if (empty($user->user_img)) 
                                                {
                                                	
                                                ?>
                                             <img class="profile-user-img img-responsive img-circle" src="<?= base_url()?>assets/files/user_pict/kuser.png" alt="Pengguna">
                                             <?php	
                                                } 
                                                else {
                                                ?>
                                             <img src="<?php bs() ?>assets/files/user_pict/<?php echo $user->user_img ?>" class="img-responsive img-circle" width="200" alt="">
                                             <?php		
                                                }
                                                
                                                
                                                ?>    
                                             <br><br>
                                             <input type="file" name="chang_image">
                                             
                                             
                                          </div>
                                       </div>
                                       <div class="form-group">
                                          <div class="col-lg-offset-2 col-lg-10">
                                             <button type="submit" class="btn btn-success">Ubah</button>
                                             <button type="button" class="btn btn-default">Cancel</button>
                                          </div>
                                       </div>
                                    </form>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="tab-pane" id="tab-edit">
                        <div class="panel">
                           <div class="panel-heading">
                              <h2>Ubah Data Pengguna</h2>
                           </div>
                           <div class="panel-body">
                              <div class="row">
                                 <div class="col-md-12">
                                    <form method="post" action="<?php bs() ?>index.php/users/edit_profile">
                                       <div class="form-group">
                                          <label for="exampleInputEmail1">Nama awal</label>
                                          <input type="text" class="form-control" name="first_name" value="<?php echo $user_profile->first_name ?>">
                                       </div>
                                       <div class="form-group">
                                          <label for="exampleInputPassword1">Nama akhir</label>
                                          <input type="text" class="form-control" name="last_name" value="<?php echo $user_profile->last_name ?>">
                                       </div>
                                       <div class="form-group">
                                          <label for="exampleInputPassword1">Alamat Email</label>
                                          <input type="email" class="form-control" name="email" value="<?php echo $user_profile->email ?>">
                                       </div>
                                       <div class="form-group">
                                          <label for="exampleInputPassword1">Mobile No</label>
                                          <input type="text" class="form-control" name="mobile_no" value="<?php echo $user_profile->phone ?>">
                                       </div>
                                       <button type="submit" class="btn btn-primary">Simpan</button>
                                    </form>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <!-- .tab-content -->
               </div>

          </div>
       </div>
      </section>
   </div>
   <!-- .container-fluid -->
</div>
<!-- #page-content -->
</div>               

                
