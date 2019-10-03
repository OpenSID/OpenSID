<div class="content-wrapper">
   <div class="static-content">
      <div class="page-content">
         <section class="content-header">
		<h1>Ubah Data Pengguna</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?= site_url('users')?>"> Manajemen Pengguna</a></li>
			<li class="active">Ubah Data Pengguna</li>
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
            <?php echo validation_errors(); ?>
            <?= form_open(uri_string(),array('id'=>'social_config','class'=>'form-horizontal validate')); ?>
                <div class="form-group">
                  <label for="fieldname" class="col-md-3 control-label">Nama Awal</label>
                  <div class="col-md-6">
                    <?php
                      $first_name['class']='form-control';  
                      echo form_input($first_name);
                     ?>
                  </div>
                </div>
                <div class="form-group">
                  <label for="fieldname" class="col-md-3 control-label">Nama Akhir</label>
                  <div class="col-md-6">
                    <?php 
                       $last_name['class']='form-control'; 
                       echo form_input($last_name);
                    ?>
                  </div>
                </div>
                <div class="form-group">
                  <label for="fieldname" class="col-md-3 control-label">Username</label>
                  <div class="col-md-6">
                    <?php
                      echo form_input($username);
                    ?>
                  </div>
                </div>
                <div class="form-group">
                  <label for="fieldname" class="col-md-3 control-label">Alamat Email</label>
                  <div class="col-md-6">
                    <?php
                      echo form_input($email);
                    ?>
                  </div>
                </div>
                <div class="form-group">
                  <label for="fieldname" class="col-md-3 control-label">Organisasi</label>
                  <div class="col-md-6">
                    <?php
                      echo form_input($company);
                    ?>
                  </div>
                </div>
                <div class="form-group">
                  <label for="fieldname" class="col-md-3 control-label">No.Telp.</label>
                  <div class="col-md-6">
                    <?php
                      echo form_input($phone);
                    ?>
                  </div>
                </div>
                <div class="form-group">
                  <label for="fieldname" class="col-md-3 control-label">Password</label>
                  <div class="col-md-6">
                    <?php
                      echo form_input($password);
                    ?>
                  </div>
                </div>
                <div class="form-group">
                  <label for="fieldname" class="col-md-3 control-label">Konfirmasi Password</label>
                  <div class="col-md-6">
                    <?php
                      echo form_input($password_confirm);
                    ?>
                  </div>
                </div>
                <div class="form-group">
                  <label for="fieldname" class="col-md-3 control-label"><?php echo lang('edit_user_groups_heading');?></label>
                  <div class="col-md-6">
                    <?php if ($this->ion_auth->is_admin()): ?>

                        <?php foreach ($groups as $group):?>
                            <label class="btn btn-info">
                            <?php
                                $gID=$group['id'];
                                $checked = null;
                                $item = null;
                                foreach($currentGroups as $grp) {
                                    if ($gID == $grp->id) {
                                        $checked= ' checked="checked"';
                                    break;
                                    }
                                }
                            ?>
                            <input type="checkbox" class="bg" name="groups[]" value="<?php echo $group['id'];?>"<?php echo $checked;?>>
                            <?php echo htmlspecialchars($group['name'],ENT_QUOTES,'UTF-8');?>
                            </label>
                        <?php endforeach?>

                    <?php endif ?>

                    <?php echo form_hidden('id', $user->id);?>
                    <?php echo form_hidden($csrf); ?>
                  </div>
                </div>
                <div class="form-group">
                  <div class="col-md-6 col-md-offset-3">
                    <input type="submit" name="submit" class="finish btn-primary btn" value="Kirim">
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
