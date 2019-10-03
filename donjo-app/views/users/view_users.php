<div class="content-wrapper">
<div class="static-content">
   <div class="page-content">
     <section class="content-header">
		<h1>Manajemen Pengguna</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li class="active">Manajemen Pengguna</li>
		</ol>
	</section>
      <section class="content" id="maincontent">
         <div data-widget-group="group1">
            <div class="row">
               <div class="col-md-12">
                  <div class="panel panel-default">
                     <div class="box box-info">
					<div class="box-header with-border">
						<a href="<?= site_url('users/create_user')?>" class="btn btn-social btn-flat btn-success btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-plus"></i> Tambah Pengguna Baru</a>
						
					</div>

                     <div class="panel-body no-padding">
                        <table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
                           <thead>
                              <tr>
                                 <th>Username</th>
                                 <th>Email</th>
                                 <?php if ($this->session->userdata("group_id") == 1): ?>
                                 <th>Group / Hak Akses</th>
                                 <th>Status</th>
                                 <th>Ubah</th>
                                 <th>Hapus</th>
                              
                                 <?php endif ?>
                              </tr>
                           </thead>
                           <tbody>
                              <?php foreach ($users as $user):?>
                              <tr>
                                 <td>
                                    <?php echo htmlspecialchars($user->username,ENT_QUOTES,'UTF-8');?>
                                 </td>
                                 <td>
                                    <?php echo htmlspecialchars($user->email,ENT_QUOTES,'UTF-8');?>
                                 </td>
                                 <?php if ($this->session->userdata("group_id") == 1): ?>
                                 <td>
                                    <?php foreach ($user->groups as $group):?>
                                    <?php echo anchor("User_groups/edit_group/".$group->id,htmlspecialchars($group->name,ENT_QUOTES,'UTF-8'),array('i','class'=>"btn bg-red btn-flat btn-sm", '/i','title'=>"Ubah Grup")) ;?><br />
                                    <?php endforeach?>
                                 </td>
                                 
                                 <td>
                                    <?php echo ($user->active) ? anchor("auth/deactivate/".$user->id, lang('index_active_link'),array('class'=>"btn bg-navy btn-flat btn-sm",'title'=>"Non Aktifkan Pengguna")) : anchor("users/activate/". $user->id, lang('index_inactive_link'),array('class'=>"btn bg-navy btn-flat btn-sm",'title'=>"Aktifkan Pengguna"));?>
                                    </a>
                                 </td>
                                 <td>
                                    <a href="<?= site_url('users/edit_user')?>/<?= $user->id ?>" class="btn bg-orange btn-flat btn-sm" title="Ubah"><i class="fa fa-edit"></i></a>
                                    </a>
                                 </td>
                                 <td>
                                    <a href="<?= site_url('Auth/delete_user')?>/<?= $user->id ?>" class="btn bg-maroon btn-flat btn-sm" title="Hapus"><i class="fa fa-trash-o"></i></a>
                                    
                                 </td>
                                 
                                 <?php endif ?>	
                              </tr>
                              <?php endforeach;?>
                           </tbody>
                        </table>
                     </div>
                     <div class="panel-footer"></div>
                  </div>
               </div>
            </div>
         </section>
      </div>
   </div>
</div>

