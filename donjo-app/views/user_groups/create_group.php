<div class="content-wrapper">
<div class="static-content">
   <div class="page-content">
     <section class="content-header">
		<h1>Tambah Grup Baru</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('user_groups')?>"><i class="fa fa-home"></i> Manajemen Group</a></li>
			<li class="active">Tambah Grup Baru</li>
		</ol>
	</section>
      <section class="content" id="maincontent">
         <div data-widget-group="group1">
            <div class="row">
               <div class="col-md-12">
                  <div class="panel panel-default">
                     <div class="box box-info">
					<div class="box-header with-border">
						<a href="<?= site_url()?>user_groups" class="btn btn-social btn-flat btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-arrow-circle-o-left"></i> Kembali Ke Manajemen Group</a>
							
					</div>

                  <div class="panel-body">
                     <?php echo form_open("User_groups/create_group",array('id'=>'wizard','class'=>'form-horizontal'));?>
                     <fieldset title="Step 1">
                        <div class="form-group">
                           <label for="fieldname" class="col-md-3 control-label">Nama Group</label>
                           <div class="col-md-6">
                              <?php echo form_input($group_name);?>
                              <span id="err_msg" style="color:red"></span>
                           </div>
                        </div>
                        <div class="form-group">
                           <label for="fieldemail" class="col-md-3 control-label">Keterangan</label>
                           <div class="col-md-6">
                              <?php echo form_input($description);?>
                           </div>
                        </div>
                     </fieldset>
                     <input type="submit" class="finish btn-success btn" value="Kirim" />
                     </form>
                  </div>

                
                     <div class="panel-footer"></div>
                       </div>
                     </div>
                  </div>
               </div>
            </div>
         </section>
      </div>
   </div>
</div>

<script>
   $(document).ready(function() {
   
      $("#group_name").change(function()
      {
         var group_name = $("#group_name").val();
   
         if (group_name.length === 0) 
         {
           $('#err_msg').text('Nama Group Harap Diisi');
           return false;
         }
   
         $.ajax({
           url: '<?= site_url("User_groups/check_group_name") ?>',
           method: 'POST',
           dataType: 'TEXT',
           data: {group_name: group_name},
           success: function(result) 
           {
             var msg = result.split("::");
   
             if (msg[0] == "ok")
             {
               $("#err_msg").fadeIn();
               $("#err_msg").text("Nama Group sudah ada.");
             }  
             else
             {
               console.log('Success');
               $("#err_msg").fadeIn();
               $("#err_msg").html("<span class='fa fa-check-circle text-success'> Success</span>");
               $("#err_msg").delay(3000).fadeOut();
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
