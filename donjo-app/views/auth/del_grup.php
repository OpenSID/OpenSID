<div class="content-wrapper">
   <div class="static-content">
      <div class="page-content">
         <section class="content-header">
		<h1>Hapus Group</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?= site_url('user_groups')?>"> Manajemen Group</a></li>
			<li class="active">Hapus Grup</li>
		</ol>
	</section>

         <section class="content" id="maincontent">
	 
		<div data-widget-group="group1">

			<div class="box box-info">
					<div class="box-header with-border">
						<a href="<?=site_url()?>user_groups" class="btn btn-social btn-flat btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-arrow-circle-o-left"></i> Kembali Ke Manajemen Group</a> </div>
					</div>
            
            <div class="panel panel-info" data-widget='{"draggable": "false"}'>
               <div class="panel-body">
                  <p><?php echo sprintf(lang('delete_group'), $group->name);?></p>

                  <?php echo form_open("auth/delete_group/".$group->id);?>
                    <p>
                      <?php echo lang('deactivate_confirm_y_label', 'confirm');?>
                      <input type="radio" name="confirm" value="yes" checked="checked" />
                       <br>
                      <?php echo lang('deactivate_confirm_n_label', 'confirm');?>
                      <input type="radio" name="confirm" value="no" />
                    </p>

                    <?php echo form_hidden($csrf); ?>
                    <?php echo form_hidden(array('id'=>$group->id)); ?>

                    <p><?php echo form_submit('submit',"Hapus Grup",'class="btn btn-info"');?>
                    </p>

                  <?php echo form_close();?>
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
