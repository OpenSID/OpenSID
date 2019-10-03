<div class="content-wrapper">
<div class="static-content">
   <div class="page-content">
     <section class="content-header">
		<h1>Manajemen Group</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li class="active">Manajemen Group</li>
		</ol>
	</section>
      <section class="content" id="maincontent">
         <div data-widget-group="group1">
            <div class="row">
               <div class="col-md-12">
                  <div class="panel panel-default">
                     <div class="box box-info">
					<div class="box-header with-border">
						<a href="<?= site_url('user_groups/create_group')?>" class="btn btn-social btn-flat btn-success btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-plus"></i> Tambah Group Baru</a>
   				</div>

                   <div class="panel-body no-padding">
                       <table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
                           <thead>
                              <tr>
                                 <th>Nama Group</th>
                                 <th>Keterangan</th>
                                 <th>Atur Group / Hak Akses</th>
                                 <th>Hapus Group</th>
                              </tr>
                           </thead>
                           <tbody>
                              <?php foreach ($groups as $group):?>
                              <tr>
                                 <td>
                                    <?php echo htmlspecialchars($group->name,ENT_QUOTES,'UTF-8');?>
                                 </td>
                                 <td>
                                    <?php echo htmlspecialchars($group->description,ENT_QUOTES,'UTF-8');?>
                                 </td>
                                 <td>
                                    <a href="<?= site_url('User_groups/edit_group')?>/<?= $group->id ?>">
                                    <button class="btn bg-orange btn-flat btn-sm" title="Atur Hak Akses"><i class="fa fa-edit">&nbsp;</i> Atur Hak Akses</button>
                                    </a>
                                 </td>
                                 <td>
                                    <a href="<?= site_url('Auth/delete_group')?>/<?= $group->id ?>"> 
                                    <button class="btn bg-maroon btn-flat btn-sm" title="Hapus Group">
                                    <i class="fa fa-trash-o">&nbsp;</i> Hapus Group</button>
                                    </a> 
                                 </td>
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

